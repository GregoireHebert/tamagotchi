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
