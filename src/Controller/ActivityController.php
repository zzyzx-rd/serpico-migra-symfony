<?php

namespace App\Controller;

use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use App\Form\ActivityReportForm;
use App\Form\AddActivityCriteriaForm;
use App\Form\AddSurveyForm;
use App\Form\AddTemplateForm;
use App\Form\CreateCriterionForm;
use App\Form\DelegateActivityForm;
use App\Form\RequestActivityForm;
use App\Form\Type\StageUniqueParticipationsType;
use App\Entity\Activity;
use App\Entity\Participation;
use App\Entity\Answer;
use App\Entity\Client;
use App\Entity\Criterion;
use App\Entity\CriterionName;
use App\Entity\DbObject;
use App\Entity\Decision;
use App\Entity\Department;
use App\Entity\ElementUpdate;
use App\Entity\Event;
use App\Entity\EventDocument;
use App\Entity\EventGroup;
use App\Entity\EventType;
use App\Entity\ExternalUser;
use App\Entity\GeneratedImage;
use App\Entity\Grade;
use App\Entity\Icon;
use App\Entity\InstitutionProcess;
use App\Entity\IProcessParticipation;
use App\Entity\IProcessStage;
use App\Entity\Organization;
use App\Entity\OrganizationUserOption;
use App\Entity\Result;
use App\Entity\Stage;
use App\Entity\Survey;
use App\Entity\Team;
use App\Entity\Member;
use App\Entity\User;
use App\Entity\UserGlobal;
use App\Entity\UserMaster;
use App\Entity\WorkerFirm;
use App\Form\ActivityMinElementForm;
use App\Form\AddEventForm;
use App\Repository\UserRepository;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use App\Service\NotificationManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class ActivityController extends MasterController
{


    /** Most simple way of creating an activity */
    /**
     * @param string $entity
     * @param int $inpId
     * @param string $actName
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/stage/create",name="createStage")
     */
    public function createStageAction(Request $request, NotificationManager $notificationManager)
    {       
        $currentUser = $this->user;
        $organization = $this->org;
        $em = $this->em;
        $clickedBtn = $request->get('btn');
        $actId = $request->get('aid');
        $stage = new Stage;
        /** @var Form */
        $createActivityForm = $this->createForm(ActivityMinElementForm::class, $stage, ['organization' => $organization, 'currentUser' => $currentUser]);
        $createActivityForm->handleRequest($request);

        //return new JsonResponse(['coucou'],200);

        if($createActivityForm->isSubmitted()){

            $participants = $createActivityForm->get('participants')->getData();
            $participantData = $createActivityForm->get('participants');
            //dd($participants);
            
            //return ['coucou'];
            foreach($participants as $key => $participant){
                if($participantData[$key]->get('userPart')->getData() != null){
                    $usrId = $participantData[$key]->get('userPart')->getData();
                    $user = $em->getRepository(User::class)->find($usrId);
                    if($user == $currentUser){
                        $participant->setLeader(true);
                    }
                    $participant->setUser($user);
                } else {
                    $participant->setUser(null);
                }
                if ($participantData[$key]->get('teamPart')->getData() != null){
                    $teaId = $participantData[$key]->get('teamPart')->getData();
                    /** @var Team */
                    $team = $em->getRepository(Team::class)->find($teaId);
                    foreach($team->getMembers() as $member){

                        $participation = new Participation();
                        $user = $member->getUser();
                        $participation->setUser($user)
                            ->setTeam($team);
                        if($member->getUser() == $currentUser){
                            $participation->setLeader(true);
                        }
                        $stage->addParticipation($participation);
                    }

                    $em->persist($stage);

                } else {
                    $participant->setTeam(null);
                }
                if ($participantData[$key]->get('externalUserPart')->getData() != null){
                    $extId = $participantData[$key]->get('externalUserPart')->getData();
                    $externalUser = $em->getRepository(ExternalUser::class)->find($extId);
                    $participant->setExternalUser($externalUser);
                } else {
                    $participant->setExternalUser(null);
                }
            }

            if($clickedBtn == 'submit'){

                if($createActivityForm->isValid()){

                    $progress = (int) ($stage->getStartdate() < new DateTime('tomorrow'));
                    
                    $activity = $actId == 0 ? new Activity : $em->getRepository(Activity::class)->find($actId);

                    $userMaster = new UserMaster;
                    $userMaster->setUser($currentUser);

                    $stage  
                        ->setOrganization($organization)
                        ->setProgress($progress)
                        ->setStatus($progress)
                        ->addUserMaster($userMaster)
                        ->setCreatedBy($currentUser->getId());
    
                    foreach($stage->getParticipants() as $participation){
                        $participation->setActivity($activity)
                            ->setCreatedBy($currentUser->getId());
                    }

                    $toBeMailedParticipants = $stage->getUniqueParticipations()->filter(fn(Participation $p) => $p->getUser() && !$p->getUser()->isSynthetic());
                    
                    foreach($toBeMailedParticipants as $toBeMailedParticipant){
                        $recipients[] = $toBeMailedParticipant->getUser();
                    }

                    $activity->addStage($stage)
                    ->setName($stage->getName())
                    ->setProgress($progress)
                    ->setStatus($progress)
                    ->setOrganization($organization)
                    ->addUserMaster($userMaster)
                    ->setCreatedBy($currentUser->getId());
                    $em->persist($activity);
                    
                    $notificationManager->registerUpdates($activity, ElementUpdate::CREATION, 'content');
                    
                    $em->flush();
                    /*
                    $response = $this->forward('App\Controller\MailController::sendMail', [
                        'recipients' => $recipients, 
                        'settings' => [
                            'activity' => $activity->getStages()->count() > 1 ? null : $activity, 
                            'stage' => $activity->getStages()->count() > 1 ? $stage : null,
                        ], 
                        'actionType' => 'activityParticipation']
                    );
                    if($response->getStatusCode() == 500){
                        return new JsonResponse(['msg' => $response->getContent()], 500);
                    } else {
                    }*/
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
                    $locale = $request->getLocale();
                    $participants = [];
                    $clients = [];

                    foreach($stage->getParticipants() as $participant){
                        $user = $participant->getUser();
                        $partData = [];
                        $clientOrgList = [];
                        $partData['id'] = $participant->getId();
                        $partData['fullname'] = $user->getFullname();
                        $externalUser = $participant->getExternalUser();
                        $isSynthetic = $user->isSynthetic();
                        if($externalUser){
                            $clientOrg = $user->getOrganization();
                            $clientOrgName = $clientOrg->getCommname();
                            if(!in_array($clientOrg,$clientOrgList)){
                                $clientList[] = $clientOrg;
                                $clientOrgData = [];
                                $clientOrgData['name'] = $clientOrgName;
                                $clientOrgData['logo'] = $clientOrg->getLogo() ?: ($clientOrg->getWorkerFirm()->getLogo() ?: '/lib/img/org/no-picture.png');
                                $clients[] = $clientOrgData;
                            }
                            
                            if(!$isSynthetic){
                                $partData['fullname'] .= " ($clientOrgName)";
                            } else {
                                $partData['fullname'] = $clientOrgName;
                            }
                        }
                        $partData['synth'] = $isSynthetic;
                        $partData['picture'] = $isSynthetic ? '/lib/img/org/no-picture.png' : ($user->getPicture() ?: '/lib/img/user/no-picture.png');
                        $participants[] = $partData;
                    }
                    
                    return new JsonResponse([
                        'aid' => $activity->getId(),
                        'ap' => $activity->getPeriod(),
                        'an' => $activity->getName(),
                        'apr' => $progressStatuses[$activity->getProgress()],
                        'asd' => $activity->getStartdateU(),
                        'sd' => $stage->getStartdateU(),
                        'ssed' => $stage->getStartdate()->format($locale != 'en' ? 'j/n' : 'n/j') . ($stage->getStartdate() == $stage->getEnddate() ? '' : ' - ' . $stage->getEnddate()->format($locale != 'en' ? 'j/n' : 'n/j')),
                        'p' => $stage->getPeriod(),
                        'n' => $stage->getName(),
                        'id' => $stage->getId(), 
                        'pr' => $progressStatuses[$stage->getProgress()],
                        'participants' => $participants,
                        'clients' => $clients
                    ], 200);
                } else {
            
                    $errors = $this->buildErrorArray($createActivityForm);
                    return $errors;
                }

            } else {

                $activity = new Activity;
                $progress = $createActivityForm->get('startdate')->isValid() ? (int) ($stage->getStartdate() < new DateTime('tomorrow')) : 0;

                foreach($createActivityForm->get('participants') as $participantForm){
                    if($participantForm->isValid()){
                        $participant = $participantForm->getData();
                        $participant->setActivity($activity);
                        $stage->addParticipant($participant);
                    }
                }

                if($createActivityForm->get('name')->isValid()){
                    $stage->setName($createActivityForm->get('name')->getData());
                } else {
                    $stage->setName("Phase 1");
                }

                $activityNb = $organization->getActivities()->count() + 1;
                $activity->setName("Activity $activityNb");
                $activity->setProgress($progress);
                $stage->setProgress($progress);
                $activity->addStage($stage);
                $em->persist($activity);
                $em->flush();

                return $this->redirectToRoute('manageActivityElement',['entity' => 'activity', 'elmtId' => $activity->getId()]);

            }
        }
    }

    /**
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @Route("/participant/create",name="createParticipant")
     */
    public function createParticipant(Request $request){
        $currentUser = $this->user;
        $organization = $this->org;
        $em = $this->em;
        $uname = $request->get('uname');
        $type = $request->get('type');
        $stgId = $request->get('sid');
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $firm = $_POST['firm'];
        $email = $_POST['email'];

        $newUser = false;

        $client = empty($_POST['cid']) ? new Client : $em->getRepository(Client::class)->find($_POST['cid']);
        $entityName = $type == 'u' ? $firm : $uname;

        if(empty($_POST['wid'])){

            $workerFirm = new WorkerFirm;
            $workerFirm->setCommonName($entityName)
                ->setName($entityName)
                ->setCreatedBy($currentUser->getId());
            
            $em->persist($workerFirm);
            $em->flush();
        } else {
            $workerFirm = $em->getRepository(WorkerFirm::class)->find($_POST['wid']);
        }

        if(empty($_POST['oid'])){

            $clientOrganization = new Organization;
            $now = new DateTime;
            $clientOrganization
                ->setCommname($entityName)
                ->setType($type != 'u' ? strtoupper($type) : 'F')
                ->setExpired($now->add(new DateInterval('P21D')))
                ->setWeightType('role')
                ->setPlan(ORGANIZATION::PLAN_PREMIUM)
                ->setWorkerFirm($workerFirm)
                ->setCreatedBy($currentUser->getId());

            $em->persist($clientOrganization);

            $this->forward('App\Controller\OrganizationController::updateOrgFeatures', ['organization' => $clientOrganization, 'nonExistingOrg' => true, 'createdAsClient' => true]);

        } else {

            $clientOrganization = $em->getRepository(Organization::class)->find($_POST['oid']);
            if($clientOrganization != $currentUser->getOrganization() && empty($_POST['cid'])){
                $this->forward('App\Controller\OrganizationController::updateOrgFeatures', ['organization' => $clientOrganization, 'nonExistingOrg' => false, 'createdAsClient' => true]);
            }

        }

        $synthUser = $em->getRepository(User::class)->findOneBy(['organization' => $clientOrganization, 'synthetic' => true]);
        
        if($clientOrganization != $organization && (empty($_POST['cid']) || empty($_POST['oid']))){

            /** @var ExternalUser */
            $externalSynthUser = new ExternalUser;
            $externalSynthUser->setUser($synthUser)
                ->setOwner(true)->setFirstname($organization->getCommname())
                ->setSynthetic(true)
                ->setLastname($type == 'u' ? $firm : $uname);

            $client
            ->setName($type == 'u' ? $firm : $uname)
            ->addExternalUser($externalSynthUser)
            ->setOrganization($organization)
            ->setClientOrganization($clientOrganization)
            ->setWorkerFirm($workerFirm)
            ->setCreatedBy($currentUser->getId());

            $em->persist($client);
            
        }

        if($type != 'f'){
            if(!empty($email)){
                $user = $em->getRepository(User::class)->findOneBy(['organization' => $clientOrganization, 'email' => $_POST['email']]);
            } else {
                $user = $em->getRepository(User::class)->findOneBy(['organization' => $clientOrganization, 'firstname' => $_POST['firstname'], 'lastname' => $_POST['lastname']]);
            }
            if(!$user){

                
                $newUser = true;
                $user = new User;
                $user->setFirstname($firstname)
                ->setLastname($lastname)
                ->setEmail(!empty($email) ? $email : null)
                ->setToken(!empty($email) ? md5(rand()) : null)
                ->setUsername("$firstname $lastname")
                ->setRole(
                    empty($_POST['oid']) ? USER::ROLE_ADMIN : 
                        ($organization->getPlan() == ORGANIZATION::PLAN_FREE || $organization->getPlan() != ORGANIZATION::PLAN_FREE && new DateTime() > $organization->getExpired() ? USER::ROLE_ADMIN : USER::ROLE_AM)
                )
                ->setCreatedBy($currentUser->getId());
                $clientOrganization->addUser($user);
                
                $userGlobal = new UserGlobal();
                $userGlobal->setUsername("$firstname $lastname")
                ->addUserAccount($user);
                $em->persist($userGlobal);
            }

            if($clientOrganization != $organization){
                
                $externalUser = new ExternalUser;
                $externalUser->setFirstname($firstname)
                    ->setLastname($lastname)
                    ->setEmail(!empty($email) ? $email : null)
                    ->setWeightValue(100)
                    ->setClient($client)
                    ->setCreatedBy($currentUser->getId());
                $user->addExternalUser($externalUser);
                $em->persist($clientOrganization);
            } else {
                $externalUser = null;
            }
            
        } else {

            $user = $synthUser;
            $externalUser = $externalSynthUser;
        }

        if($stgId){
            $stage = $em->getRepository(Stage::class)->find($stgId);
            $participation = new Participation;
            $participation//->setTeam()
                ->setExternalUser($externalUser)
                ->setUser($user);
            $stage->addParticipation($participation);
            $em->persist($stage);
        } else {
            $participation = null;
        }
        
        $em->flush();
        if(!empty($email)){

            $settings = [];
            if($newUser){
                $settings['tokens'][] = $user->getToken();
            }
            $settings['invitingUser'] = $currentUser;
            $settings['invitingOrganization'] = $currentUser->getOrganization();
            $recipients[] = $user;
            if($externalUser->getEmail() != ""){
                $response = $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'externalInvitation']);
                if($response->getStatusCode() == 500){
                    return $response->getContent();
                }
            }
            $externalUser->setClient($client)->setUser($user);
        }

        $picFolder = $type == 'u' || $type == 'i' ? 'user' : ($type == 'f' ? 'org' : 'team');
        $fn = $type == 'u' ? "$firstname $lastname" : $firm;
        $tn = $type == 'u' && $clientOrganization != $organization ? "$firstname $lastname ($firm)" : "$firstname $lastname";
        $outputType = $type == 't' ? 't' : ($externalUser ? 'eu' : 'u');

        $responseArray = ['wid' => $workerFirm, 'oid' => $clientOrganization->getId(), 'uid' => $user->getId(), 'euid' => $externalUser ? $externalUser->getId() : '', 'pic' => "lib/img/$picFolder/no-picture.png", 'fn' => $fn, 'tn' => $tn, 'type' => $outputType];
        if($participation){
            $responseArray['pid'] = $participation->getId();
        }

        return new JsonResponse($responseArray, 200);

    }

    // Creating activity V1 : attributing leadership to current user and redirecting to parameters
    /**
     * @param string $entity
     * @param int $inpId
     * @param string $actName
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{entity}/{inpId}/{actName}/create",name="activityInitialisation")
     */
    public function addActivityId(string $entity, int $inpId = 0, string $actName = '')
    {
        $currentUser = $this->user;
        $usrId = $currentUser->getId();
        $usrOrg = $currentUser->getOrganization();
        $isActivity = $entity === 'activity';

        $activity = new Activity ;
        $stage = new Stage ;
        //$criterion = $isActivity ? new Criterion : new TemplateCriterion;
        //$participant = $isActivity ? new Participation : new TemplateParticipation;

        $startDate = new DateTime;
        $activityStartDate = clone $startDate;
        $activityEndDate = clone $startDate;
        $activityGStartDate = clone $startDate;
        $activityGEndDate = clone $startDate;

        $activityCount = $usrOrg->getActivities()->count();
        $nextActIndex = $activityCount + 1;
        $actDefaultName = "Activity $nextActIndex";
        $stgDefaultName = "Stage 1";
        $activityName = $actName !== '' ? $actName : $actDefaultName;
        $stageName = $actName !== '' ? $actName : $stgDefaultName;

        $userMaster = new UserMaster;
        $userMaster->setUser($currentUser);

        $activity
            ->setName($activityName)
            ->setOrganization($currentUser->getOrganization())
            ->addUserMaster($userMaster)
            ->addStage($stage)
            ->setCreatedBy($currentUser->getId());

        if ($inpId !== 0) {
            $activity->setInstitutionProcess($this->em->getRepository(InstitutionProcess::class)->find($inpId));
        }

        $stage
            ->setName($stageName)
            ->addUserMaster($userMaster)
            ->setWeight(1)
            ->setStartdate($activityStartDate)
            ->setEnddate($activityEndDate)
            ->setMode(1)
            ->setProgress(STAGE::PROGRESS_ONGOING)
            ->setCreatedBy($usrId);

        if ($isActivity) {
            $activity
                ->setStatus(-1);
            $stage
                ->setOrganization($usrOrg);
        }

        $this->em->persist($activity);
        $this->em->flush();
        return $this->json(['message' => 'success to create activity', 'redirect' => $this->generateUrl('manageActivityElement', ['entity' => 'activity', 'elmtId' => $activity->getId()])], 200);
    }

    // Delegation of activity creation

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/activity/delegate", name="activityDelegate")
     */
    public function delegateActivityAction(Request $request)
    {
        $currentUser = $this->user;
        if (!$currentUser) {
            throw new Exception('unauthorized');
        }
        $currentUserId = $currentUser->getId();
        $em = $this->em;
        /** @var UserRepository */
        $repoU = $em->getRepository(User::class);
        $repoCN = $em->getRepository(CriterionName::class);
        $repoO = $em->getRepository(Organization::class);

        /** @var FormFactory */
        $delegateActivityForm = $this->createForm(
            DelegateActivityForm::class,
            null,
            [
                'standalone' => true,
            ]
        )->handleRequest($request);

        if ($delegateActivityForm->isValid()) {
            // 1 - Create Activity (similar to addActivityId without redirection)
            $startDate = new DateTime;
            $activityName = $delegateActivityForm->get('activityName')->getData();
            /** @var User */
            $activityLeader = $delegateActivityForm->get('activityLeader')->getData();
            $activityDescription = $delegateActivityForm->get('activityDescription')->getData();

            /** @var CriterionName */
            $defaultCriterionName = $repoCN->findOneBy(['organization' => $currentUser->getOrganization()]);

            $userMaster = new UserMaster;
            $userMaster->setUser($activityLeader);

            $activity = (new Activity)
                ->setName($activityName)
                ->addUserMaster($userMaster)
                ->setOrganization($currentUser->getOrganization())
                ->setObjectives($activityDescription)
                ->setStatus(-2)
                ->setCreatedBy($currentUserId);
            $em->persist($activity);
            $em->flush();

            $stage = (new Stage)
                ->setName($activityName)
                ->addUserMaster($userMaster)
                ->setWeight(1)
                ->setMode(1)
                ->setStartdate(clone $startDate)
                ->setEnddate(clone $startDate)
                ->setCreatedBy($currentUserId);

            /*$criterion = (new Criterion)
                ->setCName($defaultCriterionName)
                ->setStage($stage)
                ->setCreatedBy($currentUserId);

            $stage->addCriterion($criterion);
            */
            $activity->addStage($stage);

            //$em->persist($criterion);
            $em->persist($stage);
            $em->flush();

            // 2 - Send mail to the designated activity leader
            self::sendMail($app, [$activityLeader], 'delegate', [
                'actId' => $activity->getId(),
                'delegatorFullName' => $currentUser->getFullName(),
                'activityName' => $activityName,
                'description' => $activityDescription,
            ]);

        } else {
            $errors = $this->buildErrorArray($delegateActivityForm);
            return $errors;
        }

        return new JsonResponse(['message' => 'success'], 200);
    }

    // Request of activity creation

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/activity/request", name="activityRequest")
     */
    public function requestActivityAction(Request $request)
    {
        $em = $this->em;
        $repoU = $em->getRepository(User::class);
        $currentUser = $this->user;
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }
        $organization = $currentUser->getOrganization();
        /** @var FormFactory */
        $requestActivityForm = $this->createForm(RequestActivityForm::class, null, ['standalone' => true]);
        $requestActivityForm->handleRequest($request);

        if ($requestActivityForm->isValid()) {
            $formData = $requestActivityForm->getData();

            // 1 - Create Activity (similar to addActivityId without redirection, also creating a stage for decision filling purposes)
            $activity = new Activity;
            $activityName = $requestActivityForm->get('activityName')->getData();
            $activityObjectives = $requestActivityForm->get('activityDescription')->getData();
            $isRequestAnon = $requestActivityForm->get('discloseRequester')->getData();
            $recipientsId = [];

            // Recipients are depending on the submitted form.
            if (isset($_POST['request_activity_form']['specificRecipients'])) {
                $recipientsId = $_POST['request_activity_form']['specificRecipients'];
            }

            $recipients = ($recipientsId) ? $repoU->findBy(['id' => $recipientsId]) : $repoU->findBy(['role' => [1, 4], 'orgId' => $currentUser->getOrgId()]);

            $activity->setName($activityName);
            // Activity is created without Master user, waiting for validation before attributing leadership to validating user
            $activity->setOrganization($organization);
            $activity->setObjectives($activityObjectives);
            $activity->setStatus(-3);

            $em->persist($activity);
            //$em->flush();
            $stage = new Stage;
            $startDate = new DateTime;
            $activityStartDate = clone $startDate;
            $activityEndDate = clone $startDate;
            $activityGStartDate = clone $startDate;
            $activityGEndDate = clone $startDate;
            $stage->setName($activityName)->setActivity($activity)->setStartdate($activityStartDate)->setEnddate($activityEndDate)->setGstartdate($activityGStartDate)->setGenddate($activityGEndDate);
            $stage->setCreatedBy($currentUser->getId());
            $em->persist($stage);

            $criterion = new Criterion;
            $criterion->setStage($stage)->setCName($organization->getCriterionNames()->first());
            $criterion->setCreatedBy($currentUser->getId());
            $em->persist($criterion);
            $em->flush();

            $settings = [];
            $settings['activity'] = $activity;
            $settings['requester'] = $currentUser;

            //2 - Send mail to recipients, set them as deciders
            foreach ($recipients as $recipient) {

                $decision = new Decision;
                $decision->setType(1)->setRequester($currentUser->getId())->setAnonymousRequest($isRequestAnon)->setAnonymousDecision(false)->setDecider($recipient->getId())->setOrganization($organization)->setActivity($activity)->setStage($stage);
                $decision->setCreatedBy($currentUser->getId());
                $em->persist($decision);

            }

            MasterController::sendMail($app, $recipients, 'request', $settings);

            $em->flush();
            return new JsonResponse(['message' => 'success', 'recipientIds' => $recipientsId], 200);

        } else {
            $errors = $this->buildErrorArray($requestActivityForm);
            return $errors;
        }

    }

    /**
     * @param Request $request
     * @param $actId
     * @param $action
     * @return bool|JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/activity/request/{actId}/{action}", name="activityResolveRequest")
     */
    public function resolveActivityRequest(Request $request, $actId, $action)
    {

        $em = $this->em;
        $currentUser = $this->user;
        $repoU = $em->getRepository(User::class);
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }
        $activity = $em->getRepository(Activity::class)->find($actId);
        $settings = [];
        $settings['activity'] = $activity;

        $decision = ($action != 'cancel') ?
        $em->getRepository(Decision::class)->findOneBy(['activity' => $activity, 'decider' => $currentUser->getId()]) :
        $em->getRepository(Decision::class)->findOneBy(['activity' => $activity, 'requester' => $currentUser->getId()])
        ;
        $requester = $repoU->find($decision->getRequester());
        $recipients = [];
        $recipients[] = $requester;
        $decision->setDecided(new DateTime);

        // Here we consider that the activity is validated or discarded thanks to a single person.
        // But in a enhanced approach, we could consider the approval of a certain number of users,
        // thus we should leave validated field to NULL until conditions are met
        $decision->setValidated(new DateTime);
        $decision->setValidator($currentUser->getId());

        if ($action == 'cancel') {
            $activity->setStatus(-5);
            $decision->setResult(-1);
            MasterController::sendMail($app, $recipients, 'activityCancellation', $settings);

        } elseif ($action == 'discard') {
            $activity->setStatus(-4);
            $decision->setResult(0);
            if ($decision->isAnonymousDecision()) {
                $settings['decider'] = $currentUser;
            }
            MasterController::sendMail($app, $recipients, 'activityRefusal', $settings);

        } elseif ($action == 'validate') {
            $activity->setStatus(-2);
            $decision->setResult(1);

            
            $validateRequestForm = $this->createForm(DelegateActivityForm::class, null, ['standalone' => true, 'request' => true]);
            $validateRequestForm->handleRequest($request);

            if ($validateRequestForm->isValid()) {

                //1 - Create Activity (similar to addActivityId without redirection)
                $activityName = $validateRequestForm->get('activityName')->getData();
                $activityDescription = $validateRequestForm->get('activityDescription')->getData();

                if ($validateRequestForm->get('ownCreation')->getData() == false) {
                    $activityLeaderId = $validateRequestForm->get('activityLeader')->getData();
                    $activityLeader = $repoU->find($activityLeaderId);
                } else {
                    $activityLeader = $currentUser;
                }

                $userMaster = new UserMaster();
                $userMaster->setUser($activityLeader);

                $activity
                    ->setName($activityName)
                    ->setObjectives($activityDescription)
                    ->addUserMaster($userMaster);
                $stage = $activity->getStages()->first();
                $stage->addUserMaster($userMaster);
                $activity->setStatus(-2);
                $em->persist($activity);
                $em->persist($stage);
                $em->flush();

                $settings['activity'] = $activity;

                if ($decision->isAnonymousDecision()) {
                    $settings['decider'] = $currentUser;
                }

                MasterController::sendMail($app, $recipients, 'activityValidation', $settings);

                if ($validateRequestForm->get('ownCreation')->getData() == false) {

                    $recipients = [];
                    $recipients[] = $activityLeader;
                    MasterController::sendMail($app, $recipients, 'activityAssignation', $settings);
                }

            } else {
                $errors = $this->buildErrorArray($validateRequestForm);
                return $errors;
            }
        }

        $em->persist($decision);
        $em->persist($activity);
        $em->flush();
        return true;

    }

    /**
     * @Route("/activity/event/{eveId}", name="updateEvent")
     */
    public function updateEvent(
        Request $request,
        int $eveId,
        FileUploader $fileUploader,
        NotificationManager $notificationManager
    ) {
        /** @var int */
        $stgId = $request->get('sid');
        /** @var int */
        $actId = $request->get('aid');
        /** @var int */
        $notification = $request->get('mids');

        $em = $this->em;
        /** @var Event */
        $event = $eveId != 0 ? $em->getRepository(Event::class)->find($eveId) : new Event();
        $eventInitOnsetDate = $event->getOnsetDate();
        $currentUser = $this->user;
        $stage = $em->getRepository(Stage::class)->find($stgId);
        $eventForm = $this->createForm(AddEventForm::class, $event, ['currentUser' => $currentUser, 'standalone' => true]);
        $eventForm->handleRequest($request);
        if($eventForm->isSubmitted() && $eventForm->isValid()){

            $now = new DateTime;
            if(!$event->getOnsetDate()){!$eventInitOnsetDate ? $event->setOnsetDate($now) : $event->setOnsetDate(null);}
            $event->setStage($stage)
                ->setOrganization($this->org);

            if(!$eveId){
                $notificationManager->registerUpdates($event, ElementUpdate::CREATION);
            }

            $documentsForm = $eventForm->get('documents');
            
            foreach($documentsForm as $documentForm){
                /** @var UploadedFile */
                $documentFile = $documentForm->get('file')->getData();

                /** @var EventDocument */
                $document = $documentForm->getData();
                $documentFileInfo = $fileUploader->upload($documentFile);
                $document->setPath($documentFileInfo['name'])
                    ->setType($documentFileInfo['extension'])
                    ->setSize($documentFileInfo['size'])
                    ->setMime($documentFileInfo['mime']);
                $em->persist($document);

                $notificationManager->registerUpdates($document, ElementUpdate::CREATION);

            }

            $comments = $event->getComments();
            foreach($comments as $comment){
                $comment->setAuthor($currentUser);
                $notificationManager->registerUpdates($comment, ElementUpdate::CREATION, 'content');
            }

           

            /*
            if($notification){
                $recipients = $event->getStage()->getUniqueParticipations()->filter(fn(Participation $p) => $p->getUser() != $this->user)->map(fn(Participation $p) => $p->getUser())->getValues();
                $settings['event'] = $event;
                $response = $this->forward('App\Controller\MailController::sendMail', [
                    'recipients' => $recipients, 
                    'settings' => [
                        'event' => $event,
                        'update' => $eveId != 0
                    ], 
                    'actionType' => 'eventNotification'
                ]);
                if($response->getStatusCode() == 500){ return $response; };
            }*/
                
            $event
                ->setOrganization($this->org)
                ->setCreatedBy($currentUser->getId());
            $em->persist($event);
            $em->flush();

        } else {
            $errors = $this->buildErrorArray($eventForm);
            return $errors;
        }

        $locale = $request->getLocale();
        $eventType = $event->getEventType();
        $eventGroup = $eventType->getEventGroup();
        $eventName = $eventType->getEName();
        $repoET = $em->getRepository(EventType::class);
        $repoEG = $em->getRepository(EventGroup::class);

        $response = [
            'sid' => $stage->getId(),
            'eid' => $event->getId(),
            'od' => $event->getOnsetdateU(),
            'rd' => $event->getExpResDateU(),
            'p' => $event->getPeriod(),
            't' => $eventType->getId(),
            'tt' => $repoET->getDTrans($eventType, $locale, $this->org),
            'g' => $eventGroup->getId(),
            'gn' => $eventGroup->getEventGroupName()->getId(),
            'gt' => $repoEG->getDTrans($eventGroup, $locale, $this->org),
            'it' => $eventName->getIcon()->getType(),
            'in' => $eventName->getIcon()->getName(),
            'nbc' => $event->getComments()->count(),
            'nbd' => $event->getDocuments()->count()
        ];

        return new JsonResponse($response,200);
    }
    
    /**
     * @param Request $request
     * @param int $stgId
     * @param string $entity
     * @param int $elmtId
     * @return JsonResponse|void
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{entity}/stage/{stgId}/participant/validate/{elmtId}", name="validateParticipant")
     */
    public function validateParticipantAction(
        Request $request,
        int $stgId,
        string $entity,
        int $elmtId
    ) {

        $currentUser = $this->user;

        if (!$currentUser) {
            throw new Exception('current user is null');
        }

        /** @var int */
        $type = $request->get('type');
        /** @var string */
        $precomment = $request->get('precomment');
        /** @var bool */
        $leader = $request->get('leader');
        /** @var string */
        $pEntity = $request->get('pEntity');
        /** @var int */
        $pId = $request->get('pId');

        $em = $this->em;
        switch($entity){
            case 'iprocess' :
                $repoS = $em->getRepository(IProcessStage::class);
                $repoP = $em->getRepository(IProcessParticipation::class);
                break;
            case 'activity' :
                $repoS = $em->getRepository(Stage::class);
                $repoP = $em->getRepository(Participation::class);
                break;
        }
        $repoU = $em->getRepository(User::class);
        $repoPEntity = $pEntity == 'user' ? $repoU : $em->getRepository(Team::class);
        /** @var User|Team */
        $pElement = $repoPEntity->find($pId);

        $repoG = $em->getRepository(Grade::class);

        /** @var Stage|TemplateStage|IProcessStage */
        $stage = $repoS->find($stgId);
        var_dump($stage->getId());
        /** @var Activity|TemplateActivity|InstitutionProcess */
        $element = $entity != 'iprocess' ? $stage->getActivity() : $stage->getInstitutionProcess();
        $activityOrganization = $element->getOrganization();

        $stage->currentUser = $currentUser;
        $element->currentUser = $currentUser;

        if (!$stage->isModifiable()) {
            throw new Exception('unauthorized');
        }

        /** @var Participation|IProcessParticipation|TemplateParticipation|null */
        $participation = $repoP->find($elmtId);


        if (!$participation) {

            // Checking if there is compatibility user/team, otherwise return exception
            if($pEntity == 'user'){

                $doublonParticipant = $stage->getParticipants()->filter(function($p) use ($pId){
                    return $p->getUser()->getId() == $pId;
                })->first();

                if($doublonParticipant){
                    return new JsonResponse(['msg' => 'duplicateWithTeam', 'name' => $doublonParticipant->getTeam()->getName()],500);
                }

            } else {

                $doublonParticipant = $stage->getParticipants()->filter(function($p) use ($pElement){
                        return $pElement->getMembers()->exists(function(int $i, Member $tu) use ($p){
                            return $tu->getUser() == $p->getUsrId();
                        });
                    })->first();

                if($doublonParticipant){
                    return new JsonResponse(['msg' => 'duplicateWithUser', 'name' => $doublonParticipant->getDirectUser()->getFullname()],500);
                }
            }

            $iterableElements = sizeof($stage->getCriteria()) > 0 ? $stage->getCriteria() : new ArrayCollection([$stage]);

        } else {

            // Checking whether participant has already been subject to grades (by himself or others), and adapting grade type if necessary
            // If we do not change related type, it will alter results computation !!!
            $gradedPElementGrades = $repoG->findBy([
                'stage' => $stage,
                'gradedUsrId' => $participation->getUser()->getId(),
                'gradedTeaId' => $participation->getTeam() ? $participation->getTeam()->getId() : null
            ]);

            foreach ($gradedPElementGrades as $gradedPElementGrade) {
                if ($gradedPElementGrade->getType() == $type) {
                    break;
                } else {
                    $gradedPElementGrade->setType($type);
                    $em->persist($gradedPElementGrade);
                }
            }


            // Getting participations depending of part elmt type
            if($pEntity == 'user'){
                /** @var Participation[]|IProcessParticipation[]|TemplateParticipation[] */
                $participations = $repoP->findBy([
                    'stage' => $stage,
                    'user' => $participation->getUser(),
                    'team' => null,
                ]);
            } else {
                 /** @var Participation[]|IProcessParticipation[]|TemplateParticipation[] */
                 $participations = $repoP->findBy([
                    'stage' => $stage,
                    'team' => $participation->getTeam(),
                ]);
            }
            $iterableElements = $participations;
        }

        if($entity == 'activity' && ($participation == null || $participation->getType() == 0 && $type != 0)){

            // Checking if we need to unvalidate participations (we decide to unlock all stage participations and not only the modified one)
            $completedStageParticipations = $stage->getParticipants()->filter(function(Participation $p){
                return $p->getStatus() == 3;
            });

            if($completedStageParticipations->count() > 0){

                $mailRecipients = [];
                $mailRecipientIds = [];
                foreach($completedStageParticipations as $completedParticipation){
                    if(in_array($completedParticipation->getUsrId(),$mailRecipientIds) === false){
                        $mailRecipients[] = $completedParticipation->getDirectUser();
                        $mailRecipientIds[] = $completedParticipation->getUsrId();
                    }
                    $completedParticipation->setStatus(2);
                    $em->persist($completedParticipation);
                }
                $em->flush();

                self::sendMail(
                    $app,
                    $mailRecipients,
                    'unvalidateOutputDueToChange',
                    ['stage' => $stage, 'actElmt' => 'participant']
                );
            }
        }

        $participation = null;

        foreach ($iterableElements as $iterableElement) {

            // If participations are existing for submitted participant
            if ($iterableElement instanceof Participation || $iterableElement instanceof IProcessParticipation || $iterableElement instanceof TemplateParticipation) {
                $participation = $iterableElement;
                $criterion = $participation->getCriterion();
                $consideredParticipations[] = $participation;

            } else {

                $consideredParticipations = [];

                switch($entity){
                    case 'activity' :
                        if($pEntity == 'user'){
                            $participation = new Participation;
                            $consideredParticipations[] = $participation;
                        } else {
                            foreach($pElement->getCurrentMembers() as $currentMember){
//                                var_dump($currentMember->getUsrId());
                                $participation = new Participation;
                                $participation->setUser($currentMember)
                                    ->setExternalUser($currentMember->getExternalUser());
                                $consideredParticipations[] = $participation;
                            }
                        }
                        break;
                    case 'iprocess' :
                        if($pEntity == 'user'){
                            $participation = new IProcessParticipation;
                            $consideredParticipations[] = $participation;
                        } else {
                            foreach($pElement->getCurrentMembers() as $currentMember){
                                $participation = new IProcessParticipation;
                                $participation->setUser($currentMember)
                                    ->setExternalUser($currentMember->getExternalUser());
                                $consideredParticipations[] = $participation;
                            }
                        }
                        break;
                    case 'template' :
                        if($pEntity == 'user'){
                            $participation = new TemplateParticipation;
                            $consideredParticipations[] = $participation;
                        } else {
                            foreach($pElement->getCurrentMembers() as $currentMember){
                                $participation = new TemplateParticipation;
                                $participation->setUsrId($currentMember->getUsrId())
                                    ->setExtUsrId($currentMember->getExtUsrId());
                                $consideredParticipations[] = $participation;
                            }
                        }
                        break;
                }
                $criterion = sizeof($stage->getCriteria()) > 0 ? $iterableElement : null;
            }
            foreach($consideredParticipations as $consideredParticipation){

                if (!$consideredParticipation) {
                    continue; return;
                }

                $consideredParticipation
                    ->setType($type)
                    ->setStage($stage)
                    ->setCreatedBy($currentUser->getId());

                if($pEntity == 'team'){
                    $consideredParticipation->setTeam($pElement);
                } else {
                    $consideredParticipation->setUser($pElement);
                }


                $userOrganization = $pElement->getOrganization();
                $currentUserOrganization = $currentUser->getOrganization();

                if($userOrganization != $currentUserOrganization){
                    /** @var Client */
                    $client = $currentUserOrganization->getClients()->filter(function(Client $c) use ($userOrganization){
                        return $c->getClientOrganization() == $userOrganization;
                    })->first();
                    $externalUser = $client->getExternalUsers()->filter(function(ExternalUser $e) use ($pElement){
                        return $e->getUser() == $pElement;
                    })->first();

                    $consideredParticipation->setExternalUser($externalUser);
                }


                if($leader){$consideredParticipation->setLeader($leader);}

                if ($consideredParticipation instanceof IProcessParticipation) {
                    $consideredParticipation->setInstitutionProcess($element);
                } else {
                    $consideredParticipation->setActivity($element);
                }

                if ($precomment) {
                    $consideredParticipation->setPrecomment($precomment);
                } else if ($consideredParticipation->getPrecomment() !== null) {
                    $consideredParticipation->setPrecomment(null);
                }

                if ($consideredParticipation instanceof Participation) {
                    //$consideredParticipation->setIsMailed(false);

                    if ($leader) {
                        // Removing leadership to all old previous (criterion) leading participations

                        /** @var Criterion|Stage */
                        $queryableElmt = $criterion ?: $stage;

                        $previousOwningParticipants = $queryableElmt->getParticipations()->filter(function(Participation $p) use ($consideredParticipation){
                            return ($consideredParticipation->getTeam() ?
                                $p->getTeam() != $consideredParticipation->getTeam() :
                                $p->getUser() != $consideredParticipation->getUser())
                                && $p->isLeader();
                        });

                        foreach ($previousOwningParticipants as $previousOwningParticipant) {
                            $previousOwningParticipant->setLeader(false);
                            $queryableElmt->addParticipant($previousOwningParticipant);
                        }
                        //$consideredParticipation->setLeader(true);
                    } else {
                        //$consideredParticipation->setLeader(false);
                    }
                }

                $criterion ? $criterion->addParticipation($consideredParticipation) : $stage->addParticipation($consideredParticipation);

            }

            if($iterableElement instanceof Criterion){
                $stage->addCriterion($criterion);
            }

        }

        /*
        $mailed = null;
        $finalizable = (
            $entity == 'activity'
            && $participation && $participation->getType() != 0
            && (count($stage->getCriteria()) > 0 || count($stage->getCriteria()) > 0)
        );
        */

        if ($entity == 'activity' and $element->getStatus() === 1) {
            $mailedUsrIds = [];
            $recipients = [];

            foreach($consideredParticipations as $consideredParticipation){


                if(!$consideredParticipation->getisMailed()){

                    $participationUsrId = $consideredParticipation->getUser()->getId();

                    if(in_array($participationUsrId,$mailedUsrIds) === false){
                        $mailedUsrIds[] = $participationUsrId;
                        $user = $repoU->find($participationUsrId);
                        if(!($user->getLastname() == 'ZZ' && $user->getEmail() == null)){
                            $repicients[] = $user;
                        }
                    }
                    $consideredParticipation->setIsMailed(true);
                    $em->persist($consideredParticipation);
                }
            }

            if(sizeof($recipients) > 0){
                $sendMail = self::sendMail(
                    $app,
                    $recipients,
                    'activityParticipation',
                    [
                        'activity' => $element,
                        'stage' => $stage,
                    ]
                );
            }
        }

        $em->persist($stage);
        $em->flush();

        /*$user = $participation->getDirectUser();
        if ( !($user instanceof User) ) {
            throw new \Exception;
        }*/

        return new JsonResponse([
            'eid' => $consideredParticipations[0]->getId(),
            //'finalizable' => $finalizable,
            'pElement' => [
                'picture' => $pEntity == 'user' ?
                    ($pElement->getPicture() ?? 'no-picture.png') :
                    ($pElement->getPicture() ? 'team/'.$pElement->getPicture() : 'team/no-picture.png')
            ],
            'canSetup' => !$element->getActiveModifiableStages()->isEmpty(),
        ], 200);
    }

    /**
     * @param Request $request
     * @param int $teaId
     * @param int $memId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/team/{teaId}/team-user/validate/{memId}", name="validateMember")
     */
    public function validateMemberAction(
        Request $request,
        int $teaId,
        int $memId
    ) {
        $currentUser = $this->user;
        if (!$currentUser) {
            throw new Exception('current user is null');
        }

        /** @var int */
        $usrId = $request->get('user');
        /** @var bool */
        $leader = (bool) $request->get('leader');

        $em = $this->em;
        $repoU = $em->getRepository(User::class);
        $repoT = $em->getRepository(Team::class);
        $repoM = $em->getRepository(Member::class);
        $repoP = $em->getRepository(Participation::class);
        $concernedUser = $repoU->find($usrId);

        /** @var Team */
        $team = $teaId != 0 ? $repoT->find($teaId) : new Team;
        if($teaId == 0){
            $organization = $currentUser->getOrganization();
            $team->setOrganization($organization)
                ->setName($organization->getTeams()->count() + 1);
        }

        if (!$team->isModifiable() && $teaId != 0) {
            throw new Exception('unauthorized');
        }

        /** @var Member|null */
        $member = null;

        if($memId != 0){
            $member = $repoM->find($memId);
        } else {
            $member = $repoM->findOneBy(['user' => $concernedUser, 'team' => $team]) ?: new Member;
        }

        /** @var User */
        $addedUser = $repoU->find($usrId);

        if($memId == 0 || $member->isDeleted()){

            // Sending a welcome to the new team joiner
            $settings = [];
            $addedRecipients = [$addedUser];
            if (count($addedRecipients) > 0) {
                $settings['team'] = $team;
                self::sendMail($app, $addedRecipients, 'teamCreation', $settings);
            }

            //We unvalidate team user participations of non-completed activities, in order for them to grade team newcomer
            $teamParticipations = new ArrayCollection($repoP->findBy(['team' => $teaId], ['activity' => 'ASC']));
            $uncompletedTeamParticipations = $teamParticipations->filter(function (Participation $participation) {
                return $participation->getActivity()->getStatus() < 2;
            });

            $recipients                     = [];
            $settings                       = [];
            $settings['addedUsersFullName'] = [$addedUser->getFullName()];
            $settings['teamName']           = $team->getName();

            // Unvalidating team user participants of uncompleted activities, in order for them to grade team newcomer

            $unvalidatingTeamParticipations = $uncompletedTeamParticipations->matching(Criteria::create()->where(Criteria::expr()->eq("status", 3)));
            $consideredActivity             = null;
            foreach ($unvalidatingTeamParticipations as $unvalidatingTeamParticipation) {
                if ($unvalidatingTeamParticipation->getActivity() != $consideredActivity) {
                    if (count($recipients) > 0) {
                        $settings['activity'] = $consideredActivity;
                        $this->sendMail($app, $recipients, 'unvalidatedGradesTeamJoiner', $settings);
                        $recipients = [];
                    }
                    $recipients[]       = $unvalidatingTeamParticipation->getUser($app);
                    $consideredActivity = $unvalidatingTeamParticipation->getActivity();
                } else {
                    $user = $unvalidatingTeamParticipation->getUser($app);
                    if (array_search($user, $recipients) === false) {
                        $recipients[] = $unvalidatingTeamParticipation->getUser($app);
                    }
                }
                $unvalidatingTeamParticipation->setStatus(2);
                $em->persist($unvalidatingTeamParticipation);
            }

            // After last iteration we also need to send according mails to remaining team users
            if (count($unvalidatingTeamParticipations) > 0) {
                $settings['activity'] = $consideredActivity;
                $this->sendMail($app, $recipients, 'unvalidatedGradesTeamJoiner', $settings);
            }

            // Adding new team user participations in these uncompleted activities

            $consideredActivity = null;
            foreach ($uncompletedTeamParticipations as $teamParticipation) {

                $activity = $teamParticipation->getActivity();
                if ($activity != $consideredActivity && $activity->getStatus() <= 1) {
                    $consideredActivity = $activity;
                    $linesToDuplicate   = $teamParticipations->matching(Criteria::create()->where(Criteria::expr()->eq("activity", $activity))->andWhere(Criteria::expr()->eq("usrId", $teamParticipation->getUsrId())));
                    foreach ($linesToDuplicate as $line) {
                        $Participation = new Participation;
                        $Participation->setLeader($line->isLeader())
                            ->setType($line->getType())
                            ->setActivity($line->getActivity())
                            ->setUser($concernedUser)
                            ->setCreatedBy($currentUser->getId())
                            ->setTeam($repoT->findOneBy(['id' => $teaId]))
                            ->setStage($line->getStage())
                            ->setCriterion($line->getCriterion())
                            ->setStatus(0)
                            ->setMWeight($line->getMWeight())
                            ->setPrecomment($line->getPrecomment())
                            ->setIsMailed($line->getIsMailed());
                        $em->persist($Participation);
                    }
                }
            }
        }

        $member->setLeader($leader)
        ->setUsrId($usrId)
        ->setCreatedBy($currentUser->getId());

        if($member->isDeleted()){
            $member->toggleIsDeleted();
        }

        if($addedUser->getOrganization() != $currentUser){
            $repoC = $em->getRepository(Client::class);
            $externalUser = $repoC->findOneBy(['organization' => $currentUser->getOrganization(), 'clientOrganization' => $addedUser->getOrganization()])
                ->getExternalUsers()->filter(function(ExternalUser $e) use ($addedUser){
                    return $e->getUser() == $addedUser;
                })->first();
            $member->setExtUsrId($externalUser->getId());
        }

        $team->addMember($member);
        $em->persist($team);
        $em->flush();

        return new JsonResponse([
            'tid' => $team->getId(),
            'eid' => $member->getId(),
            'user' => [
                'picture' => $addedUser->getPicture() ?? 'no-picture.png'
            ],
            'canSetup' => $team->isModifiable(),
        ], 200);

    }

    /**
     * @param Request $request
     * @param $stgId
     * @param $entity
     * @param $elmtId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{_locale}/{entity}/stage/{stgId}/participant/delete/{elmtId}", name="deleteParticipant")
     */
    public function deleteParticipantAction(Request $request, $stgId, $entity, $elmtId)
    {

        $em = $this->em;
        $currentUser = $this->user;

        switch ($entity) {
            case 'activity':
                $repoS = $em->getRepository(Stage::class);
                $repoP = $em->getRepository(Participation::class);
                $stage = $repoS->find($stgId);
                $stageOrganization = $stage->getActivity()->getOrganization();
                break;
            case 'template':
                $repoS = $em->getRepository(TemplateStage::class);
                $repoP = $em->getRepository(TemplateParticipation::class);
                $stage = $repoS->find($stgId);
                $stageOrganization = $stage->getActivity()->getOrganization();
                break;
            case 'iprocess':
                $repoS = $em->getRepository(IProcessStage::class);
                $repoP = $em->getRepository(IProcessParticipation::class);
                $stage = $repoS->find($stgId);
                $stageOrganization = $stage->getInstitutionProcess()->getOrganization();
                break;
        }

        $currentUserOrganization = $currentUser->getOrganization();
        $stageLeader = $repoP->findOneBy(['stage' => $stage,'leader' => true]);
        $userStageLeader = $repoP->findOneBy(['stage' => $stage,'leader' => true, 'user' => $currentUser]);
        $hasUserInfGrantedRights = ($stageLeader && $userStageLeader || !$stageLeader && $stage->getMasterUser() == $currentUser);
        $hasPageAccess = true;

        if ($currentUser->getRole() != 4 && ($stageOrganization != $currentUserOrganization || $currentUser->getRole() != 1 && !$hasUserInfGrantedRights)) {
            $hasPageAccess = false;
        }

        if (!$hasPageAccess) {
            return $this->render('errors/403.html.twig');
        } else {

            $participation = $repoP->find($elmtId);
            $stage->removeParticipant($participation);
            $em->persist($stage);
            $em->flush();
            if ($entity == 'activity') {
                $activeParticipants = $stage->getParticipants()->filter(function (Participation $p) {
                    return $p->getType() != 0;
                });
                $finalizable = count($activeParticipants) > 0 && (count($stage->getCriteria()) > 0 || $stage->getSurvey() != null);
            } else {
                $finalizable = null;
            }

            return new JsonResponse(['message' => 'Success!', 'finalizable' => $finalizable], 200);
        }

    }

    /**
     * @param Request $request
     * @param $memId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/remove-team-user/{memId}", name="deleteMember")
     */
    public function deleteMemberAction(Request $request, $memId)
    {

        $em = $this->em;
        $repoO = $em->getRepository(Organization::class);
        $repoU = $em->getRepository(User::class);
        $repoM = $em->getRepository(Member::class);
        /** @var Member */
        $member = $repoM->find($memId);
        $memberUsrId = $member->getUser()->getId();
        /** @var Team */
        $team = $member->getTeam();
        $teamOrganization = $team->getOrganization();
        $currentUser = $this->user;

        $currentUserOrganization = $currentUser->getOrganization();
        $teamLeader = $repoM->findOneBy(['team' => $team,'leader' => true]);
        $userTeamLeader = $repoM->findOneBy(['team' => $team, 'leader' => true, 'usrId' => $currentUser->getId()]);
        $hasUserInfGrantedRights = ($teamLeader && $userTeamLeader || !$teamLeader && $team->getCreatedBy() == $currentUser->getId());
        $hasPageAccess = true;

        if ($currentUser->getRole() != 4 && ($teamOrganization != $currentUserOrganization || $currentUser->getRole() != 1 && !$hasUserInfGrantedRights)) {
            $hasPageAccess = false;
        }

        if (!$hasPageAccess) {
            return $this->render('errors/403.html.twig');
        } else {

            $member
                ->setDeleted(new DateTime)
                ->setIsDeleted(true);
            $em->persist($member);
            $em->flush();

            $settings['team'] = $team;
            $removedUser = $repoU->find($memberUsrId);
            self::sendMail($app, [$removedUser], 'teamUserRemoval', $settings);

            return new JsonResponse(['message' => 'Success!'], 200);
        }

    }


    /**
     * @param Request $request
     * @return false|string|JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/criterion-name/add", name="addCriterionName")
     */
    public function createOrganizationCriterionAction(Request $request)
    {

        $currentUser = $this->user;

        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        $organization = $currentUser->getOrganization();
        $em = $this->em;
        /** @var FormFactory */
        
        $repoCL = $em->getRepository(CriterionName::class);
        $repoI = $em->getRepository(Icon::class);

        $organizationCriteriaNames = $repoCL->findBy(['organization' => [null, $organization]]);
        $createCriterionForm = $this->createForm(
            CreateCriterionForm::class,
            null,
            ['standalone' => true]
        )->handleRequest($request);

        $submittedCriterionName = $createCriterionForm->get('name')->getData();
        $submittedIcon = $_POST['icon'];

        if ($submittedIcon == null) {
            return new JsonResponse([
                'icon' => 'You must choose a icon in order to create a new criterion',
            ], 500);
        }

        foreach ($organizationCriteriaNames as $organizationCriteriaName) {
            if ($organizationCriteriaName->getName() == $submittedCriterionName && $organizationCriteriaName->getType() == $createCriterionForm->get('type')->getData()) {
                $createCriterionForm->get('name')->addError(new FormError('There is already a criterion with such name and category in your organization. Please choose another one or select this criterion'));
                break;
            }
        }

        if ($createCriterionForm->isValid()) {

            $criterionName = new CriterionName;
            $criterionName->setName($submittedCriterionName)->setType($createCriterionForm->get('type')->getData())->setIcon($repoI->findOneByName(substr($submittedIcon, 3)));
            $criterionName->setCreatedBy($currentUser->getId());
            $organization->addCriterionName($criterionName);
            $em->persist($organization);
            $em->flush();

            return json_encode(['cnaId' => $criterionName->getId(), 'cnaName' => $criterionName->getName(), 'cnaType' => $criterionName->getType()], 200);

        } else {

            $errors = $this->buildErrorArray($createCriterionForm);
            return $errors;
        }

    }


    /**
     * @param Request $request
     * @param $actId
     * @return JsonResponse
     * @Route("/activity/{actId}/gstages", name="getGradableStages")
     */
    public function getGradableStagesAction(Request $request, $actId)
    {
        $em = $this->em;
        $currentUser = $this->user;

        if (!$currentUser) {
            return new JsonResponse('error', 500);
        }
        $gradableStages = $em->getRepository(Activity::class)->find($actId)->getActiveGradableStages();
        $output = [];
        foreach ($gradableStages as $gradableStage) {
            $entry = [];
            $entry['id'] = $gradableStage->getId();
            $entry['name'] = $gradableStage->getName();
            $output[] = $entry;
        }
        return new JsonResponse(['stages' => $output], 200);
    }

    /**
     * @param Request $request
     * @param $stgId
     * @param null $usrId
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/activity/survey/form/answer/{stgId}", name="answerSurvey")
     * @Route("/activity/survey/form/answer/{stgId}", name="AnswerRequest")
     */
    public function answerRequestAction(Request $request, $stgId, $usrId = null)
    {
        $error="";
        $em = $this->em;
        
        $repo0 = $em->getRepository(Survey::class);
        $repo2 = $em->getRepository(Participation::class);
        $repo1 = $em->getRepository(Answer::class);
        $survey = $repo0->findOneBy(array('stage' => $stgId));
        $currentUser = $this->user;;
        $surveyfield = $survey->getFields();
        $activity = $repo2->findOneBy(array('stage' => $survey->getStage(), 'usrId' => $currentUser->getId()));
        $tblId=[];

        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        if (!$survey->getStage()->getGraderUsers()->contains($currentUser)) {
            return $this->render('errors/403.html.twig');
        }

        foreach ( $survey->getAnswers() as $answer){
            array_push($tblId,$answer->getField()->getId());
        }

        if ($repo1->findBy(array('survey' => $survey, 'createdBy' => $currentUser->getId())) == null) {
            for ($i = 0; $i < count($surveyfield); $i++) {
                $answer = new Answer;
                $answer->setSurvey($survey);
                $answer->setField($surveyfield[$i]);
                $answer->setCreatedBy($currentUser->getId());
                $answer->setParticipant($activity);
                $survey->AddUserAnswer($answer);
                $em->persist($answer);

            }

            $activity->setStatus(2);
            $em->flush();
        } else {
            if (count($survey->getAnswers()) < count($survey->getFields())) {
                $nb = count($survey->getFields()) - count($survey->getAnswers());

                for ($i = 0; $i < $nb; $i++) {
                    $answer = new Answer;
                    $p = 0;

                    while (array_search($surveyfield[$p]->getId(),$tblId)!=false){
                        $p++;

                    }

                    $answer->setField($surveyfield[$p]);
                    array_push($tblId ,$surveyfield[$p]->getId());
                    $p = -1;
                    $answer->setCreatedBy($currentUser->getId());
                    $answer->setParticipant($activity);
                    $survey->addAnswer($answer);
                    $em->persist($answer);


                }
                $em->flush();

            }
        }

        $answerForm = $this->createForm(AddSurveyForm::class, $survey, ['edition' => false, 'survey' => $survey, 'user' => $currentUser]);


        $answerForm->handleRequest($request);
        $answerRequest = $answerForm->getData();
        if ($answerForm->isSubmitted() ) {

            if ($answerForm->isValid()) {

                $count = count($answerRequest->getAnswers());

                for ($i = 0; $i < $count; $i++) {

                    if (empty($answerRequest->getAnswers()[$i])) {


                        if ($surveyfield[$i]->getType() == 'UC') {

                            $answer = new Answer;
                            $answer->setField($surveyfield[$i]);
                            $answer->setCreatedBy($currentUser->getId());
                            $answer->setParticipant($activity);
                            $answer->setDesc(0);
                            $survey->addAnswer($answer);
                            $em->persist($answer);
                        }


                    } else {

                        if ($answerRequest->getAnswers()[$i]->getField()->getType() == 'MC') {
                            $survey->getAnswers()[$i]->setDesc(serialize($answerRequest->getAnswers()[$i]->getDesc()));
                        }
                        if ($answerRequest->getAnswers()[$i]->getField()->getType() == 'UC') {
                            if (!isset($answerRequest->getAnswers()[$i])) {

                                $answerRequest->getAnswers()[$i]->setDesc(0);
                            } else {


                                $answerRequest->getAnswers()[$i]->setDesc(1);

                            }
                        }
                    }
                    if (isset($_POST['finish'])) {

                        $activity->setStatus(3);
                        $em->flush();
                        $this->checkStageComputability( $request,  $app, $survey->getStage());


                    }
                }


                $em->flush();
                return $this->redirectToRoute('myActivities');
            }

        }

        $stage = $survey->getStage();
        $edition=true;
        if($activity->getStatus()==3) {
            $edition=false;
        }



        return $this->render('answer_survey.html.twig',
            ['surId' => $survey->getId(),
                'survey' => $survey,
                'form' => $answerForm->createView(),
                'edition' => $edition

            ]);
    }

    /**
     * @param Request $request
     * @param $stgId
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("}/activity/{stgId}/grade", name="newStageGrade")
     */
    public function gradeAction(Request $request, $stgId)
    {
        $em = $this->em;
        $currentUser = $this->user;
        

        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        $repoP = $em->getRepository(Participation::class);
        $repo1 = $em->getRepository(Answer::class);
        $stage = $em->getRepository(Stage::class)->find($stgId);
        if($stage->getSurvey()!=null){
            return $this->redirectToRoute('answerSurvey');
        }
        // Prevent access if current user is not a grader (i.e has none non-passive participations)
        if (!$stage->getGraderUsers()->contains($currentUser)) {
            return $this->render('errors/403.html.twig');
        } else {
            $this->updateStageGrades($stage);

            $userParticipations = $repoP->findBy(['stage' => $stage, 'usrId' => $currentUser->getId()]);
            
            $stageUniqueParticipationsForm = $this->createForm(StageUniqueParticipationsType::class, $stage, ['standalone' => true, 'mode' => 'grade']);
            $stageUniqueParticipationsForm->handleRequest($request);

            // We save grades and redirect when either grades are saved, or finalized without errors, otherwise reload of page with errors shown in modal

            if ($stageUniqueParticipationsForm->isSubmitted() && !($stageUniqueParticipationsForm->get('finalize')->isClicked() && !$stageUniqueParticipationsForm->isValid())) {

                if ($stageUniqueParticipationsForm->get('finalize')->isClicked()) {
                    foreach ($userParticipations as $userParticipation) {
                        $userParticipation->setStatus(3);
                        $em->persist($userParticipation);
                    }
                } else {
                    foreach ($userParticipations as $userParticipation) {
                        $userParticipation->setStatus(2);
                        $em->persist($userParticipation);
                    }

                }
                $em->flush();
                $val = $this->checkStageComputability($request, $app, $stage, false);
                $val = $this->checkStageComputability($request, $app, $stage);
                return $this->redirectToRoute('myActivities',['sortingType' => 'p']);
            }

            return $this->render('activity_grade_new.html.twig',
                [
                    'stage' => $stage,
                    'form' => $stageUniqueParticipationsForm->createView(),
                ]);
        }
    }

    public function newGradeAction(Request $request, $stgId)
    {

        $em = $this->em;
        $currentUser = $this->user;

        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }
        $repoO = $em->getRepository(Organization::class);
        $repoP = $em->getRepository(Participation::class);
        $repoG = $em->getRepository(Grade::class);
        $stage = $em->getRepository(Stage::class)->find($stgId);
        $organization = $currentUser->getOrganization();
        $id = $currentUser->getId();
        $actOrganization = $stage->getActivity()->getOrganization();
        $userIsParticipant = false;

        $existingParticipants = new ArrayCollection($repoP->findBy(['stage' => $stage], ['criterion' => 'DESC', 'type' => 'DESC']));
        foreach ($existingParticipants as $existingParticipant) {
            if ($existingParticipant->getUsrId() == $currentUser->getId()) {
                $userIsParticipant = true;
                break;
            }
        }

        if (!$userIsParticipant) {
            return $this->render('errors/403.html.twig');
        } else {

            //Get all participants

            $existingParticipants = new ArrayCollection($repoP->findBy(['stage' => $stage], ['criterion' => 'DESC', 'type' => 'DESC']));

            $existingTeamParticipants = $existingParticipants->matching(Criteria::create()->where(Criteria::expr()->neq("team", null))->orderBy(['team' => Criteria::ASC]));
            $existingUserParticipants = $existingParticipants->matching(Criteria::create()->where(Criteria::expr()->eq("team", null)));
            //$existingTeamParticipants = $repoP->findBy(['stage' => $stage, 'usrId' => null], ['criterion' => 'DESC', 'type' => 'DESC']);
            //$existingUserParticipants = $repoP->findBy(['stage' => $stage, 'team' => null], ['criterion' => 'DESC', 'type' => 'DESC']);
            $userTeam = null;

            $foundUserTeam = false;
            $existingParticipantUsersId = [];
            $existingParticipantTeamsId = [];

            $selectedTeam = null;

            // In case stage gets teams, we need to determine whether currentuser is part of a team in order to display participants properly
            // In case he is part of a team, we create individual grades for each team member graded by current user

            // Reminder : cannot be both selected as part of a team and individually
            if (count($existingTeamParticipants) > 0) {

                foreach ($existingTeamParticipants as $existingTeamParticipant) {

                    $userBelongsToTeam = false;

                    if ($existingTeamParticipant->getTeam() != $selectedTeam) {

                        $existingParticipantTeamsId[] = $existingTeamParticipant->getTeam()->getId();
                        $selectedTeam = $existingTeamParticipant->getTeam();

                        // Check if current user belongs to the team
                        foreach ($selectedTeam->getActiveMembers() as $participantTeamUser) {

                            if ($participantTeamUser->getUser() == $currentUser) {

                                foreach ($selectedTeam->getActiveMembers() as $member) {
                                    $existingParticipantUsersId['teaId'][] = $selectedTeam->getId();
                                    $existingParticipantUsersId['usrId'][] = $member->getUsrId();
                                    $existingParticipantUsersId['type'][] = $existingTeamParticipant->getType();
                                }
                                $userBelongsToTeam = true;
                                $userTeam = $selectedTeam;
                                break;

                            }
                        }
                        if (!$userBelongsToTeam) {
                            $existingParticipantUsersId['teaId'][] = $selectedTeam->getId();
                            $existingParticipantUsersId['usrId'][] = null;
                            $existingParticipantUsersId['type'][] = $existingTeamParticipant->getType();
                        }
                    }
                }
            }

            foreach ($existingUserParticipants as $existingUserParticipant) {
                $existingParticipantUsersId['teaId'][] = null;
                $existingParticipantUsersId['usrId'][] = $existingUserParticipant->getUsrId();
                $existingParticipantUsersId['type'][] = $existingUserParticipant->getType();
            }

            /*
            foreach ($existingParticipants as $existingParticipant) {
            $participants[] = $existingParticipant->toArray();
            }*/

            //Arranging results : on first places, third parties, then activity managers, other collaborators and connected user at the very
            $orderedParticipants = [];
            $thirdParties = [];
            $activityManagers = [];
            $currentUserStageStatus = [];
            $currentUserParticipations = [];

            foreach ($existingParticipants as $existingParticipant) {

                if ($existingParticipant->getUsrId() == $id) {
                    $currentUserParticipations[] = $existingParticipant;
                } else {
                    //If participant is HR or AM...
                    if ($existingParticipant->getUser()->getRole() < 3) {
                        $activityManagers[] = $existingParticipant;
                    } //...or he is third-party
                    elseif ($existingParticipant->getType() == 0) {
                        $thirdParties[] = $existingParticipant;
                    } else {
                        $orderedParticipants[] = $existingParticipant;
                    }
                }
            }

            //Sorting results
            foreach ($activityManagers as $activityManager) {
                array_unshift($orderedParticipants, $activityManager);
            }

            foreach ($thirdParties as $thirdParty) {
                array_unshift($orderedParticipants, $thirdParty);
            }

            if (isset($currentUserParticipant)) {
                array_push($orderedParticipants, $currentUserParticipant);
            }

            //Add the grade of the participant : new name for the list of participants : $part

            $part = [];

            $grades = new ArrayCollection;
            $currentUserPersonGrades = new ArrayCollection;
            $currentUserTeamGrades = new ArrayCollection;

            $uniqueParticipantUsersId = array_unique($existingParticipantUsersId['usrId']);
            $uniqueParticipantTeamsId = array_unique($existingParticipantUsersId['teaId']);

            foreach ($currentUserParticipations as $currentUserParticipation) {

                $participationCriterion = $currentUserParticipation->getCriterion();

                if ($stage->getMode() != 1) {
                    $criterionStageGrade = $repoG->findOneBy([
                        'participant' => $currentUserParticipation,
                        'gradedUsrId' => null,
                        'gradedTeaId' => null,
                        'criterion' => $participationCriterion]);
                    if ($criterionStageGrade == null) {
                        $criterionStageGrade = new Grade;
                        $criterionStageGrade
                            ->setParticipation($currentUserParticipation)
                            ->setTeam($currentUserParticipation->getTeam())
                            ->setGradedUsrId(null)
                            ->setGradedTeaId(null)
                            ->setCriterion($participationCriterion)
                            ->setStage($participationCriterion->getStage())
                            ->setActivity($participationCriterion->getStage()->getActivity());
                        $em->persist($criterionStageGrade);
                    }
                }

                // Get or set user grades, if stage is not a purely evaluated one

                if ($stage->getMode() != 0) {

                    foreach ($uniqueParticipantUsersId as $key => $existingParticipantUserId) {

                        // If stage in itself can be evaluated (not pure people) then we add a fictious participant having no team or user id

                        // We do not select pure teams by ensuring below variable is not null
                        if ($existingParticipantUserId != null) {
                            $criterionUserGrade = $repoG->findOneBy([
                                'participant' => $currentUserParticipation,
                                'gradedUsrId' => $existingParticipantUsersId['usrId'][$key],
                                'gradedTeaId' => $existingParticipantUsersId['teaId'][$key],
                                'criterion' => $participationCriterion]);

                            if ($criterionUserGrade == null) {
                                $criterionUserGrade = new Grade;
                                $criterionUserGrade
                                    ->setParticipation($currentUserParticipation)
                                    ->setTeam($currentUserParticipation->getTeam())
                                    ->setGradedUsrId($existingParticipantUserId)
                                    ->setGradedTeaId($existingParticipantUsersId['teaId'][$key])
                                    ->setCriterion($participationCriterion)
                                    ->setStage($participationCriterion->getStage())
                                    ->setActivity($participationCriterion->getStage()->getActivity())
                                    ->setType($existingParticipantUsersId['type'][$key]);
                                $em->persist($criterionUserGrade);
                            }

                            $currentUserPersonGrades->add($criterionUserGrade);

                        }
                    }
                    // Get or set team grades
                    foreach ($uniqueParticipantTeamsId as $key => $existingParticipantTeamId) {

                        if ($existingParticipantTeamId != null) {

                            if ($userTeam == null || $userTeam->getId() != $existingParticipantTeamId) {

                                $participationCriterion = $currentUserParticipation->getCriterion();
                                $criterionTeamGrade = $repoG->findOneBy(['participant' => $currentUserParticipation, 'gradedTeaId' => $existingParticipantTeamId, 'criterion' => $participationCriterion]);
                                if ($criterionTeamGrade == null) {
                                    $criterionTeamGrade = new Grade;
                                    $criterionTeamGrade
                                        ->setParticipant($currentUserParticipation)
                                        ->setTeam($currentUserParticipation->getTeam())
                                        ->setGradedTeaId($existingParticipantTeamId)
                                        ->setCriterion($participationCriterion)
                                        ->setStage($participationCriterion->getStage())
                                        ->setActivity($participationCriterion->getStage()->getActivity())
                                        ->setType($existingParticipantUsersId['type'][$key]);
                                    $em->persist($criterionTeamGrade);
                                }
                                $currentUserTeamGrades->add($criterionTeamGrade);
                            }
                        }
                    }

                }

            }

            try {
                $em->flush();
            } catch (OptimisticLockException $e) {
            } catch (ORMException $e) {
            }

            return $this->render('activity_grade.html.twig',
                    [
                        'stage' => $stage,
                        'currentUserPersonGrades' => $currentUserPersonGrades,
                        'currentUserTeamGrades' => $currentUserTeamGrades,
                    ]);


        }
    }

    public function updateStageGrades($stage)
    {

        $em = $this->em;

        $repoP = $em->getRepository(Participation::class);
        $repoG = $em->getRepository(Grade::class);

        //Get all participants

        $totalParticipants = new ArrayCollection($repoP->findBy(['stage' => $stage], ['criterion' => 'DESC', 'type' => 'DESC']));
        $existingParticipants = $totalParticipants->matching(Criteria::create()->where(Criteria::expr()->neq("criterion", null))->orWhere(Criteria::expr()->neq("survey", null)));
        $existingTeamParticipants = $existingParticipants->matching(Criteria::create()->where(Criteria::expr()->neq("team", null))->orderBy(['team' => Criteria::ASC]));
        $existingUserParticipants = $existingParticipants->matching(Criteria::create()->where(Criteria::expr()->eq("team", null)));
        //$existingTeamParticipants = $repoP->findBy(['stage' => $stage, 'usrId' => null], ['criterion' => 'DESC', 'type' => 'DESC']);
        //$existingUserParticipants = $repoP->findBy(['stage' => $stage, 'team' => null], ['criterion' => 'DESC', 'type' => 'DESC']);
        $userTeam = null;

        $foundUserTeam = false;
        $existingParticipantUsersId = [];
        $existingParticipantTeamsId = [];

        $selectedTeam = null;

        // In case stage gets teams, we need to determine whether currentuser is part of a team in order to display participants properly
        // In case he is part of a team, we create individual grades for each team member graded by current user

        // Reminder : cannot be both selected as part of a team and individually
        if (count($existingTeamParticipants) > 0) {

            foreach ($existingTeamParticipants as $existingTeamParticipant) {

                $userBelongsToTeam = false;

                if ($existingTeamParticipant->getTeam() != $selectedTeam) {

                    $existingParticipantTeamsId[] = $existingTeamParticipant->getTeam()->getId();
                    $selectedTeam = $existingTeamParticipant->getTeam();

                    // Check if current user belongs to the team
                    foreach ($selectedTeam->getActiveMembers() as $participantTeamUser) {

                        if ($participantTeamUser->getUser() == $currentUser) {

                            foreach ($selectedTeam->getActiveMembers() as $member) {
                                $existingParticipantUsersId['teaId'][] = $selectedTeam->getId();
                                $existingParticipantUsersId['usrId'][] = $member->getUsrId();
                                $existingParticipantUsersId['type'][] = $existingTeamParticipant->getType();
                            }
                            $userBelongsToTeam = true;
                            $userTeam = $selectedTeam;
                            break;

                        }
                    }
                    if (!$userBelongsToTeam) {
                        $existingParticipantUsersId['teaId'][] = $selectedTeam->getId();
                        $existingParticipantUsersId['usrId'][] = null;
                        $existingParticipantUsersId['type'][] = $existingTeamParticipant->getType();
                    }
                }
            }
        }

        foreach ($existingUserParticipants as $existingUserParticipant) {
            $existingParticipantUsersId['teaId'][] = null;
            $existingParticipantUsersId['usrId'][] = $existingUserParticipant->getUsrId();
            $existingParticipantUsersId['type'][] = $existingUserParticipant->getType();
        }

        //Arranging results : on first places, third parties, then activity managers, other collaborators and connected user at the very
        $orderedParticipants = [];
        $thirdParties = [];
        $activityManagers = [];
        $currentUserStageStatus = [];
        $currentUserParticipations = [];

        foreach ($existingParticipants as $existingParticipant) {

            if ($existingParticipant->getUsrId() == $currentUser->getId()) {
                $currentUserParticipations[] = $existingParticipant;
            } else {
                //If participant is HR or AM...
                if ($existingParticipant->getUser()->getRole() < 3) {
                    $activityManagers[] = $existingParticipant;
                } //...or he is third-party
                elseif ($existingParticipant->getType() == 0) {
                    $thirdParties[] = $existingParticipant;
                } else {
                    $orderedParticipants[] = $existingParticipant;
                }
            }
        }

        //Sorting results
        foreach ($activityManagers as $activityManager) {
            array_unshift($orderedParticipants, $activityManager);
        }

        foreach ($thirdParties as $thirdParty) {
            array_unshift($orderedParticipants, $thirdParty);
        }

        if (isset($currentUserParticipant)) {
            array_push($orderedParticipants, $currentUserParticipant);
        }

        //Add the grade of the participant : new name for the list of participants : $part

        $uniqueParticipantUsersId = array_unique($existingParticipantUsersId['usrId']);
        $participantTeamsId = array_filter($existingParticipantUsersId['teaId']);
        $uniqueParticipantTeamsId = array_unique($participantTeamsId);

        foreach ($currentUserParticipations as $currentUserParticipation) {

            $participationCriterion = $currentUserParticipation->getCriterion();

            if ($stage->getMode() != STAGE::GRADED_PARTICIPANTS) {
                $criterionStageGrade = $repoG->findOneBy([
                    'participant' => $currentUserParticipation,
                    'gradedUsrId' => null,
                    'gradedTeaId' => null,
                    'criterion' => $participationCriterion]);
                if ($criterionStageGrade == null) {
                    $criterionStageGrade = new Grade;
                    $criterionStageGrade
                        ->setParticipant($currentUserParticipation)
                        ->setTeam($currentUserParticipation->getTeam())
                        ->setGradedUsrId(null)
                        ->setGradedTeaId(null)
                        ->setCriterion($participationCriterion)
                        ->setStage($participationCriterion->getStage())
                        ->setActivity($participationCriterion->getStage()->getActivity());
                    $stage->addGrade($criterionStageGrade);
                    //$em->persist($criterionStageGrade);
                }

                if ($stage->getMode() == STAGE::GRADED_STAGE) {
                    $usrTeamGrades = $stage->getGrades()->matching(Criteria::create()->where(Criteria::expr()->neq("gradedTeaId", null))->orWhere(Criteria::expr()->neq("gradedUsrId", null)));
                    foreach ($usrTeamGrades as $usrTeamGrade) {
                        $stage->removeGrade($usrTeamGrade);
                    }
                }
            }

            // Get or set user grades, if stage is not a purely evaluated one

            if ($stage->getMode() != STAGE::GRADED_STAGE) {

                foreach ($uniqueParticipantUsersId as $key => $existingParticipantUserId) {

                    // If stage in itself can be evaluated (not pure people) then we add a fictious participant having no team or user id

                    // We do not select pure teams by ensuring below variable is not null
                    if ($existingParticipantUserId != null) {
                        $criterionUserGrade = $repoG->findOneBy([
                            'participant' => $currentUserParticipation,
                            'gradedUsrId' => $existingParticipantUsersId['usrId'][$key],
                            'gradedTeaId' => $existingParticipantUsersId['teaId'][$key],
                            'criterion' => $participationCriterion]);

                        if ($criterionUserGrade == null) {
                            $criterionUserGrade = new Grade;
                            $criterionUserGrade
                                ->setParticipant($currentUserParticipation)
                                ->setTeam($currentUserParticipation->getTeam())
                                ->setGradedUsrId($existingParticipantUserId)
                                ->setGradedTeaId($existingParticipantUsersId['teaId'][$key])
                                ->setCriterion($participationCriterion)
                                ->setStage($participationCriterion->getStage())
                                ->setActivity($participationCriterion->getStage()->getActivity())
                                ->setType($existingParticipantUsersId['type'][$key]);
                            $stage->addGrade($criterionUserGrade);
                            $em->persist($criterionUserGrade);
                        }
                    }
                }

                // Get or set team grades
                foreach ($uniqueParticipantTeamsId as $key => $existingParticipantTeamId) {

                    if ($userTeam == null || $userTeam->getId() != $existingParticipantTeamId) {

                        $participationCriterion = $currentUserParticipation->getCriterion();
                        $criterionTeamGrade = $repoG->findOneBy(['participant' => $currentUserParticipation, 'gradedTeaId' => $existingParticipantTeamId, 'criterion' => $participationCriterion]);
                        if ($criterionTeamGrade == null) {
                            $criterionTeamGrade = new Grade;
                            $criterionTeamGrade
                                ->setParticipant($currentUserParticipation)
                                ->setTeam($currentUserParticipation->getTeam())
                                ->setGradedTeaId($existingParticipantTeamId)
                                ->setCriterion($participationCriterion)
                                ->setStage($participationCriterion->getStage())
                                ->setActivity($participationCriterion->getStage()->getActivity())
                                ->setType($existingParticipantUsersId['type'][$key]);
                            $stage->addGrade($criterionTeamGrade);
                            $em->persist($criterionTeamGrade);
                        }
                    }
                }

                if ($stage->getMode() == STAGE::GRADED_PARTICIPANTS) {
                    $stageGrades = $stage->getGrades()->matching(Criteria::create()->where(Criteria::expr()->eq("gradedTeaId", null))->andWhere(Criteria::expr()->eq("gradedUsrId", null)));
                    foreach ($stageGrades as $stageGrade) {
                        $stage->removeGrade($stageGrade);
                    }
                }
            }
        }

        $em->persist($stage);
        $em->flush();
        return true;
    }

    /**
     * @param Application $app
     * @param Request $request
     * @param $actId
     * @param $stgIndex
     * @param $crtIndex
     * @param $equalEntries
     * @return false|string|JsonResponse
     * @Route("graph/{actId}/{stgIndex}/{crtIndex}/{equalEntries}", name="provideGraphData")
     */
    public function provideGraphDataAction(Application $app, Request $request, $actId, $stgIndex, $crtIndex, $equalEntries)
    {
        $em = $this->em;
        $repoA = $em->getRepository(Activity::class);
        $repoP = $em->getRepository(Participation::class);
        $repoR = $em->getRepository(Result::class);
        $repoG = $em->getRepository(Grade::class);
        $repoU = $em->getRepository(User::class);
        $repoC = $em->getRepository(Criterion::class);
        /** @var Activity|null $activity */
        $activity = $repoA->find($actId);
        $user = self::getAuthorizedUser();
        $organization = $user->getOrganization();

        if (!$user instanceof User) {
            return new JsonResponse(['message' => 'Unknown user'], 500);
        }

        if (!($activity instanceof Activity)) {
            return new JsonResponse(['message' => 'Unknown activity'], 500);
        }

        $userRole = $user->getRole();
        $subordinates = $user->getSubordinatesRecursive();
        $subordinates[] = $user;

        $existingAccessAndResultsViewOption = null;
        $accessAndResultsViewOptions = $organization->getOptions()->filter(function (OrganizationUserOption $option) use ($user) {return $option->getOName()->getName() == 'activitiesAccessAndResultsView' && $option->isEnabled() && ($option->getRole() == $user->getRole() || $option->getUser() == $user);});

        // We always chose the most selective access option (NB : we could in the future, create options decidated to position, departments... so below option selection should be rewritten)
        if (count($accessAndResultsViewOptions) > 0) {
            if (count($accessAndResultsViewOptions) == 2) {
                foreach ($accessAndResultsViewOptions as $accessAndResultsViewOption) {
                    if ($accessAndResultsViewOption->getUser() != null) {
                        $existingAccessAndResultsViewOption = $accessAndResultsViewOption;
                    }
                }
            } else {
                $existingAccessAndResultsViewOption = $accessAndResultsViewOptions->first();
            }
        }

        if ($existingAccessAndResultsViewOption) {
            // We get all user info and visibility options :
            // * we need to check activity access to results with the integer option value
            // * we also check results access to results with the secondary integer option value
            // * if results scope is equal to 1, we get all results, otherwise we get all participant results, otherwise own results
            // * if results detail is equal to 1, user get all grades, otherwise consolidated results
            // * if results participation condition is equal to 'none', he sees results in any case, otherwise 'participant' means he sees them only if participates, and 'owner' only if he is stage owner
            $activitiesAccess = $existingAccessAndResultsViewOption->getOptionIValue();
            $statusAccess = $existingAccessAndResultsViewOption->getOptionSecondaryIValue();
            $scope = $existingAccessAndResultsViewOption->isOptionTrue();
            $detail = $existingAccessAndResultsViewOption->getOptionFValue();
            $participationCondition = $existingAccessAndResultsViewOption->getOptionSValue();
        } else {
            // Activity access depends on user role. Admin (1) have activities access to all organizations ones (1), AM to department ones (2) and collabs to their own activities (3)
            $activitiesAccess = ($userRole == 4) ? 1 : $userRole;
            // Only administrators have acess to their own results, others have access to all participant results
            $scope = ($userRole == 1) ? 1 : 0;
            // Only administrators have access to detailed grades, other have access to average ones = results
            $detail = ($userRole == 1) ? 1 : 0;

            $statusAccess = ($userRole == 1) ? 2 : 3;
            // When looking to results, only admin have access to activities results without limitation, otherwise you need to be a participator
            // TODO : this condition is still unclear, needs to be clarified
            $participationCondition = ($userRole == 1) ? 'none' : 'participant';
        }

        // 1 - Find relevant element to access
        if ($crtIndex == -2 || $crtIndex == -1) {
            $element = ($stgIndex == -1) ? $activity : $activity->getStages()->get($stgIndex);
        } else {
            $element = ($stgIndex == -1) ? $activity->getStages()->first()->getCriteria()->get($crtIndex) : $activity->getStages()->get($stgIndex)->getCriteria()->get($crtIndex);
        }

        $hasAccess = false;
        if ($userRole == 4 || $activitiesAccess == 1) {
            $hasAccess = true;
        } else if ($activitiesAccess == 2) {
            // Check if activity belongs to user department
            $departmentUsers = $em->getRepository(Department::class)->find($user->getDptId())->getUsers();
            $departmentUserIds = [];
            foreach ($departmentUsers as $departmentUser) {
                $departmentUserIds[] = $departmentUser->getId();
            }
            //$departmentParticipations = $element->getParticipants()->matching(Criteria::create()->where(Criteria::expr()->eq("usrId", $departmentUserIds)));
            foreach ($element->getParticipants() as $participant) {
                if (in_array($participant->getUsrId(), $departmentUserIds) !== false) {
                    $hasAccess = true;
                    break;
                }
            }
        } else if ($activitiesAccess == 3) {
            //$userParticipations = $element->getParticipants()->matching(Criteria::create()->where(Criteria::expr()->eq("usrId", $user->getId())));
            foreach ($element->getParticipants() as $participant) {
                if ($participant->getUsrId() == $user->getId()) {
                    $hasAccess = true;
                    break;
                }
            }
        }

        if ($stgIndex == -1) {
            $hasAccess = $activity->getParticipants()->exists(function(int $i, Participation $p) use ($subordinates){return in_array($p->getDirectUser(), $subordinates);});
        } else {
            $stage = $activity->getStages()[$stgIndex];
            $hasAccess = $stage->getParticipants()->exists(function(int $i, Participation $p) use ($subordinates){return in_array($p->getDirectUser(), $subordinates);});
        }

        if (!$hasAccess) {
            return new JsonResponse(['message' => "No data access"], 500);
        }

        // 2 - Get relevant commented grades and structure data
        $concernedUsrId = $user->getId();
        $gradedComments = null;
        if (!isset($userParticipations)) {
            $userParticipations = $element->getParticipants()->matching(Criteria::create()->where(Criteria::expr()->eq("usrId", $user->getId())));
        }
        $isUserParticipant = count($userParticipations) > 0;
        // Note : below condition forces isUserLeader to be false when user is a collaborator, preventing him from accessing to all grades
        $isUserAMLeader = count($userParticipations->matching(Criteria::create()->where(Criteria::expr()->eq("leader", true)))) > 0 && $userRole != 3;
        //$canAlwaysSeeGrades = $user->getRole() == 4 || $activitiesAccess == 1 && $scope == 1 && $detail == 1 && $participationCondition == 'none';
        //$isUserAM = $user->getRole() == 2;

        if ($existingAccessAndResultsViewOption) {
            $FBScope = $scope;
            $FBDetail = $detail;
        } else {
            if ($isUserAMLeader || $userRole == 1) {
                $FBScope = 1;
                $FBDetail = 1;
            } else {
                $FBScope = 0;
                $FBDetail = 0;
            }
        }

        $usersResults = null;
        $binaryResults = [];
        $participantObjectives = [];
        $data = [];

        // Get graph lowerbound and upperbound, depending on activity criteria scales

        // Graph lowerbound and upperbound are respectively equal to 0 and 100 (relative results),
        // unless all criteria have the same lowerbound and upperbound

        $graphLB = null;
        $graphUB = null;
        $criteriaHaveSameScale = true;
        $stagesHaveSameScale = true;
        $oneComputedStage = false;
        $oneReleasedStage = false;

        $stagesToCheck = [];
        if ($stgIndex == -1) {
            foreach ($activity->getStages() as $stage) {
                $stagesToCheck[] = $stage;
                if (!$oneComputedStage && $stage->getStatus() >= 2) {
                    $oneComputedStage = true;
                }
                if (!$oneReleasedStage && $stage->getStatus() == 3) {
                    $oneReleasedStage = true;
                }
            }
        } else {
            $stagesToCheck[] = $activity->getStages()->get($stgIndex);
        }

        //$stagesToCheck = ($stgIndex == -1) ? $activity->getStages() : $activity->getStages()->get($stgIndex);

        foreach ($stagesToCheck as $stage) {

            $evaluationCriteria = $repoC->findBy(['stage' => $stage, 'type' => [1, 3]]);

            if ($evaluationCriteria != null) {
                $evaluationSameScaleCriteria = $repoC->findBy(['stage' => $stage, 'type' => [1, 3], 'lowerbound' => $evaluationCriteria[0]->getLowerbound(), 'upperbound' => $evaluationCriteria[0]->getUpperbound()]);

                if (count($evaluationCriteria) == count($evaluationSameScaleCriteria)) {
                    if ($graphLB !== null) {
                        if ($evaluationCriteria[0]->getLowerbound() != $graphLB || $evaluationCriteria[0]->getUpperbound() != $graphUB) {
                            $graphLB = 0;
                            $graphUB = 100;
                            $stagesHaveSameScale = false;
                            break;
                        }
                    } else {
                        $graphLB = $evaluationCriteria[0]->getLowerbound();
                        $graphUB = $evaluationCriteria[0]->getUpperbound();
                    }

                } else {
                    $graphLB = 0;
                    $graphUB = 100;
                    $criteriaHaveSameScale = false;
                    $stagesHaveSameScale = false;
                }
            }
        }

        $data['meanUserSeriesIndex'] = 0;
        $data['lowerbound'] = $graphLB;
        $data['upperbound'] = $graphUB;
        $perfGraphData = [];

        if ($stgIndex == -1) {

            //TODO : is Viewable devrait pouvoir être distingué selon que l'on est stage master ou pas

            // Two cases : if (stgIndex,crtIndex) = (-1,-2) a computed stage (released stage) is enough to view results for admin/am (collaborator),
            // otherwise switch to the only other case (s,c) = (-1, -1)

            $isPublished = $repoP->findBy(['activity' => $activity, 'status' => [0, 1, 2, 3]]) == null;
            // if ($crtIndex == -1) {
            //     $isViewable = $isPublished || $user->getRole() != 3 && $repoP->findBy(['activity' => $activity, 'status' => [0,1,2], 'type' => [0,1]]) == null;
            // } else {
            //     $isViewable = ($user->getRole() != 3 && $oneComputedStage || $user->getRole() == 3 && $oneReleasedStage);
            // }
            $isViewable = true;

            if ($isViewable) {

                if ($crtIndex == -2) {
                    // Defining first row, being all activity unique non TP participants

                    $rowData = [];
                    $rowData[] = 'Participants';

                    $activityParticipants = $repoP->findBy(['activity' => $activity], ['usrId' => 'ASC']);

                    $uniqueActivityParticipants = [];
                    $actualId = 0;
                    $nbTPs = 0;
                    $nbFBCriteria = 0;
                    $nbUserParticipations = 0;
                    $nbUserTPParticipations = 0;
                    $potentialParticipantToInsert = null;

                    foreach ($activityParticipants as $activityParticipant) {
                        if ($activityParticipant->getUsrId() != $actualId) {

                            // We check if we can add last considered participant, only depends on whether he has been at least a non-TP in a stage (if there are N participants, goes from 1 to N-1)

                            if ($nbUserParticipations > 1) {

                                if ($nbUserParticipations == $nbUserTPParticipations) {
                                    $nbTPs++;
                                } else {
                                    if ($FBScope == 1 || $user->getId() == $actualId) {
                                        $uniqueActivityParticipants[] = $potentialParticipantToInsert;
                                    }
                                }
                            }

                            $potentialParticipantToInsert = $activityParticipant;

                            $nbUserParticipations = 0;
                            $nbUserTPParticipations = 0;

                            $actualId = $activityParticipant->getUsrId();

                            if ($activityParticipant->getType() == 0) {
                                $nbUserTPParticipations++;
                            }

                            $nbUserParticipations++;

                        } else {
                            if ($activityParticipant->getType() == 0) {
                                $nbUserTPParticipations++;
                            }

                            $nbUserParticipations++;
                        }
                    }

                    // Check for N-th participant
                    if ($nbUserParticipations > 0) {

                        if ($nbUserParticipations == $nbUserTPParticipations) {
                            $nbTPs++;
                        } else {
                            if ($FBScope == 1 || $user->getId() == $actualId) {
                                $uniqueActivityParticipants[] = $potentialParticipantToInsert;
                            }
                        }
                    }

                    // return[count($uniqueActivityParticipants)];

                    foreach ($uniqueActivityParticipants as $uniqueActivityParticipant) {
                        $dataParticipant = [];
                        $dataParticipant['label'] = ($uniqueActivityParticipant->getUser()->getFullname()) ?: $uniqueActivityParticipant->getUser()->getOrganization($app)->getCommname();
                        $dataParticipant['type'] = 'number';
                        $rowData[] = $dataParticipant;
                    }

                    $rowData[] = '';
                    $style = [];
                    $style['role'] = 'annotation';
                    $rowData[] = $style;
                    $tooltip = [];
                    $tooltip['type'] = 'string';
                    $tooltip['role'] = 'tooltip';
                    $tooltip['p']['html'] = true;
                    $rowData[] = $tooltip;
                    $rowData[] = '';
                    $perfGraphData[] = $rowData;

                    // Defining data rows, provided there are activity participants to display

                    if (count($uniqueActivityParticipants) > 0) {

                        if (count($activity->getStages()) == 1) {

                            $activityCriteria = $activity->getStages()->first()->getCriteria();

                            foreach ($activityCriteria as $criterion) {
                                if ($criterion->getType() == 2) {
                                    $nbFBCriteria++;
                                }
                            }

                            if ($nbFBCriteria != count($activityCriteria)) {

                                $usersResults = $activity->getResults();

                                foreach ($activityCriteria as $criterion) {

                                    if ($criterion->getType() != 2) {

                                        $rowData = [];
                                        $rowData[] = $criterion->getCName()->getName();
                                        $criterionParticipants = $criterion->getParticipants();
                                        foreach ($uniqueActivityParticipants as $uniqueActivityParticipant) {

                                            $foundParticipant = false;

                                            foreach ($criterionParticipants as $criterionParticipant) {
                                                if ($criterionParticipant->getUsrId() == $uniqueActivityParticipant->getUsrId()) {

                                                    $foundParticipant = true;
                                                    $participantUserId = $criterionParticipant->getUsrId();

                                                    $userResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("criterion", $criterion))->andWhere(Criteria::expr()->eq("usrId", $participantUserId)))[0];

                                                    if ($equalEntries == 0) {
                                                        if ($criteriaHaveSameScale) {
                                                            $userAvgPerfResult = round($userResult->getWeightedAbsoluteResult(), 2);
                                                        } else {
                                                            $userAvgPerfResult = round($userResult->getWeightedRelativeResult() * 100, 1);
                                                        }
                                                    } else {
                                                        if ($criteriaHaveSameScale) {
                                                            $userAvgPerfResult = round($userResult->getEqualAbsoluteResult(), 2);
                                                        } else {
                                                            $userAvgPerfResult = round($userResult->getEqualRelativeResult() * 100, 1);
                                                        }
                                                    }

                                                    $rowData[] = $userAvgPerfResult;

                                                }
                                            }

                                            if (!$foundParticipant) {
                                                $rowData[] = null;
                                            }

                                        }

                                        $criterionResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("criterion", $criterion))->andWhere(Criteria::expr()->eq("usrId", null)))[0];
                                        $activityResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("activity", $activity))->andWhere(Criteria::expr()->eq("stage", null))->andWhere(Criteria::expr()->eq("usrId", null)))[0];

                                        if ($equalEntries == 0) {
                                            if ($criteriaHaveSameScale) {
                                                $criterionAvgPerfResult = round($criterionResult ? $criterionResult->getWeightedAbsoluteResult() : 0, 2);
                                                $activityAvgPerfResult = round($activityResult ? $activityResult->getWeightedAbsoluteResult() : 0, 2);
                                            } else {
                                                $criterionAvgPerfResult = round($criterionResult ? $criterionResult->getWeightedRelativeResult() * 100 : 0, 1);
                                                $activityAvgPerfResult = round($activityResult ? $activityResult->getWeightedRelativeResult() * 100 : 0, 1);
                                            }
                                        } else {
                                            if ($criteriaHaveSameScale) {
                                                $criterionAvgPerfResult = round($criterionResult ? $criterionResult->getEqualAbsoluteResult() : 0, 2);
                                                $activityAvgPerfResult = round($activityResult ? $activityResult->getEqualAbsoluteResult() : 0, 2);
                                            } else {
                                                $criterionAvgPerfResult = round($criterionResult ? $criterionResult->getEqualRelativeResult() * 100 : 0, 1);
                                                $activityAvgPerfResult = round($activityResult ? $activityResult->getEqualRelativeResult() * 100 : 0, 1);
                                            }
                                        }

                                        $perfAvgResultString = ($criteriaHaveSameScale) ? $criterionAvgPerfResult : $criterionAvgPerfResult . ' %';
                                        $activityAvgResultString = ($criteriaHaveSameScale) ? $activityAvgPerfResult : $activityAvgPerfResult . ' %';

                                        $rowData[] = $criterionAvgPerfResult;
                                        $rowData[] = "$perfAvgResultString";
                                        $rowData[] = '<h5 style="color: red"> ' . $criterionAvgPerfResult . '</h5>';
                                        $rowData[] = $activityAvgPerfResult;

                                        $perfGraphData[] = $rowData;

                                    }

                                }

                                $data['meanUserSeriesIndex'] = count($uniqueActivityParticipants);
                                $data['displayedElmts'] = 'criteria';
                                $perfAvgResult = (string) $activityAvgResultString;

                            }

                        } else {

                            $usersResults = $activity->getResults();
                            $activityStages = $activity->getOCompletedStages();
                            $nbFBStages = 0;
                            $nbUnreleasedStages = 0;

                            $activityResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("activity", $activity))->andWhere(Criteria::expr()->eq("stage", null))->andWhere(Criteria::expr()->eq("usrId", null)))[0];

                            foreach ($activityStages as $stage) {

                                if ($statusAccess <= $stage->getStatus()) {

                                    $nbFBCriteria = 0;

                                    foreach ($stage->getCriteria() as $criterion) {
                                        if ($criterion->getType() == 2) {
                                            $nbFBCriteria++;
                                        }
                                    }

                                    if ($nbFBCriteria != count($stage->getCriteria())) {

                                        $rowData = [];
                                        $rowData[] = $stage->getName();
                                        // As participants are the same for each criterion, selecting first criterion and finding associated users is equivalent to find stage participants
                                        $criterionParticipants = $stage->getCriteria()->first()->getParticipants();
                                        foreach ($uniqueActivityParticipants as $uniqueActivityParticipant) {

                                            $foundParticipant = false;

                                            foreach ($criterionParticipants as $criterionParticipant) {
                                                if ($criterionParticipant->getUsrId() == $uniqueActivityParticipant->getUsrId()) {
                                                    $participantUserId = $criterionParticipant->getUsrId();

                                                    $userResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("stage", $stage))->andWhere(Criteria::expr()->eq("usrId", $participantUserId))->andWhere(Criteria::expr()->eq("criterion", null)))[0];

                                                    if ($criterionParticipant->getType() != 0) {
                                                        if ($equalEntries == 0) {
                                                            if ($stagesHaveSameScale) {
                                                                $userAvgPerfResult = $userResult ?
                                                                round($userResult->getWeightedAbsoluteResult(), 2) :
                                                                null;
                                                            } else {
                                                                $userAvgPerfResult = $userResult ?
                                                                round($userResult->getWeightedRelativeResult() * 100, 1) :
                                                                null;
                                                            }
                                                        } else {
                                                            if ($stagesHaveSameScale) {
                                                                $userAvgPerfResult = $userResult ?
                                                                round($userResult->getEqualAbsoluteResult(), 2) :
                                                                null;
                                                            } else {
                                                                $userAvgPerfResult = $userResult ?
                                                                round($userResult->getEqualRelativeResult() * 100, 1) :
                                                                null;
                                                            }
                                                        }
                                                        $rowData[] = $userAvgPerfResult;
                                                    } else {
                                                        $rowData[] = null;
                                                    }

                                                    $foundParticipant = true;
                                                    break;
                                                }
                                            }

                                            if (!$foundParticipant) {
                                                $rowData[] = null;
                                            }

                                        }

                                        $stageResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("stage", $stage))->andWhere(Criteria::expr()->eq("criterion", null))->andWhere(Criteria::expr()->eq("usrId", null)))[0];

                                        if ($equalEntries == 0) {
                                            if ($stagesHaveSameScale) {
                                                $stageAvgPerfResult = round($stageResult->getWeightedAbsoluteResult(), 2);
                                                $activityAvgPerfResult = round($activityResult->getWeightedAbsoluteResult(), 2);
                                            } else {
                                                $stageAvgPerfResult = round($stageResult->getWeightedRelativeResult() * 100, 1);
                                                $activityAvgPerfResult = round($activityResult->getWeightedRelativeResult() * 100, 1);
                                            }
                                        } else {
                                            if ($stagesHaveSameScale) {
                                                $stageAvgPerfResult = round($stageResult->getEqualAbsoluteResult(), 2);
                                                $activityAvgPerfResult = round($activityResult->getEqualAbsoluteResult(), 2);
                                            } else {
                                                $stageAvgPerfResult = round($stageResult->getEqualRelativeResult() * 100, 1);
                                                $activityAvgPerfResult = round($activityResult->getEqualRelativeResult() * 100, 1);
                                            }
                                        }

                                        $stageAvgResultString = ($stagesHaveSameScale) ? $stageAvgPerfResult : $stageAvgPerfResult . ' %';
                                        $rowData[] = $stageAvgPerfResult;
                                        $rowData[] = "$stageAvgResultString";
                                        $rowData[] = '<h5 style="color: red"> ' . $stageAvgPerfResult . '</h5>';
                                        $rowData[] = $activityAvgPerfResult;

                                        $perfGraphData[] = $rowData;

                                    } else {
                                        $nbFBStages++;
                                    }
                                } else {
                                    $nbUnreleasedStages++;
                                }
                            }

                            $activityAvgResultString = ($stagesHaveSameScale) ? $activityAvgPerfResult : $activityAvgPerfResult . ' %';
                            $perfAvgResult = $activityAvgResultString;
                            $data['displayedElmts'] = 'stages';
                            //$data['meanUserSeriesIndex'] = count($activityStages) - $nbTPs - $nbFBStages - $nbUnreleasedStages;
                            $data['meanUserSeriesIndex'] = count($uniqueActivityParticipants);

                        }
                    }

                    $data['overview'] = 1;

                } else if ($crtIndex == -1) {
                    $genResult = $repoR->findOneBy(['activity' => $activity, 'stage' => null, 'usrId' => null]);

                    if ($equalEntries == 0) {
                        $perfAvgResult = ($criteriaHaveSameScale) ? $genResult->getWeightedAbsoluteResult() : round($genResult->getWeightedRelativeResult() * 100, 1);
                        $distAvgResult = $genResult->getWeightedDistanceRatio();
                    } else {
                        $perfAvgResult = ($criteriaHaveSameScale) ? $genResult->getEqualAbsoluteResult() : round($genResult->getEqualRelativeResult() * 100, 1);
                        $distAvgResult = $genResult->getEqualDistanceRatio();
                    }

                    $usersResults = $repoR->findBy(['activity' => $activity, 'stage' => null]);

                    // Defining perf first row
                    $rowData = [];
                    $rowData[] = '';
                    $rowData[] = '';
                    $style = [];
                    $style['role'] = 'annotation';
                    $rowData[] = $style;
                    $rowData[] = '';
                    $perfGraphData[] = $rowData;

                    foreach ($usersResults as $result) {

                        $rowData = [];
                        if ($result->getUsrId()) {
                            //Last condition enables to remove third-parties from the graph
                            if ($FBScope == 1 or $user->getId() == $result->getUsrId()) {
                                $gradedUserName = ($result->getUser()->getFullName()) ?: $result->getUser()->getOrganization($app)->getCommname();
                                $rowData[] = $gradedUserName;
                                //Annotation for result

                                if ($criteriaHaveSameScale) {
                                    // add equals
                                    if ($equalEntries == 0) {

                                        $rowData[] = $result->getWeightedAbsoluteResult();
                                        $rowData[] = round($result->getWeightedAbsoluteResult(), 2) . ' %';
                                    } else {

                                        $rowData[] = $result->getEqualAbsoluteResult();
                                        $rowData[] = round($result->getEqualAbsoluteResult(), 2) . ' %';
                                    }
                                } else {
                                    // add equals
                                    if ($equalEntries == 0) {

                                        $rowData[] = round($result->getWeightedRelativeResult() * 100, 1);
                                        $rowData[] = round($result->getWeightedRelativeResult() * 100, 1) . ' %';
                                    } else {

                                        $rowData[] = round($result->getEqualRelativeResult() * 100, 1);
                                        $rowData[] = round($result->getEqualRelativeResult() * 100, 1) . ' %';
                                    }
                                }
                                //Annotation for result
                                if ($result->getWeightedRelativeResult() != null) {
                                    $rowData[] = $perfAvgResult;
                                }
                                // We don't provide the result row if result is null (due to the participant being a third party)
                                if ($result->getWeightedRelativeResult() != null) {
                                    $perfGraphData[] = $rowData;
                                }
                            }
                        }
                    }

                } else {
                    $criterion = $activity->getStages()->first()->getCriteria()->get($crtIndex);
                    $crtId = $criterion->getId();

                    if ($criterion->getType() != 2) {

                        $genResult = $repoR->findOneBy(['criterion' => $criterion, 'usrId' => null]);
                        // add equals

                        if (is_null($genResult)) {
                            $perfAvgResult = $distAvgResult = null;
                        } else if ($equalEntries == 0) {
                            $perfAvgResult = $genResult->getWeightedAbsoluteResult();
                            $distAvgResult = $genResult->getWeightedDistanceRatio();
                        } else {
                            $perfAvgResult = $genResult->getEqualAbsoluteResult();
                            $distAvgResult = $genResult->getEqualDistanceRatio();
                        }

                        $usersResults = $repoR->findBy(['criterion' => $criterion], ['usrId' => 'ASC']);

                        // Defining perf first row
                        $rowData = [];
                        $rowData[] = '';
                        if ($criterion->getType() != 3) {

                            if ($FBScope == 1) {
                                foreach ($criterion->getParticipants() as $participant) {
                                    if ($participant->getType() != -1) {
                                        $rowData[] = $participant->getUser()->getFullname();
                                    }
                                }
                            }

                            $rowData[] = '';

                            $style = [];
                            $style['role'] = 'annotation';
                            $rowData[] = $style;

                            $tooltip = [];
                            $tooltip['type'] = 'string';
                            $tooltip['role'] = 'tooltip';
                            $tooltip['p']['html'] = true;
                            $rowData[] = $tooltip;
                            $rowData[] = '';

                        } else {

                            $rowData[] = 'Yes';
                            $rowData[] = 'No';
                            $style = [];
                            $style['role'] = 'annotation';
                            $rowData[] = $style;

                        }

                        $perfGraphData[] = $rowData;

                        // Defining perf data (results) rows
                        foreach ($usersResults as $result) {

                            $nbPassiveParticipants = 0;
                            $isUserInTeam = false;

                            foreach ($criterion->getParticipants() as $participant) {

                                if ($participant->getType() == -1) {
                                    $nbPassiveParticipants++;
                                }
                                if ($participant->getTeam() && $participant->getUsrId() == $result->getUsrId()) {
                                    $isUserInTeam = true;
                                    $userTeam = $participant->getTeam();
                                    break;
                                }
                            }

                            $rowData = [];

                            if ($result->getUsrId()) {

                                if ($FBScope == 1 || $user->getId() == $result->getUsrId()) {

                                    $gradedUserName = ($result->getUser()->getFullName()) ?: $result->getUser()->getOrganization($app)->getCommname();
                                    $rowData[] = $gradedUserName;
                                    if ($FBDetail == 1) {
                                        foreach ($criterion->getParticipants() as $participant) {

                                            $binaryResult = [];

                                            $gradeElmt = ($isUserInTeam && $participant->getTeam() != $userTeam) ?
                                            $repoG->findOneBy(['criterion' => $criterion, 'participant' => $participant, 'gradedTeaId' => $userTeam->getId()]) :
                                            $repoG->findOneBy(['criterion' => $criterion, 'participant' => $participant, 'gradedUsrId' => $result->getUsrId()]);

                                            // Get binary votes
                                            if ($criterion->getType() == 3) {
                                                if ($gradeElmt->getValue() !== null) {
                                                    $binaryResult['graded'] = $gradedUserName;
                                                    $binaryResult['name'] = $gradeElmt->getParticipant()->getUser()->getFullName();
                                                    $binaryResult['value'] = $gradeElmt->getValue();
                                                    $binaryResults[] = $binaryResult;
                                                }
                                            } else {
                                                if ($gradeElmt != null) {
                                                    $rowData[] = $gradeElmt->getValue();
                                                }
                                            }
                                            if ($participant->getPrecomment() != null && $participant->getPrecomment() != "") {
                                                $participantObjective = [];
                                                $participantObjective['fullName'] = $participant->getUser()->getFullName();
                                                $participantObjective['precomment'] = $participant->getPrecomment();
                                                $participantObjective['criterion'] = $participant->getCriterion()->getCName()->getName();
                                                $participantObjectives[] = $participantObjective;
                                            }
                                        }
                                        $data['meanUserSeriesIndex'] = count($criterion->getParticipants()) - $nbPassiveParticipants;

                                    } else {

                                        foreach ($criterion->getParticipants() as $participant) {

                                            if ($participant->getPrecomment() != null && $participant->getPrecomment() != "") {
                                                $participantObjective = [];
                                                $participantObjective['fullName'] = $participant->getUser()->getFullName();
                                                $participantObjective['precomment'] = $participant->getPrecomment();
                                                $participantObjective['criterion'] = $participant->getCriterion()->getCName()->getName();
                                                $participantObjectives[] = $participantObjective;
                                            }
                                        }
                                        $data['meanUserSeriesIndex'] = ($criterion->getType() == 3) ? count($criterion->getParticipants()) : 0;
                                    }

                                    if ($criterion->getType() != 3) {
                                        //Annotation for result
                                        if (1 == 1) {
                                            // add equals

                                            if ($equalEntries == 0) {

                                                $rowData[] = $result->getWeightedAbsoluteResult();
                                                $rowData[] = round($result->getWeightedAbsoluteResult(), 2) . ' %';
                                                $rowData[] = '<h5 style="color: red"> ' . round($result->getWeightedAbsoluteResult(), 2) . '</h5>';
                                            } else {

                                                $rowData[] = $result->getEqualAbsoluteResult();
                                                $rowData[] = round($result->getEqualAbsoluteResult(), 2) . ' %';
                                                $rowData[] = '<h5 style="color: red"> ' . round($result->getEqualAbsoluteResult(), 2) . '</h5>';
                                            }
                                        } else {
                                            // add equals
                                            if ($equalEntries == 0) {

                                                $rowData[] = round($result->getWeightedRelativeResult() * 100, 1);
                                                $rowData[] = round($result->getWeightedRelativeResult() * 100, 1) . ' %';
                                                $rowData[] = '<h5 style="color: red"> ' . round($result->getWeightedRelativeResult() * 100, 1) . '</h5>';
                                            } else {

                                                $rowData[] = round($result->getEqualRelativeResult() * 100, 1);
                                                $rowData[] = round($result->getEqualRelativeResult() * 100, 1) . ' %';
                                                $rowData[] = '<h5 style="color: red"> ' . round($result->getEqualRelativeResult() * 100, 1) . '</h5>';
                                            }
                                        }

                                        $rowData[] = $perfAvgResult;
                                    } else {

                                        $rowData[] = round($result->getWeightedRelativeResult() * 100, 1);
                                        $rowData[] = round(100 - $result->getWeightedRelativeResult() * 100, 1);
                                        $rowData[] = '';
                                    }

                                    // We don't provide the result row if result is null (due to the participant being a third party)
                                    if ($result->getWeightedRelativeResult() != null) {
                                        $perfGraphData[] = $rowData;
                                    }

                                }
                            }
                        }

                        $data['lowerbound'] = $criterion->getLowerbound();
                        $data['upperbound'] = $criterion->getUpperbound();

                    } else {

                        foreach ($criterion->getParticipants() as $participant) {

                            if ($participant->getPrecomment() != null && $participant->getPrecomment() != "") {
                                $participantObjective = [];
                                $participantObjective['fullName'] = $participant->getUser()->getFullName();
                                $participantObjective['precomment'] = $participant->getPrecomment();
                                $participantObjective['criterion'] = $participant->getCriterion()->getCName()->getName();
                                $participantObjectives[] = $participantObjective;
                            }
                        }
                    }
                }
            }

        } else {

            $stage = $activity->getStages()->get($stgIndex);
            $stgId = $stage->getId();
            if (count($stage->getCriteria()->first()->getParticipants()) == 0) {
                $isPublished = false;
                $isViewable = false;
            } else {
                $isPublished = $stage->getCriteria()->first()->getParticipants()->first()->getStatus() == 4;
                $isViewable = $statusAccess <= $stage->getStatus();
            }
            $stageStatus = $stage->getStatus();
            $nbFBCriteria = 0;

            foreach ($stage->getCriteria() as $criterion) {
                if ($criterion->getType() == 2) {
                    $nbFBCriteria++;
                }
            }

            if ($isViewable) {

                if ($crtIndex == -2) {

                    if ($nbFBCriteria != count($stage->getCriteria())) {

                        // Defining first row, being all activity unique non TP participants

                        $rowData = [];
                        $rowData[] = 'Participants';

                        $stageParticipants = $repoP->findBy(['stage' => $stage], ['usrId' => 'ASC']);
                        $uniqueActivityParticipants = [];
                        $actualId = 0;
                        $nbTPs = 0;

                        foreach ($stageParticipants as $stageParticipant) {
                            if ($stageParticipant->getUsrId() != $actualId) {

                                $actualId = $stageParticipant->getUsrId();

                                if ($stageParticipant->getType() != 0) {
                                    if ($FBScope == 1 || $user->getId() == $actualId) {
                                        $uniqueStageParticipants[] = $stageParticipant;
                                    }
                                } else {
                                    $nbTPs++;
                                }

                            }
                        }

                        foreach ($uniqueStageParticipants as $uniqueStageParticipant) {
                            $dataParticipant = [];
                            $dataParticipant['label'] = ($uniqueStageParticipant->getUser()->getFullname()) ?: $uniqueStageParticipant->getUser()->getOrganization($app)->getCommname();
                            $dataParticipant['type'] = 'number';
                            $rowData[] = $dataParticipant;
                        }

                        $rowData[] = '';
                        $style = [];
                        $style['role'] = 'annotation';
                        $rowData[] = $style;
                        $tooltip = [];
                        $tooltip['type'] = 'string';
                        $tooltip['role'] = 'tooltip';
                        $tooltip['p']['html'] = true;
                        $rowData[] = $tooltip;
                        $rowData[] = '';
                        $perfGraphData[] = $rowData;

                        // Defining data rows, provided there are stage participants to display

                        if (count($uniqueStageParticipants) > 0) {

                            $usersResults = $stage->getResults();
                            $stageCriteria = $stage->getCriteria();

                            foreach ($stageCriteria as $criterion) {

                                if ($criterion->getType() != 2) {

                                    $rowData = [];
                                    $rowData[] = $criterion->getCName()->getName();
                                    $criterionParticipants = $criterion->getParticipants();
                                    foreach ($uniqueStageParticipants as $uniqueStageParticipant) {

                                        if ($uniqueStageParticipant->getPrecomment() != null && $uniqueStageParticipant->getPrecomment() != "") {
                                            $participantObjective = [];
                                            $participantObjective['fullName'] = $uniqueStageParticipant->getUser()->getFullName();
                                            $participantObjective['precomment'] = $uniqueStageParticipant->getPrecomment();
                                            $participantObjective['criterion'] = $uniqueStageParticipant->getCriterion()->getCName()->getName();
                                            $participantObjectives[] = $participantObjective;
                                        }

                                        $foundParticipant = false;

                                        foreach ($criterionParticipants as $criterionParticipant) {
                                            if ($criterionParticipant->getUsrId() == $uniqueStageParticipant->getUsrId()) {
                                                $participantUserId = $criterionParticipant->getUsrId();
                                                $userResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("criterion", $criterion))->andWhere(Criteria::expr()->eq("usrId", $participantUserId)))[0];

                                                if ($equalEntries == 0) {
                                                    if ($criteriaHaveSameScale) {
                                                        $userAvgPerfResult = round($userResult->getWeightedAbsoluteResult(), 2);
                                                    } else {
                                                        $userAvgPerfResult = round($userResult->getWeightedRelativeResult() * 100, 1);
                                                    }
                                                } else {
                                                    if ($criteriaHaveSameScale) {
                                                        $userAvgPerfResult = round($userResult->getEqualAbsoluteResult(), 2);
                                                    } else {
                                                        $userAvgPerfResult = round($userResult->getEqualRelativeResult() * 100, 1);
                                                    }
                                                }

                                                $rowData[] = $userAvgPerfResult;
                                                $foundParticipant = true;
                                                break;
                                            }
                                        }

                                        if (!$foundParticipant) {
                                            $rowData[] = null;
                                        }

                                    }

                                    $criterionResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("criterion", $criterion))->andWhere(Criteria::expr()->eq("usrId", null)))[0];
                                    $stageResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("criterion", null))->andWhere(Criteria::expr()->eq("usrId", null)))[0];

                                    if ($equalEntries == 0) {
                                        if ($criteriaHaveSameScale) {
                                            $criterionAvgPerfResult = round($criterionResult->getWeightedAbsoluteResult(), 2);
                                            $stageAvgPerfResult = round($stageResult->getWeightedAbsoluteResult(), 2);
                                        } else {
                                            $criterionAvgPerfResult = round($criterionResult->getWeightedRelativeResult() * 100, 1);
                                            $stageAvgPerfResult = round($stageResult->getWeightedRelativeResult() * 100, 1);
                                        }
                                    } else {
                                        if ($criteriaHaveSameScale) {
                                            $criterionAvgPerfResult = round($criterionResult->getEqualAbsoluteResult(), 2);
                                            $stageAvgPerfResult = round($stageResult->getEqualAbsoluteResult(), 2);
                                        } else {
                                            $criterionAvgPerfResult = round($criterionResult->getEqualRelativeResult() * 100, 1);
                                            $stageAvgPerfResult = round($stageResult->getEqualRelativeResult() * 100, 1);
                                        }
                                    }

                                    $perfAvgResultString = ($criteriaHaveSameScale) ? $criterionAvgPerfResult : $criterionAvgPerfResult . ' %';
                                    $stageAvgResultString = ($criteriaHaveSameScale) ? $stageAvgPerfResult : $stageAvgPerfResult . ' %';

                                    $rowData[] = $criterionAvgPerfResult;
                                    $rowData[] = "$perfAvgResultString";
                                    $rowData[] = '<h5 style="color: red"> ' . $criterionAvgPerfResult . '</h5>';
                                    $rowData[] = $stageAvgPerfResult;

                                    $perfGraphData[] = $rowData;

                                }

                            }

                            $perfAvgResult = (string) $stageAvgResultString;
                        }

                        $data['meanUserSeriesIndex'] = count($uniqueStageParticipants);
                        $data['overview'] = 1;
                        $data['displayedElmts'] = 'criteria';

                    }

                } else if ($crtIndex == -1) {

                    if ($nbFBCriteria != count($stage->getCriteria())) {

                        $genResult = $repoR->findOneBy(['stage' => $stage, 'criterion' => null, 'usrId' => null]);

                        // If user is a collaborator, the only displayed results will be his own
                        $usersResults = $repoR->findBy(['stage' => $stage, 'criterion' => null]);
                        $nbPassiveParticipants = 0;

                        if ($usersResults) {

                            // add equals

                            if ($equalEntries == 0) {
                                $perfAvgResult = ($criteriaHaveSameScale) ? $genResult->getWeightedAbsoluteResult() : round($genResult->getWeightedRelativeResult() * 100, 1);
                                $distAvgResult = $genResult->getWeightedDistanceRatio();
                            } else {
                                $perfAvgResult = ($criteriaHaveSameScale) ? $genResult->getEqualAbsoluteResult() : round($genResult->getEqualRelativeResult() * 100, 1);
                                $distAvgResult = $genResult->getEqualDistanceRatio();
                            }

                            // Defining first row
                            $rowData = [];
                            $rowData[] = '';
                            // We get criterion data only in case there is only one criterion
                            if (count($stage->getCriteria()) == 1 && $FBDetail == 1) {
                                foreach ($usersResults as $result) {
                                    if ($result->getUsrId()) {
                                        if ($result->getWeightedStdDev() != null) {
                                            $rowData[] = $result->getUser()->getFullname();
                                        } else {
                                            $nbPassiveParticipants++;
                                        }
                                    }
                                }
                            }

                            $rowData[] = '';
                            $style = [];
                            $style['role'] = 'annotation';
                            $rowData[] = $style;
                            $rowData[] = '';
                            $perfGraphData[] = $rowData;

                            // Defining data (results) rows
                            foreach ($usersResults as $result) {
                                $rowData = [];
                                $isUserInTeam = false;
                                foreach ($criterion->getParticipants() as $participant) {
                                    if ($participant->getTeam() && $participant->getUsrId() == $result->getUsrId()) {
                                        $isUserInTeam = true;
                                        $userTeam = $participant->getTeam();
                                        break;
                                    }
                                }

                                if ($result->getUsrId()) {
                                    if ($FBScope == 1 or $user->getId() == $result->getUsrId()) {
                                        $gradedUserName = ($result->getUser()->getFullName()) ?: $result->getUser()->getOrganization($app)->getCommname();
                                        $rowData[] = $gradedUserName;

                                        // We get criterion data only in case there is only one criterion
                                        if (count($stage->getCriteria()) == 1) {
                                            if ($FBDetail == 1) {
                                                foreach ($criterion->getParticipants() as $participant) {
                                                    $gradeElmt = ($isUserInTeam && $participant->getTeam() != $userTeam) ?
                                                    $repoG->findOneBy(['criterion' => $criterion, 'participant' => $participant, 'gradedTeaId' => $userTeam->getId()]) :
                                                    $repoG->findOneBy(['criterion' => $criterion, 'participant' => $participant, 'gradedUsrId' => $result->getUsrId()]);

                                                    // Get binary votes
                                                    if ($criterion->getType() == 3) {
                                                        if ($gradeElmt->getValue() !== null) {
                                                            $binaryResult['graded'] = $gradedUserName;
                                                            $binaryResult['name'] = $gradeElmt->getParticipant()->getUser()->getFullName();
                                                            $binaryResult['value'] = $gradeElmt->getValue();
                                                            $binaryResults[] = $binaryResult;
                                                        }
                                                    } else {
                                                        if ($gradeElmt != null) {
                                                            $rowData[] = $gradeElmt->getValue();
                                                        }
                                                    }
                                                    if ($participant->getPrecomment() != null && $participant->getPrecomment() != "") {
                                                        $participantObjective = [];
                                                        $participantObjective['fullName'] = $participant->getUser()->getFullName();
                                                        $participantObjective['precomment'] = $participant->getPrecomment();
                                                        $participantObjective['criterion'] = $participant->getCriterion()->getCName()->getName();
                                                        $participantObjectives[] = $participantObjective;
                                                    }
                                                }
                                                $data['meanUserSeriesIndex'] = count($criterion->getParticipants()) - $nbPassiveParticipants;
                                            } else {
                                                foreach ($criterion->getParticipants() as $participant) {

                                                    if ($participant->getPrecomment() != null && $participant->getPrecomment() != "") {
                                                        $participantObjective = [];
                                                        $participantObjective['fullName'] = $participant->getUser()->getFullName();
                                                        $participantObjective['precomment'] = $participant->getPrecomment();
                                                        $participantObjective['criterion'] = $participant->getCriterion()->getCName()->getName();
                                                        $participantObjectives[] = $participantObjective;
                                                    }
                                                }
                                                $data['meanUserSeriesIndex'] = 0;
                                            }
                                        } else {
                                            $data['meanUserSeriesIndex'] = 0;
                                        }

                                        //Annotation for result
                                        if ($criteriaHaveSameScale) {
                                            // add equals
                                            if ($equalEntries == 0) {
                                                $rowData[] = $result->getWeightedAbsoluteResult();
                                                $rowData[] = (string) round($result->getWeightedAbsoluteResult(), 2);
                                            } else {
                                                $rowData[] = $result->getEqualAbsoluteResult();
                                                $rowData[] = (string) round($result->getEqualAbsoluteResult(), 2);
                                            }
                                        } else {
                                            // add equal
                                            if ($equalEntries == 0) {

                                                $rowData[] = round($result->getWeightedRelativeResult() * 100, 1);
                                                $rowData[] = round($result->getWeightedRelativeResult() * 100, 1) . ' %';
                                            } else {
                                                $rowData[] = round($result->getEqualRelativeResult() * 100, 1);
                                                $rowData[] = round($result->getEqualRelativeResult() * 100, 1) . ' %';
                                            }
                                        }

                                        $rowData[] = $perfAvgResult;
                                        if ($result->getWeightedRelativeResult() != null) {
                                            $perfGraphData[] = $rowData;
                                        }
                                    }
                                }
                            }

                            // Get graph lowerbound and upperbound, depending on stage criteria scales

                            // Graph lowerbound and upperbound are respectively equal to 0 and 100 (relative results),
                            // unless all criteria have the same lowerbound and upperbound

                            $graphLB = null;
                            $graphUB = null;

                            $evaluationCriteria = $repoC->findBy(['stage' => $stage, 'type' => [1, 3]]);
                            $evaluationSameScaleCriteria = $repoC->findBy(['stage' => $stage, 'type' => [1, 3], 'lowerbound' => $evaluationCriteria[0]->getLowerbound(), 'upperbound' => $evaluationCriteria[0]->getUpperbound()]);

                            if (count($evaluationCriteria) == count($evaluationSameScaleCriteria)) {
                                $graphLB = $evaluationCriteria[0]->getLowerbound();
                                $graphUB = $evaluationCriteria[0]->getUpperbound();
                            } else {
                                $graphLB = 0;
                                $graphUB = 100;
                            }
                            $data['lowerbound'] = $graphLB;
                            $data['upperbound'] = $graphUB;
                        }
                    }

                } else {

                    $criterion = $activity->getStages()->get($stgIndex)->getCriteria()->get($crtIndex);
                    $crtId = $criterion->getId();

                    if ($criterion->getType() != 2) {

                        $genResult = $repoR->findOneBy(['criterion' => $criterion, 'usrId' => null]);
                        // add equals

                        if ($equalEntries == 0) {

                            $perfAvgResult = $genResult->getWeightedAbsoluteResult();
                            $distAvgResult = $genResult->getWeightedDistanceRatio();
                        } else {

                            $perfAvgResult = $genResult->getEqualAbsoluteResult();
                            $distAvgResult = $genResult->getEqualDistanceRatio();
                        }

                        // Initially if user was a collaborator, the only displayed results were his own
                        // Now transparency for all users (collaborators and AM non leaders : when published)

                        $usersResults = //($user->getRole() != 3) ?
                        $repoR->findBy(['criterion' => $criterion]) /*:
                        $repoR->findBy(['criterion' => $criterion, 'usrId' => $user->getId()])*/;

                        // Defining first row
                        $rowData = [];
                        $rowData[] = '';
                        $nbPassiveParticipants = 0;

                        if ($criterion->getType() != 3) {

                            if ($FBScope == 1) {
                                foreach ($criterion->getParticipants() as $participant) {
                                    if ($participant->getType() != -1) {
                                        $rowData[] = $participant->getUser() /*->getFullName()*/->getFullname();
                                    } else {
                                        $nbPassiveParticipants++;
                                    }
                                }
                            }
                            $rowData[] = '';
                            $style = [];
                            $style['role'] = 'annotation';
                            $rowData[] = $style;
                            $rowData[] = '';
                            $perfGraphData[] = $rowData;

                        } else {

                            $rowData[] = 'Yes';
                            $rowData[] = 'No';
                            $style = [];
                            $style['role'] = 'annotation';
                            $rowData[] = $style;
                            $perfGraphData[] = $rowData;

                        }

                        // Defining data (results) rows
                        foreach ($usersResults as $result) {

                            $isUserInTeam = false;
                            foreach ($criterion->getParticipants() as $participant) {
                                if ($participant->getTeam() && $participant->getUsrId() == $result->getUsrId()) {
                                    $isUserInTeam = true;
                                    $userTeam = $participant->getTeam();
                                    break;
                                }
                            }

                            $rowData = [];
                            if ($result->getUsrId()) {

                                if ($FBScope == 1 or $user->getId() == $result->getUsrId()) {

                                    $gradedUserName = ($result->getUser()->getFullName()) ?: $result->getUser()->getOrganization($app)->getCommname();
                                    $rowData[] = $gradedUserName;
                                    if ($FBDetail == 1) {

                                        foreach ($criterion->getParticipants() as $participant) {

                                            $binaryResult = [];

                                            $gradeElmt = ($isUserInTeam && $participant->getTeam() != $userTeam) ?
                                            $repoG->findOneBy(['criterion' => $criterion, 'participant' => $participant, 'gradedTeaId' => $userTeam->getId()]) :
                                            $repoG->findOneBy(['criterion' => $criterion, 'participant' => $participant, 'gradedUsrId' => $result->getUsrId()]);

                                            // Get binary votes
                                            if ($criterion->getType() == 3) {
                                                if ($gradeElmt->getValue() !== null) {
                                                    $binaryResult['graded'] = $gradedUserName;
                                                    $binaryResult['name'] = $gradeElmt->getParticipant()->getUser()->getFullName();
                                                    $binaryResult['value'] = $gradeElmt->getValue();
                                                    $binaryResults[] = $binaryResult;
                                                }
                                            } else {
                                                if ($gradeElmt != null) {
                                                    $rowData[] = $gradeElmt->getValue();
                                                }
                                            }

                                            if ($participant->getPrecomment() != null && $participant->getPrecomment() != "") {
                                                $participantObjective = [];
                                                $participantObjective['fullName'] = $participant->getUser()->getFullName();
                                                $participantObjective['precomment'] = $participant->getPrecomment();
                                                $participantObjective['criterion'] = $participant->getCriterion()->getCName()->getName();
                                                $participantObjectives[] = $participantObjective;
                                            }
                                        }
                                        $data['meanUserSeriesIndex'] = count($criterion->getParticipants()) - $nbPassiveParticipants;

                                    } else {
                                        foreach ($criterion->getParticipants() as $participant) {

                                            if ($participant->getPrecomment() != null && $participant->getPrecomment() != "") {
                                                $participantObjectives[] = $participant;
                                            }
                                        }
                                        $data['meanUserSeriesIndex'] = ($criterion->getType() == 3) ? count($criterion->getParticipants()) : 0;
                                    }

                                    //Annotation for result
                                    if ($criterion->getType() != 3) {

                                        if (1 == 1) {
                                            // add equals
                                            if ($equalEntries == 0) {

                                                $rowData[] = $result->getWeightedAbsoluteResult();
                                                $rowData[] = (string) round($result->getWeightedAbsoluteResult(), 2);
                                            } else {

                                                $rowData[] = $result->getEqualAbsoluteResult();
                                                $rowData[] = (string) round($result->getEqualAbsoluteResult(), 2);

                                            }
                                        } else {
                                            // add equals
                                            if ($equalEntries == 0) {

                                                $rowData[] = round($result->getWeightedRelativeResult() * 100, 1);
                                                $rowData[] = round($result->getWeightedRelativeResult() * 100, 1) . ' %';
                                            } else {

                                                $rowData[] = round($result->getEqualRelativeResult() * 100, 1);
                                                $rowData[] = round($result->getEqualRelativeResult() * 100, 1) . ' %';
                                            }
                                        }

                                        $rowData[] = $perfAvgResult;

                                    } else {

                                        $rowData[] = round($result->getWeightedRelativeResult() * 100, 1);
                                        $rowData[] = round(100 - $result->getWeightedRelativeResult() * 100, 1);
                                        $rowData[] = '';

                                    }

                                    if ($result->getWeightedAbsoluteResult() != null) {
                                        $perfGraphData[] = $rowData;
                                    }
                                }
                            }
                        }

                        $data['lowerbound'] = $criterion->getLowerbound();
                        $data['upperbound'] = $criterion->getUpperbound();

                    } else {
                        foreach ($criterion->getParticipants() as $participant) {

                            if ($participant->getPrecomment() != null && $participant->getPrecomment() != "") {
                                $participantObjective = [];
                                $participantObjective['fullName'] = $participant->getUser()->getFullName();
                                $participantObjective['precomment'] = $participant->getPrecomment();
                                $participantObjective['criterion'] = $participant->getCriterion()->getCName()->getName();
                                $participantObjectives[] = $participantObjective;
                            }
                        }
                    }
                }

            }

        }

        //return [$isUserParticipant, $FBScope, $FBDetail, $participationCondition];

        /** GET FEEDBACKS (COMMENTS & GRADES) */
        $feedbacks = null;

        // We need to know whether user is participant
        if (!$isUserParticipant) {
            // If it is not, then we only retrieve feedbacks if result access is not restricted when being non-participant
            if ($participationCondition == 'none') {

                // We retrieve all feedbacks
                $relevantGrades = $element->getGrades()->matching(Criteria::create()->where(Criteria::expr()->neq("comment", null))->orWhere(Criteria::expr()->neq("value", null))->orderBy(['gradedUsrId' => Criteria::ASC, 'stage' => Criteria::DESC, 'criterion' => Criteria::ASC]));
                foreach ($relevantGrades as $key => $relevantGrade) {

                    // and select every one to be potentially inserted, except those which have not been commented in case we don't have access to detailed results
                    if (!($FBDetail == 0 && $relevantGrade->getComment() == null)) {
                        $gradeElmts = [];
                        $gradeElmts['graded'] = $relevantGrade->getGradedUser($app)->getFullName();
                        $gradeElmts['criterion'] = $relevantGrade->getCriterion()->getCName()->getName();
                        $gradeElmts['stage'] = $relevantGrade->getStage()->getName();
                        if ($FBDetail == 1) {
                            $gradeElmts['comments'][$relevantGrade->getParticipant()->getDirectUser()->getFullname()] = $relevantGrade->getComment();
                            $gradeElmts['grades'][$relevantGrade->getParticipant()->getDirectUser()->getFullname()] = $relevantGrade->getValue();
                        } else {
                            $gradeElmts['comments'][] = $relevantGrade->getComment();
                        }
                        $feedbacks[] = $gradeElmts;
                    }
                }
            }
            // Otherwise scope and detail variables will determine what is returned
        } else {

            $potentialGrades = $element->getGrades()->matching(Criteria::create()->where(Criteria::expr()->neq("comment", null))->orWhere(Criteria::expr()->neq("value", null))->orderBy(['gradedUsrId' => Criteria::ASC, 'stage' => Criteria::DESC, 'criterion' => Criteria::ASC]));

            if ($FBScope == 0) {
                // If user can access only to his own results, relevant grades are only his commented ones and those associated to him
                $relevantGrades = $potentialGrades->filter(function (Grade $grade) use ($concernedUsrId) {
                    return $grade->getParticipant()->getUsrId() == $concernedUsrId || $grade->getGradedUsrId() == $concernedUsrId;
                });
            } else {
                $relevantGrades = $potentialGrades;
            }

            foreach ($relevantGrades as $key => $relevantGrade) {

                // and select every one to be potentially inserted, except those which have not been commented in case we don't have access to detailed results
                $gradeElmts = [];
                $isSelfGrade = $relevantGrade->getParticipant()->getUsrId() == $concernedUsrId;

                if ($isSelfGrade) {
                    $gradeElmts['selfGrade'] = true;
                }
                if ($relevantGrade->getGradedUser($app) == $user) {
                    $gradeElmts['receivedGrade'] = true;
                }

                $gradeElmts['graded'] = $relevantGrade->getGradedUser($app)->getFullName();
                $gradeElmts['criterion'] = $relevantGrade->getCriterion()->getCName()->getName();
                $gradeElmts['stage'] = $relevantGrade->getStage()->getName();
                if ($FBDetail == 1 || $isSelfGrade) {

                    $gradeElmts['comments'][$relevantGrade->getParticipant()->getDirectUser()->getFullname()] = $relevantGrade->getComment();
                    $gradeElmts['grades'][$relevantGrade->getParticipant()->getDirectUser()->getFullname()] = $relevantGrade->getValue();
                } else if ($relevantGrade->getComment() != null) {
                    $gradeElmts['comments'][] = $relevantGrade->getComment();
                }

                $feedbacks[] = $gradeElmts;
            }
        }

        $data['feedbacks'] = $feedbacks;

        $canSeeGrades = $FBDetail == 1 || $FBDetail == 0 && $isUserParticipant;
        $canSeeGrader = ($FBScope == 1);
        $data['canSeeGrader'] = $canSeeGrader;
        $data['canSeeGrades'] = $canSeeGrades;
        /*

        // If user can see grades, we retrieve all grades, otherwise only those who have been commented
        if($canSeeGrades){
        $relevantGrades = $element->getGrades()->matching(Criteria::create()->where(Criteria::expr()->neq("comment", null))->orWhere(Criteria::expr()->neq("value", null))->orderBy(['stage' => Criteria::DESC, 'criterion' => Criteria::ASC, 'gradedUsrId' => Criteria::ASC]));
        } else {
        $relevantGrades = $element->getGrades()->matching(Criteria::create()->where(Criteria::expr()->neq("comment", null))->orderBy(['stage' => Criteria::DESC, 'criterion' => Criteria::ASC, 'gradedUsrId' => Criteria::ASC]));
        }

        // Grades are sorted
        foreach($relevantGrades as $key => $relevantGrade){
        if($concernedUsrId != $relevantGrade->getGradedUsrId()){
        if($key != 0){$comments[] = $gradedComments;}
        $gradedComments = null;
        if($canSeeGrader){
        $gradedComments['comments'][$relevantGrade->getParticipant()->getDirectUser()->getFullname()] = $relevantGrade->getComment();
        } else {
        $gradedComments['comments'][] = $relevantGrade->getComment();
        }
        if($canSeeGrades){
        $gradedComments['grades'][$relevantGrade->getParticipant()->getDirectUser()->getFullname()] = $relevantGrade->getValue();
        }
        $gradedComments['graded'] = $relevantGrade->getGradedUser($app)->getFullName();
        $gradedComments['criterion'] = $relevantGrade->getCriterion()->getCName()->getName();
        $gradedComments['stage'] = $relevantGrade->getStage()->getName();
        } else {
        $gradedComments['comments'][$relevantGrade->getParticipant()->getDirectUser()->getFullname()] = $relevantGrade->getComment();
        $gradedComments['grades'][$relevantGrade->getParticipant()->getDirectUser()->getFullname()] = $relevantGrade->getValue();
        }
        }
        if(count($relevantGrades) > 0){
        $comments[] = $gradedComments;
        }
        $data['feedbacks'] = $comments;
         */

        /** GET STANDARD DEVIATION GRAPH **/

        if ($isViewable) {

            // We define all conditions preventing from displaying deviation graph;
            // We do not show standard dev graph in case result criterion is binary
            // Users results are null when, for instance, all criteria or stages depending on what is displayed are pure comment elements

            $break = false;
            $noDisplay = false;

            if ($usersResults === null) {
                $noDisplay = true;
            } else {

                if (isset($criterion) && $crtIndex >= 0) {
                    if (count($criterion->getParticipants()) == 1 || $criterion->getType() == 3) {
                        $noDisplay = true;
                    }
                } else if (isset($stage) && $stgIndex >= 0) {

                    if (count($stage->getParticipants()) == count($stage->getCriteria())) {
                        $noDisplay = true;
                    } else {
                        foreach ($stage->getCriteria() as $criterion) {
                            if ($criterion->getType() == 1) {
                                $break = true;
                                break;
                            }
                        }
                        if (!$break) {
                            $noDisplay = true;
                        }
                    }

                } else {
                    foreach ($activity->getStages() as $stage) {
                        if (!(count($stage->getParticipants()) == count($stage->getCriteria()))) {
                            foreach ($stage->getCriteria() as $criterion) {
                                if ($criterion->getType() == 1) {
                                    $break = true;
                                    break;
                                }
                            }
                        }
                        if ($break) {
                            break;
                        }
                    }
                    if (!$break) {
                        $noDisplay = true;
                    }
                }
            }

            if (!$noDisplay) {
                // Removing passive participants results, as they did not take part in the evaluation process
                if (!is_object($usersResults)) {$usersResults = new ArrayCollection($usersResults);}
                $usersResults = $usersResults->matching(Criteria::create()->where(Criteria::expr()->neq("weightedStdDev", null)));

                if ($crtIndex != -2) {
                    // Defining dist data rows
                    // First row

                    $rowData = [];
                    $rowData[] = '';
                    $rowData[] = '';
                    $style = [];
                    $style['role'] = 'annotation';
                    $rowData[] = $style;
                    $rowData[] = '';
                    $distGraphData[] = $rowData;

                    // Data rows

                    foreach ($usersResults as $result) {
                        if ($result->getUsrId()) {
                            if ($scope == 1 or $user->getId() == $result->getUsrId()) {
                                $rowData = [];
                                $gradedUserName = ($result->getUser()->getFullName()) ?: $result->getUser()->getOrganization($app)->getCommname();
                                $rowData[] = $gradedUserName;
                                // add equals

                                if ($equalEntries == 0) {
                                    $rowData[] = $result->getWeightedDevRatio();
                                } else {
                                    $rowData[] = $result->getEqualDevRatio();
                                }
                                //Annotation for result
                                // add equals
                                if ($equalEntries == 0) {
                                    $rowData[] = round($result->getWeightedDevRatio() * 100, 1) . ' %';
                                } else {

                                    $rowData[] = round($result->getEqualDevRatio() * 100, 1) . ' %';
                                }
                                $rowData[] = $distAvgResult;
                                $distGraphData[] = $rowData;
                            }
                        }
                    }

                } else if ($stgIndex == -1) {
                    // Defining first row, being all activity unique non TP participants

                    $rowData = [];
                    $rowData[] = 'Participants';

                    $activityParticipants = $repoP->findBy(
                        ['activity' => $activity],
                        ['usrId' => 'ASC']
                    );
                    $uniqueActivityParticipants = [];
                    $actualId = 0;
                    $nbTPs = 0;
                    $nbFBCriteria = 0;

                    foreach ($activityParticipants as $activityParticipant) {
                        if ($activityParticipant instanceof Participation) {
                            if ($activityParticipant->getType() == -1) {
                                continue;
                            }

                            if ($activityParticipant->getUsrId() != $actualId) {
                                $actualId = $activityParticipant->getUsrId();
                                if ($detail == 1 or $user->getId() == $actualId) {
                                    $uniqueActivityParticipants[] = $activityParticipant;
                                }
                            }
                        }
                    }

                    foreach ($uniqueActivityParticipants as $uniqueActivityParticipant) {
                        if ($uniqueActivityParticipant instanceof Participation) {
                            $dataParticipant = [];
                            $dataParticipant['label'] = $uniqueActivityParticipant->getUser()->getFullname() ?: $uniqueActivityParticipant->getUser()->getOrganization()->getCommname();
                            $dataParticipant['type'] = 'number';
                            $rowData[] = $dataParticipant;
                        }
                    }

                    $rowData[] = '';
                    $style = [];
                    $style['role'] = 'annotation';
                    $rowData[] = $style;
                    $tooltip = [];
                    $tooltip['type'] = 'string';
                    $tooltip['role'] = 'tooltip';
                    $tooltip['p']['html'] = true;
                    $rowData[] = $tooltip;
                    $rowData[] = '';
                    $distGraphData[] = $rowData;

                    // Defining data rows

                    if (count($activity->getStages()) == 1) {
                        $activityCriteria = $activity->getStages()->first()->getCriteria();

                        foreach ($activityCriteria as $criterion) {
                            if ($criterion->getType() == 2) {
                                $nbFBCriteria++;
                            }
                        }

                        if ($nbFBCriteria != count($activityCriteria)) {
                            $usersResults = $activity->getResults();

                            foreach ($activityCriteria as $criterion) {
                                if ($criterion->getType() != 2) {
                                    $rowData = [];
                                    $rowData[] = $criterion->getCName()->getName();
                                    $criterionParticipants = $criterion->getParticipants();
                                    $nbCriterionParticipants = count($criterionParticipants);

                                    foreach ($uniqueActivityParticipants as $uniqueActivityParticipant) {
                                        $foundParticipant = false;

                                        foreach ($criterionParticipants as $criterionParticipant) {
                                            if ($criterionParticipant->getUsrId() == $uniqueActivityParticipant->getUsrId()) {
                                                $participantUserId = $criterionParticipant->getUsrId();

                                                $userResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("criterion", $criterion))->andWhere(Criteria::expr()->eq("usrId", $participantUserId)))[0];

                                                $rowData[] = ($equalEntries == 0) ? round($userResult->getWeightedDevRatio(), 3) : round($userResult->getEqualDevRatio(), 3);

                                                $foundParticipant = true;
                                                break;
                                            }
                                        }

                                        if (!$foundParticipant) {
                                            $rowData[] = null;
                                        }
                                    }

                                    $criterionResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("criterion", $criterion))->andWhere(Criteria::expr()->eq("usrId", null)))[0];
                                    $activityResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("activity", $activity))->andWhere(Criteria::expr()->eq("stage", null))->andWhere(Criteria::expr()->eq("usrId", null)))[0];

                                    if ($equalEntries == 0) {
                                        $criterionAvgDistResult = round($criterionResult ? $criterionResult->getWeightedDistanceRatio() : 0, 3);
                                        $activityAvgDistResult = round($activityResult ? $activityResult->getWeightedDistanceRatio() : 0, 3);
                                    } else {
                                        $criterionAvgDistResult = round($criterionResult ? $criterionResult->getEqualDistanceRatio() : 0, 3);
                                        $activityAvgDistResult = round($activityResult ? $activityResult->getEqualDistanceRatio() : 0, 3);
                                    }

                                    $rowData[] = $criterionAvgDistResult;
                                    $rowData[] = round(100 * $criterionAvgDistResult, 1) . ' %';
                                    $rowData[] = '<h5 style="color: red"> ' . round(100 * $criterionAvgDistResult, 1) . ' %</h5>';
                                    $rowData[] = $activityAvgDistResult;

                                    $distGraphData[] = $rowData;
                                }
                            }

                            $distAvgResult = round(100 * $activityAvgDistResult, 1) . ' %';
                            $meanDistSeriesIndex = ($user->getRole() != 3) ? $nbCriterionParticipants : 1;
                        }
                    } else {

                        $usersResults = $activity->getResults();
                        $activityStages = $activity->getOCompletedStages();
                        $nbFBStages = 0;

                        $activityResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("activity", $activity))->andWhere(Criteria::expr()->eq("stage", null))->andWhere(Criteria::expr()->eq("usrId", null)))[0];

                        foreach ($activityStages as $stage) {
                            if ((count($stage->getParticipants()) != count($stage->getCriteria()))) {
                                $nbFBCriteria = 0;
                                foreach ($stage->getCriteria() as $criterion) {
                                    if ($criterion->getType() == 2) {
                                        $nbFBCriteria++;
                                    }
                                }

                                if ($nbFBCriteria != count($stage->getCriteria())) {
                                    $rowData = [];
                                    $rowData[] = $stage->getName();
                                    // As participants are the same for each criterion, selecting first criterion and finding associated users is equivalent to find stage participants
                                    $criterionParticipants = $stage->getCriteria()->first()->getParticipants();
                                    foreach ($uniqueActivityParticipants as $uniqueActivityParticipant) {
                                        $foundParticipant = false;

                                        foreach ($criterionParticipants as $criterionParticipant) {
                                            if ($criterionParticipant->getUsrId() == $uniqueActivityParticipant->getUsrId()) {
                                                $participantUserId = $criterionParticipant->getUsrId();
                                                $userResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("stage", $stage))->andWhere(Criteria::expr()->eq("usrId", $participantUserId))->andWhere(Criteria::expr()->eq("criterion", null)))[0];

                                                if (is_null($userResult)) {
                                                    $rowData[] = null;
                                                } else {
                                                    $rowData[] = $equalEntries == 0 ? round($userResult->getWeightedDevRatio(), 3) : round($userResult->getEqualDevRatio(), 3);
                                                }

                                                $foundParticipant = true;
                                                break;
                                            }
                                        }

                                        if (!$foundParticipant) {
                                            $rowData[] = null;
                                        }
                                    }

                                    $stageResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("stage", $stage))->andWhere(Criteria::expr()->eq("criterion", null))->andWhere(Criteria::expr()->eq("usrId", null)))[0];

                                    if ($equalEntries == 0) {
                                        $stageAvgDistResult = round($stageResult->getWeightedDistanceRatio(), 3);
                                        $activityAvgDistResult = round($activityResult->getWeightedDistanceRatio(), 3);
                                    } else {
                                        $stageAvgDistResult = round($stageResult->getEqualDistanceRatio(), 3);
                                        $activityAvgDistResult = round($activityResult->getEqualDistanceRatio(), 3);
                                    }

                                    $rowData[] = $stageAvgDistResult;
                                    $rowData[] = round(100 * $stageAvgDistResult, 1) . ' %';
                                    $rowData[] = '<h5 style="color: red"> ' . round(100 * $stageAvgDistResult, 1) . '</h5>';
                                    $rowData[] = $activityAvgDistResult;

                                    $distGraphData[] = $rowData;
                                }
                            }
                        }

                        $meanDistSeriesIndex = count($uniqueActivityParticipants);
                        $distAvgResult = round(100 * $activityAvgDistResult, 1) . ' %';
                    }

                    $data['overview'] = 1;
                } else {
                    // Defining first row, being all activity unique non TP participants

                    $rowData = [];
                    $rowData[] = 'Criteria';
                    $stage = $activity->getStages()->get($stgIndex);

                    $stageParticipants = $repoP->findBy(['stage' => $stage], ['usrId' => 'ASC']);

                    $uniqueStageParticipants = [];
                    $actualId = 0;
                    $nbTPs = 0;
                    $nbFBCriteria = 0;

                    foreach ($stageParticipants as $stageParticipant) {
                        if ($stageParticipant->getType() == -1) {
                            continue;
                        }

                        if ($stageParticipant->getUsrId() != $actualId) {
                            $actualId = $stageParticipant->getUsrId();
                            if ($detail == 1 or $user->getId() == $actualId) {
                                $uniqueStageParticipants[] = $stageParticipant;
                            }
                        }
                    }

                    foreach ($uniqueStageParticipants as $uniqueStageParticipant) {
                        $dataParticipant = [];
                        $dataParticipant['label'] = ($uniqueStageParticipant->getUser()->getFullname()) ?: $uniqueStageParticipant->getUser()->getOrganization($app)->getCommname();
                        $dataParticipant['type'] = 'number';
                        $rowData[] = $dataParticipant;
                    }

                    $rowData[] = '';
                    $style = [];
                    $style['role'] = 'annotation';
                    $rowData[] = $style;
                    $tooltip = [];
                    $tooltip['type'] = 'string';
                    $tooltip['role'] = 'tooltip';
                    $tooltip['p']['html'] = true;
                    $rowData[] = $tooltip;
                    $rowData[] = '';
                    $distGraphData[] = $rowData;

                    $stageCriteria = $activity->getStages()->get($stgIndex)->getCriteria();

                    foreach ($stageCriteria as $criterion) {
                        if ($criterion->getType() == 2) {
                            $nbFBCriteria++;
                        }
                    }

                    if ($nbFBCriteria != count($stageCriteria)) {
                        $usersResults = $stage->getResults();

                        foreach ($stageCriteria as $criterion) {
                            if ($criterion->getType() != 2) {
                                $rowData = [];
                                $rowData[] = $criterion->getCName()->getName();
                                $criterionParticipants = $criterion->getParticipants();

                                foreach ($uniqueStageParticipants as $uniqueStageParticipant) {
                                    $foundParticipant = false;

                                    foreach ($criterionParticipants as $criterionParticipant) {
                                        if ($criterionParticipant->getUsrId() == $uniqueStageParticipant->getUsrId()) {
                                            $participantUserId = $criterionParticipant->getUsrId();
                                            $userResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("criterion", $criterion))->andWhere(Criteria::expr()->eq("usrId", $participantUserId)))[0];
                                            $rowData[] = ($equalEntries == 0) ? round($userResult->getWeightedDevRatio(), 3) : round($userResult->getEqualDevRatio(), 3);
                                            $foundParticipant = true;
                                            break;
                                        }
                                    }

                                    if (!$foundParticipant) {
                                        $rowData[] = null;
                                    }

                                }

                                $criterionResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("criterion", $criterion))->andWhere(Criteria::expr()->eq("usrId", null)))[0];
                                $stageResult = $usersResults->matching(Criteria::create()->where(Criteria::expr()->eq("criterion", null))->andWhere(Criteria::expr()->eq("usrId", null)))[0];

                                if ($equalEntries == 0) {
                                    $criterionAvgDistResult = round($criterionResult->getWeightedDistanceRatio(), 3);
                                    $stageAvgDistResult = round($stageResult->getWeightedDistanceRatio(), 3);
                                } else {
                                    $criterionAvgDistResult = round($criterionResult->getEqualDistanceRatio(), 3);
                                    $stageAvgDistResult = round($stageResult->getEqualDistanceRatio(), 3);
                                }

                                $rowData[] = $criterionAvgDistResult;
                                $rowData[] = round(100 * $criterionAvgDistResult, 1) . ' %';
                                $rowData[] = '<h5 style="color: red">' . $criterionAvgPerfResult . '</h5>';
                                $rowData[] = $stageAvgDistResult;

                                $distGraphData[] = $rowData;
                            }
                        }

                        $meanDistSeriesIndex = count($uniqueStageParticipants);
                        $distAvgResult = round(100 * $stageAvgDistResult, 1) . ' %';
                    }
                }
            }

            $userRole = $user->getRole();

            function actManagerCanPublish(EntityRepository $repoP, Activity $activity, User $user, int $stgIndex = -1)
            {
                if ($stgIndex == -1) {
                    $userId = $user->getId();

                    return $activity->getParticipants()->filter(function (Participation $e) use ($userId) {
                        return $e->getUsrId() == $userId;
                    })->forAll(function (int $i, Participation $e) {
                        return $e->isLeader();
                    });
                } else {
                    /** @var Stage */
                    $stage = $activity->getStages()->get($stgIndex);
                    /** @var Participation */
                    $Participation = $repoP->findOneBy(['stage' => $stage, 'usrId' => $user->getId()]);

                    return $Participation and $Participation->isLeader();
                }
            }

            $data['isPublishable'] = (
                $userRole == 4// root
                 or
                $userRole == 1// admin
                 or
                ($userRole == 2 and actManagerCanPublish($repoP, $activity, $user, $stgIndex)) // AM & is stage leader
            );

            $data['displayablePerfGraph'] = count($perfGraphData) > 1;
            $data['perfGraphData'] = isset($perfGraphData) ? $perfGraphData : null;
            $data['distGraphData'] = isset($distGraphData) ? $distGraphData : null;
            $data['meanDistSeriesIndex'] = isset($meanDistSeriesIndex) ? $meanDistSeriesIndex : null;
            $data['perfGraphIdElmt'] = 'chart_perf_res';
            $data['distGraphIdElmt'] = 'chart_dist_res';
            $data['perfAvgResult'] = isset($perfAvgResult) ? $perfAvgResult : null;
            $data['distAvgResult'] = isset($distAvgResult) ? $distAvgResult : null;
            $data['stageStatus'] = isset($stageStatus) ? $stageStatus : null;
            $data['isViewable'] = $isViewable;
            $data['isPublished'] = $isPublished;
            $data['actId'] = $actId;
            $data['stgId'] = isset($stgId) ? $stgId : null;
            $data['crtId'] = isset($crtId) ? $crtId : null;
            $data['stgIndex'] = $stgIndex;
            $data['crtIndex'] = $crtIndex;
            $data['binaryResults'] = ($binaryResults != null) ? $binaryResults : null;
            $data['participantObjectives'] = $participantObjectives;

            // We display the criterion name in case of an activity with one stage and criterion
            $data['crtName'] = (isset($criterion) && count($activity->getStages()) == 1 && count($activity->getStages()->first()->getCriteria()) == 1) ? $criterion->getCName()->getName() : null;
            $data['crtType'] = ($crtIndex != -1 && $crtIndex != -2) ? $criterion->getType() : null;
            $data['crtStep'] = ($crtIndex != -1 && $crtIndex != -2) ? $criterion->getStep() : null;

        } else {

            $data['stageStatus'] = isset($stageStatus) ? $stageStatus : null;
            $data['isViewable'] = $isViewable;

        }

        //$data = preg_replace('/"([a-zA-Z]+[a-zA-Z0-9_]*)":/','$1:',$data;

        return json_encode($data, JSON_UNESCAPED_UNICODE);

    }

    //Releases stage results to participants

    /**
     * @param Application $app
     * @param $stgId
     * @return bool|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/stage/{stgId}/release", name="releaseStage")
     */
    public function releaseStage(Application $app, $stgId)
    {


        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }
        $em = $this->em;

        $stage = $em->getRepository(Stage::class)->find($stgId);
        $activity = $stage->getActivity();

        foreach ($stage->getCriteria() as $criterion) {
            foreach ($criterion->getParticipants() as $participant) {

                // We just release the activity to users belonging to the organization
                if ($participant->getUser()->getOrgId() == $currentUser->getOrgId()) {
                    $participant->setStatus(4);
                    $em->persist($participant);
                }
            }
        }

        $recipientsParticipants = [];
        $recipientsReleaser = [];
        $recipientsAdministrators = [];
        $settingsParticipants = [];
        $settingsReleaser = [];
        $settingsAdministrators = [];

        $settingsParticipants['stage'] = $stage;
        $settingsReleaser['stage'] = $stage;
        $settingsAdministrators['stage'] = $stage;

        foreach ($stage->getCriteria()->first()->getParticipants() as $participant) {

            // Like above, we just email users belonging to the organization
            if ($participant->getUser()->getOrgId() == $currentUser->getOrgId()) {

                $user = $participant->getUser();
                //Sending release msg to participants and confirmation release to "releaser" (A or AM)
                if ($currentUser != $user) {
                    $recipientsParticipants[] = $user;
                } else {
                    $settingsReleaser['releaser'] = true;
                    $recipientsReleaser[] = $user;
                }
            }

        }

        $settings['releaser'] = false;

        $stage->setStatus(3);

        //Check if activity has been fully released
        $unrealasedActivity = true;
        foreach ($stage->getActivity()->getStages() as $actStage) {
            if ($actStage->getStatus() != 3) {
                $unrealasedActivity = false;
            }
        }
        if ($unrealasedActivity) {
            $stage->getActivity()->setStatus(3);
        }

        $em->persist($stage);

        //Sending notification to all administrators
        $orgId = $currentUser->getOrgId();
        $administrators = $em->getRepository(User::class)->findBy(['role' => 1, 'orgId' => $orgId]);
        foreach ($administrators as $administrator) {
            if ($currentUser->getId() != $administrator->getId()) {
                $recipientsAdministrators[] = $administrator;
            }
        }

        $settingsParticipants['participant'] = true;
        MasterController::sendMail($app, $recipientsParticipants, 'resultsReleased', $settingsParticipants);
        MasterController::sendMail($app, $recipientsReleaser, 'resultsReleased', $settingsReleaser);
        if (count($administrators) > 0) {
            $settingsAdministators['administrator'] = true;
            MasterController::sendMail($app, $recipientsAdministrators, 'resultsReleased', $settingsAdministrators);
        }

        $em->flush();
        return true;

    }

    //Releases activity results to participants

    /**
     * @param Application $app
     * @param $actId
     * @return bool|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/activity/{actId}/release", name="releaseActivity")
     */
    public function releaseActivity(Application $app, $actId)
    {

        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        $em = $this->em;
        $repoA = $em->getRepository(Activity::class);
        $repoP = $em->getRepository(Participation::class);

        /** @var Activity|null */
        $activity = $repoA->find($actId);
        if (!$activity) {
            throw new \Exception("activity with id $actId doesn't exist");
        }

        /** @var Participation[] */
        $participants = $repoP->findBy(['activity' => $activity, 'status' => 3]);
        /** @var ArrayCollection<User> */
        $totalUsers = new ArrayCollection;
        /** @var User[] */
        $recipientsParticipants = [];
        /** @var User[] */
        $recipientsReleaser = [];
        /** @var User[] */
        $recipientsAdministrators = [];
        $settingsParticipants = [];
        $settingsReleaser = [];
        $settingsAdministrators = [];
        $settingsParticipants['activity'] = $activity;
        $settingsReleaser['activity'] = $activity;
        $settingsAdministrators['activity'] = $activity;

        foreach ($participants as $participant) {
            // We just release the activity and mail users belonging to the organization
            if ($participant->getUser()->getOrgId() == $currentUser->getOrgId()) {
                $participant->setStatus(4);
                $em->persist($participant);

                if (!$totalUsers->contains($participant->getUser())) {
                    $totalUsers->add($participant->getUser());
                }
            }
        }

        /** @var User[] */
        $totalUsersValues = $totalUsers->getValues();
        foreach ($totalUsersValues as $user) {
            if ($currentUser != $user) {
                $recipientsParticipants[] = $user;
            } else {
                $settingsReleaser['releaser'] = true;
                $recipientsReleaser[] = $user;
            }
        }

        /** @var Stage[] */
        $activityStages = $activity->getStages()->getValues();
        foreach ($activityStages as $stage) {
            $stage->setStatus(Stage::STAGE_PUBLISHED);
            $em->persist($stage);
        }
        $activity->setStatus(3);
        $em->persist($activity);

        //Sending notification to all administrators
        $orgId = $currentUser->getOrgId();
        $administrators = $em->getRepository(User::class)->findBy(['role' => 1, 'orgId' => $orgId]);
        foreach ($administrators as $administrator) {
            if ($currentUser->getId() != $administrator->getId()) {
                $recipientsAdministrators[] = $administrator;
            }
        }

        $settingsParticipants['participant'] = true;
        MasterController::sendMail($app, $recipientsParticipants, 'resultsReleased', $settingsParticipants);
        MasterController::sendMail($app, $recipientsReleaser, 'resultsReleased', $settingsReleaser);
        if (count($administrators) > 0) {
            $settingsAdministators['administrator'] = true;
            MasterController::sendMail($app, $recipientsAdministrators, 'resultsReleased', $settingsAdministrators);
        }

        $em->flush();
        return true;
    }
}
