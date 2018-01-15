<?php

namespace TamagotchiBundle\Neat;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use TamagotchiBundle\Entity\Tamagotchi;
use TamagotchiBundle\Entity\TamagotchiRepository;
use Gheb\NeatBundle\HookInterface;

/**
 * Class BeforeInitHook
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class BeforeInitHook implements HookInterface
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

    /**
     * @throws NonUniqueResultException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     */
    public function __invoke()
    {
        /** @var TamagotchiRepository $repo */
        /* @var Tamagotchi $tamagotchi */
        $repo = $this->em->getRepository('TamagotchiBundle:Tamagotchi');
        $tamagotchi = $repo->findAliveTamagotchi();

        if (null === $tamagotchi) {
            $tamagotchi = new Tamagotchi();
            $this->em->persist($tamagotchi);
            $this->em->flush();
        }
    }
}
