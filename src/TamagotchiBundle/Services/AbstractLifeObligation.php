<?php

namespace TamagotchiBundle\Services;

use TamagotchiBundle\Entity\Tamagotchi;

/**
 * Class AbstractLifeObligation
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
abstract class AbstractLifeObligation
{
    /**
     * Apply any effect upon the tamagotchi
     *
     * @param Tamagotchi $tamagotchi
     *
     * @return mixed
     */
    abstract public function applyEffect(Tamagotchi $tamagotchi);
}
