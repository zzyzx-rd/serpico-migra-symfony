<?php

namespace App\Repository;

use App\Entity\RankingTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RankingTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method RankingTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method RankingTeam[]    findAll()
 * @method RankingTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RankingTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RankingTeam::class);
    }

    // /**
    //  * @return RankingTeam[] Returns an array of RankingTeam objects
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
    public function findOneBySomeField($value): ?RankingTeam
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
