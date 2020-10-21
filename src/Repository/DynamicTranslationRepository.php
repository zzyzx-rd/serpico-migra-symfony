<?php

namespace App\Repository;

use App\Entity\DynamicTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DynamicTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method DynamicTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method DynamicTranslation[]    findAll()
 * @method DynamicTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DynamicTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DynamicTranslation::class);
    }

    // /**
    //  * @return DynamicTranslation[] Returns an array of DynamicTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DynamicTranslation
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
