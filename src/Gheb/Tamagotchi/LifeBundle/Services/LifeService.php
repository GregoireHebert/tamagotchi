<?php

namespace Gheb\Tamagotchi\LifeBundle\Services;

use Doctrine\ORM\EntityManager;
use Gheb\Tamagotchi\CoreBundle\Entity\Fish;

/**
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 */
class LifeService
{
    /**
     * @var Fish
     */
    private $character;

    /**
     * @var EntityManager
     */
    private $manager;

    public function __construct(EntityManager $em)
    {
        $characters = $em->createQuery('select u from Gheb\Tamagotchi\CoreBundle\Entity\Fish u where u.health > 0')
            ->getResult();
        $this->character = array_pop($characters);
        $this->manager = $em;
    }

    public function lifeIsUnfair()
    {
        if (!$this->character->isDead()) {

            $now = new \DateTime();

            if ($now->format('H')%2 == 0) {
                $this->character->decreaseSleepFul();
                $this->character->decreaseHappiness();
                if ($this->character->getMood() == Fish::MOOD_NATURAL) {
                    $this->character->decreaseCleanliness();
                }
            } elseif ($now->format('H')%1 == 0) {
                $this->character->increaseHunger();
            }

            if ($this->character->getHappiness() < 5) {
                $this->character->setHealth($this->character->getHealth()-1);
            }

            if ($this->character->getHunger() > 30 || $this->character->getHunger() < 10) {
                $this->character->setHealth($this->character->getHealth()-1);
            }

            if ($this->character->getSleepFul() < 3) {
                $this->character->setHealth($this->character->getHealth()-1);
            }

            if ($this->character->getCleanliness() < 5) {
                $this->character->setHealth($this->character->getHealth()-1);
            }

            if ($this->character->getHappiness() <= 2) {
                $this->character->setHealth($this->character->getHealth()-1);
            }

            if ($this->character->getHunger() >= 35 || $this->character->getHunger() <= 5) {
                $this->character->setHealth($this->character->getHealth()-1);
            }

            if ($this->character->getSleepFul() == 1) {
                $this->character->setHealth($this->character->getHealth()-1);
            }

            if ($this->character->getCleanliness() <= 2) {
                $this->character->setHealth($this->character->getHealth()-1);
            }



            if ($this->character->getHealth() <= 0) {
                throw new \Exception('Je suis mort');
            }

            if ($this->character->getMood() == Fish::MOOD_STILL && $now->format('H')%6 == 0) {
                $this->character->newMood();
            }

            $this->manager->flush();
        } else {
            throw new \Exception('Je suis mort');
        }
    }
}