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

        // classic time application
        $fish->setHunger($fish->getHunger() +1); // gets hungry
        $fish->setSleepiness($fish->getSleepiness() + 1);// gets sleepy
        $fish->setPlayfull($fish->getPlayfull() +1);// needs to play

        // eat too much or does not eat
        if ($fish->getHunger()>=Fish::MAX_HUNGER || $fish->getHunger()<=0) {
            $fish->setHealth($fish->getHealth() -1);
        }

        // plays too much or does not play
        if ($fish->getPlayfull()>=Fish::MAX_PLAY || $fish->getPlayfull()<=0) {
            $fish->setHealth($fish->getHealth() -1);
        }

        // sleep too much or does not sleep
        if ($fish->getSleepiness()>=Fish::MAX_SLEEP || $fish->getSleepiness()<=0) {
            $fish->setHealth($fish->getHealth() -1);
        }

        // Bad wealth shorten it's life
        if ($fish->getWealth()>=Fish::MAX_WEALTH) {
            $fish->setHealth($fish->getHealth() -2);
        }

        // starving or fat, shorten it's life
        if ($fish->getWeight()>=Fish::MAX_WEIGHT || $fish->getWeight()<=0) {
            $fish->setHealth($fish->getHealth() -2);
        }
    }
}
