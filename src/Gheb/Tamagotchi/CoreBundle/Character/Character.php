<?php
namespace Gheb\Tamagotchi\CoreBundle\Character;

use Doctrine\Common\Collections\ArrayCollection;
use Gheb\Tamagotchi\CoreBundle\Inputs\LogSupplies;

/**
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 */
class Character
{
    const MOOD_SLEEPY   = 1;
    const MOOD_PLAYER   = 2;
    const MOOD_SICK     = 3;
    const MOOD_STILL    = 4;
    const MOOD_NATURAL  = 5;
    /**
     * @var float
     */
    private $cleanliness;
    /**
     * @var float
     */
    private $dateOfBirth;
    /**
     * @var float
     */
    private $happiness;
    /**
     * @var float
     */
    private $health;
    /**
     * @var float
     */
    private $hunger;
    /**
     * @var integer
     */
    private $mood;
    /**
     * @var string
     */
    private $name;
    /**
     * @var Array
     */
    private $personality;

    /**
     * @var ArrayCollection LogSupplies
     */
    private $logs;

    /**
     * @var float
     */
    private $sleepFul;

    public function __construct()
    {
        $this->hunger = 20;
        $this->happiness = 10;
        $this->sleepFul = 10;
        $this->cleanliness = 10;
        $this->mood = self::MOOD_STILL;
        $this->logs = new ArrayCollection();
        $this->dateOfBirth = new \DateTime();
    }

    public function decreaseCleanliness($cleanliness = 1)
    {
        $this->cleanliness -= $cleanliness;
    }

    public function decreaseHappiness($happiness = 1)
    {
        $this->happiness -= $happiness;
    }

    public function decreaseHunger($hunger = 3)
    {
        $this->hunger -= $hunger;
    }

    public function decreaseSleepFul($sleepFul = 1)
    {
        $this->sleepFul -= $sleepFul;
    }

    public function getAge()
    {
        $now = new \DateTime();
        $diff = $this->getDateOfBirth()->diff($now);

        return $diff->d;
    }

    /**
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param \DateTime $dateOfBirth
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return float
     */
    public function getCleanliness()
    {
        return $this->cleanliness;
    }

    /**
     * @param float $cleanliness
     */
    public function setCleanliness($cleanliness)
    {
        $this->cleanliness = $cleanliness;
    }

    /**
     * @return float
     */
    public function getHappiness()
    {
        return $this->happiness;
    }

    /**
     * @param float $happiness
     */
    public function setHappiness($happiness)
    {
        $this->happiness = $happiness;
    }

    /**
     * @return float
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * @param float $health
     */
    public function setHealth($health)
    {
        $this->health = $health;
    }

    /**
     * @return float
     */
    public function getHunger()
    {
        return $this->hunger;
    }

    /**
     * @param float $hunger
     */
    public function setHunger($hunger)
    {
        $this->hunger = $hunger;
    }

    public function getMaxCleanliness()
    {
        return 10;
    }

    public function getMaxHappiness()
    {
        return 10;
    }

    public function getMaxHunger()
    {
        return 40;
    }

    public function getMaxSleepFul()
    {
        return 10;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getPersonality()
    {
        return $this->personality;
    }

    /**
     * @param array $personality
     */
    public function setPersonality($personality)
    {
        $this->personality = $personality;
    }

    /**
     * @return float
     */
    public function getSleepFul()
    {
        return $this->sleepFul;
    }

    /**
     * @param float $sleepFul
     */
    public function setSleepFul($sleepFul)
    {
        $this->sleepFul = $sleepFul;
    }

    public function increaseCleanliness($cleanliness = 5)
    {
        $this->cleanliness += $cleanliness;
    }

    public function increaseHappiness($happiness = 5)
    {
        $this->happiness += $happiness;
    }

    public function increaseHunger($hunger = 1)
    {
        $this->hunger += $hunger;
    }

    public function increaseSleepFul($sleepFul = 7)
    {
        $this->sleepFul += $sleepFul;
    }

    public function addLog(LogSupplies $log)
    {
        if (!$this->logs->contains($log)) {
            $this->logs->add($log);
        }
    }

    public function removeLog(LogSupplies $log)
    {
        if ($this->logs->contains($log)) {
            $this->logs->removeElement($log);
        }
    }

    /**
     * @return ArrayCollection LogSupplies
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param ArrayCollection LogSupplies $logs
     */
    public function setLogs($logs)
    {
        $this->logs = $logs;
    }

    public function isDead()
    {
        if (
            $this->health == 0 ||
            $this->cleanliness == 0 ||
            $this->happiness == 0 ||
            $this->hunger == 0 ||
            $this->sleepFul == 0 ||
            $this->hunger == 40
        )
        {
            return true;
        }

        return false;
    }

    public function newMood()
    {
        $moods = array();
        $mood  = $this->getMood();

        $state = self::MOOD_STILL;

        switch ($mood) {
            case self::MOOD_NATURAL:
                $state = $this->personality['States']['Toilet'];
                break;
            case self::MOOD_PLAYER:
                $state = $this->personality['States']['Play'];
                break;
            case self::MOOD_SICK:
                $state = $this->personality['States']['Sick'];
                break;
            case self::MOOD_SLEEPY:
                $state = $this->personality['States']['Sleep'];
                break;
            case self::MOOD_STILL:
                $state = $this->personality['States']['Still'];
                break;
        }

        $moods = array_merge($moods, array_fill(count($moods), $state['Toilet'], self::MOOD_NATURAL));
        $moods = array_merge($moods, array_fill(count($moods), $state['Play'], self::MOOD_PLAYER));
        $moods = array_merge($moods, array_fill(count($moods), $state['Sick'], self::MOOD_SICK));
        $moods = array_merge($moods, array_fill(count($moods), $state['Sleep'], self::MOOD_SLEEPY));
        $moods = array_merge($moods, array_fill(count($moods), $state['Still'], self::MOOD_STILL));

        $this->setMood($moods[mt_rand(0,100)]);
    }

    /**
     * @return int
     */
    public function getMood()
    {
        return $this->mood;
    }

    /**
     * @param int $mood
     */
    public function setMood($mood)
    {
        $this->mood = $mood;
    }
}
