<?php

namespace FishBundle\IO\Outputs;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use FishBundle\Entity\Fish;

/**
 * Class PutToBed
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class PutToBed extends Output
{
    /**
     * Put to bed
     *
     * @throws NonUniqueResultException
     * @throws OptimisticLockException
     */
    public function apply()
    {
        $this->getFish();
        if ($this->fish instanceof Fish) {
            $sleepiness = $this->fish->getSleepiness();
            $this->fish->setSleepiness($sleepiness -8);
            $this->fish->setWealth($this->fish->getWealth() -1);
            $this->em->flush();
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'putToBed';
    }
}
