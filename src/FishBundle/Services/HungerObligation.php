<?php

namespace FishBundle\Services;

use FishBundle\Entity\Fish;

/**
 * Class HungerEffect
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class HungerObligation extends AbstractLifeObligation
{
    public function applyEffect(Fish $fish)
    {
        // fat, increase the weight and the bad wealth
        if ($fish->getHunger()>=Fish::MAX_HUNGER) {
            $fish->setWealth($fish->getWealth() +1);
            $fish->setWeight($fish->getWeight() -1);
        }

        // skinny, decrease the weight and increase the bad wealth
        if ($fish->getHunger()<=0) {
            $fish->setWealth($fish->getWealth() +1);
            $fish->setWeight($fish->getWeight() +1);
        }
    }
}
