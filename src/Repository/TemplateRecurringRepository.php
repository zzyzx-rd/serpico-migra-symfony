<?php

namespace App\Repository;

use App\Entity\TemplateRecurring;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TemplateRecurring|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateRecurring|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateRecurring[]    findAll()
 * @method TemplateRecurring[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateRecurringRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateRecurring::class);
    }

    // /**
    //  * @return TemplateRecurring[] Returns an array of TemplateRecurring objects
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
    public function findOneBySomeField($value): ?TemplateRecurring
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
