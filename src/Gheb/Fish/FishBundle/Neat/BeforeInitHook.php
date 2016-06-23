<?php

namespace Gheb\Fish\FishBundle\Neat;

use Doctrine\ORM\EntityManager;
use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\Fish\FishBundle\Entity\FishRepository;
use Gheb\NeatBundle\Command\Hook;

/**
 * Class BeforeInitHook
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\Neat
 */
class BeforeInitHook extends Hook
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * BeforeInitHook constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function hook()
    {
        /** @var FishRepository $repo */
        /** @var Fish $fish */
        $repo = $this->em->getRepository('FishBundle:Fish');
        $fish = $repo->findAliveFish();

        if (null === $fish) {
            $fish = new Fish();
            $this->em->persist($fish);
            $this->em->flush();
        }
    }
}
