<?php

namespace Gheb\Fish\IOBundle\Outputs;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\Fish\FishBundle\Entity\FishRepository;
use Gheb\Fish\IOBundle\Monolog\IOLogger;

/**
 * Class AbstractOutput
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Outputs
 */
abstract class AbstractOutput
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Fish
     */
    protected $fish;

    /**
     * @var IOLogger
     */
    protected $logger;

    /**
     * AbstractOutput constructor.
     *
     * @param EntityManager $em
     * @param IOLogger      $logger
     *
     * @throws EntityNotFoundException
     */
    public function __construct(EntityManager $em, IOLogger $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * Action of doing something for the fish (play, feed, put to bed)
     */
    public abstract function apply();

    protected function getFish()
    {
        /** @var FishRepository $repo */
        $repo = $this->em->getRepository('FishBundle:Fish');
        $this->fish = $repo->findAliveFish();

        if ($this->fish == null) {
            throw new EntityNotFoundException('There is no fish alive...');
        }
    }

    /**
     * Return the OutputName for command retrieval
     * @return string
     */
    public abstract function getName();
}
