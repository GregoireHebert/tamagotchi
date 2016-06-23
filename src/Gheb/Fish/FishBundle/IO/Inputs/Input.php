<?php

namespace Gheb\Fish\FishBundle\IO\Inputs;

use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\Fish\FishBundle\Entity\FishRepository;
use Gheb\IOBundle\Inputs\AbstractInput;

/**
 * Class AbstractOutput
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\IO\Inputs
 */
abstract class Input extends AbstractInput
{
    /**
     * @var Fish
     */
    protected $fish;

    protected function getFish()
    {
        /** @var FishRepository $repo */
        $repo = $this->em->getRepository('FishBundle:Fish');
        $this->fish = $repo->findAliveFish();
    }

}
