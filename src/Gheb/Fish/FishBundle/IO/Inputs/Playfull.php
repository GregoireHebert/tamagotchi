<?php

namespace Gheb\Fish\FishBundle\IO\Inputs;

use Gheb\Fish\FishBundle\Entity\Fish;

/**
 * Class Playfull
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\IO\Inputs
 */
class Playfull extends Input
{
    public function getName()
    {
        return 'playfull';
    }

    /**
     * get the Hunger
     */
    public function getValue()
    {
        $this->getFish();

        if ($this->fish instanceof Fish) {
            return $this->fish->getPlayfull();
        } else {
            return 0;
        }
    }
}
