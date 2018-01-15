<?php

namespace TamagotchiBundle\Neat;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use TamagotchiBundle\Entity\Tamagotchi;
use TamagotchiBundle\Entity\TamagotchiRepository;
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
        /** @var TamagotchiRepository $repo */
        /* @var Tamagotchi $tamagotchi */
        $repo = $this->em->getRepository('TamagotchiBundle:Tamagotchi');
        $tamagotchi = $repo->findLastAliveTamagotchi();

        return $tamagotchi->getLifeTick();
    }
}
