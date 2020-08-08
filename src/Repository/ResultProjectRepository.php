<?php

namespace App\Repository;

use App\Entity\ResultProject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResultProject|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResultProject|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResultProject[]    findAll()
 * @method ResultProject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResultProject::class);
    }

    // /**
    //  * @return ResultProject[] Returns an array of ResultProject objects
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
    public function findOneBySomeField($value): ?ResultProject
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
