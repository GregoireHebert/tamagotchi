<?php

namespace FishBundle\Neat;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use FishBundle\Entity\Fish;
use FishBundle\Entity\FishRepository;
use FishBundle\Services\Life;
use FishBundle\Services\TimeObligation;
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
        /** @var FishRepository $repo */
        /* @var Fish $fish */
        $repo = $this->em->getRepository('FishBundle:Fish');
        $fish = $repo->findAliveFish();

        $this->time->applyEffect($fish);
        $this->life->applyEffect($fish);
        $this->em->flush();
    }
}
