<?php
namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use App\Form\AddOrganizationForm;
use App\Form\AddClientForm;
use App\Form\DelegateActivityForm;
use App\Form\RequestActivityForm;
use App\Form\UpdateWorkerFirmForm;
use App\Form\UpdateWorkerIndividualForm;
use App\Form\SendMailProspectForm;
use App\Form\AddUserForm;
use App\Form\AddDepartmentForm;
use App\Form\AddProcessForm;
use App\Form\AddWeightForm;
use App\Form\SendMailForm;
use App\Form\SearchWorkerForm;
use App\Form\UpdateOrganizationForm;
use App\Form\ValidateFirmForm;
use App\Form\ManageProcessForm;
use App\Form\ValidateMassFirmForm;
use App\Form\ValidateMailForm;
use App\Form\ValidateMassMailForm;
use App\Form\Type\UserType;
use App\Form\Type\ClientUserType;
use App\Form\Type\OrganizationElementType;
use App\Entity\ActivityUser;
use App\Entity\Criterion;
use App\Entity\OrganizationUserOption;
use App\Entity\Process;
use App\Entity\Team;
use App\Entity\Weight;
use App\Entity\WorkerExperience;
use App\Entity\WorkerFirm;
use App\Entity\WorkerFirmCompetency;
use App\Entity\WorkerFirmSector;
use App\Entity\WorkerIndividual;
use App\Entity\Country;
use App\Entity\State;
use App\Entity\City;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\ExternalUser;
use App\Entity\Organization;
use App\Entity\Client;
use App\Entity\OptionName;
use App\Entity\Department;
use App\Entity\Position;
use App\Entity\Activity;
use App\Entity\CriterionGroup;
use App\Entity\CriterionName;
use App\Entity\InstitutionProcess;
use App\Entity\Mail;
use Symfony\Component\Form\FormFactory;

class SettingsController extends MasterController
{
    public static function getClientLangague(){
        $langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        return substr($langs[0], 0, 2);
    }

    public function displayTestingMails(Request $request, Application $app)
    {


        $em = $this->getEntityManager($app);
        $repoU = $em->getRepository(User::class);
        $formFactory = $app['form.factory'];
        $sendMailForm = $formFactory->create(SendMailForm::class, null, ['standalone' => true]);
        $sendMailForm->handleRequest($request);
        //$user = $em->getRepository(User::class)->findOneById(9);
        $actionType = $sendMailForm->get('emailType')->getData();
        $recipients = [];
        $settings = [];
        $settings['locale'] = $sendMailForm->get('lang')->getData();

        return $app['twig']->render('mail_testing.html.twig',
            [
                'form' => $sendMailForm->createView(),
            ]);

    }

    public function sendTestingMails(Request $request, Application $app)
    {


        $em = $this->getEntityManager($app);
        $repoU = $em->getRepository(User::class);
        $formFactory = $app['form.factory'];
        $sendMailForm = $formFactory->create(SendMailForm::class, null, ['standalone' => true]);
        $sendMailForm->handleRequest($request);
        //$user = $em->getRepository(User::class)->findOneById(9);

        if ($sendMailForm->isSubmitted()) {
            if ($sendMailForm->isValid()) {

                try {
                    $actionType = $sendMailForm->get('emailType')->getData();
                    $parameters = $sendMailForm->get('emailParameters')->getData();
                    $settings = [];
                    foreach($parameters as $parameter){
                        $settings[$parameter['parameterKey']] = $parameter['parameterValue'];
                    }
                    $recipients = [];
                    $settings['locale'] = $sendMailForm->get('lang')->getData();
                    $settings['locale'] = $sendMailForm->get('lang')->getData();

                    $recipient = $repoU->findOneByEmail($sendMailForm->get('emailAddress')->getData());
                    $recipients[] = $recipient;
                    $request->setLocale($sendMailForm->get('lang')->getData());

                    MasterController::sendMail($app, $recipients, $actionType, $settings);
                    return new JsonResponse(['message' => "Success"],200);
                }
                catch (\Exception $e){
                    return $e->getLine().' : '.$e->getMessage();
                }

            }
        }
    }

    public function rootManagementAction(Request $request, Application $app){

        return $app['twig']->render('root_management.html.twig',[]);

    }

    public function manageOrganizationsAction(Request $request, Application $app){
        $entityManager = $this->getEntityManager($app) ;
        $repoO = $entityManager->getRepository(Organization::class);
        $organizations = [];


        foreach ($repoO->findAll() as $organization) {

            $organizations[] = $organization->toArray($app);

        }

        //MasterController::sksort($organizations, 'lastConnectedDateTime');


        return $app['twig']->render('organization_list.html.twig',
            [
                'organizations' => $organizations,
                'lkPath' => null,
            ]) ;

    }

    public function manageProcessesAction(Request $request, Application $app){
        $em = $this->getEntityManager($app);
        $repoP = $em->getRepository(Process::class);
        $repoO = $em->getRepository(Organization::class);
        $currentUser = MasterController::getAuthorizedUser($app);
        $isRoot = $currentUser->getRole() == 4;
        $organization = $currentUser->getOrganization();
        $process = $isRoot ?  new Process : new InstitutionProcess();
        $elmtType = $isRoot ? 'process' :'iprocess';
        $formFactory = $app['form.factory'];
        $manageForm = $formFactory->create(ManageProcessForm::class, $organization, ['standalone' => true, 'isRoot' => $isRoot]);
        $manageForm->handleRequest($request);
        $createForm = $formFactory->create(AddProcessForm::class, $process, ['standalone' => true, 'organization' => $organization,'elmt' => $elmtType]);
        $createForm->handleRequest($request);

        $validatingProcesses = $isRoot ? $organization->getProcesses()->filter(function(Process $p){return $p->isApprovable();}) :
            $organization->getInstitutionProcesses()->filter(function(InstitutionProcess $p){return $p->isApprovable();});
        

        if($validatingProcesses->count() > 0){
            $validatingProcess = $validatingProcesses->first();
            $validateForm = $formFactory->create(AddProcessForm::class, $validatingProcess, ['standalone' => true, 'organization' => $organization,'elmt' => $elmtType]);
            $validateForm->handleRequest($request);
        } else {
            $validateForm = null;
        }
     

        if ($manageForm->isValid()) {
            $em->flush();
            return $app->redirect($app['url_generator']->generate('firmSettings'));
        }

        return $app['twig']->render('process_list.html.twig',
            [
                'isRoot' => $isRoot,
                'form' => $manageForm->createView(),
                'requestForm' => $createForm->createView(),
                'validateForm' => $validateForm ? $validateForm->createView() : null,
            ]);


    }

    public function validateProcessAction(Request $request, Application $app, $elmtId, $elmtType, $orgId) {
        $em = $this->getEntityManager($app);
        $repoO = $em->getRepository(Organization::class);
        
        $currentUser = MasterController::getAuthorizedUser($app);
        if (!$currentUser instanceof User) {
            return $app->redirect($app['url_generator']->generate('login'));
        }
        $currentUserOrganization = $repoO->find($currentUser->getOrgId());
        $organization = $repoO->find($orgId);
        $repoE = ($currentUser->getRole() == 4) ? $em->getRepository(Process::class) : $em->getRepository(InstitutionProcess::class);

        $elements = $repoE->findBy(['organization' => $orgId]);

        $hasPageAccess = true;

        if ($currentUser->getRole() != 4 && ($organization != $currentUserOrganization || $currentUser->getRole() != 1 && $currentUser->getId() != $elmtId)) {
            $hasPageAccess = false;
        }

        if (!$hasPageAccess) {
            return $app['twig']->render('errors/403.html.twig');
        } else {

            if ($_POST['name'] == "") {
                return new JsonResponse(['errorMsg' => 'The process must have a name'], 500);
            }

            if ($elmtId != 0) {
                /** @var InstitutionProcess|Process */
                $element = $repoE->find($elmtId);
            } else {
                /** @var InstitutionProcess|Process */
                $element = ($currentUser->getRole() == 4) ? new Process : new InstitutionProcess;
                $element->setOrganization($organization);
            }

            $doublonElmt = $repoE->findOneBy(['organization' => $organization, 'name' => $_POST['name']]);

            if (($doublonElmt == null) || ($doublonElmt == $element)) {
                $element->setName($_POST['name'])
                    ->setParent($repoE->findOneById($_POST['parent']))
                    ->setGradable($_POST['gradable']);
                if ($elmtType == 'iprocess') {
                    $repoU = $em->getRepository(User::class);
                    $selectedProcess = $em->getRepository(Process::class)->find($_POST['process']);
                    $selectedMasterUser = $repoU->find($_POST['masterUser']);
                    $element->setMasterUser($selectedMasterUser)->setProcess($selectedProcess);
                }
                $em->persist($element);
                $em->flush();
                return new JsonResponse(['message' => 'Success!','eid' => $element->getId()], 200);
            } else {
                return new JsonResponse(['errorMsg' => 'There is already a process having the same name !'], 500);
            }
        }
    }

    public function deleteProcessAction(Request $request, Application $app, $orgId, $elmtType) {

        $em = $this->getEntityManager($app);
        $repoO = $em->getRepository(Organization::class);
        $elmtId = $request->get('id');
        $repoU = $em->getRepository(User::class);
        $organization = $repoO->find($orgId);
        $currentUser = MasterController::getAuthorizedUser($app);
        $currentUserOrganization = $repoO->find($currentUser->getOrgId());
        $hasPageAccess = true;

        if ($currentUser->getRole() != 4 && ($organization != $currentUserOrganization || $currentUser->getRole() != 1)) {
            $hasPageAccess = false;
        }

        if (!$hasPageAccess) {
            return $app['twig']->render('errors/403.html.twig');
        } else {
        // $organization = $target->getOrganization();
            
            $repoE = ($elmtType == 'process') ? $em->getRepository(Process::class) : $em->getRepository(InstitutionProcess::class);
            /** @var InstitutionProcess|Process */
            $element = $repoE->find($elmtId);

            if($elmtType == 'process'){
                foreach($element->getInstitutionProcesses() as $IProcess){
                    $IProcess->removeProcess($element);
                    $IProcess->setProcess(null);
                    $this->em->persist($IProcess);
                }
            } else {
                foreach($element->getActivities() as $activity){
                    $element->removeActivity($activity);
                    $activity->setInstitutionProcess(null);
                    $this->em->persist($activity);
                }
            }   

            ($elmtType == 'process') ? $organization->removeProcess($element) : $organization->removeInstitutionProcess($element);
            $em->persist($organization);
            $em->flush();
            return new JsonResponse(['message' => 'Success!'], 200);
        }

    }

    //Adds user(s) to other organizations (limited to root)
    public function rootAddUserAction(Request $request, Application $app, $orgId) {

        $currentUser = MasterController::getAuthorizedUser($app);
        if (!$currentUser instanceof User) {
            return $app->redirect($app['url_generator']->generate('login'));
        }

        if ($currentUser->getRole() != 4) {
            return $app['twig']->render('errors/403.html.twig');
        }

        $em = $this->getEntityManager($app);
        $repoO = $em->getRepository(Organization::class);
        $organization = $repoO->find($orgId);
        $formFactory = $app['form.factory'] ;
        $createUserForm = $formFactory->create(AddUserForm::class, null, ['standalone'=>true,'organization' => $organization, 'enabledCreatingUser' => true]);
        $organizationElementForm = $formFactory->create(OrganizationElementType::class, null, ['usedForUserCreation' => true, 'standalone' => true ]);
        $createUserForm->handleRequest($request);
        $organizationElementForm->handleRequest($request);

        if($createUserForm->isSubmitted() && $createUserForm->isValid()){
            $settings = [];
            $recipients = [];
            foreach($createUserForm->get('users') as $userForm){
                $user = $userForm->getData();

                // Les lignes qui suivent sont horribles mais sont un hack au fait qu'a priori on ne puisse pas lier position et département à User (mais à vérifier, peut-être que ça remarche...)
                // $user->getPosId() renvoie une Position, et $user->getDptId() un département.

                $posId = $user->getPosId() ? $user->getPosId()->getId() : null;
                $dptId = $user->getDptId() ? $user->getDptId()->getId() : null;
                $titId = $user->getTitId() ? $user->getTitId()->getId() : null;
                $wgtId = $user->getWgtId() ? $user->getWgtId()->getId() : null;

                $token = md5(rand());

                $user->setOrgId($orgId)
                ->setPosId($posId)
                ->setDptId($dptId)
                ->setTitId($titId)
                ->setWgtId($wgtId)
                ->setToken($token);
                $em->persist($user);
                $settings['tokens'][] = $token;
                $recipients[] = $user;

            }

            $settings['rootCreation'] = true;
            $em->flush();
            MasterController::sendMail($app, $recipients,'registration', $settings);
            return $app->redirect($app['url_generator']->generate('rootManageUsers', ['orgId' => $orgId]));
        }

        return $app['twig']->render('user_create.html.twig',
            [
                'form' => $createUserForm->createView(),
                'organizationElementForm' => $organizationElementForm->createView(),
                'orgId' => $orgId,
                'enabledCreatingUser' => true,
                'creationPage' => true,
            ]);
    }



    //Modifies organization (limited to root master)
    public function modifyOrganizationAction(Request $request, Application $app){

    }

