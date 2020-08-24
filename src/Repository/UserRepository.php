<?php

namespace App\Repository;

use App\Entity\Participation;
use App\Entity\Criterion;
use App\Entity\CriterionName;
use App\Entity\Organization;
use App\Entity\Result;
use App\Entity\Stage;
use App\Entity\Target;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\{ServiceEntityRepository};
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Ranking;
use App\Entity\RankingHistory;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByOrganization(Organization $organization): ArrayCollection
    {
        return new ArrayCollection($this->_em->getRepository(User::class)->findBy(["organization" => $organization->id]));
    }

    public function findUserTopPerformingCriteria(User $u, int $precision = 0, int $count = 0)
    {
        $usrPos = $u->getPosition();
        $usrDpt = $u->getDepartment();
        $usrOrg = $u->getOrganization();

        $targetMapCallback = function (Target $t) use ($precision) {
            return [
                'cnId' => $t->getCName()->getId(),
                'sign' => $t->getSign(),
                'value' => number_format($t->getValue() * 100, $precision)
            ];
        };

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()
            ->select('cn as criterion, avg(r.weightedRelativeResult * 100) as performance, count(distinct(r)) as count')
            ->from(Result::class, 'r')
            ->innerJoin(Stage::class, 's',          'with', 's  = r.stage')
            ->innerJoin(Participation::class, 'p',   'with', 's  = p.stage')
            ->innerJoin(Criterion::class, 'c',      'with', 'c  = r.criterion')
            ->innerJoin(CriterionName::class, 'cn', 'with', 'cn = c.cName')
            ->where('s.status = :status')
            ->andWhere('r.user_usr = :user')
            ->andWhere('p.type != 0')
            ->andWhere('r.weightedRelativeResult is not null')
            ->groupBy('cn')
            ->orderBy('count, r.weightedRelativeResult', 'desc');

        if ($count > 0) {
            $qb->setMaxResults($count);
        }

        $result = $qb->getQuery()->execute([ 'status' => Stage::STAGE_PUBLISHED, 'user' => $u ]);

        $indivTargets = $u->getTargets()->map($targetMapCallback)->getValues();
        $posTargets = $usrPos ? $usrPos->getTargets()->map($targetMapCallback)->getValues() : [];
        $dptTargets = $usrDpt ? $usrDpt->getTargets()->map($targetMapCallback)->getValues() : [];
        $orgTargets = $usrOrg->getTargets()->map($targetMapCallback)->getValues();

        return array_map(
            function (array $e) use ($precision, $indivTargets, $posTargets, $dptTargets, $orgTargets) {
                /** @var CriterionName */
                $cName = $e['criterion'];
                $targetFilterCallback = function (array $t) use ($cName) {
                    return $t['cnId'] === $cName->getId();
                };

                $indiv = array_filter($indivTargets, $targetFilterCallback);
                $pos = array_filter($posTargets, $targetFilterCallback);
                $dpt = array_filter($dptTargets, $targetFilterCallback);
                $org = array_filter($orgTargets, $targetFilterCallback);

                return [
                    'criterion' => $cName,
                    'performance' => number_format($e['performance'], $precision),
                    'count' => $e['count'],
                    'target' => [
                        'indiv' => $indiv ? array_pop($indiv) : null,
                        'pos' => $pos ? array_pop($pos) : null,
                        'dpt' => $dpt ? array_pop($dpt) : null,
                        'org' => $org ? array_pop($org) : null
                    ]
                ];
            }, $result
        );
    }

    public function findUserUngradedTargets(User $u, int $precision = 0, int $count = 0)
    {
        $usrId = $u->getId();
        $usrPos = $u->getPosition();
        $usrDpt = $u->getDepartment();
        $usrOrg = $u->getOrganization();
        $orgId = $usrOrg->getId();

        $em = $this->getEntityManager();

        /** Query for retrieving criteria associated with grades */
        $q1 = $em->createQuery('select identity(g.criterion) from App\Entity\Grade g');

        // Query for retrieving criterion names associated with criteria which have grades
        $q2 = $em->createQueryBuilder();
        $q2->select('identity(c.cName)')->from(Criterion::class, 'c')
            ->innerJoin(Participation::class, 'p', 'with', 'c = p.criterion')
            ->where($q2->expr()->in('c.id', $q1->getDQL()))
            ->andWhere("p.id = $usrId");

        // Query for retrieving targets not associated with criterion names above
        $q3 = $em->createQueryBuilder();
        $q3->select('t')->from(Target::class, 't')
            ->where($q3->expr()->notIn('t.cName', $q2->getDQL()));

        $targetOr = $q3->expr()->orX("t.user = $usrId or t.organization = $orgId");
        $nullableProperties = [ 'position' => $usrPos, 'department' => $usrDpt ];
        foreach ($nullableProperties as $p => $v) {
            if ($v) {
                $id = $v->getId();
                $targetOr->add("t.$p = $id");
            }
        }
        $q3->andWhere($targetOr);

        if ($count > 0) {
            $q3->setMaxResults($count);
        }

        return array_map(static function (Target $t) use ($precision) {
            $type = null;
            if ($t->getUser()) {
                $type = 'indiv';
            } elseif ($t->getPosition()) {
                $type = 'pos';
            } elseif ($t->getDepartment()) {
                $type = 'dpt';
            } elseif ($t->getOrganization()) {
                $type = 'org';
            }

            return [
                'criterion' => $t->getCName()->getName(),
                'type' => $type,
                'value' => number_format($t->getValue() * 100, $precision),
                'sign' => $t->getSign()
            ];
        }, $q3->getQuery()->execute());
    }

    public function getRanking($seriesType, $resType, $methodType, $fullAccess = false, User $user)
    {
        $qb = $this->_em->createQueryBuilder();
        if ($fullAccess) {

            switch ($seriesType) {
                case 'A':
                    return $qb->select('rnk')
                        ->from(Ranking::class, 'rnk')
                        ->where("rnk.dType = '" . $resType . "'")
                        ->andWhere("rnk.wType = '" . $methodType . "'")
                        ->andWhere($qb->expr()->isNull('rnk.stage'))
                        ->andWhere($qb->expr()->isNull('rnk.criterion'))
                        ->andWhere('rnk.user = ' . $user)
                        ->getQuery()
                        ->getResult();
                    break;
                case 'S':
                    return $qb->select('rnk')
                        ->from(Ranking::class, 'rnk')
                        ->where("rnk.dType = '" . $resType . "'")
                        ->andWhere("rnk.wType = '" . $methodType . "'")
                        ->andWhere($qb->expr()->isNotNull('rnk.stage'))
                        ->andWhere($qb->expr()->isNull('rnk.criterion'))
                        ->andWhere('rnk.user = ' . $user)
                        ->getQuery()
                        ->getResult();
                    break;
                case 'C':
                    return $qb->select('rnk')
                        ->from(Ranking::class, 'rnk')
                        ->where("rnk.dType = '" . $resType . "'")
                        ->andWhere("rnk.wType = '" . $methodType . "'")
                        ->andWhere($qb->expr()->isNotNull('rnk.criterion'))
                        ->andWhere('rnk.user = ' . $user)
                        ->getQuery()
                        ->getResult();
                default:
                    break;
            }
        } else {

            $lastReleasedParticipation = $qb->select('au')
                ->from(Participation::class, 'au')
                ->where('au.user_usr = ' . $user)
                ->andWhere('au.status = 4')
                ->orderBy('au.inserted', 'DESC')
                ->getQuery()
                ->getResult();

            if ($lastReleasedParticipation == null) {
                return null;
            } else {
                switch ($seriesType) {
                    case 'A':
                        return
                            $qb->select('rkh')
                                ->from(RankingHistory::class, 'rkh')
                                ->where("rkh.dType = '" . $resType . "'")
                                ->andWhere("rkh.wType = '" . $methodType . "'")
                                ->andWhere($qb->expr()->isNull('rkh.stage'))
                                ->andWhere($qb->expr()->isNull('rkh.criterion'))
                                ->andWhere('rkh.usrId = ' . $user->getId())
                                ->andWhere('rkh.activity = ' . $lastReleasedParticipation[0]->getActivity())
                                ->getQuery()
                                ->getResult();
                        break;
                    case 'S':
                        return
                            $qb->select('rkh')
                                ->from(RankingHistory::class, 'rkh')
                                ->where("rkh.dType = '" . $resType . "'")
                                ->andWhere("rkh.wType = '" . $methodType . "'")
                                ->andWhere($qb->expr()->isNull('rkh.stage'))
                                ->andWhere($qb->expr()->isNull('rkh.criterion'))
                                ->andWhere('rkh.usrId = ' . $user->getId())
                                ->andWhere('rkh.stage = ' . $lastReleasedParticipation[0]->getStage())
                                ->getQuery()
                                ->getResult();
                        break;
                    case 'C':
                        return
                            $qb->select('rkh')
                                ->from(RankingHistory::class, 'rkh')
                                ->where("rkh.dType = '" . $resType . "'")
                                ->andWhere("rkh.wType = '" . $methodType . "'")
                                ->andWhere($qb->expr()->isNull('rkh.stage'))
                                ->andWhere($qb->expr()->isNull('rkh.criterion'))
                                ->andWhere('rkh.usrId = ' . $user->getId())
                                ->andWhere('rkh.criterion = ' . $lastReleasedParticipation[0]->getCriterion())
                                ->getQuery()
                                ->getResult();
                        break;
                    default:
                        break;
                }
            }
        }
    }

    /**
     * @param User $user
     * @return User[]
     */
    public function getSubordinates(User $user)
    {
        return $this->_em->getRepository(User::class)->findBy(['superior' => $user->getId()]);
    }

    /**
     * @param User $user
     * @return User[]
     */
    public function subordinatesOf(User $user): array
    {
        $subordinates = $this->getSubordinates($user);
        $return = $subordinates;

        foreach ($subordinates as $user) {
            $return = array_merge($return, $this->subordinatesOf($user));
        }

        return $return;
    }

    public function getExternalActivities(Organization $organization = null, User $user)
    {
        $externalActivities = new ArrayCollection;
        $userParticipations = $this->_em->getRepository(Participation::class)->findBy(['id' => $user->getId()]);
        foreach ($userParticipations as $userParticipation) {
            $activity = $userParticipation->getStage()->getActivity();
            if (($organization != null && $activity->getOrganization() == $organization) || ($organization == null && $activity->getOrganization() != $user->getOrganization())) {
                if (!$externalActivities->contains($activity)) {
                    $externalActivities->add($activity);
                }
            }
        }
        return $externalActivities;
    }
    /**
     * @return User[]
     */
    public function usersWithPicture(): array
    {
        $qb = $this->createQueryBuilder('u')->where('u.picture is not null');

        return $qb->getQuery()->getResult();
    }    
}
