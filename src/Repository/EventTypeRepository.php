<?php

namespace App\Repository;

use App\Entity\DynamicTranslation;
use App\Entity\EventType;
use App\Entity\Organization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InstitutionProcess|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitutionProcess|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitutionProcess[]    findAll()
 * @method InstitutionProcess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventType::class);
    }

    public function getDTrans(EventType $eventType, $locale, Organization $organization){
        $translatables = $this->_em->getRepository(DynamicTranslation::class)->findBy(['entity' => ['EventType', 'EventName'], 'entityId' => [$eventType->getId(), $eventType->getEName()->getId()], 'entityProp' => 'name', 'organization' => [null, $organization]], ['organization' => 'ASC']);
        if(!$translatables){
            return $eventType->getEName()->getName();
        } else {
            /** @var DynamicTranslation */
            $translatable = sizeof($translatables) > 1 ? $translatables[1] : $translatables[0];                
            $translatable->locale = strtoupper($locale);
            return $translatable->getDynTrans();
        }
    }

    // /**
    //  * @return InstitutionProcess[] Returns an array of InstitutionProcess objects
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
    public function findOneBySomeField($value): ?InstitutionProcess
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
