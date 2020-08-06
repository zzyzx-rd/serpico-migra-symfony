<?php

namespace App\Repository;

use App\Entity\UserTempo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserTempo|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTempo|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTempo[]    findAll()
 * @method UserTempo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTempoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTempo::class);
    }

    // /**
    //  * @return UserTempo[] Returns an array of UserTempo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserTempo
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
