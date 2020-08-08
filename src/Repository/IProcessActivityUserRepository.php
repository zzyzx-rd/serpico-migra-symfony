<?php

namespace App\Repository;

use App\Entity\IProcessActivityUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IProcessActivityUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method IProcessActivityUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method IProcessActivityUser[]    findAll()
 * @method IProcessActivityUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IProcessActivityUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IProcessActivityUser::class);
    }

    // /**
    //  * @return IProcessActivityUser[] Returns an array of IProcessActivityUser objects
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
    public function findOneBySomeField($value): ?IProcessActivityUser
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
