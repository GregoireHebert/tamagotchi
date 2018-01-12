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
        if ($fish->getPlayfull() >= 8) {
            $fish->setHealth($fish->getHealth() - 1);
        }
    }
}
