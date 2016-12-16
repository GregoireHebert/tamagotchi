<?php

namespace FishBundle\IO\Outputs;

use FishBundle\Entity\Fish;

/**
 * Class Feed
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class Feed extends Output
{
    /**
     * Feed
     */
    public function apply()
    {
        $this->getFish();

        if ($this->fish instanceof Fish) {
            $hunger = $this->fish->getHunger();
            $this->fish->setHunger($hunger - 3);
            $this->em->flush();
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Feed';
    }
}
