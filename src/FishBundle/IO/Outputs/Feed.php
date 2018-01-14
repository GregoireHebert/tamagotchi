<?php

namespace FishBundle\IO\Outputs;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use FishBundle\Entity\Fish;

/**
 * Class Feed
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class Feed extends Output
{
    /**
     * Feed
     *
     * @throws OptimisticLockException
     * @throws NonUniqueResultException
     */
    public function apply()
    {
        $this->getFish();
        if ($this->fish instanceof Fish) {
            $hunger = $this->fish->getHunger();
            $this->fish->setHunger($hunger -5);
            $this->fish->setWealth($this->fish->getWealth() -1);
            $this->fish->setWeight($this->fish->getWeight() +1);
            $this->em->flush();
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Feed';
    }
}
