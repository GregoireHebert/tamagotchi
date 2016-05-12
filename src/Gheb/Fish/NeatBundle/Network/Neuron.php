<?php

namespace Gheb\Fish\NeatBundle\Network;


use Doctrine\Common\Collections\ArrayCollection;

class Neuron
{
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
