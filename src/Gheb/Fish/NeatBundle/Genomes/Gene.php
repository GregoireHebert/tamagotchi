<?php

namespace Gheb\Fish\NeatBundle\Genomes;

class Gene
{
    /**
     * @var InputNode
     */
    public $inputNode;

    /**
     * @var OutputNode
     */
    public $outputNode;

    /**
     * @var float
     */
    public $weight;

    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var int
     */
    public $innovation;
}