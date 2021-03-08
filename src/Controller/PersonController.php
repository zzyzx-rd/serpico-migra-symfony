<?php


namespace App\Controller;

use App\Entity\Client;
use App\Entity\ExternalUser;
use App\Entity\Organization;
use App\Entity\User;
use App\Entity\UserGlobal;
use App\Entity\WorkerFirm;
use App\Form\Type\ClientType;
use App\Form\Type\ExternalUserType;
use App\Form\Type\OrganizationElementType;
use App\Form\Type\UserType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends MasterController
{
    /**
     * @param Request $request
     * @Route("/settings/{entity}/{elmtId}/overview", name="elementOverview")
     */
    public function elementOverviewAction($elmtType, $elmtId, $orgEnabledCreatingUser = false) {
        $em          = $this->em;
        $repoP      = $em->getRepository(Participation::class);
        $repoG       = $em->getRepository(Grade::class);
        $repoT       = $em->getRepository(Team::class);
        $repoO       = $em->getRepository(Organization::class);
        $repoRP      = $em->getRepository(ResultProject::class);
        $repoS       = $em->getRepository(Stage::class);
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $currentUserOrganization = $repoO->find($currentUser->getOrganization());

        if ($elmtType == 'user') {
            $repoU                     = $em->getRepository(User::class);
            $repoR                     = $em->getRepository(Result::class);
            $user                      = $repoU->find($elmtId);
            $organization              = $repoO->find($user->getOrganization());
            $element                   = $user;
            $resultParticipantProperty = 'usrId';
            $resultParticipantValue    = $elmtId;
            $gradedElmtId              = 'gradedUsrId';
        } else {
            $repoR                     = $em->getRepository(ResultTeam::class);
            $team                      = $repoT->find($elmtId);
            $organization              = $team->getOrganization();
            $element                   = $team;
            $resultParticipantProperty = 'team';
            $resultParticipantValue    = $team;
            $gradedElmtId              = 'gradedTeaId';
        }

        $hasPageAccess = true;
        if ($elmtType == 'user') {
            if (($currentUser->getRole() != 4 && $currentUser->getRole() != 1 && !($user->getDepartment($app)->getMasterUser() == $currentUser) && ($currentUser->getOrgId() != $organization->getId()) || ($orgEnabledCreatingUser && $currentUser->isEnabledCreatingUser()))) {
                return $this->render('errors/403.html.twig');
            }
        } else {
            foreach ($team->getMembers() as $member) {
                $teamUsrIds[] = $member->getUser()->getId();
            }
            if ($currentUser->getRole() != 4 && ($organization != $currentUserOrganization || $currentUser->getRole() != 1 && !in_array($currentUser->getId(), $teamUsrIds))) {
                $hasPageAccess = false;
            }
        }

        if (!$hasPageAccess) {
            return $this->render('errors/403.html.twig');
        } else {

            $hasContribActivities          = false;
            $elementParticipations         = $repoP->findBy([$resultParticipantProperty => $resultParticipantValue, 'status' => [3, 4]], ['inserted' => 'ASC']);
            $nbGradedIndivActivities       = 0;
            $nbGradedIndivStages           = 0;
            $nbGradedIndivCriteria         = 0;
            $nbGradedProjectActivities     = 0;
            $nbGradedProjectStages         = 0;
            $nbGradedProjectCriteria       = 0;
            $genResults                    = [];
            $criterionIndivResults         = new ArrayCollection;
            $stageIndivResults             = new ArrayCollection;
            $activityIndivResults          = new ArrayCollection;
            $criterionProjectResults       = new ArrayCollection;
            $stageProjectResults           = new ArrayCollection;
            $activityProjectResults        = new ArrayCollection;

            if ($currentUser->getRole() == 3 && $currentUser->getDepartment()->getMasterUser() != $currentUser) {

                $allElementResults = new ArrayCollection($repoR->findBy([$resultParticipantProperty => $resultParticipantValue], ['activity' => 'ASC', 'stage' => 'ASC', 'criterion' => 'ASC']));

                $unreleasedStages = [];

                foreach ($elementParticipations as $key => $elementParticipation) {

                    $criterion = $elementParticipation->getCriterion();
                    if ($criterion) {
                        // Find if there is any contributive criterion
                        if ($elementParticipation->getCriterion()->getType() == 0) {
                            $hasContribActivities = true;
                        }
                    }
                    // Remove results which are unreleased for collaborators
                    if ($elementParticipation->getStatus() != 4) {
                        $unreleasedStages[] = $elementParticipation->getStage();
                    }
                }

                array_unique($unreleasedStages);

                foreach ($unreleasedStages as $unreleasedStage) {
                    foreach ($allElementResults as $key => $elementResult) {
                        if ($elementResult->getStage() == $unreleasedStage || $elementResult->getActivity() == $unreleasedStage->getActivity() && $elementResult->getStage() == null) {
                            $allElementResults->removeElement($elementResult);
                        }
                    }
                }

                // Ordering results in a new array
                $allElementResults = $allElementResults->toArray();
                $elementResults    = [];
                foreach ($allElementResults as $allElementResult) {
                    $elementResults[] = $allElementResult;
                }

                $stage    = null;
                $activity = null;
                //die;

                foreach ($elementResults as $elementResult) {
                    if ($elementResult->getCriterion() == null) {
                        if ($elementResult->getStage() == null) {
                            $activityResults->add($elementResult);
                        } else {
                            $stageResults->add($elementResult);
                        }
                    } else {
                        $criterionResults->add($elementResult);
                    }

                    if ($elementResult->getActivity() !== $activity) {

                        $activity = $elementResult->getActivity();
                        $stage    = $elementResult->getStage();

                        if ($stage != null) {

                            $actStagesResults = $repoR->findBy(['stage' => $activity->getStages()->toArray(), 'criterion' => null, $resultParticipantProperty => null]);
                            foreach ($actStagesResults as $actStagesResult) {
                                $genResults[] = $actStagesResult;
                                $stageResults->add($actStagesResult);
                            }

                        } else {

                            $actStagesResults = $repoR->findBy(['activity' => $activity, 'criterion' => null, $resultParticipantProperty => null]);
                            foreach ($actStagesResults as $actStagesResult) {

                                if ($actStagesResult->getStage() != null) {
                                    $stageResults->add($actStagesResult);
                                } else {
                                    $activityResults->add($actStagesResult);
                                }
                                $genResults[] = $actStagesResult;
                            }
                        }

                        if ($elementResult->getStage() == null && $elementResult->getCriterion() == null) {$nbGradedActivities++;}
                        if ($elementResult->getStage() != null) {$nbGradedStages++;}
                        if ($elementResult->getCriterion() != null) {$nbGradedCriteria++;}

                    } else {

                        if ($elementResult->getStage() != $stage) {

                            $stage = $elementResult->getStage();
                            if ($elementResult->getCriterion() == null) {$nbGradedStages++;}
                            if ($elementResult->getCriterion() != null) {$nbGradedCriteria++;}

                        } else {
                            $nbGradedCriteria++;
                        }
                    }
                }

                // Getting comments

                $allGrades = new ArrayCollection($repoG->findBy([$gradedElmtId => $elmtId]));

                foreach ($allGrades as $elementGrade) {
                    if ($elementGrade->getGradedParticipant($app)->getStatus() == 3) {
                        $allGrades->removeElement($elementGrade);
                    }
                }

                // Ordering (grade) comments in a new array
                $allGrades     = $allGrades->toArray();
                $elementGrades = [];
                foreach ($allGrades as $allGrade) {
                    $elementGrades[] = $allGrade;
                }

            } else {

                $elementIndivResults = new ArrayCollection($repoR->findBy([$resultParticipantProperty => $resultParticipantValue], ['activity' => 'ASC']));
                $projectStages       = $repoS->findBy(['activity' => $organization->getActivities()->getValues(), 'mode' => [0, 2]]);
                $projectCriteria = [];
                foreach ($projectStages as $projectStage) {
                    foreach ($projectStage->getCriteria() as $criterion) {
                        $projectCriteria[] = $criterion;
                    }
                }
                $elementProjectParticipations = $repoP->findBy([$resultParticipantProperty => $resultParticipantValue, 'stage' => $projectStages]);
                foreach ($elementProjectParticipations as $elementProjectParticipation) {
                    $elementProjectActivities[] = $elementProjectParticipation->getActivity();
                }

                if (isset($elementProjectActivities)) {
                    $elementProjectResults = new ArrayCollection($repoRP->findBy(['activity' => $elementProjectActivities]));
                } else {
                    $elementProjectResults = false;
                }

                $activityIndivResults = $elementIndivResults->matching(
                    Criteria::create()->where(Criteria::expr()->eq("stage", null))
                        ->andWhere(Criteria::expr()->eq("criterion", null))
                );

                $stageIndivResults = $elementIndivResults->matching(
                    Criteria::create()->where(Criteria::expr()->neq("stage", null))
                        ->andWhere(Criteria::expr()->eq("criterion", null))
                );
                $criterionIndivResults = $elementIndivResults->matching(
                    Criteria::create()->where(Criteria::expr()->neq("criterion", null))
                );
                if ($elementProjectResults != false) {

                    $activityProjectResults = $elementProjectResults->matching(
                        Criteria::create()->where(Criteria::expr()->eq("stage", null))
                            ->andWhere(Criteria::expr()->eq("criterion", null))
                    );
                    $stageProjectResults = $elementProjectResults->matching(
                        Criteria::create()->where(Criteria::expr()->neq("stage", null))
                            ->andWhere(Criteria::expr()->eq("criterion", null))
                    );
                    $criterionProjectResults = $elementProjectResults->matching(
                        Criteria::create()->where(Criteria::expr()->neq("criterion", null))
                    );
                }

                $nbGradedIndivActivities = count($activityIndivResults);
                $nbGradedIndivStages     = count($stageIndivResults);
                $nbGradedIndivCriteria   = count($criterionIndivResults);

                if ($elementProjectResults != false) {
                    $nbGradedProjectActivities = count($activityProjectResults);
                    $nbGradedProjectStages     = count($stageProjectResults);
                    $nbGradedProjectCriteria   = count($criterionProjectResults);
                }

            }

            $elementGrades = $repoG->findBy([$gradedElmtId => $elmtId]);

            if ($elementGrades !== null) {
                $elementGrades        = new ArrayCollection($elementGrades);
                $elementIndivGrades   = $elementGrades->matching(Criteria::create()->where(Criteria::expr()->neq("comment", null))->andWhere(Criteria::expr()->neq("comment", ""))); //->andWhere(Criteria::expr()->notIn("criterion", $projectCriteria)));
                $elementProjectGrades = $elementGrades->matching(Criteria::create()->where(Criteria::expr()->neq("comment", null))->andWhere(Criteria::expr()->neq("comment", ""))->andWhere(Criteria::expr()->in("criterion", $projectCriteria)));
            }

            $criterionIndivNames = new ArrayCollection;
            foreach ($criterionIndivResults as $criterionIndivResult) {
                if (!$criterionIndivNames->contains($criterionIndivResult->getCriterion()->getCName())) {
                    $criterionIndivNames->add($criterionIndivResult->getCriterion()->getCName());
                }
            }

            $criterionProjectNames = new ArrayCollection;
            foreach ($criterionProjectResults as $criterionProjectResult) {
                if (!$criterionProjectNames->contains($criterionProjectResult->getCriterion()->getCName())) {
                    $criterionProjectNames->add($criterionProjectResult->getCriterion()->getCName());
                }
            }

            return $this->render('element_overview.html.twig',
                [
                    'indivGrades'               => $elementIndivGrades,
                    'projectGrades'             => $elementProjectGrades,
                    'elmtId'                    => $elmtId,
                    'elmt'                      => $element,
                    'elmtType'                  => $elmtType,
                    'username'                  => ($elmtType == 'user') ? $user->getFirstname() . ' ' . $user->getLastname() : $team->getName(),
                    'memberSince'               => ($elmtType == 'user') ? $user->getInserted() : $team->getInserted(),
                    'hasContribActivities'      => $hasContribActivities,
                    'nbGradedIndivActivities'   => $nbGradedIndivActivities,
                    'nbGradedIndivStages'       => $nbGradedIndivStages,
                    'nbGradedIndivCriteria'     => $nbGradedIndivCriteria,
                    'criterionIndivNames'       => $criterionIndivNames,
                    'nbGradedProjectActivities' => $nbGradedProjectActivities,
                    'nbGradedProjectStages'     => $nbGradedProjectStages,
                    'nbGradedProjectCriteria'   => $nbGradedProjectCriteria,
                    'criterionProjectNames'     => $criterionProjectNames,
                    'cNameId'                   => 0,
                    'dealingTeams'              => $dealingTeams,
                ]);
        }

    }

}