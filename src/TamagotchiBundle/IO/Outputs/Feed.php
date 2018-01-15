<?php

namespace TamagotchiBundle\IO\Outputs;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use TamagotchiBundle\Entity\Tamagotchi;

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
        $this->getTamagotchi();
        if ($this->tamagotchi instanceof Tamagotchi) {
            $hunger = $this->tamagotchi->getHunger();
            $this->tamagotchi->setHunger($hunger -5);
            $this->tamagotchi->setWeight($this->tamagotchi->getWeight() +1);
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
