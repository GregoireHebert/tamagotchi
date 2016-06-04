<?php

namespace Gheb\Fish\NeatBundle\Manager;

use Doctrine\ORM\EntityManager;
use Gheb\Fish\IOBundle\Inputs\InputsAggregator;
use Gheb\Fish\IOBundle\Outputs\AbstractOutput;
use Gheb\Fish\IOBundle\Outputs\OutputsAggregator;
use Gheb\Fish\NeatBundle\Aggregator;
use Gheb\Fish\NeatBundle\Network\Genome;
use Gheb\Fish\NeatBundle\Network\Mutation;
use Gheb\Fish\NeatBundle\Network\Pool;
use Gheb\Fish\NeatBundle\Network\Specie;
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
     * @var OutputsAggregator
     */
    private $outputsAggregator;

    /**
     * @var Mutation
     */
    private $mutation;

    /**
     * @var Pool
     */
    private $pool;

    /**
     * Manager constructor.
     * @param EntityManager $em
     * @param Aggregator $inputsAggregator
     * @param Aggregator $outputsAggregator
     * @param Mutation $mutation
     */
    public function __construct(
        EntityManager $em,
        Aggregator $inputsAggregator,
        Aggregator $outputsAggregator,
        Mutation $mutation
    ) {
        $this->em = $em;
        $this->inputsAggregator = $inputsAggregator;
        $this->outputsAggregator = $outputsAggregator;
        $this->mutation = $mutation;

        $repo = $this->em->getRepository('Gheb\Fish\NeatBundle\Network\Pool');
        $this->pool = $repo->findOneBy(array());

        if (!$this->pool instanceof Pool) {
            $this->initializePool();
        } else {
            $this->pool->setEm($em);
            $this->pool->setInputAggregator($inputsAggregator);
            $this->pool->setMutation($mutation);
        }
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
        $pool = new Pool($this->em, $this->outputsAggregator, $this->inputsAggregator, $this->mutation);
        $this->em->persist($pool);
        $this->em->flush();

        $repo = $this->em->getRepository('Gheb\Fish\NeatBundle\Network\Pool');
        $this->pool = $repo->findOneBy(array());

        $this->pool->setInnovation(1);
        for ($i = 0; $i < Pool::POPULATION; $i++) {
            $this->pool->addToSpecies($this->pool->createBasicGenome());
        }

        $this->initializeRun();
    }

    public function initializeRun()
    {
        /** @var Specie $specie */
        $specie = $this->pool->getSpecies()->offsetGet($this->pool->getCurrentSpecies());
        $genome = $specie->getGenomes()->offsetGet($this->pool->getCurrentGenome());


        Network::generateNetwork($genome, $this->outputsAggregator, $this->inputsAggregator);

        $this->evaluateCurrent();
    }

    public function evaluateCurrent()
    {
        /** @var Specie $specie */
        $specie = $this->pool->getSpecies()->offsetGet($this->pool->getCurrentSpecies());
        /** @var Genome $genome */
        $genome = $specie->getGenomes()->offsetGet($this->pool->getCurrentGenome());

        $inputs = $this->inputsAggregator->aggregate->toArray();
        $outputs = Network::evaluate($genome, $inputs, $this->outputsAggregator, $this->inputsAggregator);

        $this->applyOutputs($outputs);
    }

    public function applyOutputs($outputs)
    {
        /** @var AbstractOutput $output */
        foreach ($outputs as $output) {
            try {
                $output->apply();
            } catch (\Exception $e) {
                var_dump($e->getMessage());
                return;
            }
        }
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @return InputsAggregator
     */
    public function getInputsAggregator()
    {
        return $this->inputsAggregator;
    }

    /**
     * @param InputsAggregator $inputsAggregator
     */
    public function setInputsAggregator($inputsAggregator)
    {
        $this->inputsAggregator = $inputsAggregator;
    }

    /**
     * @return Mutation
     */
    public function getMutation()
    {
        return $this->mutation;
    }

    /**
     * @param Mutation $mutation
     */
    public function setMutation($mutation)
    {
        $this->mutation = $mutation;
    }

    /**
     * @return Pool
     */
    public function getPool()
    {
        return $this->pool;
    }

    /**
     * @param Pool $pool
     */
    public function setPool($pool)
    {
        $this->pool = $pool;
    }

    /**
     * @return OutputsAggregator
     */
    public function getOutputsAggregator()
    {
        return $this->outputsAggregator;
    }

    /**
     * @param OutputsAggregator $outputsAggregator
     */
    public function setOutputsAggregator($outputsAggregator)
    {
        $this->outputsAggregator = $outputsAggregator;
    }
}