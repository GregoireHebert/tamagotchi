<?php

namespace Gheb\Fish\NeatBundle\Genomes;

use Doctrine\Common\Collections\ArrayCollection;

class Genome
{
    /**
     * @var int
     */
    public $fitness = 0;

    /**
     * @var ArrayCollection
     */
    public $genes;

    /**
     * @var int
     */
    public $maxNeuron = 0;

    /**
     * @var array
     */
    public $mutationRates = array();

    /**
     * @var array
     */
    public $network = array();

    /**
     * @var int
     */
    public $globalRank;

    /**
     * @var Specie
     */
    public $specie;

    public function __construct()
    {
        $this->genes = new ArrayCollection();

        $this->mutationRates["connections"] = 0.25;
        $this->mutationRates["link"] = 2.0;
        $this->mutationRates["bias"] = 0.40;
        $this->mutationRates["node"] = 0.50;
        $this->mutationRates["enable"] = 0.2;
        $this->mutationRates["disable"] = 0.4;
        $this->mutationRates["step"] = 0.1;
    }

    /**
     * @return int
     */
    public function getGlobalRank()
    {
        return $this->globalRank;
    }

    /**
     * @param int $globalRank
     */
    public function setGlobalRank($globalRank)
    {
        $this->globalRank = $globalRank;
    }

    /**
     * @param Gene $gene
     */
    public function addGene(Gene $gene)
    {
        $this->genes->add($gene);
        $gene->setGenome($this);
    }

    /**
     * @return int
     */
    public function getFitness()
    {
        return $this->fitness;
    }

    /**
     * @return ArrayCollection
     */
    public function getGenes()
    {
        return $this->genes;
    }

    /**
     * @return int
     */
    public function getMaxNeuron()
    {
        return $this->maxNeuron;
    }

    /**
     * @return array
     */
    public function getMutationRates()
    {
        return $this->mutationRates;
    }

    /**
     * @return mixed
     */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * @return Specie
     */
    public function getSpecie()
    {
        return $this->specie;
    }

    public function removeGene(Gene $gene)
    {
        $this->genes->removeElement($gene);
    }

    /**
     * @param int $fitness
     */
    public function setFitness($fitness)
    {
        $this->fitness = $fitness;
    }

    /**
     * @param ArrayCollection $genes
     */
    public function setGenes($genes)
    {
        $this->genes = $genes;
    }

    /**
     * @param int $maxNeuron
     */
    public function setMaxNeuron($maxNeuron)
    {
        $this->maxNeuron = $maxNeuron;
    }

    /**
     * @param array $mutationRates
     */
    public function setMutationRates($mutationRates)
    {
        $this->mutationRates = $mutationRates;
    }

    /**
     * @param mixed $network
     */
    public function setNetwork($network)
    {
        $this->network = $network;
    }

    /**
     * @param Specie $specie
     */
    public function setSpecie($specie)
    {
        $this->specie = $specie;
    }
}
