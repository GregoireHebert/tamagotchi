<?php

namespace Gheb\Fish\IOBundle\Services\Outputs;

use \DateTime;

/**
 * Class Sleepy
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Services\Outputs
 */
class PutToBed extends AbstractOutputs
{
    /**
     * A.K.A Put to bed
     */
    public function apply()
    {
        $sleepiness = $this->fish->getSleepiness();
        $this->fish->setSleepiness($sleepiness-5);
    }
}
