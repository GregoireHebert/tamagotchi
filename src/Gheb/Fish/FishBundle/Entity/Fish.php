<?php

namespace Gheb\Fish\FishBundle\Entity;

/**
 * Class Fish
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\Entity
 */
class Fish
{
    /**
     * @var int
     */
    private $lifeTick;

    /**
     * @var int
     */
    private $health;

    /**
     * @var int
     */
    private $hunger;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $sleepiness;

    public function __construct()
    {
        $this->health = 100;
        $this->hunger = 2;
        $this->sleepiness = 2;
        $this->lifeTick = 0;
    }

    public function getLifeTick()
    {
        return $this->lifeTick;
    }

    /**
     * @return \DateTime
     */
    public function setLifeTick($lifeTick)
    {
        $this->lifeTick = $lifeTick;
    }

    public function addLifeTick()
    {
        $this->lifeTick++;
    }

    /**
     * @return int
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * @return int
     */
    public function getHunger()
    {
        return $this->hunger;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getSleepiness()
    {
        return $this->sleepiness;
    }

    /**
     * @param int $health
     */
    public function setHealth($health)
    {
        $this->health = min(100, max(0, $health));
    }

    /**
     * @param int $hunger
     */
    public function setHunger($hunger)
    {
        $this->hunger = min(10, max(0, $hunger));
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param int $sleepiness
     */
    public function setSleepiness($sleepiness)
    {
        $this->sleepiness = min(10, max(0, $sleepiness));
    }
}
