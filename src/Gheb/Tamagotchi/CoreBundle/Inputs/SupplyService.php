<?php
namespace Gheb\Tamagotchi\CoreBundle\Inputs;

use Doctrine\ORM\EntityManager;
use Gheb\Tamagotchi\CoreBundle\Entity\Fish;
use Gheb\Tamagotchi\CoreBundle\Entity\LogSupplies;

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

    public function __construct(EntityManager $em)
    {
        $characters = $em->createQuery('select u from Gheb\Tamagotchi\CoreBundle\Entity\Fish u where u.health > 0')
            ->getResult();
        $this->character = array_pop($characters);
        $this->manager = $em;
    }

    public function heal()
    {
        $log = new LogSupplies(LogSupplies::ACTION_GIVE_MEDICINE, 'Owner');
        $this->character->addLog($log);

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
        } else {

            $personality = $this->character->getPersonality();
            foreach ($personality['States'] as $state=>&$percents) {
                if ($state == 'Play') {
                    array_walk($percents, function(&$item) { $item = min(max(0,$item-2), 100); });
                } else {
                    $percents['Play'] = min(max(0,$percents['Play']+2), 100);
                }
            }

            $this->character->setPersonality($personality);
        }

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
                if ($state == 'Sleep') {
                    array_walk($percents, function(&$item) { $item = min(max(0,$item-2), 100); });
                } else {
                    $percents['Sleep'] = min(max(0,$percents['Sleep']+2), 100);
                }
            }

            $this->character->setPersonality($personality);
        }

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
                if ($state == 'Toilet') {
                    array_walk($percents, function(&$item) { $item = min(max(0,$item-2), 100); });
                } else {
                    $percents['Toilet'] = min(max(0,$percents['Toilet']+2), 100);
                }
            }

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
                if ($state == 'Still') {
                    array_walk($percents, function(&$item) { $item = min(max(0,$item-2), 100); });
                } else {
                    $percents['Still'] = min(max(0,$percents['Still']+2), 100);
                }
            }

            $this->character->setPersonality($personality);
        }

        $log = new LogSupplies(LogSupplies::ACTION_FEED, 'Owner');
        $this->character->addLog($log);

        $this->character->setMood(Fish::MOOD_STILL);

        $this->manager->flush();
    }
}
