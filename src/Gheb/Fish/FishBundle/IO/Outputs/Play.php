<?php

namespace Gheb\Fish\FishBundle\IO\Outputs;

use Gheb\Fish\FishBundle\Entity\Fish;

/**
 * Class Play
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class Play extends Output
{
    /**
     * Feed
     */
    public function apply()
    {
        $this->getFish();
        if ($this->fish instanceof Fish) {
            $playFull = $this->fish->getPlayfull();
            $this->fish->setPlayfull($playFull - 3);
            $this->em->flush();
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Play';
    }
}
