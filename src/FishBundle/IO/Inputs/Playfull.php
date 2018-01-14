<?php

namespace FishBundle\IO\Inputs;

use Doctrine\ORM\NonUniqueResultException;
use FishBundle\Entity\Fish;

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
        $this->getFish();

        if ($this->fish instanceof Fish) {
            return $this->fish->getPlayfull();
        }

        return 0;
    }
}
