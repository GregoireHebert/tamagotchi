<?php

namespace Gheb\Fish\IOBundle\Outputs;

use Gheb\Fish\FishBundle\Entity\Fish;
use \DateTime;

/**
 * Class Feed
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Outputs
 */
class Feed extends AbstractOutput
{
    /**
     * Feed
     */
    public function apply()
    {
        $this->getFish();
        $hunger = $this->fish->getHunger();
        $this->fish->setHunger($hunger-3);
        $this->em->flush();
        $this->logger->logger->info('Feed :'."\t".' -3 Hunger'."\n");
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Feed';
    }
}
