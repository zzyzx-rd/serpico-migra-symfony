<?php

namespace App\Repository;

use App\Entity\IProcessParticipation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IProcessParticipation|null find($id, $lockMode = null, $lockVersion = null)
 * @method IProcessParticipation|null findOneBy(array $criteria, array $orderBy = null)
 * @method IProcessParticipation[]    findAll()
 * @method IProcessParticipation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IProcessParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IProcessParticipation::class);
    }

    // /**
    //  * @return IProcessParticipation[] Returns an array of IProcessParticipation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IProcessParticipation
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
