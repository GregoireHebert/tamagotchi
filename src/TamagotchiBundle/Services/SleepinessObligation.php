<?php

namespace TamagotchiBundle\Services;

use TamagotchiBundle\Entity\Tamagotchi;

/**
 * Class HungerEffect
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class SleepinessObligation extends AbstractLifeObligation
{
    public function applyEffect(Tamagotchi $tamagotchi)
    {
        // never sleeps increase bad wealth, and reduce health
        if ($tamagotchi->getSleepiness()>=Tamagotchi::MAX_SLEEP || $tamagotchi->getSleepiness()<=0) {
            $tamagotchi->setWealth($tamagotchi->getWealth() -1);
            $tamagotchi->setHealth($tamagotchi->getHealth() -1);
        }
    }
}
