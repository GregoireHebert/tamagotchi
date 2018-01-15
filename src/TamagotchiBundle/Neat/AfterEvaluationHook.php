<?php

namespace TamagotchiBundle\Neat;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use TamagotchiBundle\Entity\Tamagotchi;
use TamagotchiBundle\Entity\TamagotchiRepository;
use TamagotchiBundle\Services\Life;
use TamagotchiBundle\Services\TimeObligation;
use Gheb\NeatBundle\HookInterface;

/**
 * Class AfterEvaluationHook
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class AfterEvaluationHook implements HookInterface
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
     * @var TimeObligation
     */
    private $time;

    /**
     * AfterEvaluationHook constructor.
     *
     * @param EntityManager  $em
     * @param TimeObligation $time
     * @param Life           $life
     */
    public function __construct(EntityManager $em, TimeObligation $time, Life $life)
    {
        $this->em   = $em;
        $this->time = $time;
        $this->life = $life;
    }

    /**
     * @throws NonUniqueResultException
     * @throws OptimisticLockException
     */
    public function __invoke()
    {
        /** @var TamagotchiRepository $repo */
        /* @var Tamagotchi $tamagotchi */
        $repo = $this->em->getRepository('TamagotchiBundle:Tamagotchi');
        $tamagotchi = $repo->findAliveTamagotchi();

        $this->life->applyEffect($tamagotchi);
        $this->time->applyEffect($tamagotchi);
        $this->em->flush();
    }
}
