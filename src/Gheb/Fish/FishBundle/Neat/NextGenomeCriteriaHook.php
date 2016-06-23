<?php

namespace Gheb\Fish\FishBundle\Neat;

use Doctrine\ORM\EntityManager;
use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\Fish\FishBundle\Entity\FishRepository;
use Gheb\NeatBundle\Command\Hook;

/**
 * Class NextGenomeCriteriaHook
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\Neat
 */
class NextGenomeCriteriaHook extends Hook
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

    public function hook()
    {

        if ($this->fish === null || $this->fish->getHealth() <= 0) {
            /** @var FishRepository $repo */
            /** @var Fish $fish */
            $repo = $this->em->getRepository('FishBundle:Fish');
            $this->fish = $repo->findAliveFish();
        }

        return $this->fish === null || $this->fish->getHealth() <= 0;
    }
}