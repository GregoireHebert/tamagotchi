<?php

namespace Gheb\Fish\IOBundle\Monolog;

use Monolog\Logger;

/**
 * Class IOLogger
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Monolog
 */
class IOLogger
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