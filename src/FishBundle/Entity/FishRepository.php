<?php

namespace FishBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class FishRepository
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class FishRepository extends EntityRepository
{
    /**
     * Return an alive fish or null
     * there cannot be two fishes alive at the same time
     *
     * @return Fish|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findAliveFish():? Fish
    {
        $qb = $this->createQueryBuilder('f');
        $qb->where('f.health > 0');
        $qb->orderBy('f.health', 'ASC');
        $qb->setFirstResult(0);
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @return mixed
     *
     * @throws NonUniqueResultException
     */
    public function findLastAliveFish()
    {
        $qb = $this->createQueryBuilder('f');
        $qb->where('f.health <= 0');
        $qb->orderBy('f.id', 'DESC');
        $qb->setFirstResult(0);
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
