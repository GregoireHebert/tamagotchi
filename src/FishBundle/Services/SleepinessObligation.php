<?php

namespace FishBundle\Services;

use FishBundle\Entity\Fish;

/**
 * Class HungerEffect
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class SleepinessObligation extends AbstractLifeObligation
{
    public function applyEffect(Fish &$fish)
    {
        // As it's too high, it's getting worse for it's life
        if ($fish->getSleepiness() >= 8) {
            $fish->setHealth($fish->getHealth() - 3);
        }

        // As it's too low, it's getting worse for it's life
        if ($fish->getSleepiness() <= 2) {
            $fish->setHealth($fish->getHealth() - 1);
        }
    }
}
