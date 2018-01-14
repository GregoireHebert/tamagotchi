<?php

namespace FishBundle\Services;

use FishBundle\Entity\Fish;

/**
 * Class PlayfullEffect
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class PlayfullObligation extends AbstractLifeObligation
{
    public function applyEffect(Fish $fish)
    {
        // if it does not play at all it gets fat and loses wealth
        if ($fish->getPlayfull()>=Fish::MAX_PLAY) {
            $fish->setWeight($fish->getWeight() +1);
            $fish->setWealth($fish->getWealth() +1);
        }

        // if it does not play at all it gets fat and loses wealth
        if ($fish->getPlayfull()<=0) {
            $fish->setWeight($fish->getWeight() -1);
        }
    }
}
