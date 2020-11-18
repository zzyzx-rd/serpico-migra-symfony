<?php

namespace App\Repository;

use App\Entity\DynamicTranslation;
use App\Entity\EventGroup;
use App\Entity\Organization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InstitutionProcess|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstitutionProcess|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstitutionProcess[]    findAll()
 * @method InstitutionProcess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventGroup::class);
    }

    
    public function getDTrans(EventGroup $eventGroup, $locale, Organization $organization){
        $translatables =  $this->_em->getRepository(DynamicTranslation::class)->findBy(['entity' => 'EventGroupName', 'entityId' => $eventGroup->getEventGroupName()->getId(), 'entityProp' => 'name', 'organization' => [null, $organization]], ['organization' => 'ASC']);
        if(!$translatables){
            return $eventGroup->getEventGroupName()->getName();
        } else {
            /** @var DynamicTranslation */
            $translatable = sizeof($translatables) > 1 ? $translatables[1] : $translatables[0];                
            $translatable->locale = strtoupper($locale);
            return $translatable->getDynTrans();
        }
    }

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
