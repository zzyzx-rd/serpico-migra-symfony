<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Participation;
use App\Entity\Decision;
use App\Entity\Department;
use App\Entity\OrganizationUserOption;
use App\Entity\Survey;
use App\Entity\User;
use App\Form\AddEventForm;
use App\Form\AddProcessForm;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\DelegateActivityForm;
use App\Form\RequestActivityForm;
use App\Repository\ActivityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

final class InstitutionController extends MasterController
{
    protected $user;
    protected $org;
    /** @var ActivityRepository */
    protected $activityRepo;

    /**
     * @Route("/settings/institution/processes", name="processesList")
     */
    public function processesListAction(): Response
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
        if(isset($_COOKIE['date_type'])){
            $dateType = $_COOKIE['date_type'];
        } else {
            setcookie('date_type', 's');
            $dateType = 's';
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
        $processActivities = $this->activityRepo->getOrgProcessActivities($this->org, $viewType);

        foreach ($statuses as $s => $status) {
            if ($orphanActivities && $orphanActivities[$s] or $processActivities && $processActivities[$s]) {
                $displayedStatuses[$s] = $status;
            }
        }

        $addProcessForm = $this->createForm(AddProcessForm::class, null, ['standalone' => true]);
        $delegateActivityForm = $this->createForm(DelegateActivityForm::class, null, ['standalone' => true, 'currentUser' => $this->user]);
        $eventForm = $this->createForm(AddEventForm::class, null, ['standalone' => true, 'currentUser' => $this->user]);

        return $this->render(
            'iprocess_list.html.twig',
            [
                'delegateForm' => $delegateActivityForm->createView(),
                'displayedStatuses'  => $displayedStatuses,
                'orphanActivities'  => $orphanActivities,
                'processesActivities' => $processActivities,
                'addProcessForm' => $addProcessForm->createView(),
                'sortingTypeCookie' => $sortingType,
                'viewTypeCookie' => $viewType,
                'dateTypeCookie' => $dateType,
                'eventForm' => $eventForm->createView(),
            ]
        );
    }

    /**
     * @param Request $request
     * @Route("/myactivities", name="myActivities")
     * @return Response
     */
    public function myActivitiesListAction(Request $request): Response
    {

        $em = $this->getEntityManager();
        $user = $this->user;
        $repoA = $em->getRepository(Activity::class);
        $repoP = $em->getRepository(Participation::class);
        $repoDec = $em->getRepository(Decision::class);
        $role = $user->getRole();
        $currentUsrId = $user->getId();
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
        if(isset($_COOKIE['date_type'])){
            $dateType = $_COOKIE['date_type'];
        } else {
            setcookie('date_type', 's');
            $dateType = 's';
        }

        $userArchivingPeriod = $user->getActivitiesArchivingNbDays();


        // Add activities where current user is either is a leader, or at least a participant;

        $orgActivities = $repoA->findBy(['organization'=> $this->org],['status' => 'ASC']);

        // We get all user info and visibility options :
        // * we need to check access to results with the integer option value

        $existingAccessAndResultsViewOption = null;
        $statusAccess = null;
        $accessAndResultsViewOptions = $this->org->getOptions()->filter(function(OrganizationUserOption $option) {return $option->getOName()->getName() == 'activitiesAccessAndResultsView' && ($option->getRole() == $this->user->getRole() || $option->getUser() == $this->user);});

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
                $departmentUsers = $this->user->getDepartment() != null ? $em->getRepository(Department::class)->find($this->user->getDptId())->getUsers() : [];
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
            $subordinates = $this->user->getSubordinates();
            /** @var Participation[] */
            $subordinatesParticipations = $repoP->findBy(['user' => $subordinates->toArray(), 'type' => [ -1, 1 ] ]);
            $activitiesWithSubordinates_IDs = array_map(function (Participation $p) {
                return $p->getStage()->getActivity()->getId();
            }, $subordinatesParticipations);

            foreach($orgActivities as $orgActivity){
                if (
                    $orgActivity->getStatus() == -2 && in_array($orgActivity->getMasterUserId(), $checkingIds) ||
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
            $externalActivities = $externalActivities = $em->getRepository(User::class)->getExternalActivities($user);
            $userActivities = new ArrayCollection((array)$userActivities->toArray() + $externalActivities->toArray());

        }

        $addProcessForm = $this->createForm(AddProcessForm::class, null, ['standalone' => true]);
        $delegateActivityForm = $this->createForm(DelegateActivityForm::class, null, ['standalone' => true, 'currentUser' => $user]) ;
        $delegateActivityForm->handleRequest($request);
        $requestActivityForm = $this->createForm(RequestActivityForm::class, null, ['standalone' => true, 'em' => $em, 'currentUser' => $user ]) ;
        $requestActivityForm->handleRequest($request);
        $validateRequestForm = $this->createForm(DelegateActivityForm::class, null,  ['standalone' => true, 'request' => true, 'currentUser' => $user]);
        $validateRequestForm->handleRequest($request);
        $eventForm = $this->createForm(AddEventForm::class, null, ['standalone' => true, 'currentUser' => $user]);
        $eventForm->handleRequest($request);

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
        $structuredProcessesActivities = [];

        if($viewType == 'd'){

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
        
        } else {

            /** @var Activity[] */
            $orphanActivities = $userActivities->matching(Criteria::create()
                ->where(Criteria::expr()->eq("institutionProcess", null))
                ->andWhere(Criteria::expr()->eq("process", null))
                ->orderBy([$sortingProperty => Criteria::DESC]));

            $displayedStatuses = [];
            $s = null;
            foreach($orphanActivities as $orphanActivity){
                $s = $sortingType == 'o' ? $orphanActivity->getStatus() : (string) $orphanActivity->getProgress();
                if(!array_key_exists($s,$displayedStatuses)){
                    $displayedStatuses[$s] = $statuses[$s];
                }
            }
            
            /** @var Activity[] */
            $processesActivities = $userActivities->matching(Criteria::create()
            ->where(Criteria::expr()->neq("institutionProcess", null))
            ->orWhere(Criteria::expr()->neq("process", null))
            ->orderBy(['institutionProcess' => Criteria::ASC, $sortingProperty => Criteria::DESC]));

            $currProcessName = null;
            foreach($processesActivities as $a){

                $processName = $a->getInstitutionProcess() ? $a->getInstitutionProcess()->getName() : $a->getProcess()->getName();
                if($processName != $currProcessName){
                    $currProcessName = $processName;
                    $structuredProcessesActivities[$processName] = [];
                    $s = null;
                }


                if($sortingType == 'o'){
                    if($a->getStatus() != $s){

                        $s = $a->getStatus();
                        $allS[] = $s;
                        $structuredProcessesActivities[$processName][$s] = [];
                        if(!array_key_exists($s,$displayedStatuses)){
                            $displayedStatuses[$s] = $statuses[$s];
                        }
                    }
                } else {

                    $progress = (string) $a->getProgress();

                    if($progress != $s){
                        $s = $progress;
                        $structuredProcessesActivities[$processName][$s] = [];
                        if(!array_key_exists($s,$displayedStatuses)){
                            $displayedStatuses[$s] = $statuses[$s];
                        }
                    }
                }

                $structuredProcessesActivities[$processName][$s][] = $a;
            }

            //rsort($displayedStatuses);
        }

        ksort($displayedStatuses);

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
                'dateTypeCookie' => $dateType,
                'eventForm' => $eventForm->createView(),
            ]
        );

    }

}
