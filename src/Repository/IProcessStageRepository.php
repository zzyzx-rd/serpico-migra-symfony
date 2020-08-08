<?php

namespace App\Repository;

use App\Entity\IProcessStage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IProcessStage|null find($id, $lockMode = null, $lockVersion = null)
 * @method IProcessStage|null findOneBy(array $criteria, array $orderBy = null)
 * @method IProcessStage[]    findAll()
 * @method IProcessStage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IProcessStageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IProcessStage::class);
    }

    // /**
    //  * @return IProcessStage[] Returns an array of IProcessStage objects
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
    public function findOneBySomeField($value): ?IProcessStage
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
