<?php

namespace App\Repository;

use App\Entity\TemplateCriterion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TemplateCriterion|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateCriterion|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateCriterion[]    findAll()
 * @method TemplateCriterion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateCriterionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateCriterion::class);
    }

    // /**
    //  * @return TemplateCriterion[] Returns an array of TemplateCriterion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TemplateCriterion
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
