<?php

namespace TamagotchiBundle\Services;

use TamagotchiBundle\Entity\Tamagotchi;

/**
 * Class HungerEffect
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class HungerObligation extends AbstractLifeObligation
{
    public function applyEffect(Tamagotchi $tamagotchi)
    {
        // fat, increase the weight and the bad wealth
        if ($tamagotchi->getHunger()>=Tamagotchi::MAX_HUNGER) {
            $tamagotchi->setWealth($tamagotchi->getWealth() +1);
            $tamagotchi->setWeight($tamagotchi->getWeight() -1);
        }

        // skinny, decrease the weight and increase the bad wealth
        if ($tamagotchi->getHunger()<=0) {
            $tamagotchi->setWealth($tamagotchi->getWealth() +1);
            $tamagotchi->setWeight($tamagotchi->getWeight() +1);
        }

        // eat too much
        if ($tamagotchi->getHunger()<=0) {
            $delta = 1 + floor(($tamagotchi->getHunger()*-1)/Tamagotchi::MAX_HUNGER);
            $tamagotchi->setHealth($tamagotchi->getHealth() - $delta);
        }

        // does not eat
        if ($tamagotchi->getHunger()>=Tamagotchi::MAX_HUNGER) {
            $delta = 1 + floor($tamagotchi->getHunger()/Tamagotchi::MAX_HUNGER);
            $tamagotchi->setHealth($tamagotchi->getHealth() - $delta);
        }
    }
}
