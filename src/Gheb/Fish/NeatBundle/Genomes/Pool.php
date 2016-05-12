<?php

namespace Gheb\Fish\NeatBundle\Genomes;

use Doctrine\Common\Collections\ArrayCollection;
use Gheb\Fish\IOBundle\Inputs\InputsAggregator;
use Gheb\Fish\IOBundle\Outputs\OutputsAggregator;

/**
 * Class Pool regroups every species
 * @package Gheb\Fish\NeatBundle\Genomes
 */
class Pool
{
    /**
     * @var int
     */
    public $currentGenome = 1;
    /**
     * @var int
     */
    public $currentSpecies = 1;
    /**
     * @var int
     */
    public $generation = 0;
    /**
     * @var int
     */
    public $innovation = 0;

    /**
     * @var InputsAggregator
     */
    private $inputAggregator;

    /**
     * @var int
     */
    public $maxFitness = 0;

    /**
     * @var ArrayCollection
     */
    public $species;

    /**
     * Pool constructor.
     * @param OutputsAggregator $outputsAggregator
     * @param InputsAggregator $inputsAggregator
     */
    public function __construct(OutputsAggregator $outputsAggregator, InputsAggregator $inputsAggregator)
    {
        $this->innovation = $outputsAggregator->count();
        $this->inputAggregator = $inputsAggregator;
    }

    public function addSpecie(Specie $specie)
    {
        $this->species->add($specie);
        $specie->setPool($this);
    }

    public function createBasicGenome()
    {
        $genome = new Genome();
        $genome->setMaxNeuron($this->inputAggregator->count()+1);
        Mutation::mutate($genome);

        return $genome;
    }

    /**
     * @return int
     */
    public function getCurrentGenome()
    {
        return $this->currentGenome;
    }

    /**
     * @return int
     */
    public function getCurrentSpecies()
    {
        return $this->currentSpecies;
    }

    /**
     * @return int
     */
    public function getGeneration()
    {
        return $this->generation;
    }

    /**
     * @return int
     */
    public function getInnovation()
    {
        return $this->innovation;
    }

    /**
     * @return int
     */
    public function getMaxFitness()
    {
        return $this->maxFitness;
    }

    /**
     * @return ArrayCollection
     */
    public function getSpecies()
    {
        return $this->species;
    }

    public function newInnovation()
    {
        $this->innovation++;
        return $this->innovation;
    }

    public function removeSpecie(Specie $specie)
    {
        $this->species->removeElement($specie);
    }

    /**
     * @param int $currentGenome
     */
    public function setCurrentGenome($currentGenome)
    {
        $this->currentGenome = $currentGenome;
    }

    /**
     * @param int $currentSpecies
     */
    public function setCurrentSpecies($currentSpecies)
    {
        $this->currentSpecies = $currentSpecies;
    }

    /**
     * @param int $generation
     */
    public function setGeneration($generation)
    {
        $this->generation = $generation;
    }

    /**
     * @param int $innovation
     */
    public function setInnovation($innovation)
    {
        $this->innovation = $innovation;
    }

    /**
     * @param int $maxFitness
     */
    public function setMaxFitness($maxFitness)
    {
        $this->maxFitness = $maxFitness;
    }

    /**
     * @param ArrayCollection $species
     */
    public function setSpecies($species)
    {
        $this->species = $species;
    }
}