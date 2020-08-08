<?php

namespace App\Repository;

use App\Entity\SurveyFieldParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SurveyFieldParameter|null find($id, $lockMode = null, $lockVersion = null)
 * @method SurveyFieldParameter|null findOneBy(array $criteria, array $orderBy = null)
 * @method SurveyFieldParameter[]    findAll()
 * @method SurveyFieldParameter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyFieldParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SurveyFieldParameter::class);
    }

    // /**
    //  * @return SurveyFieldParameter[] Returns an array of SurveyFieldParameter objects
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
    public function findOneBySomeField($value): ?SurveyFieldParameter
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
