<?php

namespace Gheb\Fish\FishBundle\Services;

use Gheb\Fish\FishBundle\Entity\Fish;
use Gheb\Fish\FishBundle\Monolog\ObligationsLogger;

/**
 * Class AbstractLifeObligation
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\Services
 */
abstract class AbstractLifeObligation
{
    /**
     * What has been applied
     * @var string
     */
    protected $application;

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(ObligationsLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Apply any effect upon the fish
     *
     * @param Fish $fish
     *
     * @return mixed
     */
    public abstract function applyEffect(Fish &$fish);

    /**
     * Record the application onto logs
     *
     * @param string $application
     *
     * @return mixed
     */
    public function logEffect()
    {
        if (!empty(trim($this->application))) {
            //var_dump($this->application);
            //$this->logger->logger->debug($this->application);
        }
    }
}