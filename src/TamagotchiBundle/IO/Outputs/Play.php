<?php

namespace TamagotchiBundle\IO\Outputs;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use TamagotchiBundle\Entity\Tamagotchi;

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
        $this->getTamagotchi();
        if ($this->tamagotchi instanceof Tamagotchi) {
            $playFull = $this->tamagotchi->getPlayfull();
            $this->tamagotchi->setPlayfull($playFull -2);
            $this->tamagotchi->setWeight($this->tamagotchi->getWeight() -1);
            $this->tamagotchi->setWealth($this->tamagotchi->getWealth() -1);
            $this->em->flush();
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'run';
    }
}
