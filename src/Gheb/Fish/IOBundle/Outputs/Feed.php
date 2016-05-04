<?php

namespace Gheb\Fish\IOBundle\Outputs;

use Gheb\Fish\FishBundle\Entity\Fish;
use \DateTime;

/**
 * Class Hunger
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Outputs
 */
class Hunger extends AbstractOutput
{
    /**
     * Feed
     */
    public function apply()
    {
        $hunger = $this->fish->getHunger();
        $this->fish->setHunger($hunger-3);
    }
}
