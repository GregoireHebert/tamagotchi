<?php

namespace Gheb\Fish\NeatBundle\Command;

use Doctrine\ORM\EntityManager;
use Gheb\Fish\FishBundle\Entity\FishRepository;
use Gheb\Fish\IOBundle\Inputs\AbstractInput;
use Gheb\Fish\IOBundle\Inputs\InputsAggregator;
use Gheb\Fish\NeatBundle\Aggregator;
use Gheb\Fish\NeatBundle\Genomes\Genome;
use Gheb\Fish\NeatBundle\Genomes\Mutation;
use Gheb\Fish\NEATBundle\Genomes\Specie;
use Gheb\Fish\NeatBundle\Manager\Manager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\output\OutputInterface;

/**
 * Class NeatCommand
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\NeatBundle\Command
 */
class NeatCommand extends ContainerAwareCommand
{
    /**
     * @var InputsAggregator
     */
    private $inputsAggregator;

    /**
     * @var Aggregator
     */
    private $outputsAggregator;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Mutation
     */
    private $mutation;

    /**
     * NeatCommand constructor.
     *
     * @param Aggregator $inputAggregator
     * @param Aggregator $outputAggregator
     * @param EntityManager $em
     * @param Mutation $mutation
     */
    public function __construct(Aggregator $inputsAggregator, Aggregator $outputsAggregator, EntityManager $em, Mutation $mutation)
    {
        $this->inputsAggregator = $inputsAggregator;
        $this->outputsAggregator = $outputsAggregator;
        $this->em = $em;
        $this->mutation = $mutation;

        parent::__construct();
    }

    /**
     * configure the command
     */
    protected function configure()
    {
        $this
            ->setName('fish:neat')
            ->setDescription('play neat');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = new Manager($this->em, $this->inputsAggregator, $this->outputsAggregator, $this->mutation);
        $manager->evaluateCurrent();

        $pool = $manager->getPool();

        /** @var Specie $specie */
        $specie = $pool->getSpecies()->offsetGet($pool->getCurrentSpecies());

        /** @var Genome $genome */
        $genome = $specie->getGenomes()->offsetGet($pool->getCurrentGenome());

        /** @var FishRepository $repo */
        $repo = $this->em->getRepository('FishBundle:Fish');
        $fish = $repo->findAliveFish();

        if ($fish == null) {
            $lastFish = $repo->findLastAliveFish();
            $fitness = $lastFish->getAge();
            $genome->setFitness($fitness);

            if ($fitness > $pool->getMaxFitness()) {
                $pool->setMaxFitness($fitness);
                $this->em->flush();
            }

            $pool->setCurrentSpecies(1);
            $pool->setCurrentGenome(1);
            while ($manager->fitnessAlreadyMeasured()) {
                $pool->nextGenome();
            }

            $output->writeln('New Life.');
        }





    }
}
