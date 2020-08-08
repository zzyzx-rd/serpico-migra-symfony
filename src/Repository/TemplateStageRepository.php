<?php

namespace App\Repository;

use App\Entity\TemplateStage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TemplateStage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateStage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateStage[]    findAll()
 * @method TemplateStage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateStageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateStage::class);
    }

    // /**
    //  * @return TemplateStage[] Returns an array of TemplateStage objects
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
    public function findOneBySomeField($value): ?TemplateStage
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
