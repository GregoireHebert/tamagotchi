<?php

namespace Gheb\Fish\FishBundle\IO\Inputs;

use Gheb\Fish\FishBundle\Entity\Fish;

/**
 * Class Hunger
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class Hunger extends Input
{
    public function getName()
    {
        return 'hunger';
    }

    /**
     * get the Hunger
     */
    public function getValue()
    {
        $this->getFish();

        if ($this->fish instanceof Fish) {
            return $this->fish->getHunger();
        } else {
            return 0;
        }
    }
}
