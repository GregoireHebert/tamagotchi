<?php

namespace Gheb\Fish\IOBundle\Outputs;

use \DateTime;

/**
 * Class PutToBed
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Outputs
 */
class PutToBed extends AbstractOutput
{
    /**
     *Put to bed
     */
    public function apply()
    {
        $sleepiness = $this->fish->getSleepiness();
        $this->fish->setSleepiness($sleepiness-5);
    }
}
