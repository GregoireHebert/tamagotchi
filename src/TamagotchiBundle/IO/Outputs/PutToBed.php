<?php

namespace TamagotchiBundle\IO\Outputs;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use TamagotchiBundle\Entity\Tamagotchi;

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
        $this->getTamagotchi();
        if ($this->tamagotchi instanceof Tamagotchi) {
            $sleepiness = $this->tamagotchi->getSleepiness();
            $this->tamagotchi->setSleepiness($sleepiness -7);
            $this->tamagotchi->setWealth($this->tamagotchi->getWealth() -1);
            $this->em->flush();
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'walk';
    }
}
