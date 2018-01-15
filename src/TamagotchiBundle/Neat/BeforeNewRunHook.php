<?php

namespace TamagotchiBundle\Neat;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use TamagotchiBundle\Entity\Tamagotchi;
use Gheb\NeatBundle\HookInterface;

/**
 * Class BeforeNewRunHook
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class BeforeNewRunHook implements HookInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * BeforeNewRunHook constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     */
    public function __invoke()
    {
        $tamagotchi = new Tamagotchi();
        $this->em->persist($tamagotchi);
        $this->em->flush();
    }
}
