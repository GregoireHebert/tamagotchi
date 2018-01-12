<?php

namespace FishBundle\Neat;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use FishBundle\Entity\Fish;
use FishBundle\Entity\FishRepository;
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
        /** @var FishRepository $repo */
        /* @var Fish $fish */
        $repo = $this->em->getRepository('FishBundle:Fish');
        $fish = $repo->findAliveFish();

        if (null === $fish) {
            $fish = new Fish();
            $this->em->persist($fish);
            $this->em->flush();
        }
    }
}
