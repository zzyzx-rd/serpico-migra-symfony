<?php

namespace App\Repository;

use App\Entity\UserMaster;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserMaster|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserMaster|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserMaster[]    findAll()
 * @method UserMaster[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserMasterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserMaster::class);
    }

    // /**
    //  * @return Weight[] Returns an array of Weight objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Weight
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
