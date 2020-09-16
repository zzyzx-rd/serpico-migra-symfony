<?php


namespace App\Repository;



use App\Entity\Output;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Output|null find($id, $lockMode = null, $lockVersion = null)
 * @method Output|null findOneBy(array $criteria, array $orderBy = null)
 * @method Output[]    findAll()
 * @method Output[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutputRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Output::class);
    }
}
