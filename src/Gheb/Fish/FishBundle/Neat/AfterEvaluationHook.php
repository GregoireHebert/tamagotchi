<?php

namespace Gheb\Fish\FishBundle\Neat;

use Doctrine\ORM\EntityManager;
use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\Fish\FishBundle\Entity\FishRepository;
use Gheb\Fish\FishBundle\Services\Life;
use Gheb\Fish\FishBundle\Services\TimeObligation;
use Gheb\NeatBundle\Hook;

/**
 * Class AfterEvaluationHook
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class AfterEvaluationHook implements Hook
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
