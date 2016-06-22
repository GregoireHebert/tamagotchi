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
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);

        /** @var FishRepository $repo */
        $repo = $this->em->getRepository('FishBundle:Fish');

        /** @var Fish $fish */
        $fish = $repo->findAliveFish();
        while (true) {

            $pool = $manager->getPool();

            /** @var Specie $specie */
            $specie = $pool->getSpecies()->offsetGet($pool->getCurrentSpecies());

            /** @var Genome $genome */
            $genome = $specie->getGenomes()->offsetGet($pool->getCurrentGenome());

            if (!$fish instanceof Fish || $fish->getHealth() <= 0) {
                /** @var Fish $lastFish */
                $lastFish = $repo->findLastAliveFish();
                $fitness = $lastFish->getLifeTick();
                $genome->setFitness($fitness);

                if ($fitness > $pool->getMaxFitness()) {
                    $pool->setMaxFitness($fitness);
                }

                $pool->setCurrentSpecies(0);
                $pool->setCurrentGenome(0);

                while ($manager->fitnessAlreadyMeasured()) {
                    $pool->nextGenome();
                }
                $this->em->flush();

                $command = $this->getApplication()->find('fish:give:birth');

                $nullOutput = new NullOutput();
                $birthInput = new ArrayInput(
                    array(
                        'command' => 'fish:give:birth'
                    )
                );

                $command->run($birthInput, $nullOutput);
                /** @var Fish $fish */
                $fish = $repo->findAliveFish();
                $output->writeln('Best Fitness :'.$pool->getMaxFitness());
                $output->writeln('New Life.');

                $manager->initializeRun();
            }

            try {
                $manager->evaluateCurrent();
            } catch (\Exception $e) {
                $command = $this->getApplication()->find('fish:give:birth');
                /** @var Fish $fish */
                $fish = $repo->findAliveFish();

                $nullOutput = new NullOutput();
                $birthInput = new ArrayInput(
                    array(
                        'command' => 'fish:give:birth'
                    )
                );

                $command->run($birthInput, $nullOutput);
                $output->writeln('Best Fitness :'.$pool->getMaxFitness());
                $output->writeln('New Life.');
            }

            $command = $this->getApplication()->find('fish:time:apply');

            $nullOutput = new NullOutput();
            $timeInput = new ArrayInput(
                array(
                    'command' => 'fish:time:apply'
                )
            );

            $command->run($timeInput, $nullOutput);

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
