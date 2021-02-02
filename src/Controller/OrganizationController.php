<?php

namespace App\Controller;

use App\Entity\Output;
use App\Model\ActivityM;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Dompdf\Dompdf;
use Exception;
use App\Form\ActivityElementForm;
use App\Form\AddAnswerForm;
use App\Form\AddClientForm;
use App\Form\AddElementTargetForm;
use App\Form\AddFirstAdminForm;
use App\Form\AddProcessForm;
use App\Form\AddStageForm;
use App\Form\AddSurveyForm;
use App\Form\AddTeamForm;
use App\Form\AddUserForm;
use App\Form\DelegateActivityForm;
use App\Form\ManageCriterionNameForm;
use App\Form\ManageOrganizationElementsForm;
use App\Form\RequestActivityForm;
use App\Form\SetClientIconForm;
use App\Form\SettingsOrganizationForm;
use App\Form\StageNameType;
use App\Form\Type\AnswerType;
use App\Form\Type\ClientType;
use App\Form\Type\ClientUserType;
use App\Form\Type\CriterionType;
use App\Form\Type\OutputType;
use App\Form\Type\ExternalUserType;
use App\Form\Type\OrganizationElementType;
use App\Form\Type\StageType;
use App\Form\Type\UserType;
use League\Csv\Reader;
use App\Entity\Activity;
use App\Entity\Participation;
use App\Entity\Answer;
use App\Entity\Client;
use App\Entity\Criterion;
use App\Entity\CriterionGroup;
use App\Entity\CriterionName;
use App\Entity\Decision;
use App\Entity\Department;
use App\Entity\DocumentAuthor;
use App\Entity\DynamicTranslation;
use App\Entity\ElementUpdate;
use App\Entity\Event;
use App\Entity\EventComment;
use App\Entity\EventDocument;
use App\Entity\EventGroup;
use App\Entity\EventGroupName;
use App\Entity\EventName;
use App\Entity\EventType;
use App\Entity\ExternalUser;
use App\Entity\GeneratedImage;
use App\Entity\Grade;
use App\Entity\InstitutionProcess;
use App\Entity\IProcessCriterion;
use App\Entity\IProcessStage;
use App\Entity\OptionName;
use App\Entity\Organization;
use App\Entity\OrganizationUserOption;
use App\Entity\Position;
use App\Entity\Process;
use App\Entity\ProcessCriterion;
use App\Entity\ProcessStage;
use App\Entity\Recurring;
use App\Entity\Result;
use App\Entity\ResultProject;
use App\Entity\ResultTeam;
use App\Entity\Stage;
use App\Entity\Survey;
use App\Entity\SurveyField;
use App\Entity\SurveyFieldParameter;
use App\Entity\Target;
use App\Entity\Team;
use App\Entity\Member;
use App\Entity\Template;
use App\Entity\TemplateActivity;
use App\Entity\TemplateCriterion;
use App\Entity\TemplateStage;
use App\Entity\Title;
use App\Entity\Update;
use App\Entity\User;
use App\Entity\UserGlobal;
use App\Entity\UserMaster;
use App\Entity\Weight;
use App\Entity\WorkerFirm;
use App\Form\AddOrganizationForm;
use App\Form\AddPictureForm;
use App\Form\AddSignupUserForm;
use App\Form\ManageProcessForm;
use App\Form\OrganizationProfileForm;
use App\Repository\OrganizationRepository;
use App\Security\LoginFormAuthenticator;
use DateTimeZone;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Environment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Service\FileUploader;
use App\Service\NotificationManager;
use DateInterval;
use DateTimeInterface;
use phpDocumentor\Reflection\Types\Nullable;
use Proxies\__CG__\App\Entity\Icon;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrganizationController extends MasterController
{
    private $notFoundResponse;

    public function __construct(EntityManagerInterface $em, Security $security, RequestStack $stack, UserPasswordEncoderInterface $encoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, Environment $twig) {
        parent::__construct($em, $security, $stack, $encoder, $guardHandler, $authenticator, $twig);
        $this->notFoundResponse = new Response(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/organization/administrators/validate", name="validateAdmins")
     */
    public function validateAdminsAction(Request $request){


        $admins = $request->get('users');

        if(!$admins){
            return new JsonResponse(['error' => "You need to select at least one person who will be administrator"], 500);
        }

        $repoU = $this->em->getRepository(User::class);
        $orgPreviousAdminUsers = new ArrayCollection($repoU->findBy(['organization' => $this->user->getOrganization(),'role' => 1]));
        $settings = [];
        $recipients = [];

        foreach($admins as $admin){
            $adminUser = $repoU->find($admin['id']);
            if($adminUser != $this->user){
                $recipients[] = $adminUser;
                $settings['tokens'][] = $adminUser->getToken();
                $orgPreviousAdminUsers->removeElement($adminUser);
            } else {
                $this->user->setRole(USER::ROLE_ADMIN);
            }
        }

        if(sizeof($recipients) > 0){
            $settings['rootCreation'] = true;
            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'registration']);
            
        }

        $settings = [];
        $settings['rootCreation'] = false;
        $settings['adminFullName'] = $this->user->getFullName();

        $recipients = [];
        foreach($orgPreviousAdminUsers as $orgPreviousAdminUser){
            $orgPreviousAdminUser->setRole(USER::ROLE_AM);
            $this->em->persist($orgPreviousAdminUser);
            $recipients[] = $orgPreviousAdminUser;
            $settings['tokens'][] = $orgPreviousAdminUser->getToken();
        }
//        if(sizeof($recipients) > 0){
//            MasterController::sendMail($app, $recipients, 'registration', $settings);
//        }

        $this->em->flush();

        return new JsonResponse(['msg' => "Success"], 200);
    }

    /**
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/teams/create", name="createTeam")
     * @Route("/team/create", name="createTeam")
     */
    public function createTeamAction(){
        $currentUser = $this->user;;
        $team = new Team;
        $organization = $currentUser->getOrganization();
        $team->setOrganization($currentUser->getOrganization())
        ->setCreatedBy($currentUser->getId())
        ->setName('Team '. ($organization->getTeams()->count() + 1));
        $this->em->persist($team);
        $this->em->flush();
        return $this->redirectToRoute('manageTeam',['teaId' => $team->getId()]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $posId
     * @return false|string
     * @Route("/ajax/position/{posId}/weights", name="retrieveWgtFromPos")
     */
    public function retrieveWgtFromPosAction(Request $request, $posId)
    {
        $repoP       = $this->em->getRepository(Position::class);
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $orgId    = $currentUser->getOrgId();
        $position = $repoP->find($posId);

        $weights = $position->getWeights();
        $keys    = [];
        $values  = [];
        foreach ($weights as $weight) {
            $keys[]   = $weight->getId();
            $values[] = $weight->getValue();
        }

        $weights = array_combine($keys, $values);
        return json_encode($weights, 200);
    }

    public function abstractActivityConfigurationAction(Request $request, string $entity,?int $id)
    {
        if (!$this->user) {
            return $this->redirectToRoute('login');
        }

        $organization = $this->user->getOrganization();
        $orgId        = $organization->getId();

        switch ($entity) {
            case 'iprocess':
                $iprocess = $this->em->getRepository(InstitutionProcess::class)->find($id);
                $redirectLink = $this->redirectToRoute('manageProcesses', ['orgId' => $orgId]);

                return $iprocess
                ? $this->iprocessConfigurationAction($iprocess, $this->user,$entity,$redirectLink)
                : $this->notFoundResponse;

            case 'process':
                $process = $this->em->getRepository(Process::class)->find($id);
                $redirectLink = $this->redirectToRoute('manageProcesses', ['orgId' => $orgId]);
                return $process
                ? $this->processConfigurationAction($process, $this->user,$entity,$redirectLink)
                : $this->notFoundResponse;

            case 'template':

            case 'activity':
                $activity = $this->em->getRepository(Activity::class)->find($id);
                $redirectLink = $this->redirectToRoute('myActivities');
                return $activity
                ? $this->activityConfigurationAction($request, $activity, $this->user,$entity,$redirectLink)
                : $this->notFoundResponse;
        }

        return new Response(null, Response::HTTP_BAD_REQUEST);
    }

    private function activityConfigurationAction(Request $request, $element, User $user, $entity, $redirectLink)
    {

        global $app;
        $currentUser = $this->user;
        $activityForm = $this->createForm(
            ActivityElementForm::class, $element, ['entity' => $entity]
        );
        $stageElementForm = $this->createForm(StageType::class,null,['entity' => $entity, 'element' => $element, 'standalone' => true]);
        $criterionElementForm = $this->createForm(CriterionType::class,null,['entity' => $entity, 'standalone' => true]);

        $activityForm->handleRequest($request);
        $stageElementForm->handleRequest($request);
        $criterionElementForm->handleRequest($request);



        if ($activityForm->isValid()) {

            $em = $this->em;

            if ($_POST['clicked-btn'] == "update" && $entity == 'activity') {

                $nbTotalStages = count($element->getStages());

                foreach ($element->getActiveModifiableStages() as $stage) {

                    // 1 - Sending participants mails if necessary
                    // Parameter for subject mail title
                    if ($nbTotalStages > 1) {
                        $settings['stage'] = $stage;
                    } else {
                        $settings['activity'] = $element;
                    }

                    $notYetMailedParticipants = $stage->getDistinctParticipations()->filter(function (Participation $p) {
                        return !$p->getisMailed();
                    });
                    /** @var Participation[] */
                    $participants = $notYetMailedParticipants->getValues();
                    $recipients   = [];
                    foreach ($participants as $participant) {
                        $recipients[] = $participant->getUser();
                        $participant->setStatus(1);
                        $participant->setIsMailed(true);
                        $em->persist($participant);
                    }

                    $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'activityParticipation']);
                    $em->flush();
                }

                if ($element->getIsFinalized() == false) {
                    $element->setIsFinalized(true);
                    $em->persist($element);
                }
                $em->flush();
            }


                if ($element->getIsFinalized()) {

                    // 2 - Updating activity status if necessary

                    $tomorrowDate = new DateTime;
                    $tomorrowDate->add(new \DateInterval('P1D'));

                    $yesterdayDate = new DateTime;
                    $yesterdayDate->sub(new \DateInterval('P1D'));
                    $k = 0;
                    $p = 0;

                    foreach ($element->getActiveStages() as $stage) {
                        if ($stage->getGStartDate() > $tomorrowDate) {
                            $k++;
                        }
                        if ($stage->getGEndDate() <= $yesterdayDate) {
                            $p++;
                        }
                    }

                    $nbActiveStages = count($element->getActiveStages());

                    // If every grading stage starts in the future...
                    if ($k == $nbActiveStages) {
                        $element->setStatus(0);
                    } else {
                        //..else if not every grading stage ends in the past...
                        if ($p != $nbActiveStages) {
                            $element->setStatus(1);
                        } else {
                            $element->setStatus(-1);
                        }
                    }

                }


            $em->flush();
            return $redirectLink;
        }

        return $this->render(
            'activity_element_2.html.twig',
            [
                'activity' => $element,
                'form' => $activityForm->createView(),
                'stageElementForm' => $stageElementForm->createView(),
                'criterionElementForm' => $criterionElementForm->createView(),
            ]
        );
    }

    private function iprocessConfigurationAction(InstitutionProcess $iprocess, User $user)
    {
        $formFactory = self::getFormFactory();
        $activityForm = $this->createForm(
            ActivityElementForm::class, $iprocess, ['elmt' => 'iprocess']
        );

        return $this->render(
            'activity_element_2.html.twig',
            [
                'activity' => $iprocess,
                'form' => $activityForm->createView()
            ]
        );
    }

    private function processConfigurationAction(Process $process, User $user)
    {
        $formFactory = self::getFormFactory();
        $activityForm = $this->createForm(
            ActivityElementForm::class, $process, ['elmt' => 'process']
        );

        return $this->render(
            'activity_element_2.html.twig',
            [
                'activity' => $process,
                'form' => $activityForm->createView()
            ]
        );
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $stgId
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/stage/{stgId}/status/update", name="updateStageStatus")
     */
    public function updateStageStatusAction(Request $request, $stgId){
        $currentUser = $this->user;
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }
        $em = $this->em;
        $repoS = $em->getRepository(Stage::class);
        /** @var Stage */
        $stage = $repoS->find($stgId);
        $progressStatus = $request->get('status');

        // Potential reset of mail updating status notifs
        if($stage->getProgress() == STAGE::PROGRESS_UNSTARTED && $progressStatus == STAGE::PROGRESS_UPCOMING){
            $stage->setUnstartedNotified(null);
        }
        if($stage->getProgress() == STAGE::PROGRESS_SUSPENDED && $progressStatus == STAGE::PROGRESS_ONGOING){
            $stage->setUncompletedNotified(null);
        }

        $stage->setProgress($progressStatus);
        if($progressStatus == -2){
            $stage->setReopened(true)
                ->setLastReopened(new DateTime());
        }
        $em->persist($stage);
        $em->flush();
        return new JsonResponse(['msg' => "Success"], 200);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $entity
     * @param $elmtId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{entity}/stage/duplicate/{elmtId}", name="duplicateStage")
     */
    public function duplicateElementStageAction(Request $request, $entity, $elmtId){
        $em    = $this->em;
        $repoO = $em->getRepository(Organization::class);
        $currentUser = $this->user;
        $currentUserOrganization = $currentUser->getOrganization();
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }

        switch($entity){
            case 'iprocess':
                $repoS = $em->getRepository(IProcessStage::class);
                $repoP = $em->getRepository(IProcessParticipation::class);
                break;
            case 'process':
                $repoS = $em->getRepository(ProcessStage::class);
                break;
            case 'template':
                $repoS = $em->getRepository(TemplateStage::class);
                $repoP = $em->getRepository(TemplateParticipation::class);
                break;
            case 'activity':
                $repoS = $em->getRepository(Stage::class);
                $repoP = $em->getRepository(Participation::class);
                break;
        }
        /** @var Stage|TemplateStage|ProcessStage|IProcessStage */
        $stage = $repoS->find($elmtId);

        /** @var Activity|TemplateActivity|InstitutionProcess|Process */
        switch ($entity) {
            case 'iprocess':
                $element = $stage->getInstitutionProcess();
                break;
            case 'process':
                $element = $stage->getProcess();
                break;
            case 'template':
            case 'activity':
                $element = $stage->getActivity();
                break;
        }

        if($entity == 'process'){
            $hasUserInfGrantedRights = null;
        } else {
            $stageLeader = $repoP->findOneBy(['stage' => $stage,'leader' => true]);
            $userStageLeader = $repoP->findOneBy(['stage' => $stage, 'leader' => true, 'user' => $currentUser]);
            $hasUserInfGrantedRights = ($stageLeader && $userStageLeader || !$stageLeader && $element->getMasterUser() == $currentUser);
        }
        $hasPageAccess = true;
        $organization = $element->getOrganization();
        if ($currentUser->getRole() != 4 && ($organization != $currentUserOrganization || $currentUser->getRole() != 1 && !$hasUserInfGrantedRights)) {
            $hasPageAccess = false;
        }

        if(!$hasPageAccess){
            return new JsonResponse(['msg' => "Error"],500);
        } else {

            $newStage = clone $stage;
            $userMaster = new UserMaster();
            $userMaster->setUser($currentUser);
            $newStage
                ->addUserMaster($userMaster)
                ->setCreatedBy($currentUser->getId())
                ->setInserted(new DateTime())
                ->setName($stage->getName()." - 2");

            if($stage->getSurvey()){
                // TO DO: Create code here to duplicate survey elements
            }

            foreach($stage->getCriteria() as $criterion){
                $clonedCriterion = clone $criterion;
                $clonedCriterion
                    ->setCreatedBy($currentUser->getId())
                    ->setInserted(new DateTime());
                $newStage->addCriterion($clonedCriterion);

                foreach($criterion->getParticipants() as $participant){
                    $clonedParticipant = clone $participant;
                    $clonedParticipant
                        ->setStatus(0)
                        ->setCreatedBy($currentUser->getId())
                        ->setInserted(new DateTime())
                        ->setStage($newStage);
                    $clonedCriterion->addParticipant($clonedParticipant);
                }
            }

            if(!$stage->getCriteria()->count() && !$stage->getSurvey()){
                foreach($stage->getParticipants() as $participant){
                    $clonedParticipant = clone $participant;
                    $clonedParticipant
                        ->setStatus(0)
                        ->setCreatedBy($currentUser->getId())
                        ->setInserted(new DateTime());
                    $newStage->addParticipant($clonedParticipant);
                }
            }

            $stgPctPointsWeight = 100 * $stage->getWeight();
            $newStageWeight = floor($stgPctPointsWeight / 2) == $stgPctPointsWeight / 2 ? $stgPctPointsWeight / 2 : $stgPctPointsWeight / 2 + 1;
            $newStageWeight = round($newStageWeight / 100,2);
            $newStage->setWeight($newStageWeight);
            $stage->setWeight($stage->getWeight() - $newStageWeight);
            $element->addStage($newStage);
            $em->persist($stage);
            $em->persist($element);
            $em->flush();
        }
        return new JsonResponse(['msg' => 'Success', 'sid' => $newStage->getId()],200);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $entity
     * @param $elmtId
     * @param $stgId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{entity}/{elmtId}/stage/validate/{stgId}", name="validateStageElement")
     */
    public function validateElementStageAction(Request $request, $entity, $elmtId, $stgId)
    {
        $em    = $this->em;
        $repoO = $em->getRepository(Organization::class);
        switch ($entity) {
            case 'iprocess':
                $repoE = $em->getRepository(InstitutionProcess::class);
                $repoS = $em->getRepository(IProcessStage::class);
                $repoP = $em->getRepository(IProcessParticipation::class);
                $stage = new IProcessStage;
                break;
            case 'process':
                $repoE = $em->getRepository(Process::class);
                $repoS = $em->getRepository(ProcessStage::class);
                $stage = new ProcessStage;
                break;
            case 'template':
                $repoE = $em->getRepository(TemplateActivity::class);
                $repoS = $em->getRepository(TemplateStage::class);
                $repoP = $em->getRepository(TemplateParticipation::class);
                $stage = new TemplateStage;
                break;
            case 'activity':
                $repoE = $em->getRepository(Activity::class);
                $repoS = $em->getRepository(Stage::class);
                $repoP = $em->getRepository(Participation::class);
                $stage = new Stage;
                break;
        }

        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $currentUserOrganization = $currentUser->getOrganization();
        /** @var Activity|TemplateActivity|InstitutionProcess|Process */
        $element = $repoE->find($elmtId);
        $organization  = $element->getOrganization();
        $stgCurrWeight = 0;

        if ($stgId != 0) {
            $stage = $repoS->find($stgId);
            $stgCurrWeight = $stage->getActiveWeight();
        } else {
            switch ($entity) {
                case 'iprocess':
                    $stage->setInstitutionProcess($element);
                    break;
                case 'process':
                    $stage->setProcess($element);
                    break;
                case 'template':
                case 'activity':
                    $stage->setActivity($element);
                    break;
            }
            $stage->setOrganization($organization);
        }

        if($entity == 'process'){
            $hasUserInfGrantedRights = null;
        } else {
            $stageLeader = $repoP->findOneBy(['stage' => $stage,'leader' => true]);
            $userStageLeader = $repoP->findOneBy(['stage' => $stage, 'leader' => true, 'user' => $currentUser]);
            $hasUserInfGrantedRights = ($stageLeader && $userStageLeader || !$stageLeader && $element->getMasterUser() == $currentUser);
        }
        $hasPageAccess = true;

        if ($currentUser->getRole() != 4 && ($organization != $currentUserOrganization || $currentUser->getRole() != 1 && !$hasUserInfGrantedRights)) {
            $hasPageAccess = false;
        }

        if (!$hasPageAccess) {
            return $this->render('errors/403.html.twig');
        } else {

            $stageForm = $this->createForm(StageType::class, $stage, ['entity' => $entity, 'element' => $element, 'standalone' => true]);
            $stageForm->handleRequest($request);

            if (!$stageForm->isValid()) {
                $errors = $this->buildErrorArray($stageForm);
                return $errors;
            } else {

                $userMaster = new UserMaster();
                $userMaster->setUser($currentUser);
                $stage->addUserMaster($userMaster);
                $now = new DateTime();

                // Setting initial progress status
                if(!$stage->getProgress()){
                    $stage->setProgress(
                        $stage->getEnddate() < $now ? STAGE::PROGRESS_COMPLETED :
                        ($stage->getStardate() < $now ? STAGE::PROGRESS_ONGOING : STAGE::PROGRESS_UPCOMING)
                    );
                }

                // Handling weight issue

                $impactedStages = new ArrayCollection();
                foreach ($element->getActiveStages() as $activityStage){
                    if($activityStage != $stage){
                        $impactedStages->add($activityStage);
                    }
                }

                //$now = clone new \DateTime();

                if(date_diff($stage->getStartdate(), new DateTime())->d == 0){
                    $stage->setStartdate(new DateTime());
                }

                $sumNewWeights = 0;
                foreach($impactedStages as $activityStage){

                    if($impactedStages->last() != $activityStage){
                        $newWeight = ($stgId == 0) ?
                            (1 - $stage->getActiveWeight()) * $activityStage->getActiveWeight() :
                            (1 - $stage->getActiveWeight()) / (1  - $stgCurrWeight) * $activityStage->getActiveWeight();

                        $activityStage->setActiveWeight($newWeight);
                        $sumNewWeights += $newWeight;
                    } else {
                        $activityStage->setActiveWeight(1 - $stage->getActiveWeight() - $sumNewWeights);
                    }
                    $em->persist($activityStage);
                }

                // If activity is not considered as incomplete, we need to update its/stage status based on dates data
                if($entity == 'activity'){

                    if($element->getStatus() != $element::STATUS_INCOMPLETE){

                        $stage->setStatus((int) ($stage->getGStartDate() < new DateTime));

                        if($element->getStatus() == $element::STATUS_FUTURE && $stage->getStatus() == $stage::STATUS_ONGOING){
                            $element->setStatus($element::STATUS_ONGOING);
                        } else {
                            if($element->getActiveStages()->forAll(function(int $i,Stage $s){
                                return $s->getStatus() == $s::STATUS_UNSTARTED;
                            })){
                                $element->setStatus($element::STATUS_FUTURE);
                            }
                        }
                    }

                    $stage->setProgress($stage->getStartdate() > new DateTime && $stage->getProgress() < STAGE::PROGRESS_COMPLETED ? STAGE::PROGRESS_UPCOMING : STAGE::PROGRESS_ONGOING);

                }

                $em->persist($stage);
                $em->flush();
                $responseArray = ['message' => 'Success!', 'sid' => $stage->getId()];
                return new JsonResponse($responseArray, 200);


            }
        }
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $surId
     * @return string
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/insert/question/{surId}", name= "createFieldAjax")
     */
    public function CreateFieldRequestActionAJAX (Request $request,$surId)
    {
        $em          = $this->em;
        $repoE       = $em->getRepository(Survey::class);
        $redirect = $request->get('redirect');
        $surveyfield= new SurveyField;
        $survey=$repoE->find($surId);
        $surveyfield->setPosition(count($survey->getFields()));
        $surveyfield->setSurvey($survey);

        $surveyfield->setTitle("Question ".(count($survey->getFields())+1));
        $surveyfield->setType('ST');
        $em->persist($surveyfield);
        $em->flush();
        return " ";
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $surId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/insert/parameter/{surId}", name="insertParametersAjax")
     */
    public function CreateParameterRequestActionAJAX(Request $request, $surId)
    {


        $em          = $this->em;
        $repoE       = $em->getRepository(Survey::class);
        $survey = $repoE->find($surId);
        $surveyfields = $survey->getFields();
        $surveyfield = $surveyfields[$request->request->get('data')];
        $surveyfield->setType($request->request->get('type'));
        $option=['non','oui'];
        if($request->request->get('bool')=="true") {
            if ($request->request->get('type')=="UC"){
                for ($i=0;$i<2;$i++){
                    $surveyfieldparameter = new SurveyFieldParameter;
                    $surveyfieldparameter->setValue($option[$i]);

                    $surveyfieldparameter->setField($surveyfield);
                    $surveyfield->addParameter($surveyfieldparameter);
                }
            }else{
                $surveyfieldparameter = new SurveyFieldParameter;
                $surveyfieldparameter->setValue("Option" );

                $surveyfieldparameter->setField($surveyfield);
                $surveyfield->addParameter($surveyfieldparameter);}

        }
        $em->flush();
        $redirect = $request->get('redirect');
        return $app->redirect($redirect);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $surId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/copy/question/{surId}", name="copyFieldAjax")
     */
    public function copyFieldRequestActionAJAX (Request $request,$surId)
    {
        $em          = $this->em;
        $repoE       = $em->getRepository(Survey::class);
        $repoR       = $em->getRepository(SurveyField::class);
        $redirect = $request->get('redirect');
        $surveyfield= new SurveyField;
        $survey=$repoE->find($surId);
        $surveyfields=$repoR->findBy(array('survey' => $survey),array('id' => 'ASC'));
        $json=json_decode($request->request->get('data'));
        $position=($json->id)+1;
        $surveyfield->setPosition($position);
        $surveyfield->setId(800);
        $table=[];
        $first=true;
        $lastid=$surveyfield->getId();
        $surveyfield->setSurvey($survey);
        $surveyfield->setTitle($json->title);
        $surveyfield->setType($json->type);
        $surveyfield->setIsMandatory($json->mand);
        if(!empty($json->value)){

            for ($i=0;$i<count($json->value);$i++){
                $parameter=new SurveyFieldParameter;
                $parameter->setValue($json->value[$i]);
                $parameter->setField($surveyfield);
                $em->persist($parameter);
            }

        }
        $em->persist($surveyfield);
        $em->flush();
        $lastid=$surveyfield->getId();
        for($u=0;$u<count($surveyfields);$u++){

            if($u>=$position){
                if ($first){

                    $surveyfield->setId($surveyfields[$u]->getId());
                    $first=false;
                }
                elseif($u==((count($surveyfields)-1))){

                    if ($first){
                        $surveyfield->setId($surveyfields[$u]->getId());
                        $first=false;
                    }

                    $surveyfields[$u]->setId($lastid);
                }

                else {
                    $surveyfields[$u]->setId($surveyfields[$u+1]->getId());
                }
            }

        }
        $em->flush();
        $redirect = $request->get('redirect');
        return $app->redirect($redirect);

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $surId
     * @return string
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/save/question/{surId}", name="saveFieldAjax")
     */
    public function saveFieldRequestActionAJAX (Request $request,$surId)
    {

        $em          = $this->em;
        $repoE       = $em->getRepository(Survey::class);
        $redirect = $request->get('redirect');
        $survey=$repoE->find($surId);
        $surveyfields = $survey->getFields();
        $json=json_decode($request->request->get('data'));
        $field = $surveyfields[$json->id];
        $field->setSurvey($survey);
        $field->setTitle($json->title);

        if($json->description!="") {

            $field->setDescription($json->description);

        }

        $field->setType($json->type);
        $field->setIsMandatory($json->mand);
        $field->setLowerbound($json->lowerbound);
        $field->setUpperbound($json->upperbound);

        if(!empty($json->value)){

            $surveyfieldparameteres=$field->getParameters();

            for ($i=0;$i<count($surveyfieldparameteres);$i++){
                $parameter=$surveyfieldparameteres[$i];
                $parameter->setValue($json->value[$i]);

            }

        }
        $em->flush();


        return "";

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $surId
     * @return string
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/delete/question/{surId}", name="deleteFieldAjax")
     */
    public function DeleteFieldRequestActionAJAX(Request $request, $surId){

        $em = $this->em;
        $repo1 = $em->getRepository(Survey::class);
        $survey= $repo1->find($surId);
        $surveyfield=$survey->getFields();
        $field=$surveyfield[$request->request->get('data')];
        $f=$field;
        $em->remove($field);
        $em->flush();
        return " ";



    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $surId
     * @return string
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/delete/parameter/{surId}", name="deleteParametersAjax")
     */
    public function DeleteParameterRequestActionAJAX(Request $request, $surId){

        $em = $this->em;
        $redirect = $request->get('redirect');
        $repo1 = $em->getRepository(Survey::class);
        $survey= $repo1->find($surId);
        $surveyfield=$survey->getFields();
        $field=$surveyfield[$request->request->get('data')];
        $param=$field->getParameters()[$request->request->get('param')];
        $em->remove($param);
        $em->flush();
        return " ";



    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $surId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/delete/all/parameter/{surId}", name="deleteAllParametersAjax")
     */
    public function DeleteAllParameterRequestActionAJAX(Request $request, $surId){

        $em = $this->em;
        $redirect = $request->get('redirect');
        $repo1 = $em->getRepository(Survey::class);
        $survey= $repo1->find($surId);
        $surveyfield=$survey->getFields();
        $field=$surveyfield[$request->request->get('data')];
        $param=$field->getParameters();
        for ($i=0;$i<count($param);$i++){
            $em->remove($param[$i]);

        }

        $em->flush();
        $redirect = $request->get('redirect');
        return $app->redirect($redirect);



    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $entity
     * @param $elmtId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{entity}/{elmtId}/validate/name", name="validateElementName")
     */
    public function validateElementNameAction(Request $request, $entity, $elmtId){

        $em = $this->em;
        /** @var string */
        $name = $request->get('name');
        switch ($entity) {
            case 'iprocess':
                $repoE = $em->getRepository(InstitutionProcess::class);break;
            case 'process':
                $repoE = $em->getRepository(Process::class);break;
            case 'template':
                $repoE = $em->getRepository(Template::class);break;
            case 'activity':
                $repoE = $em->getRepository(Activity::class);break;
            case 'team':
                $repoE = $em->getRepository(Team::class);break;
            default : break;
        }

        $element = $repoE->find($elmtId);

        if($elmtId == 0 && $entity == 'team'){
            $element = new Team;
            $element->setOrganization(MasterController::getAuthorizedUser()->getOrganization());
        }

        $possibleDuplicate = $repoE->findOneByName($name);

        if($possibleDuplicate != null || $name == ''){
            return new JsonResponse(['msg => error'],500);
        } else {
            if($element){
                $element->setName($name);
                $em->persist($element);
                $em->flush();
            }
            return new JsonResponse(['msg => success','elmtId' => $element->getId()],200);
        }
    }

    /**
     * @param Request $request
     * @param $entity
     * @param $elmtId
     * @param $stgId
     * @param $crtId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{entity}/{elmtId}/stage/{otpId}/criterion/validate/{crtId}", name="validateCriterionElement")
     */
    public function validateElementCriterionAction(Request $request, $entity, $otpId, $elmtId, $crtId)
    {
        $em = $this->em;

        $repoT = $em->getRepository(Output::class);
        $outputElement = $repoT->find($otpId);
        $stgId = $outputElement->getStage()->getId();
        $repoO = $em->getRepository(Organization::class);
        switch ($entity) {
            case 'iprocess':
                $repoE     = $em->getRepository(IProcessStage::class);
                $repoC     = $em->getRepository(IProcessCriterion::class);
                $repoP    = $em->getRepository(IProcessParticipation::class);
                $criterion = new IProcessCriterion;
                break;
            case 'process':
                $repoE     = $em->getRepository(ProcessStage::class);
                $repoC     = $em->getRepository(ProcessCriterion::class);
                $criterion = new ProcessCriterion;
                break;
            case 'template':
                $repoE     = $em->getRepository(TemplateStage::class);
                $repoC     = $em->getRepository(TemplateCriterion::class);
                $repoP    = $em->getRepository(TemplateParticipation::class);
                $criterion = new TemplateCriterion;
                break;
            case 'activity':
                $repoE     = $em->getRepository(Stage::class);
                $repoC     = $em->getRepository(Criterion::class);
                $repoP    = $em->getRepository(Participation::class);
                $criterion = new Criterion;
                break;
        }

        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $currentUserOrganization = $currentUser->getOrganization();
        $element                 = $repoE->find($stgId);

        if ($crtId != 0) {
            $criterion = $repoC->find($crtId);
            $criterionBeforeUpgrade = clone $criterion;
        }

        switch ($entity) {
            case 'iprocess':
                $activityLikeElmt = $element->getInstitutionProcess();
                break;
            case 'process':
                $activityLikeElmt = $element->getProcess();
                break;
            case 'template':
            case 'activity':
                $activityLikeElmt = $element->getActivity();
                break;
        }

        $organization  = $activityLikeElmt->getOrganization();
        if($entity == 'process'){
            $hasUserInfGrantedRights = null;
        } else {
            $stageLeader = $repoP->findOneBy(['stage' => $element,'leader' => true]);
            $userStageLeader = $repoP->findOneBy(['stage' => $element,'leader' => true, 'user' => $currentUser]);
            $hasUserInfGrantedRights = ($stageLeader && $userStageLeader || !$stageLeader && $element->getMasterUser() == $currentUser);
        }

        $hasPageAccess = true;

        if ($currentUser->getRole() != 4 && ($organization != $currentUserOrganization || $currentUser->getRole() != 1 && !$hasUserInfGrantedRights)) {
            $hasPageAccess = false;
        }

        if (!$hasPageAccess) {
            return $this->render('errors/403.html.twig');
        } else {

            $criterionForm = $this->createForm(CriterionType::class, $criterion, ['entity' => $entity, 'standalone' => true, 'currentUser' => $currentUser]);
            $criterionForm->handleRequest($request);
            if ($criterionForm->isSubmitted()) {
                if (!$criterionForm->isValid()) {
                    $errors = $this->buildErrorArray($criterionForm);
                    return $errors;
                } else {

                    if ($entity == 'activity' && ($crtId == 0 || ($criterionBeforeUpgrade->getCName() != $criterion->getCName() || $criterionBeforeUpgrade->getType() != $criterion->getType()))) {

                        // Checking if we need to unvalidate participations (we decide to unlock all stage participations and not only the modified one)
                        $completedStageParticipations = $element->getParticipants()->filter(function (Participation $p) {
                            return $p->getStatus() == 3;
                        });

                        if ($completedStageParticipations->count() > 0) {

                            $recipients = [];
                            $mailRecipientIds = [];
                            foreach ($completedStageParticipations as $completedParticipation) {
                                if (in_array($completedParticipation->getUsrId(), $mailRecipientIds) === false) {
                                    $recipients[] = $completedParticipation->getDirectUser();
                                    $mailRecipientIds[] = $completedParticipation->getUsrId();
                                }
                                $completedParticipation->setStatus(2);
                                $em->persist($completedParticipation);
                            }
                            $em->flush();
                            $settings = ['stage' => $element, 'actElmt' => 'criterion'];

                            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'unvalidateOutputDueToChange']);

                        }
                    }


                    $impactedCriteria = new ArrayCollection();
                    $outputs = $element->getOutputs();
                    foreach ($outputs as $output) {
                        foreach ($output->getCriteria() as $outputsCriterion) {
                            if ($outputsCriterion != $criterion) {
                                $impactedCriteria->add($outputsCriterion);
                            }
                        }
                    }
                    $sumNewWeights = 0;

                    foreach ($impactedCriteria as $outputsCriterion) {

                        if ($impactedCriteria->last() != $outputsCriterion) {
                            $newWeight = ($crtId == 0) ?
                                round((1 - $criterion->getWeight()) * $outputsCriterion->getWeight(), 2) :
                                round((1 - $criterion->getWeight()) / (1 - $criterionBeforeUpgrade->getWeight()) * $outputsCriterion->getWeight(), 2);

                            $outputsCriterion->setWeight($newWeight);
                            $sumNewWeights += $newWeight;
                        } else {
                            $outputsCriterion->setWeight(1 - $criterion->getWeight() - $sumNewWeights);
                        }
                        $em->persist($outputsCriterion);
                    }

                    if ($crtId == 0) {
                        if($element->getOutputs()==null){
                            $outputElement = new Output();
                            $element->addOutput($output);
                        }


                        $outputElement->addCriterion($criterion);
                        $em->persist($output);
                    } else {
                        $em->persist($criterion);
                    }
                    //$criterion->setStage($element);

                    $em->flush();


                    if ($crtId == 0) {
                        // In case participants were set before first criterion, we link these participations to this new criterion
                        if (sizeof($element->getCriteria()) == 1) {
                            $unsetParticipations = $repoP->findBy(['stage' => $element, 'criterion' => null]);
                            foreach ($unsetParticipations as $unsetParticipation) {
                                $criterion->addParticipation($unsetParticipation);
                            }
                        } else {
                            $firstCriterionExistingParticipations = $repoP->findBy(['stage' => $element, 'criterion' => $element->getCriteria()->first()]);
                            foreach ($firstCriterionExistingParticipations as $firstCriterionExistingParticipation) {
                                $newParticipation = clone $firstCriterionExistingParticipation;
                                $newParticipation->setInserted(new DateTime())
                                    ->setCreatedBy($currentUser->getId());
                                $criterion->addParticipation($newParticipation);
                            }
                        }
                        $em->persist($criterion);
                        $em->flush();
                    }

                    $responseArray = ['message' => 'Success to add criteria!', 'cid' => $criterion->getId()];
                    return new JsonResponse($responseArray, 200);
                }
            }$responseArray = ['message' => 'Success to add criteria!', 'cid' => $criterion->getId()];
            return new JsonResponse($responseArray, 200);
        }
    }
    /**
     * @param Request $request
     * @param $entity
     * @param $elmtId
     * @param $stgId
     * @param $otpId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{entity}/{elmtId}/stage/{stgId}/output/validate/{otpId}", name="validateOutputElement")
     */
    public function validateElementOutputAction(Request $request, $entity, $elmtId, $stgId, $otpId)
    {
                        $em = $this->em;
                        $currentUser = $this->getUser();
                        $repoE = $em->getRepository(Stage::class);
                        $element = $repoE->find($stgId);
                        $output = $otpId != 0 ? $repoE->find($otpId) : new Output;

                        $outputForm = $this->createForm(OutputType::class, $output, ['entity' => $entity, 'standalone' => true, 'currentUser' => $currentUser]);
                        $outputForm->handleRequest($request);

                        if ($outputForm->isSubmitted()) {
                            if ($outputForm->isValid()) {

                                $em->persist($output);
                                $output->setStage($element);
                                $em->flush();
                                $responseArray = ['message' => 'Success to add output!', 'oid' => $output->getId()];
                                return new JsonResponse($responseArray, 200);

                            }else{
                                $errors = $this->buildErrorArray($outputForm);
                                return $errors;

                            }
                        }







    }
    //Adds surveys to current organization

    /**
     * @param Request $request
     * @param Application $app
     * @param $stgId
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/activity/survey/form/{stgId}", name= "surveyRequest")
     * @Route("/edit/survey/{stgId}", name = "editSurvey")
     */
    public function surveyRequestAction(Request $request, $stgId)
    {
        $entityManager = $this->getEntityManager($app);
        $repo0         = $entityManager->getRepository(Stage::class);
        $repo1         = $entityManager->getRepository(Survey::class);
        $repo2         = $entityManager->getRepository(Participation::class);
        $stage         = $repo0->find($stgId);
        $currentUser = $this->user;;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        /*
        $act_user      = $repo2->findBy(['stage'=>$stgId,'user'=>$currentUser]);
        */
        $redirect      = $request->get('redirect');
        //$stage = $repo1->find($_GET[$stgId]);
        
        $edit=false;
        if ($repo1->findBy(['stage' => $stage]) == null) {
            $survey = new Survey;
            $survey->setCreatedBy($currentUser->getId());
            $survey->setOrganization($currentUser->getOrganization());
            $survey->setStage($stage);
            $stage->setSurvey($survey);

            $activities=$repo2->findBy(array('stage' => $stage));
            if(empty($activities)){

                $Participation= new Participation();
                $Participation->setStage($stage);
                $Participation->setSurvey($survey);
                $Participation->setUsrId($currentUser->getId());
                $Participation->setActivity($stage->getActivity());
                $entityManager->persist($Participation);
            }
            else{
            foreach ($activities as $activity) {
                $activity->setSurvey($survey);
            }}

            $entityManager->persist($survey);
            $entityManager->flush();
            $surveyForm = $this->createForm(AddSurveyForm::class, $survey);
        } else {
            $survey     = $repo1->findOneBy(['stage' => $stage]);

            $surveyForm = $this->createForm(AddSurveyForm::class, $survey);
            $edit=true;
        }


        $surveyForm->handleRequest($request);
        $parameters    = false;
        if ( $surveyForm->isSubmitted()) {
            $survey = $repo1->findOneBy(['stage' => $stage]);
            $surveyRequest = $surveyForm->getData();
            if($surveyRequest->getName()==null) {
                $survey->setName('Survey '.$survey->getId());
                //$stage->setSurvey($survey->getId());
            }

            foreach ($surveyForm->get('fields') as $surveyForm) {
                $surveyfield=  $surveyForm->getData();
                foreach ($surveyForm->get('parameters') as $surveyForm) {
                    $surveyRequest1 = $surveyForm->getData();
                    $surveyRequest1->setField($surveyfield);
                }
            }
            $entityManager->flush();
            return $this->redirectToRoute('manageActivityElement',['entity' => 'activity','elmtId' => $stage->getActivity()]);
        }

        $surveyForm = $this->createForm(AddSurveyForm::class, $survey);

        return $this->render('create_survey.html.twig',
            ['surId' => $survey->getId(),
                'form' => $surveyForm->createView(),
                'survey' => $survey,
                'edition' => true,
            ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $surId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/activity/answer/send/{surId}", name="sendAnswer")
     */
    public function sendAnswerAction(Request $request,$surId){
        $currentUser = $this->user;;
        $em = $this->em;
        $redirect = $request->get('redirect');
        $repo2 = $em->getRepository(Participation::class);
        $repo0 = $em->getRepository(Survey::class);
        $survey =$repo0->find($surId);
        $activity=$repo2->findOneBy((array('survey' => $survey->getId(),  'usrId' => $currentUser->getId())));
        $activity->setStatus(3);
        $em->flush();

        return $app->redirect($redirect);

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $surId
     * @return mixed
     * @Route("/activity/result/send/{surId}", name="sendResult")
     */
    public function sendResultAction(Request $request,$surId){

        $em = $this->em;
        $redirect = $request->get('redirect');
        $repo2 = $em->getRepository(Participation::class);
        $repo0 = $em->getRepository(Survey::class);
        $survey =$repo0->find($surId);
        $activities=$repo2->findBy(array('survey' => $survey->getId()));
        foreach ($activities as $activity) {
            $activity->setStatus(4);
        }
        try {
            $em->flush();
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }
        return $app->redirect($redirect);


    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $surId
     * @return mixed
     * @Route("/activity/survey/answer/answers/{surId}", name="answerShow")
     */
    public function answerShowAction(Request $request, $surId)
    {
        $em = $this->em;
        
        $currentUser = $this->user;;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $repo0 = $em->getRepository(Survey::class);
        $repo1 = $em->getRepository(Answer::class);
        $survey = $repo0->find($surId);
        $answer = $repo1->findBy(['survey' => $survey]);
        $answerForm = $this->createForm(AddSurveyForm::class, $survey, ['edition' => true, 'survey' => $survey, 'user' => $currentUser]);
        $surveyfield = $survey->getFields();
        for ($i = 0; $i < count($surveyfield); $i++) {
        }
        $nbquestions = count($survey->getFields());
        $nbanswers = count($survey->getAnswers())/$nbquestions;
        return $this->render('answer_sent.html.twig',
            [
                'surId' => $survey->getId(),
                'survey' => $survey,
                'answer' => $answer,
                'form' => $answerForm->createView(),
                'nbanswers' => $nbanswers,
                'nbquestions' => $nbquestions
            ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $surId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/activity/survey/delete/{surId}", name="surveyDelete")
     */
    public function deleteSurveyAction(Request $request, $surId)
    {
        $redirect = $request->get('redirect');
        $em       = $this->em;
        $survey   = $em->getRepository(Survey::class)->find($surId);
        $survey->getStage()->setSurvey(null);
        $survey->setStage(null);
        $activities   = $em->getRepository(Participation::class)->findBy(array('survey' => $survey->getId()));
        foreach ($activities as $activity) {
            $activity->setStatus(0);
            $activity->setSurvey(null);
        }

        $em->remove($survey);
        $em->flush();

            return $app->redirect($redirect);

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $stgId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/send/survey/{stgId}", name="surveyRequestFinalized")
     */
    public function surveyRequestFinalized(Request $request ,Application $app, $stgId)
    {
        $redirect = $request->get('redirect');
        $em       = $this->em;
        $activities   = $em->getRepository(Participation::class)->findBy(array('stage' => $stgId));
        foreach ($activities as $activity) {
            $activity->setStatus(1);
        }

        $em->flush();

        return $app->redirect($redirect);
    }

    //Adds user(s) to current organization (limited to HR)

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/organization/settings/users/create", name="createUser")
     */
    public function createUserAction(Request $request)
    {

        //TODO : get current language dynamically
        $locale = 'fr';

        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }

        $adminFullName = $currentUser->getFirstname() . " " . $currentUser->getLastname();
        $id            = $currentUser->getId();
        $orgId         = $currentUser->getOrganization();
        $em            = $this->em;
        $repoO         = $em->getRepository(Organization::class);
        //$repoP = $em->getRepository(Position::class);
        $repoU = $em->getRepository(User::class);
        $repoW = $em->getRepository(Weight::class);

        $organization           = $repoO->find($orgId);
        $orgEnabledCreatingUser = false;

        // Only administrators or roots canKey "0" in object with ArrayAccess of class "Doctrine\ORM\PersistentCollection" does not exist. create/update users who have the ability to create users themselves
        $orgOptions = $organization->getOptions();
        foreach ($orgOptions as $orgOption) {
            if ($orgOption->getOName()->getName() == 'enabledUserCreatingUser') {
                $orgEnabledCreatingUser = $orgOption->isOptionTrue();
            }
        }

        if ($currentUser->getRole() != 4 && $currentUser->getRole() != 1 && !($currentUser->getDepartment($app)->getMasterUser() == $currentUser || $orgEnabledCreatingUser && $currentUser->isEnabledCreatingUser())) {
            return $this->render('errors/403.html.twig');
        }

        
        $userForm                = $this->createForm(AddUserForm::class, null, ['standalone' => true, 'organization' => $organization, 'enabledCreatingUser' => $orgEnabledCreatingUser]);
        $organizationElementForm = $this->createForm(OrganizationElementType::class, null, ['standalone' => true]);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $settings   = [];
            $recipients = [];
            foreach ($userForm->get('users') as $userForm) {
                $user = $userForm->getData();
                $user->setWeightIni($user->getWeight()->getValue());
                $token = md5(rand());
                
                $user->setOrganization($orgId)
                    ->setToken($token)
                    ->setCreatedBy($currentUser->getId())
                    ->setSuperior($user->getSuperior() ?: null);
              
                $em->persist($user);
                $settings['tokens'][] = $token;
                $recipients[]         = $user;

            }

            $settings['adminFullName'] = $currentUser->getFullName();
            $settings['rootCreation']  = false;
            $em->flush();
            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'registration']);

            return $this->redirectToRoute('manageUsers');
        }

        return $this->render('user_create.html.twig',
            [
                'form'                    => $userForm->createView(),
                'organizationElementForm' => $organizationElementForm->createView(),
                'orgId'                   => $orgId,
                'enabledCreatingUser'     => $orgEnabledCreatingUser,
                'creationPage'            => true,
            ]);
    }

    /**
     * @param Request $request
     * @param $cliId
     * @return mixed
     * @Route("/client/validate/{cliId}", name="validateClient")
     */
    public function validateClientAction(Request $request, $cliId){

        $em = $this->em;
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }

        $repoC = $em->getRepository(Client::class);
        $organization = $currentUser->getOrganization();
        /** @var Client */
        $client = $cliId == 0 ? new Client : $repoC->find($cliId);
        $clientForm = $this->createForm(ClientType::class, $client, [ 'standalone' => false, 'hasChildrenElements' => false, 'currentUser' => $currentUser ]);
        $clientForm->handleRequest($request);
        if ($clientForm->isSubmitted()) {
        if ($clientForm->isValid()) {

            $clientName = $client->getName();

            /** @var WorkerFirm */
            $workerFirm = $client->getWorkerFirm() ? $this->em->getRepository(WorkerFirm::class)->find($client->getWorkerFirm()) : ($this->em->getRepository(WorkerFirm::class)->findOneByCommonName($client->getName()) ?: new WorkerFirm);
            $client->setWorkerFirm($workerFirm);
            $em->persist($client);

            if(!$workerFirm->getCommonName()){
                $workerFirm
                    ->setCommonName($clientName)
                    ->setName($clientName);
                $em->persist($workerFirm);
            }

            if($cliId == 0){
              
                $clientOrganization = $em->getRepository(Organization::class)->findOneByWorkerFirm($workerFirm);

                if(!$clientOrganization){
                    $now = new DateTime();
                    $clientOrganization = new Organization;
                    $clientOrganization
                        ->setCommname($clientName)
                        ->setType($client->getType())
                        ->setExpired($now->add(new DateInterval('P21D')))
                        ->setWeightType('role')
                        ->setWorkerFirm($workerFirm);
                    $em->persist($clientOrganization);
    
                    $this->updateOrgFeatures($clientOrganization, null, false, true, true, [$currentUser]);
                    
    
                } else {
                    $synthUser = $em->getRepository(User::class)->findOneBy(['organization' => $clientOrganization, 'synthetic' => true]);
                }

                /** @var ExternalUser */
                $externalSynthUser = new ExternalUser;
                $externalSynthUser->setUser($synthUser)
                    ->setOwner(true)->setFirstname($organization->getCommname())
                    ->setSynthetic(true)
                    ->setLastname($client->getName());

                $client
                ->addExternalUser($externalSynthUser)
                ->setOrganization($organization)
                ->setClientOrganization($clientOrganization)
                ->setCreatedBy($currentUser->getId());


                $em->persist($client);

            }

            $em->flush();

            if($cliId == 0){
                $orgId = $clientOrganization->getId();
                $orgName = strtolower(implode("-",explode(" ",$clientName)));
                mkdir(dirname(dirname(__DIR__)) . "/public/lib/cdocs/{$orgId}-{$orgName}");
            }

            return $this->json(['status' => 'done', 'cliId' => $client->getId(), 'wfiId' => $workerFirm->getId()], 200);

        } else {
            return $this->buildErrorArray($clientForm);
        }}
    }

    /**
     * @param Request $request
     * @param $orgId
     * @return mixed
     * @Route("/settings/users/create", name="addUser")
     */
    public function addUserAction(Request $request){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $userGlobalId = isset($_POST['ugid']) && $_POST['ugid'] != "" ? $_POST['ugid'] : null;
        $em = $this->em;
        $user = $em->getRepository(User::class)->findOneBy(['username' => $username, 'organization' => $this->org]);
        if($user){
            if($user->getDeleted()){
                $user->setDeleted(null);
                $em->persist($user);
                $email = $email ?: $user->getEmail();
            }
            if($user->getEmail()){
               return new JsonResponse(['msg' => 'existingUser'], 500);
            }
        } else {

            $user = new User();
            $nameElmts = explode(" ", $username, 2); 
            $firstname = trim($nameElmts[0]);
            $lastname = trim($nameElmts[1]);
            $token = md5(rand());
            $user
                ->setUsername($username)
                ->setFirstname($firstname)
                ->setLastname($lastname)
                ->setEmail($email)
                ->setToken($token)
                ->setOrganization($this->org)
                ->setRole(USER::ROLE_ADMIN);
           
            /** @var UserGlobal */
            $userGlobal = $userGlobalId ? $em->getRepository(UserGlobal::class)->find($userGlobalId) : new UserGlobal();
            if(!$userGlobal->getId()){
                $userGlobal->setUsername($username);
            }
            $userGlobal->addUserAccount($user);
            $em->persist($userGlobal);
        }

        $em->flush();
        
        if($email){
            $settings = [];
            $settings['token'] = $user->getToken();
            $settings['invitingUser'] = $this->user;
            $this->forward('App\Controller\MailController::sendMail', ['recipients' => [$user], 'settings' => $settings, 'actionType' => 'internalInvitation']);
        }
        return new JsonResponse(['id' => $user->getId()]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/clients/create", name="addClient")
     */
    public function addClientAction(Request $request)
    {
        $currentUser = $this->user;
        $em = $this->em;
        $organization = $this->org;
        $client = empty($_POST['cid']) ? new Client : $em->getRepository(Client::class)->find($_POST['cid']);
        $type = $_POST['gen-type'];
        $isIndependant = $type == 'i';
        $entityName = $isIndependant ? $_POST['username'] : $_POST['firmname'];
        $hasOrgMainFolder = true;
        $response = [];

        // Create worker firm, and organization if necessary

        if(!$isIndependant && empty($_POST['wid'])){

            $workerFirm = new WorkerFirm;
            $workerFirm->setCommonName($entityName)
                ->setName($entityName)
                ->setCreatedBy($currentUser->getId());
            $em->persist($workerFirm);
            $em->flush();

        } else {
            $workerFirm = $em->getRepository(WorkerFirm::class)->find($_POST['wid']);
        }

        if($organization->getType() == 'I'){
            $addedClientUsers = [$currentUser];
        } else {
            $addedClientUsers = $organization->getUsers()->filter(fn(User $u) => $u == $currentUser || $u->isSynthetic())->getValues();
        }

        if(empty($_POST['oid'])){

            $clientOrganization = new Organization;
            $now = new DateTime;
            $clientOrganization
                ->setCommname($entityName)
                ->setType(strtoupper($type))
                ->setExpired($now->add(new DateInterval('P21D')))
                ->setWeightType('role')
                ->setPlan(ORGANIZATION::PLAN_PREMIUM)
                ->setWorkerFirm($workerFirm)
                ->setCreatedBy($currentUser->getId());
            $em->persist($clientOrganization);

            $this->forward('App\Controller\OrganizationController::updateOrgFeatures', ['organization' => $clientOrganization, 'existingOrg' => false, 'createSynthUser' => true, 'addedAsClient' => true, 'addedClientUsers' => $addedClientUsers]);
            $hasOrgMainFolder = false;

        } else {

            $clientOrganization = $em->getRepository(Organization::class)->find($_POST['oid']);
            if($clientOrganization != $currentUser->getOrganization() && empty($_POST['cid'])){
                $this->forward('App\Controller\OrganizationController::updateOrgFeatures', ['organization' => $clientOrganization, 'addedAsClient' => true, 'addedClientUsers' => $addedClientUsers]);
            }

        }

        $synthUser = $em->getRepository(User::class)->findOneBy(['organization' => $clientOrganization, 'synthetic' => true]);
        
        if($clientOrganization != $organization && (empty($_POST['cid']) || empty($_POST['oid']))){

            /** @var ExternalUser */
            $externalSynthUser = new ExternalUser;
            $externalSynthUser->setUser($synthUser)
                ->setOwner(true)->setFirstname($organization->getCommname())
                ->setSynthetic(true)
                ->setLastname($entityName);

            $client
                ->setName($entityName)
                ->addExternalUser($externalSynthUser)
                ->setOrganization($organization)
                ->setClientOrganization($clientOrganization)
                ->setType($type)
                ->setWorkerFirm($workerFirm)
                ->setCreatedBy($currentUser->getId());

            $em->persist($client);
            $em->flush();

            if(!$hasOrgMainFolder){
                $orgId = $clientOrganization->getId();
                $orgName = strtolower(implode("-",explode(" ",$entityName)));
                mkdir(dirname(dirname(__DIR__)) . "/public/lib/cdocs/{$orgId}-{$orgName}");
            }

        }
        
        $response['cid'] = $client->getId();
        
        if(!$isIndependant && empty($_POST['wid'])){
            $response['wid'] = $workerFirm->getId();
        }
        if(empty($_POST['oid'])){
            $response['oid'] = $clientOrganization->getId();
        }
        
        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $cliId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/client/{cliId}/users/create", name="addClientUser")
     */
    public function addClientUserAction(Request $request, $cliId, TranslatorInterface $translator){
        
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }

        $emailVal = $_POST['email'] != "" ? $_POST['email'] : null;
        $usrId = $_POST['uid'] != "" ? $_POST['uid'] : null;
        
        $em = $this->em;
        $user = $usrId ? $em->getRepository(User::class)->find($usrId) : null;
        $username = $_POST['username'];
        $repoC = $em->getRepository(Client::class);
        $client             = $repoC->find($cliId);
        $clientOrganization = $client->getClientOrganization();
        $nameElmts = explode(" ", $username, 2); 
        $firstname = trim($nameElmts[0]);
        $lastname = trim($nameElmts[1]);
        $email = $user ? $user->getEmail() : $emailVal;

        $externalUser = new ExternalUser();
        $externalUser->setFirstname($firstname)
            ->setLastname($lastname)
            ->setClient($client)
            ->setEmail($email);

        $token                = md5(rand());
        if(!$user){
            $user                 = new User;
            $userGlobal = new UserGlobal();
            $user
                ->addExternalUser($externalUser)
                ->setToken($token)
                ->setFirstname($externalUser->getFirstname())
                ->setLastname($externalUser->getLastname())
                ->setUsername($username)
                ->setEmail($externalUser->getEmail())
                ->setRole(!$clientOrganization->hasActiveAdmin() ? USER::ROLE_ADMIN : USER::ROLE_AM)
                ->setWeightIni($externalUser->getWeightValue() ?: 100)
                ->setOrganization($clientOrganization)
                ->setCreatedBy($currentUser->getId());
            
            $userGlobal->setUsername($externalUser->getFirstname().' '.$externalUser->getLastname());
            $userGlobal->addUserAccount($user);
            $em->persist($userGlobal);
            $settings['tokens'][] = $token;
        } else {
            if(!$user->getEmail()){
                $settings['tokens'][] = $user->getToken();
                $user->setEmail($email);
            }
            $user->addExternalUser($externalUser);
        }

        $em->flush();

        $recipients[] = $user;
        $settings['invitingUser'] = $this->user;
        $settings['invitingOrganization'] = $this->org;
        $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'externalInvitation']);
        $alreadyConnected = $user->getLastConnected() != null;
        if($alreadyConnected){
            $status = 'a';
            $msg = $translator->trans('client_update.external_user.active_badge_msg');
        } else {
            if($email){
                $status = 'nc';
                $msg = $translator->trans('client_update.external_user.inactive_unconnected_msg');
            } else {
                $status = 'v';
                $msg = $translator->trans('client_update.external_user.inactive_virtual_msg');
            }
        }
        return new JsonResponse(['id' => $user->getId(), 'eid' => $externalUser->getId(), 'status' => $status, 'msg' => $msg]);
    }

    /**
     * @param Request $request
     * @param $cliId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/client/{cliId}", name="manageClient")
     */
    public function manageClientAction(Request $request, int $cliId){
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $em = $this->em;
        $repoC = $em->getRepository(Client::class);
        $client = $repoC->find($cliId);
        return $this->render('client_update.html.twig',
            [
                'client' => $client,
            ]
        );
    }

    /**
     * @param Request $request
     * @param $cliId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/users/{usrId}/update", name="updateUser")
     */
    public function updateUserAction(Request $request, int $usrId){
        $organization = $this->org;
        $positionName = $_POST['position'] != "" ? $_POST['position'] : null;
        $departmentName = $_POST['department'] != "" ? $_POST['department'] : null;
        $username = $_POST['username'];
        $email = $_POST['email'] != "" ?  $_POST['email'] : null;
        $em = $this->em;
        /** @var User */
        $user = $em->getRepository(User::class)->find($usrId);
        $userDepartment = $user->getDepartment();
        $userPosition = $user->getPosition();
        $dptToRemove = false;

        $department = $departmentName ? (
            $organization->getDepartments()->filter(fn(Department $d) => $d->getName() == $departmentName)->first() ?: new Department 
        ) : null;
        
        if($department != $userDepartment){
               
            if($userDepartment){
                $userDepartment->removeUser($user);
                if(!$userDepartment->getUsers()->count()){
                    $dptToRemove = true;
                    $organization->removeDepartment($userDepartment);
                }
            }
            
            if($department && !$department->getId() && $departmentName){
                $department->setOrganization($organization)
                    ->setName($departmentName);  
            }
            
            if($department){
                $department->addUser($user);
                $organization->addDepartment($department);
            } else {
                $user->setDepartment(null);
                $organization->addUser($user);
            }           
        }

        $position = $positionName ? (
            $organization->getPositions()->filter(fn(Position $d) => $d->getName() == $positionName)->first() ?: new Position 
        ) : null;
        
        if($position != $userPosition){
               
            if($userPosition){
                $userPosition->removeUser($user);
                if(!$userPosition->getUsers()->count()){
                    $dptToRemove = true;
                    $organization->removePosition($userPosition);
                }
            }

            if($position && !$position->getId() && $positionName){
                $position->setOrganization($organization)
                    ->setName($positionName);  
            }
            
            if($position){
                $position->addUser($user);
                $organization->addPosition($position);
            } else {
                $user->setPosition(null);
                $organization->addUser($user);
            }            
        }
       
        $em->persist($organization);
        $em->flush();

        $response = [];
        if($dptToRemove){
            $response['rd'] = 1;
        }

        if($department){
            $response['did'] = $department->getId();
        }
        if($position){
            $response['pid'] = $position->getId();
        }
        
        return new JsonResponse($response);
    }


    /**
     * @param Request $request
     * @param $cliId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/client/{cliId}/users/{extUsrId}/update", name="updateClientUser")
     */
    public function updateClientUserAction(Request $request, int $cliId, int $extUsrId, TranslatorInterface $translator){
        $position = $_POST['position'];
        $email = $_POST['email'] == "" ? null : $_POST['email'];
        
        $username = isset($_POST['username']) ? $_POST['username'] : null;
        $em = $this->em;
        /** @var ExternalUser */
        $externalUser = $em->getRepository(ExternalUser::class)->find($extUsrId);
        $wasNotInvited = false;
        $user = $externalUser->getUser();
        if(!$externalUser->getEmail() || !$user->getEmail()){
            $wasNotInvited = true;
        }
        $externalUser->setPositionName($position)
            ->setEmail($email);


        $alreadyConnected = $user->getLastConnected() != null;
        if($alreadyConnected){
            $status = 'a';
            $msg = $translator->trans('client_update.external_user.active_badge_msg');
        } else {
            if($email){
                $status = 'nc';
                $msg = $translator->trans('client_update.external_user.inactive_unconnected_msg');
            } else {
                $status = 'v';
                $msg = $translator->trans('client_update.external_user.inactive_virtual_msg');
            }
        }
        
        if(!$user->getEmail()){

            $user->setEmail($email);
            $em->persist($user);
            $em->flush();
            $settings['tokens'][] = $user->getToken();
            $recipients[] = $user;
            $settings['invitingUser'] = $this->user;
            $settings['invitingOrganization'] = $this->org;
            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'externalInvitation']);
        } else {
            
        }

        if($username){
            $nameElmts = explode(" ", $username, 2); 
            $firstname = trim($nameElmts[0]);
            $lastname = trim($nameElmts[1]);
            $externalUser->setFirstname($firstname)
                ->setLastname($lastname);
        }
        $em->persist($externalUser);
        $em->flush();
        return new JsonResponse(['status' => $status,'msg' => $msg]);
    }



    /**
     * @param Request $request
     * @param Application $app
     * @param $entity
     * @param $elmtId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/targets/{entity}/{elmtId}", name="updateElementTargets")
     */
    public function updateElementTargetsAction(Request $request, $entity, $elmtId)
    {
        $em          = $this->em;
        $repoO       = $em->getRepository(Organization::class);
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $currentUserOrganization = $currentUser->getOrganization();
        $targets                 = [];

        if ($entity == 'user') {
            $targets      = $em->getRepository(Target::class)->findBy(['user' => $elmtId]);
            $repoU        = $em->getRepository(User::class);
            $user         = $repoU->find($elmtId);
            $organization = $repoO->find($user->getOrgId());
            $element      = $user;
            $elementName  = $user->getFullName();

        } else if ($entity == 'team') {
            $repoT        = $em->getRepository(Team::class);
            $team         = $repoT->find($elmtId);
            $organization = $team->getOrganization();
            $element      = $team;
            $elementName  = $team->getName();

        } else if ($entity == 'department') {
            $repoD        = $em->getRepository(Department::class);
            $department   = $repoD->find($elmtId);
            $organization = $department->getOrganization();
            $element      = $department;
            $elementName  = $department->getName();

        } else if ($entity == 'position') {
            $repoP        = $em->getRepository(Position::class);
            $position     = $repoP->find($elmtId);
            $organization = $position->getOrganization();
            $element      = $position;
            $elementName  = $position->getName();

        } else {
            $targets      = $em->getRepository(Target::class)->findBy(['organization' => $elmtId]);
            $organization = $currentUserOrganization;
            $element      = $organization;
            $elementName  = $organization->getCommname();

        }

        $hasPageAccess = true;
        if ($entity != 'team') {
            if ($currentUser->getRole() != 4 && ($organization != $currentUserOrganization || $currentUser->getRole() != 1 && $currentUser->getId() != $elmtId)) {
                $hasPageAccess = false;
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

            
            $addElementTargetForm = $this->createForm(AddElementTargetForm::class, $element, ['standalone' => true, 'elmtType' => $entity, 'organization' => $organization]);
            $addElementTargetForm->handleRequest($request);
            if ($addElementTargetForm->isSubmitted()) {
            if ($addElementTargetForm->isValid()) {
                /*foreach ($addElementTargetForm->get('targets')->getData() as $userTarget) {
                if ($userTarget->getOrganization() == null) {
                $userTarget->setOrganization($organization);
                $em->persist($userTarget);
                }
                }*/
                $em->persist($element);
                $em->flush();
                switch ($entity) {
                    case 'user':
                    case 'team':
                        return $this->redirectToRoute('manageUsers');
                        break;
                    case 'department':
                        return $this->redirectToRoute('updateOrganizationElements', ['orgId' => $currentUserOrganization, 'entity' => 'department']);
                        break;
                    case 'position':
                        return $this->redirectToRoute('updateOrganizationElements', ['orgId' => $currentUserOrganization, 'entity' => 'position']);
                        break;
                    case 'organization':
                        return $this->redirectToRoute('firmSettings');
                        break;
                    case 'criterion':
                        break;
                }
            }}

            return $this->render('element_targets.html.twig',
                [
                    'targets'     => $targets,
                    'elementName' => $elementName,
                    'form'        => $addElementTargetForm->createView(),
                ]);
        }
    }

    // Function which updates either departments or positions, depending of parameter $entity

    /**
     * @param Request $request
     * @param $entity
     * @param $orgId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/organization/elements/{entity}s", name="updateOrganizationElements")
     */
    public function updateOrganizationElementsAction(Request $request, $entity)
    {
        $em    = $this->em;
        $repoO = $em->getRepository(Organization::class);

        switch ($entity) {
            case 'department':
                $repoE = $em->getRepository(Department::class);
                break;
            case 'position':
                $repoE = $em->getRepository(Position::class);
                break;
            case 'title':
                $repoE = $em->getRepository(Title::class);
                break;
            default:
                $repoE = $em->getRepository(Process::class);
                break;
        }
        $currentUser = $this->user;
        $organization = $currentUser->getOrganization();
        $role                    = $currentUser->getRole();
        $elements                = $repoE->findBy(['organization' => $currentUser->getOrganization()]);

        $hasPageAccess = true;

        if (
            $role === 3 or $role === 2
            or
            ($role === 1 and $organization != $currentUser->getOrganization())
        ) {
            $hasPageAccess = false;
        }

        if (!$hasPageAccess) {
            return $this->render('errors/403.html.twig');
        }

        $manageOrganizationElementsForm = $this->createForm(ManageOrganizationElementsForm::class, $organization, ['standalone' => true, 'elmtType' => $entity]);
        $manageOrganizationElementsForm->handleRequest($request);
        if ($manageOrganizationElementsForm->isSubmitted()) {
            if ($manageOrganizationElementsForm->isValid()) {
                $em->persist($organization);
                $em->flush();
                return $this->redirectToRoute('firmSettings');
            }
        }

        return $this->render('organization_element_list.html.twig',
            [
                'elmtType' => $entity,
                'elements' => $elements,
                'orgid' => $organization->getId(),
                'form'     => $manageOrganizationElementsForm->createView(),
                'UsersWithoutOrgElement' => $organization->getUsersWithoutJobInfo($entity)
            ]);
    }

    /**
     * @param Request $request
     * @param $etityelmtName
     * @param $elmtId
     * @param $orgId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/organization/{orgId}/{entity}/validate/{elmtId}", name="validateOrganizationElement")
     */
    public function validateOrganizationElementAction(Request $request, $entity, $elmtId, $orgId)
    {
        $em    = $this->em;


        $repoO = $em->getRepository(Organization::class);
        switch ($entity) {
            case 'department':
                $repoE   = $em->getRepository(Department::class);
                $element = new Department;
                break;
            case 'position':
                $repoE   = $em->getRepository(Position::class);
                $element = new Position;
                break;
            case 'title':
                $repoE   = $em->getRepository(Title::class);
                $element = new Title;
                break;
            case 'weight':
                $repoE   = $em->getRepository(Weight::class);
                $element = new Weight;
                break;
            default:
                break;
        }
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $organization = $currentUser->getOrganization();
        /*
        $currentUserOrganization = $repoO->find($currentUser->getOrganization());
        $organization            = $repoO->find($orgId);
        $hasPageAccess           = true;

        if ($currentUser->getRole() != 4 && ($organization != $currentUserOrganization || $currentUser->getRole() != 1 && $currentUser->getId() != $elmtId)) {
            $hasPageAccess = false;
        }

        if (!$hasPageAccess) {
            return $this->render('errors/403.html.twig');
        } else {
            */
            
            if ($elmtId != 0) {
                $element = $repoE->find($elmtId);
            } else {
                $element->setOrganization($organization)->setCreatedBy($this->user->getId());
            }

            $organizationElmtForm = $this->createForm(OrganizationElementType::class, $element, ['standalone' => false, 'entity' => $entity, 'organization' => $organization]);
            $organizationElmtForm->handleRequest($request);

        if ($organizationElmtForm->isSubmitted()) {
            if ($organizationElmtForm->isValid()) {
                $element->setCreatedBy($currentUser->getId());
                $em->persist($element);
                $em->flush();
                $output = ['message' => 'Success!', 'id' => $element->getId()];
                if ($entity != 'weight') {
                    $output['name'] = $element->getName();
                }
                return new JsonResponse($output, 200);
            } else {
                return new JsonResponse("Duplicate Element !", 500);
                //$errors = $this->buildErrorArray($organizationElmtForm);
                //return new JsonResponse($errors, 500);
            }
        }else{
        return new JsonResponse("form no submitted !", 500);
        }
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $entity
     * @param $elmtId
     * @param $orgId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/organization/{orgId}/{entity}/delete/{elmtId}", name="deleteOrganizationElement")
     */
    public function deleteOrganizationElementAction(Request $request, $entity, $elmtId, $orgId)
    {

        $em                      = $this->em;
        $repoO                   = $em->getRepository(Organization::class);
        $repoU                   = $em->getRepository(User::class);
        $organization            = $repoO->find($orgId);
        $currentUser             = $this->user;
        $currentUserOrganization = $currentUser->getOrganization();
        $hasPageAccess           = true;

        if ($currentUser->getRole() != 4 && ($organization != $currentUserOrganization || $currentUser->getRole() != 1)) {
            $hasPageAccess = false;
        }

        if (!$hasPageAccess) {
            return $this->render('errors/403.html.twig');
        } else {
            // $organization = $target->getOrganization();

            $repoE   = ($entity == 'department') ? $em->getRepository(Department::class) : $em->getRepository(Position::class);
            $element = $repoE->find($elmtId);
            if ($entity == 'department') {
                $usersWithDpt = $repoU->findByDptId($elmtId);
                foreach ($usersWithDpt as $userWithDpt) {
                    $userWithDpt->setDptId(null);
                    $em->persist($userWithDpt);
                }
                $organization->removeDepartment($element);
            } else {
                $usersWithPos = $repoU->findByPosId($elmtId);
                foreach ($usersWithPos as $userWithPos) {
                    $userWithPos->setPosId(null);
                    $em->persist($userWithPos);
                }
                $organization->removePosition($element);
            }
            $em->persist($organization);
            $em->flush();

            return new JsonResponse(['message' => 'Success!'], 200);
        }

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/organization/{orgId}/criteria", name="updateCriterionNames")
     */
    public function updateCriterionNamesAction(Request $request, $orgId)
    {
        $em          = $this->em;
        $repoO       = $em->getRepository(Organization::class);
        $repoC       = $em->getRepository(CriterionName::class);
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $currentUserOrganization = $currentUser->getOrganization();
        $organization            = $repoO->find($orgId);
        $criterionNames          = $repoC->findBy(['organization' => $orgId]);

        $hasPageAccess = true;

        if ($currentUser->getRole() != 4 && ($organization != $currentUserOrganization || $currentUser->getRole() != 1 && $currentUser->getId() != $elmtId)) {
            $hasPageAccess = false;
        }

        if (!$hasPageAccess) {
            return $this->render('errors/403.html.twig');
        } else {

            
            $addNewCriterionForm = $this->createForm(ManageCriterionNameForm::class, $organization, ['standalone' => true]);
            $addNewCriterionForm->handleRequest($request);

            if ($addNewCriterionForm->isValid()) {
                $em->persist($organization);
                $em->flush();
                return $this->redirectToRoute('firmSettings');
            }

            $unremovableCriterionNameIds = [];
            foreach ($organization->getCriteria() as $criterion) {
                if (!in_array($criterion->getCName(), $unremovableCriterionNameIds)) {
                    $unremovableCriterionNameIds[] = $criterion->getCName();
                    if (count($unremovableCriterionNameIds) == count($organization->getCriterionNames())) {
                        break;
                    }
                }
            }

            return $this->render('organization_criterion_names.html.twig',
                [
                    'unremovableCriterionNameIds' => $unremovableCriterionNameIds,
                    'criterionNames'              => $criterionNames,
                    'form'                        => $addNewCriterionForm->createView(),
                ]);
        }
    }

    public function updateOrganizationTargetsAction(Request $request)
    {
        $em          = $this->em;
        $repoO       = $em->getRepository(Organization::class);
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $currentUserOrganization = $currentUser->getOrganization();

        $hasPageAccess = true;

        if ($currentUser->getRole() != 4 && ($currentUser->getRole() != 1 && $currentUser->getId() != $elmtId)) {
            $hasPageAccess = false;
        }

        if (!$hasPageAccess) {
            return $this->render('errors/403.html.twig');
        } else {

            
            $addElementTargetForm = $this->createForm(AddOrganizationTargetForm::class, $organization, ['standalone' => true, 'app' => $app, 'organization' => $organization]);
            $addElementTargetForm->handleRequest($request);

            if ($addElementTargetForm->isValid()) {
                foreach ($addElementTargetForm->get('targets')->getData() as $userTarget) {
                    if ($userTarget->getOrganization() == null) {
                        $userTarget->setOrganization($organization);
                        $em->persist($userTarget);
                    }
                }
                $em->persist($element);
                $em->flush();
                return $this->redirectToRoute('manageUsers');
            }

            return $this->render('element_targets.html.twig',
                [
                    'username' => ($entity == 'user') ? $user->getFullName() : $team->getName(),
                    'form'     => $addElementTargetForm->createView(),
                    'element'  => $element,
                ]);
        }
    }

    public function deleteTargetAction(Request $request, $tgtId)
    {
        $em          = $this->em;
        $repoT       = $em->getRepository(Target::class);
        $repoO       = $em->getRepository(Organization::class);
        $repoU       = $em->getRepository(User::class);
        $target      = $repoT->find($tgtId);
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        if ($target->getUser() != null) {
            $currentUserTarget = $repoU->find($target->getUser());
        } elseif ($target->getOrganization() != null) {
            $currentUserTarget = $repoO->find($target->getOrganization());
        }
        // $organization = $target->getOrganization();

        $currentUserTarget->removeTarget($target);
        $em->persist($currentUserTarget);
        $em->flush();

        return $this->redirectToRoute('firmSettings');
    }

    /**
     * @param int $usrId
     * @param $superior
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/user/{usrId}/superior/{superior}", name="defineSuperior")
     */
    public function defineSuperior(int $usrId, $superior)
    {
        $em   = $this->em;
        /** @var User|null */
        $user = $em->getRepository(User::class)->find($usrId);
        if (!$user) {
            throw new NotFoundHttpException;
        }

        $user->setSuperior($superior);
        $em->persist($user);
        $em->flush();
        return new JsonResponse(['msg' => 'Success'], 200);
    }

    // Display all users (when HR clicks on "users" from /settings)

    /**
     * @param Application $app
     * @return RedirectResponse
     * @Route("/users-and-partners", name="manageUsers")
     * @Route("/colleagues-teams", name="seeColleaguesTeams")
     */
    public function getAllUsersAction(Request $request)
    {
        $user = $this->user;
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $entityManager                     = $this->em;
        $repoU                             = $entityManager->getRepository(User::class);
        $organization                      = $user->getOrganization();
        $orgEnabledUserCreatingUser        = false;
        $orgEnabledUserSeeAllUsers         = false;
        $orgEnabledUserSeePeersResults     = false;
        $enabledUserSeeSnapshotSupResults  = false;
        $enabledSuperiorOverviewSubResults = false;
        $enabledSuperiorSettingTargets     = false;
        $enabledSuperiorModifySubordinate  = false;
        $nbViewableInternalUsers           = 0;


        // Only administrators or roots can create/update users who have the ability to create users themselves
        $orgOptions = $organization->getOptions();
        foreach ($orgOptions as $orgOption) {
            switch ($orgOption->getOName()->getName()) {
                case 'enabledSuperiorModifySubordinate':
                    $enabledSuperiorModifySubordinate = $orgOption->isOptionTrue();
                    break;
                case 'enabledSuperiorOverviewSubResults':
                    $enabledSuperiorOverviewSubResults = $orgOption->isOptionTrue();
                    break;
                case 'enabledSuperiorSettingTargets':
                    $enabledSuperiorSettingTargets = $orgOption->isOptionTrue();
                    break;
                case 'enabledUserCreatingUser':
                    $orgEnabledUserCreatingUser = $orgOption->isOptionTrue();
                    break;
                case 'enabledUserSeeAllUsers':
                    $orgEnabledUserSeeAllUsers = $orgOption->isOptionTrue();
                    break;
                case 'enabledUserSeeSnapshotPeersResults':
                    $orgEnabledUserSeePeersResults = $orgOption->isOptionTrue();
                    break;
                case 'enabledUserSeeSnapshotSupResults':
                    $enabledUserSeeSnapshotSupResults = $orgOption->isOptionTrue();
                    break;
                case 'enabledUserSeeRanking':
                    $orgEnabledUserSeeRanking = $orgOption->isOptionTrue();
                    break;
            }
        }

        $clientFirms       = new ArrayCollection;
        $clientTeams       = new ArrayCollection;
        $clientIndividuals = new ArrayCollection;
        $clients           = $organization->getClients();
        $totalClientUsers  = 0;

        foreach ($clients as $client) {
            $canSeeClient = $user->getRole() == 4 || $user->getRole() == 1 || $organization->getPlan() > ORGANIZATION::PLAN_ENTERPRISE || $client->getCreatedBy() == $user->getId();
            if ($canSeeClient) {
                switch ($client->getClientOrganization()->getType()) {
                    case 'F':
                        $clientFirms->add($client);
                        break;
                    case 'T':
                        $clientTeams->add($client);
                        break;
                    case 'I':
                    case 'C':
                        $clientIndividuals->add($client);
                        break;
                }
            }
        }

        $totalClientUsers = 0;

        foreach ($organization->getClients() as $client) {
            $totalClientUsers += count($client->getExternalUsers()) - 1;
        }

        $nbViewableInternalUsers = count($organization->getActiveUsers());

        $users           = $organization->getActiveUsers();
        //$usersWithDpt    = $users->filter(fn(User $u) => $u->getDepartment() != null);
        $usersWithoutDpt = $users->filter(fn(User $u) => !$u->getDepartment() && !$u->isSynthetic());

        $viewableUsersWithoutDpt = $usersWithoutDpt;

        /*foreach ($usersWithoutDpt as $userWithoutDpt) {
            $canSeeUserWithoutDpt = $user->getRole() == 4 || ($user->getRole() == 1) || ($user->getCreatedBy() == $user->getId());
            if ($canSeeUserWithoutDpt) {$viewableUsersWithoutDpt[] = $userWithoutDpt;}
        }*/

        $viewableTeams = $organization->getTeams()->filter(function (Team $t) {return $t->getDeleted() == null;});

        //$dealingFirms = $organization->getDealingFirms();
        //$dealingTeams = $organization->getDealingTeams();
        $dealingTeams = $this->em->getRepository(Organization::class)->getDealingTeams($organization);

        return $this->render(
            'user_list.html.twig',
            [
                'rootDisplay'                       => false,
                //'dealingFirms'                      => $dealingFirms,
                'clientFirms'                       => $clientFirms,
                'clientTeams'                       => $clientTeams,
                'clientIndividuals'                 => $clientIndividuals,
                'setClientIconForm'                 => $this->createForm(SetClientIconForm::class),
                //'usersWithDpt'                      => $usersWithDpt,
                'organization'                      => $organization,
                //'viewableDepartments'               => $this->em->getRepository(Organization::class)->getUserSortedDepartments($this->user),
                'viewableDepartments'               => $organization->getDepartments(),
                'viewableTeams'                     => $viewableTeams,
                'dealingTeams'                      => $dealingTeams,
                'totalClientUsers'                  => $totalClientUsers,
                'usersWithoutDpt'                   => $viewableUsersWithoutDpt,
                'orgEnabledUserCreatingUser'        => $orgEnabledUserCreatingUser,
                'orgEnabledUserSeeAllUsers'         => $orgEnabledUserSeeAllUsers,
                'orgEnabledUserSeePeersResults'     => $orgEnabledUserSeePeersResults,
                'enabledUserSeeSnapshotSupResults'  => $enabledUserSeeSnapshotSupResults,
                'enabledSuperiorOverviewSubResults' => $enabledSuperiorOverviewSubResults,
                'enabledSuperiorSettingTargets'     => $enabledSuperiorSettingTargets,
                'enabledSuperiorModifySubordinate'  => $enabledSuperiorModifySubordinate,
                'nbViewableInternalUsers'           => $nbViewableInternalUsers,
                'orgEnabledUserSeeRanking'          => $orgEnabledUserSeeRanking ?? false,
            ]
        );
    }

    public function deleteIProcessStage(int $id)
    {
        $em        = $this->em;
        $stageRepo = $em->getRepository(IProcessStage::class);
        /** @var IProcessStage|null */
        $stage = $stageRepo->find($id);

        if (!$stage) {
            throw new NotFoundHttpException;
        }

        $iprocess = $stage->getInstitutionProcess();
        $iprocess->removeStage($stage);
        $em->persist($iprocess);
        $em->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param string $entity
     * @param int $stgId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/{entity}/stage/delete/{stgId}", name="ajaxStageDelete")
     */
    public function deleteStageAction(string $entity, int $stgId)
    {
        /*if ($elmt === 'iprocess') {
            return $this->deleteIProcessStage($stgId);
        }*/

        $em = $this->em;

        $stageRepo = null;
        switch ($entity) {
            case 'activity':
                $stageRepo = $em->getRepository(Stage::class);
                break;
            case 'iprocess':
                $stageRepo = $em->getRepository(IProcessStage::class);
                break;
            case 'process':
                $stageRepo = $em->getRepository(ProcessStage::class);
                break;
            case 'template':
                $stageRepo = $em->getRepository(TemplateStage::class);
                break;
        }

        /** @var Stage|TemplateStage|ProcessStage|IProcessStage|null */
        $stage = $stageRepo ? $stageRepo->find($stgId) : null;


        if (!$stage) {
            $message = sprintf('Stage %d not found', $stgId);
            return new JsonResponse(['status' => 'error', 'message' => $message], Response::HTTP_NOT_FOUND);
        }

        switch ($entity) {
            case 'activity':
            case 'template':
                $activity = $stage->getActivity();
                break;
            case 'iprocess':
                $activity = $stage->getInstitutionProcess();
                break;
            case 'process':
                $activity = $stage->getProcess();
                break;
        }

        $stgWeight = $stage->getActiveWeight();
        $impactedStages = $activity->getActiveStages();
        $impactedStages->removeElement($stage);
        $sumWeights = 0;
        foreach ($impactedStages as $activityStage) {


            $newWeight = ($activityStage != $impactedStages->last()) ?
                $activityStage->getActiveWeight() / (1 - $stgWeight) :
                1 - $sumWeights;
            $activityStage->setActiveWeight($newWeight);
            $sumWeights += $newWeight;
        }
        $activity->removeStage($stage);
        $em->persist($activity);
        $em->flush();

        return new JsonResponse(['status' => 'done']);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $entity
     * @param $stgId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/{entity}/stage/{stgId}/clear-output", name= "clearStageOutput")
     */
    public function clearStageOutputAction(Request $request, $entity, $stgId){
        $em       = $this->em;
        $stage = $em->getRepository(Stage::class)->find($stgId);
        $survey = $stage->getSurvey();
        $surveyDeletion = false;
        if($survey){
            $this->deleteSurveyAction($request,$app,$survey->getId(),false);
            $surveyDeletion = true;
        } else {

            foreach($stage->getCriteria() as $criterion){
                $this->deleteCriterionAction($request,$app,$entity,$criterion->getId(),1);
            }
        }
        return $app->json(['status' => 'done','surveyDeletion' => $surveyDeletion], 200);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $entity
     * @param $criId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/{entity}/criterion/delete/{criId}", name="ajaxCriterionDelete")
     */
    public function deleteCriterionAction(Request $request, $entity, $criId)
    {

        $em = $this->em;
        switch($entity){
            case 'activity' :
                $criterion = $em->getRepository(Criterion::class)->find($criId);
                break;
            case 'iprocess' :
                $criterion = $em->getRepository(IProcessCriterion::class)->find($criId);
                break;
            case 'process' :
                $criterion = $em->getRepository(ProcessCriterion::class)->find($criId);
                break;
            case 'template' :
                $criterion = $em->getRepository(TemplateCriterion::class)->find($criId);
                break;
        }

        if (!$criterion) {
            $message = sprintf('Criterion %d not found', $criId);
            return new JsonResponse(['status' => 'error', 'message' => $message], 404);
        }

        $criWeight = $criterion->getWeight();
        $output = $criterion->getOutput();
        $output->removeCriterion($criterion);

        $sumWeights = 0;

        foreach($output->getCriteria() as $outputCriterion) {

            $newWeight = ($outputCriterion != $output->getCriteria()->last()) ?
                round($outputCriterion->getWeight() / (1 - $criWeight), 2) :
                1 - $sumWeights;

            $outputCriterion->setWeight($newWeight);
            $sumWeights += $newWeight;

        }

        $em->persist($output);
        $em->flush();

        return new JsonResponse(['status' => 'done'], 200);
    }

    /**
     * @param Request $request
     * @param $actId
     * @return Response|RedirectResponse
     * @Route("/activities/delete", name="activityDelete")
     */
    public function deleteActivityAction(Request $request)
    {
        
        $returnType = $request->get('r');
        $actId = $request->get('id');
        
        $activity = $this->em->getRepository(Activity::class)->find($actId);
        //$activityM = new ActivityM($this->em, $this->stack, $this->security);
        if ($this->isDeletable($activity)) {
            $organization = $activity->getOrganization();
            $organization->removeActivity($activity);
            try {
                $this->em->persist($organization);
            } catch (ORMException $e) {
            }
            try {
                $this->em->flush();
            } catch (OptimisticLockException $e) {
            } catch (ORMException $e) {
            }
        }

        if($returnType == 'json'){
            return new JsonResponse(['msg' => 'success'],200);
        } else {
            return $this->redirectToRoute("myActivities");
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @Route("/organization/settings", name="oldFirmSettings")
     */
    public function displayFirmSettingsAction(Request $request)
    {

        $connectedUser = $this->user;
        $repoO                     = $this->em->getRepository(Organization::class);
        $organization              = $repoO->find($connectedUser->getOrganization());
        $enabledCreatingUserOption = false;
        $orgOptions                = $organization->getOptions();
        foreach ($orgOptions as $orgOption) {
            if ($orgOption->getOName()->getName() === 'enabledUserCreatingUser') {
                $enabledCreatingUserOption = $orgOption->isOptionTrue();
            }
        }
        $settingsOrganizationForm = $this->createForm(SettingsOrganizationForm::class, $organization, ['standalone' => true]);
        $settingsOrganizationForm->handleRequest($request);
        return $this->render('firm_settings.html.twig',
            [
                'form' => $settingsOrganizationForm->createView(),
                'user' => null,
            ]);
    }

    /**
     * @param Request $request
     * @return mixed
     * @Route("/organization/settings/manage", name="firmSettings")
     */
    public function manageFirmSettings(Request $request)
    {
        $currentUser = $this->user;
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        } else {
            if($currentUser->getRole() != USER::ROLE_SUPER_ADMIN){
                return new Response(null, Response::HTTP_FORBIDDEN);
            }
        }
        return $this->render('firm_management.html.twig',
            [
                
            ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/organization/profile/manage", name="manageOrgProfile")
     * @IsGranted("ROLE_ADMIN", statusCode=403)
     */
    public function manageOrganizationProfileAction(Request $request){
        $currentUser = $this->user;
        $organization = $currentUser->getOrganization();
        if (!$currentUser /*|| $organization->getCommname() != "Public"*/) {
            return $this->redirectToRoute('login');
        }


        $em = $this->em;
        
        //$workerIndividual = $currentUser->getWorkerIndividual();
        //$workerIndividualForm = $this->createForm(UpdateWorkerIndividualForm::class, $workerIndividual, ['standalone' => true]);
        $organizationProfileForm = $this->createForm(OrganizationProfileForm::class, $organization, ['standalone' => true]);
        $organizationProfileForm->handleRequest($request);
        $pictureForm = $this->createForm(AddPictureForm::class);
        $pictureForm->handleRequest($request);

        if ($organizationProfileForm->isSubmitted() && $organizationProfileForm->isValid()) {
            $em->persist($organization);
            $em->flush();
            return $this->redirectToRoute('firmSettings');
        }

        return $this->render('organization_profile.html.twig',
        [
            'form' => $organizationProfileForm->createView(),
            'pictureForm' => $pictureForm->createView(),
        ]);

    }

    /**
     * @param Request $request
     * @param Application $app
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/organization/settings/update", name="updateFirmSettings")
     */
    public function updateFirmSettingsAction(Request $request)
    {

        $connectedUser = $this->user;
        if (!$connectedUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $em                        = $this->em;
        $repoO                     = $em->getRepository(Organization::class);
        $repoOUO                   = $em->getRepository(OrganizationUserOption::class);
        $repoON                    = $em->getRepository(OptionName::class);
        $organization              = $repoO->find($connectedUser->getOrgId());
        
        $enabledCreatingUserOption = false;
        $orgOptions                = $organization->getOptions();
        foreach ($orgOptions as $orgOption) {
            if ($orgOption->getOName()->getName() == 'enabledUserCreatingUser') {
                $enabledCreatingUserOption = $orgOption->isOptionTrue();
            }
        }
        $settingsOrganizationForm = $this->createForm(SettingsOrganizationForm::class, $organization, ['standalone' => true]);
        $settingsOrganizationForm->handleRequest($request);

        if ($settingsOrganizationForm->isValid()) {

            $orgOptions         = $settingsOrganizationForm->get('options')->getData();
            $booleanOptionNames = [
                'enabledSuperiorSubRights',
                'enabledSuperiorSettingTargets',
                'enabledSuperiorModifySubordinate',
                'enabledSuperiorOverviewSubResults',
                'enabledUserSeeAllUsers',
                'enabledUserCreatingUser',
                'enabledUserSeeSnapshotPeersResults',
                'enabledUserSeeSnapshotSupResults',
                'enabledCNamesOutsideCGroups',
                'enabledUserSeeRanking',
                'enabledLeaderSeeAllGrades',
            ];

            foreach ($orgOptions as $key => $orgOption) {

                if (in_array($orgOption->getOName()->getName(), $booleanOptionNames)) {
                    //$organizationOption = $repoOUO->findOneBy(['organization' => $organization, 'oName' => $repoON->find(1)]);
                    $orgOption->setOptionTrue($_POST['settings_organization_form']['options'][$key]['optionTrue']);
                    $em->persist($orgOption);
                }
            }

            $em->flush();

            return new JsonResponse(['message' => 'success'], 200);

        } else {
            $errors = $this->buildErrorArray($settingsOrganizationForm);
            return $errors;
        }
    }

    /**
     * @param Application $app
     * @param string $entity
     * @return RedirectResponse
     * @Route("/settings/criterion/{entity}s-average-results", name="elementAvgResultPerCriterion")
     */
    public function elementAvgResultPerCriterionAction(Application $app, string $entity)
    {
        $currentUser = $this->user;
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        $org = $currentUser->getOrganization();
        $em  = $this->em;

        /** @var \Repository\OrganizationRepository */
        $orgRepo = $em->getRepository(Organization::class);

        $criteria = array_map(function (array $e) {
            $name  = $e[0];
            $count = $e[1];
            return "$name ($count)";
        }, $orgRepo->findCriteriaWithPublishedStages($org));

        return $this->render('element_result_per_criterion.html.twig', [
            'criterionChoices' => $criteria,
        ]);
    }

    // Admin user deletion function

    /**
     * @param Request $request
     * @param Application $app
     * @param $usrId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/organization/settings/user/{usrId}/delete", name="deleteUser")
     */
    public function deleteUserAction(Request $request, $usrId)
    {
        $em          = $this->em;
        $repoP      = $em->getRepository(Participation::class);
        $repoU       = $em->getRepository(User::class);

        /** @var User */
        $user        = $repoU->find($usrId);
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }

        if ($this->org != $user->getOrganization()) {
            return new JsonResponse(['message' => 'Error'], 500);
        }
        $organization = $user->getOrganization();
        // We remove completely the user if he did not participate to anything, otherwise we keep it in the DB in order to track his previous actions
        // (we should consider its anonymation)
        /*if (!sizeof($repoP->findByUser($user))) {
        //    $em->remove($user);
        } else {*/

        $user->setDeleted(new DateTime);
        $em->persist($user);
        $userGlobal = $user->getUserGlobal();
        $personalAccount = $userGlobal->getUserAccounts()->filter(fn(User $u) => $u->getOrganization()->getType() == 'C')->first();
        $otherNonPersonalAccounts = $userGlobal->getUserAccounts()->filter(fn(User $u) => $u != $user && $u->getOrganization()->getType() != 'C')->first();
        
        // We create a personal user account to the removed user in case he has none
        if(!$otherNonPersonalAccounts && !$personalAccount){
            $personalAccount = new User;
            $personalAccount->setFirstname($user->getFirstname())
                ->setLastname($user->getLastname())
                ->setUsername($userGlobal->getUsername())
                ->setEmail($userGlobal->getEmail())
                ->setRole(USER::ROLE_ADMIN)
                ->setToken(md5(rand()));

            $personalOrganization = new Organization;
                
            $personalOrganization->setCommname($userGlobal->getUsername())
                ->setType('C')
                ->addUser($personalAccount);
            
            $userGlobal->addUserAccount($personalAccount);
            
            $em->persist($userGlobal);
            $em->persist($personalOrganization);
            $em->flush();
                
            if($userGlobal->getEmail()){
                $recipients          = [];
                $recipients[]        = $user;
                $settings            = [];
                $settings['token'] = $user->getToken();
                $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'subscriptionConfirmation']);
            }
        }
        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $cliId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/client/{cliId}/delete", name="clientDelete")
     */
    public function deleteClientAction(Request $request, $cliId){
        $em = $this->em;
        $repoC  = $em->getRepository(Client::class);
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        /** @var Client */
        $client = $repoC->find($cliId);
        $clientOrganization = $client->getClientOrganization();
        $isDeletable = false;
        $orgUsers = $clientOrganization->getUsers();

        foreach($orgUsers as $user){
            if($user->getLastConnected() != null){
                $isDeletable = false;
                break;
            }
        }

        if($isDeletable){
            foreach ($orgUsers as $orgUser) {
                $em->remove($orgUser);
            }
            $em->remove($clientOrganization);
        } else {
            /** Do the code which anonymises participating users, and removed the non-participating ones */

        }

        $em->remove($client);
        $em->flush();
        return $this->json(['status' => 'done'], 200);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $extId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/external-user/{extId}/delete", name="clientUserDelete")
     */
    public function deleteClientUserAction(Request $request, $extId)
    {
        $em          = $this->em;
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $repoO       = $em->getRepository(Organization::class);
        $organization = $currentUser->getOrganization();
        $repoEU      = $em->getRepository(ExternalUser::class);
        $externalUser = $repoEU->find($extId);
        /** @var User */
        $relatedInternalUser = $externalUser->getUser();
        $repoP = $em->getRepository(Participation::class);
        $externalUserParticipations = new ArrayCollection($repoP->findBy(['activity' => $organization->getActivities()->getValues(), 'user' => $relatedInternalUser]));

        if(sizeof($externalUserParticipations) == 0){
            $em->remove($externalUser);
        } else {
            $currentFutureParticipations = $externalUserParticipations->filter(fn(Participation $p) => $p->getStage()->getStatus() < STAGE::STATUS_COMPLETED && $p->getStage()->getProgress() < STAGE::PROGRESS_COMPLETED);
            foreach($currentFutureParticipations as $currentFutureParticipation){
                $em->remove($currentFutureParticipation);
            }
            $externalUser->setDeleted(new DateTime);
            $em->persist($externalUser);
        }

        /*
        if($relatedInternalUser->getLastConnected() == null && sizeof($relatedInternalUser->getExternalUsers()) == 1){
            $em->remove($relatedInternalUser);
        }
        */

        $em->flush();
        return $this->json(['status' => 'done'], 200);
    }

    //Adds team(s) to current organization (limited to HR)

    /**
     * @param Request $request
     * @param Application $app
     * @param null $teaId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/teams/manage/{teaId}", name="manageTeam")
     */
    public function manageTeamAction(Request $request, $teaId = null)
    {
        $em = $this->em;
        $repoT = $em->getRepository(Team::class);
        $team = $teaId ? $repoT->find($teaId) : new Team;

        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }

        if (!$team->isModifiable()) {
            return $this->render('errors/403.html.twig');
        }

        if($teaId == null){
            $organization = $currentUser->getOrganization();
            $team->setOrganization($organization);
            $team->setName($organization->getTeams()->count() + 1);
        }
        
        $manageTeamForm = $this->createForm(AddTeamForm::class, $team, ['standalone' => true]);
        $manageTeamForm->handleRequest($request);
        if($manageTeamForm->isValid()){
            $em->flush();
            return $this->redirectToRoute('manageUsers');
        }
        return $this->render('team_manage.html.twig',
            [
                'form' => $manageTeamForm->createView(),
            ]);
    }

    public function addAjaxTeamAction(Request $request)
    {

        $em          = $this->em;
        $repoU       = $em->getRepository(User::class);
        $repoO       = $em->getRepository(Organization::class);
        $repoT       = $em->getRepository(Team::class);
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $organization = $currentUser->getOrganization();

        //$data = json_decode($_POST['form'], true);
        if ($_POST['team-name'] == null) {
            return new JsonResponse(['missingName' => ''], 200);
        } else if ($repoT->findOneBy(['organization' => $organization, 'name' => $_POST['team-name']])) {
            return new JsonResponse(['duplicateTeamName' => ''], 200);
        } else if (count($_POST) < 3) {
            return new JsonResponse(['incompleteTeam' => ''], 200);
        } else {

            $team = new Team;
            $team->setName($_POST['team-name'])->setOrganization($organization);
            $team->setCreatedBy($currentUser->getId());
            $em->persist($team);

            $usersId = [];

            foreach ($_POST as $key => $value) {
                if ($key != 'team-name') {
                    $usrId     = substr($key, 14);
                    $usersId[] = $usrId;
                    $member  = new Member;
                    $member->setTeam($team)->setUsrId($usrId);
                    $em->persist($member);
                }
            }
            $em->flush();

            $mailableUsers = $repoU->findById($usersId);
            $recipients    = [];
            foreach ($mailableUsers as $mailableUser) {
                $recipients[] = $mailableUser;
            }

            $settings['team'] = $team;

            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'teamCreation']);

            return true;
        }

    }

    public function updateTeamAction(Request $request, $teaId)
    {

        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $adminFullName = $currentUser->getFirstname() . " " . $currentUser->getLastname();
        $id            = $currentUser->getId();
        $orgId         = $currentUser->getOrganization();
        $em            = $this->em;
        $repoO         = $em->getRepository(Organization::class);
        $repoT         = $em->getRepository(Team::class);
        $team          = $repoT->find($teaId);
        $organization  = $repoO->find($orgId);

        if ($currentUser->getRole() != 4 && $team->getOrganization() != $organization) {
            return $this->render('/errors/403.html.twig');
        }

        $members   = $team->getActiveMembers();
        $membersId = [];
        foreach ($members as $member) {
            $membersId[] = $member->getUser()->getId();
        }

        return $this->render('team_manage.html.twig',
            [
                'team'         => $team,
                'organization' => $organization,
                'membersId'  => $membersId,
                'delete'       => true,
            ]);

    }

    public function updateAjaxTeamAction(Request $request, $teaId)
    {

        $em          = $this->em;
        $repoU       = $em->getRepository(User::class);
        $repoO       = $em->getRepository(Organization::class);
        $repoT       = $em->getRepository(Team::class);
        $repoP       = $em->getRepository(Participation::class);
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $organization      = $currentUser->getOrganization();
        $team              = $repoT->find($teaId);
        $addedRecipients   = [];
        $removedRecipients = [];

        //$data = json_decode($_POST['form'], true);
        if ($_POST['team-name'] == null) {
            return new JsonResponse(['missingName' => ''], 200);
        } else if ($repoT->findOneBy(['organization' => $organization, 'name' => $_POST['team-name']]) != $team) {
            return new JsonResponse(['duplicateTeamName' => ''], 200);
        } else if (count($_POST) < 3) {
            return new JsonResponse(['incompleteTeam' => ''], 200);
        } else {

            $members = clone $team->getMembers();
            foreach ($members as $member) {
                $memberId       = $member->getUser($app)->getId();
                $allTeamUsersId[] = $memberId;

                if ($member->isDeleted() == false) {
                    $livingTeamUsersId[] = $memberId;
                } else {
                    $deletedTeamUsersId[] = $memberId;
                }
            }

            $usersId            = [];
            $addedUsersFullName = [];
            $deletedUsrIds      = [];

            $teamParticipations = new ArrayCollection($repoP->findBy(['team' => $teaId], ['activity' => 'ASC']));

            // 1 - We look whether there is a team joiner...
            foreach ($_POST as $key => $value) {

                if ($key != 'team-name') {

                    $usrId = substr($key, 14);

                    if (array_search($usrId, $livingTeamUsersId) === false) {

                        $addedUser            = $repoU->find($usrId);
                        $addedUsersFullName[] = $addedUser->getFullName();

                        // Condition below means added users used to belong to team, we remove his deleted condition
                        if (isset($deletedTeamUsersId) && array_search($usrId, $deletedTeamUsersId) !== false) {
                            foreach ($members as $member) {
                                if ($member->getUsrId() == $usrId) {
                                    $deletedTeamUser = $member;
                                    break;
                                }
                            }
                            $deletedTeamUser->setIsDeleted(false);
                            $em->persist($deletedTeamUser);

                        } else {

                            $member = new Member;
                            $member->setTeam($team)->setUser($usrId);
                            $em->persist($member);

                        }

                        // Team joiners (new or former) will receive a team welcome mail
                        $addedRecipients[] = $addedUser;
                    }
                    $usersId[] = $usrId;
                }
            }

            if (count($addedRecipients) > 0) {
                $settings['team'] = $team;
                $this->forward('App\Controller\MailController::sendMail', ['recipients' => $addedRecipients, 'settings' => $settings, 'actionType' => 'teamCreation']);

            }

            // 2 - ... and we look whether there are team leavers

            foreach ($members as $key => $member) {
                if (array_search($allTeamUsersId[$key], $usersId) === false) {

                    // We delete all his participations on activities which have not started.
                    //If one activity has started, we remove all his participations on future stages, and keep ongoing ones.
                    $deletedUsrIds[] = $allTeamUsersId[$key];
                    $member->setDeleted(new DateTime);
                    $member->setIsDeleted(true);
                    $removedRecipients[] = $repoU->find($member->getUsrId());
                    $em->persist($member);
                }
            }

            if (count($removedRecipients) > 0) {
                $settings['team'] = $team;
                $this->forward('App\Controller\MailController::sendMail', ['recipients' => $removedRecipients, 'settings' => $settings, 'actionType' => 'teamUserRemoval']);
            }

            // We then manage some operations in case some users were added

            if (count($addedUsersFullName) > 0) {

                $uncompletedTeamParticipations = $teamParticipations->filter(function (Participation $participation) {
                    return $participation->getActivity()->getStatus() < 2;
                });

                $recipients                     = [];
                $settings                       = [];
                $settings['addedUsersFullName'] = $addedUsersFullName;
                $settings['teamName']           = $team->getName();

                // Unvalidating team user participants of uncompleted activities, in order for them to grade team newcomer

                $unvalidatingTeamParticipations = $uncompletedTeamParticipations->matching(Criteria::create()->where(Criteria::expr()->eq("status", 3)));
                $consideredActivity             = null;
                foreach ($unvalidatingTeamParticipations as $unvalidatingTeamParticipation) {
                    if ($unvalidatingTeamParticipation->getActivity() != $consideredActivity) {
                        if (count($recipients) > 0) {
                            $settings['activity'] = $consideredActivity;
                            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'unvalidatedGradesTeamJoiner']);
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
                    $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'unvalidatedGradesTeamJoiner']);
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
                                ->setUsrId($usrId)
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

            if (count($deletedUsrIds) > 0) {
                $repoG                  = $em->getRepository(Grade::class);
                $memberParticipations = new ArrayCollection($repoP->findBy(['usrId' => $deletedUsrIds, 'team' => $team], ['activity' => 'ASC']));
                $definedActivity        = null;
                foreach ($memberParticipations as $memberParticipation) {
                    $activity = $memberParticipation->getActivity();
                    if ($activity != $definedActivity) {
                        $definedActivity = $activity;

                        if ($definedActivity->getStatus() < 1) {
                            $deletableTeamUserParticipations = $memberParticipations->matching(Criteria::create()->where(Criteria::expr()->eq("activity", $activity)));
                            foreach ($deletableTeamUserParticipations as $deletableTeamUserParticipation) {
                                $activity->removeGrade($deletableTeamUserParticipation);
                            }
                            $em->persist($activity);
                        } else if ($definedActivity->getStatus() == 1) {
                            foreach ($activity->getActiveStages() as $stage) {

                                $deletableTeamUserParticipations = $memberParticipations->matching(Criteria::create()->where(Criteria::expr()->eq("stage", $stage)));
                                $deletableTeamUserGivenGrades    = $repoG->findBy(['stage' => $stage, 'team' => $team, 'participant' => $deletableTeamUserParticipations->toArray()]);
                                $deletableTeamUserReceivedGrades = $repoG->findBy(['stage' => $stage, 'gradedTeaId' => $team->getId(), 'gradedUsrId' => $deletedUsrIds]);

                                foreach ($deletableTeamUserGivenGrades as $deletableTeamUserGivenGrade) {
                                    $stage->removeGrade($deletableTeamUserGivenGrade);
                                }

                                foreach ($deletableTeamUserReceivedGrades as $deletableTeamUserReceivedGrade) {
                                    $stage->removeGrade($deletableTeamUserReceivedGrade);
                                }

                                foreach ($deletableTeamUserParticipations as $deletableTeamUserParticipation) {
                                    $stage->removeParticipant($deletableTeamUserParticipation);
                                }

                                $em->persist($stage);
                                $em->flush();

                                // Check whether the stage is computable after team user removal
                                $this->checkStageComputability($request, $app, $stage);

                            }
                        }
                    }
                }
            }

            $team->setName($_POST['team-name']);
            $em->persist($team);
            $em->flush();
            $mailableUsers = $repoU->findById($usersId);
            $recipients    = [];
            foreach ($mailableUsers as $mailableUser) {
                $recipients[] = $mailableUser;
            }

            $settings['team'] = $team;

            return true;
        }

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $teaId
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/teams/delete/{teaId}", name="deleteTeam")
     */
    public function deleteTeamAction(Request $request, $teaId)
    {
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $orgId        = $currentUser->getOrgId();
        $em           = $this->em;
        $repoO        = $em->getRepository(Organization::class);
        $repoT        = $em->getRepository(Team::class);
        $team         = $repoT->find($teaId);
        $organization = $repoO->find($orgId);
        $teamName     = $team->getName();

        if ($team->getOrganization() != $organization) {
            return $this->render('/errors/403.html.twig');
        }

        // We choose to delete team previously, but we will keep it in database instead and set him a deleted timestamp

        $team->setDeleted(new DateTime);
        // $teamName .= "(Deleted)";
        // $team->setName($teamName);
        $organization->removeTeam($team);
        $em->persist($organization);

        $em->flush();
        return true;

    }

    // AJAX call to get all institutionProcesses in a sorted json from current firm

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/organization/json/processes", name="getIProcesses")
     */
    public function getAllIProcesses(Request $request): JsonResponse
    {
        $selfId = $request->get('selfId');

        return new JsonResponse(['id' => $selfId], 200);

        /** @var Organization */
        $organization = $this->user->getOrganization();
        $institutionProcesses = $organization->getInstitutionProcesses()->filter(static fn(InstitutionProcess $p) => $p->getParent() === null && $p->getId() != $selfId);
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

    // AJAX call to get all institutionProcesses in a sorted json from current firm

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/root/json/processes", name="getProcesses")
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="You don't have access to that page")
     */
    public function getAllProcesses(Request $request): JsonResponse
    {
        $repoO = $this->em->getRepository(Organization::class);
        $data = json_decode(file_get_contents('php://input'), true);
        $selfId = $data ? $data['selfId'] : null;
        $allProcesses = new ArrayCollection($this->em->getRepository(Process::class)->findAll());
        $institutionProcesses = $allProcesses->filter(static fn(Process $p) => $p->getParent() === null && $p->getId() != $selfId);
        
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
     * @param Application $app
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/organization/processes", name="manageIProcesses")
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="You don't have access to that page")
     */
    public function manageIProcessesAction(Request $request){

        $em = $this->em;
        $repoP = $em->getRepository(Process::class);
        $repoO = $em->getRepository(Organization::class);
        $currentUser = $this->user;
        $organization = $currentUser->getOrganization();
        $process = new InstitutionProcess();
        
        $manageForm = $this->createForm(ManageProcessForm::class, $organization, ['standalone' => true, 'isRoot' => false]);
        $manageForm->handleRequest($request);
        $createForm = $this->createForm(AddProcessForm::class, $process, ['standalone' => true, 'organization' => $organization,'entity' => 'iprocess']);
        $createForm->handleRequest($request);

        $validatingProcesses = $organization->getInstitutionProcesses()->filter(function(InstitutionProcess $p){return $p->isApprovable();});
        
        if($validatingProcesses->count() > 0){
            $validatingProcess = $validatingProcesses->first();
            $validateForm = $this->createForm(AddProcessForm::class, $validatingProcess, ['standalone' => true, 'organization' => $organization, 'entity' => 'iprocess']);
            $validateForm->handleRequest($request);
        } else {
            $validateForm = null;
        }

        if ($manageForm->isSubmitted() && $manageForm->isValid()) {
            $em->flush();
            return $this->redirectToRoute('firmSettings');
        }

        return $this->render('process_list.html.twig',
            [
                'isRoot' => false,
                'form' => $manageForm->createView(),
                'requestForm' => $createForm->createView(),
                'validateForm' => $validateForm ? $validateForm->createView() : null,
                'entity' => 'iprocess',
            ]);
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{entity}/request/validate", name="validateProcessRequest")
     */
    public function validateProcessRequestAction(Request $request){
        $id = $request->get('id');
        $type = $request->get('type');
        $organization = $this->user->getOrganization();
        
        /** @var InstitutionProcess|Process */
        $element = $type == 'p' ? $this->em->getRepository(Process::class)->find($id) : $this->em->getRepository(InstitutionProcess::class)->find($id);
        $elementInitialName = $element->getName();
        $elementInitialParent = $element->getParent();
        $entity = $type == 'p' ? 'process' : 'iprocess';
        $validateProcessForm = $this->createForm(AddProcessForm::class, $element, ['standalone' => true, 'organization' => $organization, 'entity' => $entity]);
        $validateProcessForm->handleRequest($request);
        if($validateProcessForm->isValid()){
            $element->setApprovable(false);
            $this->em->persist($element);
            $this->em->flush();
            $settings['process'] = $element;
            $settings['initialProcessName'] = $elementInitialName;
            $settings['initialProcessParent'] = $elementInitialParent;
            $recipients = [$this->em->getRepository(User::class)->find($element->getCreatedBy())];
            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'validateProcess']);

            return new JsonResponse(['msg' => 'success'], 200);
        } else {
            $errors = $this->buildErrorArray($validateProcessForm);
            return $errors;
        } 
    }
    /**
     * @param Request $request
     * @param $entity
     * @param $elmtId
     * @return string|RedirectResponse|Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{entity}/{elmtId}", name="manageActivityElement")
     */
    public function manageActivityElementAction(Request $request, $entity, $elmtId)
    {

        $organization = $this->user->getOrganization();
        $orgId = $organization->getId();

        switch ($entity) {
            case 'iprocess':
                $repoE = $this->em->getRepository(InstitutionProcess::class);
                $redirect = $this->redirectToRoute('manageProcesses', ['orgId' => $orgId]);
                break;
            case 'process':
                $repoE = $this->em->getRepository(Process::class);
                $redirect = $this->redirectToRoute('manageProcesses', ['orgId' => $orgId]);
                break;
            case 'activity':
                $repoE = $this->em->getRepository(Activity::class);
                $redirect = $this->redirectToRoute('myActivities');
                break;
            default:
                return new Response(null, Response::HTTP_BAD_REQUEST);
        }
        $element = $repoE->find($elmtId);
        $element->currentUser = $this->user;

        $activityElementForm = $this->createForm(
            ActivityElementForm::class,
            $element,
            ['entity' => $entity, 'currentUser' => $this->user]
        );
        $stageElementForm = $this->createForm(
            StageType::class,
            null,
            ['entity' => $entity, 'standalone' => true]
        );

        $criterionElementForm = $this->createForm(
            CriterionType::class,
            null,
            ['entity' => $entity, 'standalone' => true, 'currentUser' => $this->user]
        );
        $outputElementForm = $this->createForm(
            OutputType::class,
            null,
            ['entity' => $entity, 'standalone' => true, 'currentUser' => $this->user]
        );

        $activityElementForm->handleRequest($request);
        $stageElementForm->handleRequest($request);
        $criterionElementForm->handleRequest($request);


        if($activityElementForm->isSubmitted()){

            if($_POST['clicked-btn'] === "save" && !$activityElementForm->isValid()){
                $element->setStatus(ACTIVITY::STATUS_INCOMPLETE);
                $this->em->persist($element);
                $this->em->flush();
                return $redirect;
            }

            if ($activityElementForm->isValid()) {

                if ($_POST['clicked-btn'] === "update" && $entity === 'activity') {

                    $nbTotalStages = count($element->getStages());

                    foreach ($element->getActiveModifiableStages() as $stage) {
                        // 1 - Sending participants mails if necessary
                        // Parameter for subject mail title
                        if ($nbTotalStages > 1) {
                            $mailSettings['stage'] = $stage;
                        } else {
                            $mailSettings['activity'] = $element;
                        }

                        $notYetMailedParticipants = $stage->getDistinctParticipations()->filter(function (Participation $p) {
                            return !$p->getisMailed();
                        });
                        /** @var Participation[] */
                        $participants = $notYetMailedParticipants->getValues();
                        $recipients   = [];
                        foreach ($participants as $participant) {
                            $recipients[] = $participant->getUser();
                            $participant->setStatus(1);
                            $participant->setIsMailed(true);
                            $this->em->persist($participant);
                        }


                        $response = $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $mailSettings, 'actionType' => 'activityParticipation']);
                        if($response->getStatusCode() == 500){
                            return new JsonResponse(['msg' => $response->getContent()], 500);
                        }               

                        $this->em->flush();
                    }


                    // We need to update activity/stage o- and p- statuses accordingly
                    foreach($element->getActiveModifiableStages() as $stage){
                        //Output status
                        if($stage->isComplete()){
                            $stage->setStatus((int) ($stage->getGStartDate() <= new DateTime));
                        } else {
                            $stage->setStatus($stage::STATUS_INCOMPLETE);
                        }

                        //Progress status
                        if(!$stage->getProgress() === -1){
                            $now = new DateTime();
                            if ($stage->getStartdate() < $now) {
                                $stage->setProgress(
                                    $stage->getEnddate() < $now ? STAGE::PROGRESS_COMPLETED :
                                        (STAGE::PROGRESS_ONGOING)
                                );
                            } else {
                                $stage->setProgress(
                                    $stage->getEnddate() < $now ? STAGE::PROGRESS_COMPLETED :
                                        (STAGE::PROGRESS_UPCOMING)
                                );
                            }
                        }

                        $this->em->persist($stage);
                    }

                    if($element->isComplete()){
                        if($element->getActiveModifiableStages()->forAll(static function(int $i, Stage $s){
                            return $s->getStatus() === $s::STATUS_UNSTARTED;
                        })){
                            $element->setStatus($element::STATUS_FUTURE);
                        } else {
                            $element->setStatus($element::STATUS_ONGOING);
                        }
                    } else {
                        $element->setStatus($element::STATUS_INCOMPLETE);
                    }

                    if (!$element->getIsFinalized()){$element->setIsFinalized(true);}
                    $this->em->persist($element);
                    $this->em->flush();
                }
                return $redirect;

            }

        }
        $userRepo        = $this->em->getRepository(User::class);
        $usersWithPic[0] = '/lib/img/no-picture.png';
        foreach ($userRepo->usersWithPicture() as $u) {
            $id                = $u->getId();
            $pic               = $u->getPicture();
            $usersWithPic[$id] = '/lib/img/' . $pic;
        }
        
        return $this->render(
            'activity_element_2.html.twig',
            [
                'activity' => $element,
                'form' => $activityElementForm->createView(),
                'stageElementForm' => $stageElementForm->createView(),
                'criterionElementForm' => $criterionElementForm->createView(),
                'outputElementForm' => $outputElementForm->createView(),
            ]
        );
    }

    // Second parameters is there to create Ext users, who are users of the connected user organization,
    // in order to enable inverse participation in activities
    // Org : client org, added usr param in case concerned user(org) is not current logged user('s one)
    // addedOrgClientUsers : if undefined, no org users are copied as logged user org external users, otherwise defined users are added as ext users
    public function updateOrgFeatures(Organization $organization, User $requestingUser = null, $existingOrg = true, $createSynthUser = false, $addedAsClient = true, $addedClientUsers = null)
    {
            $em = $this->em;
    
            if($addedAsClient){

                $connectedUser = $requestingUser ?: $this->user;
                $connectedUserOrg = $connectedUser->getOrganization();
                // Setting new client relationship (however we look whether the client has an existing relationship with logged user, in the case this client org used to be a client, deleted by logged user org)
                $client = $em->getRepository(Client::class)->findOneBy(['organization' => $organization, 'clientOrganization' => $connectedUserOrg]) ?: new Client;
                $client->setClientOrganization($connectedUserOrg)
                    ->setWorkerFirm($connectedUserOrg->getWorkerFirm() ?: null)
                    ->setType($connectedUserOrg->getType())
                    ->setName($connectedUserOrg->getCommname())
                    ->setCreatedBy($connectedUser->getId());
                
                foreach($addedClientUsers as $addedClientUser){
                    $externalUser = new ExternalUser;
                    if($addedClientUser->isSynthetic()){$externalUser->setSynthetic(true);}
                    $externalUser->setUser($addedClientUser)
                        ->setWeightValue(!$addedClientUser->isSynthetic() ? 100 : null)
                        ->setEmail($addedClientUser->getEmail())
                        ->setPositionName($addedClientUser->getPosition() ? $addedClientUser->getPosition()->getName() : null)
                        ->setFirstname(!$addedClientUser->isSynthetic() ? $addedClientUser->getFirstname() : $organization->getCommname())
                        ->setLastname($addedClientUser->getLastname())
                        ->setCreatedBy($connectedUser->getId());
                    $client->addExternalUser($externalUser);
                }
    
                $organization->addClient($client);
                $em->persist($organization);
            }

            if(!$existingOrg){
                
                $repoON = $em->getRepository(OptionName::class);
        
                // Settling default organization weight
                $defaultOrgWeight = new Weight();
                $defaultOrgWeight->setOrganization($organization)
                    ->setValue(100);
                $organization->addWeight($defaultOrgWeight);
        
                // Settling default options
                /** @var OptionName[] */
                $options = $repoON->findAll();
                foreach ($options as $option) {
        
                    $optionValid = (new OrganizationUserOption)
                    ->setOName($option);
        
                    // We set nb of days for reminding emails, very important otherwise if unset, if people create activities, can make system bug.
                    //  => Whenever someone logs in, this person triggers reminder mails to every person in every organization, organization thus should have this parameter date set.
                    if($option->getName() == 'mailDeadlineNbDays'){
                        $optionValid->setOptionFValue(2);
                    }
                    //$em->persist($optionValid);
        
                    // At least 3 options should exist for a new firm for activity & access results
                    if($option->getName() == 'activitiesAccessAndResultsView'){
        
                        // Visibility and access options has many options :
                        // * Scope (opt_bool_value in DB, optionTrue property) : defines whether user sees his own results (0), or all participant results (1)
                        // * Activities access (opt_int_value, optionIValue property) : defines whether user can access all organisation acts (1), his department activities (2) or his own activities (3)
                        // * Status access (opt_int_value_2, optionSecondaryIValue property) : defines whether user can access computed results (2), or released results (3)
                        // * Detail (opt_float_value, optionFValue property) : defines whether user accesses averaged/consolidated results (0), or detailed results (1)
                        // * Results Participation Condition (opt_string_value, optionSValue property) : defines whether user accesses activity results without condition ('none'), if he is activity owner ('owner'), or if he is participating ('participant')
        
                        $optionAdmin = $optionValid;
                        $optionAdmin->setRole(USER::ROLE_ADMIN)->setOptionTrue(true)->setOptionIValue(1)->setOptionSecondaryIValue(2)->setOptionFValue(1)->setOptionSValue('none');
                        $organization->addOption($optionAdmin);
        
                        $optionAM = (new OrganizationUserOption)
                            ->setOName($option)
                            ->setRole(USER::ROLE_AM)
                            ->setOptionTrue(true)
                            ->setOptionIValue(2)
                            ->setOptionSecondaryIValue(2)
                            ->setOptionFValue(0)
                            ->setOptionSValue('owner');
                        $organization->addOption($optionAM);
        
                        $optionC = (new OrganizationUserOption)
                            ->setOName($option)
                            ->setRole(USER::ROLE_COLLAB)
                            ->setOptionTrue(false)
                            ->setOptionIValue(3)
                            ->setOptionSecondaryIValue(3)
                            ->setOptionFValue(0)
                            ->setOptionSValue('participant');
                        $organization->addOption($optionC);
                        //$em->persist($optionC);
                    } else {
                        $organization->addOption($optionValid);
                    }
                }
    
                //$em->persist($organization);
        
                // Settling default criterion names
                $repoCN = $em->getRepository(CriterionName::class);
                $criterionGroups = [
                    1 => new CriterionGroup('Hard skills'),
                    2 => new CriterionGroup('Soft skills')
                ];
                foreach ($criterionGroups as $cg) {
                    $organization->addCriterionGroup($cg);
                    //$cg->setOrganization($organization);
                    //$em->persist($cg);
                }
        
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
                        //->setOrganization($organization)
                        ->setCriterionGroup($cg);
        
                    $cg->addCriterion($criterion);
                    $organization->addCriterionName($criterion);
                    //$em->persist($criterion);
                }

                // Setting up Events
                /** @var EventGroupName[] */
                $eventGroupNames = $em->getRepository(EventGroupName::class)->findAll();

                foreach ($eventGroupNames as $egn) {
                    $eventGroup = new EventGroup;
                    $eventGroup->setEventGroupName($egn);
                    
                    /** @var EventName[] */
                    $eventNames = $egn->getEventNames();
                    foreach($eventNames as $en){
                        $eventType = new EventType;
                        $eventType->setEName($en)
                            ->setIcon($en->getIcon());
                        $eventGroup->addEventType($eventType);
                        $organization->addEventType($eventType);
                    }
                    $organization->addEventGroup($eventGroup);
                }

                if($createSynthUser){
                    
                    //Synthetic User Creation (for external, in case no consituted team has been created to grade a physical person for an activity)
                    $syntheticUser = new User;
                    if($organization->getType() == 'I'){
                        $username = $organization->getCommname();
                        $syntheticUser->setUsername($username);
                        $nameElmts = explode(" ", $username, 2); 
                        $firstname = trim($nameElmts[0]);
                        $lastname = trim($nameElmts[1]);
                    } else {
                        $firstname = 'ZZ';
                        $lastname = $organization->getCommname();
                    }

                    $syntheticUser
                        ->setFirstname($firstname)
                        ->setLastname($lastname)
                        ->setSynthetic(true)
                        ->setRole(USER::ROLE_COLLAB);
                    
                    $organization->addUser($syntheticUser);
                    $em->persist($syntheticUser);
                }

                $em->persist($organization);
            }
            
            $em->flush();
            return new Response('success',200);
    }

    /**
     * @param Request $request
     * @param $entity
     * @param $elmtId
     * @return string|RedirectResponse|Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/organization/events/event-names", name="getEventTypesFromEventGroup")
     */
    public function getEventTypesFromEventGroup(Request $request){
        $evgId = $request->get('id');
        $em = $this->em;
        $locale = $request->getLocale();
        $org = $this->org;
        $eventGroup = $em->getRepository(EventGroup::class)->find($evgId);
        $repoET = $em->getRepository(EventType::class);
        $eventTypes = $eventGroup->getEventTypes()->map(fn(EventType $et) => [
            'evnId' => $et->getEName()->getId(),
            'id' => $et->getId(),
            'name' => ($et->getIcon() ? '~' . $et->getIcon()->getUnicode() .'~ ' : '') . $repoET->getDTrans($et,$locale,$org),
        ])->getValues();
        
        //dd($eventTypes);
        return new JsonResponse($eventTypes, 200);
    }

    /**
     * Delete event
     * @Route("/organization/event/delete/{eveId}", name="deleteEvent")
     */
    public function deleteEvent(int $eveId){
        $em = $this->em;
        /** @var Event */
        $event = $em->getRepository(Event::class)->find($eveId);
        if(!$event){
            return new JsonResponse(['msg' => 'error'], 500);
        }
        foreach($event->getDocuments() as $document){
            $path = $document->getPath();
            unlink(dirname(dirname(__DIR__)) . "/public/lib/evt/$path");
        }
        $stage = $event->getStage();
        $stage->removeEvent($event);
        $em->persist($stage);
        $em->flush();
        return new JsonResponse(['msg' => 'success'], 200);
    }
    /**
     * Delete event document
     * @Route("/organization/event/document/delete/{id}", name="deleteDocument")
     */
    public function deleteDocument(int $id){
        $em = $this->em;
        /** @var EventDocument */
        $document = $em->getRepository(EventDocument::class)->find($id);
        if(!$document){
            return new JsonResponse(['msg' => 'error'], 500);
        }
        $path = $document->getPath();
        $event = $document->getEvent();
        $event ? $event->removeDocument($document) : $em->remove($document);
        unlink(dirname(dirname(__DIR__)) . "/public/lib/evt/$path");
        if($event){$em->persist($event);}
        $em->flush();
        return new JsonResponse(['msg' => 'success'], 200);
    }
    /**
     * Delete event comment
     * @Route("/organization/event/comment/delete/{id}", name="deleteComment")
     */
    public function deleteComment(int $id){
        $em = $this->em;
        /** @var EventComment */
        $eventComment = $em->getRepository(EventComment::class)->find($id);
        if(!$eventComment){
            return new JsonResponse(['msg' => 'error'], 500);
        }
        $event = $eventComment->getEvent();
        $event->removeComment($eventComment);
        $em->persist($event);
        $em->flush();
        return new JsonResponse(['msg' => 'success'], 200);
    }

    /**
     * Gets current data of related stage
     * @Route("/organization/document/content/update", name="updateDocumentContent")
     */
    public function updateDocumentContent(Request $request, FileUploader $fileUploader, NotificationManager $notificationManager){

        $docId = $request->get('id');
        $evtId = $request->get('eid');
        $stgId = $request->get('sid');
        $evtTypeId = $request->get('evtid');
        $oDateStr = $request->get('oDateStr');
        $expResDateStr = $request->get('expResDateStr');
        if($oDateStr != ""){$oDate = new DateTime($oDateStr);}
        $expResDate = $expResDateStr != "" ? new DateTime($expResDateStr) : null;
        $docTitle = $request->get('title');
        $documentFile = $request->files->get('file');
        $em = $this->em;
        $documentFileInfo = $fileUploader->upload($documentFile);
        $type = $documentFileInfo['extension'];
        $size = $documentFileInfo['size'];
        $path = $documentFileInfo['name'];
        $mime = $documentFileInfo['mime'];

        if($evtId || $stgId){

        
            
            if(!$evtId){
                 /** @var Stage */
                 $stage = $em->getRepository(Stage::class)->find($stgId);
                 $eventType = $em->getRepository(EventType::class)->find($evtTypeId);
                 $event = new Event;
                 $event->setEventType($eventType)
                    ->setOnsetDate($oDate)
                    ->setExpResDate($expResDate)
                    ->setOrganization($this->org)
                    ->setCreatedBy($this->user->getId());
                 $stage->addEvent($event);
            } else {
                /** @var Event */
                $event = $em->getRepository(Event::class)->find($evtId);
                $stage = $event->getStage();
            }
        }


        /** @var EventDocument */
        $document = $docId ? $em->getRepository(EventDocument::class)->find($docId) : new EventDocument;
        $document
            ->setType($type)
            ->setSize($size)
            ->setPath($path)
            ->setMime($mime)
            ->setCreatedBy($this->user->getId());
        if(!$docId){
            $document
                ->setTitle($docTitle)
                ->setOrganization($this->org)
                ->setCreatedBy($this->user->getId());
            $documentAuthor = new DocumentAuthor();
            $documentAuthor->setAuthor($this->user)->setDocument($document);
            $document->addDocumentAuthor($documentAuthor);
            if($evtId || $stgId){     
                $event->addDocument($document);
                $em->persist($event);
            } else {
                $em->persist($document);
            }
        } else {
            $path = $document->getPath();
            unlink(dirname(dirname(__DIR__)) . "/public/lib/evt/$path");
            $document->setModified(new DateTime);
            $em->persist($document);
        }

        if(!$evtId && $stgId){
            $notificationManager->registerUpdates($event, ElementUpdate::CREATION);
        }

        $status = $docId ? ElementUpdate::CHANGE : ElementUpdate::CREATION;
        $property = $docId ? ElementUpdate::EVENT_DOC_CONTENT : null;

        if($evtId || $stgId){
            $notificationManager->registerUpdates($document,$status,$property);
        }
        /*foreach($event->getStage()->getParticipants() as $participation){
            $update = new Update;
            $update->setType($docId ? ElementUpdate::CHANGE : ElementUpdate::CREATION)
                ->setEventDocument($document)
                ->setUser($participation->getUser())
                ->setStage($stage)
                ->setActivity($stage->getActivity());
            $event->addUpdate($update);
        }*/
        
        if(!$evtId && $stgId){
            $em->persist($stage);
        }

        $em->flush();

        /*
        $recipients = $event->getStage()->getParticipants()->filter(fn(Participation $p) => $p->getUser() != $this->user)->map(fn(Participation $p) => $p->getUser())->getValues();
        $response = $this->forward('App\Controller\MailController::sendMail', [
            'recipients' => $recipients, 
            'settings' => [
                'event' => $event, 
                'commentUpdate' => false,
                'documentUpdate' => true,
            ],
            'actionType' => 'eventUpdate'
        ]);
        if($response->getStatusCode() == 500){ return $response; };
        */

        $outputData = ['type' => $type, 'size' => $size, 'mime' => $mime, 'path' => $path, 'title' => $docTitle, 'did' => $document->getId()];
        if(!$evtId && $stgId){
            $eventName = $eventType->getEName();
            $eventGroup = $eventType->getEventGroup();
            $locale = $request->getLocale();
            $repoEG = $em->getRepository(EventGroup::class);
            $repoET = $em->getRepository(EventType::class);
            $outputData['sid'] = $stgId;
            $outputData['eid'] = $event->getId();
            $outputData['od'] = $event->getOnsetdateU();
            $outputData['rd'] = $event->getExpResDateU();
            $outputData['p'] = $event->getPeriod();
            $outputData['it'] = $eventName->getIcon()->getType();
            $outputData['in'] = $eventName->getIcon()->getName();
            $outputData['gn'] = $eventGroup->getEventGroupName()->getId();
            $outputData['gt'] = $repoEG->getDTrans($eventGroup, $locale, $this->org);
            $outputData['tt'] = $repoET->getDTrans($eventType, $locale, $this->org);
            $outputData['nbc'] = $event->getComments()->count();
            $outputData['nbd'] = $event->getDocuments()->count();
        }
        return new JsonResponse($outputData, 200);
    }

    /**
     * Gets current data of related stage
     * @Route("/organization/comment/content/update", name="updateCommentContent")
     */
    public function updateCommentContent(Request $request, NotificationManager $notificationManager){
        $locale = $request->getLocale();
        $comId = $request->get('id');
        $evtId = $request->get('eid');
        $parentId = $request->get('cid');
        $comContent = $request->get('content');
        $stgId = $request->get('sid');
        $evtTypeId = $request->get('evtid');
        $oDateStr = $request->get('oDateStr');
        $expResDateStr = $request->get('expResDateStr');

        if($oDateStr != ""){$oDate = new DateTime($oDateStr);}
        $expResDate = $expResDateStr != "" ? new DateTime($expResDateStr) : null;
        $em = $this->em;
        /** @var EventComment */
        $comment = $comId ? $em->getRepository(EventComment::class)->find($comId) : new EventComment;
        $isCurrentlyModified = $comment->getContent() && $comContent != $comment->getContent();
        if(!$comId){
            
            $comment->setContent($comContent)
                ->setAuthor($this->user)
                ->setOrganization($this->org)
                ->setCreatedBy($this->user->getId());
            if($parentId){
                $parent = $em->getRepository(EventComment::class)->find($parentId);
                $comment->setParent($parent);
            }

            if(!$evtId){
                /** @var Stage */
                $stage = $em->getRepository(Stage::class)->find($stgId);
                $eventType = $em->getRepository(EventType::class)->find($evtTypeId);
                $event = new Event;
                $event->setEventType($eventType)
                   ->setOnsetDate($oDate)
                   ->setExpResDate($expResDate)
                   ->setOrganization($this->org)
                   ->setCreatedBy($this->user->getId());
                $stage->addEvent($event);
           } else {
                /** @var Event */
                $event = $em->getRepository(Event::class)->find($evtId);
                $stage = $event->getStage();
           }
            
            $recipients = $event->getStage()->getUniqueParticipations()->filter(fn(Participation $p) => $p->getUser() != $this->user)->map(fn(Participation $p) => $p->getUser())->getValues();
            $event->addComment($comment);
            $em->persist($event);
            //$em->flush();

            /*$response = $this->forward('App\Controller\MailController::sendMail', [
                'recipients' => $recipients, 
                'settings' => [
                    'event' => $event, 
                    'commentUpdate' => true,
                    'documentUpdate' => false
                ],
                'actionType' => 'eventUpdate'
            ]);
            if($response->getStatusCode() == 500){ return $response; };*/

        } else {
            if($isCurrentlyModified){
                $comment->setContent($comContent)
                    ->setModified(new DateTime);
                $em->persist($comment);
                //$em->flush();
            }
        }

        if(!$evtId){
            $notificationManager->registerUpdates($event, ElementUpdate::CREATION);
        }
        if(!$comId){
            $notificationManager->registerUpdates($comment, ElementUpdate::CREATION, 'content');
        }

        $em->flush();
        /*
        foreach($event->getStage()->getParticipants() as $participation){
            $update = new Update;
            $update->setType($comId ? ElementUpdate::CHANGE : ElementUpdate::CREATION)
                ->setEventComment($comment)
                ->setUser($participation->getUser())
                ->setStage($stage)
                ->setActivity($stage->getActivity());
            $event->addUpdate($update);
        }*/

        $outputData = ['msg' => 'success', 'author' => $this->user->getFullName(), 'modified' => $comment->getModified() != null, 'inserted' => $this->nicetime($comment->getInserted(),$locale), 'cid' => $comment->getId()];
        if(!$evtId){
            $outputData['sid'] = $stgId;
            $outputData['eid'] = $event->getId();
            $eventName = $eventType->getEName();
            $eventGroup = $eventType->getEventGroup();
            $locale = $request->getLocale();
            $repoEG = $em->getRepository(EventGroup::class);
            $repoET = $em->getRepository(EventType::class);
            $outputData['od'] = $event->getOnsetdateU();
            $outputData['rd'] = $event->getExpResDateU();
            $outputData['p'] = $event->getPeriod();
            $outputData['it'] = $eventName->getIcon()->getType();
            $outputData['in'] = $eventName->getIcon()->getName();
            $outputData['gn'] = $eventGroup->getEventGroupName()->getId();
            $outputData['gt'] = $repoEG->getDTrans($eventGroup, $locale, $this->org);
            $outputData['tt'] = $repoET->getDTrans($eventType, $locale, $this->org);
            $outputData['nbc'] = $event->getComments()->count();
            $outputData['nbd'] = $event->getDocuments()->count();
        }
        return new JsonResponse($outputData, 200);
    }

    /**
     * Gets current data of related stage
     * @Route("/organization/document/title/update", name="updateDocumentTitle")
     */
    public function updateDocumentTitle(Request $request, NotificationManager $notificationManager){
        $docId = $request->get('id');
        $docTitle = $request->get('title');
        $em = $this->em;
        /** @var EventDocument */
        $document = $em->getRepository(EventDocument::class)->find($docId);
        $document->setTitle($docTitle);
        $notificationManager->registerUpdates($document, ElementUpdate::CHANGE, 'title');
        $em->persist($document);
        $em->flush();
        return new JsonResponse(['msg' => 'success'], 200);
    }

    /**
     * Gets current data of related stage
     * @Route("/organization/stage/name/update", name="updateStageName")
     */
    public function updateStageName(Request $request, NotificationManager $notificationManager){
        $stgId = $request->get('id');
        $stgName = $request->get('name');
        $em = $this->em;
        /** @var Stage */
        $stage = $em->getRepository(Stage::class)->find($stgId);
        $activity = $stage->getActivity();
        $actNameChg = false;
        if($activity->getStages()->count() == 1){
            $actNameChg = true;
            $activity->setName($stgName);
            $em->persist($activity);
        }
        $stage->setName($stgName);
        $notificationManager->registerUpdates($stage, ElementUpdate::CHANGE, 'name');
        $em->persist($stage);
        $em->flush();
        return new JsonResponse(['msg' => 'success', 'actNameChg' => $actNameChg], 200);
    }

    /** 
     * Gets current data of related stage
     * @Route("/organization/stage/{stgId}/istatus/update", name="updateStageInvitationStatus")
     */
    public function updateStageInvitationStatus(Request $request, int $stgId){
        $em = $this->em;
        /** @var Stage */
        $stage = $em->getRepository(Stage::class)->find($stgId);
        $istatus = $request->get('istatus');
        $stage->setInvitStatus($istatus);
        $em->persist($stage);
        $em->flush();
        return new JsonResponse();
    }


    /**
     * Gets current data of related stage
     * @Route("/organization/stage/{stgId}/ilink", name="createStageInvitationLink")
     */
    public function createStageInvitationLink(Request $request, int $stgId){
        $em = $this->em;
        /** @var Stage */
        $stage = $em->getRepository(Stage::class)->find($stgId);
        $stage->setAccessLink(md5(rand()));
        $em->persist($stage);
        $em->flush();
        return new JsonResponse(['link' => $stage->getAccessLink()]);
    }
    
    /**
     * Gets current data of related stage
     * @Route("/organization/stage/dates/update", name="updateStageDates")
     */
    public function updateStageDates(Request $request, NotificationManager $notificationManager){
        $stgId = $request->get('id');
        $startdateStr = $request->get('sd');
        $enddateStr = $request->get('ed');
        $startdate = new DateTime($startdateStr);
        $enddate = $enddateStr ? new DateTime($enddateStr) : null;
        $em = $this->em;
        $property = null;

        /** @var Stage */
        $stage = $em->getRepository(Stage::class)->find($stgId);
        /*
        if($stage->getStartdate() != $startdate && $stage->getEnddate() == $enddate){
            $property = 'startdate';
        } else if ($stage->getStartdate() == $startdate && $stage->getEnddate() != $enddate){
            $property = 'enddate';
        }
        */

        //if($property){
            $stage->setStartdate($startdate)
                ->setEnddate($enddate);
            $notificationManager->registerUpdates($stage, ElementUpdate::CHANGE, $property);
            $em->persist($stage);
            $em->flush();
        //}
        return new JsonResponse(['msg' => 'success', 'sd' => intval($startdate->format("U")), 'p' => $stage->getPeriod()], 200);
    }

    /**
     * Gets current data of related stage
     * @Route("/organization/event/dates/update", name="updateEventDates")
     */
    public function updateEventDates(Request $request, NotificationManager $notificationManager){
        $em = $this->em;
        $evtId = $request->get('id');
        $onsetDateStr = $request->get('sd');
        $expResStr = $request->get('ed');
        $stgId = $request->get('sid');
        $evtTypeId = $request->get('evtid');
        $onsetDate = new DateTime($onsetDateStr);
        $expResDate = $expResStr ? new DateTime($expResStr) : null;
        if(!$evtId){
            /** @var Stage */
            $stage = $em->getRepository(Stage::class)->find($stgId);
            $eventType = $em->getRepository(EventType::class)->find($evtTypeId);
            $event = new Event;
            $event->setEventType($eventType)
               ->setOnsetDate($onsetDate)
               ->setExpResDate($expResDate)
               ->setOrganization($this->org)
               ->setCreatedBy($this->user->getId());
            $stage->addEvent($event);
            $notificationManager->registerUpdates($event, ElementUpdate::CREATION);

        } else {
            /** @var Event */
            $event = $em->getRepository(Event::class)->find($evtId);
            $stage = $event->getStage();
            if($event->getOnsetDate() != $onsetDate && $expResDate == $event->getExpResDate()){
               $property = 'onsetDate';
            } else if ($event->getOnsetDate() == $onsetDate && $expResDate != $event->getExpResDate()){
                $property = 'expResDate';
            } else if ($event->getOnsetDate() != $onsetDate && $expResDate != $event->getExpResDate()){
                $property = 'dates';
            }

            if($property){
                $event->setOnsetDate($onsetDate)
                    ->setExpResDate($expResDate);
                $notificationManager->registerUpdates($event, ElementUpdate::CHANGE, $property);
            }
        }
       
        $em->persist($event);
        $em->flush();
        return new JsonResponse(['msg' => 'success'], 200);
        
    }

    /**
     * Gets current data of related stage
     * @Route("/organization/stage/{stgId}/participants/add", name="addParticipantStage")
     */
    public function addParticipantStage(Request $request, int $stgId, TranslatorInterface $translator){
        $usrId = $_POST['uid'] != "" ? $_POST['uid'] : $_POST['iuid'];
        $extUsrId = $_POST['euid'];
        $teaId = $_POST['tid'];
        $genType = $_POST['gen-type'];
        $userType = isset($_POST['user-type']) ? $_POST['user-type'] : null;
        $firmname = $_POST['firmname'];
        $username = $_POST['username'];
        $cliId = $_POST['cid'];
        $orgId = $_POST['oid'];

        if($genType == 'i' || $genType == 'f' || $userType == 'ext'){
            if(!$cliId){
                // We create client relationship...
                $clientJsonResponse = $this->addClientAction($request);
                $clientResponse = json_decode($clientJsonResponse,true);
                $cliId = $clientResponse['cid'];
            }
            if($genType != 'i'){
                if(!$extUsrId){
                    $clientUserJsonResponse = $this->addClientUserAction($request, $cliId, $translator);
                    $clientUserResponse = json_decode($clientUserJsonResponse,true);
                    $extUsrId = $clientUserResponse['euid'];
                    if(!$usrId){
                        $usrId = $clientUserResponse['id'];
                    }
                } 
            }
        } else {
            if(!$usrId){
                $userJsonResponse = $this->addUserAction($request);
                $userResponse = json_decode($userJsonResponse,true);
                $usrId = $userResponse['id'];
            }
        }

        $em = $this->em;
        /** @var Stage */
        $stage = $em->getRepository(Stage::class)->find($stgId);
        $user = $usrId ? $em->getRepository(User::class)->find($usrId) : null;
        $organization = $this->org;
        $userOrganization = $user->getOrganization();
        if($userOrganization != $organization){
            // It means this user does not belong to a client organization, we need to add it)
            if(!$extUsrId){
                $client = new Client;
                $client->setOrganization($this->org)
                    ->setClientOrganization($userOrganization)
                    ->setName($userOrganization->getCommname())
                    ->setWorkerFirm($userOrganization->getWorkerFirm());
                    
                $nameElmts = explode(" ", $user->getUsername(), 2); 
                $firstname = trim($nameElmts[0]);
                $lastname = trim($nameElmts[1]);

                $externalUser = new ExternalUser();
                $externalUser->setFirstname($firstname)
                    ->setLastname($lastname)
                    ->setEmail($user->getEmail())
                    ->setUser($user);

                $client->addExternalUser($externalUser);
                $organization->addClient($client);
                $em->persist($organization);
                $em->flush();
                $this->updateOrgFeatures($userOrganization, null, true, false, true, [$this->user]);

            } else {
                $externalUser = $em->getRepository(ExternalUser::class)->find($extUsrId);
            }
        }
        $team = $teaId ? $em->getRepository(Team::class)->find($teaId) : null;
        $participant = new Participation;
        $participant->setUser($user)
            ->setExternalUser($externalUser)
            ->setTeam($team);
        $stage->addParticipation($participant);
        $em->persist($stage);
        $em->flush();

        // Adding oneself does not trigger any email sending
        if($user != $this->user){
            $recipients = $team ? $team->getMembers()->map(fn(Member $m) => [$m->getUser()])->getValues()[0] : [$user];
            $activity = $stage->getActivity();
            $response = $this->forward('App\Controller\MailController::sendMail', [
                'recipients' => $recipients, 
                'settings' => [
                    'activity' => $activity->getStages()->count() > 1 ? null : $activity, 
                    'stage' => $activity->getStages()->count() > 1 ? $stage : null,
                ], 
                'actionType' => 'activityParticipation']
            );
        }
        if($response->getStatusCode() == 500){ return $response; };
        return new JsonResponse(['msg' => 'success','pid' => $participant->getId()], 200);
    }

    /**
     * Gets current data of related stage
     * @Route("/organization/stage/data", name="getStageData")
     */
    public function retrieveStageData(Request $request){
        $stgId = $request->get('id');
        $em = $this->em;
        $locale = $request->getLocale();
        $repoET = $em->getRepository(EventType::class);
        $repoEG = $em->getRepository(EventGroup::class);
        $currentUser = $this->user;
        $org = $this->org;
        /** @var Stage */
        $stage = $em->getRepository(Stage::class)->find($stgId);
        $activity = $stage->getActivity();
        $data['ms'] =  $activity->getStages()->count() > 1;
        $data['aname'] = $activity->getName();
        $data['name'] = $stage->getName();
        $data['progress'] = $stage->getProgress();
        $data['sdate'] = $stage->getStartdate();
        $data['edate'] = $stage->getEnddate();
        if($stage->getAccessLink()){
            $data['link'] = $stage->getAccessLink();
            $data['istatus'] = $stage->getInvitStatus();
        }

        foreach($stage->getEvents() as $event){
            $evtData = [];
            $evtData['id'] = $event->getId();
            /** @var EventGroup */
            $eventGroup = $event->getEventType()->getEventGroup();
            /** @var EventType */
            $eventType = $event->getEventType();
            $evtData['evgId'] = $eventGroup->getEventGroupName()->getId();
            $evtData['evg'] = $repoEG->getDTrans($eventGroup, $locale, $org);
            $evtData['evt'] = $repoET->getDTrans($eventType, $locale, $org);
            $evtData['odate'] = $event->getOnsetDate()->diff(new DateTime)->d > 5 ? $event->getOnsetDate() : $event->nicetime($event->getOnsetDate(), $locale);
            $evtData['rdate'] = $event->getExpResDate() == null ? null : ($event->getExpResDate()->diff(new DateTime)->d > 5 ? $event->getExpResDate() : $event->nicetime($event->getExpResDate(),$locale));
            $evtData['nbdocs'] = $event->getDocuments()->count();
            $evtData['nbcoms'] = $event->getComments()->count();
            $evtData['oid'] = $event->getOrganization()->getId();
            $data['events'][] = $evtData;
        }

        foreach($stage->getUniqueParticipations() as $participant){
            $user = $participant->getUser();
            $partData = [];
            $partData['id'] = $participant->getId();
            $partData['fullname'] = $user->getFullname();
            $externalUser = $participant->getExternalUser();
            $isSynthetic = $user->isSynthetic();
            $isPrivate = $user->getOrganization()->getType() == 'C';
            if($externalUser){
                $clientName = $externalUser->getClient()->getName();
                if(!$isSynthetic && !$isPrivate){
                    $partData['fullname'] .= " ($clientName)";
                } else {
                    $partData['fullname'] = $clientName;
                }
            }
            $partData['synth'] = $isSynthetic;
            $partData['priv'] = $isPrivate;
            $partData['picture'] = $isSynthetic ? '/lib/img/org/no-picture.png' : (
                $user->getPicture() ? '/lib/img/user/'.$user->getPicture() : (
                    $isPrivate ? '/lib/img/user/no-picture-i.png' : '/lib/img/user/no-picture.png'
                )
            );
            if($org != $user->getOrganization()){
                $partData['firmLogo'] =  $user->getOrganization()->getOrganizationLogo();
            }
            $data['participants'][] = $partData;
        }
        
        return new JsonResponse($data, 200);
    }

    /**
     * Gets current data of related event
     * @Route("/organization/event/data", name="getEventData")
     */
    public function retrieveEventData(Request $request){
        $locale = $request->getLocale();
        $eveId = $request->get('id');
        $em = $this->em;
        $currentUser = $this->user;
        /** @var Event */
        $event = $em->getRepository(Event::class)->find($eveId);
        $data['sid'] = $event->getStage()->getId();
        $isExt = $event->getOrganization() != $this->org;
        if($isExt){
            $data['ext'] = 1;
        }
        $data['sname'] = $event->getStage()->getName();
        if($isExt){
            $data['type'] = $em->getRepository(EventType::class)->getDTrans($event->getEventType(),$locale,$event->getOrganization());
            $data['group'] = $em->getRepository(EventGroup::class)->getDTrans($event->getEventType()->getEventGroup(),$locale,$event->getOrganization());
        } else {
            $data['type'] = $event->getEventType()->getEName()->getId();
            $data['group'] = $event->getEventType()->getEventGroup()->getEventGroupName()->getId();
        }
        $data['odate'] = $event->getOnsetDate();
        $data['expResDate'] = $event->getExpResDate();
        $tz = new DateTimeZone('Europe/Paris');
        foreach($event->getDocuments() as $document){
            $docData = [];
            $docData['id'] = $document->getId();
            $docData['title'] = $document->getTitle();
            $docData['path'] = $document->getPath();
            $docData['type'] = $document->getType();
            $docData['mime'] = $document->getMime();
            $docData['size'] = $document->getSize();
            $docData['authors'] = $document->getDocumentAuthors()->count() > 0 ? $document->getDocumentAuthors()->map(fn(DocumentAuthor $da) => ['mainAuthor' => $da->isLeader(), 'fullname' => $da->getAuthor()->getFullName(), 'id' => $da->getAuthor()->getId()])->getValues()[0] : '';
            $docData['inserted'] = $document->getInserted()->setTimezone($tz);
            $docData['oid'] = $document->getOrganization()->getId();
            $docData['modified'] = $document->getModified() ? $document->getModified()->setTimezone($tz) : "";
            $data['documents'][] = $docData;
        }

        foreach($event->getParentComments() as $comment){
            $comData = [];
            $comData['id'] = $comment->getId();
            $comData['self'] = $comment->getAuthor() == $currentUser;
            $comData['author'] = $comment->getAuthor()->getFullName();
            $comData['content'] = $comment->getContent();
            $comData['inserted'] = $this->nicetime($comment->getInserted()->setTimezone($tz), $locale);
            $comData['modified'] = $comment->getModified() != null;
            $comData['oid'] = $comment->getOrganization()->getId();

            foreach($comment->getReplies() as $reply){
                $replyData = [];
                $replyData['id'] = $reply->getId();
                $replyData['self'] = $reply->getAuthor() == $currentUser;
                $replyData['author'] = $reply->getAuthor()->getFullName();
                $replyData['content'] = $reply->getContent();
                $replyData['inserted'] = $this->nicetime($reply->getInserted()->setTimezone($tz), $locale);
                $replyData['modified'] = $reply->getModified() != null;
                $replyData['oid'] = $reply->getOrganization()->getId();
                $comData['replies'][] = $replyData;
            }
            $data['comments'][] = $comData;
        }
        
        return new JsonResponse($data, 200);
    }


     /**
     * @param Request $request
     * @param $entity
     * @param $elmtId
     * @return string|RedirectResponse|Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/dynamic/translations", name="getDynamicTranslations")
     */

    public function getDynamicTranslations(Request $request){

        $em = $this->em;
        
        $repoET = $em->getRepository(EventType::class);
        $repoT = $em->getRepository(DynamicTranslation::class);
        $locale = strtoupper($request->getLocale());  
        $translatableElmts = $request->get('elmts');
        $lElmts = [];
        
        foreach($translatableElmts as $translatableElmt){
            if($translatableElmt['e'] == 'EventGroupName'){
                $motherEntityId = $em->getRepository(EventGroup::class)->find($translatableElmt['id'])->getEventGroupName()->getId();
            } else {
                $motherEntityId = $em->getRepository(EventType::class)->find($translatableElmt['id'])->getEName()->getId();
            }
            
            $translatables = $repoT->findBy(['entity' => $translatableElmt['e'], 'entityId' => $motherEntityId, 'entityProp' => $translatableElmt['p'], 'organization' =>[null, $this->org]], ['organization' => 'ASC']);
            if(!$translatables){
                $lElmts[] = "";
            } else {
                /** @var DynamicTranslation */
                $translatable = sizeof($translatables) > 1 ? $translatables[1] : $translatables[0];                
                $translatable->locale = $locale;
                $lElmts[] = $translatable->getDynTrans();
            }
        }
        
        return new JsonResponse(['lElmts' => $lElmts], 200);

    }

    /**
     * @param Request $request
     * @param $entity
     * @param $elmtId
     * @return string|RedirectResponse|Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/dummies/clients", name="getDummyClients")
     */

    public function getDummyClientsAndActNames(Request $request){

        $em = $this->em;
        $locale = $request->getLocale();
        $withActNames = $request->get('wa') ?: true;
        $totalDummies = $request->get('td') ?: 3;

        $qb = $em->createQueryBuilder();
        $allWFIds = $qb->select('wf.id AS wfIds')
            ->from('App\Entity\WorkerFirm','wf')
            ->where('wf.logo IS NOT NULL')
            ->setMaxResults(500)
            ->getQuery()
            ->getResult();

        //return new JsonResponse(['clientIds' => $allWFIds],200);

        
        if($withActNames){
            $repoEG = $em->getRepository(EventGroup::class);
            $repoET = $em->getRepository(EventType::class);
            $participants = [
                    'Valentina Suarez',
                    'Georges Dabault',
                    'Piotr Civosky',
                    'Qingxin Meng',
                    'Daniela d\'Ambrozzo',
                    'Sigur Olovson',
                    'Dietmar Hengersen',
                    'Cécile Argence',
                    'Fabrice Cacault',
                    'Denitsa Wooles',
                    'Zack Finley',
                    'Jackie Denson',
                    'Lionel Manternach',
                    'Ricco Stumper',
                    'Aïssa Coulibaly',
                    'Dimitra Papandreou'
            ];
            if($locale == 'en'){
                $actNames = ['Marketing project', 'VP Recruitment', 'Contract Negotiation', 'Delivery', 'Proof of Concept', 'Deal Agreement', 'Trade Fair exhibition', 'Call for Tender', 'Training - Logistics'];
            } else if($locale == 'fr') {
                $actNames = ['Projet marketing', 'Recrutement VP', 'Nego contractuelle', 'Réalisation prestation', 'Test client', 'Projet de collaboration', 'Salon - Expo', 'Appel d\'offres', 'Training - Logistique'];
            }
            /** @var ArrayCollection */
            $allOrgs = new ArrayCollection($em->getRepository(Organization::class)->findAll());
            $allOrgs = $allOrgs->filter(fn(Organization $o) => $o->getType() != 'C');
            $fullOrg = $allOrgs->last();
            $eventGroups = $fullOrg->getEventGroups()->map(fn(EventGroup $eg) => 
                [
                    'id' => $eg->getId(),
                    'name' => $repoEG->getDTrans($eg,$locale,$fullOrg),
                    'types' => $eg->getEventTypes()->map(fn(EventType $et) => [
                        'name' => $repoET->getDTrans($et,$locale,$fullOrg),
                        'icon_type' => $et->getIcon()->getType(),
                        'icon_name' => $et->getIcon()->getName(),
                    ])->getValues(),
                ])->getValues();
        }

        $randomIds = [];
        $randomWFArrayKeys = [];
        $randomActNameArrayKeys = [];

        for($i=0; $i<$totalDummies; $i++){
            
            $randomWFArrayKey = random_int(0,sizeof($allWFIds) - 1);
            while(array_search($randomWFArrayKey,$randomWFArrayKeys) !== false){
                $randomWFArrayKey = random_int(0,sizeof($allWFIds) - 1);
            }
            $randomWFArrayKeys[] = $randomWFArrayKey;
            $randomIds[] = $allWFIds[$randomWFArrayKey]['wfIds'];

            $randomActNameArrayKey = random_int(0,sizeof($actNames) - 1);
            while(array_search($randomActNameArrayKey,$randomActNameArrayKeys) !== false){
                $randomActNameArrayKey = random_int(0,sizeof($actNames) - 1);
            }
            $randomActNameArrayKeys[] = $randomActNameArrayKey;

        }
        
        $dummyElmts = [];

        /** @var WorkerFirm[] */
        $dummyClients = $em->getRepository(WorkerFirm::class)->findById($randomIds);

        foreach($dummyClients as $key => $dummyClient){
            $output['name'] = $dummyClient->getName();
            $output['logo'] = $dummyClient->getLogo() ? "/lib/img/wf/".$dummyClient->getLogo() : "/lib/img/org/no-picture.png";
            $output['actName'] = $actNames[$randomActNameArrayKeys[$key]];
            $output['events'] = [];
            $output['participants'] = [];

            for($j=0; $j < random_int(1,3); $j++){
                $output['events'][] = $eventGroups[random_int(0,sizeof($eventGroups) - 1)];
            }
            for($j=0; $j < random_int(1,3); $j++){
                $output['participants'][] = $participants[random_int(0,sizeof($participants) - 1)];
            }
            $dummyElmts[] = $output; 
        }

        return new JsonResponse(['dummyElmts' => $dummyElmts], 200);

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

    function isDeletable(Activity $activity): ?bool
    {
        $currentUser = $this->user;
        $role = $currentUser->getRole();

        if ($role === 4) {
            return true;
        }

        if ($role === 3) {
            return false;
        }

        if ($activity->status >= 2) {
            return false;
        }
        if ($role === 1) {
            return true;
        }

        /*if (!$activity->isFinalized() && $activity->getMasterUser() == $currentUser) {
            return true;
        }*/

        // Only case left : activity manager being leader of all stages
        $k = 0;
        foreach ($activity->stages as $stage) {
            foreach ($stage->getParticipants() as $participant) {
                if ($participant->getUser() == $currentUser && $participant->isLeader()) {
                    $k++;
                    break;
                }
            }
        }
        if ($k === $activity->stages->count()) {return true;}
        return false;
    }

    /**
     * @param Request $request
     * @return mixed
     * @Route("/organization/self/update", name="saveOrganizationSelfModifications")
     */
    public function saveOrganizationSelfModification(Request $request){
        $organization = $this->org;
        $em = $this->em;
        $organizationProfileForm = $this->createForm(OrganizationProfileForm::class, $organization, ['standalone' => true]);
        $organizationProfileForm->handleRequest($request);
        if($organizationProfileForm->isSubmitted() && $organizationProfileForm->isValid()){            
            $em->persist($organization);
            $em->flush();
            return new JsonResponse();
        } else {
            $errors = $this->buildErrorArray($organizationProfileForm);
            return $errors;
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @Route("/settings/{entity}/icon/update", name="updateIcon")
     * @IsGranted("ROLE_ADMIN", statusCode=403)
     */
    public function updateIcon(Request $request, string $entity){
        $em = $this->em;
        if(!$request->get('id')){
            return new Response(null, Response::HTTP_NO_CONTENT); 
        }
        switch($entity){
            case 'event-type' :
                $repoE = $em->getRepository(EventType::class);
                break;
            default:
                break;
        }

        $element = $repoE->find($request->get('id'));
        $icon = $request->get('icoId') ? $em->getRepository(Icon::class)->find($request->get('icoId')): null;
        $element->setIcon($icon);
        $em->persist($element);
        $em->flush();
        return new JsonResponse();
    }

    /**
     * 
     * @param Request $request
     * @Route("/settings/{entity}/trans/update", name="updateTrans")
     * @IsGranted("ROLE_ADMIN", statusCode=403)
     */
    public function updateTrans(Request $request, string $entity){
        $em = $this->em;
        $id = $request->get('id');
        $trans = $request->get('trans');
        switch($entity){
            case 'event-group' :
                $entity = 'EventGroup';
                $property = 'name';
                break;
            case 'event-type' :
                $entity = 'EventType';
                $property = 'name';
                break;
            case 'event-name' :
                $entity = 'EventName';
                $property = 'name';
                break;
            case 'event-group-name' :
                $entity = 'EventGroupName';
                $property = 'name';
                break;
        }

        /** @var DynamicTranslation */
        $dynTrans = $em->getRepository(DynamicTranslation::class)->findOneBy(['entity' => $entity, 'entityId' => $id, 'entityProp' => $property]);
        if(!$dynTrans){
            $dynTrans = new DynamicTranslation();
            $dynTrans
                ->setEntity($entity)
                ->setEntityId($id)
                ->setEntityProp($property)
                ->setCreatedBy($this->user->getId());
        }

        foreach($trans as $locale => $transElmt){
            switch($locale){
                case 'fr':
                    $dynTrans->setFR($transElmt);
                    break;
                case 'en':
                    $dynTrans->setEN($transElmt);
                    break;
                case 'de':
                    $dynTrans->setDE($transElmt);
                    break;
                case 'es':
                    $dynTrans->setES($transElmt);
                    break;
                case 'lu':
                    $dynTrans->setLU($transElmt);
                    break;
                default:
                    break;
            } 
        }

        $em->persist($dynTrans);
        $em->flush();
        return new JsonResponse();
    }


    public function isDateFormatValid(string $date, $format='n/j/Y'){
        $dt = DateTime::createFromFormat($format, $date);
        return $dt && $dt->format($format) === $date;
    }

    /**
     * 
     * Function which queries on our database, depending of query type.
     * Could be either : participants, organizations (clients, non-clients or both), workerfirms, clients, external users or internal users
     * Type : 
     * -iu when query within self organization (feature : changing super admin, defining new admin...)
     * -eu when query within defined client (feature : adding new client user)
     * -p when query withing stage participants (feature : adding participants)
     * -wf when query on all known DealDrive firms (from initial base of 10k+ firms)
     * -nc when query on all known DealDrive firms which are not client of user organization (feature : adding new client)
     * -c when query on all firms which are user organization's clients (feature : adding a user within a existing client...)
     * -i when query on all firms, which may be considered as independant
     * 
     * @param Request $request
     * @return JsonResponse
     * @Route("/search/dynamic/elements", name="dynamicSearch")
     */
    public function dynamicSearchElements(Request $request, TranslatorInterface $translator){

        $em = $this->em;
        $qb = $em->createQueryBuilder();
        $qType = $request->get('qt');
        $name = $request->get('name');
        $qEntityId = $request->get('qid');
        $isFirmQuery = $qType == 'wf' || $qType == 'f' || $qType == 'nc' || $qType == 'c';
        $openUserQueries = $qType == 'p' || $qType == 'u' || $qType == 'eu';
        $excludingElementIds = [];
        // Already existing element ids within user organization - will be used to disable already existing choices
   
        if($qType == 'p'){
            $organization = $this->org;
            if($qEntityId){
                $excludingElementIds = $em->getRepository(Stage::class)->find($qEntityId)->getParticipants()->map(fn(Participation $p) => $p->getUser()->getId())->getValues();
            }
        } else if($qType == 'nc' || $qType == 'i'){
            $excludingElementIds = $this->org->getClients()->map(fn(Client $c) => $c->getId())->getValues();
        } else if($qType == 'eu'){
            $excludingElementIds = $em->getRepository(Client::class)->find($qEntityId)->getAliveExternalUsers()->map(fn(ExternalUser $eu) => $eu->getId())->getValues(); 
        } else if($qType == 'u'){
            $excludingElementIds = $this->org->getActiveUsers()->map(fn(User $u) => $u->getId())->getValues();
        } else if($qType == 'iu'){
            $excludingElementIds = [$qEntityId];
        }

        
        if($openUserQueries){
            $existingClientIds = $this->org->getClients()->map(fn(Client $c) => $c->getId())->getValues();
        }

        $user = $this->user;
        $organization = $user ? $this->org : null;
        $orgId = $organization ? $organization->getId() : null;
        $clients = $organization ? $organization->getClients() : new ArrayCollection();
        $repoP = $em->getRepository(Participation::class);

        if(!$isFirmQuery){

            $qb->select('ug.username', 'ug.id', 'u.picture AS usrPicture', 'u.id AS usrId', 'u.email AS usrEmail', 'eu.id AS extUsrId', 'c.id as cliId', 'u.synthetic AS synth', 'o.commname AS orgName', 'o.type AS orgType','wf.logo AS wfiLogo', 'wf.id AS wfiId', 'o.logo AS orgLogo', 'o.id AS orgId')
                ->from('App\Entity\User', 'u')
                ->leftJoin('App\Entity\UserGlobal', 'ug', 'WITH', 'ug.id = u.userGlobal')
                ->leftJoin('App\Entity\Organization', 'o', 'WITH', 'o.id = u.organization')
                ->leftJoin('App\Entity\WorkerFirm', 'wf', 'WITH', 'wf.id = o.workerFirm')
                ->leftJoin('App\Entity\Client', 'c', 'WITH', 'c.clientOrganization = u.organization')
                ->leftJoin('App\Entity\ExternalUser','eu','WITH','eu.client = c.id')
                ->where('u.username LIKE :startOpt1 OR u.username LIKE :startOpt2 AND u.deleted IS NULL');
                if($qType == 'iu'){
                    $qb->andWhere('u.organization = :org');
                    $qb->setParameter('org',$this->org);
                }

            $qb->setParameter('startOpt1', '% '. $name .'%')
                ->setParameter('startOpt2', $name .'%')
                ->addOrderBy('ug.id','ASC')
                ->addOrderBy('u.id','ASC');

            $userElmts = $qb->getQuery()->getResult();

            $currentUsrId = $userElmts[0]['usrId'];
            $trigger = false;
            $offset = 0;
            $usersGroupedById = [];
            // We did not group by usrId previous query, in order users who may be already user clients out of them. We simply determine out of
            // all users results having same user id, whether it is client or not, and we group the data.
            if($openUserQueries){
                foreach($userElmts as $key => $userElmt){
                    if($userElmt['usrId'] != $currentUsrId){
                        $currentUsrId = $userElmt['usrId'];
                        if($offset){
                            $userElmts[$key - 1]['cliId'] = $userElmts[$key - $offset]['cliId'];
                            $userElmts[$key - 1]['extUsrId'] = $userElmts[$key - $offset]['extUsrId'];
                        } else {
                            $userElmts[$key - 1]['cliId'] = '';
                            $userElmts[$key - 1]['extUsrId'] = '';
                        }
                        $usersGroupedById[] = $userElmts[$key - 1];
                        $offset = 0;
                        $trigger = false;
                    } else {
                        if(in_array($userElmt['cliId'], $existingClientIds) !== false){
                            $trigger = true;
                        }
                        if($trigger){
                            $offset++;
                        }
                    }
                }
                if($userElmts[sizeof($userElmts) - 1]['usrId'] != $userElmts[sizeof($userElmts) - 2]['usrId']){
                    if($offset){
                        $userElmts[sizeof($userElmts) - 1]['cliId'] = $userElmts[sizeof($userElmts) - 1 - $offset]['cliId'];
                        $userElmts[sizeof($userElmts) - 1]['extUsrId'] = $userElmts[sizeof($userElmts) - 1 - $offset]['extUsrId'];
                    } else {
                        $userElmts[sizeof($userElmts) - 1]['cliId'] = '';
                        $userElmts[sizeof($userElmts) - 1]['extUsrId'] = '';
                    }
                    $usersGroupedById[] = $userElmts[sizeof($userElmts) - 1];
                }

                $userElmts = $usersGroupedById;
            }
        }

        if($qType == 'p'){

            $qb2 = $em->createQueryBuilder();
            $teamElmts = $qb2->select('t.name', 't.id AS teaId')
                ->from('App\Entity\Team', 't')
                ->leftJoin('App\Entity\Participation', 'p', 'WITH', 'p.team = t.id')
                ->where('t.name LIKE :startOpt1 OR t.name LIKE :startOpt2')
                ->setParameter('startOpt1', '% '. $name .'%')
                ->setParameter('startOpt2', $name .'%')
                ->getQuery()
                ->getResult();

            $qb3 = $em->createQueryBuilder();
            $firmElmts = $qb3->select('wf.name AS orgName','wf.id AS wfiId', 'wf.logo AS wfiLogo', 'o.logo AS orgLogo', 'o.id AS orgId', 'c.id AS cliId', 'u.id AS usrId', 'eu.id AS extUsrId')
                ->from('App\Entity\WorkerFirm', 'wf')
                ->leftJoin('App\Entity\Organization', 'o', 'WITH', 'o.workerFirm = wf.id')
                ->leftJoin('App\Entity\User', 'u', 'WITH', 'u.organization = o.id')
                ->leftJoin('App\Entity\Client', 'c', 'WITH', 'c.clientOrganization = o.id')
                ->leftJoin('App\Entity\ExternalUser', 'eu', 'WITH', 'eu.client = c.id')
                ->where('wf.name LIKE :startOpt1 OR wf.name LIKE :startOpt2')
                ->andWhere('u.synthetic = TRUE')
                ->andWhere('eu.synthetic = TRUE')
                ->setParameter('startOpt1', '% '. $name .'%')
                ->setParameter('startOpt2', $name .'%')
                ->getQuery()
                ->getResult();

            $elements = new ArrayCollection(array_filter(array_merge($userElmts, $teamElmts, $firmElmts)));


        } else if($qType == 'u' || $qType == 'eu' || $qType == 'i' || $qType == 'iu'){
            
            $elements = new ArrayCollection($userElmts);


        } else if ($isFirmQuery) {


                $qb->select('wf.name AS orgName', 'wf.id AS wfiId', 'wf.logo as wfiLogo', 'o.id AS orgId', 'o.logo as orgLogo' ,'c.id AS cliId', 'IDENTITY(c.organization) AS cOrg')
                    ->from('App\Entity\WorkerFirm', 'wf')
                    ->leftJoin('App\Entity\Organization', 'o', 'WITH', 'o.workerFirm = wf.id')
                    ->leftJoin('App\Entity\Client', 'c', 'WITH', 'c.clientOrganization = o.id');
           

            $qb->where('wf.name LIKE :startOpt1 OR wf.name LIKE :startOpt2');

            if($qType == 'c'){
                $qb->andWhere('c.organization = :oid')
                ->setParameter('oid',$this->org);
            }

            $qb->groupBy('o.id')
            ->setParameter('startOpt1', '% '. $name .'%')
            ->setParameter('startOpt2', $name .'%')
            ->orderBy('wf.commonName','ASC')
            ->getQuery()->getResult();

            $elements = $qb->getQuery()->getResult();

            /*
            if($qType == 'nc' || $qType == 'f'){
                $arrangedFirmElements = [];
                foreach($firmElements as $firmElement){

                    $arrangedFirmElement = $firmElement;
                    $client = $this->org->getClients()->filter(fn(Client $c) => $c->getClientOrganization()->getId() == $firmElement['orgId'])->first();
                    if($client){
                            $arrangedFirmElement['cliId'] = $client->getId();
                            if($qType == 'nc'){
                                $arrangedFirmElement['ex'] = 1;
                            }
                            $arrangedFirmElements[] = $arrangedFirmElement;
                    } else {
                        $arrangedFirmElements[] = $arrangedFirmElement;
                    }
                }
            } else {
                $arrangedFirmElements = $firmElements;
            }*/
          
            //$elements = new ArrayCollection($arrangedFirmElements);

            
        }

        $qParts = [];
        $arrangedElmts = [];
        $arrangedElmt = null;
        $currentUserGlobalValue = null;
        $excluding = false;
        
        //if($qType == 'p' || $qType == 'eu' || $qType == 'u' || $qType == 'i' || $qType == 'iu'){
        //return new JsonResponse($elements);

            foreach($elements as $element){

                    switch($qType){
                        case 'eu':
                            $potentialExcludingElmtId = $element['extUsrId'];
                            break;
                        case 'i':
                        case 'nc':
                            $potentialExcludingElmtId = $element['cliId'];
                            break;
                        case 'u':
                        case 'iu':
                        case 'p':
                            $potentialExcludingElmtId = $element['usrId'];
                            break;
                    }
                    
                    if(!$isFirmQuery){
                        
                        // Aggregating by global user
    
                        if($element['id'] != $currentUserGlobalValue){
                            if($currentUserGlobalValue){
                                $arrangedElmts[] = $arrangedElmt;
                            }
                            $currentUserGlobalValue = $element['id'];
                            $arrangedElmt = $element;
                            unset($arrangedElmt['synth']);
                            unset($arrangedElmt['orgId']);
                            if(!$isFirmQuery){
                                unset($arrangedElmt['usrId']);
                                unset($arrangedElmt['extUsrId']);
                                unset($arrangedElmt['usrEmail']);
                            }
                            unset($arrangedElmt['cliId']);
                            unset($arrangedElmt['wfiId']);
                            unset($arrangedElmt['orgName']);
                            unset($arrangedElmt['orgType']);
                            unset($arrangedElmt['orgLogo']);
                            unset($arrangedElmt['wfiLogo']);
                            $existing = false;
                        }
    
                        
                        $arrangedElmt['usrId'][] = $element['usrId'];
                        $arrangedElmt['extUsrId'][] = $element['extUsrId'];
                        $arrangedElmt['hasEm'][] = $element['usrEmail'] ? 1 : 0;
                        $arrangedElmt['synth'][] = $element['synth'] ? 1 : 0;
                        if(!array_key_exists('usrPicture', $arrangedElmt))  {
                            $arrangedElmt['usrPicture'] = $element['usrPicture'];
                        }
                        
                        $arrangedElmt['wfiId'][] = $element['wfiId'];
                        $arrangedElmt['orgId'][] = $element['orgId'];
                        $arrangedElmt['cliId'][] = $element['cliId'];
                        $arrangedElmt['orgName'][] = $element['orgType'] == 'C' ? '' : $element['orgName'];
                        $arrangedElmt['orgLogo'][] = $element['orgLogo'];
                        $arrangedElmt['wfiLogo'][] = $element['wfiLogo'];
            
                        if(!$excluding && sizeof($excludingElementIds) > 0 && in_array($potentialExcludingElmtId,$excludingElementIds) !== false){
                            $excluding = true;
                            $arrangedElmt['ex'] = 1;
                        }
                    } else {

                        $arrangedElmt = $element;
                        if(sizeof($excludingElementIds) > 0 && in_array($element['cliId'],$excludingElementIds) !== false){
                            $arrangedElmt['ex'] = 1;
                        }
                        $arrangedElmts[] = $arrangedElmt;
                    }
                    
                //}

            /*} else if($qType == 'nc' || $qType == 'f'){

                $arrangedElmt = $element;
                $client = $this->org->getClients()->filter(fn(Client $c) => $c->getClientOrganization()->getId() == $element['orgId'])->first();
                if($client){
                        $arrangedElmt['cliId'] = $client->getId();
                        if($qType == 'nc'){
                            $arrangedElmt['ex'] = 1;
                        }
                        $arrangedElmts[] = $arrangedElmt;
                } else {
                    $arrangedElmts[] = $arrangedElmt;
                }

            } else {
                $arrangedElmts[] = $element;
            }
            */
            
            //foreach($userElmts as $userElmt){

                /*if($qType == 'u' && in_array($userElmt['usrId'], $elmtUserIds) !== false || $qType == 'eu' && in_array($userElmt['extUsrId'], $elmtExtUserIds) !== false){
                    continue;
                }*/
                
                



            
            }
        
        // Adding last prepared element for user queries as we leave the loop before adding it
        if(!$isFirmQuery && sizeof($elements)){
            $arrangedElmts[] = $arrangedElmt;
        } 

        foreach($arrangedElmts as $arrangedElmt){
            
            if(isset($arrangedElmt['extUsrId'])){
                $arrangedElmt['e'] = 'eu';
            } else if(isset($arrangedElmt['usrId'])){
                $arrangedElmt['e'] = 'u';
            } else if(isset($arrangedElmt['teaId'])){
                $arrangedElmt['e'] = 't';
            } else {
                $arrangedElmt['e'] = 'f';
            }
            //if(!$element['username']){$element['usrId'] = "";}
            $qParts[] = $arrangedElmt;
        }

        $msg = '';
        if(!sizeof($qParts) && ($qType == 'iu' || $qType == 'c')){
            switch($qType){
                case 'iu':
                    $elmtType = 'user';
                    break;
                case 'c':
                    $elmtType = 'client';
                    break;
                
            }
            $msg = $translator->trans('user_list.add_user_modal.unexisting_element', ['element' => $translator->trans($elmtType)]);
        }
        //$workerFirms = array_combine($values,$keys);
        return new JsonResponse(['qParts' => $qParts, 'msg' => $msg],200);

    }

}
