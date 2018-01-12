<?php

namespace FishBundle\Neat;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use FishBundle\Entity\Fish;
use FishBundle\Entity\FishRepository;
use Gheb\NeatBundle\HookInterface;

/**
 * Class GetFitnessHook
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class GetFitnessHook implements HookInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * GetFitnessHook constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return int
     * @throws NonUniqueResultException
     */
    public function __invoke()
    {
        /** @var FishRepository $repo */
        /* @var Fish $fish */
        $repo = $this->em->getRepository('FishBundle:Fish');
        $fish = $repo->findLastAliveFish();

        return $fish->getLifeTick();
    }
}
