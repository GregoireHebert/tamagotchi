<?php

namespace TamagotchiBundle\IO\Outputs;

/**
 * Class Nothing
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
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
    public function getName(): string
    {
        return 'idle';
    }
}
