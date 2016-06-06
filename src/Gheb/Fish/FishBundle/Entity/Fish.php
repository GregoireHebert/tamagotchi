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
    private $playfull;

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
        $this->hunger = 3;
        $this->sleepiness = 3;
        $this->playfull = 3;
        $this->lifeTick = 0;
    }

    public function getLifeTick()
    {
        return $this->lifeTick;
    }

    /**
     * @param $lifeTick
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

    /**
     * @return int
     */
    public function getPlayfull()
    {
        return $this->playfull;
    }

    /**
     * @param int $playfull
     */
    public function setPlayfull($playfull)
    {
        $this->playfull = min(10, max(0, $playfull));
    }
}
