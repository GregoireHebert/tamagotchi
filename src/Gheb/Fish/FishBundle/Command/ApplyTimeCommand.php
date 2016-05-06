<?php

namespace Gheb\Fish\FishBundle\Command;

use Doctrine\ORM\EntityManager;
use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\Fish\FishBundle\Entity\FishRepository;
use Gheb\Fish\FishBundle\Services\TimeObligation;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\output\OutputInterface;

/**
 * Class ApplyTimeCommand
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\Command
 */
class ApplyTimeCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var TimeObligation
     */
    private $time;

    /**
     * Give birth constructor.
     *
     * @param EntityManager  $em
     * @param TimeObligation $time
     */
    public function __construct(EntityManager $em, TimeObligation $time)
    {
        $this->em = $em;
        $this->time = $time;
        parent::__construct();
    }

    /**
     * configure the command
     */
    protected function configure()
    {
        $this
            ->setName('fish:time:apply')
            ->setDescription('Apply the effect of life upon the living fish');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var FishRepository $repo */
        $repo = $this->em->getRepository('FishBundle:Fish');
        /** @var Fish $fish */
        $fish = $repo->findAliveFish();

        if ($fish == null) {
            $output->writeln('There is no fish alive...');
        } else {
            $this->time->applyEffect($fish);
            $this->em->flush();
            $output->writeln('Time has made it\'s job.');
        }
    }
}
