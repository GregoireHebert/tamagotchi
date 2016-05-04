<?php

namespace Gheb\Fish\IOBundle\Inputs;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\Fish\FishBundle\Entity\FishRepository;

/**
 * Class AbstractInput
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Inputs
 */
abstract class AbstractInput
{
    /**
     * @var Fish
     */
    protected $fish;

    public function __construct(EntityManager $em)
    {
        /** @var FishRepository $repo */
        $repo = $em->getRepository('FishBundle:Fish');
        $this->fish = $repo->findAliveFish();

        if ($this->fish == null) {
            throw new EntityNotFoundException('There is no fish alive...');
        }
    }

    /**
     * Getting the value of the input
     * @return mixed
     */
    public abstract function getValue();
}
