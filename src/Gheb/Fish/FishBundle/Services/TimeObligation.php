<?php

namespace Gheb\Fish\FishBundle\Services;

use Gheb\Fish\FishBundle\Entity\Fish;

/**
 * Class HungerEffect
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\Services
 */
class TimeObligation extends AbstractLifeObligation
{
    public function applyEffect(Fish &$fish)
    {
        // time goes by, health is going down
        $fish->setHealth($fish->getHealth() -1);
        $fish->addLifeTick();

        $fish->setHunger($fish->getHunger() +1);
        $fish->setSleepiness($fish->getSleepiness() +1);

        $this->application .= 'Time :'."\t".' -1 Health'."\n";
        $this->application .= 'Time :'."\t".' +1 Hunger'."\n";
        $this->application .= 'Time :'."\t".' +1 Sleepiness'."\n";
    }
}