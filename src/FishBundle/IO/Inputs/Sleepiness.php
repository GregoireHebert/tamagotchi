<?php

namespace FishBundle\IO\Inputs;

use FishBundle\Entity\Fish;

/**
 * Class Sleepiness
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class Sleepiness extends Input
{
    public function getName()
    {
        return 'sleepiness';
    }

    /**
     * get the Sleepiness
     */
    public function getValue()
    {
        $this->getFish();

        if ($this->fish instanceof Fish) {
            return $this->fish->getSleepiness();
        } else {
            return 0;
        }
    }
}
