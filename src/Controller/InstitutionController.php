<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Client;
use App\Entity\Participation;
use App\Entity\Decision;
use App\Entity\Department;
use App\Entity\EventGroup;
use App\Entity\Organization;
use App\Entity\OrganizationUserOption;
use App\Entity\Stage;
use App\Entity\Survey;
use App\Entity\User;
use App\Entity\WorkerFirm;
use App\Form\ActivityMinElementForm;
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
    public function processesListAction(Request $request): Response
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
        if(isset($_COOKIE['ts'])){
            $timescale = $_COOKIE['ts'];
        } else {
            setcookie('ts', 'y');
            $timescale = 's';
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
        $em = $this->em;
        $locale = strtoupper($request->getLocale());
        $org = $this->org;
        $eventGroups = $this->org->getEventGroups()->map(
            function(EventGroup $eg) use ($em,$locale,$org){
                $eg->em = $em;
                $eg->locale = $locale;
                $eg->org = $org;
                return [
                    'id' => $eg->getId(),
                    'name' => $eg->getDTrans(),
                    'evnId' => $eg->getEventGroupName()->getId(),
                ];
            }
        )->getValues();

        //print_r(sizeof($orphanActivities));
        //die;

        foreach ($statuses as $s => $status) {
            if ($orphanActivities && $orphanActivities[$s] or $processActivities && $processActivities[$s]) {
                $displayedStatuses[$s] = $status;
            }
        }

        $addProcessForm = $this->createForm(AddProcessForm::class, null, ['standalone' => true]);
        $delegateActivityForm = $this->createForm(DelegateActivityForm::class, null, ['standalone' => true, 'currentUser' => $this->user]);
        $eventForm = $this->createForm(AddEventForm::class, null, ['standalone' => true, 'currentUser' => $this->user]);
        $createForm = $this->createForm(ActivityMinElementForm::class, new Stage, ['currentUser' => $this->user]);

        return $this->render(
            'activities_dashboard.html.twig',
            [
                'delegateForm' => $delegateActivityForm->createView(),
                'displayedStatuses'  => $displayedStatuses,
                'orphanActivities'  => $orphanActivities,
                'processesActivities' => $processActivities,
                'addProcessForm' => $addProcessForm->createView(),
                'sortingTypeCookie' => $sortingType,
                'viewTypeCookie' => $viewType,
                'dateTypeCookie' => $dateType,
                'timescaleCookie' => $timescale,
                'eventForm' => $eventForm->createView(),
                'newActivityForm' => $createForm->createView(),
                'firstConnection' => true,
                'em' => $em,
                'eventGroups' => $eventGroups,
            ]
        );
    }

    /**
     * @param Request $request
     * Cookies :
     * ts defines chosen timescale
     * ci defines current interval
     * view_type defines either temporal view, or by status view
     * date_type defines we see either duration for the stage itself (s) or eventual outputs (o)
     * 
     * @Route("/myactivities", name="myActivities")
     * @return Response
     */
    public function myActivitiesListAction(Request $request): Response
    {

        $em = $this->getEntityManager();
        $currentUser = $this->user;
        $repoA = $em->getRepository(Activity::class);
        $repoP = $em->getRepository(Participation::class);
        $repoDec = $em->getRepository(Decision::class);
        $role = $currentUser->getRole();
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
        if(isset($_COOKIE['ts'])){
            $timescale = $_COOKIE['ts'];
        } else {
            setcookie('ts', 'y');
            $timescale = 'y';
        }
        if(!isset($_COOKIE['ci'])) {
            if($timescale == 'y'){
                setcookie('ci', date("Y"));
            } else {
                $currentQt = ceil(date("n") / 3);
                $currentYear = date("Y");
                setcookie('ci', "q-$currentQt-$currentYear");
            }
        }

        $userArchivingPeriod = $currentUser->getActivitiesArchivingNbDays();


        // Add activities where current user is either is a leader, or at least a participant;

        $orgActivities = $repoA->findBy(['organization'=> $this->org],['status' => 'ASC']);

        // We get all user info and visibility options :
        // * we need to check access to results with the integer option value

        $existingAccessAndResultsViewOption = null;
        $statusAccess = null;

        if($this->org){

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
        
        }

        $checkingIds = [$currentUser->getId()];
        $userActivities = new ArrayCollection;

        // 1 - Retrieving activities 
        // Depends on either organization plan (for free organization, no privacy/segregation) and/or user rights/roles

        if($this->org && $this->org->getPlan() == Organization::PLAN_FREE){

            $externalOrgActivities = $this->org->getExternalActivities();
            $userActivities = new ArrayCollection($orgActivities + (array)$externalOrgActivities->toArray());

        } else {

            if($existingAccessAndResultsViewOption){
                $activitiesAccess = $existingAccessAndResultsViewOption->getOptionIValue();
                $statusAccess = $existingAccessAndResultsViewOption->getOptionSecondaryIValue();
                $noParticipationRestriction = $existingAccessAndResultsViewOption->getOptionSValue() == 'none';
                /*if($activitiesAccess == 1){
                    $userActivities = new ArrayCollection($orgActivities);
                } else if ($activitiesAccess == 2){*/
                    $departmentUsers = $currentUser->getDepartment() != null ? $currentUser->getDepartment()->getUsers() : [];
                    foreach($departmentUsers as $departmentUser){
                        $checkingIds[] = $departmentUser->getId();
                    }
                //}
            }
    
            $userArchivedActivities = new ArrayCollection;
    
            //if($existingAccessAndResultsViewOption == null || $activitiesAccess != 1){
    
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
    
                                $orgStage->currentUser = $currentUser;
    
                                foreach ($orgStage->getParticipations() as $orgParticipant){
                                    if (in_array($orgParticipant->getUser()->getId(), $checkingIds)){

                                        $isParticipant = 1;
                                        ($orgParticipant->getStatus() != 5) ? $userActivities->add($orgActivity) : $userArchivedActivities->add($orgActivity);
                                        break;
                                    }
                                }
    
                                if ($isParticipant == 1){
                                    break;
                                } 
                                /*
                                elseif(in_array($orgStage->getMasterUser()->getId(), $checkingIds) && (!$orgStage->getOwnerUserId() || in_array($orgStage->getOwnerUserId(), $checkingIds))){
                                    $isMasterUserId = 1;
                                    break;
                                }
                                */
                            }
                            
                            /*
                            if($isMasterUserId == 1 && $isParticipant == 0 && $orgActivity->getStatus() != -2){
                                $userActivities->add($orgActivity);
                            }*/
    
                        } else {
                            $userArchivedActivities->add($orgActivity);
                        }
                    }
                }
                //Get activities where user is participating as external user
                $externalActivities = $currentUser->getExternalActivities();
                //dd($externalActivities);
                
                $userActivities = new ArrayCollection((array)$userActivities->toArray() + $externalActivities->toArray());
    
            //}
        }

        if($this->org){
            $addProcessForm = $this->createForm(AddProcessForm::class, null, ['standalone' => true]);
            $delegateActivityForm = $this->createForm(DelegateActivityForm::class, null, ['standalone' => true, 'currentUser' => $currentUser]) ;
            $delegateActivityForm->handleRequest($request);
            $requestActivityForm = $this->createForm(RequestActivityForm::class, null, ['standalone' => true, 'em' => $em, 'currentUser' => $currentUser ]) ;
            $requestActivityForm->handleRequest($request);
            $validateRequestForm = $this->createForm(DelegateActivityForm::class, null,  ['standalone' => true, 'request' => true, 'currentUser' => $currentUser]);
            $validateRequestForm->handleRequest($request);
            $eventForm = $this->createForm(AddEventForm::class, null, ['standalone' => true, 'currentUser' => $currentUser]);
            $eventForm->handleRequest($request);
            $createForm = $this->createForm(ActivityMinElementForm::class, new Stage, ['currentUser' => $this->user]);
        }

        // In case they might access results depending on user participation, then we need to feed all stages and feed a collection which will be analysed therefore in hideResultsFromStages function
        if($existingAccessAndResultsViewOption && empty($noParticipationRestriction)){
            $userStages = new ArrayCollection;
            foreach($userActivities as $activity){
                foreach($activity->getStages() as $stage){
                    $userStages->add($stage);
                }
            }
        }

        // 2 - Sorting activities per status and process

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
        //dd($orphanActivities);

        $firstConnection = $currentUser->getLastConnected() == null;
        if($firstConnection){
            $currentUser->setLastConnected(new \DateTime);
            $em->persist($currentUser);
            $em->flush();
        }

        $locale = strtoupper($request->getLocale());
        $org = $this->org;

        $eventGroups = $this->org->getEventGroups()->map(
            function(EventGroup $eg) use ($em,$locale,$org){
                $eg->em = $em;
                $eg->locale = $locale;
                $eg->org = $org;
                return [
                    'id' => $eg->getId(),
                    'name' => $eg->getDTrans(),
                    'evnId' => $eg->getEventGroupName()->getId(),
                ];
            }
        )->getValues();
 

        return $this->render(
            'activities_dashboard.html.twig',
            [
                'displayedStatuses'  => $displayedStatuses,
                'orphanActivities'  => $orphanActivities,
                'processesActivities' => $this->org ? $structuredProcessesActivities : null,
                'addProcessForm' => $this->org ? $addProcessForm->createView() : null,
                'delegateForm' => $this->org ? $delegateActivityForm->createView() : null,
                'validateRequestForm' => $this->org ? $validateRequestForm->createView() : null,
                'requestForm' => $this->org ? $requestActivityForm->createView() : null,
                'sortingTypeCookie' => $sortingType,
                'viewTypeCookie' => $viewType,
                'dateTypeCookie' => $dateType,
                'timescaleCookie' => $timescale,
                'eventForm' => $this->org ? $eventForm->createView() : null,
                'newActivityForm' => $this->org ? $createForm->createView() : null,
                'firstConnection' => (int) $firstConnection,
                'eventGroups' => $eventGroups,
                'em' => $em,
            ]
        );

    }

}
