<?php

namespace Gheb\Fish\FishBundle\Services;

use Gheb\Fish\FishBundle\Entity\Fish;

/**
 * Class AbstractLifeObligation
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
abstract class AbstractLifeObligation
{
    /**
     * Apply any effect upon the fish
     *
     * @param Fish $fish
     *
     * @return mixed
     */
    abstract public function applyEffect(Fish &$fish);
}
