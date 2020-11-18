<?php




namespace App\Repository;


use App\Entity\OrganizationPaymentMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrganizationPaymentMethod|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganizationPaymentMethod|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganizationPaymentMethod[]    findAll()
 * @method OrganizationPaymentMethod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganizationPaymentMethodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganizationPaymentMethod::class);
    }

    // /**
    //  * @return OptionName[] Returns an array of OptionName objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OptionName
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
