<?php

namespace TamagotchiBundle\Services;

use TamagotchiBundle\Entity\Tamagotchi;

/**
 * Class HungerEffect
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class TimeObligation extends AbstractLifeObligation
{
    /**
     * @param Tamagotchi $tamagotchi
     * @return mixed|void
     */
    public function applyEffect(Tamagotchi $tamagotchi)
    {
        // time goes by, health is going down
        $tamagotchi->setHealth($tamagotchi->getHealth() -1);
        $tamagotchi->addLifeTick();

        // classic time application
        $tamagotchi->setHunger($tamagotchi->getHunger() +1); // gets hungry
        $tamagotchi->setSleepiness($tamagotchi->getSleepiness() + 1);// gets sleepy
        $tamagotchi->setPlayfull($tamagotchi->getPlayfull() +1);// needs to play

        // eat too much or does not eat
        if ($tamagotchi->getHunger()>=Tamagotchi::MAX_HUNGER || $tamagotchi->getHunger()<=0) {
            $tamagotchi->setHealth($tamagotchi->getHealth() -1);
        }

        // plays too much or does not play
        if ($tamagotchi->getPlayfull()>=Tamagotchi::MAX_PLAY || $tamagotchi->getPlayfull()<=0) {
            $tamagotchi->setHealth($tamagotchi->getHealth() -1);
        }

        // sleep too much or does not sleep
        if ($tamagotchi->getSleepiness()>=Tamagotchi::MAX_SLEEP || $tamagotchi->getSleepiness()<=0) {
            $tamagotchi->setHealth($tamagotchi->getHealth() -1);
        }

        // Bad wealth shorten it's life
        if ($tamagotchi->getWealth()>=Tamagotchi::MAX_WEALTH) {
            $tamagotchi->setHealth($tamagotchi->getHealth() -2);
        }

        // starving or fat, shorten it's life
        if ($tamagotchi->getWeight()>=Tamagotchi::MAX_WEIGHT || $tamagotchi->getWeight()<=0) {
            $tamagotchi->setHealth($tamagotchi->getHealth() -2);
        }
    }
}
