<?php

namespace Gheb\Fish\IOBundle\Inputs;

use Gheb\Fish\FishBundle\Entity\Fish;
use \DateTime;
use Gheb\Fish\IOBundle\Inputs\AbstractInput;

/**
 * Class Health
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Inputs
 */
//class Health extends AbstractInput
class Health
{
    public function getName()
    {
        return 'health';
    }

    /**
     * get the Health
     */
    public function getValue()
    {
        $this->getFish();
        return $this->fish->getHealth();
    }
}
