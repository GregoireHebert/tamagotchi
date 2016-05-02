<?php

namespace Gheb\Fish\FishBundle\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Gheb\Fish\FishBundle\Entity\Fish;

/**
 * Class Health
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\Services
 */
class Life
{
    /**
     * @var ArrayCollection
     */
    private $obligations;

    /**
     * Add a life obligation
     * @param $obligation
     */
    public function addObligation($obligation)
    {
        if (!$this->obligations->contains($obligation)) {
            $this->obligations->add($obligation);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $fish = $args->getEntity();

        if (!$fish instanceof Fish) {
            return;
        }

        foreach ($this->obligations as $obligation) {
            if ($obligation instanceof AbstractLifeObligation) {
                $obligation->applyEffect($fish);
            }
        }

        $entityManager = $args->getEntityManager();
        $entityManager->flush();
    }
}
