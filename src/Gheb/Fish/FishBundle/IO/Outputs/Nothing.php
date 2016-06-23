<?php

namespace Gheb\Fish\FishBundle\IO\Outputs;

/**
 * Class Nothing
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\IO\Outputs
 */
class Nothing extends Output
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
