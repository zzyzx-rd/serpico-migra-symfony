<?php

namespace App\Repository;

use App\Entity\InsitutionProcess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InsitutionProcess|null find($id, $lockMode = null, $lockVersion = null)
 * @method InsitutionProcess|null findOneBy(array $criteria, array $orderBy = null)
 * @method InsitutionProcess[]    findAll()
 * @method InsitutionProcess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InsitutionProcessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InsitutionProcess::class);
    }

    // /**
    //  * @return InsitutionProcess[] Returns an array of InsitutionProcess objects
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
    public function findOneBySomeField($value): ?InsitutionProcess
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
