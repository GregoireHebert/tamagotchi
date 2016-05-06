<?php

namespace Gheb\Fish\FishBundle\Monolog;

use Monolog\Logger;

/**
 * Class ObligationsLogger
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\FishBundle\Monolog
 */
class ObligationsLogger
{
    /**
     * @var Logger
     */
    public $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }
}