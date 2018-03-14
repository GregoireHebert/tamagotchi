<?php

namespace TamagotchiBundle\Entity;

/**
 * Class Tamagotchi
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class Tamagotchi
{
    public const MAX_HEALTH = 300;
    public const MAX_HUNGER = 10;
    public const MAX_PLAY = 10;
    public const MAX_SLEEP = 10;
    public const MAX_WEALTH = 10;
    public const MAX_WEIGHT = 10;

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
        $this->hunger     = 1;
        $this->sleepiness = 1;
        $this->playfull   = 1;
        $this->lifeTick   = 0;
        $this->wealth     = 10;
        $this->weight     = 5;
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
        $this->weight = min(max(0, $weight), $this::MAX_WEIGHT);
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
        $this->wealth = min(max(0, $wealth), $this::MAX_WEALTH);
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
        $this->health = min(max(0, $health), $this::MAX_HEALTH);
    }

    /**
     * @param int $hunger
     */
    public function setHunger($hunger): void
    {
        $this->hunger = min(max(0, $hunger), $this::MAX_HUNGER);
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
        $this->playfull = min(max(0, $playfull), $this::MAX_PLAY);
    }

    /**
     * @param int $sleepiness
     */
    public function setSleepiness($sleepiness): void
    {
        $this->sleepiness = min(max(0, $sleepiness), $this::MAX_SLEEP);
    }
}
