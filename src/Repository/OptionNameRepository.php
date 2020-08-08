<?php

namespace App\Repository;

use App\Entity\OptionName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OptionName|null find($id, $lockMode = null, $lockVersion = null)
 * @method OptionName|null findOneBy(array $criteria, array $orderBy = null)
 * @method OptionName[]    findAll()
 * @method OptionName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OptionName::class);
    }

    // /**
    //  * @return OptionName[] Returns an array of OptionName objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OptionName
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
