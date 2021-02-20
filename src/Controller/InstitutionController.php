<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Client;
use App\Entity\Participation;
use App\Entity\Decision;
use App\Entity\Department;
use App\Entity\ElementUpdate;
use App\Entity\EventComment;
use App\Entity\EventType;
use App\Entity\EventGroup;
use App\Entity\Organization;
use App\Entity\OrganizationUserOption;
use App\Entity\Stage;
use App\Entity\Survey;
use App\Entity\User;
use App\Entity\UserMaster;
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
use App\Service\NotificationManager;
use DateTime;
use DateTimeZone;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

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
        $em = $this->em;
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
        $currentUser = $this->user;
        if(!$currentUser){
            return $this->redirectToRoute('login');
        }
        $em = $this->em;
        

        // In case user comes from login, we log him to his last updated, otherwise last connected account
        $comesFromLogin = strpos($request->headers->get('referer'),'login') !== false;

        if($comesFromLogin){

            $lastNotifs = $em->getRepository(ElementUpdate::class)->findBy(['user' => $this->user->getUserGlobal()->getUserAccounts()->getValues()],['inserted' => 'DESC']);
            $lastConnectedUser = $currentUser->getUserGlobal()->getUserAccounts()->first();
            $lastUpdatedUser = $lastNotifs ? $lastNotifs[0]->getUser() : null;
            
            /** @var User */
            $newCurrentUser = $lastUpdatedUser && $lastUpdatedUser != $currentUser ? $lastUpdatedUser : (
                $lastConnectedUser && $lastConnectedUser != $currentUser ? $lastConnectedUser : $currentUser
            );
    
            if($newCurrentUser != $currentUser){

                $currentUser = $newCurrentUser;

                $this->guardHandler->authenticateUserAndHandleSuccess(
                    $currentUser,
                    $request,
                    $this->authenticator,
                    'main'
                );
                $em->refresh($currentUser);
            }
        }


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
            setcookie('view_type', 't');
            $viewType = 't';
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

        if($currentUser->getParticipations()->count() == 0){
            $now = new DateTime;
            setcookie('ts', 'y' ,time() + 30 * 60 * 60 * 24, '/');
            setcookie('ci', $now->format('Y'), time() + 30 * 60 * 60 * 24, '/');
        } else {

        }

        $followingStages = $currentUser->getMasterings()->filter(fn(UserMaster $m) => $m->getStage() != null && $m->getProperty() == 'followableStatus' && $m->getType() >= UserMaster::ADDED)->map(fn(UserMaster $m) => $m->getStage());
        $followingActivities = new ArrayCollection(array_unique($followingStages->map(fn(Stage $s) => $s->getActivity())->getValues(), SORT_REGULAR));

        if(isset($_COOKIE['is'])){

            $followingStageIds = $followingStages->map(fn(Stage $s) => $s->getId())->getValues();
            // If invitation stage cookie exists with a stage id value relative to an already followed one - case occurs when an unlogged user clicks to a invitation link for a stage he's already following
            // we need to remove that value
            $values = preg_split("/\,/", $_COOKIE['is']);
            foreach($values as $key => $value){
                if(in_array($value,$followingStageIds) !== false){
                    unset($values[$key]);
                }
            }
            if(!$values){
                setcookie("is", "", time()-3600);
            } else {
                setcookie("is", serialize($values), time());
            }
            if($values){
                $invitationStages = new ArrayCollection($em->getRepository(Stage::class)->findById($values));
                $potentialInvitationActivities = new ArrayCollection(array_unique($invitationStages->map(fn(Stage $s) => $s->getActivity())->getValues(), SORT_REGULAR));
            } else {
                $potentialInvitationActivities = new ArrayCollection;
            }

        } else {
            $potentialInvitationActivities = new ArrayCollection;
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

        $userActivities = new ArrayCollection(array_merge(
            $currentUser->getExternalActivities()->getValues(), 
            $currentUser->getInternalActivities()->getValues()
        ));

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

        $firstConnection = $currentUser->getLastConnected() == null;        
        $currentUser->setLastConnected(new \DateTime);
        $em->persist($currentUser);
        $em->flush();

        $locale = $request->getLocale();
        $org = $this->org;
        $repoEG = $em->getRepository(EventGroup::class);

        $eventGroups = $org->getEventGroups()->map(fn(EventGroup $eg) => [
            'id' => $eg->getId(), 
            'name' => $repoEG->getDTrans($eg,$locale,$org), 
            'evnId' => $eg->getEventGroupName()->getId()
        ])->getValues();

        //dd($request->headers->get('user_agent'));
        $renderedTwigFile = strpos($request->headers->get('user_agent'), "Mobile") === false ? 'activities_dashboard.html.twig' : 'mobile_activities_dashboard.html.twig';

        $invitationActivities = new ArrayCollection();

        foreach($potentialInvitationActivities as $potentialInvitationActivity){
            if(!$orphanActivities->contains($potentialInvitationActivity) && !$processesActivities->contains($potentialInvitationActivity)){
                $invitationActivities->add($potentialInvitationActivity);
            }
        }

        return $this->render(
            $renderedTwigFile,
            [
                'invitationActivities' => $invitationActivities,
                'displayedStatuses'  => $displayedStatuses,
                'orphanActivities'  => $orphanActivities,
                'followingActivities' => $followingActivities,
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
                'comesFromLogin' => $comesFromLogin,
                //'invitationStages' => $invitationStages
            ]
        );

        
    }


    public function retrieveNewActElmts(Request $request, TranslatorInterface $translator){
        $currentUser = $this->user;
        if(!$currentUser){
            return new JsonResponse(['error' => 'User not connected', 500]);
        }
        $newUpdates = $currentUser->getUpdates()->filter(fn(ElementUpdate $u) => $u->getViewed() == null && $u->getStage() != null);
    }

    // Sends mails recapitulating updates of the last 24 hours for users who have not seen them (by clicking on the notif button)

    /**
    * @Route("/users/notifications/check", name="checkNotificationMails")
    * @return Response
    */
    public function checkNotificationMails(Request $request, NotificationManager $notificationManager){
        
        $em = $this->em;
        $allUpdates = new ArrayCollection($em->getRepository(ElementUpdate::class)->findAll());
        $lastMailedUpdate = $allUpdates->matching(Criteria::create()->orderBy(['mailed' => Criteria::DESC]))->first();
        $now = new DateTime();
        
        if($lastMailedUpdate->getMailed() == null || $now->getTimestamp() - $lastMailedUpdate->getMailed()->getTimestamp() > 20 * 60){

            $updateMailingData = $notificationManager->retrieveUpdatesToBeMailed();
            $response = $this->forward('App\Controller\MailController::sendMail', [
                'recipients' => $updateMailingData['recipients'], 
                'settings' => [
                    'updates' => $updateMailingData['updates'],
                    'locale' => $request->getLocale(),
                ], 
                'actionType' => 'updateNotification'
            ]);
            if($response->getStatusCode() == 500){ 
                return $response; 
            } else {
               return new JsonResponse(['nupdates' => 'utd']);
            }
            
        } else {
            return new JsonResponse(['nupdates' => 'ntu']);
        }
    }


    // Recurring function which loads new updates for connected user, and checks whether there should dynamic changes to dashboards

    /**
    * @Route("/updates/retrieve", name="retrieveUpdates")
    * @return Response
    */
    public function retrieveUpdates(Request $request, TranslatorInterface $translator){
        
        //$maxRetrieved = 7;
        $currentUser = $this->user;
        if(!$currentUser){
            return new JsonResponse(['error' => 'User not connected', 500]);
        }

        $newUIds = $request->get('newUIds') ?: [] ;
        $existingUIds = $request->get('existingUIds') ?: [];
        $modifyDashboard = $request->get('md');
        $updates = $currentUser->getUpdates()->matching(Criteria::create()->orderBy(['inserted' => Criteria::DESC, 'stage' => Criteria::ASC]));
        $em = $this->em;
        $repoET = $em->getRepository(EventType::class);
        
        if($updates->count() == sizeof($newUIds) + sizeof($existingUIds)){
            $dataUpdates['ntu'] = true;
            
        } else {
            
            if($updates->count() == 0){
                $dataUpdates['noUpdatesMsg'] = $translator->trans('updates.no_update_yet');
            }

            // We need to update on 3 cases : new update (necessarily unseen by currentuser), or cancellation of an action by anotyher user, causing update deletion which was present before

            $tz = new DateTimeZone('Europe/Paris');
            $locale = $request->getLocale();
            $repoU = $this->em->getRepository(User::class);
            $dataUpdates['notifs'] = [];
            $dataUpdates['stages'] = [];
            $dataUpdates['rUIds'] = [];
            $updateIds = $updates->map(fn(ElementUpdate $u) => $u->getId())->getValues();
            foreach($newUIds as $newId){
                if (!in_array($newId,$updateIds)){
                    $dataUpdates['rUIds'][] = $newId;
                }
            }
            foreach($existingUIds as $existingId){
                if (!in_array($existingId,$updateIds)){
                    $dataUpdates['rUIds'][] = $existingId;
                }
            }
            
            if($modifyDashboard){

                $stageIds = array_merge(
                        $currentUser->getExternalStages()->map(fn(Stage $s) => $s->getId())->getValues(), 
                        $currentUser->getInternalStages()->map(fn(Stage $s) => $s->getId())->getValues()
                );

                foreach($currentUser->getExternalStages() as $extStage){
                    foreach($extStage->getEvents() as $event){
                        $eventIds[] = $event->getId(); 
                    }
                }
                foreach($currentUser->getInternalStages() as $intStage){
                    foreach($intStage->getEvents() as $event){
                        $eventIds[] = $event->getId(); 
                    }
                }
                

                $aIds = $request->get('aIds') ?: [];
                $sIds = $request->get('sIds') ?: [];
                $eIds = $request->get('eIds') ?: [];
                $progressStatuses = [
                    -5 => 'stopped',
                    -4 => 'postponed',
                    -3 => 'suspended',
                    -2 => 'reopened',
                    -1 => 'unstarted',
                    0  => 'upcoming',
                    1  => 'ongoing',
                    2  => 'completed',
                    3  => 'finalized',
                ];
               
                foreach($sIds as $sId){
                    if (!in_array($sId,$stageIds)){
                        $dataUpdates['rSIds'][] = $sId;
                    }
                }

                foreach($eIds as $eId){
                    if (!in_array($eId,$eventIds)){
                        $dataUpdates['rEIds'][] = $eId;
                    }
                }
            }

            /** @var ArrayCollection|ElementUpdate[] */
            $newUpdates = $updates->filter(fn(ElementUpdate $u) => $u->getViewed() == null);
            
            foreach ($newUpdates as $newUpdate){

                if(!in_array($newUpdate->getId(), array_merge($newUIds,$existingUIds))){

                    if($newUpdate->getActivity()){
                        // Different case : first, updates related to activities.
                        // Then can be on : act/stg creation, participation, event and related documents/comments, criterion, output
                        $comment = $newUpdate->getEventComment();
                        $document = $newUpdate->getEventDocument();
                        $event = $newUpdate->getEvent();
                        $participation = $newUpdate->getParticipation();
                        $stage = $newUpdate->getStage();
                        $activity = $newUpdate->getActivity();
                        $stageName = $stage->getName();
                        $activityName = $activity->getName();
                        $of = $translator->trans('of');
                        $theStage = $translator->trans('the_phase');
                        $theActivity = $translator->trans('the_activity');
                    
                        if($event && ($document || $comment)){
                            $theEvent = $translator->trans('the_event');
                            $eventType = $event->getEventType();
                            $eventTypeName = strtolower($repoET->getDTrans($eventType, $locale, $this->org));
                        }
    
                        $transParameters = [];
                        $transParameters['property'] = $newUpdate->getProperty();
                        $transParameters['actElmtMsg'] =
                            ($event && ($document || $comment) ? "$theEvent $of <span class=\"strong\">$eventTypeName</span> $of " : "") .
                            
                            ($activity->getStages()->count() > 1 ? 
                            "$theStage <span class=\"strong\">$stageName</span> $of $theActivity <span class=\"strong\">$activityName</span>" :
                            "$theActivity <span class=\"strong\">$activityName</span>");

                        $joinableFollowableUpdateTypes = ['join_request','join_direct','follow_request','follow_direct'];
                        $joinableFollowableStageUpdate = array_search($newUpdate->getProperty(), $joinableFollowableUpdateTypes);
    
                        if($comment != null){
                            $transParameters['update_type'] = $newUpdate->getStatus() == ElementUpdate::CREATION ? 'comment_creation' : 'comment_change';
                            $creator = $comment->getInitiator();
                            $transParameters['commentLevel'] = $comment->getParent() ? $translator->trans('updates.comment_level.withParent') : $translator->trans('updates.comment_level.withoutParent');
                        } else if($document != null){
                            $transParameters['update_type'] = $newUpdate->getStatus() == ElementUpdate::CREATION ? 'document_creation' : 'document_change';
                            $creator = $document->getInitiator();
                            $transParameters['docName'] = $document->getTitle();
                        } else if ($event != null && $document == null && $comment == null) {
                            $creator = $event->getInitiator();
                            $transParameters['update_type'] = $newUpdate->getStatus() == ElementUpdate::CREATION ? 'event_creation' : 'event_change';
                            $eventType = $event->getEventType();
                            $transParameters['type'] = strtolower(implode("_",explode(" ",$eventType->getEName()->getName())));
                            $transParameters['group'] = strtolower($eventType->getEventGroup()->getEventGroupName()->getName());
                        } else if ($participation != null) {
                            $creator = $participation->getInitiator();
                            $dataUpdate['type'] = 'p';
                        } else if ($joinableFollowableStageUpdate !== false){
                            $transParameters['update_type'] = $joinableFollowableUpdateTypes[$joinableFollowableStageUpdate];
                            $creator = $newUpdate->getInitiator();
                        } else {
                            $creator = $stage->getInitiator();
                            $dataUpdate['type'] = 's';
                            $transParameters['update_type'] = 'act_elmt_creation';
                        }
                 
                        
                    } else if  ($newUpdate->getUser()) {
                    
                        $concernedUser = $newUpdate->getUser();
                        
                        if($newUpdate->getProperty() == 'role'){
                            $transParameters['update_type'] = 'user_role';
                            $transParameters['role'] = $newUpdate->getValue() == User::ROLE_SUPER_ADMIN ? $translator->trans('profile.super_administrator') : (
                                $newUpdate->getValue() == User::ROLE_ADMIN ? $translator->trans('profile.administrator') : $translator->trans('create_user.collaborator')
                            );  
                        }
                    }

                    $dataUpdate['id'] = $newUpdate->getId();
                    $dataUpdate['picture'] = $creator->getPicture() ?: 'no-picture.png';
                    $dataUpdate['inserted'] = $this->nicetime($newUpdate->getInserted()->setTimezone($tz), $locale);
                    $transParameters['author'] = $creator->getOrganization() != $currentUser->getOrganization() ? $creator->getFullName(). ' ('.$creator->getOrganization()->getCommname().')' : $creator->getFullName();
                    $transParameters['updateLevel'] = $newUpdate->getStatus() == ElementUpdate::CREATION ? $translator->trans($document ? 'updates.document_update_level.creation' : 'updates.common_level.creation') : (ElementUpdate::CHANGE ? $translator->trans($document ? 'updates.document_update_level.update' : 'updates.common_level.update') : $translator->trans('updates.common_level.delete'));
                    $dataUpdate['msg'] = $translator->trans('updates.update_msg', $transParameters);
                    $dataUpdate['viewed'] = $newUpdate->getViewed() != null;
                    
                    $dataUpdates['notifs'][] = $dataUpdate;
                    //$nbRetrieved++;
                }

                // Checking existing/removed activities/stg
                if($modifyDashboard){
                    
                    $stage = $newUpdate->getStage();
                    if(!$event && $stage && !in_array($stage->getId(), $sIds)){
                        $stageData = [];
                        $activity = $stage->getActivity();
                        $stageData['aid'] = $activity->getId();
                        if(!in_array($activity->getId(), $aIds)){
                            $stageData['asd'] = $activity->getStartdateU();
                            $stageData['ap'] = $activity->getPeriod();
                            $stageData['an'] = $activity->getName();
                            $stageData['apr'] = $progressStatuses[$activity->getProgress()];
                            $stageData['as'] = $activity->getOrganization() == $currentUser->getOrganization() ? 1 : -1;
                        }

                        $stageData['sd'] = $stage->getStartdateU();
                        $stageData['ssed'] = $stage->getStartdate()->format($locale != 'en' ? 'j/n' : 'n/j') . ($stage->getStartdate() == $stage->getEnddate() ? '' : ' - ' . $stage->getEnddate()->format($locale != 'en' ? 'j/n' : 'n/j'));
                        $stageData['p'] = $stage->getPeriod();
                        $stageData['n'] = $stage->getName();
                        $stageData['id'] = $stage->getId();
                        $stageData['pr'] = $progressStatuses[$stage->getProgress()];
                        $stageData['s'] = $stage->getOrganization() == $currentUser->getOrganization() ? 1 : -1;

                        foreach($stage->getParticipants() as $participant){
                            $user = $participant->getUser();
                            $partData = [];
                            $clientOrgList = [];
                            $partData['id'] = $participant->getId();
                            $partData['fullname'] = $user->getFullname();
                            $isSynthetic = $user->isSynthetic();
                            if($user->getOrganization() != $currentUser->getOrganization()){
                                $clientOrg = $user->getOrganization();
                                $clientOrgName = $clientOrg->getCommname();
                                if(!in_array($clientOrg,$clientOrgList)){
                                    $clientList[] = $clientOrg;
                                    $clientOrgData = [];
                                    $clientOrgData['name'] = $clientOrgName;
                                    $clientOrgData['logo'] = $clientOrg->getLogo() ?: ($clientOrg->getWorkerFirm()->getLogo() ?: '/lib/img/org/no-picture.png');
                                    $stageData['clients'][] = $clientOrgData;
                                }
                                
                                if(!$isSynthetic){
                                    $partData['fullname'] .= " ($clientOrgName)";
                                } else {
                                    $partData['fullname'] = $clientOrgName;
                                }
                            }
                            $partData['synth'] = $isSynthetic;
                            $partData['picture'] = $isSynthetic ? '/lib/img/org/no-picture.png' : ($user->getPicture() ? 'lib/img/user/'.$user->getPicture() : '/lib/img/user/no-picture.png');
                            $stageData['participants'][] = $partData;
                        }

                        $dataUpdates['stages'][] = $stageData;

                    } else if ($event) {
                        $eventData = [];
                        $stage = $event->getStage();
                        $repoET = $em->getRepository(EventType::class);
                        $repoEG = $em->getRepository(EventGroup::class);
                        $eventData['id'] = $event->getId();
                        //if(!$comment && !$document){
                            $eventData['sid'] = $stage->getId();
                            $eventData['od'] = $event->getOnsetdateU();
                            $eventData['rd'] = $event->getExpResDateU();
                            $eventData['p'] = $event->getPeriod();
                            $eventType = $event->getEventType();
                            $eventData['t'] = $eventType->getId();
                            $eventData['tt'] = $repoET->getDTrans($eventType, $locale, $this->org);
                            $eventGroup = $eventType->getEventGroup();
                            $eventData['g'] = $eventGroup->getId();
                            $eventData['gn'] = $eventGroup->getEventGroupName()->getId();
                            $eventData['gt'] = $repoEG->getDTrans($eventGroup, $locale, $this->org);
                            $eventName = $eventType->getEName();
                            $eventData['n'] = $eventName->getId();
                            $eventData['it'] = $eventName->getIcon()->getType();
                            $eventData['in'] = $eventName->getIcon()->getName();
                            $eventData['nbd'] = $event->getDocuments()->count();
                            $eventData['nbc'] = $event->getComments()->count();
                            //} else {
                            if($comment){
                                $eventData['cid'] = $comment->getId();
                                $eventData['cct'] = $comment->getContent();
                                if($comment->getParent()){$eventData['cpid'] = $comment->getParent()->getId();}
                            } else if ($document) {
                                $eventData['did'] = $document->getId();
                                $eventData['dn'] = $document->getTitle();
                                $eventData['dt'] = $document->getType();
                                $eventData['ds'] = $document->getSize();
                                $eventData['dp'] = $document->getPath();
                            }
                        //}
                        $dataUpdates['events'][] = $eventData;
                    }
                }
            }

            $dataUpdates['nbNew'] = sizeof($dataUpdates['notifs']) - sizeof($dataUpdates['rUIds']);

        }   
        
        //dd($dataUpdates);

        return new JsonResponse($dataUpdates, 200);

    }

    /**
    * @Route("/updates/view", name="viewUpdates")
    * @return Response
    */
    public function hasBeenViewedUpdates(Request $request){
        $em = $this->em;
        $currentUser = $this->user;
        $updates = $currentUser->getUpdates()->filter(fn(ElementUpdate $u) => $u->getViewed() == null && $u->getUser() == $currentUser);
        
        foreach($updates as $update){
            $update->setViewed(new DateTime);
            $currentUser->addUpdate($update);
        }

        $em->persist($currentUser);
        $em->flush();
        return new JsonResponse(['msg' => 'success'], 200);
    }

    function nicetime(DateTime $date, string $locale)
    {
        if(empty($date)) {
            return "No date provided";
        }
        
        switch($locale){
            case 'en' :
                $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
                $nowMsg = "Now";
                break;
            case 'fr' :
                $periods = array("seconde", "minute", "heure", "jour", "semaine", "mois", "année", "décennie");
                $nowMsg = "A l'instant";
                break;
            case 'es' :
                $periods = array("secundo", "minuto", "hora", "dia", "semana", "mes", "año", "decena");
                $nowMsg = "Ahora";
                break;
        }

        $lengths         = array("60","60","24","7","4.35","12","10");
        $now             = time();
        $unix_date       = $date->getTimestamp();
    
        // check validity of date
        if(empty($unix_date)) {   
            return "Bad date";
        }

        // is it future date or past date
        if($now > $unix_date) {   
            $difference = $now - $unix_date;
            switch($locale){
                case 'fr' :
                    $tense = "il y a ";
                    break;
                case 'en' :
                    $tense = "ago";
                    break;
                case 'es' :
                    $tense = "hace";
                    break;
            }
        
        } else {
            $difference = $unix_date - $now;
            switch($locale){
                case 'fr' :
                    $tense = "dans";
                    break;
                case 'en' :
                    $tense = "from now";
                    break;
                case 'es' :
                    $tense = "en";
                    break;
            }
        }
    
        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }
    
        $difference = round($difference);
    
        if($difference != 1) {
            $periods[$j].= "s";
        }

        switch($locale){
            case 'en' :
                return $j == 0 ? $nowMsg : "$difference $periods[$j] {$tense}";
            case 'fr' :
            case 'es' :
                return $j == 0 ? $nowMsg : "{$tense} $difference $periods[$j]";
        }
        
    }

    public static function skipAccents( $str, $charset='utf-8' ) {
    
        $str = htmlentities( $str, ENT_NOQUOTES, $charset );
        $str = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
        $str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
        $str = preg_replace( '#&[^;]+;#', '', $str );
        return new Response($str);
    }

}
