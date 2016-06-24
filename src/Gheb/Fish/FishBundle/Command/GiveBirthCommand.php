<?php

namespace Gheb\Fish\FishBundle\Command;

use Doctrine\ORM\EntityManager;
use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\Fish\FishBundle\Entity\FishRepository;
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
            ->setName('fish:give:birth')
            ->setDescription('Give birth to a new fish, if there is none alive');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var FishRepository $repo */
        $repo = $this->em->getRepository('FishBundle:Fish');
        $fish = $repo->findAliveFish();

        if ($fish == null) {
            var_dump('giveBirthCommand');
            $fish = new Fish();
            $this->em->persist($fish);
            $this->em->flush();
            $output->writeln('A new fish is born.');
        } else {
            $output->writeln('There is already a fish alive...');
        }
    }
}
