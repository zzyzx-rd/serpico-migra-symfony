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
use App\Form\AddPictureForm;
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
use App\Entity\Client;
use App\Entity\ElementUpdate;
use App\Entity\ExternalUser;
use App\Entity\InstitutionProcess;
use App\Entity\Process;
use App\Entity\UserGlobal;
use App\Entity\UserMaster;
use App\Entity\WorkerFirm;
use App\Entity\WorkerIndividual;
use App\Form\AddSignupUserForm;
use App\Form\PasswordForm;
use App\Model\UserM;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\NotificationManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Stripe\Customer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends MasterController
{
    /*********** ADDITION, MODIFICATION, DELETION AND DISPLAY OF USERS *****************/

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
                case 'activityParticipationFollowing':
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
     * @Route("/password/update/{token}", name="updatePassword", methods={"GET"})
     */
    public function updatePasswordAction(Request $request, $token)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(User::class);
        /** @var User */
        $user = $repository->findOneByToken($token);
        $pwdForm = $this->createForm(PasswordDefinitionForm::class, $user, ['standalone' => true]);
        $pwdForm->handleRequest($request);

        if (!$user) {
            return $this->render('user_no_token.html.twig');
        } else {

            return $this->render('password_update.html.twig',
                [
                    'firstname' => $user->getFirstName(),
                    'hasConnectedAlready' => $user->getLastConnected(),
                    'hasNotSetupOrg' => !$user->getLastConnected() && $user->getOrganization()->getType() == 'C',
                    'form' => $pwdForm->createView(),
                    'token' => $token,
                    'request' => $request,
                ]);
        }
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/signup", name="signup")
     */
    public function signupAction(Request $request)
    {

        $em = $this->em;
        /** @var User */
        $user = new User;
        $signupForm = $this->createForm(AddSignupUserForm::class, $user);
        $signupForm->handleRequest($request);

        if ($signupForm->isSubmitted()) {
            if ($signupForm->isValid()) {
                $username = $user->getUsername();
                $email = $signupForm->get('email')->getData();
                $nameElmts = explode(" ", $username, 2); 
                $firstname = trim($nameElmts[0]);
                $lastname = trim($nameElmts[1]);
                $token = md5(rand());
                $user->setToken($token)
                    ->setFirstname($firstname)
                    ->setLastname($lastname)
                    ->setRole(USER::ROLE_ADMIN);

                $organization = new Organization();
                $organization->setCommname($username)
                    ->setType('C')
                    ->addUser($user);

                $userGlobal = new UserGlobal();
                $userGlobal->setUsername($username)
                    ->addUserAccount($user);
                $em->persist($userGlobal);
                
                // Creating user citizen organization
                $this->forward('App\Controller\OrganizationController::updateOrgFeatures', ['organization' => $organization, 'existingOrg' => false, 'addedAsClient' => false]);
                $em->persist($organization);
                $em->flush();

                $orgId = $organization->getId();
                $individualName = strtolower(implode("-",explode(" ",$username)));
                mkdir(dirname(dirname(__DIR__)) . "/public/lib/idocs/{$orgId}-{$individualName}");
             
                $this->forward('App\Controller\SettingsController::addDefaultSubscriptor',['organization' => $organization, 'email' => $email]);
                $em->persist($organization);
                $em->flush();

                //Sending mail to DealDrive root users 
                $repoU = $em->getRepository(User::class);
                $recipients = $repoU->findBy(['role' => USER::ROLE_ROOT]);

                $settings['fullname'] = $username;
                $settings['userEmail'] = $user->getEmail();
                
                $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'userSignupInfo']);
                
                //Sending mail acknowledgment receipt to the requester
                $recipients          = [];
                $recipients[]        = $user;
                $settings            = [];
                $settings['token'] = $user->getToken();

                $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'subscriptionConfirmation']);
                
                setcookie('signup', 'y');
                return $this->redirectToRoute('home_welcome');

            }
        }

        return $this->render('signup.html.twig',
            [
                'form' => $signupForm->createView(),
            ]);

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
            $element->setOrganization($organization)->setApprovable(true)->setInitiator($currentUser);
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

    // Save pwd

    /**
     * @param Request $request
     * @param Application $app
     * @param $token
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * * @Route("/password/update/{token}", name="savePassword", methods={"POST"})
     */
    public function savePassword(Request $request, $token)
    {
        $priorValidationCheck = intval($request->get('prior_check'));
        $em = $this->em;
        $repoU = $em->getRepository(User::class);
        $user = $repoU->findOneByToken($token);
        $organization = $user->getOrganization();
        $settablePasswordUsers = $em->getRepository(User::class)->findByEmail($user->getEmail());
        $pwdForm = $this->createForm(PasswordDefinitionForm::class, $user, ['standalone' => true]);
        $pwdForm->handleRequest($request);

        if ($pwdForm->isValid()) 
        {   
            $needToSetOrg = !$user->getLastConnected() && $organization->getType() == 'C';
            
            if($needToSetOrg && $priorValidationCheck){
                return new JsonResponse(['id' => $user->getId(), 'needToSetOrg' => true]);
            } else {

                $password = $this->encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password)
                    ->setToken(null);
                $em->persist($user);

                if(!$user->getSubscriptionId()){
                    // We create a 21-day premium monthly subscription for user
                    
                    $this->forward('App\Controller\SettingsController::addDefaultSubscriptor', ['usrId' => $user->getId()]);
                    /*
                    // We create a 21-day premium monthly subscription for personal account
                    $stripe = $this->stripe;
                    // 1 - Create customer ID
                    $customer = Customer::create([
                        'email' => $user->getEmail()
                    ]);
                    $cId = $customer->id;
                    $organization->setStripeCusId($cId);
                    $em->persist($organization);
                    
                    $pId = strpos("dealdrive.app", $_SERVER["HTTP_HOST"]) === false ? 'price_1INPSqLU0XoF52vKRN5T8i9Y' : 'price_1HoAmcLU0XoF52vKHDggsDpq';
                    // 2 - Create subscription
                    $subscription = $stripe->subscriptions->create([
                        'customer' => $cId,
                        'items' => [
                            ['price' => $pId],
                        ],
                        'trial_period_days' => 21,
                    ]);
                    $user->setSubscriptionId($subscription->id);
                    $em->persist($user);
                    $em->flush();
                    */
                }        
    
                if(sizeof($settablePasswordUsers) > 1){
                    foreach($settablePasswordUsers as $settablePasswordUser){
                        if($settablePasswordUser != $user){
                            $user->setPassword($password)
                            ->setToken(null);
                            $em->persist($settablePasswordUser);
                        }
                    }
                }

                $em->flush();
    
                // In the case person has been added in an organization, and did not sign up, we create his/her individual and private account
                if(!$needToSetOrg){
                    if(sizeof($settablePasswordUsers) == 1){
    
                        $individualUser = clone $user;
                        $individualUser
                            ->setRole(USER::ROLE_ADMIN)
                            ->setLastConnected(null)
                            ->setAltEmail(null)
                            ->setPosition(null)
                            ->setDepartment(null)
                            ->setTitle(null)
                            ->setSuperior(null)
                            ->setUserGlobal($user->getUserGlobal())
                            ->setInserted(new DateTime);
        
                        $organization = new Organization();
                        $organization->setCommname($user->getUsername())
                            ->setType('C')
                            ->addUser($individualUser);
                        
                        // Updating new citizen org
                        $this->forward('App\Controller\OrganizationController::updateOrgFeatures', ['organization' => $organization, 'existingOrg' => false, 'addedAsClient' => false]);
        
                        $em->persist($organization);
                        $em->flush();
    
                        $orgId = $organization->getId();
                        $individualName = strtolower(implode("-",explode(" ",$user->getUsername())));
                        mkdir(dirname(dirname(__DIR__)) . "/public/lib/idocs/{$orgId}-{$individualName}");
    
                    
                    }
    
                    $this->guardHandler->authenticateUserAndHandleSuccess(
                        $user,
                        $request,
                        $this->authenticator,
                        'main'
                    );
                }
                return new JsonResponse(['id' => $user->getId(), 'needToSetOrg' => $needToSetOrg], 200);
            }

        } else {
            $errors = $this->buildErrorArray($pwdForm);
            return $errors;
        }
    }

    /**
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @Route("/user/account/add",name="addAccount")
     */
    public function addAccount(Request $request, TranslatorInterface $translator){
        $em = $this->em;
        $wfiId = $_POST['wid'];

        if(strpos($request->headers->get('referer'), 'password/update') !== false){
            $assoc = $request->get('assoc');
            $usrId = $request->get('id');
            $currentUser = $em->getRepository(User::class)->find($usrId);
        } else {
            $currentUser = $this->user;
            if($wfiId && in_array($wfiId, $currentUser->getUserGlobal()->getUserAccounts()->map(fn(User $u) => $u->getOrganization()->getWorkerFirm())->getValues()) !== false){
                $errorMsg = $translator->trans('profile.duplicate_account');
                return new JsonResponse(['msg' => $errorMsg], 500);
            }
            $assoc = true;
        }

        if($assoc){

            $firmName = $_POST['firmname'];
            $currentOrgUser = clone $currentUser;
            $currentOrgUser->setToken(null)
                ->setInserted(new DateTime)
                ->setUserGlobal($currentUser->getUserGlobal());
            $em->persist($currentOrgUser);
            if(!$wfiId){
                $workerFirm = new WorkerFirm;
                $workerFirm->setCommonName($firmName)
                    ->setName($firmName)
                    ->setInitiator($currentOrgUser);
                $em->persist($workerFirm);
                $em->flush();
            } else {
                $workerFirm = $em->getRepository(WorkerFirm::class)->find($wfiId);
            }
    
            $organization = $workerFirm->getOrganizations()->filter(fn(Organization $o) => $o->getCommname() == $firmName)->first();
            
            $noPriorOrganization = !$organization;
            if($noPriorOrganization){
                $organization = new Organization;
                $now = new DateTime();
                $organization
                    ->setCommname($firmName)
                    ->setType('F')
                    ->setExpired($now->add(new DateInterval('P21D')))
                    ->setWeightType('role')
                    ->setWorkerFirm($workerFirm)
                    ->setPlan(ORGANIZATION::PLAN_PREMIUM)
                    ->setInitiator($currentUser);
                $em->persist($organization);
                $em->persist($workerFirm);
                $this->forward('App\Controller\OrganizationController::updateOrgFeatures', ['organization' => $organization, 'existingOrg' => false, 'createSynthUser' => true, 'addedAsClient' => false]);
                $currentOrgUser->setRole(User::ROLE_ADMIN);
                $organization->addUser($currentOrgUser);
                $workerFirm->addOrganization($organization);
                $em->persist($workerFirm);
            } else {
                $currentOrgUser->setRole(!$organization->hasActiveAdmin() ? User::ROLE_ADMIN : USER::ROLE_AM);
                $organization->addUser($currentOrgUser);
                $em->persist($organization);
            }
            
            $em->flush();
            if($noPriorOrganization){
                $wfiId = $organization->getId();
                $orgName = strtolower(implode("-", explode(" ", $firmName)));
                mkdir(dirname(dirname(__DIR__)) . "/public/lib/cdocs/{$wfiId}-{$orgName}");
            }
            $this->forward('App\Controller\SettingsController::addDefaultSubscriptor',['organization' => $organization, 'email' => $currentUser->getEmail()]);
            $em->refresh($currentOrgUser);
            $em->persist($organization);
            $em->flush();
        }



        $this->guardHandler->authenticateUserAndHandleSuccess(
            $assoc ? $currentOrgUser : $currentUser,
            $request,
            $this->authenticator,
            'main' // firewall name in security.yaml
        );

        return new JsonResponse(['msg' => 'success'], 200);
    }


    // TODO : renforcer security
    /**
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @Route("/user/account/remove",name="deleteAccount")
     */
    public function removeAccount(Request $request){
        $em = $this->em;
        $usrId = $request->get('id');
        $orgId = $request->get('oid');

        $user = $em->getRepository(User::class)->find($usrId);
        $userGlobal = $user->getUserGlobal();
        $connectedUserAfterRemoval = $userGlobal->getUserAccounts()->filter(fn(User $u) => $u->getOrganization()->getId() != $orgId)->first();

        $this->guardHandler->authenticateUserAndHandleSuccess(
            $connectedUserAfterRemoval,
            $request,
            $this->authenticator,
            'main'
        );

        $organization = $user->getOrganization();
        $organization->removeUser($user);
        $em->persist($organization);
        $em->flush();
        return new JsonResponse();
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
        
        //$workerIndividual = $currentUser->getWorkerIndividual();
        //$workerIndividualForm = $this->createForm(UpdateWorkerIndividualForm::class, $workerIndividual, ['standalone' => true]);
        $workerIndividualForm = $this->createForm(UpdateWorkerIndividualForm::class, $currentUser, ['standalone' => true, 'currentUser' => $currentUser]);
        $workerIndividualForm->handleRequest($request);
        $passwordForm = $this->createForm(PasswordForm::class, $currentUser, ['standalone' => true, 'mode' => 'modification']);
        $passwordForm->handleRequest($request);
        $pictureForm = $this->createForm(AddPictureForm::class);
        $pictureForm->handleRequest($request);

        if ($workerIndividualForm->isSubmitted() && $workerIndividualForm->isValid()) {
            $repoWF = $entityManager->getRepository(WorkerFirm::class);
            /*
            foreach($workerIndividualForm->get('experiences') as $key => $experienceForm){
                $experience = $experienceForm->getData();
                $experience->setFirm($repoWF->find((int) $experienceForm->get('firm')->getData()));
                $entityManager->persist($experience);
            }*/
            $entityManager->persist($currentUser);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('worker_individual_data.html.twig',
        [
            'form' => $workerIndividualForm->createView(),
            'pictureForm' => $pictureForm->createView(),
            'passwordModificationForm' => $passwordForm->createView()
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
            $masterUser = $entity === 'activity' ? $repoU->find($element->getMasterUser()) : ($element->getInitiator());
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
        $users = $repository->findByEmail($_POST['email']);
        $isSentMail = false;
        $token = md5(rand());

        if(sizeof($users) > 0){
            foreach($users as $user){

                $user->setToken($token);
                $user->setPassword(null);
                $entityManager->persist($user);
                $entityManager->flush();
                if(!$isSentMail){
                    $settings['token'] = $token;
                    $recipients = [];
                    $recipients[] = $user;
                    $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'passwordModify']);
                    $isSentMail = true;
                }
            }
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
     * @Route("/{element}/picture/update", name="updatePicture", methods={"POST"})
     */
    public function updatePictureAction(Request $request, string $element, FileUploader $fileUploader)
    {

        $folder = $element == 'user' ? 'user' : 'org';
        $currentUser = $this->user;
        $organization = $this->org;
        $fileUploader->setTargetDirectory("../public/lib/img/$folder");
        $picture = $element == 'user' ? $currentUser->getPicture() : $organization->getLogo();
        $em = $this->em;

        if ($element == 'user' && !$currentUser || $element == 'organization' && $currentUser->getRole() > USER::ROLE_SUPER_ADMIN/*&& $currentUser->getMasterings()->filter(fn(UserMaster $um) => $um->getOrganization() == $organization)->count() == 0*/) {
            return new Response(null, Response::HTTP_UNAUTHORIZED);
        }

        if($picture){
            unlink(dirname(dirname(__DIR__)) . "/public/lib/img/$folder/$picture");
        }

        $pictureFile = new UploadedFile($_FILES['profile-pic']['tmp_name'], $_FILES['profile-pic']['name']);
        $pictureFileInfo = $fileUploader->upload($pictureFile);
        $element == 'user' ? $currentUser->setPicture($pictureFileInfo['name']) : $organization->setLogo($pictureFileInfo['name']);
        $em->persist($element == 'user' ? $currentUser : $organization);
        $em->flush();
        return new JsonResponse(['filename' => $pictureFileInfo['name']]);
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
     * @Route("/home", name="home", methods={"GET"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function homeAction(Request $request)
    {

        $user = $this->user;
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
        $orgHasActiveAdmin = $repoO->hasActiveAdmin($organization);
        $orgOptions = $organization->getOptions();
        foreach($orgOptions as $orgOption){
            if($orgOption->getOName()->getName() == 'enabledUserSeeRanking'){
                $enabledUserSeeRanking = $orgOption->isOptionTrue();
            }
        }

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
        $totalHotStageParticipations = 5;

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
                    if (count($myHotStageParticipations) < $totalHotStageParticipations) {
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
                            if ($theStage->getEnddate() >= new DateTime) {
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
                    if ($first->getStage()->getEnddate() == $second->getStage()->getEnddate()) {
                        return 0;
                    }
                    return $first->getStage()->getEnddate() > $second->getStage()->getEnddate()
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

        if(sizeof($myHotStageParticipations) < 5){
            
        }

        return $this->render('my_profile.html.twig', [
            'firstConnection' => $firstco,
            'orgHasActiveAdmin' => $orgHasActiveAdmin,
            'addFirstAdminForm' => $addFirstAdminFormView,
            'userData' => $user->toArray(),
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

    /**
     * @param Request $request
     * @return mixed
     * @Route("/user/self/update", name="saveUserSelfModifications")
     */
    public function saveUserSelfModification(Request $request){
        $currentUser = $this->user;
        $em = $this->em;
        $currEmail =  $currentUser->getEmail();
        $updateUserForm = $this->createForm(UpdateWorkerIndividualForm::class, $currentUser, ['standalone' => true, 'currentUser' => $currentUser]);
        $updateUserForm->handleRequest($request);
        if($updateUserForm->isSubmitted() && $updateUserForm->isValid()){
            $email = $updateUserForm->get('email')->getData();
            if($email != $currEmail){
                $token = md5(rand());
                $currentUser
                    ->setAltEmail($email)
                    ->setToken($token);
                $settings = [];
                $settings['token'] = $token;
                $recipients = [];
                $recipients[] = $currentUser;
                $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'emailModify']);
            }
            $em->persist($currentUser);
            $em->flush();
            return new JsonResponse();
        } else {
            $errors = $this->buildErrorArray($updateUserForm);
            return $errors;
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @Route("/user/email/confirm/{token}", name="confirmEmailModif")
     */
    public function confirmEmailModif(string $token, Request $request){
        $em = $this->em;
        $isValidToken = $em->getRepository(User::class)->findOneByToken($token) != null;
        $params['isValidToken'] = $isValidToken;
        
        if($isValidToken){
            
            $currentUser = $em->getRepository(User::class)->findOneByToken($token);
            $noUserToken = false;
            $linkHasExpired = null;
            $passwordForm = $this->createForm(PasswordForm::class, $currentUser, ['standalone' => true]);
            $passwordForm->handleRequest($request);
            $params['passwordForm'] = $passwordForm->createView();
            $now = new DateTime();
            $lastChangeInvitation = $em->getRepository(Mail::class)->findBy(['type' => 'emailModify','user' => $currentUser], ['inserted' => 'DESC'])[0];
            if($lastChangeInvitation->getInserted()->getTimestamp() - $now->getTimestamp() > 24 * 60 * 60 && $currentUser->getAltEmail() != null){
                $linkHasExpired = true;
            } else {
                $linkHasExpired = false;
            }
            $params['linkHasExpired'] = $linkHasExpired;
            $params['currentUserMail'] = $currentUser->getEmail();

            if($passwordForm->isSubmitted() && $passwordForm->isValid()){
    
                $currentUser->setEmail($currentUser->getAltEmail())
                    ->setAltEmail(null)
                    ->setToken(null);
                $em->persist($currentUser);
                $em->flush();
                return $this->redirectToRoute('myActivities');
    
            }
        }
        
        return $this->render('email_change_confirmation.html.twig', $params);
        
    }

    /**
     * @param Request $request
     * @return mixed
     * @Route("/user/password/modify", name="passwordSelfModification")
     */
    public function userPwdSelfModification(Request $request){
        $currentUser = $this->user;
        $em = $this->em;
        $passwordForm = $this->createForm(PasswordForm::class, $currentUser, ['standalone' => true, 'mode' => 'modification']);
        $passwordForm->handleRequest($request);
        if($passwordForm->isValid()){
            $newPassword = $this->encoder->encodePassword($currentUser, $passwordForm->get('newPassword')->getData());
            $currentUser->setPassword($newPassword);
            $recipients = [];
            $settings = [];
            $recipients[] = $currentUser;
            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'passwordChangeConfirmation']);
            $em->persist($currentUser);
            $em->flush();
            $em->refresh($currentUser);
            return new JsonResponse();
        } else {
            $errors = $this->buildErrorArray($passwordForm);
            $em->refresh($currentUser);
            return $errors;
        }    
    }

    /**
     * Function which retrieves all accounts/organizations associated with user email
     * @param Request $request
     * @return mixed
     * @Route("/user/accounts/list", name="getUserAccounts", methods={"GET"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function getAccounts(Request $request, TranslatorInterface $translator){
        $currentUser = $this->user;
        $em = $this->em;
        $allUsersWithSameEmail = new ArrayCollection($em->getRepository(User::class)->findByEmail($currentUser->getEmail()));
        
        if(sizeof($allUsersWithSameEmail) == 2){
            foreach($allUsersWithSameEmail as $user){
                if($user != $currentUser){


                    // Create subscription for private account, in case user was added by firm administrator (did not sign up)
                    if(!$user->getSubscriptionId()){
                        $this->forward('App\Controller\SettingsController::addDefaultSubscriptor', ['usrId' => $user->getId()]);
                    }

                    $this->guardHandler->authenticateUserAndHandleSuccess(
                        $user,
                        $request,
                        $this->authenticator,
                        'main'
                    );
                    $user->setLastConnected(new DateTime());
                    $em->persist($user);
                    $em->flush();
                    return new JsonResponse(['changed' => true]);
                }
            }
        }
        
        return new JsonResponse(
            $allUsersWithSameEmail->map(fn(User $u) => 
            [
                'id' => $u->getOrganization()->getId(),
                'name' => $u->getOrganization()->getType() != 'C' ? $translator->trans('profile.my_firm_account',['accountName' => $u->getOrganization()->getCommname()]) : $translator->trans('profile.my_personal_account'),
            ])->getValues()
        );
    }

    /**
     * @param Request $request
     * @return mixed
     * @Route("/user/accounts/change", name="changeUserAccount", methods={"POST"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function changeAccount(Request $request){
        $currentUser = $this->user;
        $em = $this->em;
        $organization = $em->getRepository(Organization::class)->find($request->get('id'));
        /** @var User */
        $user = $em->getRepository(User::class)->findOneBy(['email' => $currentUser->getEmail(), 'organization' => $organization]);

        if(!$user->getSubscriptionId()){
            $this->forward('App\Controller\SettingsController::addDefaultSubscriptor', ['usrId' => $user->getId()]);
        }

        $this->guardHandler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $this->authenticator,
            'main'
        );

        $user->setLastConnected(new DateTime());
        $em->persist($user);
        $em->flush();
        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @return mixed
     * @Route("/user/invitations/stage/link", name="invitationPositioning", methods={"POST"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function invitationPositioningAction(Request $request, TranslatorInterface $translator, NotificationManager $notificationManager){
        $stgId = $request->get('id');
        $em = $this->em;
        // If user has no multiple accounts, request param does not exists; considered user is then logged user
        if($request->get('uid')){
            $usrId = $request->get('uid');
            /** @var User */
            $user = $em->getRepository(User::class)->find($usrId);
        } else {
            $user = $this->user;
        }
        $positioningValue = $request->get('v');
        /** @var Stage */
        $stage = $em->getRepository(Stage::class)->find($stgId);
        
        // User wants to either participate (1), follow (0) or discard stage joining proposal (-1)
        
        if($positioningValue != -1){
            // We'll give an update to stage masters
            $notifiedUsers = $stage->getLeaders();
        }

        $waitingApproval = false;

        // Corresponds to participation
        if($positioningValue == 1){
 
            if($stage->getJoinableStatus() == STAGE::JOINABLE_FORBIDDEN){
                return new JsonResponse(['msg' => $translator->trans('stage_invitation_page.closed_invitation_message')],500);
            } else {
                
                if($stage->getJoinableStatus() == STAGE::JOINABLE_REQUEST){
                    
                    $updateProperty = 'join_request';
                    /** @var UserMaster */
                    $userMastering = $user->getMasterings()->filter(fn(UserMaster $m) => $m->getStage() == $stage && $m->getProperty() == 'joinableStatus')->first();
                    if(!$userMastering){
                        $userMastering = new UserMaster();
                        $userMastering->setStage($stage)
                            ->setProperty('joinableStatus');
                    } 
                    $userMastering->setType(UserMaster::PENDING);
                    $user->addMastering($userMastering);
                    $em->persist($user);
                    $notificationManager->registerUpdates($stage, $notifiedUsers, ElementUpdate::CREATION, 'join_request');
                    $waitingApproval = true;
                } else {

                    $updateProperty = 'join_direct';
                    // We need to determine whether current user is client of activity organizer, to send query parameters if neccessary
                    $request->attributes->set('uid',$usrId);
                    if($user->getOrganization() != $stage->getOrganization()){
                        /** @var Client|null */
                        $userOrgClient = $stage->getOrganization()->getClients()->filter(fn(Client $c) => $c->getClientOrganization() == $this->org)->first();
                        if($userOrgClient){
                            $request->attributes->set('cid',$userOrgClient->getId());
                            $userExternalUser = $userOrgClient->getAliveExternalUsers()->filter(fn(ExternalUser $eu) => $eu->getUser() == $this->user)->first();
                            if($userExternalUser){
                                $request->attributes->set('euid',$userExternalUser->getId());
                            }
                        }
                        $request->attributes->set('user-type','ext');
                    } else {
                        $request->attributes->set('user-type','int');
                    }
                    $this->forward('App\Controller\OrganizationController::addStageParticipantFollower', ['stgId' => $stgId, 'fjType' => 'participant']);
                }

                $notificationManager->registerUpdates($stage, $notifiedUsers, ElementUpdate::CREATION, $updateProperty);
            }

        // Corresponds to follow option
        } else if ($positioningValue == 0) {

            if($stage->getFollowableStatus() == STAGE::FOLLOWABLE_FORBIDDEN){
                return new JsonResponse(['msg' => $translator->trans('stage_invitation_page.closed_invitation_message')],500);
            } else{
                
                /** @var UserMaster */
                $userMastering = $user->getMasterings()->filter(fn(UserMaster $m) => $m->getStage() == $stage && $m->getProperty() == 'followableStatus')->first();
                if(!$userMastering){
                    $userMastering = new UserMaster();
                    $userMastering->setStage($stage)
                    ->setProperty('followableStatus');
                } 

                if($stage->getFollowableStatus() == STAGE::FOLLOWABLE_REQUEST){
                    $masteringValue = UserMaster::PENDING;
                    $updateProperty = 'follow_request';
                    $waitingApproval = true;
                } else {
                    $masteringValue = UserMaster::ADDED;
                    $updateProperty = 'follow_direct';
                }
                $userMastering->setType($masteringValue);
                $user->addMastering($userMastering);
                $em->persist($user);
                $notificationManager->registerUpdates($stage, $notifiedUsers, ElementUpdate::CREATION, $updateProperty);
            } 
        }

        // Connect to account which stage was linked to
        if($this->user != $user){
            $this->guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $this->authenticator,
                'main'
            );  
            $user->setLastConnected(new DateTime());
            $em->persist($user);
        }
        $em->flush();

        return new JsonResponse(['waitingApproval' => (int) $waitingApproval]);
    }

    /**
     * @param Request $request
     * @return mixed
     * @Route("/user/invitations/stage/{stgId}/unfollow", name="unfollowStage", methods={"POST"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function unfollowStageAction(Request $request, int $stgId){
        $em = $this->em;
        /** @var Stage */
        $stage = $em->getRepository(Stage::class)->find($stgId);
        $userStageFollowing = $this->user->getMasterings()->filter(fn(UserMaster $m) => $m->getStage() == $stage && $m->getProperty() == 'followableStatus' && $m->getType() >= UserMaster::ADDED)->first();
        $userStageFollowing->setType(UserMaster::REMOVAL);
        $em->persist($userStageFollowing);

        // If user notification has not been read, recall notification
        $concernedUpdate = $em->getRepository(ElementUpdate::class)->findOneBy(['property' => 'follow_direct', 'initiator' => $this->user, 'stage' => $stage, 'type' => ElementUpdate::CREATION]);
        $this->user->removeUpdate($concernedUpdate);
        $em->persist($this->user);
        $em->flush();
        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @return mixed
     * @Route("/user/requests/stage/get", name="getPendingFJRequests", methods={"GET"})
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */
    public function getPendingFJRequests(Request $request, TranslatorInterface $translator){
        $pendingFJRequests =  $this->user->getMasterings()->filter(fn(UserMaster $m) => $m->getType() == 0)->map(fn(UserMaster $m) => [
            'name' => $m->getStage()->getName(),
            'type' => $m->getProperty() == 'followableStatus' ? $translator->trans('stage_visibility_modal.followable_title') : $translator->trans('stage_visibility_modal.joinable_title'),
            'rdate' => $m->getInserted()
            ])->getValues(); 
        return new JsonResponse($pendingFJRequests);
    }
}
