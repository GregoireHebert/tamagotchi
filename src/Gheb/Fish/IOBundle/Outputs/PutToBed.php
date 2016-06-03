<?php

namespace Gheb\Fish\IOBundle\Outputs;

use \DateTime;

/**
 * Class PutToBed
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Outputs
 */
class PutToBed extends AbstractOutput
{
    /**
     *Put to bed
     */
    public function apply()
    {
        $this->getFish();
        $sleepiness = $this->fish->getSleepiness();
        $this->fish->setSleepiness($sleepiness-5);
        $this->em->flush();
        //var_dump('Sleep :'."\t".' -5 Sleepiness');
        //$this->logger->logger->info('Sleep :'."\t".' -5 Sleepiness'."\n");
    }

    public function getName()
    {
        return 'putToBed';
    }
}
