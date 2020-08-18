<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\ActivityUser;
use App\Entity\Decision;
use App\Entity\OrganizationUserOption;
use App\Entity\Survey;
use App\Entity\User;
use App\Form\AddProcessForm;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\DelegateActivityForm;
use App\Form\RequestActivityForm;
use App\Repository\ActivityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

final class InstitutionController extends MasterController
{
    protected $user;
    protected $org;
    /** @var ActivityRepository */
    protected $activityRepo;

    /**
     * @return string
     * @Route("/settings/institution/processes", name="processList")
     */
    public function processesListAction()
    {
        if(isset($_COOKIE['sorting_type'])){
            $sortingType = $_COOKIE['sorting_type'];
        } else {
            setcookie('sorting_type', 'p');
            $sortingType = 'p';
        }
        if(isset($_COOKIE['view_type'])){
            $viewType = $_COOKIE['view_type'];
        } else {
            setcookie('view_type', 'd');
            $viewType = 'd';
        }


        $statuses = [
            -5 => $sortingType == 'o' ? 'cancelled' : 'stopped',
            -4 => $sortingType == 'o' ? 'discarded' : 'postponed',
            -3 => $sortingType == 'o' ? 'requested' : 'suspended',
            -2 => $sortingType == 'o' ? 'attributed' : 'reopened',
            -1 => $sortingType == 'o' ? 'incomplete' : 'unstarted',
            0  => $sortingType == 'o' ? 'future' : 'upcoming',
            1  => $sortingType == 'o' ? 'current' : 'ongoing',
            2  => 'completed',
            3  => $sortingType == 'o' ? 'published' : 'finalized',
        ];

        $sortingProperty = $sortingType == 'o' ? 'status' : 'progress';
        $displayedStatuses = [];

        $orphanActivities  = $this->activityRepo->getOrgOrphanActivities($this->org);
        $processActivities = $this->activityRepo->getOrgProcessActivities($this->org);

        foreach ($statuses as $s => $status) {
            if ($orphanActivities[$s] or $processActivities[$s]) {
                $displayedStatuses[$s] = $status;
            }
        }

        $addProcessForm = $this->createForm(AddProcessForm::class, null, ['standalone' => true]);
        $delegateActivityForm = $this->createForm(DelegateActivityForm::class, null, ['standalone' => true, 'app' => $app]) ;

        //$name = $viewType == 'd' ? 'iprocess_list.html.twig' : 'iprocess2_list.html.twig';

        return $this->render(
            'iprocess_list.html.twig',
            [
                'delegateForm' => $delegateActivityForm->createView(),
                'displayedStatuses'  => $displayedStatuses,
                'orphanActivities'  => $orphanActivities,
                'processActivities' => $processActivities,
                'addProcessForm' => $addProcessForm->createView(),
                'sortingTypeCookie' => $sortingType,
                'viewTypeCookie' => $viewType,
            ]
        );
    }

