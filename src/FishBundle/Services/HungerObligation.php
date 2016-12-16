<?php

namespace FishBundle\Services;

use FishBundle\Entity\Fish;

/**
 * Class HungerEffect
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class HungerObligation extends AbstractLifeObligation
{
    public function applyEffect(Fish &$fish)
    {
        // As it's too low, it's getting worse for it's life
        if ($fish->getHunger() <= 2) {
            $fish->setHealth($fish->getHealth() - 5);
        }

        // As it's too high, it's getting worse for it's life
        if ($fish->getHunger() >= 8) {
            $fish->setHealth($fish->getHealth() - 10);
        }
    }
}
