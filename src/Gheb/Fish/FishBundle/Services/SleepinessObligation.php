<?php

namespace Gheb\Fish\FishBundle\Services;

use Gheb\Fish\FishBundle\Entity\Fish;

/**
 * Class HungerEffect
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\Services
 */
class SleepinessObligation extends AbstractLifeObligation
{
    public function applyEffect(Fish &$fish)
    {
        // As it's too high, it's getting worse for it's life
        if ($fish->getSleepiness() >= 8) {
            $fish->setHealth($fish->getHealth() - 5);
            $this->application .= 'Sleepiness >= 8 :'."\t".' -5 Health'."\n";
        }

        // As it's too low, it's getting worse for it's life
        if ($fish->getSleepiness() <= 2) {
            $fish->setHealth($fish->getHealth() - 3);
            $this->application .= 'Sleepiness <= 2 :'."\t".' -3 Health'."\n";
        }
    }
}