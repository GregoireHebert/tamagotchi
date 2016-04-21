<?php
namespace Gheb\Fish\FishBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fish")
 * @author Grégoire Hébert <gregoirehebert@gheb.fr>
 */
class Fish
{
    const MOOD_SLEEPY   = 1;
    const MOOD_PLAYER   = 2;
    const MOOD_SICK     = 3;
    const MOOD_STILL    = 4;
    const MOOD_NATURAL  = 5;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @var float
     */
    private $cleanliness;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @var float
     */
    private $happiness;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @var float
     */
    private $health;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @var float
     */
    private $hunger;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $mood;

    /**
     * @ORM\Column(type="string", length=256)
     * @var string
     */
    private $name;

    /**
     *  @ORM\Column(type="array")
     * @var array
     */
    private $personality;

    /**
     * @var @ORM\Column(type="decimal", scale=2)
     * @var float
     */
    private $weight;

    /**
     * @var @ORM\Column(type="decimal", scale=2)
     * @var float
     */
    private $madness;

    /**
     * @ORM\ManyToMany(targetEntity="LogSupplies", cascade={"persist", "merge", "remove"})
     * @ORM\JoinTable(name="fish_logs",
     *      joinColumns={@ORM\JoinColumn(name="fish_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="log_id", referencedColumnName="id", unique=true)}
     *      )
     * @var ArrayCollection LogSupplies
     */
    private $logs;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @var float
     */
    private $sleepFul;

    public function __construct()
    {
        $this->name = 'fishy';
        $this->hunger = 8;
        $this->health = 100;
        $this->happiness = 8;
        $this->sleepFul = 14;
        $this->cleanliness = 6;
        $this->mood = self::MOOD_STILL;
        $this->logs = new ArrayCollection();
        $this->dateOfBirth = new \DateTime();
        $this->weight = 30;
        $this->madness = 5;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getMadness()
    {
        return $this->madness;
    }

    /**
     * @param mixed $madness
     */
    public function setMadness($madness)
    {
        $this->madness = $madness;
    }

    public function decreaseCleanliness($cleanliness = 1)
    {
        $this->cleanliness -= $cleanliness;
        $this->cleanliness = min(max($this->cleanliness, 0), $this->getMaxCleanliness());
    }

    public function getMaxCleanliness()
    {
        return 8;
    }

    public function decreaseHappiness($happiness = 1)
    {
        $this->happiness -= $happiness;
        $this->happiness = min(max($this->happiness, 0), $this->getMaxHappiness());
    }

    public function getMaxHappiness()
    {
        return 10;
    }

    public function decreaseHunger($hunger = 5)
    {
        $this->hunger -= $hunger;
        $this->hunger = min(max($this->hunger, 0), $this->getMaxHunger());
    }

    public function getMaxHunger()
    {
        return 10;
    }

    public function decreaseWeight($weight = 5)
    {
        $this->weight = max(0, $this->weight - $weight);
    }

    public function increaseWeight($weight = 10)
    {
        $this->weight = min($this->getMaxWeight(), $this->weight + $weight);
    }

    public function getMaxWeight()
    {
        return 200;
    }

    public function decreaseMadness($madness = 5)
    {
        $this->madness = max(0, $this->madness - $madness);
    }

    public function increaseMadness($madness = 10)
    {
        $this->madness = min($this->getMaxMadness(), $this->madness+$madness);
    }

    public function getMaxMadness()
    {
        return 50;
    }

    public function decreaseSleepFul($sleepFul = 1)
    {
        $this->sleepFul -= $sleepFul;
        $this->sleepFul = min(max($this->sleepFul, 0), $this->getMaxSleepFul());
    }

    public function getMaxSleepFul()
    {
        return 16;
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
        $this->cleanliness = min(max($cleanliness, 0), $this->getMaxCleanliness());
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
        $this->happiness = min(max($happiness, 0), $this->getMaxHappiness());
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
        $this->hunger = min(max($hunger, 0), $this->getMaxHunger());
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
        $this->sleepFul = min(max($sleepFul, 0), $this->getMaxSleepFul());
    }

    public function increaseCleanliness($cleanliness = 5)
    {
        $this->cleanliness += $cleanliness;
        $this->cleanliness = min(max($this->cleanliness, 0), $this->getMaxCleanliness());
    }

    public function increaseHappiness($happiness = 5)
    {
        $this->happiness += $happiness;
        $this->happiness = min(max($this->happiness, 0), $this->getMaxHappiness());
    }

    public function increaseHunger($hunger = 1)
    {
        $this->hunger += $hunger;
        $this->hunger = min(max($this->hunger, 0), $this->getMaxHunger());
    }

    public function increaseSleepFul($sleepFul = 7)
    {
        $this->sleepFul += $sleepFul;
        $this->sleepFul = min(max($this->sleepFul, 0), $this->getMaxSleepFul());
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
        return $this->health == 0;
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

        $this->setMood($moods[mt_rand(0,count($moods))]);
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
