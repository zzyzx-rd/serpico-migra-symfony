<?php

namespace App\Repository;

use App\Entity\ProcessStage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProcessStage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProcessStage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProcessStage[]    findAll()
 * @method ProcessStage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProcessStageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProcessStage::class);
    }

    // /**
    //  * @return ProcessStage[] Returns an array of ProcessStage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProcessStage
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
