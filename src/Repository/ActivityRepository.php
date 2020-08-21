<?php

namespace App\Repository;

use App\Entity\Activity;
use App\Entity\InstitutionProcess;
use App\Entity\Organization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }
    public const ACTIVITY_STATUS = [
        Activity::STATUS_CANCELLED,
        Activity::STATUS_REJECTED,
        Activity::STATUS_REQUESTED,
        Activity::STATUS_AWAITING_CREATION,
        Activity::STATUS_INCOMPLETE,
        Activity::STATUS_FUTURE,
        Activity::STATUS_ONGOING,
        Activity::STATUS_FINALIZED,
        Activity::STATUS_PUBLISHED,
    ];

    /**
     * Retrive activities not bound to a process.
     *
     * @param Organization $org
     * @return Activity[]
     */
    public function getOrgOrphanActivities(Organization $org): array
    {
        $return = [];
        foreach (self::ACTIVITY_STATUS as $s) {
            $return[$s] = $this->createQueryBuilder('a')
                ->where('a.institutionProcess is null')
                ->andWhere('a.organization = :org')
                ->andWhere('a.status = :status')
                ->orderBy('a.name')
                ->getQuery()
                ->execute([
                    'org'    => $org,
                    'status' => $s,
                ]);
        }

        return $return;
    }

    /**
     * Retrieve activities bound to a head (has no parent) process.
     *
     * @param Organization $org an organization
     * @return Activity[]
     */
    public function getOrgProcessActivities(Organization $org)
    {
        foreach (self::ACTIVITY_STATUS as $s) {
            /** @var Activity[] */
            $activities = $this->createQueryBuilder('a')
                ->innerJoin(InstitutionProcess::class, 'ip')
                ->where('a.institutionProcess is not null')
                ->andWhere('ip.parent is null')
                ->andWhere('a.organization = :org')
                ->andWhere('a.status = :status')
                ->orderBy('a.name')
                ->getQuery()
                ->execute([
                    'org'    => $org,
                    'status' => $s,
                ]);

            $return[$s] = [];
            foreach ($activities as $activity) {
                $institutionProcess = $activity->getInstitutionProcess()->getName();
                $return[$s][$institutionProcess][] = $activity;
            }
        }

        return $return;
    }
    // /**
    //  * @return Activity[] Returns an array of Activity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Activity
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
