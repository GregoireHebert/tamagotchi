<?php

namespace FishBundle\IO\Inputs;

use Doctrine\ORM\NonUniqueResultException;
use FishBundle\Entity\Fish;

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
     * @return int|mixed
     * @throws NonUniqueResultException
     */
    public function getValue()
    {
        $this->getFish();

        if ($this->fish instanceof Fish) {
            return $this->fish->getSleepiness();
        }

        return 0;
    }
}
