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
    public function applyEffect(Fish $fish)
    {
        // never sleeps increase bad wealth, and reduce health
        if ($fish->getSleepiness()>=Fish::MAX_SLEEP || $fish->getSleepiness()<=0) {
            $fish->setWealth($fish->getWealth() +1);
        }
    }
}
