<?php
/**
 * Created by PhpStorm.
 * User: gregoire
 * Date: 09/05/2016
 * Time: 22:40
 */

namespace Gheb\Fish\NeatBundle\Genomes;


use Doctrine\Common\Collections\ArrayCollection;

class Mutation
{
    const PERTURB_CHANCE = 0.90;

    public static function mutate(Genome &$genome)
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

    /**
     * Transform an enable gene to a disable one
     * @param Genome $genome
     * @param bool $enabled //chances are to change enabled one to disabled when true, and to change disabled one to enabled when false
     */
    public function enableDisableMutate(Genome &$genome, $enabled = true)
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
    }

    public function nodeMutate(Genome &$genome)
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
        $clone->setInnovation();
        $clone->setEnabled(true);

        $genome->addGene($clone);

        $clone2 = clone $gene;

        $clone2->setOut($genome->getMaxNeuron());
        $clone2->setWeight(1.0);
        $clone2->setInnovation();
        $clone2->setEnabled(true);

        $genome->addGene($clone2);
    }

    public function linkMutate(Genome &$genome, $forceBias = false)
    {
        // todo link mutate
    }

    /**
     * @param Genome $genome
     */
    public function pointMutate(Genome &$genome)
    {
        if ($genome->getGenes()->count() == 0) return;

        $stepRate = $genome->mutationRates['step'];

        /** @var Gene $gene */
        foreach ($genome->getGenes() as $gene) {
            if (rand(0,1) < self::PERTURB_CHANCE) {
                $newWeight = $gene->getWeight() + rand(0,1) * $stepRate * 2 - $stepRate;
                $gene->setWeight($newWeight);
            } else {
                $gene->setWeight(rand(0,1) * 4 - 2);
            }
        }
    }
}