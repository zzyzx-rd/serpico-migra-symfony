<?php

namespace App\Repository;

use App\Entity\Team;
use Controller\MasterController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function getRanking($app, $seriesType, $resType, $methodType, $fullAccess = false){
        $qb = MasterController::getEntityManager()->createQueryBuilder();
        if($fullAccess == true){

            switch ($seriesType) {
                case 'A':
                    return $qb->select('rkt')
                        ->from('Model\RankingTeam', 'rkt')
                        ->where("rkt.dType = '". $resType ."'")
                        ->andWhere("rkt.wType = '" . $methodType ."'")
                        ->andWhere($qb->expr()->isNull('rkt.stage'))
                        ->andWhere($qb->expr()->isNull('rkt.criterion'))
                        ->andWhere('rkt.team = ' . $this)
                        ->getQuery()
                        ->getResult();
                    break;
                case 'S':
                    return $qb->select('rkt')
                        ->from('Model\RankingTeam', 'rkt')
                        ->where("rkt.dType = '". $resType ."'")
                        ->andWhere("rkt.wType = '" . $methodType ."'")
                        ->andWhere($qb->expr()->isNotNull('rkt.stage'))
                        ->andWhere($qb->expr()->isNull('rkt.criterion'))
                        ->andWhere('rkt.team = ' . $this)
                        ->getQuery()
                        ->getResult();
                    break;
                case 'C':
                    return $qb->select('rkt')
                        ->from('Model\RankingTeam', 'rkt')
                        ->where("rkt.dType = '". $resType ."'")
                        ->andWhere("rkt.wType = '" . $methodType ."'")
                        ->andWhere($qb->expr()->isNotNull('rkt.criterion'))
                        ->andWhere('rkt.team = ' . $this)
                        ->getQuery()
                        ->getResult();
                default:
                    break;
            }
        } else {

            $lastReleasedParticipation = $qb->select('p')
                ->from('Model\Participation','p')
                ->where('p.team = '.$this)
                ->andWhere('p.status = 4')
                ->orderBy('p.inserted','DESC')
                ->getQuery()
                ->getResult();

            if($lastReleasedParticipation == null){
                return null;
            } else {
                switch ($seriesType) {
                    case 'A':
                        return
                            $qb->select('rth')
                                ->from('Model\RankingTeamHistory','rth')
                                ->where("rth.dType = '". $resType ."'")
                                ->andWhere("rth.wType = '" . $methodType ."'")
                                ->andWhere($qb->expr()->isNull('rth.stage'))
                                ->andWhere($qb->expr()->isNull('rth.criterion'))
                                ->andWhere('rth.team = '.$this)
                                ->andWhere('rth.activity = '.$lastReleasedParticipation[0]->getActivity())
                                ->getQuery()
                                ->getResult();
                        break;
                    case 'S':
                        return
                            $qb->select('rth')
                                ->from('Model\RankingTeamHistory','rth')
                                ->where("rth.dType = '". $resType ."'")
                                ->andWhere("rth.wType = '" . $methodType ."'")
                                ->andWhere($qb->expr()->isNull('rth.stage'))
                                ->andWhere($qb->expr()->isNull('rth.criterion'))
                                ->andWhere('rth.team = '.$this)
                                ->andWhere('rth.stage = '.$lastReleasedParticipation[0]->getStage())
                                ->getQuery()
                                ->getResult();
                        break;
                    case 'C':
                        return
                            $qb->select('rth')
                                ->from('Model\RankingTeamHistory','rth')
                                ->where("rth.dType = '". $resType ."'")
                                ->andWhere("rth.wType = '" . $methodType ."'")
                                ->andWhere($qb->expr()->isNull('rth.stage'))
                                ->andWhere($qb->expr()->isNull('rth.criterion'))
                                ->andWhere('rth.team = '.$this)
                                ->andWhere('rth.criterion = '.$lastReleasedParticipation[0]->getCriterion())
                                ->getQuery()
                                ->getResult();
                        break;
                    default:
                        break;
                }
            }
        }
    }
    // /**
    //  * @return Team[] Returns an array of Team objects
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
    public function findOneBySomeField($value): ?Team
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
