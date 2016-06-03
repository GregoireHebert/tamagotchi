<?php

namespace Gheb\Fish\NeatBundle\Network;


use Doctrine\Common\Collections\ArrayCollection;

class Neuron
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var ArrayCollection
     */
    public $incoming ;

    /**
     * @var float
     */
    public $value = 0.0;

    public function __construct()
    {
        $this->incoming = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param $gene
     */
    public function addIncoming($gene)
    {
        $this->incoming->add($gene);
    }

    /**
     * @return ArrayCollection
     */
    public function getIncoming()
    {
        return $this->incoming;
    }

    /**
     * @param ArrayCollection $incoming
     */
    public function setIncoming($incoming)
    {
        $this->incoming = $incoming;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param float $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
