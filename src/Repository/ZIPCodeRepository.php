<?php

namespace App\Repository;

use App\Entity\ZIPCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ZIPCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method ZIPCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method ZIPCode[]    findAll()
 * @method ZIPCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZIPCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ZIPCode::class);
    }

    // /**
    //  * @return ZIPCode[] Returns an array of ZIPCode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ZIPCode
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}