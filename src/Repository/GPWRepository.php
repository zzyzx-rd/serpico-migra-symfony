<?php

namespace App\Repository;

use App\Entity\GPW;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GPW|null find($id, $lockMode = null, $lockVersion = null)
 * @method GPW|null findOneBy(array $criteria, array $orderBy = null)
 * @method GPW[]    findAll()
 * @method GPW[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GPWRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GPW::class);
    }

    // /**
    //  * @return GPW[] Returns an array of GPW objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GPW
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
