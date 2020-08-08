<?php

namespace App\Repository;

use App\Entity\WorkerFirmLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkerFirmLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkerFirmLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkerFirmLocation[]    findAll()
 * @method WorkerFirmLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkerFirmLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkerFirmLocation::class);
    }

    // /**
    //  * @return WorkerFirmLocation[] Returns an array of WorkerFirmLocation objects
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
    public function findOneBySomeField($value): ?WorkerFirmLocation
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
