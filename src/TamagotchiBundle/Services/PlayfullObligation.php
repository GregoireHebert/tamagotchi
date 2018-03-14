<?php

namespace TamagotchiBundle\Services;

use TamagotchiBundle\Entity\Tamagotchi;

/**
 * Class PlayfullEffect
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class PlayfullObligation extends AbstractLifeObligation
{
    public function applyEffect(Tamagotchi $tamagotchi)
    {
        // if it does not play at all it gets fat and loses wealth
        if ($tamagotchi->getPlayfull()>=Tamagotchi::MAX_PLAY) {
            $tamagotchi->setWeight($tamagotchi->getWeight() +1);
            $tamagotchi->setWealth($tamagotchi->getWealth() -1);
        }

        // if it play too much it loses weight again
        if ($tamagotchi->getPlayfull()<=0) {
            $tamagotchi->setWeight($tamagotchi->getWeight() -1);
        }
    }
}
