<?php

namespace Gheb\Fish\FishBundle\IO\Inputs;

use Gheb\Fish\FishBundle\Entity\Fish;

/**
 * Class Sleepiness
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\IO\Inputs
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
