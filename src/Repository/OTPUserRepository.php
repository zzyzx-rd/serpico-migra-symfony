<?php

namespace App\Repository;

use App\Entity\OTPUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OTPUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method OTPUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method OTPUser[]    findAll()
 * @method OTPUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OTPUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OTPUser::class);
    }

    // /**
    //  * @return OTPUser[] Returns an array of OTPUser objects
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
    public function findOneBySomeField($value): ?OTPUser
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
