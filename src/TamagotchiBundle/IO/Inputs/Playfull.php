<?php

namespace TamagotchiBundle\IO\Inputs;

use Doctrine\ORM\NonUniqueResultException;
use TamagotchiBundle\Entity\Tamagotchi;

/**
 * Class Playfull
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class Playfull extends Input
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'playfull';
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
            return $this->tamagotchi->getPlayfull();
        }

        return 0;
    }
}
