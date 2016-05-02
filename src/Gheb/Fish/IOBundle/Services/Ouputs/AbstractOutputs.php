<?php

namespace Gheb\Fish\IOBundle\Services\Outputs;

use Gheb\Fish\FishBundle\Entity\Fish;

/**
 * Class AbstractOutputs
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Services\Outputs
 */
abstract class AbstractOutputs
{
    /**
     * @var Fish
     */
    protected $fish;

    public function __construct(Fish $fish)
    {
        $this->fish = $fish;
    }

    /**
     * Action of doing something for the fish (play, feed, put to bed)
     * @return mixed
     */
    public abstract function apply();
}
