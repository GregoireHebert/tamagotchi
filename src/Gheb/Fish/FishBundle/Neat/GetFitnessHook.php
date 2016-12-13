<?php

namespace Gheb\Fish\FishBundle\Neat;

use Doctrine\ORM\EntityManager;
use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\Fish\FishBundle\Entity\FishRepository;
use Gheb\NeatBundle\Hook;

/**
 * Class GetFitnessHook
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class GetFitnessHook implements Hook
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

    public function __invoke()
    {
        /** @var FishRepository $repo */
        /* @var Fish $fish */
        $repo = $this->em->getRepository('FishBundle:Fish');
        $fish = $repo->findLastAliveFish();

        return $fish->getLifeTick();
    }
}
