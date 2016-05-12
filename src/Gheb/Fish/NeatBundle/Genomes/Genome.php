<?php

namespace Gheb\Fish\NeatBundle\Genomes;

use Doctrine\Common\Collections\ArrayCollection;

class Genome
{
    /**
     * @var ArrayCollection
     */
    public $genes;

    /**
     * @var int
     */
    public $fitness = 0;

    /**
     * @var array
     */
    public $network = array();

    /**
     * @var int
     */
    public $maxNeuron = 0;

    /**
     * @var array
     */
    public $mutationRates = array();

    public function __construct()
    {
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
    public function getFitness()
    {
        return $this->fitness;
    }

    /**
     * @param int $fitness
     */
    public function setFitness($fitness)
    {
        $this->fitness = $fitness;
    }

    /**
     * @return mixed
     */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * @param mixed $network
     */
    public function setNetwork($network)
    {
        $this->network = $network;
    }

    /**
     * @return int
     */
    public function getMaxNeuron()
    {
        return $this->maxNeuron;
    }

    /**
     * @param int $maxNeuron
     */
    public function setMaxNeuron($maxNeuron)
    {
        $this->maxNeuron = $maxNeuron;
    }

    /**
     * @return array
     */
    public function getMutationRates()
    {
        return $this->mutationRates;
    }

    /**
     * @param array $mutationRates
     */
    public function setMutationRates($mutationRates)
    {
        $this->mutationRates = $mutationRates;
    }

    /**
     * @param Gene $gene
     */
    public function addGene(Gene $gene)
    {
        $this->genes->add($gene);
    }

    public function removeGene(Gene $gene)
    {
        $this->genes->removeElement($gene);
    }

    /**
     * @return ArrayCollection
     */
    public function getGenes()
    {
        return $this->genes;
    }

    /**
     * @param ArrayCollection $genes
     */
    public function setGenes($genes)
    {
        $this->genes = $genes;
    }
}
