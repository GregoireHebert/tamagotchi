<?php

namespace TamagotchiBundle\IO\Inputs;

use Doctrine\ORM\NonUniqueResultException;
use TamagotchiBundle\Entity\Tamagotchi;
use TamagotchiBundle\Entity\TamagotchiRepository;
use Gheb\IOBundle\Inputs\AbstractInput;

/**
 * Class AbstractOutput
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
abstract class Input extends AbstractInput
{
    /**
     * @var Tamagotchi
     */
    protected $tamagotchi;

    /**
     * @throws NonUniqueResultException
     */
    protected function getTamagotchi()
    {
        /** @var TamagotchiRepository $repo */
        $repo       = $this->em->getRepository('TamagotchiBundle:Tamagotchi');
        $this->tamagotchi = $repo->findAliveTamagotchi();
    }
}
