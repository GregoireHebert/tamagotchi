<?php

namespace Gheb\Fish\IOBundle\Inputs;

use Gheb\Fish\FishBundle\Entity\Fish;
use \DateTime;
use Gheb\Fish\IOBundle\Inputs\AbstractInput;

/**
 * Class Sleepiness
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Inputs
 */
class Sleepiness extends AbstractInput
{
    /**
     * get the Sleepiness
     */
    public function getValue()
    {
        return $this->fish->getSleepiness();
    }
}
