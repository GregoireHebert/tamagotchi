<?php

namespace Gheb\Fish\NeatBundle\Genomes;

class Gene
{
    /**
     * @var bool
     */
    public $enabled = true;
    /**
     * @var int
     */
    public $innovation = 0;
    /**
     * @var int
     */
    public $into = 0;

    /**
     * @var int
     */
    public $out = 0;
    /**
     * @var float
     */
    public $weight = 0.0;

    /**
     * @return int
     */
    public function getInnovation()
    {
        return $this->innovation;
    }

    /**
     * @return int
     */
    public function getInto()
    {
        return $this->into;
    }

    /**
     * @return int
     */
    public function getOut()
    {
        return $this->out;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @param int $innovation
     */
    public function setInnovation($innovation)
    {
        $this->innovation = $innovation;
    }

    /**
     * @param int $into
     */
    public function setInto($into)
    {
        $this->into = $into;
    }

    /**
     * @param int $out
     */
    public function setOut($out)
    {
        $this->out = $out;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }
}
