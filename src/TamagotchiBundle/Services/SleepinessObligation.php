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
            $tamagotchi->setWealth($tamagotchi->getWealth() +1);
        }

        // sleep too much
        if ($tamagotchi->getSleepiness()<=0) {
            $delta = 1 + floor(($tamagotchi->getSleepiness()*-1)/Tamagotchi::MAX_SLEEP);
            $tamagotchi->setHealth($tamagotchi->getHealth() - $delta);
        }

        // does not sleep
        if ($tamagotchi->getSleepiness()>=Tamagotchi::MAX_SLEEP ) {
            $delta = 1 + floor($tamagotchi->getSleepiness()/Tamagotchi::MAX_SLEEP);
            $tamagotchi->setHealth($tamagotchi->getHealth() - $delta);
        }
    }
}
