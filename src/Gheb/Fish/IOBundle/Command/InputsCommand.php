<?php

namespace Gheb\Fish\IOBundle\Command;

use Gheb\Fish\IOBundle\Inputs\AbstractInput;
use Gheb\Fish\IOBundle\Inputs\InputsAggregator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\output\OutputInterface;

/**
 * Class InputsCommand
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Command
 */
class InputsCommand extends ContainerAwareCommand
{
    /**
     * @var InputsAggregator
     */
    private $inputsAggregator;

    /**
     * InputsCommand constructor.
     *
     * @param InputsAggregator $aggregator
     */
    public function __construct(InputsAggregator $aggregator)
    {
        $this->inputsAggregator = $aggregator;
        parent::__construct();
    }

    /**
     * configure the command
     */
    protected function configure()
    {
        $this
            ->setName('fish:inputs')
            ->setDescription('get an Input value')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Which Input to trigger'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $aggregated = $this->inputsAggregator->getAggregated($name);
        if ($aggregated instanceof AbstractInput) {
            try {
                $output->writeln($aggregated->getValue());
            } catch (\Exception $e) {
                $output->writeln($e->getMessage());
            }
        } else {
            $output->writeln('This input does not exists');
        }
    }
}
