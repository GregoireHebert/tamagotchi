<?php

namespace FishBundle\Neat;

use Doctrine\ORM\EntityManager;
use FishBundle\Entity\Fish;
use FishBundle\Entity\FishRepository;
use Gheb\NeatBundle\Hook;

/**
 * Class NextGenomeCriteriaHook
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class NextGenomeCriteriaHook implements Hook
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Fish
     */
    protected $fish;

    /**
     * NextGenomeCriteriaHook constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function __invoke()
    {
        if ($this->fish === null || $this->fish->getHealth() <= 0) {
            /** @var FishRepository $repo */
            /* @var Fish $fish */
            $repo       = $this->em->getRepository('FishBundle:Fish');
            $this->fish = $repo->findAliveFish();
        }

        return $this->fish === null || $this->fish->getHealth() <= 0;
    }
}