    /**
     * @param Request $request
     * @return string
     * @Route("/myactivities", name="myActivities")
     */
    public function myActivitiesListAction(Request $request){

        $em = $this->getEntityManager();
        $repoA = $em->getRepository(Activity::class);
        $repoAU = $em->getRepository(ActivityUser::class);
        $repoDec = $em->getRepository(Decision::class);
        $role = $this->user->getRole();
        $currentUsrId = $this->user->getId();
        $repoS = $em->getRepository(Survey::class);

        if(isset($_COOKIE['sorting_type'])){
            $sortingType = $_COOKIE['sorting_type'];
        } else {
            setcookie('sorting_type', 'p');
            $sortingType = 'p';
        }
        if(isset($_COOKIE['view_type'])){
            $viewType = $_COOKIE['view_type'];
        } else {
            setcookie('view_type', 'd');
            $viewType = 'd';
        }

        $userArchivingPeriod = $this->user->getActivitiesArchivingNbDays();


        // Add activities where current user is either is a leader, or at least a participant;

        $orgActivities = $repoA->findBy(['organization'=> $this->org],['status' => 'ASC']);

        // We get all user info and visibility options :
        // * we need to check access to results with the integer option value

        $existingAccessAndResultsViewOption = null;
        $statusAccess = null;
        $accessAndResultsViewOptions = $this->org->getOptions()->filter(function(OrganizationUserOption $option) {return $option->getOName()->getName() == 'activitiesAccessAndResultsView' && ($option->getRole()->getId() == $this->user->getRole() || $option->getUser() == $this->user);});

        // We always chose the most selective access option (NB : we could in the future, create options decidated to position, departments... so below option selection should be rewritten)
        if(count($accessAndResultsViewOptions) > 0){
            if(count($accessAndResultsViewOptions) == 2){
                foreach($accessAndResultsViewOptions as $accessAndResultsViewOption){
                    if($accessAndResultsViewOption->getUser() != null){
                        $existingAccessAndResultsViewOption = $accessAndResultsViewOption;
                    }
                }
            } else {
                $existingAccessAndResultsViewOption = $accessAndResultsViewOptions->first();
            }
        }

        $checkingIds = [$currentUsrId];
        $userActivities = new ArrayCollection;

        if($existingAccessAndResultsViewOption){
            $activitiesAccess = $existingAccessAndResultsViewOption->getOptionIValue();
            $statusAccess = $existingAccessAndResultsViewOption->getOptionSecondaryIValue();
            $noParticipationRestriction = $existingAccessAndResultsViewOption->getOptionSValue() == 'none';
            if($activitiesAccess == 1){
                $userActivities = new ArrayCollection($orgActivities);
            } else if ($activitiesAccess == 2){
                $departmentUsers = $em->getRepository(Department::class)->find($this->user->getDptId())->getUsers();
                foreach($departmentUsers as $departmentUser){
                    $checkingIds[] = $departmentUser->getId();
                }
            }
        }

        $userArchivedActivities = new ArrayCollection;


        if($existingAccessAndResultsViewOption == null || $activitiesAccess != 1){

            // 1/ Get requested activities (as currentuser is not a participant, we have to retrieve them with a different query)
            // passing through Decision table

            if ($role == 3){
                $pendingDecisions = $repoDec->findBy(['requester' => $checkingIds, 'validated' => null]);
                foreach($pendingDecisions as $pendingDecision){
                    $userActivities->add($pendingDecision->getActivity());
                }
            } else {
                $pendingDecisions = $repoDec->findBy(['decider' => $checkingIds, 'validated' => null]);
                foreach($pendingDecisions as $pendingDecision){
                    $userActivities->add($pendingDecision->getActivity());
                }
            }

            // IDs of activities in which at least one *graded* participant is a direct or indirect subordinate
            $subordinates = $this->em->getRepository(User::class)->subordinatesOf($this->user);
            /** @var ActivityUser[] */
            $subordinatesParticipations = $repoAU->findBy(['id' => $subordinates, 'type' => [ -1, 1 ] ]);
            $activitiesWithSubordinates_IDs = array_map(function (ActivityUser $p) {
                return $p->getStage()->getActivity()->getId();
            }, $subordinatesParticipations);

            foreach($orgActivities as $orgActivity){
                if (
                    ($orgActivity->getStatus() == -2 && in_array($orgActivity->getMasterUserId(), $checkingIds)) ||
                    in_array($orgActivity->getId(), $activitiesWithSubordinates_IDs)
                ){
                    $userActivities->add($orgActivity);
                }

                // 3/ Get all activities in which current user is participating

                if(!in_array($orgActivity->getId(), $activitiesWithSubordinates_IDs)){
                    if(!$orgActivity->getArchived()){

                        $isParticipant = 0;
                        $isMasterUserId = 0;
                        foreach ($orgActivity->getStages() as $orgStage){
                            foreach ($orgStage->getParticipants() as $orgParticipant){
                                if (in_array($orgParticipant->getUsrId(), $checkingIds)){
                                    $isParticipant = 1;
                                    ($orgParticipant->getStatus() != 5) ? $userActivities->add($orgActivity) : $userArchivedActivities->add($orgActivity);
                                    break;
                                }
                            }

                            if ($isParticipant == 1){
                                break;
                            } elseif(in_array($orgStage->getMasterUserId(), $checkingIds) && (!$orgStage->getOwnerUserId() || in_array($orgStage->getOwnerUserId(), $checkingIds))){
                                $isMasterUserId = 1;
                                break;
                            }
                        }

                        if($isMasterUserId == 1 && $isParticipant == 0 && $orgActivity->getStatus() != -2){
                            $userActivities->add($orgActivity);
                        }

                    } else {
                        $userArchivedActivities->add($orgActivity);
                    }
                }
            }
            //Get activities where user is participating as external user
            $externalActivities = $this->em->getRepository(User::class)->getExternalActivities(null, $this->user);
            $userActivities = new ArrayCollection((array)$userActivities->toArray() + $externalActivities->toArray());

        }

        $addProcessForm = $this->createForm( AddProcessForm::class, null, ['standalone' => true]);
        $delegateActivityForm = $this->createForm(DelegateActivityForm::class,null, ['standalone' => true, 'currentUser' => $this->user]) ;
        $delegateActivityForm->handleRequest($request);
        $requestActivityForm = $this->createForm(RequestActivityForm::class, null, ['standalone' => true, 'em' => $this->em, 'currentUser' => $this->user]) ;
        $requestActivityForm->handleRequest($request);
        $validateRequestForm = $this->createForm(DelegateActivityForm::class, null,  [ 'standalone' => true, 'request' => true, 'currentUser' => $this->user]);
        $validateRequestForm->handleRequest($request);

        // In case they might access results depending on user participation, then we need to feed all stages and feed a collection which will be analysed therefore in hideResultsFromStages function
        if($existingAccessAndResultsViewOption && !$noParticipationRestriction){
            $userStages = new ArrayCollection;
            foreach($userActivities as $activity){
                foreach($activity->getStages() as $stage){
                    $userStages->add($stage);
                }
            }
        }



        $statuses = [
            -5 => $sortingType == 'o' ? 'cancelled' : 'stopped',
            -4 => $sortingType == 'o' ? 'discarded' : 'postponed',
            -3 => $sortingType == 'o' ? 'requested' : 'suspended',
            -2 => $sortingType == 'o' ? 'attributed' : 'reopened',
            -1 => $sortingType == 'o' ? 'incomplete' : 'unstarted',
            0  => $sortingType == 'o' ? 'future' : 'upcoming',
            1  => $sortingType == 'o' ? 'current' : 'ongoing',
            2  => 'completed',
            3  => $sortingType == 'o' ? 'published' : 'finalized',
        ];

        $sortingProperty = $sortingType == 'o' ? 'status' : 'progress';

        $displayedStatuses = [];

        /*
        foreach($userActivities as $userActivity){
            if(!in_array($userActivity->getStatus(), $displayedStatuses)){
                $displayedStatuses[] = $userActivity->getStatus();
            }
        }*/

        $orphanActivities = [];
        $processActivities = [];
        foreach($statuses as $key => $status){
            $orphanActivities[$key] = $userActivities->matching(Criteria::create()
                ->where(Criteria::expr()->eq("institutionProcess", null))
                ->andWhere(Criteria::expr()->eq("process", null))
                ->andWhere(Criteria::expr()->eq($sortingProperty, $key)));
            /** @var Activity[] */
            $processesActivities = $userActivities->matching(Criteria::create()
                ->where(Criteria::expr()->neq("institutionProcess", null))
                ->orWhere(Criteria::expr()->neq("process", null))
                ->andWhere(Criteria::expr()->eq($sortingProperty, $key)))->getValues();

            $structuredProcessesActivities[$key] = [];

            foreach($processesActivities as $a){
                $processName = $a->getInstitutionProcess() ? $a->getInstitutionProcess()->getName() : $a->getProcess()->getName();
                $structuredProcessesActivities[$key][$processName][] = $a;
            }
            if(count($orphanActivities[$key]) || count($structuredProcessesActivities[$key])){
                $displayedStatuses[$key] = $status;
            }
        }

        //$name = $viewType == 'd' ? 'iprocess_list.html.twig' : 'iprocess2_list.html.twig';

        return $this->render(
            'iprocess_list.html.twig',
            [
                'displayedStatuses'  => $displayedStatuses,
                'orphanActivities'  => $orphanActivities,
                'processesActivities' => $structuredProcessesActivities,
                'addProcessForm' => $addProcessForm->createView(),
                'delegateForm' => $delegateActivityForm->createView(),
                'validateRequestForm' => $validateRequestForm->createView(),
                'requestForm' => $requestActivityForm->createView(),
                'sortingTypeCookie' => $sortingType,
                'viewTypeCookie' => $viewType,
            ]
        );

    }

}
