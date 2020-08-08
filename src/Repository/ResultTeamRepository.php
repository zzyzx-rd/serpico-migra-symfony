<?php

namespace App\Repository;

use App\Entity\ResultTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResultTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResultTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResultTeam[]    findAll()
 * @method ResultTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResultTeam::class);
    }

    // /**
    //  * @return ResultTeam[] Returns an array of ResultTeam objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ResultTeam
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
