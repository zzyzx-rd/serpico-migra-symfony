<?php

namespace App\Repository;

use App\Entity\WorkerIndividual;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkerIndividual|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkerIndividual|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkerIndividual[]    findAll()
 * @method WorkerIndividual[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkerIndividualRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkerIndividual::class);
    }

    // /**
    //  * @return WorkerIndividual[] Returns an array of WorkerIndividual objects
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
    public function findOneBySomeField($value): ?WorkerIndividual
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
