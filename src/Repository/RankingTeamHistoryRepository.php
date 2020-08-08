<?php

namespace App\Repository;

use App\Entity\RankingTeamHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RankingTeamHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method RankingTeamHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method RankingTeamHistory[]    findAll()
 * @method RankingTeamHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RankingTeamHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RankingTeamHistory::class);
    }

    // /**
    //  * @return RankingTeamHistory[] Returns an array of RankingTeamHistory objects
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
    public function findOneBySomeField($value): ?RankingTeamHistory
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
