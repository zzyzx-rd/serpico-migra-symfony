<?php

namespace App\Repository;

use App\Entity\SurveyField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SurveyField|null find($id, $lockMode = null, $lockVersion = null)
 * @method SurveyField|null findOneBy(array $criteria, array $orderBy = null)
 * @method SurveyField[]    findAll()
 * @method SurveyField[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyFieldRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SurveyField::class);
    }

    // /**
    //  * @return SurveyField[] Returns an array of SurveyField objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SurveyField
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
