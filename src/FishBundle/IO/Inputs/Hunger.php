<?php

namespace FishBundle\IO\Inputs;

use FishBundle\Entity\Fish;

/**
 * Class Hunger
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class Hunger extends Input
{
    /**
     * @return string
     */
    public function getName(): string
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
        }

        return 0;
    }
}
