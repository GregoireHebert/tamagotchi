<?php

namespace TamagotchiBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class TamagotchiRepository
 *
 * @author  Grégoire Hébert <gregoire@opo.fr>
 */
class TamagotchiRepository extends EntityRepository
{
    /**
     * Return an alive tamagotchi or null
     * there cannot be two tamagotchies alive at the same time
     *
     * @return Tamagotchi|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findAliveTamagotchi():? Tamagotchi
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
    public function findLastAliveTamagotchi()
    {
        $qb = $this->createQueryBuilder('f');
        $qb->where('f.health <= 0');
        $qb->orderBy('f.id', 'DESC');
        $qb->setFirstResult(0);
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
