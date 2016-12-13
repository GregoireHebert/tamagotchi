<?php

namespace Gheb\Fish\FishBundle\Neat;

use Doctrine\ORM\EntityManager;
use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\NeatBundle\Hook;

/**
 * Class BeforeNewRunHook
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class BeforeNewRunHook implements Hook
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

    public function __invoke()
    {
        $fish = new Fish();
        $this->em->persist($fish);
        $this->em->flush();
    }
}
