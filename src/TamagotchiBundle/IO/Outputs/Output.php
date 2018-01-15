<?php

namespace TamagotchiBundle\IO\Outputs;

use Doctrine\ORM\NonUniqueResultException;
use TamagotchiBundle\Entity\Tamagotchi;
use TamagotchiBundle\Entity\TamagotchiRepository;
use Gheb\IOBundle\Outputs\AbstractOutput;

/**
 * Class AbstractOutput
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
abstract class Output extends AbstractOutput
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
