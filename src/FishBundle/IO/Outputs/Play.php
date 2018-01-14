<?php

namespace FishBundle\IO\Outputs;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use FishBundle\Entity\Fish;

/**
 * Class Play
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class Play extends Output
{
    /**
     * Feed
     *
     * @throws NonUniqueResultException
     * @throws OptimisticLockException
     */
    public function apply()
    {
        $this->getFish();
        if ($this->fish instanceof Fish) {
            $playFull = $this->fish->getPlayfull();
            $this->fish->setPlayfull($playFull -2);
            $this->fish->setWeight($this->fish->getWeight() -1);
            $this->fish->setWealth($this->fish->getWealth() -1);
            $this->em->flush();
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Play';
    }
}
