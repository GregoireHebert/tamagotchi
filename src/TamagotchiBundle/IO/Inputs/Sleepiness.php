<?php

namespace TamagotchiBundle\IO\Inputs;

use Doctrine\ORM\NonUniqueResultException;
use TamagotchiBundle\Entity\Tamagotchi;

/**
 * Class Sleepiness
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class Sleepiness extends Input
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'sleepiness';
    }

    /**
     * get the Sleepiness
     *
     * @throws NonUniqueResultException
     *
     * @return int
     */
    public function getValue(): int
    {
        $this->getTamagotchi();

        if ($this->tamagotchi instanceof Tamagotchi) {
            return $this->tamagotchi->getSleepiness();
        }

        return 0;
    }
}
