<?php

namespace Gheb\Fish\IOBundle\Outputs;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\Fish\FishBundle\Entity\FishRepository;

/**
 * Class AbstractOutput
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Outputs
 */
abstract class AbstractOutput
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
     * Action of doing something for the fish (play, feed, put to bed)
     * @return mixed
     */
    public abstract function apply();
}
