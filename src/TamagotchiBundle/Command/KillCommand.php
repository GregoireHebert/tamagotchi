<?php

namespace TamagotchiBundle\Command;

use Doctrine\ORM\EntityManager;
use TamagotchiBundle\Entity\Tamagotchi;
use TamagotchiBundle\Entity\TamagotchiRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\output\OutputInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;

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
            ->setName('tamagotchi:kill')
            ->setDescription('Kill the last tamagotchi alive');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws NonUniqueResultException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var TamagotchiRepository $repo */
        $repo = $this->em->getRepository('TamagotchiBundle:Tamagotchi');
        /** @var Tamagotchi $tamagotchi */
        $tamagotchi = $repo->findAliveTamagotchi();

        if (null === $tamagotchi) {
            $output->writeln('There is no tamagotchi alive...');
        } else {
            $tamagotchi->setHealth(0);
            $this->em->flush();
            $output->writeln('The tamagotchi is dead.');
        }
    }
}
