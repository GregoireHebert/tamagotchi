<?php

namespace TamagotchiBundle\Neat;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use TamagotchiBundle\Entity\Tamagotchi;
use TamagotchiBundle\Entity\TamagotchiRepository;
use Gheb\NeatBundle\HookInterface;

/**
 * Class NextGenomeCriteriaHook
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class NextGenomeCriteriaHook implements HookInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Tamagotchi
     */
    protected $tamagotchi;

    /**
     * NextGenomeCriteriaHook constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return bool
     * @throws NonUniqueResultException
     */
    public function __invoke()
    {
        if ($this->tamagotchi === null || $this->tamagotchi->getHealth() <= 0) {
            /** @var TamagotchiRepository $repo */
            /* @var Tamagotchi $tamagotchi */
            $repo       = $this->em->getRepository('TamagotchiBundle:Tamagotchi');
            $this->tamagotchi = $repo->findAliveTamagotchi();
        }

        return $this->tamagotchi === null || $this->tamagotchi->getHealth() <= 0;
    }
}
