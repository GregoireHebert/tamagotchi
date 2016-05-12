<?php
/**
 * Created by PhpStorm.
 * User: gregoire
 * Date: 09/05/2016
 * Time: 22:40
 */

namespace Gheb\Fish\NeatBundle\Genomes;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class Mutation
{
    const PERTURB_CHANCE = 0.90;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Manager constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function cloneEntity($entity)
    {
        $this->em->clear($entity);
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    /**
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

        foreach ($g1->getGenes() as $gene) {
            /** @var Gene $gene2 */
            $gene2 = isset($newInnovation[$gene->getInnovation()]) ? $newInnovation[$gene->getInnovation()] : null;
            if ($gene != null && rand(1,2) == 1 && $gene2->isEnabled()) {
                $child->addGene($this->cloneEntity($gene2));
            } else {
                $child->addGene($this->cloneEntity($gene));
            }
        }

        $child->setMaxNeuron(max($g1->getMaxNeuron(), $g2->getMaxNeuron()));
        $child->setMutationRates($g1->getMutationRates());

        $this->em->persist($child);
        $this->em->flush();
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

        $gene = $candidates->get(rand(0,$candidates->count()));
        $gene->setEnabled(!$gene->isEnabled());
        $this->em->flush();
    }

    public static function mutate(Genome $genome)
    {
        $rates = $genome->mutationRates;

        foreach ($rates as $mutation=>$rate) {
            if (rand(1,2) == 1) {
                $genome->mutationRates[$mutation] = 0.95*$rate;
            } else {
                $genome->mutationRates[$mutation] = 1.05263*$rate;
            }
        }

        if (rand(0,1) < $genome->mutationRates['connections']) {
            self::pointMutate($genome);
        }

        $linkRate = $genome->mutationRates['link'];
        while ($linkRate > 0) {
            if (rand(0,1) < $linkRate) {
                self::linkMutate($genome);
            }

            $linkRate = $linkRate-1;
        }

        $biasRate = $genome->mutationRates['bias'];
        while ($linkRate > 0) {
            if (rand(0,1) < $biasRate) {
                self::linkMutate($genome, true);
            }

            $biasRate = $biasRate-1;
        }

        $nodeRate = $genome->mutationRates['node'];
        while ($nodeRate > 0) {
            if (rand(0,1) < $nodeRate) {
                self::nodeMutate($genome);
            }

            $nodeRate = $nodeRate-1;
        }

        $enableRate = $genome->mutationRates['enable'];
        while ($enableRate > 0) {
            if (rand(0,1) < $enableRate) {
                self::enableDisableMutate($genome);
            }

            $enableRate = $enableRate-1;
        }

        $disableRate = $genome->mutationRates['disable'];
        while ($disableRate > 0) {
            if (rand(0,1) < $disableRate) {
                self::enableDisableMutate($genome, false);
            }

            $disableRate = $disableRate-1;
        }
    }

    public function nodeMutate(Genome $genome)
    {
        if ($genome->getGenes()->count() == 0) return;

        $genome->setMaxNeuron($genome->getMaxNeuron()+1);

        /** @var Gene $gene */
        $gene = $genome->getGenes()->get(rand(0, $genome->getGenes()->count()));
        if ($gene->isEnabled() == false) return;

        $gene->setEnabled(false);
        $clone = clone $gene;

        $clone->setOut($genome->getMaxNeuron());
        $clone->setWeight(1.0);
        $clone->setInnovation($clone->getGenome()->getSpecie()->getPool()->newInnovation());
        $clone->setEnabled(true);

        $genome->addGene($clone);

        $clone2 = clone $gene;

        $clone2->setOut($genome->getMaxNeuron());
        $clone2->setWeight(1.0);
        $clone2->setInnovation($clone->getGenome()->getSpecie()->getPool()->newInnovation());
        $clone2->setEnabled(true);

        $genome->addGene($clone2);

        $this->em->flush();
    }
}
