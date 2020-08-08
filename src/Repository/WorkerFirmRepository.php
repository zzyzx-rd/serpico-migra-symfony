<?php

namespace App\Repository;

use App\Entity\WorkerFirm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkerFirm|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkerFirm|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkerFirm[]    findAll()
 * @method WorkerFirm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkerFirmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkerFirm::class);
    }

    // /**
    //  * @return WorkerFirm[] Returns an array of WorkerFirm objects
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
    public function findOneBySomeField($value): ?WorkerFirm
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
