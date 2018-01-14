<?php

namespace FishBundle\Entity;

/**
 * Class Fish
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class Fish
{
    public const MAX_HEALTH = 300;
    public const MAX_HUNGER = 7;
    public const MAX_PLAY = 4;
    public const MAX_SLEEP = 16;
    public const MAX_WEALTH = 4;
    public const MAX_WEIGHT = 7;

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
    private $lifeTick;

    /**
     * @var int
     */
    private $playfull;

    /**
     * @var int
     */
    private $sleepiness;

    /**
     * @var int
     */
    private $weight;

    /**
     * @var int
     */
    private $wealth;

    public function __construct()
    {
        $this->health     = self::MAX_HEALTH;
        $this->hunger     = 2;
        $this->sleepiness = 4;
        $this->playfull   = 1;
        $this->lifeTick   = 0;
        $this->wealth     = 0;
        $this->weight     = 2;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     */
    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * @return int
     */
    public function getWealth(): int
    {
        return $this->wealth;
    }

    /**
     * @param int $wealth
     */
    public function setWealth(int $wealth): void
    {
        $this->wealth = max(0, $wealth);
    }

    public function addLifeTick(): void
    {
        $this->lifeTick++;
    }

    /**
     * @return int
     */
    public function getHealth(): int
    {
        return $this->health;
    }

    /**
     * @return int
     */
    public function getHunger(): int
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

    public function getLifeTick(): int
    {
        return $this->lifeTick;
    }

    /**
     * @return int
     */
    public function getPlayfull(): int
    {
        return $this->playfull;
    }

    /**
     * @return int
     */
    public function getSleepiness(): int
    {
        return $this->sleepiness;
    }

    /**
     * @param int $health
     */
    public function setHealth($health): void
    {
        $this->health = max(0, $health);
    }

    /**
     * @param int $hunger
     */
    public function setHunger($hunger): void
    {
        $this->hunger = $hunger;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @param $lifeTick
     */
    public function setLifeTick($lifeTick): void
    {
        $this->lifeTick = $lifeTick;
    }

    /**
     * @param int $playfull
     */
    public function setPlayfull($playfull): void
    {
        $this->playfull = max(0, $playfull);
    }

    /**
     * @param int $sleepiness
     */
    public function setSleepiness($sleepiness): void
    {
        $this->sleepiness = $sleepiness;
    }
}
