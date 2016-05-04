<?php

namespace Gheb\Fish\IOBundle\Inputs;

use Gheb\Fish\FishBundle\Entity\Fish;
use \DateTime;
use Gheb\Fish\IOBundle\Inputs\AbstractInput;

/**
 * Class Hunger
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Inputs
 */
class Hunger extends AbstractInput
{
    /**
     * get the Hunger
     */
    public function getValue()
    {
        return $this->fish->getHunger();
    }
}
