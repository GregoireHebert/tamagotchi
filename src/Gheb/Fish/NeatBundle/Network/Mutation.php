<?php
/**
 * Created by PhpStorm.
 * User: gregoire
 * Date: 09/05/2016
 * Time: 22:40
 */

namespace Gheb\Fish\NeatBundle\Network;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Gheb\Fish\IOBundle\Inputs\InputsAggregator;
use Gheb\Fish\IOBundle\Outputs\OutputsAggregator;
use Gheb\Fish\NeatBundle\Network\Network;

class Mutation
{
    const PERTURB_CHANCE = 0.90;

    /**
     * @var EntityManager
     */
    private $em;

    private $pool;

    /**
     * Manager constructor.
     * @param EntityManager $em
     * @param InputsAggregator $inputsAggregator
     * @param OutputsAggregator $outputsAggregator
     */
    public function __construct(EntityManager $em, InputsAggregator $inputsAggregator, OutputsAggregator $outputsAggregator)
    {
        $this->em = $em;
        $this->inputsAggregator = $inputsAggregator;
        $this->outputsAggregator = $outputsAggregator;
    }

    /**
     * Clone an entity and persist it
     * @param $entity
     *
     * @return mixed
     */
    public function cloneEntity($entity)
    {
        $this->em->clear($entity);
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    /**
     * Create a new genome based on two genomes
     * @param Genome $g1
     * @param Genome $g2
     *
     * @return Genome
     */
    public function crossOver(Genome $g1, Genome $g2)
    {
        if ($g2->getFitness() > $g1->getFitness()) {
            $temp = $g1;
            $g1 = $g2;
            $g2 = $temp;
        }

        $child = new Genome();

        $newInnovation = array();
        /** @var Gene $gene */
        foreach ($g2->getGenes() as $gene) {
            $newInnovation[$gene->getInnovation()] = $gene;
        }

        // add to the genome each gene contained in the first genome.
        // If the second genome has also an enabled gene for the same innovation number,
        // the gene added is randomly chosen between the two.
        foreach ($g1->getGenes() as $gene) {
            /** @var Gene $gene2 */
            $gene2 = isset($newInnovation[$gene->getInnovation()]) ? $newInnovation[$gene->getInnovation()] : null;
            if ($gene2 != null && mt_rand(1,2) == 1 && $gene2->isEnabled()) {
                $child->addGene($this->cloneEntity($gene2));
            } else {
                $child->addGene($this->cloneEntity($gene));
            }
        }

        $child->setMaxNeuron(max($g1->getMaxNeuron(), $g2->getMaxNeuron()));
        $child->setMutationRates($g1->getMutationRates());

        return $child;
    }

    /**
     * Transform an enable gene to a disable one
     * @param Genome $genome
     * @param bool $enabled //chances are to change enabled one to disabled when true, and to change disabled one to enabled when false
     */
    public function enableDisableMutate(Genome $genome, $enabled = true)
    {
        if ($genome->getGenes()->count() == 0) return;

        $candidates = new ArrayCollection();

        /** @var Gene $gene */
        foreach ($genome->getGenes() as $gene) {
            if ($gene->isEnabled() == !$enabled) {
                $candidates->add($gene);
            }
        }

        if ($candidates->count() == 0) return;

        $gene = $candidates->get(mt_rand(1,$candidates->count())-1);
        $gene->setEnabled(!$gene->isEnabled());
        $this->em->flush();
    }

    /**
     * Return a random neuron. A neuron can be an input, an output or an hidden node
     * @param      $genes
     * @param bool $nonInput
     *
     * @return mixed
     */
    public function getRandomNeuron($genes, $nonInput = false)
    {
        $neurons = array();
        if (!$nonInput) {
            for ($i = 0; $i < $this->inputsAggregator->count(); $i++) {
                $neurons[$i] = $i;
            }
        }

        for ($j = 0; $j < $this->outputsAggregator->count(); $j++) {
            $neurons[Network::MAX_NODES+$j] = Network::MAX_NODES+$j;
        }

        /** @var Gene $gene */
        foreach ($genes as $gene) {
            if (!$nonInput || $gene->getInto() > $this->inputsAggregator->count()) {
                $neurons[$gene->getInto()] = $gene->getInto();
            }

            if (!$nonInput || $gene->getOut() > $this->inputsAggregator->count()) {
                $neurons[$gene->getOut()] = $gene->getOut();
            }
        }

        $r = mt_rand(1,count($neurons)) -1;
        $n = array_values($neurons);

        return $n[$r];
    }

    /**
     * Has a chance to create a new gene in between two random in and out genes
     * or a chance to create a new link from a bias to the output
     * @param Genome $genome
     * @param        $forceBias
     */
    public function linkMutate(Genome $genome, $forceBias)
    {
        $rn1 = $this->getRandomNeuron($genome->getGenes(), false);
        $rn2 = $this->getRandomNeuron($genome->getGenes(), true);

        // both are inputs, nothing to do
        if ($rn1 <= $this->inputsAggregator->count() && $rn2 <= $this->inputsAggregator->count()) {
            return;
        }

        // set as rn1 is an input and rn2 a nonInput
        if ($rn2 <= $this->inputsAggregator->count()) {
            $tmp = $rn1;
            $rn1 = $rn2;
            $rn2 = $tmp;
        }

        $newLink = new Gene();
        $newLink->setInto($rn1);
        $newLink->setOut($rn2);

        if ($forceBias) {
            $newLink->setInto($this->inputsAggregator->count());
        }

        $exists = $genome->getGenes()->filter(function ($gene) use ($newLink){
            /** @var Gene $gene */
            return $gene->getInto() == $newLink->getInto() && $gene->getOut() == $newLink->getOut();
        });

        if ($exists->count() > 0) {
            return;
        }

        $pool = $this->pool instanceof Pool ? $this->pool : $genome->getSpecie()->getPool();
        $newLink->setInnovation($pool->newInnovation());
        $newLink->setWeight(lcg_value()*4-2);

        $genome->addGene($newLink);
    }

    /**
     * Applies a mutation upon a genome
     *
     * @param Genome $genome
     * @param Pool $pool pool to innovate, when the genome hasn't been attached to it yet
     */
    public function mutate(Genome $genome, $pool = null)
    {
        $this->pool = $pool;
        $rates = $genome->mutationRates;

        // has a chance to reduce the mutation rate or rise it up
        foreach ($rates as $mutation=>$rate) {
            if (mt_rand(1,2) == 1) {
                $genome->mutationRates[$mutation] = 0.95*$rate;
            } else {
                $genome->mutationRates[$mutation] = 1.05263*$rate;
            }
        }

        // has a chance to create a new link in between 2 input and output nodes
        $linkRate = $genome->mutationRates['link'];
        while ($linkRate > 0) {
            if (lcg_value() < $linkRate) {
                $this->linkMutate($genome, false);
            }

            $linkRate = $linkRate-1;
        }

        // has a chance to create a new link in between a bias node and an output nodes
        $biasRate = $genome->mutationRates['bias'];
        while ($biasRate > 0) {
            if (lcg_value() < $biasRate) {
                $this->linkMutate($genome, true);
            }

            $biasRate = $biasRate-1;
        }

        // has a chance to split a link in adding a new node in between
        $nodeRate = $genome->mutationRates['node'];
        while ($nodeRate > 0) {
            if (lcg_value() < $nodeRate) {
                $this->nodeMutate($genome);
            }

            $nodeRate = $nodeRate-1;
        }

        // has a chance to enable a disabled gene
        $enableRate = $genome->mutationRates['enable'];
        while ($enableRate > 0) {
            if (lcg_value() < $enableRate) {
                $this->enableDisableMutate($genome);
            }

            $enableRate = $enableRate-1;
        }

        // has a chance to disable an enabled gene
        $disableRate = $genome->mutationRates['disable'];
        while ($disableRate > 0) {
            if (lcg_value() < $disableRate) {
                $this->enableDisableMutate($genome, false);
            }

            $disableRate = $disableRate-1;
        }
    }

    /**
     * Adds a new node in between two existing nodes and disable the initial node in order to
     * get from A--C to A--B--C making the weight between A and B to 1.0
     * This new node is here to break the linearity in the network and expand the structure that will may evolve for speciation later.
     *
     * @param Genome $genome
     */
    public function nodeMutate(Genome $genome)
    {
        if ($genome->getGenes()->count() == 0) return;

        $genome->setMaxNeuron($genome->getMaxNeuron()+1);

        /** @var Gene $gene */
        $gene = $genome->getGenes()->get(mt_rand(1, $genome->getGenes()->count())-1);
        if ($gene->isEnabled() == false) return;

        $gene->setEnabled(false);

        $pool = $this->pool instanceof Pool ? $this->pool : $gene->getGenome()->getSpecie()->getPool();
        $clone = clone $gene;

        $clone->setOut($genome->getMaxNeuron());
        $clone->setWeight(1.0);
        $clone->setInnovation($pool->newInnovation());
        $clone->setEnabled(true);

        $genome->addGene($clone);

        $clone2 = clone $gene;

        $clone2->setInto($genome->getMaxNeuron());
        $clone2->setInnovation($pool->newInnovation());
        $clone2->setEnabled(true);

        $genome->addGene($clone2);

        $this->em->flush();
    }
}
