<?php


namespace App\Model;


use App\Entity\ActivityUser;
use App\Entity\Stage;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

class StageM extends ModelEntity
{
    public function getSelfParticipations(Stage $stage): ArrayCollection
    {
        return $stage->participants->filter(function(ActivityUser $p){
            return $p->getUser() == $this->currentUser;
        });
    }

    /**
     * @param Stage $stage
     * @return Collection|ActivityUser[]
     */
    public function getUniqueParticipations(Stage $stage)
    {

        // Depends on whether current user is part of a team
        $eligibleParticipants = null;
        $uniqueParticipants = new ArrayCollection;
        $teams = [];

        $eligibleParticipants = count($stage->criteria) === 0 ? $stage->participants : $stage->criteria->first()->getParticipants();

        $myParticipations = $this->getSelfParticipations($stage);
        $myTeam = $myParticipations->count() === 0 ? null : $myParticipations->first()->getTeam();

        foreach ($eligibleParticipants as $eligibleParticipant) {
            $currentTeam = $eligibleParticipant->getTeam();
            if ($currentTeam === null || $currentTeam == $myTeam) {
                $uniqueParticipants->add($eligibleParticipant);
            } else if (!in_array($currentTeam, $teams, true)) {
                $uniqueParticipants->add($eligibleParticipant);
                $teams[] = $currentTeam;
            }
        }

        return $uniqueParticipants;
    }

    public function isModifiable(Stage $stage): bool
    {
        $connectedUser = $this->currentUser;
        $connectedUserRole = $connectedUser->getRole();
        $connectedUserId = $connectedUser->getId();

        if ($connectedUserRole === 4) {
            return true;
        }

        if ($stage->status >= 2) {
            return false;
        }

        if ($connectedUserRole === 1) {
            return true;
        }

        if ($stage->getMasterUser() == $connectedUser && ($stage->getUniqueGraderParticipations() === null || !$stage->getUniqueGraderParticipations()->exists(static function(int $i, ActivityUser $p){return $p->isLeader();}))) {
            return true;
        }

        return $stage->getUniqueGraderParticipations()->exists(
            static function (int $i, ActivityUser $p) use ($connectedUser) { return $p->getUser() === $connectedUser && $p->isLeader(); }
        );
    }

    /**
     * @param Stage $stage
     * @return Collection|ActivityUser[]
     */
    public function getUniqueGraderParticipations(Stage $stage)
    {
        return $this->getUniqueParticipations($stage)->matching(Criteria::create()->where(Criteria::expr()->neq("type", -1)));
    }

    /**
     * @param Stage $stage
     * @return Collection|ActivityUser[]
     */
    public function getUniqueGradableParticipations(Stage $stage)
    {
        return $this->getUniqueParticipations($stage)->matching(Criteria::create()->where(Criteria::expr()->neq("type", 0)));
    }

    public function hasMinimumParticipationConfig(Stage $stage): bool
    {
        return $this->getUniqueGraderParticipations($stage)->count() > 0 && $this->getUniqueGradableParticipations($stage)->count() > 0;
    }

}