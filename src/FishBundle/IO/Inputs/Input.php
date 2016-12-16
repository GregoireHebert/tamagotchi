<?php

namespace FishBundle\IO\Inputs;

use FishBundle\Entity\Fish;
use FishBundle\Entity\FishRepository;
use Gheb\IOBundle\Inputs\AbstractInput;

/**
 * Class AbstractOutput
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
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
        $repo       = $this->em->getRepository('FishBundle:Fish');
        $this->fish = $repo->findAliveFish();
    }
}
