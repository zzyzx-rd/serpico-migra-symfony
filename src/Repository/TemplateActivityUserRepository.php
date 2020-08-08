<?php

namespace App\Repository;

use App\Entity\TemplateActivityUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TemplateActivityUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateActivityUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateActivityUser[]    findAll()
 * @method TemplateActivityUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateActivityUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateActivityUser::class);
    }

    // /**
    //  * @return TemplateActivityUser[] Returns an array of TemplateActivityUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TemplateActivityUser
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
