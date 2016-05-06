<?php

namespace Gheb\Fish\IOBundle\Inputs;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\Fish\FishBundle\Entity\FishRepository;
use Gheb\Fish\IOBundle\Monolog\IOLogger;

/**
 * Class AbstractInput
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Inputs
 */
abstract class AbstractInput
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
     * AbstractInput constructor.
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
     * Return the InputName for command retrieval
     * @return string
     */
    public abstract function getName();

    /**
     * Getting the value of the input
     * @return mixed
     */
    public abstract function getValue();
}
