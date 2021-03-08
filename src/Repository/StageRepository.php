<?php

namespace App\Repository;

use App\Entity\Participation;
use App\Entity\Stage;
use App\Entity\User;
use App\Entity\UserMaster;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stage[]    findAll()
 * @method Stage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stage::class);
    }

    // /**
    //  * @return Stage[] Returns an array of Stage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Stage
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
      * @return Stage[] Returns an array of Stage objects
      */
    public function findExternalStages(User $u)
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('App\Entity\Participation','p','WITH','p.stage = s.id')
            ->where('p.user = :user')
            ->andWhere('s.organization != :org')
            ->setParameter('user', $u)
            ->setParameter('org', $u->getOrganization())
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return ArrayCollection|Stage[]
    */
    public function getAccessibleStages(User $u, int $startingTS = null, int $endingTS = null)
    {
        switch($u->getRole()){
            case User::ROLE_ROOT:
            case User::ROLE_SUPER_ADMIN:
            case User::ROLE_ADMIN:
                $otherThanFollowingStages = array_merge(
                    $u->getOrganization()->getStages()->getValues(),
                    $this->findExternalStages($u)
                );  
                break;
            case User::ROLE_COLLAB:
                $otherThanFollowingStages = array_merge(
                    $u->getOrganization()->getStages()->filter(fn(Stage $s) => $s->getParticipations()->exists(fn(int $i, Participation $p) => $p->getUser() == $u))->getValues(),
                    $this->findExternalStages($u)
                );
                break;
            default:
                break;
        }

        $potentiallyAccessibleStages = new ArrayCollection(
            array_unique(
                array_merge(
                    $otherThanFollowingStages, 
                    $u->getMasterings()->filter(fn(UserMaster $m) => $m->getStage() != null && $m->getProperty() == 'followableStatus' && $m->getType() >= UserMaster::ADDED)->map(fn(UserMaster $m) => $m->getStage())->getValues()
                )
            , SORT_REGULAR)
        );
    
        if($startingTS && $endingTS){
            $accessibleStages = $potentiallyAccessibleStages->filter(fn(Stage $s) => 
                $startingTS < $s->getStartdate()->getTimestamp() && ($s->getEnddate() ?: new DateTime())->getTimestamp() < $endingTS
            );
            
        } else {
            $accessibleStages = $potentiallyAccessibleStages;
        }
                
        return $accessibleStages;
    }
}
