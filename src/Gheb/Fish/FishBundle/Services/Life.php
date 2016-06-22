<?php

namespace Gheb\Fish\FishBundle\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Gheb\Fish\FishBundle\Entity\Fish;

/**
 * Class Life
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\Services
 */
class Life
{
    /**
     * @var ArrayCollection
     */
    private $obligations;

    public function __construct()
    {
        $this->obligations = new ArrayCollection();
    }

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

    public function applyEffect(Fish $fish)
    {
        if ($fish->getHealth() == 0) {
            return;
        }

        foreach ($this->obligations as $obligation) {
            if ($obligation instanceof AbstractLifeObligation) {
                $obligation->applyEffect($fish);
                //$obligation->logEffect();
            }
        }
    }
}
