<?php

namespace App\Repository;

use App\Entity\TeamUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeamUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamUser[]    findAll()
 * @method TeamUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamUser::class);
    }

    // /**
    //  * @return TeamUser[] Returns an array of TeamUser objects
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
    public function findOneBySomeField($value): ?TeamUser
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
