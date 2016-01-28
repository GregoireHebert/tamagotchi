<?php

namespace Gheb\Tamagotchi\LifeBundle\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Gheb\Tamagotchi\CoreBundle\Entity\Fish;
use Gheb\Tamagotchi\CoreBundle\Entity\LogSupplies;

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
        if ($this->character instanceof Fish && !$this->character->isDead()) {

            $now = new \DateTime();

            $logs = $this->character->getLogs()->filter(function ($el) {
                /** @var LogSupplies $el */
                return $el->getAction() == LogSupplies::ACTION_LIFE_TAKE_PART;
            });

            $lastBirthday = $this->character->getLogs()->filter(function ($el) {
                /** @var LogSupplies $el */
                return $el->getAction() == LogSupplies::ACTION_HAPPY_BIRTHDAY;
            });

            $logIterator = $logs->getIterator();
            $logIterator->uasort(function ($a, $b) {
                return ($a->getTakenAt() > $b->getTakenAt()) ? -1 : 1;
            });

            $BDiterator = $lastBirthday->getIterator();
            $BDiterator->uasort(function ($a, $b) {
                return ($a->getTakenAt() > $b->getTakenAt()) ? -1 : 1;
            });

            /** @var LogSupplies $lifeLog */
            $lifeLog = new ArrayCollection(iterator_to_array($logIterator));
            $lifeLog = $lifeLog->first();

            /** @var LogSupplies $lifeLog */
            $birthdayLog = new ArrayCollection(iterator_to_array($BDiterator));
            $birthdayLog = $birthdayLog->first();

            $diff = $lifeLog instanceof LogSupplies ? ($lifeLog->getTakenAt()->diff($now)->m%20) : 0;
            $lifeMustStrikeOnce = !$lifeLog instanceof LogSupplies;
            $lifeStrikesAgain = false;

            $lifeEffects = 'Life';

            $aging = !$birthdayLog instanceof LogSupplies ? 1 : $birthdayLog->getTakenAt()->diff($now)->d;
            if ($aging > 0) {
                $this->character->setHealth($this->character->getHealth() - $aging);
                $lifeEffects .= ';happyBirthday';
            }

            while ($lifeMustStrikeOnce || $diff > 0) {
                $lifeStrikesAgain = true;
                $lifeMustStrikeOnce = false;

                if ($this->character->getMood() == Fish::MOOD_STILL && $now->format('i')%20 == 0) {
                    $this->character->newMood();
                    $lifeEffects .= ';newMood';
                }

                if ($this->character->getMood() == Fish::MOOD_SLEEPY) {
                    $this->character->decreaseSleepFul(1.5);
                    $lifeEffects .= ';decreaseSleepFul';
                } else {
                    $this->character->decreaseSleepFul();
                    $lifeEffects .= ';decreaseSleepFul';
                }

                if ($this->character->getMood() == Fish::MOOD_SICK) {
                    $this->character->setHealth($this->character->getHealth() - 5);
                    $lifeEffects .= ';sick';
                }

                if ($this->character->getMood() == Fish::MOOD_PLAYER) {
                    $this->character->decreaseHappiness();
                    $lifeEffects .= ';decreaseHappiness';
                }

                if ($this->character->getMood() == Fish::MOOD_NATURAL) {
                    $this->character->decreaseCleanliness();
                    $lifeEffects .= ';decreaseCleanliness';
                }

                $this->character->increaseHunger();
                $lifeEffects .= ';increaseHunger';

                if ($this->character->getHappiness() <= 2) {
                    $this->character->setHealth($this->character->getHealth() - 5);
                    $lifeEffects .= ';nothappy';
                }

                if ($this->character->getHunger() <= 2) {
                    $this->character->setHealth($this->character->getHealth() - 5);
                    $lifeEffects .= ';tooMuchFeed';
                }

                if ($this->character->getHunger() >= 8) {
                    $this->character->setHealth($this->character->getHealth() - 5);
                    $lifeEffects .= ';needToEat';
                }

                if ($this->character->getSleepFul() <= 2) {
                    $this->character->setHealth($this->character->getHealth() - 5);
                    $lifeEffects .= ';needtosleep';
                }

                if ($this->character->getCleanliness() <= 2) {
                    $this->character->setHealth($this->character->getHealth() - 5);
                    $lifeEffects .= ';needToClean';
                }

                if ($this->character->getWeight() > 100) {
                    $this->character->setHealth($this->character->getHealth() - 5);
                    $lifeEffects .= ';tooMuchWeight';
                }

                if ($this->character->getWeight() < 5) {
                    $this->character->setHealth($this->character->getHealth() - 5);
                    $lifeEffects .= ';tooSkinny';
                }

                if ($this->character->getMadness() > 25) {
                    $this->character->setHealth($this->character->getHealth() - 5);
                    $lifeEffects .= ';madness';
                }

                $diff--;
            }

            if ($lifeStrikesAgain) {
                $log = new LogSupplies(LogSupplies::ACTION_LIFE_TAKE_PART, $lifeEffects);
                $this->character->addLog($log);

                $this->manager->flush();
            }

            if ($this->character->getHealth() <= 0) {
                throw new \Exception('Je n\'ai plus de vie');
            }
        } else {
            throw new \Exception('Je suis mort');
        }
    }
}