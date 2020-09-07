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
     * @return ArrayCollection|Participation[]
     */
    public function getGraderParticipants()
    {
        return $this->getParticipants()->matching(Criteria::create()->where(Criteria::expr()->neq("type", -1)));
    }
    /**
     * @param Stage $stage
     * @return ArrayCollection|Participation[]
     */

   /* public function getParticipants(Stage $stage)
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
    }*/
    /**
     * @return ArrayCollection|Participation[]
     */
    public function getIndivParticipants()
    {
        $indivParticipants = new ArrayCollection;
        $myParticipations = $this->getSelfParticipations();
        $myTeam = $myParticipations->count() === 0 ? null : $myParticipations->first()->getTeam();

        foreach ($this->getParticipants() as $participant) {
            $team = $participant->getTeam();
            if ($team === null || $team == $myTeam) {
                $indivParticipants->add($participant);
            }
        };
        return count($indivParticipants) > 0 ? $indivParticipants : null;
    }
    /**
     * @return ArrayCollection|Participation[]
     */
    public function getIntParticipants(){
        return $this->getParticipants()->filter(function(Participation $p){
            return $p->getTeam() === null && $p->getUser()->getOrganization() ==
                $this->currentUser->getOrganization();
        });
    }
    /**
     * @return ArrayCollection|Participation[]
     */
    public function getGradableParticipants()
    {
        return $this->getParticipants()->matching(Criteria::create()->where(Criteria::expr()->neq("type", 0)));
    }

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getExtParticipants()
    {
        return $this->getParticipants()->filter(function(Participation $p){
            return $p->getTeam() === null && $p->getUser()->getOrganization() != $this->currentUser->getOrganization();
        });
    }
    /**
     * @return ArrayCollection|Participation[]
     */
    public function getTeamParticipants()
    {
        $myParticipations = $this->getSelfParticipations();
        $myTeam = $myParticipations->count() === 0 ? null : $myParticipations->first()->getTeam();
        return $this->getParticipants()->filter(static function(Participation $p) use ($myTeam){
            return $p->getTeam() !== null && $p->getTeam() != $myTeam;
        });
    }
    /**
     * @return ArrayCollection|Participation[]
     */
    public function getUserGradableParticipants()
    {
        // We get all non-third party user participations, except those of people who are part of a team we don't belong to
        if ($this->mode == STAGE::GRADED_STAGE) {
            return null;
        } else {

            $userGradableParticipants = new ArrayCollection;

            $unorderedGradableParticipants = $this->getIndivParticipants()->filter(function(Participation $p){
                return $p->getType() != Participation::PARTICIPATION_THIRD_PARTY;
            });

            foreach($unorderedGradableParticipants as $unorderedGradableParticipant){
                $userGradableParticipants->add($unorderedGradableParticipant);
            }

            return $userGradableParticipants;
        }
    }

    public function addTeamGradableParticipation(Participation $participant): Stage
    {
        $this->participations->add($participant);
        return $this;
    }

    public function removeTeamGradableParticipation(Participation $participant): Stage
    {
        $this->participations->removeElement($participant);
        return $this;
    }



    public function addGradableParticipant(Participation $participant): Stage
    {
        if ($this->participations->exists(function (Participation $u) use ($participant) {
            return $u->getUser()->getId() === $participant->getUser()->getId();
        })) {
            return $this;
        }

        foreach ($this->criteria as $criterion) {
            $criterion->addParticipation($participant);
            $participant->setCriterion($criterion)->setStage($this);
        }
        return $this;
    }

    public function removeGradableParticipant(Participation $participant): Stage
    {
        foreach ($this->criteria as $criterion) {
            $criterion->participants->removeElement($participant);
        }
        return $this;
    }

    /**
     * Get distinct participants, independant from current user
     * @return ArrayCollection|Participation[]
     */
    public function getIndependantParticipants()
    {
        $eligibleParticipations = null;
        $independantParticipants = new ArrayCollection;
        $teams = [];

        $eligibleParticipations = count($this->criteria) === 0 ? $this->participations : $this->criteria->first()->getParticipations();

        foreach ($eligibleParticipations as $eligibleParticipation) {
            $team = $eligibleParticipation->getTeam();
            if ($team === null) {
                $independantParticipants->add($eligibleParticipation);
            } else {
                if (!in_array($team, $teams)) {
                    $independantParticipants->add($eligibleParticipation);
                    $teams[] = $team;
                }
            }
        }
        return $independantParticipants;
    }
    public function getParticipants(): ArrayCollection
    {

        // Depends on whether current user is part of a team
        $eligibleParticipations = null;
        $participants = new ArrayCollection;
        $teams = [];

        $eligibleParticipations = count($this->criteria) === 0 ? $this->participations : $this->criteria->first()->getParticipations();

        $myParticipations = $this->getSelfParticipations();
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
    public function getGradingParticipants()
    {
        return count($this->getParticipants()->matching(
            Criteria::create()->where(Criteria::expr()->neq("type", -1))
        ));
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
    /*
    public function getGraderParticipants(Stage $stage)
    {
        return $this->getParticipants($stage)->matching(Criteria::create()->where(Criteria::expr()->neq("type", -1)));
    }
*/
    /**
     * @param Stage $stage
     * @return ArrayCollection|Participation[]
     */
    /*
    public function getGradableParticipants(Stage $stage)
    {
        return $this->getParticipants($stage)->matching(Criteria::create()->where(Criteria::expr()->neq("type", 0)));
    }
*/
    public function hasMinimumParticipationConfig(Stage $stage): bool
    {
        return $this->getGraderParticipants($stage)->count() > 0 && $this->getGradableParticipants($stage)->count() > 0;
    }

}
