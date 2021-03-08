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

class ActivityM extends ModelEntity {

    public function hasFeedbackExpired(Activity $activity): bool
    {
        return $this->getActiveModifiableStages($activity)->forAll(static function(int $i, Stage $s){
            return $s->hasFeedbackExpired() === 1;
        });
    }

    public function hasMinimumOutputConfig(Activity $activity): bool
    {
        return $this->getActiveModifiableStages($activity)->forAll(static function(int $i, Stage $s){
            return $s->hasMinimumOutputConfig() === 1;
        });
    }

    public function hasMinimumParticipationConfig(Activity $activity): bool
    {
        return $this->getActiveModifiableStages($activity)->forAll(function(int $i, Stage $s){
            $stageM = new StageM($this->em, $this->requestStack, $this->security);
            return $stageM->hasMinimumParticipationConfig($s) === 1;
        });
    }


    public function getActiveConfiguredStages(Activity $activity){
        return $this->getActiveModifiableStages($activity)->filter(static function(Stage $s){
            return $s->hasMinimumOutputConfig() === 1 && $s->hasMinimumParticipationConfig() === 1;
        });
    }


    /**
     * @param Activity $activity
     * @return ArrayCollection|Stage[]
     */
    public function getActiveModifiableStages(Activity $activity)
    {
        if ($this->currentUser === null) {
            return new ArrayCollection();
        }

        $activeStages = $activity->getActiveStages();
        $stageM = new StageM($this->em, $this->requestStack, $this->security);
        foreach ($activeStages as $activeStage) {
            if (!$stageM->isModifiable($activeStage)) {
                $activeStages->removeElement($activeStage);
            }
        }
        return $activeStages;
    }

    public function hasParticipant(Activity $activity, User $u)
    {
        return $activity->getParticipations()->exists(
            function (int $i, Participation $p) use ($u) { return $p->getUser() == $u; }
        );
    }


    public function getInitiator(Activity $activity): ?object
    {
        return $activity->getInitiator();
    }

    public function userCanEdit(Activity $activity, ?User $u): bool
    {
        if(!$u){return false;}
        $role = $u->getRole();

        /*if (
            $activity->status !== Activity::STATUS_ONGOING &&
            $activity->status !== Activity::STATUS_FUTURE &&
            $activity->status !== Activity::STATUS_INCOMPLETE &&
            $activity->status !== Activity::STATUS_AWAITING_CREATION
        ) {
            return false;
        }*/
        if ($role === 4) {
            return true;
        }
        if (! $this->getInitiator($activity)){
            return false;
        }
        if (($role === 1 || $this->getActiveModifiableStages($activity)) && ($this->getInitiator($activity)->getOrganization() == $u->getOrganization())) {
            return true;
        }
        return false;
    }

    public function userCanGiveOutput(Activity $activity,User $u): bool
    {
        return $activity->getStages()->exists(function(int $i,Stage $s) use ($u) {
            return $s->userCanGiveOutput($u);
        });
    }

    public function userCanViewResults(Activity $activity, User $u)
    {
        return $this->userCanSeeResults($activity, $u);
    }

    public function userCanSeeResults(Activity $activity, User $u)
    {

        if ($activity->getStatus() < ACTIVITY::STATUS_FINALIZED) {

            return false;
        }

        $role = $u->getRole();

        if ($role == 4) {
            return true;
        }

        if (!$activity->getStages()->exists(function (int $i,Stage $s) { return $s->getCriteria()->count(); })) {
            // no stage with criteria. if none of them got replies (as they are surveys)
            // results are not available


            if (

                $activity->getStages()->forAll(function (int $i,Stage $s) {
                    $survey = $s->getSurvey();
                    if (!$survey) {

                        return false;
                    }

                    return $survey->getAnswers()->isEmpty();
                })
            ) {


                return false;
            }
        }

        if ($role == 1) {
            if ($u->getOrganization() != $activity->getOrganization()) {
                return false;
            }
        }

        if ($this->hasParticipant($activity,$u)) {
            return true;
        }
        $subordinates = $u->getSubordinates()->toArray();
        $department = $u->getDepartment();
        $departmentUsers = $department ? $department->getUsers() : [];

        foreach ($this->participants as $p) {
            if ($p->getType() != ACTIVITY::STATUS_FUTURE) {
                if (in_array($p->getDirectUser(), $subordinates)) {
                    return true;
                } elseif (in_array($p->getDirectUser(), $departmentUsers) and $this->status == self::STATUS_PUBLISHED) {
                    return true;
                }
            }
        }

        return false;
    }

    public function isDeletable(Activity $activity): ?bool
    {
        $role = $this->currentUser->getRole();

        if ($role === 4) {
            return true;
        }

        if ($role === 3) {
            return false;
        }

        if ($activity->status >= 2) {
            return false;
        }
        if ($role === 1) {
            return true;
        }

        if (!$activity->isFinalized() && $activity->getMasterUser() == $this->currentUser()) {
            return true;
        }
        // Only case left : activity manager being leader of all stages
        $k = 0;
        foreach ($activity->stages as $stage) {
            foreach ($stage->getParticipants() as $participant) {
                if ($participant->getUser() == $this->currentUser && $participant->isLeader()) {
                    $k++;
                    break;
                }
            }
        }
        if ($k === $this->stages->count()) {return true;}
        return false;
    }


}