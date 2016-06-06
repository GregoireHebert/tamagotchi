<?php

namespace Gheb\Fish\IOBundle\Outputs;

use Gheb\Fish\FishBundle\Entity\Fish;
use \DateTime;

/**
 * Class Play
 * @author  Grégoire Hébert <gregoire@opo.fr>
 * @package Gheb\Fish\IOBundle\Outputs
 */
class Play extends AbstractOutput
{
    /**
     * Feed
     */
    public function apply()
    {
        $this->getFish();
        $playfull = $this->fish->getPlayfull();
        $this->fish->setPlayfull($playfull-3);
        $this->em->flush();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Play';
    }
}
