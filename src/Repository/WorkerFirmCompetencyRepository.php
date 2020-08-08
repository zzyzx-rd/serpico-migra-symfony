<?php

namespace App\Repository;

use App\Entity\WorkerFirmCompetency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkerFirmCompetency|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkerFirmCompetency|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkerFirmCompetency[]    findAll()
 * @method WorkerFirmCompetency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkerFirmCompetencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkerFirmCompetency::class);
    }

    // /**
    //  * @return WorkerFirmCompetency[] Returns an array of WorkerFirmCompetency objects
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
    public function findOneBySomeField($value): ?WorkerFirmCompetency
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
