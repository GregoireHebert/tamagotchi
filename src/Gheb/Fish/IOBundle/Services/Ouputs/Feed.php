<?php

namespace Gheb\Fish\IOBundle\Services\Outputs;

use Gheb\Fish\FishBundle\Entity\Fish;
use \DateTime;

/**
 * Class Sleepy
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Services\Outputs
 */
class Hunger extends AbstractOutputs
{
    /**
     * A.K.A Feed
     */
    public function apply()
    {
        $hunger = $this->fish->getHunger();
        $this->fish->setHunger($hunger-3);
    }
}
