<?php

namespace FishBundle\Command;

use Doctrine\ORM\EntityManager;
use FishBundle\Entity\Fish;
use FishBundle\Entity\FishRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\output\OutputInterface;

/**
 * Class KillCommand
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class KillCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Give birth constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    /**
     * configure the command
     */
    protected function configure()
    {
        $this
            ->setName('fish:kill')
            ->setDescription('Kill the last fish alive');
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

        if (null === $fish) {
            $output->writeln('There is no fish alive...');
        } else {
            $fish->setHealth(0);
            $this->em->flush();
            $output->writeln('The fish is dead.');
        }
    }
}
