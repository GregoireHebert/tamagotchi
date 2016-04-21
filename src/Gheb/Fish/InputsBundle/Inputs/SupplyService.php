<?php
namespace Gheb\Fish\InputsBundle\Inputs;

use Doctrine\ORM\EntityManager;
use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\Fish\FishBundle\Life\Life;

/**
 * Class SupplyService
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 */
class SupplyService
{
    /**
     * @var Fish
     */
    private $character;

    /**
     * @var EntityManager
     */
    private $manager;

    public function __construct(EntityManager $em, Life $life)
    {
        $this->manager = $em;
        $this->character = $life->createLife();
    }

    public function heal()
    {
        $log = new LogSupplies(LogSupplies::ACTION_GIVE_MEDICINE, 'Owner');
        $this->character->addLog($log);

        $this->character->decreaseMadness();

        $this->character->setMood(Fish::MOOD_STILL);
        $this->manager->flush();
    }

    public function play()
    {
        $logs = $this->character->getLogs();
        $now = new \DateTime();

        $todayPlayLog = $logs->filter(function ($el) use ($now) {
            /** @var LogSupplies $el */
            return $el->getTakenAt()->diff($now)->d == 0 && $el->getAction() == LogSupplies::ACTION_PLAY;
        });

        if ($todayPlayLog->count() < 2) {
            $this->character->increaseHappiness();
            $this->character->decreaseSleepFul();
        } else {
            $this->character->decreaseSleepFul(2);
            $personality = $this->character->getPersonality();
            foreach ($personality['States'] as $state=>&$percents) {
                array_walk($percents, function(&$item, $key) {
                    if ($key != 'Sick' && $key != 'Play') {
                        $item = max(0,$item-5);
                    }
                    elseif ($key == 'Play')
                    {
                        $item = min($item+15, 95);
                    }
                });
            }

            $this->character->setPersonality($personality);
        }

        $this->character->decreaseWeight();

        $log = new LogSupplies(LogSupplies::ACTION_PLAY, 'Owner');
        $this->character->addLog($log);

        $this->character->setMood(Fish::MOOD_STILL);
        $this->manager->flush();
    }

    public function turnOffLight()
    {
        $logs = $this->character->getLogs();
        $now = new \DateTime();

        $todaySleepLog = $logs->filter(function ($el) use ($now) {
            /** @var LogSupplies $el */
            return $el->getTakenAt()->diff($now)->d == 0 && $el->getAction() == LogSupplies::ACTION_TURN_OFF_LIGHT;
        });

        if ($todaySleepLog->count() < 2) {
            $this->character->increaseSleepFul();
        } else {
            $this->character->increaseSleepFul();
            $personality = $this->character->getPersonality();
            foreach ($personality['States'] as $state=>&$percents) {
                array_walk($percents, function(&$item, $key) {
                    if ($key != 'Sick' && $key != 'Sleep') {
                        $item = max(0,$item-5);
                    }
                    elseif ($key == 'Sleep')
                    {
                        $item = min($item+15, 95);
                    }
                });
            }

            $this->character->increaseMadness();
            $this->character->setPersonality($personality);
        }

        $this->character->decreaseWeight(1);

        $log = new LogSupplies(LogSupplies::ACTION_TURN_OFF_LIGHT, 'Owner');
        $this->character->addLog($log);

        $this->character->setMood(Fish::MOOD_STILL);
        $this->manager->flush();
    }

    public function clean()
    {
        $logs = $this->character->getLogs();
        $now = new \DateTime();

        $todayCleanLog = $logs->filter(function ($el) use ($now) {
            /** @var LogSupplies $el */
            return $el->getTakenAt()->diff($now)->d == 0 && $el->getAction() == LogSupplies::ACTION_CLEAN;
        });

        if ($todayCleanLog->count() < 2) {
            $this->character->increaseCleanliness();
        } else {
            $this->character->increaseCleanliness();
            $personality = $this->character->getPersonality();
            foreach ($personality['States'] as $state=>&$percents) {
                array_walk($percents, function(&$item, $key) {
                    if ($key != 'Sick' && $key != 'Play') {
                        $item = max(0,$item-5);
                    }
                    elseif ($key == 'Play')
                    {
                        $item = min($item+15, 95);
                    }
                });
            }

            $this->character->increaseMadness();
            $this->character->setPersonality($personality);
        }

        $log = new LogSupplies(LogSupplies::ACTION_CLEAN, 'Owner');
        $this->character->addLog($log);

        $this->character->setMood(Fish::MOOD_STILL);
        $this->manager->flush();
    }

    public function feed()
    {
        $logs = $this->character->getLogs();
        $now = new \DateTime();

        $todayFeedLog = $logs->filter(function ($el) use ($now) {
            /** @var LogSupplies $el */
            return $el->getTakenAt()->diff($now)->d == 0 && $el->getAction() == LogSupplies::ACTION_FEED;
        });

        if ($todayFeedLog->count() < 5) {
            $this->character->decreaseHunger();
        } else {
            $this->character->decreaseHunger();
            $personality = $this->character->getPersonality();
            foreach ($personality['States'] as $state=>&$percents) {
                array_walk($percents, function(&$item, $key) {
                    if ($key != 'Sick' && $key != 'Still') {
                        $item = max(0,$item-5);
                    }
                    elseif ($key == 'Still')
                    {
                        $item = min($item+15, 95);
                    }
                });
            }

            $this->character->setPersonality($personality);
        }

        $this->character->increaseWeight();

        $log = new LogSupplies(LogSupplies::ACTION_FEED, 'Owner');
        $this->character->addLog($log);

        $this->character->setMood(Fish::MOOD_STILL);

        $this->manager->flush();
    }
}
