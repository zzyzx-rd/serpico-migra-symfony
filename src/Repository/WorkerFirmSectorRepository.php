<?php

namespace App\Repository;

use App\Entity\WorkerFirmSector;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkerFirmSector|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkerFirmSector|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkerFirmSector[]    findAll()
 * @method WorkerFirmSector[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkerFirmSectorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkerFirmSector::class);
    }

    // /**
    //  * @return WorkerFirmSector[] Returns an array of WorkerFirmSector objects
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
    public function findOneBySomeField($value): ?WorkerFirmSector
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
