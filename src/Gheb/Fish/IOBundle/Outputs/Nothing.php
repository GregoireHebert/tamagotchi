<?php

namespace Gheb\Fish\IOBundle\Outputs;

use Gheb\Fish\FishBundle\Entity\Fish;
use \DateTime;

/**
 * Class Nothing
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Outputs
 */
class Nothing extends AbstractOutput
{
    /**
     * Do nothing
     */
    public function apply()
    {
        // do nothing
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Nothing';
    }
}
