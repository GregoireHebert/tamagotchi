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

            $iterator = $logs->getIterator();
            $iterator->uasort(function ($a, $b) {
                return ($a->getTakenAt() > $b->getTakenAt()) ? -1 : 1;
            });

            /** @var LogSupplies $lifeLog */
            $lifeLog = new ArrayCollection(iterator_to_array($iterator));
            $lifeLog = $lifeLog->first();

            $diff = $lifeLog instanceof LogSupplies ? $lifeLog->getTakenAt()->diff($now)->s : 0;
            $lifeMustStrikeOnce = !$lifeLog instanceof LogSupplies;
            $lifeStrikesAgain = false;

            $lifeEffects = 'Life';

            while ($lifeMustStrikeOnce || $diff > 0) {
                $lifeStrikesAgain = true;
                $lifeMustStrikeOnce = false;

                if ($this->character->getMood() == Fish::MOOD_SLEEPY) {
                    $this->character->decreaseSleepFul();
                    $lifeEffects .= ';decreaseSleepFul';
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

                if ($this->character->getHappiness() < 5) {
                    $this->character->setHealth($this->character->getHealth() - 1);
                    $lifeEffects .= ';missinghappiness';
                }

                if ($this->character->getHunger() > 30 || $this->character->getHunger() < 10) {
                    $this->character->setHealth($this->character->getHealth() - 1);
                    $lifeEffects .= ';badfood';
                }

                if ($this->character->getSleepFul() < 3) {
                    $this->character->setHealth($this->character->getHealth() - 1);
                    $lifeEffects .= ';notSleepful';
                }

                if ($this->character->getCleanliness() < 5) {
                    $this->character->setHealth($this->character->getHealth() - 1);
                    $lifeEffects .= ';notClean';
                }

                if ($this->character->getHappiness() <= 2) {
                    $this->character->setHealth($this->character->getHealth() - 1);
                    $lifeEffects .= ';nothappy';
                }

                if ($this->character->getHunger() >= 35 || $this->character->getHunger() <= 5) {
                    $this->character->setHealth($this->character->getHealth() - 1);
                    $lifeEffects .= ';starvingOrObesity';
                }

                if ($this->character->getSleepFul() == 1) {
                    $this->character->setHealth($this->character->getHealth() - 1);
                    $lifeEffects .= ';needtosleep';
                }

                if ($this->character->getCleanliness() <= 2) {
                    $this->character->setHealth($this->character->getHealth() - 1);
                    $lifeEffects .= ';reallydirty';
                }

                if ($this->character->getMood() == Fish::MOOD_STILL || $now->format('i')%6 == 0) {
                    $this->character->newMood();
                    $lifeEffects .= ';newMood';
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