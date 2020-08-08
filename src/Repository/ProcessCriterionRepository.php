<?php

namespace App\Repository;

use App\Entity\ProcessCriterion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProcessCriterion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProcessCriterion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProcessCriterion[]    findAll()
 * @method ProcessCriterion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProcessCriterionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProcessCriterion::class);
    }

    // /**
    //  * @return ProcessCriterion[] Returns an array of ProcessCriterion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProcessCriterion
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
