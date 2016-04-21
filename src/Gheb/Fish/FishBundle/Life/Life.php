<?php

namespace Gheb\Fish\FishBundle\Life;

use Doctrine\ORM\EntityManager;
use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\Fish\FishBundle\Personality\PersonalityLoader;

/**
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 */
class Life
{
    /**
     * @var Fish
     */
    private $fish;

    /**
     * @var EntityManager
     */
    private $manager;

    public function __construct(EntityManager $em)
    {
        $this->manager = $em;
    }

    /**
     * Create a new fish if there is none.
     * Return the alive one when possible.
     *
     * @return Fish
     */
    public function createLife()
    {
        $fishes = $this->manager->createQuery('select f from FishBundle:Fish f where f.health > 0')->getResult();
        if (empty($fishes)) {
            $personality = PersonalityLoader::load('average');
            $fish = new Fish();

            $fish->setPersonality($personality);

            $this->manager->persist($fish);
            $this->manager->flush();
        } else {
            $fish = array_pop($fishes);
        }

        $this->fish = $fish;
        return $fish;
    }

    public function fishIsOlder($lifeStrikesAgain = 1)
    {
        $now = new \DateTime();

        while ($lifeStrikesAgain > 0) {
            $lifeStrikesAgain = true;

            // every body gets hungry :)
            $this->fish->increaseHunger();

            // the fish did nothing for 30min... it needs a new mood
            if ($this->fish->getMood() == Fish::MOOD_STILL && $now->format('i') % 30 == 0) {
                $this->fish->newMood();
            }

            // the fish is more and more sleepy every time.
            // if it really need to sleep, increase the need to sleep even more
            if ($this->fish->getMood() == Fish::MOOD_SLEEPY) {
                $this->fish->decreaseSleepFul(1.5);
            } else {
                $this->fish->decreaseSleepFul();
            }

            // the fish is sick, it's health goes down
            if ($this->fish->getMood() == Fish::MOOD_SICK) {
                $this->fish->setHealth($this->fish->getHealth() - 5);
            }

            // the fish wants to play, decrease it's happiness until it plays
            if ($this->fish->getMood() == Fish::MOOD_PLAYER) {
                $this->fish->decreaseHappiness();
            }

            // the fish did poop, and the water is dirty,
            // decrease it's cleanliness until it's clean again
            if ($this->fish->getMood() == Fish::MOOD_NATURAL) {
                $this->fish->decreaseCleanliness();
            }

            // As it's low, it's getting worse for it's life
            if ($this->fish->getHappiness() <= 2) {
                $this->fish->setHealth($this->fish->getHealth() - 5);
            }

            // As it's low, it's getting worse for it's life
            if ($this->fish->getHunger() <= 2) {
                $this->fish->setHealth($this->fish->getHealth() - 5);
            }

            // As it's low, it's getting worse for it's life
            if ($this->fish->getSleepFul() <= 2) {
                $this->fish->setHealth($this->fish->getHealth() - 5);
            }

            // As it's low, it's getting worse for it's life
            if ($this->fish->getCleanliness() <= 2) {
                $this->fish->setHealth($this->fish->getHealth() - 5);
            }

            // As it's low, it's getting worse for it's life
            if ($this->fish->getWeight() < 5) {
                $this->fish->setHealth($this->fish->getHealth() - 5);
            }

            // As it's to high, it's getting worse for it's life
            if ($this->fish->getHunger() >= 8) {
                $this->fish->setHealth($this->fish->getHealth() - 5);
            }

            // As it's to high, it's getting worse for it's life
            if ($this->fish->getWeight() > 100) {
                $this->fish->setHealth($this->fish->getHealth() - 5);
            }

            // As it's to high, it's getting worse for it's life
            if ($this->fish->getMadness() > 25 && $this->fish->getHealth() <= 0) {
                $this->fish->setHealth($this->fish->getHealth() - 5);
            }

            $lifeStrikesAgain--;
        }
    }

    public function lifeIsUnfair()
    {
        if ($this->fish instanceof Fish && !$this->fish->isDead()) {

            $this->fish->setHealth($this->fish->getHealth() - $this->fish->getAge());
            $this->fishIsOlder();

            $this->manager->flush();

            if ($this->fish->getHealth() <= 0) {
                throw new \Exception('Je n\'ai plus de vie');
            }
        } else {
            throw new \Exception('Je suis mort');
        }
    }
}