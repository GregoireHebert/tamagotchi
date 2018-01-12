<?php

namespace FishBundle\Services;

use FishBundle\Entity\Fish;

/**
 * Class HungerEffect
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class TimeObligation extends AbstractLifeObligation
{
    /**
     * @param Fish $fish
     * @return mixed|void
     */
    public function applyEffect(Fish $fish)
    {
        // time goes by, health is going down
        $fish->setHealth($fish->getHealth() -1);
        $fish->addLifeTick();

        $fish->setHunger($fish->getHunger() +1);
        $fish->setSleepiness($fish->getSleepiness() +0.5);
        $fish->setPlayfull($fish->getPlayfull() +3);
    }
}
