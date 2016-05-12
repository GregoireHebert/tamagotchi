<?php

namespace Gheb\Fish\NeatBundle\Manager;


use Doctrine\ORM\EntityManager;

class Manager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Manager constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function cloneEntity($entity)
    {
        $this->em->clear($entity);
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }
}