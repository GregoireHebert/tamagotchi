<?php

namespace Gheb\Fish\FishBundle\Neat;

use Doctrine\ORM\EntityManager;
use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\NeatBundle\Command\Hook;

/**
 * Class BeforeNewRunHook
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\Neat
 */
class BeforeNewRunHook extends Hook
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

    public function hook()
    {
        $fish = new Fish();
        $this->em->persist($fish);
        $this->em->flush();
    }
}
