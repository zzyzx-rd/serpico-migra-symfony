<?php

namespace App\Repository;

use App\Entity\Recurring;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recurring|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recurring|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recurring[]    findAll()
 * @method Recurring[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecurringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recurring::class);
    }

    // /**
    //  * @return Recurring[] Returns an array of Recurring objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recurring
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
