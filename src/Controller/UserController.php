<?php

namespace App\Controller;

use App\Model\ActivityM;
use App\Model\StageM;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use App\Form\AddFirstAdminForm;
use App\Form\AddUserPictureForm;
use App\Form\DelegateActivityForm;
use App\Form\FinalizeUserForm;
use App\Form\RequestActivityForm;
use App\Form\ContactForm;
use App\Entity\Participation;
use App\Entity\Member;
use App\Entity\Decision;
use App\Entity\Department;
use App\Entity\Organization;
use App\Entity\Contact;
use App\Entity\Ranking;
use App\Entity\RankingTeam;
use App\Entity\Result;
use App\Entity\Stage;
use App\Entity\Criterion;
use App\Entity\CriterionName;
use App\Entity\OrganizationUserOption;
use App\Form\AddProcessForm;
use App\Form\PasswordDefinitionForm;
use App\Form\SignUpForm;
use App\Form\UpdateWorkerIndividualForm;
use App\Form\UserPublicForm;
use phpDocumentor\Reflection\Type;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Mail;
use App\Entity\Position;
use App\Entity\Activity;
use App\Entity\InstitutionProcess;
use App\Entity\Process;
use App\Entity\WorkerFirm;
use App\Entity\WorkerIndividual;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends MasterController
{
    /*********** ADDITION, MODIFICATION, DELETION AND DISPLAY OF USERS *****************/
    /**
     * @param Request $request
     * @return mixed
     * @Route("/terms-conditions", name= "displayTC")
     */
    public function displayTCAction(Request $request) {
        return $this->render(
            'terms_conditions.html.twig',
            [
                'request' => $request
            ]
        );
    }

    /**
     * @Route ("/trk/{trkToken}", name="trackMLinkClick")
     * @param Application $app
     * @param $trkToken
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function trackMLinkClick(Application $app, $trkToken) {
        $this->em = $this->em;
        $repoM = $this->em->getRepository(Mail::class);
        $clickedLinkMail = $repoM->findOneByToken($trkToken);
        if ($clickedLinkMail !== null) {
            $parameters = [];
            $actionType = $clickedLinkMail->getType();
            switch ($actionType) {
                case 'prospecting_1':
                    $path = 'login';
                    break;
                case 'activityParticipation':
                case 'activityValidation':
                case 'activityAssignation':
                case 'updateProgressStatus':
                case 'request' :
                    $path = 'myActivities';
                    break;
                case 'teamCreation':
                    $path = 'home';
                    break;
                case 'activityCreation':
                    $path = 'oldActivityDefinition';
                    $parameters = [
                        'elmt' => 'activity',
                        'elmtId' => $clickedLinkMail->getActivity()->getId()
                    ];
                    break;
                case 'resultsReleasable':
                case 'resultsReleased':
                    $path = 'activityResults';
                    $parameters = [
                        'actId' => $clickedLinkMail->getActivity()->getId()
                    ];
                    break;
                case 'gradingDeadlineReminder':
                case 'unvalidatedGradesStageJoiner':
                case 'unvalidatedGradesTeamJoiner':
                    $path = 'newStageGrade';
                    $parameters = [
                        'stgId' => $clickedLinkMail->getStage()->getId()
                    ];
                    break;
                case 'validateOrgSbuscription':
                    $path = 'validateOrganization';
                    $parameters = [
                        'orgId' => $clickedLinkMail->getOrganization()->getId()
                    ];
                    break;
                default:
                    break;
            }

            $clickedLinkMail->setRead(new DateTime);
            $this->em->persist($clickedLinkMail);
            $this->em->flush();
            return $this->redirectToRoute($path, $parameters);
        } else {
            return $this->redirectToRoute('login');
        }
    }

    // Create pwd (render form)

    /**
     * @param Request $request
     * @param Application $app
     * @param $token
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/password/define/{token}", name="definePassword")
     */
    public function definePasswordAction(Request $request, $token)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(User::class);
        $user = $repository->findOneByToken($token);
        
        $pwdForm = $this->createForm(PasswordDefinitionForm::class, $user, ['standalone' => true]);
        $pwdForm->handleRequest($request);

        if ($pwdForm->isSubmitted() && $pwdForm->isValid()) {
            $encoder = $app['security.encoder_factory']->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), 'azerty');
            $user->setPassword($password);

            $user->setToken('');
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        if (!$user) {
            return $this->render('user_no_token.html.twig');
        } else {

            return $this->render('password_definition.html.twig',
                [
                    'firstname' => $user->getFirstName(),
                    'form' => $pwdForm->createView(),
                    'token' => $token,
                    'request' => $request,
                ]);
        }
    }

    /**
     * @Route("/accounts/signup", name="insticoSignup")
     * @param Request $request
     * @param Application $app
     * @return string
     * @throws ORMException
     * @throws OptimisticLockException
     *
     */
    public function signupAction(Request $request)
    {
        $entityManager = $this->getEntityManager();

        
        $user = new User;
        $signupForm = $this->createForm(SignUpForm::class, $user, ['standalone' => true]);
        $signupForm->handleRequest($request);

        if ($signupForm->isSubmitted() && $signupForm->isValid()) {

            $encoder = $app['security.encoder_factory']->getEncoder($user);
            $unencodedPwd = $user->getPassword();
            $password = $encoder->encodePassword($unencodedPwd, 'azerty');
            $repoO = $entityManager->getRepository(Organization::class);
            $repoWI = $entityManager->getRepository(WorkerIndividual::class);
            $user->setPassword($password)->setRole(3)->setOrgId($repoO->findOneByCommname('Public')->getId());
            $entityManager->persist($user);
            $entityManager->flush();

            $workerIndividual = $repoWI->findBy(['firstname' => $user->getFirstname(), 'lastname' => $user->getLastname()]);
            if(!$workerIndividual){
                $workerIndividual = new WorkerIndividual;
                $workerIndividual
                    ->setFirstname($user->getFirstname())
                    ->setLastname($user->getLastname())
                    ->setCreated(true);
            }

            $workerIndividual
                ->setEmail($user->getEmail())
                ->setCreatedBy($user->getId());

            $entityManager->persist($workerIndividual);
            $user->setWorkerIndividual($workerIndividual);

            $recipients = [];
            $recipients[] = $user;
            $settings = [];
            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'signup']);


            return $this->redirectToRoute('home');
        }


        return $this->render(
            'signup.html.twig',
            [
                'form' => $signupForm->createView(),
                'request' => $request,
            ]
        );
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("/institutions/all", name="displayAllInstitutions")
     */
    public function displayAllInstitutionsAction(Request $request){

        $entityManager = $this->em;
        $repoO = $entityManager->getRepository(Organization::class);
        $institutions = $repoO->findBy(['type' => 'P'],['commname' => 'ASC']);
        

        $addInstitutionProcessForm = $this->createForm(AddProcessForm::class, null, ['standalone' => true]);
        $addInstitutionProcessForm->handleRequest($request);
        $currentUser = $this->user;

        return $this->render('institution_list.html.twig',
            [
                'institutions' => $institutions,
                'processForm' => $addInstitutionProcessForm->createView(),
            ]);

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/process/create/institution/{orgId}", name="createProcessRequest")
     */
    public function createProcessRequestAction(Request $request, $orgId)
    {
        $entityManager = $this->getEntityManager();
        $repoO = $entityManager->getRepository(Organization::class);
        /** @var Organization */
        if($orgId == 0){
            $organization = $entityManager->getRepository(Process::class)->findAll()[0]->getOrganization();
            $element = new Process;
            $entity = 'process';
        } else {
            $organization = $repoO->find($orgId);
            $element = new InstitutionProcess;
            $entity = 'iprocess';
        }
       
        $currentUser = $this->user;;

        if (!$organization) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        
        $createProcessRequestForm = $this->createForm(AddProcessForm::class, $element, ['standalone' => true, 'organization' => $organization, 'entity' => $entity]);
        $createProcessRequestForm->handleRequest($request);

        if ($createProcessRequestForm->isValid()) {
            $element->setOrganization($organization)->setApprovable(true)->setCreatedBy($currentUser->getId());
            $entityManager->persist($element);
            $entityManager->flush();

            $recipientsAdministrators = [];

            $administrators = $entityManager->getRepository(User::class)->findBy(['role' => $orgId == 0 ? 4 : 1, 'orgId' => $organization->getId()]);
            foreach ($administrators as $administrator) {
                if ($currentUser->getId() != $administrator->getId()) {
                    $recipientsAdministrators[] = $administrator;
                }
            }

            $settings['process'] = $element;
            $settings['requester'] = $currentUser;
            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipientsAdministrators, 'settings' => $settings, 'actionType' => 'requestProcess']);

            return new JsonResponse(['id' => $element->getId()],200);
        } else {
            $errors = $this->buildErrorArray($createProcessRequestForm);
            return $errors;
        }
    }

    // Create pwd (AJAX submission)

    /**
     * @param Request $request
     * @param Application $app
     * @param $token
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/password/{token}", name="createPasswordAJAX")
     */
    public function createPwdActionAJAX(Request $request, $token)
    {
        //try{
        $entityManager = $this->em;
        $repoU = $entityManager->getRepository(User::class);
        $user = $repoU->findOneByToken($token);
            
            $pwdForm = $this->createForm(PasswordDefinitionForm::class, $user, ['standalone' => true]);
            $pwdForm->handleRequest($request);

            if ($pwdForm->isValid()) 
            {
                $password = $this->encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $user->setToken('');
                $entityManager->persist($user);
                $entityManager->flush();
                return new JsonResponse(['message' => 'Success!'], 200);
                /*return $this->redirectToRoute('login')*/
            } else {
                $errors = $this->buildErrorArray($pwdForm);
                return $errors;
            }
        /*} catch(\Exception $e){
            print_r($e->getMessage());
            die;
        }*/

    }

    /**
     * @param Request $request
     * @param Application $app
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/profile", name="manageProfile")
     */
    public function manageProfileAction(Request $request){
        $currentUser = $this->user;
        $organization = $currentUser->getOrganization();
        if (!$currentUser /*|| $organization->getCommname() != "Public"*/) {
            return $this->redirectToRoute('login');
        }

        if($currentUser->getWorkerIndividual() == null){
            $workerIndividual = new WorkerIndividual;
            $workerIndividual->setFirstname($currentUser->getFirstname())
                ->setLastname($currentUser->getLastname());
        }

        $entityManager = $this->em;
        
        $workerIndividual = $currentUser->getWorkerIndividual();
        $workerIndividualForm = $this->createForm(UpdateWorkerIndividualForm::class, $workerIndividual, ['standalone' => true]);
        $workerIndividualForm->handleRequest($request);
        $pictureForm = $this->createForm(AddUserPictureForm::class);
        $pictureForm->handleRequest($request);

        if ($workerIndividualForm->isSubmitted() && $workerIndividualForm->isValid()) {
            $repoWF = $entityManager->getRepository(WorkerFirm::class);
            foreach($workerIndividualForm->get('experiences') as $key => $experienceForm){
                $experience = $experienceForm->getData();
                $experience->setFirm($repoWF->find((int) $experienceForm->get('firm')->getData()));
                $entityManager->persist($experience);
                /*
                if($experience->getEnddate() == null){
                    $workerIndividual->getExperiences()->get($key)->setEnddate(new \DateTime);
                }*/
            }
            $entityManager->persist($currentUser);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('worker_individual_data.html.twig',
        [
            'form' => $workerIndividualForm->createView(),
            'pictureForm' => $pictureForm->createView()
        ]);

    }

    /**
     * @param Request $request
     * @param $entity
     * @param $elmtId
     * @return JsonResponse|Response
     * @Route("/institution/{entity}/config/{elmtId}", name="getElementConfig")
     */
    public function getElementConfigAction($entity, $elmtId){
        switch ($entity) {
            case 'iprocess':
                $repoE    = $this->em->getRepository(InstitutionProcess::class);
                break;
            case 'process':
                $repoE    = $this->em->getRepository(Process::class);
                break;
            case 'activity':
                $repoE    = $this->em->getRepository(Activity::class);
                break;
            default:
                return new Response(null, Response::HTTP_BAD_REQUEST);
        }

        $element = $repoE->find($elmtId);
        $elmtConfig = [];
        $elmtConfig['id'] = $element->getId();
        $elmtConfig['name'] = $element->getName();
        $currentUser = $this->user;
        if (($this->user->getOrganization() == $element->getOrganization())) {
            if (($this->user->getRole() === USER::ROLE_ADMIN || $this->user->getRole() === USER::ROLE_ROOT)) {
                $stages =
                    ($element->getActiveStages());
            } else {
                $stages =
                    ($element->getActiveModifiableStages());
            }
        } else {
            $stages = $element->getStages()->filter(static function ($s) use ($currentUser) {
                    return $s->getParticipants()->filter(static function ($p) use ($currentUser) {
                        return $p->getUser() == $currentUser;
                    });
                });
        }

        if($entity === 'activity'){
            $elmtConfig['pStatus'] = $element->getProgress();
            $elmtConfig['oStatus'] = $element->getStatus();
            $elmtConfig['nbOCompletedStages'] = $element->getOCompletedStages()->count();
            $elmtConfig['nbPCompletedStages'] = $element->getPCompletedStages()->count();
        }

        $repoU = $this->em->getRepository(User::class);
        if ($element->getMasterUser()) {
            $masterUser = $entity === 'activity' ? $repoU->find($element->getMasterUser()) : ($element->getMasterUser());
        } else {
            $masterUser = $entity === 'activity' ? $repoU->find($element->getMasterUser()) : ($repoU->find($element->getCreatedBy()));
        }
        $elmtConfig['masterUserFullname'] = $masterUser->getFullname();
        $elmtConfig['masterUserPicture'] = $masterUser->getPicture();
        $elmtConfig['masterFNFL'] = $masterUser->getFirstName()[0];
        $activityM = new ActivityM($this->em, $this->stack, $this->security);
        $stageM = new StageM($this->em, $this->stack, $this->security);
        $elmtConfig['canEdit'] = $activityM->userCanEdit($element, $this->user);
        $elmtConfig['isDeletable'] = ($entity === 'activity') ? $activityM->isDeletable($element) && $elmtConfig['canEdit'] : false;
        foreach($stages as $stage){
            $sData = [];
            $sData['id'] = $stage->getId();
            if($entity === 'activity'){
                $sData['r'] = $stage->isReopened();
            }
            $sData['name'] = $stage->getName();
            $sData['pReadiness'] = sizeof($stageM->getGradableParticipants($stage)) >= 1 && sizeof($stageM->getGraderParticipants($stage)) >= 1;
            $sData['oReadiness'] = sizeof($stage->getCriteria()) >= 1 || $stage->getSurvey() !== null;
            $sData['progress'] = $stage->getProgress();
            $sData['ddates'] = $stage->isDefiniteDates();
            $sData['startdate'] = $stage->getStartdate();
            $sData['enddate'] = $stage->getEnddate();
            //$sData['gstartdate'] = $stage->getGStartdate();
            //$sData['genddate'] = $stage->getGEnddate();
            $sData['dorigin'] = $stage->getDOrigin();
            $sData['dperiod'] = $stage->getDPeriod();
            $sData['dfreq'] = $stage->getDFrequency();
            $sData['forigin'] = $stage->getFOrigin();
            $sData['fperiod'] = $stage->getFPeriod();
            $sData['ffreq'] = $stage->getFFrequency();
            $sData['activeWeight'] = $stage->getActiveWeight();
            $sData['mode'] = $stage->getMode();

            foreach($stage->getCriteria() as $criterion){
                $cData = [];
                $cData['name'] = $criterion->getCName()->getName();
                $cData['icon'] = $criterion->getCName()->getIcon()->getUnicode() ? '~'.$criterion->getCName()->getIcon()->getUnicode().'~' : '~f1b2~';
                $cData['type'] = $criterion->getType();
                $cData['weight'] = $criterion->getWeight();
                $sData['criteria'][] = $cData;
            }

            $team = null;
            foreach($stage->getIndependantParticipants() as $participant){
                $pData = [];
                if($participant->getTeam() !== null){

                    $team = $participant->getTeam();
                    $tData = [];
                    $tData['name'] = $team->getName();
                    $tData['picture'] = $team->getPicture();

                    foreach($team->getMembers() as $member){
                        $user = $member->getUser();
                        $pData['img'] = $user->getPicture();
                        $pData['name'] = $user->getFullName();
                        $pData['fnfl'] = $user->getFirstName()[0];
                        $pData['type'] = $participant->getType();
                        $pData['status'] = $participant->getStatus();
                        $tData['indivs'][] = $pData;
                    }

                    $sData['teams'][] = $tData;

                } else {

                    $user = $participant->getUser();
                    $pData['img'] = $user->getPicture();
                    $name = $user->getFirstname() === 'ZZ' ? $user->getOrganization()->getCommname() :  $user->getFullName();
                    $pData['name'] = $name;
                    $pData['fnfl'] = $name[0];
                    $pData['type'] = $participant->getType();
                    $pData['status'] = $participant->getStatus();
                    $sData['indivs'][] = $pData;

                }

            }

            $config['stage']['survey'] = [];
            if($stage->getSurvey()){
                $surData = [];
                $surData['name'] = $stage->getSurvey()->getName();
                $surData['nbQuestions'] = sizeof($stage->getSurvey()->getFields());
                $sData['survey'] = $surData;
            }

            $elmtConfig['activeStages'][] = $sData;

        }
        return new JsonResponse($elmtConfig, 200);
    }


    /**
     * @param Request $request
     * @param $inpId
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Exception
     * @Route("/institution/activity/process/{inpId}", name="createUserProcessActivity")
     */
    public function createUserProcessActivity(Request $request, $inpId){
        $repoIP = $this->em->getRepository(InstitutionProcess::class);
        $repoU = $this->em->getRepository(User::class);
        $currentUser = $this->user;
        // If not fresh new internal activity, institution is null. If activity request by citizen/external, then is necessarily linked to an (i)process
        $institutionProcess = $inpId !== 0 ? $repoIP->findOneById($inpId) : 0;
        var_dump($institutionProcess);
        $institution = ((int)$_POST['fi'] === 1) ? $currentUser->getOrganization() : $institutionProcess->getOrganization();
        $activity = new Activity;
        $startdate = new DateTime;
        $fromInternal = ((int)$_POST['fi']) === 1;
        $actName = $_POST['an'];
        $isUnlinkedToAnyProcess = isset($_POST['up']) &&  (int)$_POST['up'] === 1;
        $informingMail = isset($_POST['im']) && (int)$_POST['im'] === 1;

        if($actName !== ''){
            $duplicateActivity = $this->em->getRepository(Activity::class)->findOneBy(['name' => $actName, 'organization' => $institution]);
            if($duplicateActivity){
                return new JsonResponse(['errorMsg' => 'There is already an activity created with such name. Please give another one'], 500);
            }
        }

        // Does IProcess has defined stages ? If it is the case, activity will inheritate from IProcess stages and so on
        // Otherwise, activity will inheritate from Process stages and so on
        if($institutionProcess != null) {
            if ($institutionProcess && !$institutionProcess->isApprovable()) {
                $IProcessStages = $institutionProcess->getStages();

                if (count($IProcessStages) !== 0) {
                    $baseElement = $institutionProcess;
                } else if (count($institutionProcess->getProcess()->getStages()) !== 0) {
                    $baseElement = $institutionProcess->getProcess();
                } else {
                    return new JsonResponse('No possibility to create activity', 500);
                }
            }

            if ($isUnlinkedToAnyProcess || $institutionProcess->isApprovable()) {
                return $this->redirectToRoute("activityInitialisation", ['entity' => 'activity', 'inpId' => $inpId, 'actName' => $actName]);
                //$activityController = new ActivityController($this->em, $this->security, $this->stack);
                //return $activityController->addActivityId('activity', $inpId, $actName);
            }

// We duplicate activity process/iprocess
            $activity
                ->setName($actName !== '' ? $actName : $institutionProcess->getName())
                ->setOrganization($institution)
                ->setMasterUserId(
                    $institutionProcess->getMasterUser() ?
                        $institutionProcess->getMasterUser()->getId() :
                        $repoU->findOneBy(['firstname' => 'ZZ', 'lastname' => 'ZZ', 'orgId' => $institution->getId()])->getId()
                )
                ->setCreatedBy($currentUser->getId())
                ->setStatus($institutionProcess->isApprovable() ? -3 : 1);

            if ($institutionProcess->isApprovable() & !$fromInternal) {
                $recipients = [];
                /*** We need the person in charge of approval : it is, by order of importance,
                 * 1/ The person in charge of the process, or
                 * 2/ Parent process responsible (up to order 2), or
                 * 3/ One administrator (the first alive in the DB)
                 ***/
                $IProcessResponsible = $institutionProcess->getMasterUser();
                $parentIProcess = $institutionProcess->getParent();
                $grandParentIProcess = $parentIProcess !== null ? $parentIProcess->getParent() : null;

                if ($IProcessResponsible !== null) {
                    $recipients[] = $IProcessResponsible;
                } else if ($parentIProcess) {
                    $parentIProcessResponsible = $parentIProcess->getMasterUser();
                    if ($parentIProcessResponsible !== null) {
                        $recipients[] = $parentIProcessResponsible;
                    } else if ($grandParentIProcess) {
                        $grandParentIProcessResponsible = $grandParentIProcess->getMasterUser();
                        if ($grandParentIProcessResponsible !== null) {
                            $recipients[] = $grandParentIProcessResponsible;
                        }
                    }
                }

                if (!$recipients) {
                    $firstAliveAdministrator = $repoU->findOneBy(['role' => 1, 'deleted' => null, 'orgId' => $institution->getId()]);
                    $recipients[] = $firstAliveAdministrator;
                }

                $settings = [];
                $settings['activity'] = $activity;
                $settings['requester'] = $currentUser;

                //2 - Send mail to recipients, set them as deciders
                foreach ($recipients as $recipient) {

                    $decision = new Decision;
                    $decision
                        ->setType(1)
                        ->setRequester($currentUser->getId())
                        ->setAnonymousRequest(false)
                        ->setAnonymousDecision(false)
                        ->setDecider($recipient->getId())
                        ->setOrganization($institution)
                        ->setActivity($activity);
                    $decision->setCreatedBy($currentUser->getId());
                    $this->em->persist($decision);
                }

                $this->em->persist($activity);

                $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'request']);

            }


            (count($IProcessStages) !== 0) ?
                $activity->setInstitutionProcess($baseElement)->setProcess($baseElement->getProcess()) :
                $activity->setProcess($baseElement);

            $pStages = $baseElement->getStages();
            $accessLinks = [];

            foreach ($pStages as $pStage) {
                $stage = new Stage;

                if (!$pStage->isDefiniteDates()) {

                    $clonedSD = clone $startdate;
                    $pStageFreq = $pStage->getDFrequency();
                    if ($pStageFreq !== 'BD') {
                        $dateIntervalPrefix = ($pStageFreq === 'm' || $pStageFreq === 'H') ? 'PT' : 'P';
                        $enddate = $clonedSD->add(new DateInterval($dateIntervalPrefix . $pStage->getDPeriod() . $pStageFreq));
                    } else {
                        $enddate = $clonedSD->modify("+{$pStage->getPeriod()}weekdays");
                    }
                    $gStartdate = $clonedSD;
                    $gEnddate = $clonedSD->add(new DateInterval('P15D'));

                } else {
                    $startdate = $pStage->getStartdate();
                    $enddate = $pStage->getEnddate();
                    $gStartdate = $pStage->getGStartdate();
                    $gEnddate = $pStage->getGEnddate();
                }

                $accessLink = mt_rand(100000, 999999);

                $stage
                    ->setName($pStage->getName())
                    ->setMasterUserId($pStage->getMasterUserId())
                    ->setVisibility($pStage->getVisibility())
                    ->setAccessLink($accessLink)
                    ->setWeight(1)
                    ->setStartdate($startdate)
                    ->setEnddate($enddate)
                    ->setGstartdate($gStartdate)
                    ->setGenddate($gEnddate)
                    ->setMode(1)
                    ->setCreatedBy($currentUser->getId());


                if ($pStage->getVisibility() === 2) {
                    $accessLinks[] = $accessLink;
                }
                $pCriteria = $pStage->getCriteria();

                if (count($pCriteria) !== 0) {
                    foreach ($pCriteria as $pCriterion) {
                        $criterion = new Criterion;

                        $criterion->setCName($pCriterion->getCName())
                            ->setType($pCriterion->getType())
                            ->setWeight($pCriterion->getWeight())
                            ->setLowerbound($pCriterion->getLowerbound())
                            ->setUpperbound($pCriterion->getUpperbound())
                            ->setStep($pCriterion->getStep())
                            ->setForceCommentCompare($pCriterion->isForceCommentCompare())
                            ->setForceCommentSign($pCriterion->getForceCommentSign())
                            ->setForceCommentValue($pCriterion->getForceCommentValue());

                        $stage->addCriterion($criterion);

                        $pParticipations = count($IProcessStages) != 0 ? $pCriterion->getParticipants() : null;
                        if (count($IProcessStages) != 0 && count($pParticipations) != 0) {
                            foreach ($pParticipations as $pParticipation) {
                                $participation = new Participation;
                                $participation->setLeader($pParticipation->isLeader())
                                    ->setMWeight($pParticipation->getMWeight())
                                    ->setPrecomment($pParticipation->getPrecomment())
                                    ->setTeam($pParticipation->getTeam())
                                    ->setUsrId($pParticipation->getUsrId())
                                    ->setType($pParticipation->getType());
                                $stage->addParticipant($participation);
                                $criterion->addParticipant($participation);
                            }

                        } else {
                            $synthParticipation = new Participation;
                            $institutionSynthUser = $repoU->findOneBy(['firstname' => 'ZZ', 'lastname' => 'ZZ', 'orgId' => $institution->getId()]);

                            $synthParticipation->setLeader(true)
                                ->setTeam(null)
                                ->setUsrId($institutionSynthUser->getId())
                                ->setType(-1);
                            $stage->addParticipant($synthParticipation);
                            $criterion->addParticipant($synthParticipation);
                        }

                        // If comes from an external request, we create external participation
                        if (!$fromInternal) {
                            $userParticipation = new Participation;
                            $userParticipation->setLeader(false)
                                ->setUsrId($currentUser->getId())
                                ->setType(0);
                            $stage->addParticipant($userParticipation);
                            $criterion->addParticipant($userParticipation);
                        }
                    }
                    $stage->addCriterion($criterion);
                }
                $activity->addStage($stage);
                $this->em->persist($activity);

                // Sending participants mails if necessary
                if ($informingMail) {
                    if (count($stage->getCriteria()) > 0) {

                        // Parameter for subject mail title
                        if (count($institutionProcess->getStages()) > 1) {
                            $mailSettings['stage'] = $stage;
                        } else {
                            $mailSettings['activity'] = $activity;
                        }

                        /** @var Participation[] */
                        $uniqueParticipations = $stage->getUniqueParticipations();
                        if (count($uniqueParticipations) > 0) {
                            foreach ($uniqueParticipations as $uniqueParticipation) {
                                if (count($pParticipations) !== 0 || (count($pParticipations) === 0 && $uniqueParticipation->getUsrId() != $synthParticipation->getUsrId())) {
                                    $recipients[] = $uniqueParticipation->getDirectUser();
                                    $uniqueParticipation->setIsMailed(true);
                                    $this->em->persist($uniqueParticipation);
                                }
                            }

                            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $mailSettings, 'actionType' => 'activityParticipation']);
                        }
                    }
                }
            }

        $this->em->flush();
        return new JsonResponse(['message' => 'success', 'aid' => $activity->getId(), 'ali' => $accessLinks],200);
        }
        return $this->redirectToRoute("activityInitialisation", ['entity' => 'activity', 'inpId' => $inpId, 'actName' => $actName]);
    }



    // Reset pwd

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/rpassword", name="resetPassword")
     */
    public function resetPwdAction(Request $request)
    {

        
        $entityManager = $this->em;
        $repository = $entityManager->getRepository(User::class);
        $user = $repository->findOneByEmail($_POST['email']);

        if($user){
            $token = md5(rand());
            $user->setToken($token);
            $user->setPassword('');
            $entityManager->persist($user);
            $entityManager->flush();
            $settings['token'] = $token;

            $recipients = [];
            $recipients[] = $user;
            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'passwordModify']);

        }
        return new JsonResponse(['message' => 'success'],200);
    }

    // Modify pwd

    /**
     * @param Request $request
     * @param Application $app
     * @param $token
     * @return mixed
     * @Route("/password/modify/{token}", name="modifyPassword")
     */
    public function modifyPwdAction(Request $request, $token)
    {

        $entityManager = $this->em;
        $repository = $entityManager->getRepository(User::class) ;
        $user = $repository->findOneByToken($token);
        if(!$user){
            return $this->render('user_no_token.html.twig');
        }
        
        $pwdForm = $this->createForm(PasswordDefinitionForm::class, $user, ['standalone' => true]) ;
        $pwdForm->handleRequest($request);

        if($pwdForm->isSubmitted() && $pwdForm->isValid()){
            $password = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setToken(null);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('password_modify.html.twig',
            [
                'firstname' => $user->getFirstName(),
                'form' => $pwdForm->createView(),
                'token' => $token,
                'request' => $request
            ]
        );
    }

    // Display all activities for current user
    public function getAllUserActivitiesAction(Request $request)
    {

        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        $this->em = $this->getEntityManager();
        $repoA = $this->em->getRepository(Activity::class);
        $repoP = $this->em->getRepository(Participation::class);
        $repoO = $this->em->getRepository(Organization::class);
        $repoDec = $this->em->getRepository(Decision::class);
        $role = $currentUser->getRole();
        $currentUsrId = $currentUser->getId();
        $organization = $currentUser->getOrganization();

        $userArchivingPeriod = $currentUser->getActivitiesArchivingNbDays();


        // Add activities where current user is either is a leader, or at least a participant;

        $orgActivities = $repoA->findBy(['organization'=> $organization],['status' => 'ASC']);

        // We get all user info and visibility options :
        // * we need to check access to results with the integer option value

        $existingAccessAndResultsViewOption = null;
        $statusAccess = null;
        $accessAndResultsViewOptions = $organization->getOptions()->filter(function(OrganizationUserOption $option) use($currentUser){return $option->getOName()->getName() == 'activitiesAccessAndResultsView' && ($option->getRole() == $currentUser->getRole() || $option->getUser() == $currentUser);});

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
                $departmentUsers = $this->em->getRepository(Department::class)->find($currentUser->getDptId())->getUsers();
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
            $subordinates = $currentUser->getSubordinatesRecursive();
            /** @var Participation[] */
            $subordinatesParticipations = $repoP->findBy([ 'usrId' => $subordinates, 'type' => [ -1, 1 ] ]);
            $activitiesWithSubordinates_IDs = array_map(function (Participation $p) {
                return $p->getActivity()->getId();
            }, $subordinatesParticipations);

            foreach($orgActivities as $orgActivity){
                if (
                    $orgActivity->getStatus() == -2 && in_array($orgActivity->getMasterUserId(), $checkingIds) ||
                    in_array($orgActivity->getId(), $activitiesWithSubordinates_IDs)
                ){
                    $userActivities->add($orgActivity);
                }

                // 3/ Get all activities in which current user is participating

                if(!$orgActivity->getArchived()){

                    // Remove created non-requested, -discarded and -cancelled activity(ies) which have unsaved stage dates (possible if user skips activity creation)
                    /*
                    if($orgActivity->getStatus() != -4 && $orgActivity->getStatus() != -3 && $orgActivity->getStatus() != -2){
                        $firstStage = $orgActivity->getStages()->first();
                        if($firstStage->getStartdate() == null && $firstStage->getEnddate() == null && $firstStage->getGStartdate() == null && $firstStage->getGEnddate() == null) {
                            $organization->removeActivity($orgActivity);
                            $entityManager->persist($organization);
                        }
                    }*/

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
                        } elseif(in_array($orgStage->getMasterUserId(), $checkingIds)){
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
            //Get activities where user is participating as external user
            $externalActivities = $currentUser->getExternalActivities();
            $userActivities = new ArrayCollection((array)$userActivities->toArray() + $externalActivities->toArray());

        }




        $nbActivitiesCategories = 0;

        $cancelledActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", -5)));
        $nbActivitiesCategories = (count($cancelledActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
        $discardedActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", -4)));
        $nbActivitiesCategories = (count($discardedActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
        $requestedActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", -3)));
        $nbActivitiesCategories = (count($requestedActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
        $attributedActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", -2)));
        $nbActivitiesCategories = (count($attributedActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
        $incompleteActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", -1)));
        $nbActivitiesCategories = (count($incompleteActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
        $futureActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 0)));
        $nbActivitiesCategories = (count($futureActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
        $currentActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 1))->orderBy(['gEnddate' => Criteria::ASC]));
        /*
        $iterator = $currentActivities->getIterator();

        $iterator->uasort(function ($first, $second) {
            return (int) $first->getGEnddate() > (int) $second->getGEnddate() ? 1 : -1;
        });
        $currentActivities = $iterator;*/


        //$currentActivities = $currentActivities->matching(Criteria::create()));
        /*
        foreach($requestedActivities as $requestedActivity){
            print_r(count($repoDec->findOneBy(['decider' => $currentUsrId, 'activity' => $requestedActivity])));
        }
        die;
        */

        $iterator = $requestedActivities->getIterator();

        $iterator->uasort(function ($a, $b) use ($repoDec, $checkingIds) {
            foreach($checkingIds as $checkingId){
                return ($repoDec->findOneBy(['decider' => $checkingId, 'activity' => $a]) != null && $repoDec->findOneBy(['decider' => $checkingId, 'activity' => $b]) == null) ? -1 : 1;
            }
            //return ($a->getId() > $b->getId()) ? -1 : 1;
        });
        $requestedActivities = new ArrayCollection(iterator_to_array($iterator));

        $currActGEnddates = [];
        foreach ($currentActivities as $currentActivity){
            $currActGEnddates[] = $currentActivity->getGEnddate();
        }

        $nbActivitiesCategories = (count($currentActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
        $completedActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 2))->orderBy(['gEnddate' => Criteria::DESC]));
        $releasedActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 3))->orderBy(['gEnddate' => Criteria::DESC]));
        $nbActivitiesCategories = (count($completedActivities) + count($releasedActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
        $archivedActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 4)));

        

        $delegateActivityForm = $this->createForm(DelegateActivityForm::class, null, ['standalone' => true, 'app' => $app]) ;
        $delegateActivityForm->handleRequest($request);
        $requestActivityForm = $this->createForm(RequestActivityForm::class, null, ['standalone' => true, 'app' => $app]) ;
        $requestActivityForm->handleRequest($request);
        $validateRequestForm = $this->createForm(DelegateActivityForm::class, null,  ['app' => $app, 'standalone' => true, 'request' => true]);
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

        return $this->render('activity_list.html.twig',
            [
                'organization' => $organization,
                'user_activities' => $userActivities,
                'request' => $request,
                'delegateForm' => $delegateActivityForm->createView(),
                'validateRequestForm' => $validateRequestForm->createView(),
                'requestForm' => $requestActivityForm->createView(),
                'orgMode' => false,
                'cancelledActivities' => $cancelledActivities,
                'discardedActivities' => $discardedActivities,
                'requestedActivities' => $requestedActivities,
                'attributedActivities' => $attributedActivities,
                'incompleteActivities' => $incompleteActivities,
                'futureActivities' => $futureActivities,
                'currentActivities' => $currentActivities,
                'completedActivities' => $completedActivities,
                'releasedActivities' => $releasedActivities,
                'archivedActivities' => $userArchivedActivities,
                'nbCategories' => $nbActivitiesCategories,
                'statusAccess' => $statusAccess,
                'existingAccessAndResultsViewOption' => $existingAccessAndResultsViewOption,
                'hideResultsFromStageIds' => (!$existingAccessAndResultsViewOption || $noParticipationRestriction) ? [] : self::hideResultsFromStages($userStages),
            ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("mysettings", name="mySettings")
     */
    public function getUserSettingsAction(Request $request){


        $entityManager = $this->em;
        $currentUser = $this->user;;
        // TODO : to create
        return $this->render('user_settings.html.twig',
            [
                'settings' => true,
            ]);


    }

    // Modify user info  (ajax call)

    /**
     * @param Request $request
     * @param Application $app
     * @return JsonResponse|Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/profile/picture", name="updatePicture")
     */
    public function updatePictureAction(Request $request)
    {
        $user = self::getAuthorizedUser();
        if (!$user) {
            return new Response(null, Response::HTTP_UNAUTHORIZED);
        }

        $this->em = self::getEntityManager();
        /** @var App\FormFactoryInterface */
        
        $pictureForm = $this->createForm(AddUserPictureForm::class);
        $pictureForm->handleRequest($request);
        $userPicture = $user->getPicture();

        // Delete existing image
        if ($userPicture) {
            unlink(__DIR__ . "/../../web/lib/img/$userPicture");
        }

        $path = $_FILES['profile-pic']['name'];
        $rand = md5(rand());
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $fileName = "$rand.$ext";
        move_uploaded_file($_FILES['profile-pic']['tmp_name'], __DIR__ . "/../../web/lib/img/$fileName");
        $user->setPicture($fileName);
        $this->em->persist($user);
        $this->em->flush();
        return new JsonResponse([ 'filename' => $fileName ]);
    }

    // Delete user (ajax call)
    public function deleteUserAction(Request $request, $id){
        $manager = $app['orm.em'];
        $repository = $manager->getRepository(User::class);
        $user = $repository->find($id);

        if (!$user) {
            $message = sprintf('User %d not found', $id);
            return $app->json(['status' => 'error', 'message' => $message], 404);
        }
        $user->setDeleted(new DateTime);
        $manager->persist($user);
        //$manager->remove($user);
        $manager->flush();

        return $app->json(['status' => 'done']);
    }

    /************ CONTACT PAGE **********************************/

    public function displayContactAction(Request $request){

            return $this->render('contact.html.twig',[
                'last_username' => $app['session']->get('security.last_username'),
                'error' => $app['security.last_error']($request),
                'request' => $request,
            ]);

    }

    /************ NEWS PAGE **********************************/

    public function displayNewsAction(Request $request){

        return $this->render('news.html.twig',[
            'error' => $app['security.last_error']($request),
            'request' => $request,
        ]);

    }

    /*********** USER LOGIN AND CONTEXTUAL MENU *****************/

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("/about", name="about")
     */
    public function displayAboutPageAction(Request $request,Application $app){

            $csrf_token = $app['csrf.token_manager']->getToken('token_id');

            return $this->render('about.html.twig',
            [
                'csrf_token' => $csrf_token,
                'error' => $app['security.last_error']($request),
                'last_username' => $app['session']->get('security.last_username'),
                'request' => $request,
            ]);
    }

     public function displaySolutionPageAction(Request $request,Application $app){

            $csrf_token = $app['csrf.token_manager']->getToken('token_id');

            return $this->render('use_cases.html.twig',
            [
                'csrf_token' => $csrf_token,
                'error' => $app['security.last_error']($request),
                'last_username' => $app['session']->get('security.last_username'),
                'request' => $request,
            ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("some-use-cases", name="useCases")
     */
    public function displayUseCasesAction(Request $request,Application $app){

        $csrf_token = $app['csrf.token_manager']->getToken('token_id');

        return $this->render('use_cases.html.twig',
        [
            'csrf_token' => $csrf_token,
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('security.last_username'),
            'request' => $request,
        ]);
}

    //Save registering user

    /**
     * @param Request $request
     * @param Application $app
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/contact/{type}/{isAborted}", name="contact_us")
     * @Route("/contact", name="contact")
     * To do à la fin
     */
    public function contactAction(Request $request,Application $app){
        //Insert Grades
        $entityManager = $this->em;
        //$repoR = $entityManager->getRepository(Contact::class);
        $contact = new Contact;
        $repoO = $entityManager->getRepository(Organization::class);
        $repoU = $entityManager->getRepository(User::class);
        
        $csrf_token = $app['csrf.token_manager']->getToken('token_id');
        $contactForm = $this->createForm(ContactForm::class,$contact,['standalone' => true]);
        $contactForm->handleRequest($request);

        if($contactForm->isValid()){

            $entityManager->persist($contact);
            $entityManager->flush();

            $rootSettings = [];
            $rootSettings['email'] = $contactForm->get('mail')->getData();
            $rootSettings['fullName'] = $contactForm->get('fullName')->getData();
            $rootSettings['company'] = $contactForm->get('company')->getData();
            $rootSettings['address'] = $contactForm->get('address')->getData();
            $rootSettings['zipcode'] = $contactForm->get('zipcode')->getData();
            $rootSettings['city'] = $contactForm->get('city')->getData();
            $rootSettings['country'] = $contactForm->get('country')->getData();
            $rootSettings['message'] = $contactForm->get('message')->getData();
            $rootSettings['meetingDate'] = $contactForm->get('meetingDate')->getData();
            $rootSettings['meetingTime'] = $contactForm->get('meetingTime')->getData();

            $settings = [];
            $settings['email'] = $rootSettings['email'];
            $settings['fullName'] = $rootSettings['fullName'];
            $settings['company'] = $rootSettings['company'];
            $settings['address'] = $rootSettings['address'];
            $settings['zipcode'] =  $rootSettings['zipcode'];
            $settings['city'] = $rootSettings['city'];
            $settings['country'] = $rootSettings['country'];
            $settings['message'] = $rootSettings['message'];
            $settings['meetingDate'] = $rootSettings['meetingDate'];
            $settings['meetingTime'] = $rootSettings['meetingTime'];
            $settings['recipientUsers'] = false;
            $recipients[] = $settings['email'];
            // Send mail acknowledgment to meeting requester
            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'meetingValidation']);


            // Notify Serpico administrators
            $serpicoOrg = $repoO->findOneByCommname('Serpico');
            $serpiValidatingUsers = $repoU->findBy(['role' => 4, 'orgId' => $serpicoOrg->getId()]);
            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $serpiValidatingUsers, 'settings' => $rootSettings, 'actionType' => 'meetingValidation']);
            return new JsonResponse(['message' => 'Success'],200);
        } else {
            $errors = $this->buildErrorArray($contactForm);
            return $errors;
        }


    }

    // Display all users (when HR clicks on "users" from /settings)

    /**
     * @param Request $request
     * @param Application $app
     * @param $usrId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/user/{usrId}/finalize", name="finalizeUser")
     */
    public function finalizeUserAction(Request $request, $usrId)
    {
        $entityManager = $this->em;
        $repoO = $entityManager->getRepository(Organization::class);
        
        $user = MasterController::getAuthorizedUser($app);
        $orgId = $user->getOrgId();
        $organization = $repoO->findOneById($orgId);
        $finalizeUserForm = $this->createForm(FinalizeUserForm::class,null,['user' => $user]);
        $finalizeUserForm->handleRequest($request);
        if($finalizeUserForm->isValid()){
            $department = new Department;
            $department->setName($finalizeUserForm->get('department')->getData());
            $position = new Position;
            $position->setName($finalizeUserForm->get('position')->getData());
            $department->addPosition($position);
            $organization->addPosition($position);
            $user->setFirstname($finalizeUserForm->get('firstname')->getData());
            $user->setLastname($finalizeUserForm->get('lastname')->getData());
            $user->setPositionName(null);
            $entityManager->persist($user);
            $organization->addDepartment($department);
            $entityManager->persist($organization);
            $entityManager->flush();
            $user
                ->setPosId($position->getId())
                ->setDptId($department->getId());
            $entityManager->persist($user);
            $entityManager->flush();
            return new JsonResponse(['message' => 'Success'],200);
        } else {
            $errors = $this->buildErrorArray($finalizeUserForm);
            return $errors;
        }
    }

    //Displays the menu in relation with user role
    /**
     * @param Request $request
     * @param $orgId
     * @return JsonResponse
     * @Route("/institution/processes/{orgId}", name="getAllProcessesFromInstitution")
     */
    public function getAllProcessesFromInstitution(Request $request, $orgId): JsonResponse
    {
        $repoO = $this->em->getRepository(Organization::class);
        /** @var Organization */
        $organization = $repoO->findOneById($orgId);
        if($orgId != 0){
            $institutionProcesses = $organization->getInstitutionProcesses()->filter(static function(InstitutionProcess $p){return $p->getParent() === null;});
        } else {
            $allProcesses = new ArrayCollection($this->em->getRepository(Process::class)->findAll());
            $institutionProcesses = $allProcesses->filter(static function(Process $p){return $p->getParent() === null;});
        }
        $orgIProcesses = [];
        foreach($institutionProcesses as $institutionProcess) {
            $children = $institutionProcess->getChildren();
            if($institutionProcess->isGradable() || count($children)){
                $orgIProcess = [];
                $orgIProcess['key'] = $institutionProcess->getId();
                $orgIProcess['value'] = $institutionProcess->getName();
                $orgIProcess['disabled'] = $institutionProcess->isGradable() ? '' : 'disabled';
                $IProcessChild = [];
                foreach($institutionProcess->getChildren() as $child){
                    $subchildren = $child->getChildren();
                    if($child->isGradable() || count($subchildren)){
                        $IProcessChild['key'] = $child->getId();
                        $IProcessChild['value'] = $child->getName();
                        $IProcessChild['disabled'] = $child->isGradable() == true ? '' : 'disabled';
                        $IProcessSubChild = [];
                        foreach($child->getChildren() as $subchild){
                            $subsubchilden = $subchild->getChildren();
                            if($subchild->isGradable() || count($subsubchilden)){
                                $IProcessSubChild['key'] = $subchild->getId();
                                $IProcessSubChild['value'] = $subchild->getName();
                                $IProcessSubChild['disabled'] = $subchild->isGradable()? '' : 'disabled';
                                $IProcessChild['children'][] = $IProcessSubChild;
                            }
                        }
                        $orgIProcess['children'][] = $IProcessChild;
                    }
                }
                $orgIProcesses[] = $orgIProcess;
            }
        }
        return new JsonResponse(['processes' => $orgIProcesses],200);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/home", name="home")
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function homeAction(Request $request)
    {

        $user = $this->security->getUser();
        $repoA = $this->em->getRepository(Activity::class);
        $repoO = $this->em->getRepository(Organization::class);
        /** @var UserRepository */
        $repoU = $this->em->getRepository(User::class);
        $repoP = $this->em->getRepository(Participation::class);
        $repoR = $this->em->getRepository(Result::class);
        $repoRK = $this->em->getRepository(Ranking::class);
        $repoRT = $this->em->getRepository(RankingTeam::class);
        $repoM = $this->em->getRepository(Member::class);
        $repoC = $this->em->getRepository(Criterion::class);
        $repoCN = $this->em->getRepository(CriterionName::class);
        $organization = $user->getOrganization();
        $orgHasActiveAdmin =$repoO->hasActiveAdmin($organization);
        $orgOptions = $organization->getOptions();
        foreach($orgOptions as $orgOption){
            if($orgOption->getOName()->getName() == 'enabledUserSeeRanking'){
                $enabledUserSeeRanking = $orgOption->isOptionTrue();
            }
        }
        $pictureForm = $this->createForm(AddUserPictureForm::class);
        $pictureForm->handleRequest($request);

        $finalizeUserForm = $this->createForm(FinalizeUserForm::class,null,['user' => $user]);
        $finalizeUserForm->handleRequest($request);

        $myHotStageParticipations = new ArrayCollection;

        // As there are no date listeners, check if we need to refresh organization activity status
        $orgUsers = $repoU->findByOrganization($organization);

        $isRefreshed = false;
        foreach ($orgUsers as $orgUser) {
            $dateObject = new DateTime;
            if ($orgUser->getLastConnected() > $dateObject->sub(new DateInterval('P1D'))) {
                $isRefreshed = true;
            }
        }

        if (!$isRefreshed) {
            $repoA = $this->em->getRepository(Activity::class);
            $supposedlyFutureActivities = $repoA->findBy(['organization' => $organization, 'status' => 0]);
            foreach ($supposedlyFutureActivities as $supposedlyFutureActivity) {
                if ($supposedlyFutureActivity->getStartdate() < new DateTime) {
                    $supposedlyFutureActivity->setStatus(1);
                }
                $this->em->persist($supposedlyFutureActivity);
            }
        }

        $firstco = 0;
        if($user->getLastConnected()!=null) {
            $firstco = 1;
        }
        $user->setLastConnected(new DateTime);
        //TODO : reset le rememberme dans le twig
//        $user->setRememberMeToken($_COOKIE['REMEMBERME']);
        $this->em->persist($user);
        $this->em->flush();
        $totalParticipations = new ArrayCollection($repoP->findBy(['user' => $user], ['status' => 'ASC', 'activity' => 'ASC', 'stage' => 'ASC']));

        // We consider each graded activity as having at least one releasable criterion an nothing more
        $nbPublishedActivities = 0;
        $nbPublishedStages = 0;
        $nbGradedActivity = 0;
        $nbActivities = 0;
        $theActivity = null;
        $nbStages = 0;
        $nbCriteria = 0;
        $nbUpcomingCriteria = 0;
        $nbOngoingCriteria = 0;
        $nbResultsCriteria = 0;
        $nbPublishedCriteria = 0;
        $theStage = null;
        $myHotStages = [];
        $nbUpcomingStages = 0;
        $nbOngoingStages = 0;
        $nbResultsStages = 0;
        $nbPublishedStages = 0;
        $nbResultsActivities = 0;
        $nbUpcomingActivities = 0;
        $nbOngoingActivities = 0;
        $notOrderedHotParticipations = true;

        $nowDT = date_create('now');
        //$totalParticipations = new ArrayCollection($totalParticipations);
        $iterator = $totalParticipations->getIterator();



        $totalParticipations = iterator_to_array($iterator);

        if ($totalParticipations) {
            // Find the two hottest activities for current user
            $nowDT = date_create('now');

            // We get the first 5 nearest stage in terms of grading deadline in our results
            foreach ($totalParticipations as $participation) {
                $nbCriteria++;
                $stage = $participation->getStage();
                $activity = $stage->getActivity();

                if ($participation->getStatus() < 3) {
                    //$stageGEnddate = $participation->getStage()->getGEnddate();
                    //$stageGStartdate = $participation->getStage()->getGStartdate();
                    if (count($myHotStageParticipations) < 5) {
                        // Invert is equal to 1 if dateInterval is negative (ie genddate is in the past)
                        //if (($nowDT->diff($stageGEnddate)->invert == 0 || $nowDT->diff($stageGEnddate)->days == 0) && !in_array($participation->getStage(),$myHotStages)) {
                            if(!in_array($stage,$myHotStages) && $activity->getStatus() >= 0){
                                $myHotStages[] = $stage;
                                $myHotStageParticipations->add($participation);
                            }
                            //$participationStatus[] = ($nowDT->diff($stageGEnddate)->invert == 1) ? -1 : $participation->getStatus();
                        //}
                    }
                }

                if ($participation->getStatus() >= 3 && count($myHotStageParticipations) < 5 && !in_array($stage, $myHotStages)) {

                    if(!in_array($stage,$myHotStages)){
                        $myHotStages[] = $stage;
                        $myHotStageParticipations->add($participation);
                    }
                }

                if ($activity != $theActivity) {
                    $theActivity = $activity;
                    $nbActivities++;

                    if ($theActivity->getStatus() == 0) {
                        $nbUpcomingActivities++;
                    } else if ($theActivity->getStatus() == 1) {
                        $nbOngoingActivities++;
                    } else if ($theActivity->getStatus() >= 2) {
                        $nbResultsActivities++;
                        if ($theActivity->getStatus() == 3) {
                            $nbPublishedActivities++;
                        }
                    }
                }

                if ($stage != $theStage) {
                    $nbStages++;
                    $theStage = $stage;

                    if ($theStage->getStatus() <= 1) {
                        // Ongoing and upcoming
                        if ($theActivity->getStatus() == 1) {
                            if ($theStage->getGEnddate() >= new DateTime) {
                                $nbOngoingStages++;
                                $nbOngoingCriteria += count($theStage->getCriteria());
                            }
                        } else if ($activity->getStatus() == 0){
                            $nbUpcomingStages++;
                            $nbUpcomingCriteria += count($theStage->getCriteria());
                        }
                    } else if ($theStage->getStatus() >= 2) {
                        $nbResultsStages++;
                        $nbResultsCriteria += count($theStage->getCriteria());
                        if ($theStage->getStatus() == 3) {
                            $nbPublishedStages++;
                        }
                    }
                }

                if ($participation->getStatus() >= 3) {
                    $ungradedActivity = 0;
                    ($participation->getStatus() == 4) ? $nbPublishedCriteria++ : $nbGradedActivity++;
                }
            }

            // Sorting participations in case they were not already sorted, i.e. when there are at least 5 "real" hot stages for a participant and we do not add finished participations

            if ($notOrderedHotParticipations) {
                $iterator = $myHotStageParticipations->getIterator();
                $iterator->uasort(function ($first, $second) {
                    if ($first->getStage()->getGEnddate() == $second->getStage()->getGEnddate()) {
                        return 0;
                    }
                    return $first->getStage()->getGEnddate() > $second->getStage()->getGEnddate()
                    ? 1
                    : -1;
                });
                $myHotStageParticipations = new ArrayCollection(iterator_to_array($iterator));
            }
        }

        // If user is a external, no ranking is computed for him
        if ($user->isInternal() && $repoR->findOneBy(['type' => 1, 'user' => $user]) != null) {
            $userWPerfRanking = $repoRK->findOneBy(['dType' => 'P', 'wType' => 'W', 'usrId' => $user->getId(), 'period' => 0, 'frequency' => 'D']);
            $userWDevRatioRanking = $repoRK->findOneBy(['dType' => 'D', 'wType' => 'W', 'usrId' => $user->getId(), 'period' => 0, 'frequency' => 'D']);
        }
        //TODO trouver pourquoi çà merde au niveau des stages
//        MasterController::sendStageDeadlineMails();
//        MasterController::sendOrganizationTestingReminders();
//        MasterController::updateProgressStatus();

        $teamParticipations = [];
        $memberInclusions = $repoM->findByUser($user);
        foreach($memberInclusions as $memberInclusion){
//            dd( $memberInclusion instanceof TeamUser);
            $team = $memberInclusion->getTeam();
            $teamParticipation['name'] = $team->getName();
            $teamParticipation['id'] = $team->getId();
            $teamParticipation['nbParticipants'] = count($team->getActiveTeamUsers());
            $teamMeanPerf = $repoRT->findOneBy(['dType' => 'P', 'wType' => 'W', 'team' => $team, 'period' => 0, 'frequency' => 'D']);
            $teamMeanDist = $repoRT->findOneBy(['dType' => 'D', 'wType' => 'W', 'team' => $team, 'period' => 0, 'frequency' => 'D']);
            $teamParticipation['meanPerf'] = ($teamMeanPerf === null) ?: $teamMeanPerf->getValue();
            $teamParticipation['meanDist']= ($teamMeanDist === null) ?: $teamMeanDist->getValue();
            $teamParticipations[] = $teamParticipation;
        }
        $topCriteria=[];
        $topUsedCrits=[];
        $topValue = 0;
        $array_length = 0;
        $topCritIds = [];
        $topCriterionIds = [];

        $topCriteriaResults = $repoR->findBy(['user' => $user], ['weightedRelativeResult'=>'DESC']);
        foreach($topCriteriaResults as $topCriteriaResult){
            if ($topCriteriaResult->getCriterion() != null && $topCriteriaResult->getWeightedRelativeResult() !== null){
                $topCritNameIds = $repoC->findBy(['id' => $topCriteriaResult->getCriterion()]);
                foreach($topCritNameIds as $topCritNameId){
                    $topCritIds[]=$topCritNameId->getCName();
                    $topCriterionIds[]=$topCritNameId->getId();
                }
                $topCNameIds = array_unique($topCritIds);
                foreach($topCNameIds as $topCNameId){
                    if(!in_array($topCNameId, $topUsedCrits)){
                        $topUsedCrits[]=$topCNameId;
                        $topCritSum=0;
                        $topCritAvg = 0;
                        $array_length = 0;
                        $topCritKeys = array_keys($topCritIds,$topCNameId);
                        foreach($topCritKeys as $key=>$value){
                            $results = $repoR->findBy(['usrId'=> $user->getId(),'criterion'=>$topCriterionIds[$value]]);
                            foreach($results as $result){
                                if($result->getWeightedRelativeResult() != null){
                                    $array_length++;
                                    $topCritSum += $result->getWeightedRelativeResult();
                                }
                            }
                        }
                        $topCritAvg = $topCritSum/$array_length;
                        $topValue = round($topCritAvg * 100, 1);
                        $topCNames = $repoCN->findBy(['id' => $topCritNameId->getCName()]);
                        foreach($topCNames as $topCName){
                            $topCriteria[] = ['cName' => $topCName, 'value' => $topValue, 'vote' => $topCritSum];
                        }
                    }
                }
            }
        }

        $topPerformingCriteria = $repoU->findUserTopPerformingCriteria($user);
        $ungradedTargets = $repoU->findUserUngradedTargets($user);
        //if(!$orgHasActiveAdmin){

            $addFirstAdminFormView = !$orgHasActiveAdmin ? $this->createForm(AddFirstAdminForm::class,null)->createView() : null;

            //$addFirstAdminFormView = !$orgHasActiveAdmin ? $finalizeUserForm->createView() : null;
        //}

        $myHotStageParticipations = array_reverse($myHotStageParticipations->getValues());

        return $this->render('my_profile.html.twig', [
            'firstConnection' => $firstco,
            'orgHasActiveAdmin' => $orgHasActiveAdmin,
            'addFirstAdminForm' => $addFirstAdminFormView,
            'userData' => $user->toArray(),
            'pictureForm' => $pictureForm->createView(),
            'finalizeUserForm' => isset($finalizeUserForm) ? $finalizeUserForm->createView() : null,
            'organization' => $organization->getCommname(),
            'wRelPerfAbsRanking' => isset($userWPerfRanking) ? $userWPerfRanking->getAbsResult() : null,
            'wRelPerfRelRanking' => isset($userWPerfRanking) ? $userWPerfRanking->getRelResult() : null,
            'wDevRatioRelRanking' => isset($userWDevRatioRanking) ? $userWDevRatioRanking->getRelResult() : null,
            'wDevRatioAbsRanking' => isset($userWDevRatioRanking) ? $userWDevRatioRanking->getAbsResult() : null,
            'nbOrgUsers' => count($repoU->findBy(['organization'=> $organization, 'deleted' => null, 'internal' => 1])),
            'nbEvaluatedPerfUsers' => count($repoRK->findBy(['organization' => $organization, 'dType' => 'P', 'wType' => 'W', 'period' => 0, 'frequency' => 'D'])),
            'nbEvaluatedDistUsers' => count($repoRK->findBy(['organization' => $organization, 'dType' => 'D', 'wType' => 'W', 'period' => 0, 'frequency' => 'D'])),
            'myHotStageParticipations' => $myHotStageParticipations,
            'nbCriteria' => $nbCriteria,
            'nbOngoingCriteria' => $nbOngoingCriteria,
            'nbUpcomingCriteria' => $nbUpcomingCriteria,
            'nbResultsCriteria' => $nbResultsCriteria,
            'nbPublishedCriteria' => $nbPublishedCriteria,
            'nbStages' => $nbStages,
            'nbOngoingStages' => $nbOngoingStages,
            'nbUpcomingStages' => $nbUpcomingStages,
            'nbResultsStages' => $nbResultsStages,
            'nbPublishedStages' => $nbPublishedStages,
            'nbActivities' => $nbActivities,
            'nbOngoingActivities' => $nbOngoingActivities,
            'nbUpcomingActivities' => $nbUpcomingActivities,
            'nbResultsActivities' => $nbResultsActivities,
            'nbPublishedActivities' => $nbPublishedActivities,
            'error' => null,
            //TODO get les errors (et check le coup du last user, et gérer les crsf token
            'last_username' => $user->getUsername(),
            'token' => null,
            'teamParticipations' => $teamParticipations,
            'topCriteria' => $topCriteria,
            'topPerformingCriteria' => $topPerformingCriteria,
            'ungradedTargets' => $ungradedTargets,
            'enabledUserSeeRanking' => $enabledUserSeeRanking ?? false,
            'userpicture' => 'lib/img/' . ($user->getPicture() ?: 'no-picture.png'),
        ]);
    }

    /*********** ADDITION, MODIFICATION AND DELETION *****************/

    /**
     * @param Application $app
     * @return mixed
     * @Route("/user/display/profile", name="displayProfile")
     */
    public function displayProfile(Application $app)
    {
        $entityManager = $this->getEntityManager();
        $repoP = $entityManager->getRepository(Participation::class);
        $repoO = $entityManager->getRepository(Organization::class);
        $user = self::getAuthorizedUser();
        $organization = $repoO->find($user->getOrgId());
        $totalParticipations = $repoP->findBy([ 'usrId' => $user->getId(), 'activity' => 'ASC' ]);

        // We consider each graded activity as having at least one releasable criterion an nothing more
        $totalGradedActivity = 0;
        $totalUngradedActivity = 0;

        if ($totalParticipations) {
            $ungradedActivity = 1;
            $skipParticipations = 0;
            $actId = $totalParticipations[0]->getActId();

            foreach ($totalParticipations as $participation) {
                if ($participation->getActId() != $actId) {
                    $skipParticipations = 0;
                    if ($ungradedActivity == 1) {
                        ++$totalUngradedActivity;
                    }
                    $actId = $participation->getId();
                } else {
                    if ($participation->getStatus() >= 3 and $skipParticipations == 0) {
                        $ungradedActivity = 0;
                        $totalGradedActivity++;
                        $skipParticipations = 1;
                    }
                }
            }
        }

        return $this->render('my_profile.html.twig',
            [
                'organization' => $organization->getCommname(),
                'validatedActivities' => $totalGradedActivity,
                'unvalidatedActivities' => $totalUngradedActivity,
            ]
        );
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("/help", name="help")
     */
    public function helpAction(Request $request)
    {
        return $this->render('help.html.twig',
            [
                'request' => $request
            ]
        );
    }
}
