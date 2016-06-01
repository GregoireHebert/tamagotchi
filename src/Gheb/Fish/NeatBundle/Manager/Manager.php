<?php

namespace Gheb\Fish\NeatBundle\Manager;


use Doctrine\ORM\EntityManager;
use Gheb\Fish\IOBundle\Inputs\InputsAggregator;
use Gheb\Fish\IOBundle\Outputs\AbstractOutput;
use Gheb\Fish\IOBundle\Outputs\OutputsAggregator;
use Gheb\Fish\NeatBundle\Genomes\Genome;
use Gheb\Fish\NeatBundle\Genomes\Mutation;
use Gheb\Fish\NeatBundle\Genomes\Pool;
use Gheb\Fish\NEATBundle\Genomes\Specie;
use Gheb\Fish\NeatBundle\Network\Network;

class Manager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var InputsAggregator
     */
    private $inputsAggregator;

    /**
     * @var Mutation
     */
    private $mutation;

    /**
     * @var Pool
     */
    private $pool;

    /**
     * @var Network
     */
    private $network;

    /**
     * Manager constructor.
     * @param EntityManager $em
     * @param InputsAggregator $inputsAggregator
     * @param OutputsAggregator $outputsAggregator
     * @param Mutation $mutation
     */
    public function __construct(EntityManager $em, InputsAggregator $inputsAggregator, OutputsAggregator $outputsAggregator, Mutation $mutation)
    {
        $this->em = $em;
        $this->inputsAggregator = $inputsAggregator;
        $this->outputsAggregator = $outputsAggregator;
        $this->mutation = $mutation;

        if (!$this->pool instanceof Pool) {
            $this->initializePool();
        }
    }

    /**
     * Duplicate an entity
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
     * Return either a genome fitness has been measured or not
     *
     * @return bool
     */
    public function fitnessAlreadyMeasured()
    {
        /** @var Specie $specie */
        $specie = $this->pool->getSpecies()->offsetGet($this->pool->getCurrentSpecies());

        /** @var Genome $genome */
        $genome = $specie->getGenomes()->offsetGet($this->pool->getCurrentGenome());

        return $genome->getFitness() != 0;
    }

    public function initializePool()
    {
        $this->pool = new Pool($this->em, $this->outputsAggregator, $this->inputsAggregator, $this->mutation);
        $this->pool->setInnovation(1);
        for ($i=0; $i < Pool::POPULATION; $i++) {
            $this->pool->addToSpecies($this->pool->createBasicGenome());
        }

        $this->initializeRun();
    }

    public function initializeRun()
    {
        /** @var Specie $specie */
        $specie = $this->pool->getSpecies()->offsetGet($this->pool->getCurrentSpecies());
        $genome = $specie->getGenomes()->offsetGet($this->pool->getCurrentGenome());
        $this->network = new Network($genome, $this->outputsAggregator, $this->inputsAggregator);

        $this->evaluateCurrent();
    }

    public function evaluateCurrent()
    {
        $specie = $this->pool->getSpecies()->offsetGet($this->pool->getCurrentSpecies());
        $genome = $specie->getGenomes()->offsetGet($this->pool->getCurrentGenome());

        $inputs = $this->inputsAggregator->aggregate->toArray();
        $outputs = $this->network->evaluate($inputs);

        $this->applyOutputs($outputs);
    }

    public function applyOutputs($outputs)
    {
        /** @var AbstractOutput $output */
        foreach ($outputs as $output) {
            $output->apply();
        }
    }
}