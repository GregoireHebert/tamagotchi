<?php

namespace Gheb\Fish\FishBundle\Services;

use Gheb\Fish\FishBundle\Entity\Fish;

/**
 * Class AbstractLifeObligation
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\Services
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
    public abstract function applyEffect(Fish &$fish);

}