<?php

namespace TamagotchiBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use TamagotchiBundle\Entity\Tamagotchi;
use TamagotchiBundle\Entity\TamagotchiRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\output\OutputInterface;

/**
 * Class GiveBirthCommand
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class GiveBirthCommand extends ContainerAwareCommand
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
            ->setName('tamagotchi:give:birth')
            ->setDescription('Give birth to a new tamagotchi, if there is none alive');
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
        $tamagotchi = $repo->findAliveTamagotchi();

        if (null === $tamagotchi) {
            $tamagotchi = new Tamagotchi();
            $this->em->persist($tamagotchi);
            $this->em->flush();
            $output->writeln('A new tamagotchi is born.');
        } else {
            $output->writeln('There is already a tamagotchi alive...');
        }
    }
}