    // Display all organization activities (for root user)
    public function getAllOrganizationActivitiesAction(Request $request, Application $app, $orgId)
    {
        try{
            $entityManager = $this->getEntityManager($app) ;
            $repoO = $entityManager->getRepository(Organization::class);
            $currentUser = MasterController::getAuthorizedUser($app);
            $organization = $repoO->findOneById($orgId);
            $formFactory = $app['form.factory'] ;
            $delegateActivityForm = $formFactory->create(DelegateActivityForm::class, null,  ['app' => $app, 'standalone' => true]);
            $delegateActivityForm->handleRequest($request);
            $requestActivityForm = $formFactory->create(RequestActivityForm::class, null, ['app' => $app, 'standalone' => true]);
            $requestActivityForm->handleRequest($request);
            $userActivities = $organization->getActivities();


            //Remove future recurring activities which are far ahead (at least two after current one
            foreach($userActivities as $activity){
                if($activity->getRecurring()){
                    $recurring = $activity->getRecurring();

                    if($recurring->getOngoingFutCurrActivities()->contains($activity) && $recurring->getOngoingFutCurrActivities()->indexOf($activity) > 1){

                        $userActivities->removeElement($activity);
                    }
                }
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
            $currentActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 1)));
            $nbActivitiesCategories = (count($currentActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
            $completedActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 2)));
            $nbActivitiesCategories = (count($completedActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
            $releasedActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 3)));
            $archivedActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 4)));

        } catch (\Exception $e){
            print_r($e->getLine().' '.$e->getMessage());
            die;
        }


        return $app['twig']->render('activity_list.html.twig',
            [
                'user_activities' => $userActivities,
                'organization' => $organization,
                'delegateForm' => $delegateActivityForm->createView(),
                'requestForm' => $requestActivityForm->createView(),
                'orgMode' => true,
                'request' => $request,
                'cancelledActivities' => $cancelledActivities,
                'discardedActivities' => $discardedActivities,
                'requestedActivities' => $requestedActivities,
                'attributedActivities' => $attributedActivities,
                'incompleteActivities' => $incompleteActivities,
                'futureActivities' => $futureActivities,
                'currentActivities' => $currentActivities,
                'completedActivities' => $completedActivities,
                'releasedActivities' => $releasedActivities,
                'archivedActivities' => $archivedActivities,
                'nbCategories' => $nbActivitiesCategories,
                'existingAccessAndResultsViewOption' => false,
                'hideResultsFromStageIds' => [],
                'resultsAccess' => 2,
            ]);

    }

    // Display all organization users (for root user)
    // Same code as OrgController::getAllUsersAction
    public function getAllOrganizationUsersAction(Request $request, Application $app, $orgId){


        $entityManager = $this->getEntityManager($app);
        $repoEU = $entityManager->getRepository(ExternalUser::class);
        $repoU = $entityManager->getRepository(User::class);
        $repoO = $entityManager->getRepository(Organization::class);
        /** @var Organization */
        $organization = $repoO->findOneById($orgId);
        $clientFirms = new ArrayCollection;
        $clientTeams = new ArrayCollection;
        $clientIndividuals = new ArrayCollection;
        $clients = $organization->getClients();
        $totalClientUsers = 0;
        $orgEnabledCreatingUser = false;
        // Only administrators or roots can create/update users who have the ability to create users themselves
        $orgOptions = $organization->getOptions();

        // Selecting viewable departments
        $viewableDepartments     = $organization->getDepartments();

        foreach ($clients as $client){
            switch ($client->getClientOrganization()->getType()){
                case 'F':
                    $clientFirms->add($client);
                    break;
                case 'T':
                    $clientTeams->add($client);
                    break;
                case 'I':
                    $clientIndividuals->add($client);
                    break;
                default :
                    break;
            }

        }
        foreach($orgOptions as $orgOption){
            if($orgOption->getOName()->getName() == 'enabledUserCreatingUser'){
                $orgEnabledCreatingUser = $orgOption->isOptionTrue();
            }
        }

        $totalClientUsers = count($organization->getExternalUsers());
        $nbViewableInternalUsers = count($organization->getUsers($app));

        $viewableTeams = $organization->getTeams();

        $firmLogoPath = $organization->getLogo();

        $users = new ArrayCollection($repoU->findBy(['orgId' => $orgId],['dptId' => 'ASC','posId' =>'ASC']));
        $usersWithDpt = $users->matching(Criteria::create()->where(Criteria::expr()->neq("dptId", null)));
        $usersWithoutDpt = $users->matching(Criteria::create()->where(Criteria::expr()->eq("dptId", null))->andWhere(Criteria::expr()->neq("lastname", "ZZ")));

        return $app['twig']->render('user_list.html.twig',
            [
                'rootDisplay' => true,
                'app' => $app,
                'clientFirms' => $clientFirms,
                'clientTeams' => $clientTeams,
                'clientIndividuals' => $clientIndividuals,
                'usersWithDpt' => $usersWithDpt,
                'organization' => $organization,
                'totalClientUsers' => $totalClientUsers,
                'firm_logo' => $firmLogoPath,
                'usersWithoutDpt' => $usersWithoutDpt,
                'viewableDepartments' => $viewableDepartments,
                'viewableTeams' => $viewableTeams,
                'orgEnabledUserCreatingUser' => $orgEnabledCreatingUser,
                'orgEnabledUserSeeRanking' => true,
                'nbViewableInternalUsers' => $nbViewableInternalUsers,
                'orgEnabledUserSeeAllUsers' => true,
                'orgEnabledUserSeePeersResults' => true,
                'enabledUserSeeSnapshotSupResults' => true,
                'enabledSuperiorOverviewSubResults' => true,
                'enabledSuperiorSettingTargets' => true,
                'enabledSuperiorModifySubordinate' => true,
            ]);
    }


    // Delete organization (limited to root master)
    public function deleteOrganizationAction(Application $app, $orgId) {
        $em = self::getEntityManager();
        $repoO = $em->getRepository(Organization::class);
        $repoU = $em->getRepository(User::class);

        /** @var Organization */
        $organization = $repoO->find($orgId);
        $em->remove($organization);

        /** @var User[] */
        $orgUsers = $repoU->findByOrgId($orgId);
        foreach ($orgUsers as $orgUser) {
            $em->remove($orgUser);
        }
        $em->flush();

        return $app->redirect($app['url_generator']->generate('manageOrganizations'));
    }


    //Adds organization (limited to root master)
    public function addOrganizationAction(Request $request, Application $app)
    {
        $em = self::getEntityManager();
        /** @var FormFactory */
        $formFactory = $app['form.factory'];
        $organizationForm = $formFactory->create(AddOrganizationForm::class, null,['standalone' => true, 'orgId' => 0, 'app' => $app]);
        $organizationForm->handleRequest($request);
        $errorMessage = '';
        $organization = new Organization;
        $options = [];
        $repoO = $em->getRepository(Organization::class);
        $repoWF = $em->getRepository(WorkerFirm::class);
        $repoU = $em->getRepository(User::class);
        $repoON = $em->getRepository(OptionName::class);
        /** @var OptionName[] */
        $options = $repoON->findAll();

        if ($organizationForm->isSubmitted()) {
            /*
            if($repoO->findOneByLegalname($organizationForm->get('workerFirm')->getData())){
                $organizationForm->get('commercialName')->addError(new FormError('You already have created an organization with such legalname. Make sure you correctly provided the data'));
            }
            */
            /*
            if($repoU->findOneByEmail($organizationForm->get('email')->getData())){
                $organizationForm->get('email')->addError(new FormError('Ooopsss ! There is already a user registered with this email address. Please make sure you have not already created an organization with such an address'));
            }*/

            if ($organizationForm->isValid()) {

                /** @var string */
                $email = $organizationForm->get('email')->getData();
                /** @var string */
                $firstname = $organizationForm->get('firstname')->getData();
                /** @var string */
                $lastname = $organizationForm->get('lastname')->getData();
                /** @var WorkerFirm */
                $workerFirm = $repoWF->find((int) $organizationForm->get('workerFirm')->getData());
                /** @var string */
                $positionName = $organizationForm->get('position')->getData();
                /** @var string */
                $departmentName = $organizationForm->get('department')->getData();
                /** @var string */
                $orgType = $organizationForm->get('type')->getData();
                $user = null;

                $token = md5(rand());

                if(!($email == "" && $firstname == "" && $lastname == "")){
                    $user = new User;
                    $user
                        ->setFirstname($firstname)
                        ->setLastname($lastname)
                        ->setEmail($email)
                        ->setRole(USER::ROLE_AM)
                        ->setToken($token)
                        ->setWeightIni(100)
                        ->setOrgId($organization->getId());
                    $em->persist($user);
                    $em->flush();
                }
                
                $defaultOrgWeight = new Weight;
                $defaultOrgWeight->setOrganization($organization)
                    ->setValue(100);
                $organization->addWeight($defaultOrgWeight);

                $organization
                    ->setValidated(new \DateTime)
                    ->setCommname($workerFirm->getName())
                    ->setLegalname($workerFirm->getName())
                    ->setIsClient(true)
                    ->setType($orgType)
                    ->setWeight_type('role')
                    ->setExpired(new \DateTime('2100-01-01 00:00:00'))
                    ->setWorkerFirm($workerFirm);

                if($user){$organization->setMasterUserId($user->getId());}

                $em->persist($organization);
                $em->flush();

                if($user){

                    if($departmentName != "") {
                        $department = new Department;
                        $department
                            ->setName($departmentName)
                            ->setOrganization($organization);
                        $em->persist($department);
                        $em->flush();
                    }

                    if($positionName != "") {
                        $position = new Position;
                        $position
                            ->setName($positionName)
                            ->setOrganization($organization);
                        $em->persist($position);
                        $em->flush();
                    }

                    /*$weight = (new Weight)
                        ->setInterval(0)
                        ->setTimeframe('D')
                        ->setOrganization($organization)
                        ->setValue(100);

                    if($positionName != ""){$weight->setPosition($position);}

                    $em->persist($weight);
                    $em->flush();*/

                    $user
                        ->setOrgId($organization->getId())
                        ->setDptId($department->getId())
                        ->setPosId($position->getId())
                        ->setWgtId($defaultOrgWeight->getId());
                    $em->persist($user);
                    $em->flush();

                }


                foreach ($options as $option) {

                    $optionValid = (new OrganizationUserOption)
                    ->setOName($option)
                    ->setOrganization($organization);

                    // We set nb of days for reminding emails, very important otherwise if unset, if people create activities, can make system bug.
                    //  => Whenever someone logs in, this person triggers reminder mails to every person in every organization, organization thus should have this parameter date set.
                    if($option->getName() == 'mailDeadlineNbDays'){
                        $optionValid->setOptionFValue(2);
                    }
                    $em->persist($optionValid);

                    // At least 3 options should exist for a new firm for activity & access results
                    if($option->getName() == 'activitiesAccessAndResultsView'){

                        // Visibility and access options has many options :
                        // * Scope (opt_bool_value in DB, optionTrue property) : defines whether user sees his own results (0), or all participant results (1)
                        // * Activities access (opt_int_value, optionIValue property) : defines whether user can access all organisation acts (1), his department activities (2) or his own activities (3)
                        // * Status access (opt_int_value_2, optionSecondaryIValue property) : defines whether user can access computed results (2), or released results (3)
                        // * Detail (opt_float_value, optionFValue property) : defines whether user accesses averaged/consolidated results (0), or detailed results (1)
                        // * Results Participation Condition (opt_string_value, optionSValue property) : defines whether user accesses activity results without condition ('none'), if he is activity owner ('owner'), or if he is participating ('participant')

                        $optionAdmin = $optionValid;
                        $optionAdmin->setRole(1)->setOptionTrue(true)->setOptionIValue(1)->setOptionSecondaryIValue(2)->setOptionFValue(1)->setOptionSValue('none');
                        $em->persist($optionAdmin);

                        $optionAM = (new OrganizationUserOption)
                        ->setOName($option)
                        ->setOrganization($organization);
                        $optionAM->setRole(2)->setOptionTrue(true)->setOptionIValue(2)->setOptionSecondaryIValue(2)->setOptionFValue(0)->setOptionSValue('owner');
                        $em->persist($optionAM);

                        $optionC = (new OrganizationUserOption)
                        ->setOName($option)
                        ->setOrganization($organization);
                        $optionC->setRole(3)->setOptionTrue(false)->setOptionIValue(3)->setOptionSecondaryIValue(3)->setOptionFValue(0)->setOptionSValue('participant');
                        $em->persist($optionC);
                    }
                }

                $em->flush();

                $repoCN = $em->getRepository(CriterionName::class);
                $criterionGroups = [
                    1 => new CriterionGroup('Hard skills', $organization),
                    2 => new CriterionGroup('Soft skills', $organization)
                ];
                foreach ($criterionGroups as $cg) {
                    $em->persist($cg);
                }
                $em->flush();

                /**
                 * @var CriterionName[]
                 */
                $defaultCriteria = $repoCN->findBy(['organization' => null]);
                foreach ($defaultCriteria as $defaultCriterion) {
                    $criterion = clone $defaultCriterion;
                    // 1: hard skill
                    // 2: soft skill
                    $type = $criterion->getType();
                    $cg = $criterionGroups[$type];
                    $criterion
                        ->setOrganization($organization)
                        ->setCriterionGroup($cg);

                    $cg->addCriterion($criterion);
                    $em->persist($criterion);
                }

                //Synthetic User Creation (for external, in case no consituted team has been created to grade a physical person for an activity)
                $syntheticUser = new User;
                $syntheticUser
                    ->setFirstname('ZZ')
                    ->setLastname('ZZ')
                    ->setRole(3)
                    ->setOrgId($organization->getId());
                $em->persist($syntheticUser);

                $em->flush();

                // Sending mail to created firm master user, if such user exists
                if($user){
                    $settings['tokens'][] = $token;
                    $recipients = [];
                    $recipients[] = $user;
                    $settings['rootCreation'] = true;

                    MasterController::sendMail($app,$recipients,'registration',$settings);
                }

                return $app->redirect($app['url_generator']->generate('manageOrganizations'));
            }

        }

        return $app['twig']->render('organization_add.html.twig',
            [
                'form' => $organizationForm->createView(),
                'message' => $errorMessage,
                //'nbweights'=> $nbWeights,
                //'totalweights' => $totalWeights
            ]);
    }

    public function validateOrganizationAction(Request $request, Application $app, $orgId){

        $currentUser = self::getAuthorizedUser($app);
        if($currentUser->getRole() != 4){
            return $app['twig']->render('errors/404.html.twig');
        }

        $em = $this->getEntityManager($app);
        $repoO = $em->getRepository(Organization::class);
        $repoU = $em->getRepository(User::class);
        $organization = $repoO->findOneById($orgId);
        $formFactory = $app['form.factory'];
        $organizationForm = $formFactory->create(AddOrganizationForm::class, null,['standalone' => true, 'orgId' => $orgId, 'app' => $app, 'toValidate' => true]);
        $organizationForm->handleRequest($request);

        if($organizationForm->isSubmitted()){

            $masterUser = $repoU->findOneById($organization->getMasterUserId());

            if($organizationForm->get('validate')->isClicked()){

                $organization
                    ->setIsClient(true)
                    ->setValidated(new \DateTime)
                    ->setType($organizationForm->get('type')->getData());

                $masterUser->setValidated(new \DateTime);
                $em->persist($organization);
                $em->persist($masterUser);

                $recipients = [];
                $recipients[] = $masterUser;
                $settings = [];
                $settings['token'] = $masterUser->getToken();

                MasterController::sendMail($app, $recipients,'subscriptionConfirmation', $settings);

            } else {

                $em->remove($masterUser);
                $em->remove($organization);
            }

            $em->flush();
            return $app->redirect($app['url_generator']->generate('manageOrganizations'));
        }

        return $app['twig']->render('organization_add.html.twig',
            [
                'form' => $organizationForm->createView(),
                'toValidate' => true,
            ]);
    }

    public function deleteOrganizationActivityAction(Request $request, Application $app, $orgId, $actId){
        $em = $this->getEntityManager($app);
        $activity = $em->getRepository(Activity::class)->findOneById($actId);
        $user = self::getAuthorizedUser($app);
        $organization = $em->getRepository(Organization::class)->findOneById($orgId);
        $organization->removeActivity($activity);
        $em->persist($organization);
        $em->flush();
        return true;
    }

    public function updateOrganizationAction(Request $request, Application $app, $orgId){

        $em = $this->getEntityManager($app);
        $repoO = $em->getRepository(Organization::class);
        $formFactory = $app['form.factory'];
        $organizationForm = $formFactory->create(AddOrganizationForm::class, null,['standalone' => true, 'orgId' => $orgId, 'app' => $app]);
        $organizationForm->handleRequest($request);
        $errorMessage = '';
        $organization = $repoO->findOneById($orgId);
        $user = new User;
        $department = new Department;
        $position = new Position;

        if ($organizationForm->isSubmitted()) {
            if ($organizationForm->isValid()) {
                $email = $organizationForm->get('email')->getData();
                $token = md5(rand());

                $organization->setCommname($organizationForm->get('commname')->getData());
                $organization->setLegalname($organizationForm->get('legalname')->getData());
                $organization->setMasterUserId(0);
                $em->persist($organization);

                $department->setOrganization($organization);
                $department->setName($organizationForm->get('department')->getData());
                $em->persist($department);
                //$em->flush();

                //$position->setDepartment($department->getId());
                $position->setDepartment($department);
                $position->setName($organizationForm->get('position')->getData());

                $em->persist($position);
                $em->flush();

                $user->setFirstname($organizationForm->get('firstname')->getData());
                $user->setLastname($organizationForm->get('lastname')->getData());
                $user->setEmail($email);

                $user->setRole(1);
                $user->setToken($token);

                $user->setPosId($position->getId());

                $user->setOrgId($organization->getId());
                $em->persist($user);
                $em->flush();

                $organization->setMasterUserId($user->getId());
                $em->persist($organization);
                $em->flush();

                $recipients = [];
                $recipients[] = $user;
                $recipients[] = $user;
                $settings = [];

                MasterController::sendMail($app,$recipients,'orgMasterUserChange',$settings);

                return $app->redirect($app['url_generator']->generate('manageOrganizations'));
            }

        }

        return $app['twig']->render('organization_add.html.twig',
            [
                'form' => $organizationForm->createView(),
                'message' => $errorMessage,
                'update' => true,
                'orgId' => $organization->getId()
            ]);

    }

    // Display user info, enables modification. Note : root user can modify users from other organizations
    public function updateUserAction(Request $request, Application $app, $orgId, $usrId)
    {

        $em = $this->getEntityManager($app);
        $repoO = $em->getRepository(Organization::class);
        $repoOC = $em->getRepository(Client::class);
        $searchedUser = $em->getRepository(User::class)->findOneById($usrId);
        $connectedUser = MasterController::getAuthorizedUser($app);
        $searchedUserOrganization = $repoO->findOneById($searchedUser->getOrgId());
        $orgOptions = $searchedUserOrganization->getOptions();
        $formFactory = $app['form.factory'];
        $departments = ($searchedUser->getOrgId() == $connectedUser->getOrgId() || $connectedUser->getRole() == 4) ? $searchedUserOrganization->getDepartments() : null;
        $enabledCreatingUserOption = false;
        foreach($orgOptions as $orgOption){
            if($orgOption->getOName()->getName() == 'enabledUserCreatingUser'){
                $enabledCreatingUserOption = $orgOption->isOptionTrue();
            }
        }
        // Look through organization clients if user belongs to org clients
        if($searchedUser->getOrgId() != $connectedUser->getOrgId()){

            $connectedUserOrganization = $repoO->findOneById($connectedUser->getOrgId());
            $connectedUserOrgClients = $repoOC->findByOrganization($connectedUserOrganization);
            $connectedUserClients = [];
            foreach($connectedUserOrgClients as $connectedUserOrgClient){
                $connectedUserClients[] = $connectedUserOrgClient->getClientOrganization();
            }

            if(!in_array($searchedUserOrganization,$connectedUserClients) && $connectedUser->getRole() != 4){
                return $app['twig']->render('errors/403.html.twig');
            }

            if(in_array($searchedUserOrganization,$connectedUserClients)){
                $modifyIntern = false;
                $userForm = $formFactory->create(ClientUserType::class, null, ['standalone' => true, 'user' => $searchedUser, 'app' => $app, 'clients' => $connectedUserClients]);
            } else {
                // This case only applies to root users
                $modifyIntern = true;
                $userForm = $formFactory->create(UserType::class, null, ['standalone' => true, 'app' => $app, 'organization' => $searchedUserOrganization, 'user' => $searchedUser]);
            }

        } else {
            if($connectedUser->getRole() == 2 || $connectedUser->getRole() == 3){
                return $app['twig']->render('errors/403.html.twig');
            }

            $modifyIntern = true;
            $userForm = $formFactory->create(UserType::class, $searchedUser, ['standalone' => true, 'organization' => $searchedUserOrganization]);
        }



        $userForm->handleRequest($request);
        $organizationElementForm = $formFactory->create(OrganizationElementType::class, null, ['usedForUserCreation' => false, 'standalone' => true, 'organization' => $searchedUserOrganization]);
        $organizationElementForm->handleRequest($request);
        /*} catch (\Exception $e){
            print_r($e->getMessage());
            die;
        }*/

        return $app['twig']->render('user_create.html.twig',
            [
                'modifyIntern' => $modifyIntern,
                'form' => $userForm->createView(),
                'orgId' => $searchedUserOrganization->getId(),
                'organizationElementForm' => $organizationElementForm->createView(),
                'clientForm' => ($modifyIntern) ? null : $formFactory->create(AddClientForm::class, null, ['standalone'=>true])->createView(),
                'enabledCreatingUser' => false,
                'creationPage' => false,

            ]);

    }

    public function updateUserActionAJAX(Request $request, Application $app, $orgId, $usrId)
    {

        try{
        $em = $this->getEntityManager($app);
        $repoO = $em->getRepository(Organization::class);
        $repoOC = $em->getRepository(Client::class);
        $searchedUser = $em->getRepository(User::class)->findOneById($usrId);
        $connectedUser = MasterController::getAuthorizedUser($app);
        $formFactory = $app['form.factory'];

        $searchedUserOrganization = $repoO->findOneById($searchedUser->getOrgId());

        $departments = ($searchedUser->getOrgId() == $connectedUser->getOrgId() || $connectedUser->getRole() == 4) ? $searchedUserOrganization->getDepartments() : null;

        // Look through organization clients if user belongs to org clients
        if($searchedUser->getOrgId() != $connectedUser->getOrgId()){

            $connectedUserOrganization = $repoO->findOneById($connectedUser->getOrgId());
            $connectedUserOrgClients = $repoOC->findByOrganization($connectedUserOrganization);
            $connectedUserClients = [];
            foreach($connectedUserOrgClients as $connectedUserOrgClient){
                $connectedUserClients[] = $connectedUserOrgClient->getClientOrganization();
            }

            if(!in_array($searchedUserOrganization,$connectedUserClients) && $connectedUser->getRole() != 4){
                return $app['twig']->render('errors/403.html.twig');
            }

            $userForm = (!in_array($searchedUserOrganization,$connectedUserClients)) ?
            $formFactory->create(UserType::class, null, ['standalone' => true, 'app' => $app, 'departments' => $departments, 'user' => $searchedUser]) :
            $formFactory->create(ClientUserType::class, null, ['standalone' => true, 'user' => $searchedUser, 'app' => $app, 'clients' => $connectedUserOrgClients]);

        } else {
            if($connectedUser->getRole() == 2 || $connectedUser->getRole() == 3){
                return $app['twig']->render('errors/403.html.twig');
            }

            $userForm = $formFactory->create(UserType::class, null, ['standalone' => true, 'app' => $app, 'departments' => $departments, 'user' => $searchedUser]);
        }



        $userForm->handleRequest($request);

        if($userForm->isValid()){

            if($searchedUser->getOrgId() == $connectedUser->getOrgId() || !in_array($searchedUserOrganization,$connectedUserClients)){

                $repoW = $em->getRepository(Weight::class);
                //$repoP = $em->getRepository(Position::class);
                $searchedUser
                    ->setFirstname($userForm->get('firstname')->getData())
                    ->setLastname($userForm->get('lastname')->getData())
                    ->setRole($userForm->get('role')->getData())
                    ->setPosId($userForm->get('position')->getData())
                    ->setDptId($userForm->get('department')->getData())
                    ->setWgtId($userForm->get('weightIni')->getData());

                if($searchedUser->getEmail() != $userForm->get('email')->getData()){
                    $repicients = [];
                    $recipients[] = $searchedUser;
                    $token = md5(rand());
                    $settings['token'] = $token;
                    $searchedUser->setPassword(null)->setToken($token)->setEmail($userForm->get('email')->getData());
                    MasterController::sendMail($app,$recipients,'emailChangeNotif',$settings);
                }

                $existingWeight = $repoW->findOneById($userForm->get('weightIni')->getData());
                $searchedUser->setWeightIni($existingWeight->getValue());

                $em->persist($searchedUser);
                $em->flush();

            } else {

                $externalUser = $searchedUser->getExternalUser($app);

                if($externalUser->getEmail() != $userForm->get('email')->getData()){
                    $repicients = [];
                    $recipients[] = $searchedUser;
                    $token = md5(rand());
                    $settings['token'] = $token;
                    $externalUser->setPassword(null)->setToken($token)->setEmail($userForm->get('email')->getData());
                    MasterController::sendMail($app,$recipients,'emailChangeNotif',$settings);
                }

                $externalUser
                    ->setFirstname($userForm->get('firstname')->getData())
                    ->setLastname($userForm->get('lastname')->getData())
                    ->setPositionName($userForm->get('positionName')->getData())
                    ->setWeightValue($userForm->get('weightValue')->getData());

                if($userForm->get('type')->getData() != 'I'){
                    $searchedUser->setOrgId($userForm->get('orgId')->getData());
                    $clientOrganization = $repoO->findOneById(intval($userForm->get('orgId')->getData()));
                    $clientOrganization->setType($userForm->get('type')->getData());
                } else {
                    $clientOrganization = new Organization;
                    $clientOrganization->setType('I')->setIsClient(false)->setCommname($userForm->get('firstname')->getData().' '.$userForm->get('lastname')->getData())->setWeight_type('role');
                    $client = new Client;
                    $client->setOrganization($connectedUserOrganization)->setClientOrganization($clientOrganization);
                    $connectedUserOrganization->addClient($client);
                }

                $em->persist($externalUser);
                $em->persist($clientOrganization);
                $em->persist($connectedUserOrganization);
                $em->flush();

            }

            return new JsonResponse(['message' => 'Success!'], 200);

        } else {
            $errors = $this->buildErrorArray($userForm);
            return $errors;
        }
    }
    catch(\Exception $e) {
        print_r($e->getLine().' '.$e->getMessage());
        die;
    }
    }

    // Root user deletion function (does it permanently)
    public function deleteUserAction(Request $request, Application $app, $orgId, $usrId){
        $em = $this->getEntityManager($app);
        $repoAU = $em->getRepository(ActivityUser::class);
        $repoU = $em->getRepository(User::class);
        $repoO = $em->getRepository(Organization::class);
        $repoD = $em->getRepository(Department::class);
        $repoP = $em->getRepository(Position::class);
        $repoW = $em->getRepository(Weight::class);
        $user = $repoU->findOneById($usrId);
        $organization = $repoO->findOneById($orgId);
        $deleteOrg = false;
        $userOrgId = $user->getOrgId();
        $posId = $user->getPosId();
        $dptId = $user->getDptId();
        $wgtId = $user->getWgtId();
        $nbUserParticipations = count($repoAU->findByUsrId($usrId));

        if($posId != null){
            $position = $repoP->findOneById($posId);
            $positionUsers = $position->getUsers($app);
            if(count($positionUsers) == 1){
                if($nbUserParticipations == 0){
                    $organization->removePosition($position);
                } else {
                    $position->setDeleted(new \DateTime);
                    $em->persist($position);
                }
            }
        }

        if($dptId != null){
            $department = $repoD->findOneById($dptId);
            $departmentUsers = $department->getUsers($app);
            if(count($departmentUsers) == 1){
                if($nbUserParticipations == 0){
                    $organization->removeDepartment($department);
                } else {
                    $department->setDeleted(new \DateTime);
                    $em->persist($department);
                }
            }
        }

        if($wgtId != null){
            $weight = $repoW->findOneById($wgtId);
            $weightUsers = $weight->getUsers($app);
            if(count($weightUsers) == 1){
                if($nbUserParticipations == 0){
                    $em->remove($weight);
                }
            }
        }

        if($nbUserParticipations == 0){
            $em->remove($user);
        } else {
            $user->setDeleted(new \DateTime);
            $em->persist($user);
        }

        $em->flush();

        $organizationUsers = $organization->getUsers($app);
        if($organizationUsers == null){
            $em->remove($organization);
            $deleteOrg = true;
            $em->flush();
        }

        return new JsonResponse(['message' => 'Success!', 'deleteOrg' => $deleteOrg], 200);
    }

    // Root external user deletion (does it permanently)
    public function deleteClientUserAction(Request $request, Application $app, $orgId, $usrId){
        $em = $this->getEntityManager($app);
        $repoAU = $em->getRepository(ActivityUser::class);
        $repoEU = $em->getRepository(ExternalUser::class);
        $repoU = $em->getRepository(User::class);
        $repoO = $em->getRepository(Organization::class);
        $em->flush();
        $organization = $repoO->findOneById($orgId);
        $nbUserParticipations = count($repoAU->findByUsrId($usrId));
        $internalUser = $repoU->findOneById($usrId);
        $externalUser = $repoEU->findOneBy(['user' => $internalUser, 'organization' => $organization]);
        if($nbUserParticipations == 0){
            $this->deleteUserAction($request, $app, $orgId, $usrId);
            $em->remove($externalUser);
        } else {
            $externalUser->setDeleted(new \DateTime);
            $em->persist($externalUser);
        }
        $em->flush();
        return new JsonResponse(['message' => 'Success!'], 200);
    }

    public function insertIndividualExperience($em, $repoWF, $repoWE, $worker, $experience){

        // 1 - We look whether firm exists
        $firmName = $experience['firm']['name'];
        $firmSuffix = $experience['firm']['urlSuffix'];
        $startDate = new \DateTime($experience['SD']);
        $endDate = new \DateTime($experience['ED']);

        if($firmSuffix != null){
            $firm = $repoWF->findOneBy(['url' => $firmSuffix]);
        } else {
            $firm = $repoWF->findOneBy(['name' => $firmName]);
        }

        if($firm == null){
            $firm = new WorkerFirm;
            $firm
                ->setName($firmName)
                ->setUrl($firmSuffix)
                ->setCreated(0);
        }

        if($experience['ED'] == null){
            if($firm->isActive() == false){
                $firm->setActive(true);
            }
            $nbActiveExperiences = $firm->getNbActiveExperiences();
            if($nbActiveExperiences == null){
                $firm->setNbActiveExperiences(1);
            } else {
                $firm->setNbActiveExperiences($nbActiveExperiences+1);
            }
        } else {
            $nbOldExperiences = $firm->getNbOldExperiences();
            if($nbOldExperiences == null){
                $firm->setNbOldExperiences(1);
            } else {
                $firm->setNbOldExperiences($nbOldExperiences+1);
            }
        }

        //$workerExp = $repoWE->findOneBy(['startDate' => $startDate, 'firm' => $firm, 'individual' => $worker]);

        //if($workerExp == null){
            $workerExp = new WorkerExperience;
            $workerExp->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setPosition($experience['pos'])
                ->setActive($experience['ED'] == null);
            if(isset($experience['location'])){
                $workerExp->setLocation($experience['location']);
            }
            $firm->addExperience($workerExp);
            $worker->addExperience($workerExp);
        /*} else {
            if($experience['ED'] != null && $workerExp->getEndDate() == null){
                $workerExp->setActive(false);
            }

            if(isset($experience['location']) && $experience['location'] != null && $workerExp->getLocation() == null){
                $workerExp->setLocation($experience['location']);
            }
        }*/

        $em->persist($firm);
        $em->persist($worker);
        $em->flush();
    }

    /*public function searchWorkerElmts(Request $request, Application $app){

        $formFactory = $app['form.factory'];
        $searchWorkerForm = $formFactory->create(SearchWorkerForm::class, null);
        $searchWorkerForm->handleRequest($request);

        return $app['twig']->render('worker_search.html.twig',
        [
            'form' => $searchWorkerForm->createView(),
        ]);

    }*/

    public function duplicateOrganizationAction(Request $request, Application $app,$orgId){
        set_time_limit(240);
        ini_set('memory_limit', '500M');
        $em = $this->getEntityManager($app);
        $repoO = $em->getRepository(Organization::class);
        $repoA = $em->getRepository(Activity::class);
        $repoU = $em->getRepository(User::class);
        $repoEU = $em->getRepository(ExternalUser::class);
        $repoT = $em->getRepository(Team::class);
        $repoOC = $em->getRepository(Client::class);
        $firm = clone $repoO->findOneById($orgId);
        $clonedFirm = clone $firm;
        $clonedFirm->setCommname($firm->getCommname().'_Test')
            ->setLegalname($firm->getLegalname())
            ->setInserted(new \DateTime);
        $em->persist($clonedFirm);
        $em->flush();

        // Duplicate users (internal & external), & teams

        $firmUsers = $repoU->findByOrgId($firm->getId());

        $firmExternalUsers = $repoEU->findByOrganization($firm);

        $selectedWeight = null;
        $selectedDepartment = null;
        $selectedPosition = null;

        foreach($firm->getDepartments() as $department){
            $clonedDepartment = clone $department;
            $clonedDepartment->setInserted(new \DateTime);
            $clonedFirm->addDepartment($clonedDepartment);
            $clonedDepartments[] = $clonedDepartment;
            $originalDepartments[] = $department;
        }

        foreach($firm->getPositions() as $position){
            $clonedPosition = clone $position;
            $clonedPosition->setInserted(new \DateTime);
            $clonedFirm->addPosition($clonedPosition);
            $clonedPositions[] = $clonedPosition;
            $originalPositions[] = $position;
        }

        foreach($firm->getWeights() as $weight){
            $clonedWeight = clone $weight;
            $clonedWeight->setInserted(new \DateTime);
            $clonedFirm->addWeight($clonedWeight);
            $clonedWeights[] = $clonedWeight;
            $originalWeights[] = $weight;
        }

        $em->persist($clonedFirm);
        $em->flush();

        foreach($firmUsers as $firmUser){

            $userClonedDepartment = $clonedDepartments[array_search($firmUser->getDepartment($app),$originalDepartments)];
            $userClonedPosition = $clonedPositions[array_search($firmUser->getPosition($app),$originalPositions)];
            $userClonedWeight = $clonedWeights[array_search($firmUser->getWeight($app),$originalWeights)];

            $userClonedWeight
                ->setPosition($userClonedPosition);

            $em->persist($userClonedWeight);
            $em->flush();

            $clonedFirmUser = clone $firmUser;

            // We change user email address to avoid creating duplicates
            $oldEmail = $firmUser->getEmail();
            $prefix = explode("@", $oldEmail)[0].'_dev';
            $suffix = explode("@", $oldEmail)[1];
            $newEmail = implode("@",[$prefix,$suffix]);

            $clonedFirmUser->setOrgId($clonedFirm->getId())
                ->setDptId($userClonedDepartment->getId())
                ->setPosId($userClonedPosition->getId())
                ->setWgtId($userClonedWeight->getId())
                ->setEmail($newEmail)
                ->setLastConnected(null)
                ->setInserted(new \DateTime);
            $em->persist($clonedFirmUser);

            if($userClonedWeight->getUsrId() != null){
                $em->flush();
                $userClonedWeight->setUsrId($clonedFirmUser->getId());
                $em->persist($userClonedWeight);
            }

            $clonedUsers[] = $clonedFirmUser;
            $originalUsers[] = $firmUser;
        }

        $extOrgs = [];
        foreach($firmExternalUsers as $firmExternalUser){

            $clonedFirmExternalUser = clone $firmExternalUser;
            $clonedExternalUsers[] = $clonedFirmExternalUser;
            $originalExternalUsers[] = $firmExternalUser;

            $assocIntUser = $firmExternalUser->getUser();

            $assocOrgIntUser = $repoO->findOneById($assocIntUser->getOrgId());

            if(!in_array($assocOrgIntUser, $extOrgs)){

                $clonedAssocOrgIntUser = clone $assocOrgIntUser;
                $clonedAssocOrgIntUser->setMasterUserId(0)->setValidated(new \DateTime);

                $orgClient = $repoOC->findOneBy(['organization' => $firm, 'clientOrganization' => $assocOrgIntUser]);
                $clonedOrgClient = clone $orgClient;
                $clonedOrgClient->setOrganization($clonedFirm)
                    ->setClientOrganization($clonedAssocOrgIntUser);
                $em->persist($clonedOrgClient);
                $em->persist($clonedAssocOrgIntUser);
                $em->flush();
                $clonedExtOrgs[] = $clonedAssocOrgIntUser;
                $extOrgs[] = $assocOrgIntUser;
            } else {
                $clonedAssocOrgIntUser = $clonedExtOrgs[array_search($assocOrgIntUser,$extOrgs)];
            }

            $clonedAssocIntUser = clone $assocIntUser;
            $clonedAssocIntUser->setOrgId($clonedAssocOrgIntUser->getId())
                ->setPosId(null)
                ->setDptId(null)
                ->setWgtId(null);
            $em->persist($clonedAssocIntUser);

            $clonedUsers[] = $clonedAssocIntUser;
            $originalUsers[] = $assocIntUser;

            $clonedFirmExternalUser
                ->setUser($clonedUsers[array_search($firmExternalUser->getUser(),$originalUsers)])
                ->setOrganization($clonedFirm);

            $em->persist($clonedFirmExternalUser);

            if($assocOrgIntUser->getMasterUserId() == 0){
                $em->flush();
                $assocOrgIntUser->getMasterUserId($clonedAssocIntUser->getId());
                $em->persist($assocOrgIntUser);
                $em->flush();
            }

        }

        $em->flush();

        $clonedFirm->setMasterUserId($clonedUsers[array_search($repoU->findOneById($firm->getMasterUserId()),$originalUsers)]->getId());

        $firmTeams = $repoT->findByOrganization($firm);

        foreach($firmTeams as $team){
            $clonedTeam = clone $team;
            $clonedFirm->addTeam($clonedTeam);
            $clonedTeams[] = $clonedTeam;
            $originalTeams[] = $team;
            foreach($team->getTeamUsers() as $teamUser){
                $clonedTeamUser = clone $teamUser;
                $clonedTeamUser->setUsrId($clonedUsers[array_search($repoU->findOneById($teamUser->getUsrId()),$originalUsers)]->getId());
                $clonedTeam->addTeamUser($clonedTeamUser);
                $clonedTeamUsers[] = $clonedTeamUser;
                $originalTeamUsers[] = $teamUser;
            }
        }

        $em->persist($clonedFirm);
        $em->flush();

        // Duplicate activity elements

        $firmActivities = $repoA->findByOrganization($firm);
        foreach($firmActivities as $key => $activity){
            $clonedActivity = clone $activity;
            $clonedFirm->addActivity($clonedActivity);
            $clonedActivities[] = $clonedActivity;
            $originalActivities[] = $activity;

            foreach($activity->getStages() as $stage){
                $clonedStage = clone $stage;
                $clonedActivity->addStage($clonedStage);
                $clonedStages[] = $clonedStage;
                $originalStages[] = $stage;

                foreach($stage->getCriteria() as $criterion){
                    $clonedCriterion = clone $criterion;
                    $clonedStage->addCriterion($clonedCriterion)
                        ->setMasterUserId($clonedUsers[array_search($repoU->findOneById($stage->getMasterUserId()),$originalUsers)]->getId());
                    $clonedCriteria[] = $clonedCriterion;
                    $originalCriteria[] = $criterion;

                    foreach($criterion->getParticipants() as $participant){
                        $clonedParticipant = clone $participant;
                        $clonedParticipant->setActivity($clonedActivity)
                            ->setStage($clonedStage)
                            ->setUsrId($clonedUsers[array_search($repoU->findOneById($participant->getUsrId()),$originalUsers)]->getId());
                        if($participant->getTeam() != null){
                            $clonedParticipant->setTeam($clonedTeams[array_search($participant->getTeam(),$originalTeams)]);
                        }
                        $clonedCriterion->addParticipant($clonedParticipant);
                        $clonedParticipants[] = $clonedParticipant;
                        $originalParticipants[] = $participant;

                        foreach($participant->getGrades() as $grade){
                            $clonedGrade = clone $grade;
                            $clonedGrade
                                ->setCriterion($clonedCriterion)
                                ->setStage($clonedStage)
                                ->setActivity($clonedActivity);
                            $clonedParticipant->addGrade($clonedGrade);
                            if($grade->getTeam() != null){
                                $clonedGrade->setTeam($clonedTeams[array_search($grade->getTeam(),$originalTeams)]);
                            }
                            if($grade->getGradedUsrId() != null){
                                $clonedGrade->setGradedUsrId($clonedUsers[array_search($repoU->findOneById($grade->getGradedUsrId()),$originalUsers)]->getId());
                            }
                            if($grade->getGradedTeaId() != null){
                                $clonedGrade->setGradedTeaId($clonedTeams[array_search($repoT->findOneById($grade->getGradedTeaId()),$originalTeams)]->getId());
                            }
                            $clonedGrades[] = $clonedGrade;
                            $originalGrades[] = $grade;
                        }
                    }
                }
            }

            // Duplicate activity results & rankings

            foreach($activity->getResults() as $result){
                $clonedResult = clone $result;
                if($result->getStage() != null){
                    $clonedResult->setStage($clonedStages[array_search($result->getStage(),$originalStages)]);
                }
                if($result->getCriterion() != null){
                    $clonedResult->setCriterion($clonedCriteria[array_search($result->getCriterion(),$originalCriteria)]);
                }
                if($result->getUsrId() != null){
                    $clonedResult->setUsrId($clonedUsers[array_search($repoU->findOneById($result->getUsrId()),$originalUsers)]->getId());
                }
                $clonedActivity->addResult($clonedResult);
                $clonedResults[] = $clonedResult;
                $originalResults[] = $result;
            }

            foreach($activity->getResultTeams() as $resultTeam){
                $clonedResultTeam = clone $resultTeam;
                if($resultTeam->getStage() != null){
                    $clonedResultTeam->setStage($clonedStages[array_search($resultTeam->getStage(),$originalStages)]);
                }
                if($resultTeam->getCriterion() != null){
                    $clonedResultTeam->setCriterion($clonedCriteria[array_search($resultTeam->getCriterion(),$originalCriteria)]);
                }
                if($resultTeam->getTeam() != null){
                    $clonedResultTeam->setTeam($clonedTeams[array_search($resultTeam->getTeam(),$originalTeams)]);
                }
                $clonedActivity->addResultTeam($clonedResultTeam);
                $clonedResultTeams[] = $clonedResultTeam;
                $originalResultTeams[] = $resultTeam;
            }

            foreach($activity->getRankings() as $ranking){
                $clonedRanking = clone $ranking;
                if($ranking->getStage() != null){
                    $clonedRanking->setStage($clonedStages[array_search($ranking->getStage(),$originalStages)]);
                }
                if($result->getCriterion() != null){
                    $clonedRanking->setCriterion($clonedCriteria[array_search($ranking->getCriterion(),$originalCriteria)]);
                }
                if($result->getUsrId() != null){
                    $clonedRanking->setUsrId($clonedUsers[array_search($repoU->findOneById($ranking->getUsrId()),$originalUsers)]->getId());
                }
                $clonedActivity->addRanking($clonedRanking);
            }

            foreach($activity->getHistoricalRankings() as $hRanking){
                $clonedHRanking = clone $hRanking;
                if($hRanking->getStage() != null){
                    $clonedHRanking->setStage($clonedStages[array_search($hRanking->getStage(),$originalStages)]);
                }
                if($hRanking->getCriterion() != null){
                    $clonedHRanking->setCriterion($clonedCriteria[array_search($hRanking->getCriterion(),$originalCriteria)]);
                }
                if($hRanking->getUsrId() != null){
                    $clonedHRanking->setUsrId($clonedUsers[array_search($repoU->findOneById($hRanking->getUsrId()),$originalUsers)]->getId());
                }
                $clonedActivity->addHistoricalRanking($clonedHRanking);
            }

            foreach($activity->getRankingTeams() as $rankingTeam){
                $clonedRankingTeam = clone $rankingTeam;
                if($rankingTeam->getStage() != null){
                    $clonedRankingTeam->setStage($clonedStages[array_search($rankingTeam->getStage(),$originalStages)]);
                }
                if($resultTeam->getCriterion() != null){
                    $clonedRankingTeam->setCriterion($clonedCriteria[array_search($rankingTeam->getCriterion(),$originalCriteria)]);
                }
                if($resultTeam->getTeam() != null){
                    $clonedRankingTeam->setTeam($clonedTeams[array_search($rankingTeam->getTeam(),$originalTeams)]);
                }
                $clonedActivity->addRankingTeam($clonedRankingTeam);
            }

            foreach($activity->getHistoricalRankingTeams() as $hRankingTeam){
                $clonedHRankingTeam = clone $hRankingTeam;
                if($hRankingTeam->getStage() != null){
                    $clonedHRankingTeam->setStage($clonedStages[array_search($hRankingTeam->getStage(),$originalStages)]);
                }
                if($hRankingTeam->getCriterion() != null){
                    $clonedHRankingTeam->setCriterion($clonedCriteria[array_search($hRankingTeam->getCriterion(),$originalCriteria)]);
                }
                if($hRankingTeam->getTeam() != null){
                    $clonedHRankingTeam->setTeam($clonedTeams[array_search($hRankingTeam->getTeam(),$originalTeams)]);
                }
                $clonedActivity->addHistoricalRankingTeam($clonedHRankingTeam);
            }

            if($key % 10 == 0){
                $em->persist($clonedFirm);
                $em->flush();
            }

        }

        $em->persist($clonedFirm);
        $em->flush();

        return $app->redirect($app['url_generator']->generate('massiveUpdateOrganization', ['orgId' => $clonedFirm->getId()]));

    }

    public function massiveUpdateOrganizationAction(Request $request, Application $app, $orgId){

        $em = $this->getEntityManager($app);
        $repoO = $em->getRepository(Organization::class);
        $organization = $repoO->findOneById($orgId);
        $formFactory = $app['form.factory'];
        $organizationUsersForm = $formFactory->create(UpdateOrganizationForm::class, $organization, ['standalone' =>true,'organization' => $organization,'app'=> $app]);
        $organizationUsersForm->handleRequest($request);

        if($organizationUsersForm->isValid()){

            foreach($organizationUsersForm->get('orgUsers')->getData() as $key => $submittedUser){

                    if($submittedUser->getPassword() != null){
                        $encoder = $app['security.encoder_factory']->getEncoder($submittedUser);
                        $submittedUser->setPassword($encoder->encodePassword($submittedUser->getPassword(), 'azerty'));
                    } else {
                        $consideredUser = $organization->getOrgUsers()[$key];
                        $submittedUser->setPassword($consideredUser->getPassword());
                    }
                    $em->persist($submittedUser);
            }

            foreach($organizationUsersForm->get('orgExtUsers')->getData() as $key => $submittedUser){
                $extUserFirstName = $submittedUser->getFirstname();
                $extUserLastName = $submittedUser->getLastname();
                $assocIntUser = $organization->getOrgExtUsers()[$key]->getUser();
                $assocIntUser->setFirstname($extUserFirstName)
                    ->setLastname($extUserLastName);

                $em->persist($assocIntUser);
            }
            $em->flush();
            foreach ($repoO->findAll() as $organization) {
                $organizations[] = $organization->toArray($app);
            }
            return $app['twig']->render('organization_list.html.twig',['organizations' => $organizations]);
            //return $app->redirect($app['url_generator']->generate('manageOrganizations'));

        }

        return $app['twig']->render('organization_massive_update.html.twig',
        [
            'form' => $organizationUsersForm->createView(),
            'organization' => $organization,
        ]);

    }

    public function displayWorkerElmts(Request $request, Application $app, $currentPage = 1, $limit = 100){

        $em = $this->getEntityManager($app);
        $repoWE = $em->getRepository(WorkerExperience::class);
        $formFactory = $app['form.factory'];
        $searchWorkerForm = $formFactory->create(SearchWorkerForm::class, null,['app' => $app]);
        $validateFirmForm = $formFactory->create(ValidateFirmForm::class, null, ['standalone' => true]);
        $validateMailForm = $formFactory->create(ValidateMailForm::class, null, ['standalone' => true]);
        $sendMailProspectForm = $formFactory->create(SendMailProspectForm::class, null, ['standalone' => true]);
        $searchWorkerForm->handleRequest($request);

        $validateMassFirmForm = $formFactory->create(ValidateMassFirmForm::class, null, ['standalone' => true, 'firms' => $searchedWorkerFirms]);
            $validateMassFirmForm->handleRequest($request);

        return $app['twig']->render('worker_search.html.twig',
        [
            'form' => $searchWorkerForm->createView(),
            'validateFirmForm' => $validateFirmForm->createView(),
            'validateMailForm' => $validateMailForm->createView(),
            'sendMailProspectForm' => $sendMailProspectForm->createView(),
            'validateMassFirmForm' =>  $validateMassFirmForm->createView(),
            'wfIdsSeq' => $workerFirmIdsSequence,
        ]);

        //return new JsonResponse(['searchedWorkers' => $searchedWorkers],200);

    }

    public function findWorkerElmts(Request $request, Application $app, $currentPage = 1, $limit = 500){

        $em = $this->getEntityManager($app);
        $repoWE = $em->getRepository(WorkerExperience::class);
        $formFactory = $app['form.factory'];
        $searchWorkerForm = $formFactory->create(SearchWorkerForm::class, null,['app' => $app]);
        $validateFirmForm = $formFactory->create(ValidateFirmForm::class, null, ['standalone' => true]);
        $validateMailForm = $formFactory->create(ValidateMailForm::class, null, ['standalone' => true]);
        $sendMailProspectForm = $formFactory->create(SendMailProspectForm::class, null, ['standalone' => true]);
        $searchWorkerForm->handleRequest($request);
        $searchedWorkerFirms = null;
        $searchedWorkerIndividuals = null;
        $searchedWorkerExperiences = null;
        $isSearchByLocation = false;
        $workerFirmIdsSequence = '0';
        if($searchWorkerForm->get('fullName')->getData() === '' && $searchWorkerForm->get('firmName')->getData() === '' && $searchWorkerForm->get('position')->getData() === ''){
            $searchWorkerForm->get('submit')->addError(new FormError('There must be at least one filled criterion to look for'));
        }
        if($searchWorkerForm->isValid()){

            $qb = $em->createQueryBuilder();

            if($searchWorkerForm->get('position')->getData() != ''){



                $qb->select('we')
                ->from('Model\WorkerExperience','we')
                ->innerJoin('Model\WorkerFirm', 'wf', 'WITH', 'we.firm = wf.id')
                ->innerJoin('Model\WorkerIndividual', 'wi', 'WITH', 'we.individual = wi.id')
                //->where('au.status = 4')
                ->where('we.position LIKE :position')
                ->andWhere('wf.name LIKE :firm')
                ->andWhere('wi.fullName LIKE :indiv');
                if($searchWorkerForm->get('currentOnly')->getData() == 1){
                    $qb->andWhere('we.active = 1');
                }
                if($searchWorkerForm->get('HQLocation')->getData() != ''){
                    $qb->andWhere('wf.HQLocation LIKE :HQLocation');
                }
                if($searchWorkerForm->get('country')->getData() != ''){
                    $qb->andWhere('wf.country = :country');
                }

                if($searchWorkerForm->get('state')->getData() != ''){
                    $qb->andWhere('wf.state = :state');
                }

                if($searchWorkerForm->get('city')->getData() != ''){
                    $qb->andWhere('wf.city = :city');
                }

                $qb->setParameter('position', '%'.$searchWorkerForm->get('position')->getData().'%')
                ->setParameter('firm', '%'.$searchWorkerForm->get('firmName')->getData().'%')
                ->setParameter('indiv', '%'.$searchWorkerForm->get('fullName')->getData().'%');


                if($searchWorkerForm->get('country')->getData() != ''){
                    $qb->setParameter('country',$searchWorkerForm->get('country')->getData());
                }

                if($searchWorkerForm->get('state')->getData() != ''){
                    $qb->setParameter('state', $searchWorkerForm->get('state')->getData());
                }

                if($searchWorkerForm->get('city')->getData() != ''){
                    $qb->setParameter('city', $searchWorkerForm->get('city')->getData());
                }

                if($searchWorkerForm->get('HQLocation')->getData() != ''){
                    $qb->setParameter('HQLocation', '%'.$searchWorkerForm->get('HQLocation')->getData().'%');
                }

                $qb2 = $em->createQueryBuilder();
                $qb2->select('count(we.id)')
                ->from('Model\WorkerExperience','we')
                ->innerJoin('Model\WorkerFirm', 'wf', 'WITH', 'we.firm = wf.id')
                ->innerJoin('Model\WorkerIndividual', 'wi', 'WITH', 'we.individual = wi.id')
                //->where('au.status = 4')
                ->where('we.position LIKE :position')
                ->andWhere('wf.name LIKE :firm')
                ->andWhere('wi.fullName LIKE :indiv');


                if($searchWorkerForm->get('currentOnly')->getData() == 1){
                    $qb2->andWhere('we.active = 1');
                }
                if($searchWorkerForm->get('HQLocation')->getData() != ''){
                    $qb->andWhere('wf.HQLocation LIKE :HQLocation');
                }
                if($searchWorkerForm->get('country')->getData() != ''){
                    $qb->andWhere('wf.country = :country');
                }

                if($searchWorkerForm->get('state')->getData() != ''){
                    $qb->andWhere('wf.state = :state');
                }

                if($searchWorkerForm->get('city')->getData() != ''){
                    $qb->andWhere('wf.city = :city');
                }

                $qb2->setParameter('position', '%'.$searchWorkerForm->get('position')->getData().'%')
                ->setParameter('firm', '%'.$searchWorkerForm->get('firmName')->getData().'%')
                ->setParameter('indiv', '%'.$searchWorkerForm->get('fullName')->getData().'%');

                if($searchWorkerForm->get('country')->getData() != ''){
                    $qb->setParameter('country',$searchWorkerForm->get('country')->getData());
                }

                if($searchWorkerForm->get('state')->getData() != ''){
                    $qb->setParameter('state', $searchWorkerForm->get('state')->getData());
                }

                if($searchWorkerForm->get('city')->getData() != ''){
                    $qb->setParameter('city', $searchWorkerForm->get('city')->getData());
                }

                if($searchWorkerForm->get('HQLocation')->getData() != ''){
                    $qb->setParameter('HQLocation', '%'.$searchWorkerForm->get('HQLocation')->getData().'%');
                }


                $count = $qb2->getQuery()->getSingleScalarResult();

                //->setParameter('expTypes', $searchWorkerForm->get('currentOnly')->getData() == 1 ? '[true]' : '[false,true]');



                $searchedWorkerExperiences = new ArrayCollection(
                    $qb->setFirstResult($limit * ($currentPage - 1))
                        ->setMaxResults($limit)
                        ->getQuery()->getResult()
                );


                $iterator = $searchedWorkerExperiences->getIterator();

                $iterator->uasort(function ($first, $second) {
                    return ($first->getIndividual()->getExperiences()->last()->getStartDate() > $second->getIndividual()->getExperiences()->last()->getStartDate()) ? 1 : -1;
                });

                $searchedWorkerExperiences = new ArrayCollection(iterator_to_array($iterator));

            }

            /*if($searchWorkerForm->get('fullName')->getData() == '' && $searchWorkerForm->get('position')->getData() == '' && $searchWorkerForm->get('firmName')->getData() != ''){

                $searchedWorkerFirms = new ArrayCollection($qb->select('wf')
                ->from('Model\WorkerFirm', 'wf')
                ->where('wf.name LIKE :firmName')
                ->setParameter('firmName', '%'.$searchWorkerForm->get('firmName')->getData().'%')
                ->getQuery()
                ->getResult());

                $iterator = $searchedWorkerFirms->getIterator();

                $iterator->uasort(function ($first, $second) {
                    return (count($first->getActiveExperiences()) < count($second->getActiveExperiences())) ? 1 : -1;
                });

                $searchedWorkerFirms = new ArrayCollection(iterator_to_array($iterator));



            }*/

            if($searchWorkerForm->get('fullName')->getData() != '' && $searchWorkerForm->get('position')->getData() == '' && $searchWorkerForm->get('firmName')->getData() == ''){

                $searchedWorkerIndividuals = new ArrayCollection($qb->select('wi')
                ->from('Model\WorkerIndividual', 'wi')
                ->where('wi.fullName LIKE :fullName')
                ->setParameter('fullName', '%'.$searchWorkerForm->get('fullName')->getData().'%')
                ->getQuery()
                ->getResult());

                $iterator = $searchedWorkerIndividuals->getIterator();

                $iterator->uasort(function ($first, $second) {
                    if(count($first->getExperiences()) == 0 || count($second->getExperiences()) == 0 ){
                        return -1;
                    } else {
                        return ($first->getExperiences()->last()->getStartDate() > $second->getExperiences()->last()->getStartDate()) ? 1 : -1;
                    }
                });

                $searchedWorkerIndividuals = new ArrayCollection(iterator_to_array($iterator));

            }

            if($searchWorkerForm->get('fullName')->getData() == '' && $searchWorkerForm->get('position')->getData() == ''){

                $qb->select('wf')
                ->from('Model\WorkerFirm', 'wf')
                ->where('wf.name LIKE :firmName');




                if($searchWorkerForm->get('country')->getData() != ''){
                    $qb->andWhere('wf.country = :country');
                }

                if($searchWorkerForm->get('state')->getData() != ''){
                    $qb->andWhere('wf.state = :state');
                }

                if($searchWorkerForm->get('city')->getData() != ''){
                    $qb->andWhere('wf.city = :city');
                }

                if($searchWorkerForm->get('HQLocation')->getData() != ''){
                    $qb->andWhere('wf.HQLocation LIKE :HQLocation');
                }

                if($searchWorkerForm->get('fSizeFrom')->getData() != -1){
                    if($searchWorkerForm->get('fSizeTo')->getData() == -1){
                        $qb->andWhere('wf.size >= :fSizeFrom AND wf.size IS NULL');
                    } else {
                        $qb->andWhere('wf.size BETWEEN :fSizeFrom AND :fSizeTo');
                    }

                } else {
                    if($searchWorkerForm->get('fSizeTo')->getData() != -1){
                        $qb->andWhere('wf.size <= :fSizeFrom AND wf.size IS NULL');
                    }
                }

                if($searchWorkerForm->get('fSector')->getData() != 0){
                    $qb->andWhere('wf.mainSector = :fSector');
                }

                if($searchWorkerForm->get('fType')->getData() != 99){
                    $qb->andWhere('wf.firmType = :fType');
                }

                $qb->orderBy('wf.nbLKEmployees','DESC');

                $qb->setParameter('firmName', '%'.$searchWorkerForm->get('firmName')->getData().'%');

                if($searchWorkerForm->get('fSizeFrom')->getData() != -1){
                    $qb->setParameter('fSizeFrom', $searchWorkerForm->get('fSizeFrom')->getData());
                }
                if($searchWorkerForm->get('fSizeTo')->getData() != -1){
                    $qb->setParameter('fSizeTo', $searchWorkerForm->get('fSizeTo')->getData());
                }

                if($searchWorkerForm->get('fSector')->getData() != 0){
                    $qb->setParameter('fSector', $searchWorkerForm->get('fSector')->getData());
                }

                if($searchWorkerForm->get('fType')->getData() != 99){
                    $qb->setParameter('fType', $searchWorkerForm->get('fType')->getData());
                }

                if($searchWorkerForm->get('country')->getData() != ''){
                    $qb->setParameter('country',$searchWorkerForm->get('country')->getData());
                }

                if($searchWorkerForm->get('state')->getData() != ''){
                    $qb->setParameter('state', $searchWorkerForm->get('state')->getData());
                }

                if($searchWorkerForm->get('city')->getData() != ''){
                    $qb->setParameter('city', $searchWorkerForm->get('city')->getData());
                }

                if($searchWorkerForm->get('HQLocation')->getData() != ''){
                    $qb->setParameter('HQLocation', '%'.$searchWorkerForm->get('HQLocation')->getData().'%');
                }

                $searchedWorkerFirms = new ArrayCollection($qb->getQuery()
                ->getResult());

                /*$iterator = $searchedWorkerFirms->getIterator();

                $iterator->uasort(function ($first, $second) {
                    return (count($first->getNbActiveExperiences()) < count($second->getNbActiveExperiences())) ? 1 : -1;
                });

                $searchedWorkerFirms = new ArrayCollection(iterator_to_array($iterator));*/
                $i = 0;
                foreach($searchedWorkerFirms as $searchedWorkerFirm){
                    if($i != 0){
                        $workerFirmIdsSequence .= '-'.$searchedWorkerFirm->getId();
                    } else {
                        $workerFirmIdsSequence = $searchedWorkerFirm->getId();
                    }
                    $i++;
                }



            }

            if($searchWorkerForm->get('HQLocation')->getData() != ''){
                $isSearchByLocation = true;
            }

        }

        $validateMassFirmForm = $formFactory->create(ValidateMassFirmForm::class, null, ['standalone' => true, 'searchByLocation' => $isSearchByLocation, 'firms' => $searchedWorkerFirms]);
            $validateMassFirmForm->handleRequest($request);

        return $app['twig']->render('worker_search.html.twig',
        [
            'form' => $searchWorkerForm->createView(),
            'searchedWorkerIndividuals' => $searchedWorkerIndividuals,
            'searchedWorkerFirms' => $searchedWorkerFirms,
            'searchedWorkerExperiences' => $searchedWorkerExperiences,
            'validateFirmForm' => $validateFirmForm->createView(),
            'validateMailForm' => $validateMailForm->createView(),
            'sendMailProspectForm' => $sendMailProspectForm->createView(),
            'validateMassFirmForm' =>  $validateMassFirmForm->createView(),
            'wfIdsSeq' => $workerFirmIdsSequence,
            'searchByLocation' => $isSearchByLocation,
        ]);

        //return new JsonResponse(['searchedWorkers' => $searchedWorkers],200);

    }

    public function getStatesFromCountry(Request $request, Application $app, $couId){
        $em = $this->getEntityManager($app);
        $repoC = $em->getRepository(Country::class);
        $repoS = $em->getRepository(State::class);
        $repoCI = $em->getRepository(City::class);
        if($couId != 0){
            $country = $repoC->findOneById($couId);
            $states = $repoS->findByCountry($country);
        } else {
            $states = $repoS->findAll();
        }
        $statesData = [];
        $citiesData = [];

        foreach($states as $state){

            $cities = $repoCI->findByState($state);
            foreach($cities as $city){
                $cityData = [];
                $cityData['value'] = $city->getId();
                $cityData['key'] = $city->getName();
                $citiesData[] = $cityData;
            }

            $stateData = [];
            $stateData['value'] = $state->getId();
            $stateData['key'] = $state->getName();
            $statesData[] = $stateData;
        }
        return new JsonResponse(['states' => $statesData,'cities' => $citiesData],200);
    }

    public function getCitiesFromState(Request $request, Application $app, $staId){
        $em = $this->getEntityManager($app);
        $repoS = $em->getRepository(State::class);
        $repoC = $em->getRepository(City::class);
        if($staId != 0){
            $state = $repoS->findOneById($staId);
            $cities = $repoC->findByState($state);
        } else {
            $cities = $repoC->findAll();
        }
        $citiesData = [];
        foreach($cities as $city){
            $cityData = [];
            $cityData['value'] = $city->getId();
            $cityData['key'] = $city->getName();
            $citiesData[] = $cityData;
        }
        return new JsonResponse(['cities' => $citiesData],200);
    }

    public function validateMailSent(Request $request, Application $app, $mid){
        $em = $this->getEntityManager($app);
        $repoM = $em->getRepository(Mail::class);
        $mail = $repoM->findOneById($mid);
        $mail->setRead(new \DateTime);
        $em->persist($mail);
        $em->flush();
        return new JsonResponse(['message' => 'success'],200);
    }

    public function deactivateMail(Request $request, Application $app, $mid){
        $em = $this->getEntityManager($app);
        $repoM = $em->getRepository(Mail::class);
        $mail = $repoM->findOneById($mid);
        $workerIndividual = $mail->getWorkerIndividual();
        $workerIndividual->setGDPR(new \DateTime);
        $em->persist($workerIndividual);
        $em->flush();
        return new JsonResponse(['message' => 'success'],200);
    }

    public function deleteMailSent(Request $request, Application $app, $mid){
        $em = $this->getEntityManager($app);
        $repoM = $em->getRepository(Mail::class);
        $mail = $repoM->findOneById($mid);
        $em->remove($mail);
        $em->flush();
        return new JsonResponse(['message' => 'success'],200);
    }

    public function createWorkerFirm(Request $request, Application $app){
        $em = $this->getEntityManager($app);
        $name = $request->get('name');
        $workerFirm = new WorkerFirm;
        $workerFirm->setName($name)->setActive(true);
        $em->persist($workerFirm);
        $em->flush();
        return new JsonResponse(['wfId' => $workerFirm->getId()],200);

    }


    public function updateWorkerFirm(Request $request, Application $app, $wfId)
    {
        $currentUser = MasterController::getAuthorizedUser($app);
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $em = $this->getEntityManager($app);
        $repoWF = $em->getRepository(WorkerFirm::class);

        $workerFirm = $repoWF->findOneById($wfId);
        if($workerFirm == null){
            $workerFirm = new WorkerFirm;
        }
        $formFactory = $app['form.factory'];
        $updateWorkerFirmForm = $formFactory->create(UpdateWorkerFirmForm::class, $workerFirm, ['standalone' => true, 'app' => $app, 'workerFirm' => $workerFirm]);
        $updateWorkerFirmForm->handleRequest($request);

        if($updateWorkerFirmForm->isValid()){

            $repoWFS = $em->getRepository(WorkerFirmSector::class);
            $repoCO = $em->getRepository(Country::class);
            $repoCI = $em->getRepository(City::class);
            $repoS = $em->getRepository(State::class);
            $country = null;
            $state = null;
            $city = null;

            $repoWFS = $em->getRepository(WorkerFirmSector::class);
            $country = $repoCO->findOneByAbbr($updateWorkerFirmForm->get('HQCountry')->getData());

            if($updateWorkerFirmForm->get('HQState')->getData() != null){
                $state = $repoS->findOneByName($updateWorkerFirmForm->get('HQState')->getData());
                if($state == null){
                    $state = new State;
                    $state->setCountry($country)->setName($updateWorkerFirmForm->get('HQState')->getData())->setCreatedBy($currentUser->getId());
                    $em->persist($state);
                }
            }

            if($updateWorkerFirmForm->get('HQCity')->getData() != null)
            $city = $repoCI->findOneByName($updateWorkerFirmForm->get('HQCity')->getData());
            if($city == null){
                $city = new City;
                $city->setCountry($country)->setState($state)->setName($updateWorkerFirmForm->get('HQCity')->getData())->setCreatedBy($currentUser->getId());
                $em->persist($city);
            }

            $workerFirm
                ->setMainSector($repoWFS->findOneById($updateWorkerFirmForm->get('mainSector')->getData()))
                ->setCountry($country)
                ->setState($state)
                ->setCity($city);

            if($wfId == 0){
                $workerFirm->setCreated(1)->setName($updateWorkerFirmForm->get('commonName')->getData());
            }

            $em->persist($workerFirm);
            $em->flush();

            return $app->redirect($app['url_generator']->generate('displayWorkerFirm',['wfId' => $workerFirm->getId()]));
        }

        return $app['twig']->render('worker_firm_data.html.twig',
        [
            'form' => $updateWorkerFirmForm->createView(),
            'wFirm' => $workerFirm,
        ]);

    }

    public function dynamicSearchParentFirm(Request $request, Application $app){


        $firmName = $request->get('name');

        $em = $this->getEntityManager($app);
        $repoWF = $em->getRepository(WorkerFirm::class);
        $qb = $em->createQueryBuilder();
        $firms = new ArrayCollection($qb->select('wf')
        ->from('Model\WorkerFirm', 'wf')
        ->where('wf.name LIKE :firmName')
        ->andWhere('wf.active = true AND wf.nbActiveExperiences > 0')
        ->setParameter('firmName', '%'.$firmName.'%')
        ->orderBy('wf.nbActiveExperiences','DESC')
        ->getQuery()
        ->getResult());

        if(sizeof($firms) == 0){
            $firms = new ArrayCollection($qb/*->select('wf')
            ->from('Model\WorkerFirm','wf')*/
            ->where('wf.name LIKE :firmName')
            ->andWhere('wf.active = true')
            ->setParameter('firmName', '%'.$firmName.'%')
            ->orderBy('wf.nbActiveExperiences','DESC')
            ->getQuery()
            ->getResult());
        }

        $workerFirms = [];
        foreach($firms as $firm){
            $workerFirm = ['id' => $firm->getId(), 'name' => $firm->getName(),'logo' => $firm->getLogo()];
            $workerFirms[] = $workerFirm;
        }

        //$workerFirms = array_combine($values,$keys);
        return new JsonResponse(['workerFirms' => $workerFirms],200);

    }

    public function getFirmFromId(Request $request, Application $app, $wfiId){
        $em = $this->getEntityManager($app);
        $repoWF = $em->getRepository(WorkerFirm::class);
        $firm = $repoWF->findOneById($wfiId);
        return new JsonResponse(['firmName' => $firm->getName()],200);
    }

    public function updateWorkerIndividual(Request $request, Application $app, $wiId){

        $em = $this->getEntityManager($app);
        $repoWI = $em->getRepository(WorkerIndividual::class);
        $repoWF = $em->getRepository(WorkerFirm::class);
        $workerIndividual = $repoWI->findOneById($wiId);
        $formFactory = $app['form.factory'];
        $expFirm = null;
        $mailPrefix = null;
        $mailSuffix = null;
        if(count($workerIndividual->getExperiences()) > 0){
            $expFirm = $workerIndividual->getExperiences()->first()->getFirm();
            $mailPrefix = $expFirm->getMailPrefix();
            $mailSuffix = $expFirm->getMailSuffix();
        }
        $updateWorkerIndividualForm = $formFactory->create(UpdateWorkerIndividualForm::class, $workerIndividual, ['workerIndividual' => $workerIndividual, 'mailPrefix' => $mailPrefix, 'mailSuffix' => $mailSuffix, 'standalone' => true]);
        $sendMailProspectForm = $formFactory->create(SendMailProspectForm::class, null, ['standalone' => true]);
        $updateWorkerIndividualForm->handleRequest($request);
        if($updateWorkerIndividualForm->isSubmitted()){
            if($updateWorkerIndividualForm->isValid()){
                foreach($updateWorkerIndividualForm->get('experiences') as $key => $experienceForm){
                    $workerIndividual->getExperiences()->get($key)->setFirm($repoWF->findOneById((int) $experienceForm->get('firm')->getData()));
                    if($experienceForm->get('enddate')->getData() == null){
                        $workerIndividual->getExperiences()->get($key)->setEnddate(new \DateTime);
                    }
                }
                //die;

                $em->persist($workerIndividual);
                $em->flush();
                if($expFirm != null){
                    return $app->redirect($app['url_generator']->generate('displayWorkerFirm',['wfId' => $expFirm->getId()]));
                } else {
                    return $app->redirect($app['url_generator']->generate('findWorkerElmts'));
                }
            } else {
                $errors = $this->buildErrorArray($updateWorkerIndividualForm);
                return $errors;
            }
        }

        return $app['twig']->render('worker_individual_data.html.twig',
        [
            'workerIndividual' => $workerIndividual,
            'form' => $updateWorkerIndividualForm->createView(),
            'sendMailForm' => $sendMailProspectForm->createView()
        ]);
    }

    public function setReadEmail(Request $request, Application $app, $mailId){
        $em = $this->getEntityManager($app);
        $repoM = $em->getRepository(Mail::class);
        $mail = $repoM->findOneBy($mailId);
        $mail->setRead(new \DateTime);
        $em->persist($mail);
        $em->flush();
        return true;
    }

    public function sendProspectMail(Request $request, Application $app, $winId){
        $em = $this->getEntityManager($app);
        $repoWI = $em->getRepository(WorkerIndividual::class);
        $workerIndividual = $repoWI->findOneById($winId);
        $firmLocation = $workerIndividual->getExperiences()->first()->getFirm()->getCountry()->getAbbr();
        $formFactory = $app['form.factory'];
        $sendMailProspectForm = $formFactory->create(SendMailProspectForm::class, null, ['standalone' => true]);
        $sendMailProspectForm->handleRequest($request);
        if($sendMailProspectForm->isValid()){
            $settings = [];
            $recipients = [];
            $recipients[] = $workerIndividual;
            $settings['location'] = $firmLocation;
            $settings['pType'] = $sendMailProspectForm->get('pType')->getData();
            $settings['language'] = $sendMailProspectForm->get('language')->getData();
            $settings['addPresFR'] = $sendMailProspectForm->get('addPresentationFR')->getData();
            $settings['addPresEN'] = $sendMailProspectForm->get('addPresentationEN')->getData();

            MasterController::sendMail($app, $recipients, 'prospecting_1', $settings);
            return new JsonResponse(['message' => 'success'],200);
        } else {
            $errors = $this->buildErrorArray($sendMailProspectForm);
            return $errors;
        }

    }

    public function checkMails(Request $request, Application $app){
        $em = $this->getEntityManager($app);
        $repoM = $em->getRepository(Mail::class);
        $mails = $repoM->findBy([],['inserted' => 'DESC']);
        return $app['twig']->render('check_mails.html.twig',
        [
            'mails' => $mails,
            'app' => $app,
        ]);
    }

    public function addWorkerFirmIndividual(Request $request, Application $app, $wfId){
        $em = $this->getEntityManager($app);
        $workerIndividual = new WorkerIndividual;
        $workerExperience = new WorkerExperience;
        $workerExperience->setActive(true);

        // We force the creation of a fictious experience

        $repoWF = $em->getRepository(WorkerFirm::class);
        $workerFirm = $repoWF->findOneById($wfId);
        $workerIndividual->setCreated(1)->addExperience($workerExperience);
        $workerFirm->addExperience($workerExperience);


        //return print_r($workerIndividual->getExperiences()->get(0)->getInserted());

        //$em->persist($workerExperience)
        //$em->flush()
        $formFactory = $app['form.factory'];
        $workerIndividualForm = $formFactory->create(UpdateWorkerIndividualForm::class, $workerIndividual, ['standalone' => true]);
        $sendMailProspectForm = $formFactory->create(SendMailProspectForm::class, null, ['standalone' => true]);
        $sendMailProspectForm->handleRequest($request);
        $workerIndividualForm->handleRequest($request);
        if($workerIndividualForm->isSubmitted()){
            if($workerIndividualForm->isValid()){
                //$workerIndividualData = $workerIndividualForm->getData();
                $workerIndividual->setCreated(1);

                //$experiences = $workerIndividualForm->get('experiences')->getData();
                foreach($workerIndividualForm->get('experiences') as $key => $experienceForm){
                    //if(count($experiences) > 1){
                        $workerIndividual->getExperiences()->get($key)->setFirm($repoWF->findOneById((int) $experienceForm->get('firm')->getData()));
                    //} else {
                        //$experiences->setFirm($repoWF->findOneById((int) $experienceForm->get('firm')->getData()));
                    //}
                }
                $em->persist($workerIndividual);
                $em->persist($workerFirm);
                $em->flush();
                return $app->redirect($app['url_generator']->generate('displayWorkerFirm',['wfId' => $wfId]));
            }
        }

        return $app['twig']->render('worker_individual_data.html.twig',
        [
            'form' => $workerIndividualForm->createView(),
            'sendMailForm' => $sendMailProspectForm->createView(),
        ]);
    }

    public function addWorkerIndividual(Request $request, Application $app){

        $em = $this->getEntityManager($app);
        $workerIndividual = new WorkerIndividual;
        $repoWF = $em->getRepository(WorkerFirm::class);
        $workerExperience = new WorkerExperience;
        $workerExperience->setActive(true);
        $workerIndividual->setCreated(1)->addExperience($workerExperience);

        $formFactory = $app['form.factory'];
        $workerIndividualForm = $formFactory->create(UpdateWorkerIndividualForm::class, $workerIndividual, ['standalone' => true]);
        $sendMailProspectForm = $formFactory->create(SendMailProspectForm::class, null, ['standalone' => true]);
        $sendMailProspectForm->handleRequest($request);
        $workerIndividualForm->handleRequest($request);
        if($workerIndividualForm->isSubmitted()){
            if($workerIndividualForm->isValid()){
                $workerIndividual = $workerIndividualForm->getData();
                $workerIndividual->setCreated(1);
                foreach($workerIndividualForm->get('experiences') as $key => $experienceForm){
                    $wfId = (int) $experienceForm->get('firm')->getData();
                    $workerIndividual->getExperiences()->get($key)->setFirm($repoWF->findOneById($wfId));
                }
                $em->persist($workerIndividual);
                $em->flush();
                return $app->redirect($app['url_generator']->generate('displayWorkerFirm',['wfId' => $wfId]));
            }
        }

        return $app['twig']->render('worker_individual_data.html.twig',
        [
            'form' => $workerIndividualForm->createView(),
            'sendMailForm' => $sendMailProspectForm->createView(),
        ]);

    }

    public function deleteWorkerIndividual(Request $request, Application $app, $wiId){

        $connectedUser = MasterController::getAuthorizedUser($app);
        if($connectedUser->getRole() != 4){
            return $app['twig']->render('errors/403.html.twig');
        }
        $em = $this->getEntityManager($app);
        $repoWI = $em->getRepository(WorkerIndividual::class);
        $workerIndividual = $repoWI->findOneById($wiId);
        $em->remove($workerIndividual);
        $em->flush();
        return new JsonResponse(['message' => "Success"],200);
    }

    public function deleteWorkerFirm(Request $request, Application $app, $wfId){

        $connectedUser = MasterController::getAuthorizedUser($app);
        if($connectedUser->getRole() != 4){
            return $app['twig']->render('errors/403.html.twig');
        }
        $em = $this->getEntityManager($app);
        $repoWF = $em->getRepository(WorkerFirm::class);
        $workerFirm = $repoWF->findOneById($wfId);
        $em->remove($workerFirm);
        $em->flush();
        return new JsonResponse(['message' => "Success"],200);
    }

    public function validateWorkerEmail(Request $request, Application $app, $wiId){
        $em = $this->getEntityManager($app);
        $repoWI = $em->getRepository(WorkerIndividual::class);
        $workerIndividual = $repoWI->findOneById($wiId);
        $formFactory = $app['form.factory'];
        $validateMailForm = $formFactory->create(ValidateMailForm::class, $workerIndividual, ['standalone' => true]);
        $validateMailForm->handleRequest($request);

        if($validateMailForm->isValid()){
            $em->persist($workerIndividual);
            $em->flush();
            return new JsonResponse(['message' => 'success', 'email' => $workerIndividual->getEmail()],200);
        } else {
            $errors = $this->buildErrorArray($validateMailForm);
            return $errors;
        }

    }

    public function validateMassWorkerEmails(Request $request, Application $app, $wfId){
        $em = $this->getEntityManager($app);

        $formFactory = $app['form.factory'];
        $validateMassMailForm = $formFactory->create(ValidateMassMailForm::class, null, ['standalone' => true]);
        $validateMassMailForm->handleRequest($request);

        if($validateMassMailForm->isValid()){
            $mails = [];
            $repoWF = $em->getRepository(WorkerFirm::class);
            $workerFirm = $repoWF->findOneById($wfId);
            $workingIndividuals = $workerFirm->getWorkingIndividuals();
            foreach($validateMassMailForm->get('workingIndividuals') as $key => $workingIndividualForm){
                $workingIndividualFormData = $workingIndividualForm->getData();
                $workingIndividual = $workingIndividuals->get($key);
                $workingIndividual->setFirstname($workingIndividualFormData->getFirstname())
                    ->setLastname($workingIndividualFormData->getLastname())
                    ->setEmail($workingIndividualFormData->getEmail())
                    ->setMale($workingIndividualFormData->isMale());
                $mails[] = $workingIndividualFormData->getEmail();
                $em->persist($workingIndividual);

            }
            $em->flush();
            return new JsonResponse(['message' => 'success', 'emails' => $mails],200);
        } else {
            $errors = $this->buildErrorArray($validateMassMailForm);
            return $errors;
        }

    }

    public function setOrganizationToCriteriaAndStages(Request $request, Application $app, $orgId){

        $em = $this->getEntityManager($app);
        $repoC = $em->getRepository(Criterion::class);
        $repoO = $em->getRepository(Organization::class);
        $organization = $repoO->findOneById($orgId);
        $activities = $organization->getActivities();
        foreach($activities as $activity){
            foreach($activity->getStages() as $stage){
                $stage->setOrganization($organization);
                $em->persist($stage);
                foreach($stage->getCriteria() as $criterion){
                    $criterion->setOrganization($organization);
                    $em->persist($criterion);
                }
            }
        }
        $em->flush();
        return $app->redirect($app['url_generator']->generate('manageOrganizations'));
    }

    public function validateMassFirm(Request $request, Application $app, $isSearchByLocation, $wfIdsSeq)
    {
        $currentUser = MasterController::getAuthorizedUser($app);
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }

        $formFactory = $app['form.factory'];

        $workerFirmIds = explode("-",$wfIdsSeq);
        $em = $this->getEntityManager($app);
        $repoWF = $em->getRepository(WorkerFirm::class);
        $workerFirms = [];
        foreach($workerFirmIds as $workerFirmId){
            $workerFirms[] = $repoWF->findOneById($workerFirmId);
        }
        $validateMassFirmForm = $formFactory->create(ValidateMassFirmForm::class, null, ['standalone' => true, 'searchByLocation' => $isSearchByLocation, 'firms' => $workerFirms]);
        $validateMassFirmForm->handleRequest($request);

        if($validateMassFirmForm->isValid()){

            $repoCO = $em->getRepository(Country::class);
            $repoCI = $em->getRepository(City::class);
            $repoS = $em->getRepository(State::class);

            foreach($validateMassFirmForm->get('firms') as $key => $workerFirmForm){

                $workerFirmFormData = $workerFirmForm->getData();
                //$workerFirm = $workerFirmForm->getData();
                $workerFirm = $workerFirms[$key];
                $country = null;
                $state = null;
                $city = null;


                $workerFirm->setCommonName($workerFirmFormData->getCommonName())
                    ->setMailPrefix($workerFirmFormData->getMailPrefix())
                    ->setMailSuffix($workerFirmFormData->getMailSuffix());

                //$workerFirm->setHQCountry($workerFirmFormData->getHQCountry());
                $country = $repoCO->findOneByAbbr($workerFirmFormData->getHQCountry());

                if($workerFirmFormData->getHQState() != ''){
                    $state = $repoS->findOneByName($workerFirmFormData->getHQState());
                    if($state == null){
                        $state = new State;
                        $state->setCountry($country)->setName($workerFirmFormData->getHQState())->setCreatedBy($currentUser->getId());
                        $em->persist($state);
                    }
                    //$workerFirm->setHQState($workerFirmFormData->getHQState());
                }

                if($workerFirmFormData->getHQCity() != ''){
                    $city = $repoCI->findOneByName($workerFirmFormData->getHQCity());
                    if($city == null){
                        $city = new City;
                        $city->setCountry($country)->setState($state)->setName($workerFirmFormData->getHQCity())->setCreatedBy($currentUser->getId());
                        $em->persist($city);
                    }
                    //$workerFirm->setHQCity($workerFirmFormData->getHQCity());
                }

                $workerFirm->setCountry($country)->setState($state)->setCity($city);
                $em->persist($workerFirm);

                if(!$isSearchByLocation && $validateMassFirmForm->get('createLocOtherFirms')->getData() == true){

                    $qb = $em->createQueryBuilder();
                    $qb->select('wf')
                        ->from('Model\WorkerFirm', 'wf')
                        ->where('wf.HQLocation LIKE :HQLocation');

                    $qb->setParameter('HQLocation', '%'.$workerFirm->getHQCity().'%');



                    $firmsWithSameLocation = $qb->getQuery()->getResult();

                    foreach($firmsWithSameLocation as $firmWithSameLocation){
                        $firmWithSameLocation->setHQCity($workerFirm->getHQCity())
                        ->setHQState($workerFirm->getHQState())
                        ->setHQCountry($workerFirm->getHQCountry())
                        ->setCity($workerFirm->getCity())
                        ->setState($workerFirm->getState())
                        ->setCountry($workerFirm->getCountry());
                        $em->persist($firmWithSameLocation);
                    }
                }

                $em->flush();
            }

            return new JsonResponse(['message' => 'success'], 200);

        } else {
            $errors = $this->buildErrorArray($validateMassFirmForm);
            return $errors;
        }

    }

    public function getMailableIndividualsFromFirm(Request $request, Application $app, $wfId){
        $em = $this->getEntityManager($app);
        $repoWF = $em->getRepository(WorkerFirm::class);
        $workerFirm = $repoWF->findOneById($wfId);
        $activeExperiences = $workerFirm->getActiveExperiences();
        $options = [];

        foreach($activeExperiences as $activeExperience){
            if($activeExperience->getIndividual()->getEmail() != null){
                $indiv = $activeExperience->getIndividual();
                $option['key'] = $indiv->getFullName().' ('.$activeExperience->getPosition().')';
                $option['email'] = $indiv->getEmail();
                $option['value'] = $indiv->getId();
                $options[] = $option;
            }
        }

        return new JsonResponse(['options' => $options,200]);
    }

    public function validateFirm(Request $request, Application $app, $wfId){
        $em = $this->getEntityManager($app);
        $repoWF = $em->getRepository(WorkerFirm::class);
        $workerFirm = $repoWF->findOneById($wfId);
        $formFactory = $app['form.factory'];
        $validateFirmForm = $formFactory->create(ValidateFirmForm::class, $workerFirm, ['standalone' => true]);
        $validateFirmForm->handleRequest($request);

        if($validateFirmForm->isValid()){

            $workerFirmData = $validateFirmForm->getData();
            $em->persist($workerFirm);
            $em->flush();
            return new JsonResponse(['message' => 'success', 'firmMailPrefix' => $workerFirm->getMailPrefix(), 'firmMailSuffix' => $workerFirm->getMailSuffix()],200);
            } else {
            $errors = $this->buildErrorArray($validateFirmForm);
            return $errors;
        }

    }

    public function validateWorkerEmailFromSelfPage(Request $request, Application $app, $wiId, $firstname, $lastname, $male, $wiEmail){
        $em = $this->getEntityManager($app);
        $repoWI = $em->getRepository(WorkerIndividual::class);
        if(!preg_match("/^(?:[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])*$/",$wiEmail)){
            $message = "Email is not correctly formatted, reconsider email address";
            $code = 500;
        } else if($repoWI->findOneByEmail($wiEmail) != null){
            $message = "There is already";
            $code = 500;
        } else {
            $message = "Related user has now a valid email address !";
            $code = 200;
            $workerIndividual = $repoWI->findOneById($wiId);
            $workerIndividual->setEmail($wiEmail)->setMale($male)->setFirstname($firstname)->setLastname($lastname);
            $em->persist($workerIndividual);
            $em->flush();
        }
        return new JsonResponse(['message' => $message],$code);
    }

    public function displayWorkerFirm(Request $request, Application $app, $wfId){

        $em = $this->getEntityManager($app);
        $repoWF = $em->getRepository(WorkerFirm::class);
        $repoWE = $em->getRepository(WorkerExperience::class);
        $wFirm = $repoWF->findOneById($wfId);
        $searchedIndividuals = [];
        $searchedFirmExperiences = $repoWE->findByFirm($wFirm);
        $formFactory = $app['form.factory'];
        $validateMailForm = $formFactory->create(ValidateMailForm::class, null, ['standalone' => true]);
        $sendMailProspectForm = $formFactory->create(SendMailProspectForm::class, null, ['standalone' => true]);

        foreach($searchedFirmExperiences as $searchedFirmExperience){
            $searchedIndividuals[] = $searchedFirmExperience->getIndividual();
        }

        $searchedFirmIndividuals = new ArrayCollection(array_unique($searchedIndividuals));

        $firmActiveIndividuals = $searchedFirmIndividuals->filter(function(WorkerIndividual $individual) use ($wFirm) {
            return $individual->getExperiences()->first()->getFirm() == $wFirm;
        });
        $firmInactiveIndividuals = $searchedFirmIndividuals->filter(function(WorkerIndividual $individual) use ($wFirm) {
            return $individual->getExperiences()->first()->getFirm() != $wFirm;
        });


        $iterator = $firmActiveIndividuals->getIterator();

        $iterator->uasort(function ($first, $second) {
            return ($first->getExperiences()->last()->getStartDate() > $second->getExperiences()->last()->getStartDate()) ? 1 : -1;
        });

        $firmActiveIndividuals = new ArrayCollection(iterator_to_array($iterator));
        $validateMassMailForm = $formFactory->create(ValidateMassMailForm::class, $wFirm, ['standalone' => true]);
        $validateMassMailForm->handleRequest($request);

        $iterator = $firmInactiveIndividuals->getIterator();

        $iterator->uasort(function ($first, $second) {
            return ($first->getExperiences()->last()->getStartDate() > $second->getExperiences()->last()->getStartDate()) ? 1 : -1;
        });

        $firmInactiveIndividuals = new ArrayCollection(iterator_to_array($iterator));

        return $app['twig']->render('worker_firm_elements.html.twig',
        [
            'wFirm' => $wFirm,
            'firmActiveIndividuals' => $firmActiveIndividuals,
            'firmInactiveIndividuals' => $firmInactiveIndividuals,
            'validateMailForm' => $validateMailForm->createView(),
            'sendMailProspectForm' => $sendMailProspectForm->createView(),
            'validateMassMailForm' => $validateMassMailForm->createView(),
        ]);
    }

    public function updateNbExpsInAllFirms(Request $request, Application $app,$from,$to){

        $em = $this->getEntityManager($app);
        $repoWF = $em->getRepository(WorkerFirm::class);
        $repoWE = $em->getRepository(WorkerExperience::class);
        $firmsIdsToSearch = [];
        for($i = $from; $i < $to; $i++){
            $firmsIdsToSearch[] = $i;
        }

        $wFirms = $repoWF->findById($firmsIdsToSearch);

        foreach($wFirms as $key => $wFirm){
            $nbActiveExp = count($repoWE->findBy(['firm' => $wFirm, 'active' => 1]));
            $nbOldExp = count($repoWE->findBy(['firm' => $wFirm, 'active' => 0]));
            $wFirm->setNbActiveExperiences($nbActiveExp)
                ->setNbOldExperiences($nbOldExp);
            $em->persist($wFirm);
            if($key % 100 == 0){
                $em->flush();
            }
        }
        $em->flush();
        return true;
    }

    public function createMostPossibleMails(Request $request, Application $app, $from, $to){

        $em = $this->getEntityManager($app);
        $repoWF = $em->getRepository(WorkerFirm::class);
        $firmsIdsToSearch = [];
        for($i = $from; $i < $to; $i++){
            $firmsIdsToSearch[] = $i;
        }
        $l = 0;

        $wFirms = $repoWF->findById($firmsIdsToSearch);

        foreach($wFirms as $wFirm){
            $website = $wFirm->getWebsite();
            $commonName = $wFirm->getCommonName();
            if($website != null || $commonName != null){
                if($website != null){

                    if(count(explode('//',$website)) > 1){
                        $suf = explode('//',$website)[1];
                    } else {
                        $suf = $website;
                    }
                    $domain = explode('/',$suf)[0];
                    $suffix = $domain;
                    while(count(explode('.',$suffix)) > 2){
                        $suffix = explode('.',$suffix,2)[1];
                    }
                    $wFirm->setMailPrefix(1)->setMailSuffix($suffix);
                }
                if($commonName != null){
                    $wFirm->setCommonName($wFirm->getName());
                }
                $em->persist($wFirm);
                if($l % 100 == 0){
                    $em->flush();
                }
                $l++;
            }
        }
        $em->flush();
        return true;
    }

    // Function which sets data into our DB
    public function insertLKJSONData(Request $request, Application $app){

        try {
        $em = $this->getEntityManager($app);
        $repoWF = $em->getRepository(WorkerFirm::class);

        if(isset($_POST['individuals'])){

            $repoWI = $em->getRepository(WorkerIndividual::class);
            $repoWE = $em->getRepository(WorkerExperience::class);

            foreach($_POST['individuals'] as $key => $individual){

                $fullName = $individual['name'];
                $fullUrl = $individual['url'];
                $tmp = explode("/",$fullUrl);
                $url = end($tmp);

                $worker = $repoWI->findOneBy(['url' => $url, 'fullName' => $fullName]);
                if($worker == null){
                    $worker = new WorkerIndividual;
                    $worker->setCreated(0);
                }

                    $worker
                        ->setUrl($url)
                        ->setFullName($fullName)
                        ->setCountry('LU')
                        ->setNbConnections($individual['experiences']['nbConnections']);

                    if($worker->getCreated() == null){
                        $worker->setCreated(0);
                    }

                foreach($individual['experiences'] as $key => $expType){
                    if($key == "cExp"){
                        $this->insertIndividualExperience($em, $repoWF, $repoWE, $worker, $expType);
                    } else if($key == "pExps") {
                        foreach($expType as $experience){
                            $this->insertIndividualExperience($em, $repoWF, $repoWE, $worker, $experience);
                        }
                    }
                }

                $em->persist($worker);
                $em->flush();
            }

        } else if (isset($_POST['firms'])) {

            $repoWFS = $em->getRepository(WorkerFirmSector::class);

            foreach($_POST['firms'] as $key => $firm){

                $urlElements = explode("/",$firm['url']);
                $workerFirm = $repoWF->findOneByUrl(array_pop($urlElements));

                $firmDetails = $firm['details'];
                foreach($firmDetails as $key => $data){
                    switch($key){
                        case 'activitySector' :
                            $firmSector = $repoWFS->findOneByName($data);
                            if($firmSector == null){
                                $firmSector = new WorkerFirmSector;
                                $firmSector->setName($data);
                                $em->persist($firmSector);
                                $em->flush();
                            }
                            $workerFirm->setMainSector($firmSector);
                            break;
                        case 'nbSubscribers' :
                            $workerFirm->setNbLKFollowers($data);
                            break;
                        case 'nbLKEmployees' :
                            $workerFirm->setNbLKEmployees($data);
                            break;
                        case 'website' :
                            $workerFirm->setWebsite($data);
                            break;
                        case 'cDate' :
                            $workerFirm->setCreationDate(new \DateTime($data.'-01-01'));
                            break;
                        case 'fType' :
                            switch($data){
                                case "Non lucratif" :
                                    $workerFirm->setFirmType(-3);
                                    break;
                                case "Administration publique" :
                                    $workerFirm->setFirmType(-2);
                                    break;
                                case "Établissement éducatif":
                                    $workerFirm->setFirmType(-1);
                                    break;
                                case "Travailleur indépendant ou profession libérale":
                                    $workerFirm->setFirmType(0);
                                    break;
                                case "Entreprise individuelle" :
                                    $workerFirm->setFirmType(1);
                                    break;
                                case "Société civile/Société commerciale/Autres types de sociétés" :
                                    $workerFirm->setFirmType(2);
                                    break;
                                case "Société de personnes (associés)" :
                                    $workerFirm->setFirmType(3);
                                    break;
                                case "Société cotée en bourse" :
                                    $workerFirm->setFirmType(4);
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 'fSize' :
                            switch($data){
                                case "1-10 employés" :
                                    $workerFirm->setSize(0);
                                    break;
                                case "11-50 employés" :
                                    $workerFirm->setSize(1);
                                    break;
                                case "51-200 employés" :
                                    $workerFirm->setSize(2);
                                    break;
                                case "201-500 employés" :
                                    $workerFirm->setSize(3);
                                    break;
                                case "501-1 000 employés" :
                                    $workerFirm->setSize(4);
                                    break;
                                case "1001-5 000 employés" :
                                    $workerFirm->setSize(5);
                                    break;
                                case "5 001-10 000 employés" :
                                    $workerFirm->setSize(6);
                                    break;
                                case "+ de 10 000 employés" :
                                    $workerFirm->setSize(7);
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 'HQLocation' :
                            $location = ucwords(strtolower($data));
                            $workerFirm->setHQLocation($location);

                            $splitLocation = explode(", ",$location,2);
                            $workerFirm->setHQCity($splitLocation[0]);
                            if(count($splitLocation) > 1) {
                                $workerFirm->setHQState($splitLocation[1]);
                            }
                            break;
                        case 'fCompetencies' :
                            foreach($data as $key => $firmCompetency){
                                $workerFirmCompetency = new WorkerFirmCompetency;
                                $firmCompetencyName = ucwords(trim($firmCompetency));
                                $workerFirmCompetency->setFirm($workerFirm)
                                    ->setName($firmCompetency);
                                $em->persist($workerFirmCompetency);
                            }
                        break;
                    }
                }
                $em->persist($workerFirm);

            }
            $em->flush();
        }


        }catch (\Exception $e){
            print_r($e->getLine().' '.$e->getMessage());
            die;
        }
        return true;
    }

    public function transformFirmsIntoJSONVector(Request $request, Application $app, $from, $to){
        $em = $this->getEntityManager($app);
        $repoWF = $em->getRepository(WorkerFirm::class);
        $firmsIdsToSearch = [];
        for($i = $from; $i < $to; $i++){
            $firmsIdsToSearch[] = $i;
        }
        $firmsData = [];
        $wFirms = new ArrayCollection($repoWF->findById($firmsIdsToSearch));
        $searchableLKFirms = $wFirms->matching(Criteria::create()->where(Criteria::expr()->neq("url", null))->andWhere(Criteria::expr()->neq("url", "")));
        foreach($searchableLKFirms as $searchableLKFirm){
            $firmData = [];
            $firmData['name'] = $searchableLKFirm->getName();
            $firmData['url'] = 'https://lu.linkedin.com/company/'.$searchableLKFirm->getUrl();
            $firmsData[] = $firmData;
        }

        return new JsonResponse(['firms' => json_encode($firmsData)], 200);
    }
}
