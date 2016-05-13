<?php

namespace Gheb\Fish\NEATBundle\Genomes;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Species
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\NEATBundle\Genomes
 */
class Specie
{
    /**
     * @var int
     */
    public $averageFitness = 0;

    /**
     * @var ArrayCollection
     */
    public $genomes;

    /**
     * @var Pool
     */
    public $pool;

    /**
     * @var int
     */
    public $staleness = 0;

    /**
     * @var int
     */
    public $topFitness = 0;

    public function addGenome(Genome $genome)
    {
        $this->genomes->add($genome);
        $genome->setSpecie($this);
    }

    /**
     * @return int
     */
    public function getAverageFitness()
    {
        return $this->averageFitness;
    }

    /**
     * @return ArrayCollection
     */
    public function getGenomes()
    {
        return $this->genomes;
    }

    public function disjoint(Genome $g1, Genome $g2)
    {
        $disjointGenes = 0;

        $innovation1 = array();
        /** @var Gene $gene */
        foreach ($g1->getGenes() as $gene) {
            $innovation1[] = $gene->getInnovation();
        }

        $innovation2 = array();
        /** @var Gene $gene */
        foreach ($g2->getGenes() as $gene) {
            $innovation2[] = $gene->getInnovation();
        }

        foreach ($g1->getGenes() as $gene) {
            if (!in_array($gene->getInnovation(), $innovation2)) {
                $disjointGenes++;
            }
        }

        foreach ($g2->getGenes() as $gene) {
            if (!in_array($gene->getInnovation(), $innovation1)) {
                $disjointGenes++;
            }
        }

        $max = max($g1->getGenes()->count(), $g2->getGenes()->count());
        return $disjointGenes / $max;
    }

    public function weight(Genome $g1, Genome $g2)
    {
        $innovation2 = array();
        /** @var Gene $gene */
        foreach ($g2->getGenes() as $gene) {
            $innovation2[$gene->getInnovation()] = $gene;
        }

        $sum = 0;
        $coincident = 0;

        foreach ($g1->getGenes() as $gene) {
            if (isset($innovation2[$gene->getInnovation()])) {
                $gene2 = $innovation2[$gene->getInnovation()];
                $sum += abs($gene->getWeight() - $gene2->getWeight());
                $coincident++;
            }
        }

        return $sum / $coincident;
    }

    /**
     * @return Pool
     */
    public function getPool()
    {
        return $this->pool;
    }

    /**
     * @return int
     */
    public function getStaleness()
    {
        return $this->staleness;
    }

    /**
     * @return int
     */
    public function getTopFitness()
    {
        return $this->topFitness;
    }

    public function removeGenome(Genome $genome)
    {
        $this->genomes->removeElement($genome);
    }

    /**
     * @param int $averageFitness
     */
    public function setAverageFitness($averageFitness)
    {
        $this->averageFitness = $averageFitness;
    }

    /**
     * @param ArrayCollection $genomes
     */
    public function setGenomes($genomes)
    {
        $this->genomes = $genomes;
    }

    /**
     * @param Pool $pool
     */
    public function setPool($pool)
    {
        $this->pool = $pool;
    }

    /**
     * @param int $staleness
     */
    public function setStaleness($staleness)
    {
        $this->staleness = $staleness;
    }

    /**
     * @param int $topFitness
     */
    public function setTopFitness($topFitness)
    {
        $this->topFitness = $topFitness;
    }
}
