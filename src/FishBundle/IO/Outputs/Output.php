<?php

namespace FishBundle\IO\Outputs;

use FishBundle\Entity\Fish;
use FishBundle\Entity\FishRepository;
use Gheb\IOBundle\Outputs\AbstractOutput;

/**
 * Class AbstractOutput
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
abstract class Output extends AbstractOutput
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