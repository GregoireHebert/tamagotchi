<?php

namespace Gheb\Fish\NeatBundle\Command;

use Doctrine\ORM\EntityManager;
use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\Fish\FishBundle\Entity\FishRepository;
use Gheb\Fish\IOBundle\Inputs\InputsAggregator;
use Gheb\Fish\NeatBundle\Aggregator;
use Gheb\Fish\NeatBundle\Network\Genome;
use Gheb\Fish\NeatBundle\Network\Mutation;
use Gheb\Fish\NEATBundle\Network\Specie;
use Gheb\Fish\NeatBundle\Manager\Manager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
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
        /** @var FishRepository $repo */
        $repo = $this->em->getRepository('FishBundle:Fish');

        while (true) {
            /** @var Fish $fish */
            $fish = $repo->findAliveFish();

            $pool = $manager->getPool();

            /** @var Specie $specie */
            $specie = $pool->getSpecies()->offsetGet($pool->getCurrentSpecies());

            /** @var Genome $genome */
            $genome = $specie->getGenomes()->offsetGet($pool->getCurrentGenome());

            if ($fish == null) {
                /** @var Fish $lastFish */
                $lastFish = $repo->findLastAliveFish();
                $fitness = $lastFish->getLifeTick();
                $genome->setFitness($fitness);

                if ($fitness > $pool->getMaxFitness()) {
                    $pool->setMaxFitness($fitness);
                    $this->em->flush();
                }

                $pool->setCurrentSpecies(0);
                $pool->setCurrentGenome(0);

                while ($manager->fitnessAlreadyMeasured()) {
                    $pool->nextGenome();
                }

                $command = $this->getApplication()->find('fish:give:birth');

                $nullOutput = new NullOutput();
                $birthInput = new ArrayInput(
                    array(
                        'command' => 'fish:give:birth'
                    )
                );

                $command->run($birthInput, $nullOutput);
                $fish = $repo->findAliveFish();
                $output->writeln('New Life.');

                $manager->initializeRun();
            }

            $manager->evaluateCurrent();

            $this->em->flush();

            $command = $this->getApplication()->find('fish:time:apply');

            $nullOutput = new NullOutput();
            $timeInput = new ArrayInput(
                array(
                    'command' => 'fish:time:apply'
                )
            );

            $command->run($timeInput, $nullOutput);

            if ($fish->getLifeTick() % 5 == 0) {
                $command = $this->getApplication()->find('fish:life:apply');

                $nullOutput = new NullOutput();
                $timeInput = new ArrayInput(
                    array(
                        'command' => 'fish:life:apply'
                    )
                );

                $command->run($timeInput, $nullOutput);
            }
        }
    }
}
