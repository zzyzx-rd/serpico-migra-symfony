<?php

namespace App\Repository;

use App\Entity\UserGlobal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserGlobal|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGlobal|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGlobal[]    findAll()
 * @method UserGlobal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGlobalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGlobal::class);
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
