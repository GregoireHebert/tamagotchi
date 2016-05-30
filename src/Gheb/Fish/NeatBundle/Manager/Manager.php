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
    }

    public function cloneEntity($entity)
    {
        $this->em->clear($entity);
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    public function initializePool()
    {
        $pool = new Pool($this->em, $this->outputsAggregator, $this->inputsAggregator, $this->mutation);
        $pool->setInnovation(1);
        for ($i=0; $i < Pool::POPULATION; $i++) {
            $pool->addToSpecies($this->basicGenome());
        }

        $this->initializeRun($pool);
    }

    public function initializeRun(Pool $pool)
    {
        /** @var Specie $specie */
        $specie = $pool->getSpecies()->offsetGet($pool->getCurrentSpecies());
        $genome = $specie->getGenomes()->offsetGet($pool->getCurrentGenome());
        $network = new Network($genome, $this->outputsAggregator, $this->inputsAggregator);
    }

    public function evaluateCurrent(Network $network, Pool $pool)
    {
        $specie = $pool->getSpecies()->offsetGet($pool->getCurrentSpecies());
        $genome = $specie->getGenomes()->offsetGet($pool->getCurrentGenome());

        $inputs = $this->inputsAggregator->aggregate->toArray();
        $outputs = $network->evaluate($inputs);

        $this->applyOutputs($outputs);
    }

    public function applyOutputs($outputs)
    {
        /** @var AbstractOutput $output */
        foreach ($outputs as $output) {
            $output->apply();
        }
    }

    public function basicGenome()
    {
        $genome = new Genome();
        $genome->setMaxNeuron($this->inputsAggregator->count());
        $this->mutation->mutate($genome);

        return $genome;
    }
}