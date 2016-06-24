<?php

namespace Gheb\Fish\FishBundle\Command;

use Doctrine\ORM\EntityManager;
use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\Fish\FishBundle\Entity\FishRepository;
use Gheb\Fish\FishBundle\Services\Life;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\output\OutputInterface;

/**
 * Class ApplyLifeCommand
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class ApplyLifeCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Life
     */
    private $life;

    /**
     * Give birth constructor.
     *
     * @param EntityManager $em
     * @param Life          $life
     */
    public function __construct(EntityManager $em, Life $life)
    {
        $this->em   = $em;
        $this->life = $life;
        parent::__construct();
    }

    /**
     * configure the command
     */
    protected function configure()
    {
        $this
            ->setName('fish:life:apply')
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
            $this->life->applyEffect($fish);
            $this->em->flush();
            $output->writeln('Life has made it\'s job.');
        }
    }
}
