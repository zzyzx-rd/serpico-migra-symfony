<?php

namespace App\Repository;

use App\Entity\LKUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LKUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method LKUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method LKUrl[]    findAll()
 * @method LKUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LKUrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LKUrl::class);
    }

    // /**
    //  * @return LKUrl[] Returns an array of LKUrl objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LKUrl
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
