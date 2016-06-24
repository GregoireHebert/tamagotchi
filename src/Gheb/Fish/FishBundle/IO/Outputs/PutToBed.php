<?php

namespace Gheb\Fish\FishBundle\IO\Outputs;

use Gheb\Fish\FishBundle\Entity\Fish;

/**
 * Class PutToBed
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class PutToBed extends Output
{
    /**
     *Put to bed
     */
    public function apply()
    {
        $this->getFish();
        if ($this->fish instanceof Fish) {
            $sleepiness = $this->fish->getSleepiness();
            $this->fish->setSleepiness($sleepiness - 5);
            $this->em->flush();
        }
    }

    public function getName()
    {
        return 'putToBed';
    }
}
