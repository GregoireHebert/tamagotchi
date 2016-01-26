<?php

namespace Gheb\Tamagotchi\LifeBundle\Services;

use Gheb\Tamagotchi\CoreBundle\Entity\Fish;
use Gheb\Tamagotchi\CoreBundle\Personality\PersonalityLoader;

/**
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 */
class CreateLifeService
{
    public function __construct($personality = 'average')
    {
        $personality = PersonalityLoader::load($personality);
        $character = new Fish();

        $character->setPersonality($personality);

        ini_set('max_execution_time', '-1');

        while (!$character->isDead()) {

            $now = new \DateTime();

            if ($now->format('H')%2 == 0) {
                $character->decreaseSleepFul();
                $character->decreaseHappiness();
                if ($character->getMood() == Fish::MOOD_NATURAL) {
                    $character->decreaseCleanliness();
                }
            } elseif ($now->format('H')%1 == 0) {
                $character->increaseHunger();
            }

            if ($character->getHappiness() < 5) {
                $character->setHealth($character->getHealth()-1);
            }

            if ($character->getHunger() > 30 || $character->getHunger() < 10) {
                $character->setHealth($character->getHealth()-1);
            }

            if ($character->getSleepFul() < 3) {
                $character->setHealth($character->getHealth()-1);
            }

            if ($character->getCleanliness() < 5) {
                $character->setHealth($character->getHealth()-1);
            }

            if ($character->getHappiness() <= 2) {
                $character->setHealth($character->getHealth()-1);
            }

            if ($character->getHunger() >= 35 || $character->getHunger() <= 5) {
                $character->setHealth($character->getHealth()-1);
            }

            if ($character->getSleepFul() == 1) {
                $character->setHealth($character->getHealth()-1);
            }

            if ($character->getCleanliness() <= 2) {
                $character->setHealth($character->getHealth()-1);
            }

            if ($character->getHealth() <= 0) {
                return ('Je suis mort');
            }

            if ($character->getMood() == Fish::MOOD_STILL && $now->format('H')%6 == 0) {
                $character->newMood();
            }

            sleep(3600);
        }

        return ('je suis mort');
    }
}