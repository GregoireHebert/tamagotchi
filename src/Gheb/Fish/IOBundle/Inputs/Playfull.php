<?php

namespace Gheb\Fish\IOBundle\Inputs;

use Gheb\Fish\FishBundle\Entity\Fish;
use \DateTime;
use Gheb\Fish\IOBundle\Inputs\AbstractInput;

/**
 * Class Playfull
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Inputs
 */
class Playfull extends AbstractInput
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
        return $this->fish->getPlayfull();
    }
}
