<?php


namespace App\Controller;

use App\Entity\Client;
use App\Entity\ExternalUser;
use App\Entity\Organization;
use App\Entity\User;
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

     // Display user info, enables modification. Note : root user can modify users from other organizations

    /**
     * @param Request $request
     * @param Application $app
     * @param $usrId
     * @return mixed
     * @Route("/settings/user/{usrId}", name="updateUser", methods={"GET","POST"})
     */
    public function updateUserAction(Request $request, $usrId)
    {

        $em            = $this->em;
        $repoO         = $em->getRepository(Organization::class);
        $repoC         = $em->getRepository(Client::class);
        $searchedUser  = $em->getRepository(User::class)->find($usrId);
        $connectedUser = $this->user;
        if (!$connectedUser instanceof User) {
            return $this->redirectToRoute('login');
        }

        $connectedUserOrganization = $connectedUser->getOrganization();
        $searchedUserOrganization  = $repoO->find($searchedUser->getOrganization());

        $departments = ($searchedUserOrganization == $connectedUserOrganization || $connectedUser->getRole() == 4) ? $searchedUserOrganization->getDepartments() : null;

        // Look through organization clients if user belongs to org clients
        if ($searchedUserOrganization != $connectedUserOrganization) {

            $connectedUserOrganization = $repoO->find($connectedUser->getOrganization());
            $connectedUserOrgClients   = $repoC->findByOrganization($connectedUserOrganization);
            $connectedUserClients      = [];
            foreach ($connectedUserOrgClients as $connectedUserOrgClient) {
                $connectedUserClients[] = $connectedUserOrgClient->getClientOrganization();
            }

            if (!in_array($searchedUserOrganization, $connectedUserClients) && $connectedUser->getRole() != 4) {
                return $this->render('errors/403.html.twig');
            }

            if (in_array($searchedUserOrganization, $connectedUserClients)) {
                $modifyIntern = false;
                $userForm     = $this->createForm(ClientUserType::class, null, ['standalone' => true, 'user' => $searchedUser, 'app' => $app, 'clients' => $connectedUserClients]);
            } else {
                // This case only applies to root users
                $modifyIntern = true;
                $userForm     = $this->createForm(UserType::class, null, ['standalone' => true, 'app' => $app, 'departments' => $departments, 'user' => $searchedUser]);
            }

        } else {
            if ($connectedUser->getRole() == 2 || $connectedUser->getRole() == 3) {
                return $this->render('errors/403.html.twig');
            }

            $modifyIntern = true;
        }

        $orgOptions                = $searchedUserOrganization->getOptions();
        $enabledCreatingUserOption = false;
        foreach ($orgOptions as $orgOption) {
            if ($orgOption->getOName()->getName() == 'enabledUserCreatingUser') {
                $enabledCreatingUserOption = $orgOption->isOptionTrue();
            }
        }

        if ($modifyIntern) {

            $updateUserForm          = $this->createForm(UserType::class, $searchedUser, ['standalone' => true, 'standalone' => true, 'organization' => $searchedUserOrganization]);
            $organizationElementForm = $this->createForm(OrganizationElementType::class, null, ['usedForUserCreation' => false, 'standalone' => true, 'organization' => $searchedUserOrganization]);
            $updateUserForm->handleRequest($request);
            $organizationElementForm->handleRequest($request);

            if($updateUserForm->isSubmitted() && $updateUserForm->isValid()){
                $em->persist($searchedUser);
                $em->flush();
                return $this->redirectToRoute('manageUsers');
            }

            return $this->render('user_update.html.twig',
                [
                    'form'                    => $updateUserForm->createView(),
                    'organizationElementForm' => $organizationElementForm->createView(),
                    'orgId'                   => $searchedUserOrganization->getId(),
                    'enabledCreatingUser'     => $enabledCreatingUserOption,
                    'creationPage'            => false,
                ]);
        }

        return $this->render('user_update.html.twig',
            [
                'modifyIntern'        => $modifyIntern,
                'form'                => $userForm->createView(),
                'user'                => $searchedUser,
                'orgId'               => $searchedUserOrganization->getId(),
                'clientForm'          => ($modifyIntern) ? null : $this->createForm(AddClientForm::class, null, ['standalone' => true])->createView(),
                'enabledCreatingUser' => $enabledCreatingUserOption,
            ]);

    }

    /**
     * @param Request $request
     * @param $cliId
     * @param $extId
     * @return mixed
     * @Route("/client/{cliId}/user/validate/{extId}", name="validateClientUser")
     */
    public function validateClientUserAction(Request $request, $cliId, $extId){

        $em = $this->em;
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $repoC = $em->getRepository(Client::class);
        $repoE = $em->getRepository(ExternalUser::class);
        /** @var Client */
        $client = $repoC->find($cliId);
        $externalUser = $extId == 0 ? new ExternalUser : $repoE->find($extId);
        $individualForm = $this->createForm(ExternalUserType::class, $externalUser, ['standalone' => true]);
        $individualForm->handleRequest($request);
        if ($individualForm->isValid()) {
            if($extId == 0){

                $token = md5(rand());
                $user = new User;
                $user->setFirstname($externalUser->getFirstname())
                    ->setLastname($externalUser->getLastname())
                    ->setEmail($externalUser->getEmail())
                    ->setCreatedBy($currentUser->getId())
                    ->setOrganization($client->getClientOrganization())
                    ->setRole(3)
                    ->setToken($token);
                $em->persist($user);
                $em->flush();
                $settings = [];
                $settings['tokens'][] = $token;
                $settings['invitingUser'] = $currentUser;
                $settings['invitingOrganization'] = $currentUser->getOrganization();
                $recipients[] = $user;
                if($externalUser->getEmail() != ""){
                    $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'externalInvitation']);
                }
                $externalUser->setClient($client)->setUser($user);
            }
            $em->persist($externalUser);
            $em->flush();
            return $extId == 0 ?
                $this->json(['status' => 'done', 'extId' => $externalUser->getId()], 200) :
                $this->json(['status' => 'done'], 200);
        } else {
            return $this->buildErrorArray($individualForm);
        }
    }

}