<?php
namespace App\Model;
use App\Entity\Activity;
use App\Entity\Participation;
use App\Entity\Stage;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Ranking;

class UserM extends ModelEntity {
    
    public function getRanking(User $user, $seriesType, $resType, $methodType, $fullAccess = false)
    {
        $qb = $this->em->createQueryBuilder();
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
                ->where('au.usrId = ' . $user->getId())
                ->andWhere('au.status = 4')
                ->orderBy('au.inserted', 'DESC')
                ->getQuery()
                ->getResult();

            if ($lastReleasedParticipation === null) {
                return null;
            }

            switch ($seriesType) {
                case 'A':
                    return
                    $qb->select('rkh')
                        ->from('Entity\RankingHistory', 'rkh')
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
                        ->from('Entity\RankingHistory', 'rkh')
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
                        ->from('Entity\RankingHistory', 'rkh')
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

    public function getNbCompletedActivities(User $user){

        $connection = $this->em->getConnection();
        $sql =
            'SELECT par_id
         FROM participation
         INNER JOIN activity ON participation.activity_act_id = activity.act_id
         WHERE activity.act_status >= :status
         AND participation.user_usr_id = :usrId GROUP BY participation.activity_act_id';

        $pdoStatement = $connection->prepare($sql);
        $pdoStatement->bindValue(':status', 2);
        $pdoStatement->bindValue(':usrId', $user->getId());
        $pdoStatement->execute();
        return count($pdoStatement->fetchAll());

    }

    public function getNbScheduledActivities(User $user){

        $connection = $this->em->getConnection();
        $sql =
            'SELECT par_id
         FROM participation
         INNER JOIN activity ON participation.activity_act_id = activity.act_id
         WHERE activity.act_status IN (:status_1, :status_2)
         AND participation.user_usr_id = :usrId
         GROUP BY participation.activity_act_id';

        $pdoStatement = $connection->prepare($sql);
        $pdoStatement->bindValue(':status_1', 0);
        $pdoStatement->bindValue(':status_2', 1);
        $pdoStatement->bindValue(':usrId', $user->getId());
        $pdoStatement->execute();
        return count($pdoStatement->fetchAll());

    }


}