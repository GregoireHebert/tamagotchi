<?php

namespace TamagotchiBundle\IO\Inputs;

use Doctrine\ORM\NonUniqueResultException;
use TamagotchiBundle\Entity\Tamagotchi;

/**
 * Class Hunger
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class Hunger extends Input
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'hunger';
    }

    /**
     * get the Hunger
     *
     * @throws NonUniqueResultException
     *
     * @return int
     */
    public function getValue(): int
    {
        $this->getTamagotchi();

        if ($this->tamagotchi instanceof Tamagotchi) {
            return $this->tamagotchi->getHunger();
        }

        return 0;
    }
}
