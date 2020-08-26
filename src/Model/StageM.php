<?php


namespace App\Model;


use App\Entity\Participation;
use App\Entity\Stage;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

class StageM extends ModelEntity
{
    public function getSelfParticipations(Stage $stage): ArrayCollection
    {
        return $stage->participations->filter(function(Participation $p){
            return $p->getUser() == $this->currentUser;
        });
    }

    /**
     * @param Stage $stage
     * @return ArrayCollection|Participation[]
     */
    public function getParticipants(Stage $stage)
    {

        // Depends on whether current user is part of a team
        $eligibleParticipations = null;
        $participants = new ArrayCollection;
        $teams = [];

        $eligibleParticipations = count($stage->criteria) === 0 ? $stage->participations : $stage->criteria->first()->getParticipations();

        $myParticipations = $this->getSelfParticipations($stage);
        $myTeam = $myParticipations->count() === 0 ? null : $myParticipations->first()->getTeam();

        foreach ($eligibleParticipations as $eligibleParticipation) {
            $currentTeam = $eligibleParticipation->getTeam();
            if ($currentTeam === null || $currentTeam == $myTeam) {
                $participants->add($eligibleParticipation);
            } else if (!in_array($currentTeam, $teams, true)) {
                $participants->add($eligibleParticipation);
                $teams[] = $currentTeam;
            }
        }

        return $participants;
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

        if ($stage->getMasterUser() == $connectedUser && ($stage->getGraderParticipants() === null || !$stage->getGraderParticipants()->exists(static function(int $i, Participation $p){return $p->isLeader();}))) {
            return true;
        }

        return $stage->getGraderParticipants()->exists(
            static function (int $i, Participation $p) use ($connectedUser) { return $p->getUser() === $connectedUser && $p->isLeader(); }
        );
    }

    /**
     * @param Stage $stage
     * @return ArrayCollection|Participation[]
     */
    public function getGraderParticipants(Stage $stage)
    {
        return $this->getParticipants($stage)->matching(Criteria::create()->where(Criteria::expr()->neq("type", -1)));
    }

    /**
     * @param Stage $stage
     * @return ArrayCollection|Participation[]
     */
    public function getGradableParticipants(Stage $stage)
    {
        return $this->getParticipants($stage)->matching(Criteria::create()->where(Criteria::expr()->neq("type", 0)));
    }

    public function hasMinimumParticipationConfig(Stage $stage): bool
    {
        return $this->getGraderParticipants($stage)->count() > 0 && $this->getGradableParticipants($stage)->count() > 0;
    }

}