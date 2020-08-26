<?php

namespace App\Controller;

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
use App\Entity\TeamUser;
use App\Entity\Template;
use App\Entity\TemplateActivity;
use App\Entity\TemplateCriterion;
use App\Entity\TemplateStage;
use App\Entity\Title;
use App\Entity\User;
use App\Entity\Weight;
use App\Entity\WorkerFirm;
use App\Repository\OrganizationRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
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

class OrganizationController extends MasterController
{
    private $notFoundResponse;

    public function __construct(EntityManagerInterface $em, Security $security, RequestStack $stack, MailerInterface $mailer) {
        parent::__construct($em, $security, $stack, $mailer);
        $this->notFoundResponse = new Response(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/organization/administrators/create", name="addFirstAdmin")
     */
    public function addFirstAdminAction(Request $request){
        $formFactory = self::getFormFactory();
        $user = new User;
        $addFirstAdminForm = $this->createForm(AddFirstAdminForm::class,$user);
        $addFirstAdminForm->handleRequest($request);
        if($addFirstAdminForm->isValid()){
            $defaultOrgWeight = $this->user->getOrganization()->getWeights()->filter(function(Weight $w){
                return $w->getPosition() == null && $w->getCreatedBy() == null && $w->getInterval() == null && $w->getTimeframe() == null;
            })->first();
            $user->setOrgId($this->user->getOrgId())
                ->setRole(USER::ROLE_ADMIN)
                ->setToken(md5(rand()))
                ->setWgtId($defaultOrgWeight->getId())
                ->setWeightIni($defaultOrgWeight->getValue());
            $this->em->persist($user);
            $this->em->flush();
            return new JsonResponse(['id' => $user->getId(), 'fullname' => $user->getFullName()],200);
        } else {
            $errors = $this->buildErrorArray($addFirstAdminForm);
            return $errors;
        }
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
            MasterController::sendMail($app, $recipients, 'registration', $settings);
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
        $currentUser = MasterController::getAuthorizedUser();
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
                        $recipients[] = $participant->getDirectUser();
                        $participant->setStatus(1);
                        $participant->setIsMailed(true);
                        $em->persist($participant);
                    }

                    self::sendMail($app, $recipients, 'activityParticipation', $mailSettings);
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
            $newStage
                ->setMasterUserId($currentUser->getId())
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

                $stage->setMasterUser($currentUser);
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

                        if($element->getStatus() == $element::STATUS_FUTURE && $stage->getStatus() == $stage::STAGE_ONGOING){
                            $element->setStatus($element::STATUS_ONGOING);
                        } else {
                            if($element->getActiveStages()->forAll(function(int $i,Stage $s){
                                return $s->getStatus() == $s::STAGE_UNSTARTED;
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
     * @Route("/{entity}/{elmtId}/stage/{stgId}/criterion/validate/{crtId}", name="validateCriterionElement")
     */
    public function validateElementCriterionAction(Request $request, $entity, $elmtId, $stgId, $crtId)
    {
        $em = $this->em;
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

            if (!$criterionForm->isValid()) {
                $errors = $this->buildErrorArray($criterionForm);
                return $errors;
            } else {

                if($entity == 'activity' && ($crtId == 0 || ($criterionBeforeUpgrade->getCName() != $criterion->getCName() || $criterionBeforeUpgrade->getType() != $criterion->getType()))){

                    // Checking if we need to unvalidate participations (we decide to unlock all stage participations and not only the modified one)
                    $completedStageParticipations = $element->getParticipants()->filter(function(Participation $p){
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
                            ['stage' => $element, 'actElmt' => 'criterion']
                        );
                    }
                }


                $impactedCriteria = new ArrayCollection();
                foreach ($element->getCriteria() as $stageCriterion){
                    if($stageCriterion != $criterion){$impactedCriteria->add($stageCriterion);}
                }
                $sumNewWeights = 0;

                foreach($impactedCriteria as $stageCriterion){

                    if($impactedCriteria->last() != $stageCriterion){
                        $newWeight = ($crtId == 0) ?
                            round((1 - $criterion->getWeight()) * $stageCriterion->getWeight(), 2) :
                            round((1 - $criterion->getWeight()) / (1 - $criterionBeforeUpgrade->getWeight()) * $stageCriterion->getWeight(), 2);

                        $stageCriterion->setWeight($newWeight);
                        $sumNewWeights += $newWeight;
                    } else {
                        $stageCriterion->setWeight(1 - $criterion->getWeight() - $sumNewWeights);
                    }
                    $em->persist($stageCriterion);
                }

                if($crtId == 0){
                    $element->addCriterion($criterion);
                    $em->persist($element);
                } else {
                    $em->persist($criterion);
                }
                //$criterion->setStage($element);
                $em->flush();


                if($crtId == 0){
                    // In case participants were set before first criterion, we link these participations to this new criterion
                    if(sizeof($element->getCriteria()) == 1){
                        $unsetParticipations = $repoP->findBy(['stage' => $element, 'criterion' => null]);
                        foreach($unsetParticipations as $unsetParticipation){
                            $criterion->addParticipation($unsetParticipation);
                        }
                    } else {
                        $firstCriterionExistingParticipations = $repoP->findBy(['stage' => $element, 'criterion' => $element->getCriteria()->first()]);
                        foreach($firstCriterionExistingParticipations as $firstCriterionExistingParticipation){
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
        $currentUser = MasterController::getAuthorizedUser();
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
        $currentUser = MasterController::getAuthorizedUser();
        $em = $this->getEntityManager($app);
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

        $em = $this->getEntityManager($app);
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
        $em = $this->getEntityManager($app);
        
        $currentUser = MasterController::getAuthorizedUser();
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
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/users/create", name="createUser")
     */
    public function addUserAction(Request $request)
    {
        $currentUser = $this->user;
        $adminFullName = $currentUser->getFirstname() . " " . $currentUser->getLastname();
        $id            = $currentUser->getId();
        $orgId         = $currentUser->getOrganization()->getId();
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
            if ($orgOption->getOName()->getName() === 'enabledUserCreatingUser') {
                $orgEnabledCreatingUser = $orgOption->isOptionTrue();
            }
        }

        if ($currentUser->getRole() !== 4 && $currentUser->getRole() !== 1 && !($currentUser->getDepartment()->getMasterUser() == $currentUser || ($orgEnabledCreatingUser && $currentUser->isEnabledCreatingUser()))) {
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

                // Les lignes qui suivent sont horribles mais sont un hack au fait qu'a priori on ne puisse pas lier position et département à User (mais à vérifier, peut-être que ça remarche...)
                // $user->getPosId() renvoie une Position, et $user->getDptId() un département.

                $user->setWeightIni($user->getWeight()->getValue());
                $token = md5(mt_rand());

                $user->setOrganization($organization)
                    ->setPosition($user->getPosition())
                    ->setDepartment($user->getDepartment())
                    ->setTitle($user->getTitle())
                    ->setWeight($user->getWeight())
                    ->setToken($token)
                    ->setCreatedBy($currentUser->getId());

                if ($user->getSuperior() !== null) {
                    $user->setSuperior($user->getSuperior());
                }
                else {
                    $user->setSuperior(null);
                }
                $em->persist($user);
                $settings['tokens'][] = $token;
                $recipients[]         = $user;

            }

            $settings['adminFullName'] = $currentUser->getFullName();
            $settings['rootCreation']  = false;
            $em->flush();
//            self::sendMail($app, $recipients, 'registration', $settings);
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
     * @param Application $app
     * @param $cliId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/client/validate/{cliId}", name="validateClient")
     */
    public function validateClientAction(Request $request, $cliId){

        $em = $this->em;
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        
        $repoC = $em->getRepository(Client::class);
        $repoO = $em->getRepository(Organization::class);

        $organization = $currentUser->getOrganization();
        $client = $cliId == 0 ? new Client : $repoC->find($cliId);
        $clientForm = $this->createForm(ClientType::class, $client, [ 'standalone' => false, 'hasChildrenElements' => false ]);
        $clientForm->handleRequest($request);

        if ($clientForm->isValid()) {

            $wfiId = $request->get('wfiId');
            $repoWF = $em->getRepository(WorkerFirm::class);
            /** @var WorkerFirm */
            $workerFirm = $wfiId ? $repoWF->find((int) $wfiId) : new WorkerFirm;

            if(!$wfiId){
                $workerFirm
                    ->setCommonName($client->getCommname())
                    ->setName($client->getCommname());
                $em->persist($workerFirm);
            }

            if($cliId == 0){

                $clientOrganization = new Organization;
                $clientOrganization
                    ->setCommname($workerFirm->getName())
                    ->setType($client->getType())
                    ->setValidated(new DateTime)
                    ->setExpired(new DateTime('2100-01-01 00:00:00'))
                    ->setWeight_type('role')
                    ->setWorkerFirm($workerFirm);
                $em->persist($clientOrganization);

                $defaultOrgWeight = new Weight;
                $defaultOrgWeight->setOrganization($clientOrganization)
                    ->setValue(100);
                $clientOrganization->addWeight($defaultOrgWeight);

                $em->flush();


                $client
                ->setWorkerFirm($workerFirm)
                ->setOrganization($organization)
                ->setClientOrganization($clientOrganization)
                ->setCreatedBy($currentUser->getId());

                // Synth ZZ user creation
                $em->flush();
                $syntheticUser = new User;
                $syntheticUser
                    ->setFirstname('ZZ')
                    ->setLastname('ZZ')
                    ->setRole(3)
                    ->setOrgId($clientOrganization->getId());
                $em->persist($syntheticUser);

                $em->persist($client);

                // Synth ZZ ext user creation
                $syntheticExtUser = new ExternalUser;
                $syntheticExtUser
                    ->setFirstname('ZZ')
                    ->setLastname('ZZ')
                    ->setWeightValue(100)
                    ->setUser($syntheticUser)
                    ->setCreatedBy($currentUser->getId())
                    ->setClient($client);
                $em->persist($syntheticExtUser);

            }

            $em->flush();

            return $cliId == 0 ?
                $app->json(['status' => 'done', 'cliId' => $client->getId()], 200) :
                $app->json(['status' => 'done'], 200);
        } else {
            return $this->buildErrorArray($clientForm);
        }
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $cliId
     * @param $extId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
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
                    ->setOrgId($client->getClientOrganization()->getId())
                    ->setRole(3)
                    ->setToken($token);
                $em->persist($user);
                $em->flush();
                $settings = [];
                $settings['tokens'][] = $token;
                $settings['invitingUser'] = $currentUser;
                $settings['invitingOrganization'] = $currentUser->getOrganization();
                $recipients[] = $user;
                MasterController::sendMail($app, $recipients, 'externalInvitation', $settings);

                $externalUser->setClient($client)->setUser($user);
            }
            $em->persist($externalUser);
            $em->flush();
            return $extId == 0 ?
                $app->json(['status' => 'done', 'extId' => $externalUser->getId()], 200) :
                $app->json(['status' => 'done'], 200);
        } else {
            return $this->buildErrorArray($individualForm);
        }
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/clients/create", name="createClient")
     */
    public function addClientAction(Request $request)
    {
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $em           = $this->em;
        $repoO        = $em->getRepository(Organization::class);
        $repoC        = $em->getRepository(Client::class);
        $organization = $currentUser->getOrganization();

        $orgOptions = $organization->getOptions();
        foreach ($orgOptions as $orgOption) {
            if ($orgOption->getOName()->getName() == 'enabledUserCreatingUser') {
                $orgEnabledCreatingUser = $orgOption->isOptionTrue();
            }
        }

        if ($currentUser->getRole() != 4 && $currentUser->getRole() != 1 && !($currentUser->getDepartment($app)->getMasterUser() == $currentUser || $orgEnabledCreatingUser && $currentUser->isEnabledCreatingUser())) {
            return $this->render('errors/403.html.twig');
        }

        $organizationClients = $organization->getClients();
        $clients             = [];
        foreach ($organizationClients as $organizationClient) {
            $clients[] = $organizationClient->getClientOrganization();
        }
        

        $clientsForm = $this->createForm(AddClientForm::class, null, ['standalone' => true, 'organization' => $organization]);
        $clientForm = $this->createForm(ClientType::class, null, [ 'standalone' => false, 'hasChildrenElements' => false]);
        $individualForm = $this->createForm(ExternalUserType::class, null, ['standalone' => true]);

        $clientsForm->handleRequest($request);
        $clientForm->handleRequest($request);
        $individualForm->handleRequest($request);

        if ($clientsForm->isSubmitted() && $clientsForm->isValid()) {

            $settings           = [];
            $recipients         = [];
            $existingRecipients = [];
            foreach ($clientsForm->get('clients') as $clientForm) {

                /** @var Client $client */
                $client = $repoC->findOneBy(['organization' => $currentUser->getOrganization(), 'commname' => $clientForm->getData()->getCommname()]);
                /** @var Organization */
                $organization = $client->getClientOrganization();

                foreach ($clientForm->get('aliveExternalUsers') as $individualForm) {

                    $individual = $individualForm->getData();
                    $defaultOrgWeight = $em->getRepository(Weight::class)->findOneBy(['organization' => $organization, 'position' => null, 'usrId' => null]);

                    // Create internal user CPTY

                    $user = $em->getRepository(User::class)->findOneBy(['firstname' => $individual->getFirstname(), 'lastname' => $individual->getLastname()]);
                    if ($user == null) {
                        $token                = md5(rand());
                        $settings['tokens'][] = $token;
                        $user                 = new User;
                        $user
                            ->setToken($token)
                            ->setFirstname($individual->getFirstname())
                            ->setLastname($individual->getLastname())
                            ->setEmail($individual->getEmail())
                            ->setRole(2)
                            ->setWgtId($defaultOrgWeight->getId())
                            ->setWeightIni($defaultOrgWeight->getValue())
                            ->setCreatedBy($currentUser->getId())
                            ->setOrgId($organization->getId());
                        $em->persist($user);
                        $recipients[] = $user;
                    } else {
                        $existingRecipients[] = $user;
                    }

                    $individual
                        ->setUser($user)
                        ->setCreatedBy($currentUser->getId())
                        ->setClient($client);

                    $em->persist($individual);
                }
            }

            $em->flush();

            $settings['invitingUser'] = $currentUser;
            $settings['invitingOrganization'] = $currentUser->getOrganization();
            MasterController::sendMail($app, $recipients, 'externalInvitation', $settings);
            return $app->redirect($app['url_generator']->generate('manageUsers'));

        }

        return $this->render('user_external_create.html.twig',
            [
                'form'                   => $clientsForm->createView(),
                'multipleClientCreation' => true,
                'clientForm' => $clientForm->createView(),
                'individualForm' => $individualForm->createView(),
            ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $cliId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/client/{cliId}/users/create", name="createClientUser")
     */
    public function addClientUserAction(Request $request, $cliId)
    {
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $em    = $this->em;
        $repoC = $em->getRepository(Client::class);

        $client             = $repoC->find($cliId);
        $clientOrganization = $client->getClientOrganization();

        
        $clientUserForm = $this->createForm(ClientType::class, $client, ['standalone' => true]);
        $clientForm = $this->createForm(ClientType::class, null, [ 'standalone' => true, 'hasChildrenElements' => false ]);
        $individualForm = $this->createForm(ExternalUserType::class, null, ['standalone' => true]);
        $clientUserForm->handleRequest($request);
        $clientForm->handleRequest($request);
        $individualForm->handleRequest($request);

        if ($clientUserForm->isSubmitted() && $clientUserForm->isValid()) {
            $existingRecipients = [];
            $recipients         = [];
            foreach ($clientUserForm->get('aliveExternalUsers') as $externalUserForm) {

                $externalUser = $externalUserForm->getData();

                // Create internal user CPTY

                $user = $em->getRepository(User::class)->findOneBy(['firstname' => $externalUser->getFirstname(), 'lastname' => $externalUser->getLastname(), 'orgId' => $clientOrganization->getId()]);

                if ($externalUser->getEmail() != null && $user == null) {
                    $token                = md5(rand());
                    $settings['tokens'][] = $token;
                    $user                 = new User;
                    $token                = md5(rand());
                    $user
                        ->setToken($token)
                        ->setFirstname($externalUser->getFirstname())
                        ->setLastname($externalUser->getLastname())
                        ->setEmail($externalUser->getEmail())
                        ->setRole(!$clientOrganization->hasActiveAdmin() ? 2 : 3)
                        ->setWeightIni($externalUser->getWeightValue() ?: 100)
                        ->setCreatedBy($currentUser->getId())
                        ->setOrgId($clientOrganization->getId());
                    $em->persist($user);
                    $recipients[] = $user;
                } else {
                    //$existingRecipients[] = $user;
                }

                $externalUser
                    ->setUser($user)
                    ->setCreatedBy($currentUser->getId())
                    ->setClient($client);

                $em->persist($externalUser);
            }

            $em->flush();

            $settings['adminFullName'] = $currentUser->getFullName();
            $settings['rootCreation']  = false;
            MasterController::sendMail($app, $recipients, 'registration', $settings);
            return $app->redirect($app['url_generator']->generate('manageUsers'));
        }

        return $this->render('user_external_create.html.twig',
            [
                'form'                   => $clientUserForm->createView(),
                'multipleClientCreation' => false,
                'clientForm' => $clientForm->createView(),
                'individualForm' => $individualForm->createView(),
            ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $fileName
     * @param $headerParameters
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/users-csv-insert/{fileName}/{headerParameters}", name="insertCheckedCSV")
     */
    public function insertCheckedCSV(Request $request, $fileName, $headerParameters)
    {
        $filePath = __DIR__ . '/' . $fileName . '.txt';
        $csv      = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);
        $header = $csv->getHeader();

        $headerOrderEntries = explode("-", $headerParameters);
        $em                 = $this->em;
        $currentUser        = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $orgId = $currentUser->getOrgId();
        $repoO = $em->getRepository(Organization::class);
        $repoU = $em->getRepository(User::class);
        $repoD = $em->getRepository(Department::class);
        $repoP = $em->getRepository(Position::class);
        $repoW = $em->getRepository(Weight::class);

        $organization = $repoO->find($orgId);

        $firstNamePos  = array_search(0, $headerOrderEntries);
        $lastNamePos   = array_search(1, $headerOrderEntries);
        $emailPos      = array_search(2, $headerOrderEntries);
        $departmentPos = array_search(3, $headerOrderEntries);
        $positionPos   = array_search(4, $headerOrderEntries);
        $superiorPos   = array_search(5, $headerOrderEntries);
        $rolePos       = array_search(6, $headerOrderEntries);
        $weightPos     = array_search(7, $headerOrderEntries);

        $records   = $csv->getRecords();
        $fullNames = [];
        $emails    = [];
        foreach ($records as $key => $theRecord) {
            $record     = array_values($theRecord);
            $email      = $record[$emailPos];
            $firstName  = $record[$firstNamePos];
            $lastName   = $record[$lastNamePos];
            $position   = null;
            $department = null;

            // If user doesn't exist in the organization
            if ($repoU->findOneBy(['firstname' => $firstName, 'lastname' => $lastName, 'email' => $email, 'orgId' => $organization->getId()]) == null) {

                $userFullName = $record[$firstNamePos] . ' ' . $record[$lastNamePos];

                // If user has enough mandatory data and is not a inserted twice
                if ($firstName != "" &&
                    $lastName != "" &&
                    $email != "" &&
                    preg_match("/^(?:[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.) {3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])*$/", $email) &&
                    !in_array($userFullName, $fullNames) &&
                    !in_array($email, $emails)) {

                    $user = new User;
                    $user
                        ->setCreatedBy($currentUser->getId())
                        ->setFirstname($firstName)
                        ->setLastname($lastName)
                        ->setEmail($record[$emailPos])
                        ->setOrgId($organization->getId());

                    $emails[]    = $email;
                    $fullNames[] = $userFullName;

                    if ($departmentPos !== false && $record[$departmentPos] != '') {
                        $department = $repoD->findOneBy(['name' => $record[$departmentPos], 'organization' => $organization]);
                        if ($department == null) {
                            $department = new Department;
                            $department->setOrganization($organization)->setName($record[$departmentPos]);
                            $em->persist($department);
                            $em->flush();
                        }
                        $user->setDptId($department->getId());
                    }
                    if ($positionPos !== false && $record[$positionPos] != '') {
                        $position = $repoP->findOneBy(['name' => $record[$positionPos], 'organization' => $organization]);
                        if ($position == null) {
                            $position = new Position;
                            $position->setOrganization($organization)->setName($record[$positionPos]);
                            $em->persist($position);
                            $em->flush();
                        }
                        $user->setPosId($position->getId());
                    }
                    if ($rolePos !== false) {
                        $insertedRole = $record[$rolePos];
                        if ($insertedRole != null) {
                            switch ($insertedRole) {
                                case 'Administrator':
                                    $user->setRole(1);
                                    break;
                                case 'Activity_Manager':
                                    $user->setRole(2);
                                    break;
                                case 'Collaborator':
                                    $user->setRole(3);
                                    break;
                                default:
                                    $user->setRole(3);
                                    break;
                            }
                        } else {
                            $user->setRole(3);
                        }

                    } else {
                        $user->setRole(3);
                    }
                    $em->persist($user);
                    $insertedUsers[$key]      = $user;
                    $recipients[]             = $user;
                    $token                    = md5(rand());
                    $settings['tokens'][]     = $token;
                    $settings['rootCreation'] = false;
                }
            }
        }

        $em->flush();

        if ($superiorPos !== false) {
            // Insert superiors to user if necessary
            foreach ($records as $key => $theRecord) {
                $record = array_values($theRecord);
                if (array_key_exists($key, $insertedUsers)) {
                    $user = $insertedUsers[$key];
                    if ($record[$superiorPos] != null) {
                        $superiorFirstName = explode(" ", $record[$superiorPos])[0];
                        $superiorLastName  = explode(" ", $record[$superiorPos])[1];
                        $superior          = $repoU->findOneBy(['firstname' => $superiorFirstName, 'lastname' => $superiorLastName, 'orgId' => $organization->getId()]);
                        if ($superior != null) {
                            $user->setSuperior($superior->getId());
                            $em->persist($user);
                        }
                    }
                }
            }
            $em->flush();
        }

        // Adding user(s) weight
        if ($weightPos !== false) {

            foreach ($records as $key => $theRecord) {
                $record = array_values($theRecord);
                if (array_key_exists($key, $insertedUsers)) {

                    $user     = $insertedUsers[$key];
                    $position = $repoP->findOneBy(['name' => $record[$positionPos], 'organization' => $organization]);

                    if ($position != null) {
                        $weight = $repoW->findOneBy(['position' => $position, 'value' => $record[$weightPos], 'usrId' => null]);
                        if ($weight != null) {
                            if ($weight->getValue() != $record[$weightPos]) {
                                $weight = new Weight;
                                $weight->setOrganization($organization);
                                $weight->setUsrId($user->getId());
                            }
                            $weightValue = $record[$weightPos];
                        } else {
                            $weight = new Weight;
                            $weight->setOrganization($organization)->setPosition($position);
                            $weightValue = 100;
                        }
                    } else {
                        $weight = new Weight;
                        $weight->setOrganization($organization);
                        $weightValue = 100;
                    }

                    $weight->setValue($weightValue)->setInterval(0)->setTimeframe('D')->setCreatedBy($this->user->getId());
                    if ($position != null) {
                        $position->addWeight($weight);
                    }
                    $organization->addWeight($weight);
                    $em->persist($weight);
                    $em->flush();
                    $user->setWeightIni($weightValue)->setWgtId($weight->getId());
                }
            }
            $em->flush();
        } else {
            foreach ($records as $key => $theRecord) {
                $record = array_values($theRecord);
                if (array_key_exists($key, $insertedUsers)) {
                    $user     = $insertedUsers[$key];
                    $position = $repoP->findOneBy(['name' => $record[$positionPos], 'organization' => $organization]);
                    if ($position != null) {
                        $weight = $repoW->findOneBy(['position' => $position, 'usrId' => null]);
                        if ($weight != null) {
                            $weightValue = $weight->getValue();
                        } else {
                            $weight = new Weight;
                            $weight->setOrganization($organization);
                            $weightValue = 100;
                            $weight->setValue($weightValue)->setCreatedBy($this->user->getId());
                        }
                    } else {
                        $weight = new Weight;
                        $weight->setOrganization($organization);
                        $weightValue = 100;
                        $weight->setUsrId($user->getId())->setValue($weightValue)->setCreatedBy($this->user->getId());
                    }
                    $em->persist($weight);
                    $em->flush();
                    $user->setWeightIni($weightValue)->setWgtId($weight->getId());
                }
            }
            $em->flush();
        }
        //self::sendMail($app, $recipients,'registration', $settings);
        return new JsonResponse(['message' => 'Success!'], 200);
        unlink($filePath);

    }

    //Create users in a ajax mode

    /**
     * @param Request $request
     * @param Application $app
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/users", name="ajexUSerAdd")
     */
    public function ajaxAddUserAction(Request $request)
    {
        //TODO : get current language dynamically

        $locale      = 'fr';
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $adminFullName = $currentUser->getFirstname() . " " . $currentUser->getLastname();
        $orgId         = $currentUser->getOrgId();
        $em            = $this->em;
        $repoO         = $em->getRepository(Organization::class);
        $repoU         = $em->getRepository(User::class);
        $repoW         = $em->getRepository(Weight::class);

        $organization           = $repoO->find($orgId);
        $departments            = $organization->getDepartments();
        
        $orgEnabledCreatingUser = false;

        $orgOptions = $organization->getOptions();
        foreach ($orgOptions as $orgOption) {
            if ($orgOption->getOName()->getName() == 'enabledUserCreatingUser') {
                $orgEnabledCreatingUser = $orgOption->isOptionTrue();
            }
        }

        $userForm = $this->createForm(AddUserForm::class, null, ['standalone' => true, 'organization' => $organization, 'app' => $app, 'enabledCreatingUser' => $orgEnabledCreatingUser]);
        $userForm->handleRequest($request);

        $recipients = [];
        // Send user email
        $settings                  = [];
        $settings['adminFullName'] = $adminFullName;
        $settings['tokens']        = [];

        $file = $userForm->getData()['usersCSV'];

        if ($userForm->isValid()) {

            // Get imported users

            //$file = $userForm->getData()->getUsersCSV();

            if ($file) {
                $fileNameWithoutExtension = $this->generateUniqueFileName();
                $fileName                 = $fileNameWithoutExtension . '.' . $file->guessExtension();
                $file->move(__DIR__, $fileName);
                $filePath = __DIR__ . '/' . $fileName;
                $csv      = Reader::createFromPath($filePath, 'r');
                $csv->setHeaderOffset(0);
                $header = $csv->getHeader();

                $firstNameHeaders   = ['First name', 'FirstName', 'firstname', 'first_name', 'firstName', 'Firstname', 'first name', 'prenom', 'Prenom', 'Prénom'];
                $lastNameHeaders    = ['Last name', 'LastName', 'lastname', 'last_name', 'Lastname', 'Last Name', 'nom', 'Nom'];
                $emailHeaders       = ['email', 'Email', 'mail', 'email_address', 'EmailAddress', 'Addresse email', 'adresse_email', 'Courriel', 'courriel'];
                $departmentHeaders  = ['Department', 'department', 'Département', 'Departement', 'departement'];
                $positionHeaders    = ['position', 'function', 'Function', 'Job', 'Position', 'Fonction', 'fonction', 'poste'];
                $superiorHeaders    = ['manager', 'Manager', 'Boss', 'Chief', 'boss', 'chief', 'line_manager', 'Line Manager', 'superior', 'Superior', 'Responsible', 'responsible', 'Supérieur', 'Superieur', 'Responsable', 'responsable'];
                $roleHeaders        = ['role,Role,rôle,Rôle'];
                $weightHeaders      = ['weight', 'Weight', 'Poids', 'poids'];
                $allPossibleHeaders = [$firstNameHeaders, $lastNameHeaders, $emailHeaders, $departmentHeaders, $positionHeaders, $superiorHeaders, $roleHeaders, $weightHeaders];

                if (count($header) < 3) {
                    unlink($filePath);
                    return new JsonResponse(['error' => 'There might be a missing mandatory parameter in your CSV file. CSV should have at least 3 parameters labelled as : \'first_name\', \'last_name\' and \'email\'.'], 500);
                } else if (count(array_unique($header)) != count($header)) {
                    unlink($filePath);
                    return new JsonResponse(['error' => 'Your CSV file has columns with duplicate labels. Make sure each label is unique !'], 500);
                }

                foreach ($header as $headerEntry) {
                    $trigger = 0;
                    foreach ($allPossibleHeaders as $key => $possibleElementHeaders) {
                        if (in_array($headerEntry, $possibleElementHeaders)) {
                            $headerParameters[] = $key;
                            $trigger            = 1;
                            break;
                        }
                    }
                    if ($trigger == 0) {
                        $headerParameters[] = -1;
                    }
                }

                $tmp = new \SplTempFileObject;
                $tmp->fputcsv($header);
                $records = $csv->getRecords();
                $k       = 0;
                foreach ($records as $record) {
                    $tmp->fputcsv($record);
                    $k++;
                }

                if ($k == 0) {
                    unlink($filePath);
                    return new JsonResponse(['error' => 'There are no data in your CSV (there should one line for column references, and data in subsequent lines'], 500);
                }

                $reader  = Reader::createFromFileObject($tmp);
                $jsonCSV = json_encode($reader);
                return new JsonResponse(['headerParameters' => $headerParameters, 'csv' => $jsonCSV, 'fileName' => $fileNameWithoutExtension], 200);
            }

            // Fill results with manual added users
            //foreach ($userForm->getData()->getUsers() as $addedUser)

            // Throwing error if any user is already in DB
            foreach ($userForm->get('users') as $userData) {

                if ($repoU->findOneBy(['email' => $userData['email']->getData(), 'orgId' => $orgId])) {

                    $userData->get('email')->addError(new FormError('There is already a user address with such email address in your organization. Please choose another one'));
                    $errors = $this->buildErrorArray($userForm);
                    return $errors;
                }
            }

            //Inserting new users in DB

            foreach ($userForm->get('users') as $userData) {

                $user   = new User;
                $weight = $repoW->find($userData['weightIni']->getData());
                $token  = md5(rand());
                $user->setInternal($userData['internal']->getData())
                    ->setFirstname($userData['firstname']->getData())
                    ->setLastname($userData['lastname']->getData())
                    ->setEmail($userData['email']->getData())
                    ->setPosId($userData['position']->getData() == 0 ? null : $userData['position']->getData())
                    ->setDptId($userData['department']->getData() == 0 ? null : $userData['department']->getData())
                    ->setRole($userData['role']->getData())
                    ->setWeightIni($weight->getValue())
                    ->setOrgId($orgId)
                    ->setToken($token)
                    ->setWgtId($userData['weightIni']->getData())
                    ->setCreatedBy($currentUser->getId());
                $em->persist($user);
                $em->flush();

                $settings['tokens'][]     = $token;
                $settings['rootCreation'] = false;
                $recipients[]             = $user;
            }
            self::sendMail($app, $recipients, 'registration', $settings);
            return new JsonResponse(['message' => 'Success!'], 200);

        } else {

            if ($file) {
                //$translator = new Translator('fr_FR');
                //return new JsonResponse(['csv' => $translator->trans('create_user.csv.error')],500);
            }

            $errors = $this->buildErrorArray($userForm);
            return $errors;

        };

    }

    //Create users in a ajax mode

    /**
     * @param Request $request
     * @param Application $app
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/client/users", name="ajaxClientUserAdd")
     */
    public function ajaxAddClientUserAction(Request $request)
    {
        //TODO : get current language dynamically
        $locale      = 'fr';
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $adminFullName = $currentUser->getFirstname() . " " . $currentUser->getLastname();
        $orgId         = $currentUser->getOrgId();
        $em            = $this->em;
        $repoU         = $em->getRepository(User::class);
        $repoO         = $em->getRepository(Organization::class);
        $repoW         = $em->getRepository(Weight::class);

        $organization        = $repoO->find($orgId);
        $clients             = $organization->getClients();
        $organizationClients = $organization->getClients();
        $clients             = [];
        foreach ($organizationClients as $organizationClient) {
            $clients[] = $organizationClient->getClientOrganization();
        }
        
        $clientUserForm = $this->createForm(AddClientForm::class, null, ['standalone' => true, 'clients' => $clients, 'app' => $app]);
        $clientUserForm->handleRequest($request);

        $recipients = [];
        // Send user email
        $settings                         = [];
        $settings['invitingUser']         = $currentUser;
        $settings['invitingOrganization'] = $organization;
        $settings['tokens']               = [];

        //return($clientUserForm->get('clientUsers')->getData());
        //die;

        if ($clientUserForm->isValid()) {

            // Get imported users

            //$file = $userForm->getData()->getUsersCSV();

            /*
            $file = $userForm->getData()['usersCSV'];
            if ($file) {
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
            $file->move('/../Data', $fileName);
            $reader = Reader::createFromPath('/../Data/' . $fileName);
            $results = $reader->fetchAssoc();
            }*/

            // Throwing error if any external user is already in DB or organization is not set for external client which is not an individual

            foreach ($clientUserForm->get('clientUsers') as $clientUser) {
                if ($repoU->findOneBy(['email' => $clientUser->get('email')->getData(), 'orgId' => $clientUser->get('orgId')->getData()])) {
                    $clientUser->get('email')->addError(new FormError('There is already such a email address linked to this client'));
                    $errors = $this->buildErrorArray($clientUserForm);
                    return $errors;
                }

                if ($clientUser->get('type')->getData() != 'I' && $clientUser->get('orgId')->getData() == null) {
                    $clientUser->get('orgId')->addError(new FormError('You need to link this external user with its associated group or entity'));
                    $errors = $this->buildErrorArray($clientUserForm);
                    return $errors;
                }
            }

            // Inserting new users in DB

            foreach ($clientUserForm->get('clientUsers') as $clientUser) {

                $newClientUser = new User;

                $token = md5(rand());
                $newClientUser
                    ->setToken($token)
                    ->setFirstname($clientUser['firstname']->getData())
                    ->setLastname($clientUser['lastname']->getData())
                    ->setEmail($clientUser['email']->getData())
                    ->setPositionName($clientUser['positionName']->getData())
                    ->setRole(3)
                    ->setCreatedBy($currentUser->getId())
                    ->setWeightIni($clientUser['weightValue']->getData());
                /*if ($clientUserData['type'] != 'I') {
                $clientUser->setExtOrgId($clientUserData['extOrgGroup']);
                }*/

                $newExternalUser = new ExternalUser;
                $newExternalUser
                    ->setFirstname($clientUser['firstname']->getData())
                    ->setLastname($clientUser['lastname']->getData())
                    ->setEmail($clientUser['email']->getData())
                    ->setPositionName($clientUser['positionName']->getData())
                    ->setWeightValue($clientUser['weightValue']->getData())
                    ->setUser($newClientUser)
                    ->setCreatedBy($currentUser->getId())
                    ->setOrganization($organization);

                if ($clientUser['type']->getData() != 'I') {
                    $newClientUser->setOrgId($clientUser['orgId']->getData());
                    $clientOrganization = $repoO->find(intval($clientUser->get('orgId')->getData()));
                    $clientOrganization->setType($clientUser['type']->getData());
                } else {
                    $clientOrganization = new Organization;
                    $clientOrganization
                        ->setType('I')
                        ->setIsClient(false)
                        ->setCommname($clientUser['firstname']->getData() . ' ' . $clientUser['lastname']->getData())
                        ->setWeight_type('role')
                        ->setCreatedBy($currentUser->getId())
                        ->setValidated(new DateTime)
                        ->setExpired(new DateTime('2100-01-01 00:00:00'));
                    $client = new Client;
                    $client->setOrganization($organization)->setClientOrganization($clientOrganization);
                    $organization->addClient($client);
                }

                $em->persist($newExternalUser);
                $em->persist($clientOrganization);
                $em->persist($organization);
                $em->persist($newClientUser);
                $em->flush();

                if ($clientUser['type']->getData() == 'I') {
                    $clientOrganization->setMasterUserId($newClientUser->getId());
                    $newClientUser->setOrgId($clientOrganization->getId());
                    $em->persist($clientOrganization);
                    $em->persist($newClientUser);
                    $em->flush();
                }
                // Note : We add a weight to new created users, even if it is null
                /*
                $weight = new Weight;
                $weight->setValue($newExternalUser->getWeightValue())->setUsrId($newClientUser->getId());
                $em->persist($weight);
                $em->flush();

                $newClientUser->setWgtId($weight->getId());
                $em->persist($newClientUser);
                $em->flush();
                 */

                $settings['tokens'][] = $token;
                if ($newClientUser->getEmail() != null) {
                    $recipients[] = $newClientUser;
                }
            }

            if ($recipients != null) {
                self::sendMail($app, $recipients, 'externalInvitation', $settings);
            }
            return new JsonResponse(['message' => 'Success!'], 200);

        } else {
            $errors = $this->buildErrorArray($clientUserForm);
            return $errors;
        }

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
            foreach ($team->getTeamUsers() as $teamUser) {
                $teamUsrIds[] = $teamUser->getUser()->getId();
            }
            if ($currentUser->getRole() != 4 && ($organization != $currentUserOrganization || $currentUser->getRole() != 1 && !in_array($currentUser->getId(), $teamUsrIds))) {
                $hasPageAccess = false;
            }
        }

        if (!$hasPageAccess) {
            return $this->render('errors/403.html.twig');
        } else {

            
            $addElementTargetForm = $this->createForm(AddElementTargetForm::class, $element, ['standalone' => true, 'entity' => $entity, 'app' => $app, 'organization' => $organization]);
            $addElementTargetForm->handleRequest($request);

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
            }

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
     * @Route("/settings/organization/{orgId}/{entity}s", name="updateOrganizationElements")
     */
    public function updateOrganizationElementsAction(Request $request, $entity, $orgId)
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
                dd($entity);
                break;
        }
        $currentUser = $this->user;
        $organization = $repoO->findOneBy(["id" => $orgId]);
        $role                    = $currentUser->getRole();
        $organization            = $repoO->find($orgId);
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

        $manageOrganizationElementsForm = $this->createForm(ManageOrganizationElementsForm::class, $organization, ['standalone' => true, 'entity' => $entity]);
        $manageOrganizationElementsForm->handleRequest($request);

        if ($manageOrganizationElementsForm->isValid()) {
            $em->persist($organization);
            $em->flush();
            return $app->redirect($app['url_generator']->generate('firmSettings'));
        }

        return $this->render('organization_element_list.html.twig',
            [
                'entity' => $entity,
                'elements' => $elements,
                'form'     => $manageOrganizationElementsForm->createView(),
            ]);
    }

    /**
     * @param Request $request
     * @param $elmtName
     * @param $elmtId
     * @param $orgId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/organization/element/validate", name="validateOrganizationElement")
     */
    public function validateOrganizationElementAction(Request $request)
    {
        $em    = $this->em;
        $elmtId = $request->get('id');
        $entity = $request->get('e');
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

            if ($organizationElmtForm->isValid()) {
                $element->setCreatedBy($currentUser->getId());
                $em->persist($element);
                $em->flush();
                $output = ['message' => 'Success!', 'id' => $element->getId()];
                if($entity != 'weight'){
                    $output['name'] = $element->getName();
                }
                return new JsonResponse($output, 200);
            } else {
                return new JsonResponse("Duplicate Element !", 500);
                //$errors = $this->buildErrorArray($organizationElmtForm);
                //return new JsonResponse($errors, 500);
            }
        //}
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
                return $app->redirect($app['url_generator']->generate('firmSettings'));
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
                return $app->redirect($app['url_generator']->generate('manageUsers'));
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

        return $app->redirect($app['url_generator']->generate('firmSettings'));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return bool|JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/organization/convert", name="convertAccount")
     */
    public function convertAccountAction(Request $request)
    {
        $entityManager = $this->em;
        $repoO         = $entityManager->getRepository(Organization::class);
        $repoU         = $entityManager->getRepository(User::class);

        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $orgId        = $currentUser->getOrgId();
        $organization = $repoO->find($orgId);

        if ($_POST['delegation-email'] != null) {
            if (!filter_var($_POST['delegation-email'], FILTER_VALIDATE_EMAIL)) {
                return new JsonResponse(['message' => 'The email address is not correct, please have a look'], 200);
            } else {

                if (!$repoU->findOneBy(['email' => $_POST['delegation-email'], 'orgId' => $orgId])) {

                    $addedUser = new User;
                    $token     = md5(rand());
                    $addedUser
                        ->setEmail($_POST['delegation-email'])
                        ->setToken($token)
                        ->setRole(1)
                        ->setOrgId($currentUser->getOrgId());
                    $entityManager->persist($addedUser);
                    $entityManager->flush();

                    $repoON  = $entityManager->getRepository(OptionName::class);
                    $options = $repoON->findAll();

                    foreach ($options as $option) {
                        $optionValid = new OrganizationUserOption;
                        $optionValid->setOName($repoON->find($option))->setOrganization($organization);

                        $entityManager->persist($optionValid);
                        $entityManager->flush();
                    }

                    $organization->setMasterUserId($addedUser->getId());

                    $settings['tokens'][]      = $token;
                    $settings['adminFullName'] = $currentUser->getFirstname() . ' ' . $currentUser->getLastname();
                    $settings['rootCreation']  = false;
                    $recipients[]              = $addedUser;

                    self::sendMail($app, $recipients, 'registration', $settings);

                } else {
                    return new JsonResponse(['message' => 'This user has already been created in your organization'], 200);

                }

            }

        } else {
            $currentUser->setRole(1);
            $entityManager->persist($currentUser);
        }

        $organization->setIsClient(true);
        $entityManager->persist($organization);

        $entityManager->flush();
        return true;
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
     * @Route("/settings/users", name="manageUsers")
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
            $canSeeClient = ($user->getRole() == 4) || ($user->getRole() == 1) || ($client->getCreatedBy() == $user->getId());
            if ($canSeeClient) {
                switch ($client->getClientOrganization()->getType()) {
                    case 'F':
                        $clientFirms->add($client);
                        break;
                    case 'T':
                        $clientTeams->add($client);
                        break;
                    case 'I':
                        $clientIndividuals->add($client);
                        break;
                }
            }
        }

        $totalClientUsers = 0;

        foreach ($organization->getClients() as $client) {
            $totalClientUsers += count($client->getExternalUsers()) - 1;
        }

        $nbViewableInternalUsers = count($this->em->getRepository(Organization::class)->getActiveUsers($organization));

        $users           = new ArrayCollection($repoU->findBy(['organization' => $organization, 'deleted' => null], ['department' => 'ASC', 'position' => 'ASC']));
        $usersWithDpt    = $users->matching(Criteria::create()->where(Criteria::expr()->neq('department', null)));
        $usersWithoutDpt = $users->matching(Criteria::create()->where(Criteria::expr()->eq('department', null))->andWhere(Criteria::expr()->neq('lastname', 'ZZ')));

        $viewableUsersWithoutDpt = [];
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
                'usersWithDpt'                      => $usersWithDpt,
                'organization'                      => $organization,
                'viewableDepartments'               => $this->em->getRepository(Organization::class)->getUserSortedDepartments($this->user),
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
            return $app->json(['status' => 'error', 'message' => $message], 404);
        }

        $criWeight = $criterion->getWeight();
        $stage = $criterion->getStage();
        $stage->removeCriterion($criterion);

        $sumWeights = 0;

        foreach($stage->getCriteria() as $stageCriterion) {

            $newWeight = ($stageCriterion != $stage->getCriteria()->last()) ?
                round($stageCriterion->getWeight() / (1 - $criWeight), 2) :
                1 - $sumWeights;

            $stageCriterion->setWeight($newWeight);
            $sumWeights += $newWeight;

        }

        $em->persist($stage);
        $em->flush();

        return $app->json(['status' => 'done']);
    }

    /**
     * @param Request $request
     * @param $actId
     * @return RedirectResponse
     * @Route("/ajax/activity/delete/{actId}", name="ajaxActivityDelete")
     */
    public function deleteActivityAction(Request $request, $actId): RedirectResponse
    {
        $activity = $this->em->getRepository(Activity::class)->find($actId);
        $activityM = new ActivityM($this->em, $this->stack, $this->security);
        if ($activityM->isDeletable($activity)) {
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
        return $this->redirectToRoute("myActivities");
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $rctId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/recurring/delete/{rctId}", name="ajaxRecurringDelete")
     */
    public function deleteRecurringAction(Request $request, $rctId)
    {

        $em           = $this->em;
        $recurring    = $em->getRepository(Recurring::class)->find($rctId);
        $organization = $recurring->getOrganization();
        $organization->removeRecurring($recurring);
        $em->persist($organization);
        $em->flush();

        return $app->json(['status' => 'done']);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $actId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/activity/archive/{actId}", name="ajaxActivityArchive")
     */
    public function archiveActivityAction(Request $request, $actId)
    {

        $em       = $this->em;
        $activity = $em->getRepository(Activity::class)->find($actId);
        $activity->setArchived(new DateTime);
        $em->persist($activity);
        $em->flush();
        return $app->json(['status' => 'done', 'message' => 'archived', 'aid' => $actId]);
    }

    public function restoreActivityAction(Request $request, $actId)
    {
        $em       = $this->em;
        $activity = $em->getRepository(Activity::class)->find($actId);
        $activity->setArchived(null);
        $em->persist($activity);
        $em->flush();
        return $app->json(['status' => 'done', 'message' => 'archived']);
    }

    // Display all organization activities (limited to HR)

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("/activities/all", name="firmActivities")
     */
    public function getAllActivitiesAction(Request $request)
    {
        $entityManager = $this->em;
        $repoO         = $entityManager->getRepository(Organization::class);
        $repoDec       = $entityManager->getRepository(Decision::class);
        $currentUser   = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $currentUsrId         = $currentUser->getId();
        $orgId                = $currentUser->getOrgId();
        $organization         = $repoO->find($orgId);
        
        $delegateActivityForm = $this->createForm(DelegateActivityForm::class, null, ['app' => $app, 'standalone' => true]);
        $delegateActivityForm->handleRequest($request);
        $validateRequestForm = $this->createForm(DelegateActivityForm::class, null, ['app' => $app, 'standalone' => true, 'request' => true]);
        $validateRequestForm->handleRequest($request);
        $requestActivityForm = $this->createForm(RequestActivityForm::class, null, ['app' => $app, 'standalone' => true]);
        $requestActivityForm->handleRequest($request);

        $userActivities = $organization->getActivities();

        //Remove future recurring activities which are far ahead (at least two after current one
        foreach ($userActivities as $activity) {
            if ($activity->getRecurring()) {
                $recurring = $activity->getRecurring();

                if ($recurring->getOngoingFutCurrActivities()->contains($activity) && $recurring->getOngoingFutCurrActivities()->indexOf($activity) > 1) {

                    $userActivities->removeElement($activity);
                }
            }
        }

        $nbActivitiesCategories = 0;

        $cancelledActivities    = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", -5)));
        $nbActivitiesCategories = (count($cancelledActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
        $discardedActivities    = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", -4)));
        $nbActivitiesCategories = (count($discardedActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
        $requestedActivities    = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", -3)));

        $iterator = $requestedActivities->getIterator();

        $iterator->uasort(function ($a, $b) use ($repoDec, $currentUsrId) {
            return ($repoDec->findOneBy(['decider' => $currentUsrId, 'activity' => $a]) != null && $repoDec->findOneBy(['decider' => $currentUsrId, 'activity' => $b]) == null) ? 1 : -1;
            //return ($a->getId() > $b->getId()) ? -1 : 1;
        });

        $requestedActivities = new ArrayCollection(iterator_to_array($iterator));

        $nbActivitiesCategories = (count($requestedActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
        $attributedActivities   = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", -2)));
        $nbActivitiesCategories = (count($attributedActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
        $incompleteActivities   = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", -1)));
        $nbActivitiesCategories = (count($incompleteActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
        $futureActivities       = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 0)));
        $nbActivitiesCategories = (count($futureActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
        $currentActivities      = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 1)));
        $nbActivitiesCategories = (count($currentActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
        $completedActivities    = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 2)));
        $nbActivitiesCategories = (count($completedActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
        $releasedActivities     = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 3)));
        $archivedActivities     = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 4)));

        return $this->render('activity_list.html.twig',
            [
                'organization'                       => $organization,
                'user_activities'                    => $userActivities,
                'delegateForm'                       => $delegateActivityForm->createView(),
                'validateRequestForm'                => $validateRequestForm->createView(),
                'requestForm'                        => $requestActivityForm->createView(),
                'orgMode'                            => true,
                'cancelledActivities'                => $cancelledActivities,
                'discardedActivities'                => $discardedActivities,
                'requestedActivities'                => $requestedActivities,
                'attributedActivities'               => $attributedActivities,
                'incompleteActivities'               => $incompleteActivities,
                'futureActivities'                   => $futureActivities,
                'currentActivities'                  => $currentActivities,
                'completedActivities'                => $completedActivities,
                'releasedActivities'                 => $releasedActivities,
                'archivedActivities'                 => $archivedActivities,
                'nbCategories'                       => $nbActivitiesCategories,
                'app'                                => $app,
                'existingAccessAndResultsViewOption' => false,
                'hideResultsFromStageIds'            => []/*self::hideResultsFromActivities($userActivities)*/,
                'resultsAccess'                      => 2,
            ]
        );
    }

    /**
     * @param Request $request
     * @return mixed
     * @Route("/organization/settings", name="firmSettings")
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
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("/settings/templates/manage", name="manageTemplates")
     */
    public function manageTemplatesAction(Request $request)
    {
        $connectedUser = $this->user;
        if (!$connectedUser instanceof User) {
            return $this->redirectToRoute('login');
        }

        if ($connectedUser->getRole() == 3) {
            return $this->render('errors/403.html.twig');
        }

        $em           = $this->em;
        $repoTA       = $em->getRepository(TemplateActivity::class);
        $repoO        = $em->getRepository(Organization::class);
        $organization = $repoO->find($connectedUser->getOrgId());
        $templates    = $repoTA->findByOrganization($organization);

        return $this->render('template_manage.html.twig',
            [
                'templates' => $templates,
                'app'       => $app,
                'user'      => $connectedUser,
            ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $tmpId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/template/{tmpId}", name="templateDelete")
     */
    public function deleteTemplateAction(Request $request, $tmpId)
    {

        $connectedUser = $this->user;
        if (!$connectedUser instanceof User) {
            throw new Exception('unauthorized');
        }

        if ($connectedUser->getRole() == 3) {
            return $this->render('errors/403.html.twig');
        }

        $em     = $this->em;
        $repoTA = $em->getRepository(TemplateActivity::class);
        $repoO  = $em->getRepository(Organization::class);
        $repoA  = $em->getRepository(Activity::class);

        $organization = $repoO->find($connectedUser->getOrgId());

        $template = $repoTA->find($tmpId);

        // Remove link with template activities
        $templateActivities = $repoA->findByTemplate($template);

        foreach ($templateActivities as $templateActivity) {
            $templateActivity->setTemplate(null);
            $em->persist($templateActivity);
        }

        $organization->removeTemplateActivity($template);
        $em->persist($organization);
        $em->flush();
        return new JsonResponse(['message' => "Success"], 200);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $usrId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateUserActionAJAX(Request $request, $usrId)
    {
        $em            = $this->em;
        $repoO         = $em->getRepository(Organization::class);
        $repoOC        = $em->getRepository(Client::class);
        $searchedUser  = $em->getRepository(User::class)->find($usrId);
        $connectedUser = $this->user;
        if (!$connectedUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        

        $searchedUserOrganization  = $repoO->find($searchedUser->getOrgId());
        $orgOptions                = $searchedUserOrganization->getOptions();
        $enabledCreatingUserOption = false;
        foreach ($orgOptions as $orgOption) {
            if ($orgOption->getOName()->getName() == 'enabledUserCreatingUser') {
                $enabledCreatingUserOption = $orgOption->isOptionTrue();
            }
        }

        $departments = ($searchedUser->getOrgId() == $connectedUser->getOrgId() || $connectedUser->getRole() == 4) ? $searchedUserOrganization->getDepartments() : null;

        // Look through organization clients if user belongs to org clients
        if ($searchedUser->getOrgId() != $connectedUser->getOrgId()) {

            $connectedUserOrganization = $repoO->find($connectedUser->getOrgId());
            $connectedUserOrgClients   = $repoOC->findByOrganization($connectedUserOrganization);
            $connectedUserClients      = [];
            foreach ($connectedUserOrgClients as $connectedUserOrgClient) {
                $connectedUserClients[] = $connectedUserOrgClient->getClientOrganization();
            }

            if (!in_array($searchedUserOrganization, $connectedUserClients) && $connectedUser->getRole() != 4) {
                return $this->render('errors/403.html.twig');
            }

            $userForm = (!in_array($searchedUserOrganization, $connectedUserClients)) ?
                $this->createForm(UserType::class, $searchedUser, ['standalone' => true, 'organization' => $searchedUserOrganization]) :
                $this->createForm(ClientUserType::class, $searchedUser, ['standalone' => true, 'clients' => $connectedUserOrgClients]);

        } else {
            if ($connectedUser->getRole() == 2 || $connectedUser->getRole() == 3) {
                return $this->render('errors/403.html.twig');
            }

            $userForm = $this->createForm(UserType::class, $searchedUser, ['standalone' => true, 'organization' => $searchedUserOrganization, 'enabledCreatingUser' => $enabledCreatingUserOption]);
        }

        $userForm->handleRequest($request);

        if ($userForm->isValid()) {

            if ($searchedUser->getOrgId() == $connectedUser->getOrgId() || !in_array($searchedUserOrganization, $connectedUserClients)) {

                $repoW = $em->getRepository(Weight::class);

                $dptId = $userForm->get('dptId')->getData();
                $posId   = $userForm->get('posId')->getData();
                $wgtId   = $userForm->get('wgtId')->getData();
                $superiorUser   = $userForm->get('superiorUser')->getData();

                $searchedUser->setDptId($dptId ? $dptId->getId() : null)
                    ->setPosId($posId ? $posId->getId() : null)
                    ->setWgtId($wgtId ? $wgtId->getId() : null)
                    ->setSuperiorUser($superiorUser);

                $searchedUser
                    ->setFirstname($userForm->get('firstname')->getData())
                    ->setLastname($userForm->get('lastname')->getData())
                    ->setRole($userForm->get('role')->getData());

                if ($enabledCreatingUserOption) {
                    $searchedUser
                        ->setEnabledCreatingUser($userForm->get('enabledCreatingUser')->getData());
                }

                if ($searchedUser->getEmail() != $userForm->get('email')->getData()) {
                    $repicients        = [];
                    $recipients[]      = $searchedUser;
                    $token             = md5(rand());
                    $settings['token'] = $token;
                    $searchedUser->setPassword(null)->setToken($token)->setEmail($userForm->get('email')->getData());
                    self::sendMail($app, $recipients, 'emailChangeNotif', $settings);
                }

                $existingWeight = $repoW->find($userForm->get('wgtId')->getData());
                $searchedUser->setWeightIni($existingWeight->getValue());

                $em->persist($searchedUser);
                $em->flush();

            } else {

                $externalUser = $searchedUser->getExternalUser($app);

                if ($externalUser->getEmail() != $userForm->get('email')->getData()) {
                    $repicients        = [];
                    $recipients[]      = $searchedUser;
                    $token             = md5(rand());
                    $settings['token'] = $token;
                    $searchedUser->setPassword(null)->setToken($token)->setEmail($userForm->get('email')->getData());
                    self::sendMail($app, $recipients, 'emailChangeNotif', $settings);
                }

                $externalUser
                    ->setFirstname($userForm->get('firstname')->getData())
                    ->setLastname($userForm->get('lastname')->getData())
                    ->setPositionName($userForm->get('positionName')->getData())
                    ->setWeightValue($userForm->get('weightValue')->getData());

                if ($userForm->get('type')->getData() != 'I') {
                    $searchedUser->setOrgId($userForm->get('orgId')->getData());
                    $clientOrganization = $repoO->find(intval($userForm->get('orgId')->getData()));
                    $clientOrganization->setType($userForm->get('type')->getData());
                } else {
                    $clientOrganization = new Organization;
                    $clientOrganization->setType('I')->setIsClient(false)->setCommname($userForm->get('firstname')->getData() . ' ' . $userForm->get('lastname')->getData())->setWeight_type('role');
                    $client = new Client;
                    $client->setOrganization($connectedUserOrganization)->setClientOrganization($clientOrganization);
                    $connectedUserOrganization->addClient($client);
                }

                $em->persist($externalUser);
                $em->persist($clientOrganization);
                $em->persist($connectedUserOrganization);
                $em->flush();

            }

            /*
            $usrPosition = $repoP->find($userForm->get('position')->getData());

            // Weight mgt (new weight is created only if weight associated with selected value doesn't belong to any user)
            $existingUsrWeight = $repoW->findOneBy(['position' => $usrPosition, 'usrId' => $usrId]);

            $submittedWeight = $repoW->find($userForm->get('weightIni')->getData());

            if ($existingUsrWeight->getPosition() != $usrPosition || $existingUsrWeight->getValue() != $submittedWeight->getValue()) {
            $existingUsrWeight->setUsrId(null);
            $em->persist($existingUsrWeight);

            $existingNewSubmittedNullWeight = $repoW->findOneBy(['value' => $submittedWeight->getValue(), 'position' => $usrPosition, 'usrId' => null]);
            if (!$existingNewSubmittedNullWeight) {
            $newWeight = new Weight;
            $newWeight->setUsrId($user->getId())->setValue($submittedWeight)->setInterval(0)->setTimeframe('D');
            $usrPosition->addWeight();
            $em->persist($newWeight);
            } if ($existingNewSubmittedNullWeight) {
            $existingNewSubmittedNullWeight->setUsrId($usrId);
            $em->persist($existingNewSubmittedNullWeight);
            }
            }

            $em->flush();
             */
            //$organization = $repoO->find($orgId);
            //$submittedDptName = $userForm->get('department')->getData();
            //$submittedPosName = $userForm->get('position')->getData();

            return $app->redirect($app['url_generator']->generate('manageUsers'));
            //return new JsonResponse(['message' => 'Success!'], 200);

        } else {
            $errors = $this->buildErrorArray($userForm);
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

    /**
     * @param Application $app
     * @param $entity
     * @param $elmtId
     * @param bool $orgEnabledCreatingUser
     * @return mixed
     * @Route("/settings/{entity}/{elmtId}/overview", name="elementoverview")
     */
    public function elementOverviewAction(
        Application $app, $entity, $elmtId, $orgEnabledCreatingUser = false
    ) {
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
        $currentUserOrganization = $currentUser->getOrganization();

        if ($entity == 'user') {
            $repoU                     = $em->getRepository(User::class);
            $repoR                     = $em->getRepository(Result::class);
            $user                      = $repoU->find($elmtId);
            $organization              = $repoO->find($user->getOrgId());
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
        if ($entity == 'user') {
            if (($currentUser->getRole() != 4 && $currentUser->getRole() != 1 && !($user->getDepartment($app)->getMasterUser() == $currentUser) && ($currentUser->getOrgId() != $organization->getId()) || ($orgEnabledCreatingUser && $currentUser->isEnabledCreatingUser()))) {
                return $this->render('errors/403.html.twig');
            }
        } else {
            foreach ($team->getTeamUsers() as $teamUser) {
                $teamUsrIds[] = $teamUser->getUser($app)->getId();
            }
            if ($currentUser->getRole() != 4 && ($organization != $currentUserOrganization || $currentUser->getRole() != 1 && !in_array($currentUser->getId(), $teamUsrIds))) {
                $hasPageAccess = false;
            }
        }

        if (!$hasPageAccess) {
            return $this->render('errors/403.html.twig');
        } else {

            $currentUser = $this->user;
            if (!$currentUser instanceof User) {
                return $this->redirectToRoute('login');
            }
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

            if ($currentUser->getRole() == 3 && $currentUser->getDepartment($app)->getMasterUser() != $currentUser) {

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
                    'elmtType'                  => $entity,
                    'username'                  => ($entity == 'user') ? $user->getFirstname() . ' ' . $user->getLastname() : $team->getName(),
                    'memberSince'               => ($entity == 'user') ? $user->getInserted() : $team->getInserted(),
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
                ]);
        }

    }

    /**
     * @param string $entity
     * @param int $participationType
     * @param int $cName
     * @return JsonResponse
     * @throws Exception
     * @Route("/settings/{entity}/graph/{participationType}/{cName}", name="getElementResPerCrtGraph")
     */
    public function getElementResPerCrtGraphAction(string $entity, int $participationType, int $cName)
    {
        $currentUser = $this->user;
        if (!$currentUser) {
            throw new \Exception;
        }

        $organization = $currentUser->getOrganization();
        $em           = $this->em;

        // data fields for graph/chart
        $graphData             = [];
        $graphData['names']    = [];
        $graphData['averages'] = [];
        $graphData['colors']   = [];

        $rank = 0;

        /** @var OrganizationRepository */
        $orgRepo           = $em->getRepository(Organization::class);
        $criterionNameRepo = $em->getRepository(CriterionName::class);
        $results           = $orgRepo->findOrgUserPerformancePerCriterion(
            $organization, $criterionNameRepo->find($cName)
        );
        $resultsCount = count($results);
        foreach ($results as $result) {
            $name  = $result->user()->getFullname();
            $perf  = $result->performance();
            $count = $result->count();

            $graphData['names'][]    = "$name ($count)";
            $graphData['averages'][] = $perf;

            $graphData['colors'][] = (function ($nbCrt, $m) {
                if ($nbCrt == 1) {
                    $redLevel = 32;
                } else {
                    $redLevel = (int) (($nbCrt & 1)
                        ? min(154, 32 + 2 * ($m - 1) * (154 - 32) / ($nbCrt - 1))
                        : min(154, 32 + 2 * $m * (154 - 32) / (2 + $nbCrt)));
                }

                if ($nbCrt == 1) {
                    $greenLevel = 154;
                } else {
                    $greenLevel = (int) (($nbCrt & 1)
                        ? min(154, 32 + 2 * ($nbCrt - $m) * (154 - 32) / ($nbCrt - 1))
                        : min(154, 32 + 2 * ($nbCrt - $m) * (154 - 32) / ($nbCrt + 2)));
                }

                return "rgb($redLevel, $greenLevel, 32)";
            })($resultsCount, ++$rank);
        }

        $graphData['count']        = $resultsCount;
        $graphData['grandAverage'] = number_format(array_sum($graphData['averages']) / ($resultsCount ?: 1), 1);

        return new JsonResponse(['elementGraphData' => $graphData], 200);
    }

    /**
     * @param Application $app
     * @param $entity
     * @param $actElmt
     * @param $mode
     * @param $elmtId
     * @param $participationType
     * @param $cName
     * @return JsonResponse
     * @throws Exception
     * @Route("/settings/{entity}/graph/{mode}/{actElmt}/{elmtId}/{participationType}/{cName}", name="getElementGraph")
     */
    public function getElementGraphAction(
        Application $app, $entity, $actElmt, $mode, $elmtId, $participationType, $cName
    ) {
        $em          = $this->em;
        $repoP      = $em->getRepository(Participation::class);
        $repoG       = $em->getRepository(Grade::class);
        $repoU       = $em->getRepository(User::class);
        $repoO       = $em->getRepository(Organization::class);
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $organization = $currentUser->getOrganization();

        if ($entity == 'user') {
            $repoE           = $em->getRepository(User::class);
            $repoR           = $mode == 1 ? $em->getRepository(Result::class) : $em->getRepository(ResultProject::class);
            $elementProperty = 'usrId';
            $elementValue    = $elmtId;
        } else {
            $repoE           = $em->getRepository(Team::class);
            $repoR           = $mode == 1 ? $em->getRepository(ResultTeam::class) : $em->getRepository(ResultProject::class);
            $elementId       = 'teaId';
            $elementProperty = 'team';
            $elementValue    = $repoE->find($elmtId);
        }

        $nbGradedActivities       = 0;
        $nbGradedStages           = 0;
        $nbGradedCriteria         = 0;
        $nbGradedActivitiesStages = 0;
        $genResults               = [];
        $actElementResults        = new ArrayCollection;
        $criterionResults         = new ArrayCollection;
        $stageResults             = new ArrayCollection;
        $activityResults          = new ArrayCollection;
        $projectCriteria = [];

        /*  if ($currentUser->getRole() == 3) {

        switch ($participationType) {
        case -1:
        $allElementResults = new ArrayCollection($repoR->findBy([ $elementProperty => $elementValue ], ['activity' => 'ASC', 'stage' => 'ASC', 'criterion' => 'ASC']));
        break;
        case 0:
        $allElementResults = new ArrayCollection($repoR->findBy([$elementProperty => $elementValue, 'weightedRelativeResult' => null], ['activity' => 'ASC', 'stage' => 'ASC', 'criterion' => 'ASC']));
        break;
        case 1:
        $allElementResults = new ArrayCollection($repoR->findBy([$elementProperty => $elementValue, 'weightedDistanceRatio' => null], ['activity' => 'ASC', 'stage' => 'ASC', 'criterion' => 'ASC']));
        break;
        }
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
        $elementResults = [];
        foreach ($allElementResults as $allElementResult) {
        $elementResults[] = $allElementResult;
        }
        } else {*/

        if ($mode == 1) {
            $queryArray = $participationType == 0 ?
                [$elementProperty => $elementValue, 'weightedRelativeResult' => null] :
                [$elementProperty => $elementValue];
        } else {
            $repoS = $em->getRepository(Stage::class);

            $projectStages = $repoS->findBy(['activity' => $organization->getActivities()->getValues(), 'mode' => [0, 2]]);

            foreach ($projectStages as $projectStage) {
                foreach ($projectStage->getCriteria() as $criterion) {
                    $projectCriteria[] = $criterion;
                }
            }

            $elementProjectParticipations = $repoP->findBy([$elementProperty => $elementValue, 'stage' => $projectStages]);

            foreach ($elementProjectParticipations as $elementProjectParticipation) {
                $elementProjectActivities[] = $elementProjectParticipation->getActivity();
            }

            $queryArray = ['activity' => $elementProjectActivities];

        }

        $elementResults = new ArrayCollection($repoR->findBy($queryArray, ['activity' => 'ASC']));
        if ($participationType == 1) {
            $elementResults = $elementResults->matching(
                Criteria::create()->where(Criteria::expr()->neq("weightedRelativeResult", null))
            );
        }

        $theGeneralResults = ($mode == 1) ?
            new ArrayCollection($repoR->findBy([$elementProperty => null], ['activity' => 'ASC'])) :
            new ArrayCollection($repoR->findBy(['activity' => $elementProjectActivities], ['activity' => 'ASC']));

        $elementResults =
            ($actElmt == 'activity') ?

                $elementResults->matching(
                    Criteria::create()->where(Criteria::expr()->eq("stage", null))
                        ->andWhere(Criteria::expr()->eq("criterion", null))) :

                (($actElmt == 'stage') ?

                    $elementResults->matching(
                        Criteria::create()->where(Criteria::expr()->neq("stage", null))
                            ->andWhere(Criteria::expr()->eq("criterion", null))) :

                    $elementResults->matching(
                        Criteria::create()->where(Criteria::expr()->neq("stage", null))
                            ->andWhere(Criteria::expr()->neq("criterion", null))));

        if ($currentUser->getRole() == 3 && $currentUser->getDepartment($app)->getMasterUser() != $currentUser) {

            switch ($participationType) {
                case -1:
                    $participationTypes = [-1, 0, 1];
                    break;
                case 0:
                    $participationTypes = [0];
                    break;
                case 1:
                    $participationTypes = [-1, 1];
                    break;
            }
            $elementParticipations = $repoP->findBy([$elementProperty => $elementValue, 'status' => [3, 4], 'type' => $participationTypes], ['activity' => 'ASC']);
            $concernedStage        = null;
            $concernedActivity     = null;
            foreach ($elementParticipations as $elementParticipation) {

                switch ($actElmt) {
                    case 'stage':
                    case 'criterion':
                        $participationStage = $elementParticipation->getStage();
                        if ($participationStage != $concernedStage && $participationStage->getStatus() < 3) {
                            $removableResultStages[] = $participationStage;
                            $concernedStage          = $participationStage;
                        }
                        break;
                    case 'activity':
                        $participationActivity = $elementParticipation->getActivity();
                        if ($participationActivity != $concernedActivity && $participationActivity->getStatus() < 3) {
                            $removableResultActivities[] = $participationActivity;
                            $concernedActivity           = $participationActivity;
                        }
                        break;
                }

            }

            foreach ($elementResults as $elementResult) {
                switch ($actElmt) {
                    case 'stage':
                    case 'criterion':
                        if (array_search($elementResult->getStage(), $removableResultStages) !== false) {
                            $elementResults->removeElement($elementResult);
                        }
                        break;
                    case 'activity':
                        if (array_search($elementResult->getActivity(), $removableResultActivities) !== false) {
                            $elementResults->removeElement($elementResult);
                        }
                        break;

                }
            }
        }

        $theGeneralResults =
            ($actElmt == 'activity') ?

                $theGeneralResults->matching(
                    Criteria::create()->where(Criteria::expr()->eq("stage", null))
                        ->andWhere(Criteria::expr()->eq("criterion", null))) :

                (($actElmt == 'stage') ?

                    $theGeneralResults->matching(
                        Criteria::create()->where(Criteria::expr()->neq("stage", null))
                            ->andWhere(Criteria::expr()->eq("criterion", null))) :

                    $theGeneralResults->matching(
                        Criteria::create()->where(Criteria::expr()->neq("stage", null))
                            ->andWhere(Criteria::expr()->neq("criterion", null))));

        //}

        $generalResults = new ArrayCollection;

        // We still have to work a bit to remove unnecessary general results elements
        switch ($actElmt) {
            case 'activity':
                $resultsActElmts = [];
                foreach ($elementResults as $elementResult) {
                    $resultsActElmts[] = $elementResult->getActivity();
                }
                foreach ($theGeneralResults as $theGeneralResult) {
                    if (array_search($theGeneralResult->getActivity(), $resultsActElmts) !== false) {
                        $generalResults->add($theGeneralResult);
                    }
                }
                break;
            case 'stage':
                $resultsActElmts = [];
                foreach ($elementResults as $elementResult) {
                    $resultsActElmts[] = $elementResult->getStage();
                }
                foreach ($theGeneralResults as $theGeneralResult) {
                    if (array_search($theGeneralResult->getStage(), $resultsActElmts) !== false) {
                        $generalResults->add($theGeneralResult);
                    }
                }
                break;
            case 'criterion':
                $resultsActElmts = [];
                foreach ($elementResults as $elementResult) {
                    $resultsActElmts[] = $elementResult->getCriterion();
                }
                foreach ($theGeneralResults as $theGeneralResult) {
                    if (array_search($theGeneralResult->getCriterion(), $resultsActElmts) !== false) {
                        $generalResults->add($theGeneralResult);
                    }
                }
                break;
        }

        if ($actElmt == 'criterion' && $cName != 0) {
            $theElementResults = clone $elementResults;
            $theGeneralResults = clone $generalResults;
            $elementResults    = new ArrayCollection;
            foreach ($theElementResults as $theElementResult) {
                if ($theElementResult->getCriterion()->getCName()->getId() == $cName) {
                    $elementResults->add($theElementResult);
                }
            }
            $generalResults = new ArrayCollection;
            foreach ($theGeneralResults as $theGeneralResult) {
                if ($theGeneralResult->getCriterion()->getCName()->getId() == $cName) {
                    $generalResults->add($theGeneralResult);
                }
            }
        }

        $iterator = $elementResults->getIterator();
        switch ($actElmt) {
            case 'activity':
                $iterator->uasort(function ($first, $second) {
                    return ($first->getActivity()->getEnddate() >= $second->getActivity()->getEnddate()) ? 1 : -1;
                });
                break;
            case 'stage':
                $iterator->uasort(function ($first, $second) {
                    if ($first->getStage()->getEnddate() > $second->getStage()->getEnddate()) {
                        return 1;
                    } else if ($first->getStage()->getEnddate() < $second->getStage()->getEnddate()) {
                        return -1;
                    } else {
                        if ($first->getId() > $second->getId()) {
                            return 1;
                        } else {
                            return -1;
                        }
                    }
                });
                break;
            case 'criterion':
                $iterator->uasort(function ($first, $second) {
                    if ($first->getStage()->getEnddate() > $second->getStage()->getEnddate()) {
                        return 1;
                    } else if ($first->getStage()->getEnddate() < $second->getStage()->getEnddate()) {
                        return -1;
                    } else {
                        if ($first->getId() > $second->getId()) {
                            return 1;
                        } else {
                            return -1;
                        }
                    }
                });
                break;
        }

        $theElementResults = new ArrayCollection(iterator_to_array($iterator));
        $elementResults    = new ArrayCollection;
        foreach ($theElementResults as $theElementResult) {
            $elementResults->add($theElementResult);
        }

        $iterator = $generalResults->getIterator();
        switch ($actElmt) {
            case 'activity':
                $iterator->uasort(function ($first, $second) {
                    return ($first->getActivity()->getEnddate() >= $second->getActivity()->getEnddate()) ? 1 : -1;
                });
                break;
            case 'stage':
                $iterator->uasort(function ($first, $second) {
                    if ($first->getStage()->getEnddate() > $second->getStage()->getEnddate()) {
                        return 1;
                    } else if ($first->getStage()->getEnddate() < $second->getStage()->getEnddate()) {
                        return -1;
                    } else {
                        if ($first->getId() > $second->getId()) {
                            return 1;
                        } else {
                            return -1;
                        }
                    }
                });
                break;
            case 'criterion':
                $iterator->uasort(function ($first, $second) {
                    if ($first->getStage()->getEnddate() > $second->getStage()->getEnddate()) {
                        return 1;
                    } else if ($first->getStage()->getEnddate() < $second->getStage()->getEnddate()) {
                        return -1;
                    } else {
                        if ($first->getId() > $second->getId()) {
                            return 1;
                        } else {
                            return -1;
                        }
                    }
                });
                break;
        }

        $theGeneralResults = new ArrayCollection(iterator_to_array($iterator));
        $generalResults    = new ArrayCollection;
        foreach ($theGeneralResults as $theGeneralResult) {
            $generalResults->add($theGeneralResult);
        }

        if ($participationType != 0) {
            $hasResults = true;
            $hasStdDev  = false;
            foreach ($elementResults as $elementResult) {
                if (!$hasStdDev && $elementResult->getWeightedDevRatio() !== null) {
                    $hasStdDev = true;
                }
                if ($hasStdDev) {
                    break;
                }
            }
        } else {
            $hasResults = false;
            $hasStdDev  = true;
        }

        $elementGraphData = [
            'labels'   => [],
            'datasets' => [
                'performance'         => [],
                'meanPerformance'     => [],
                'gradingDistance'     => [],
                'meanGradingDistance' => [],
            ],
        ];

        //return[count($elementResults),count($generalResults)];
        if (count($elementResults) > 0) {
            $graphTitle         = ($actElmt == 'activity') ? 'Activities' : (($actElmt == 'stage') ? 'Stages' : 'Criteria');
            $elementGraphData   = [];
            $elementTableData   = [];
            $elementGraphData[] = ($hasResults && $hasStdDev) ?
                [$graphTitle, 'Performance', 'Mean performance', 'Grading distance', 'Mean grading distance'] :
                (
                (!$hasResults && $hasStdDev) ?
                    [$graphTitle, 'Grading distance', 'Mean grading distance'] :
                    [$graphTitle, 'Performance', 'Mean performance']
                );

            foreach ($elementResults as $key => $elementResult) {
                if ($actElmt === 'criterion') {
                    $activityName = $elementResult->getStage()->getActivity()->getName();
                    $stageName    = $elementResult->getStage()->getName();

                    if ($cName == 0) {
                        $criterionName = $elementResult->getCriterion()->getCName()->getName();
                        $actElmtName   = "$criterionName ($stageName)";
                    } else {
                        $actElmtName = $stageName === $activityName ? $stageName : "$stageName ($activityName)";
                    }

                    $actElmtDate = $elementResult->getStage()->getEnddate();
                } else if ($actElmt === 'stage') {
                    $activityName = $elementResult->getStage()->getActivity()->getName();
                    $stageName    = $elementResult->getStage()->getName();
                    $actElmtName  = $stageName === $activityName ? $stageName : "$stageName ($activityName)";
                    $actElmtDate  = $elementResult->getStage()->getEnddate();
                } else if ($actElmt === 'activity') {
                    $actElmtName = $elementResult->getActivity()->getName();
                    $actElmtDate = $elementResult->getActivity()->getEnddate();
                }

                $elementGraphData['labels'][] = $actElmtName;

                $weightedRelativeResult     = null;
                $weightedRelativeMeanResult = null;
                $weightedDevRatio           = null;
                $weightedDevMeanRatio       = null;

                if ($hasResults) {
                    $weightedRelativeResult = $elementResult->getWeightedRelativeResult();

                    if ($weightedRelativeResult) {
                        $weightedRelativeMeanResult = $generalResults->get($key)->getWeightedRelativeResult();
                    }
                }

                if ($hasStdDev) {
                    $weightedDevRatio = $elementResult->getWeightedDevRatio();

                    if ($weightedDevRatio) {
                        $weightedDevMeanRatio = $generalResults->get($key)->getWeightedDistanceRatio();
                    }
                }

                $elementGraphData['datasets']['performance'][] =
                    $weightedRelativeResult ? round($weightedRelativeResult * 100, 1) : null;
                $elementGraphData['datasets']['meanPerformance'][] =
                    $weightedRelativeMeanResult ? round($weightedRelativeMeanResult * 100, 1) : null;
                $elementGraphData['datasets']['gradingDistance'][] =
                    $weightedDevRatio ? round($weightedDevRatio * 100, 1) : null;
                $elementGraphData['datasets']['meanGradingDistance'][] =
                    $weightedDevMeanRatio ? round($weightedDevMeanRatio * 100, 1) : null;

                // $elementGraphDataSet = [];
                $elementTableDataSet   = [];
                $elementTableDataSet[] = $key + 1;

                switch ($actElmt) {
                    case 'criterion':
                        if ($cName == 0) {
                            $stageName    = $elementResult->getStage()->getName();
                            $activityName = $elementResult->getStage()->getActivity()->getName();
                            $actElmtName  = $elementResult->getCriterion()->getCName()->getName() . ' (' . $stageName . ')';
                        } else {
                            $stageName    = $elementResult->getStage()->getName();
                            $activityName = $elementResult->getStage()->getActivity()->getName();
                            $actElmtName  = ($stageName == $activityName) ? $stageName : $stageName . ' (' . $activityName . ')';
                        }
                        $elementTableDataSet[] = $elementResult->getCriterion()->getCName()->getName();
                        $actElmtDate           = $elementResult->getStage()->getEnddate();
                        break;

                    case 'stage':
                        $stageName    = $elementResult->getStage()->getName();
                        $activityName = $elementResult->getStage()->getActivity()->getName();
                        $actElmtName  = ($stageName == $activityName) ? $stageName : $stageName . ' (' . $activityName . ')';
                        $actElmtDate  = $elementResult->getStage()->getEnddate();
                        break;

                    case 'activity':
                        $actElmtName = $elementResult->getActivity()->getName();
                        $actElmtDate = $elementResult->getActivity()->getEnddate();
                        break;
                }

                // $elementGraphDataSet[] = $actElmtName;
                if ($actElmt == 'criterion' && $cName == 0) {
                    $elementTableDataSet[] = ($stageName == $activityName) ? $stageName : "$stageName ($activityName)";
                } else {
                    $elementTableDataSet[] = $actElmtName;
                }
                $elementTableDataSet[] = $actElmtDate;

                if ($hasResults) {
                    // We do not push general perf result, if user/team has not own perf
                    $weightedRR = $elementResult->getWeightedRelativeResult();
                    // $elementGraphDataSet[] = $weightedRR;
                    $elementTableDataSet[] = $weightedRR;
                    // $elementGraphDataSet[] = $weightedRR ? $generalResults->get($key)->getWeightedRelativeResult() : null;
                    $elementTableDataSet[] = $weightedRR ? $generalResults->get($key)->getWeightedRelativeResult() : null;
                } else {
                    $elementTableDataSet[] = null;
                    $elementTableDataSet[] = null;
                }
                if ($hasStdDev) {
                    $weightedDevRatio = $elementResult->getWeightedDevRatio();
                    // $elementGraphDataSet[] = $weightedDevRatio;
                    $elementTableDataSet[] = $weightedDevRatio;
                    // $elementGraphDataSet[] = ($weightedDevRatio !== null) ? $generalResults->get($key)->getWeightedDistanceRatio() : null;
                    $elementTableDataSet[] = ($weightedDevRatio !== null) ? $generalResults->get($key)->getWeightedDistanceRatio() : null;
                } else {
                    $elementTableDataSet[] = null;
                    $elementTableDataSet[] = null;
                }

                // $elementGraphData[] = $elementGraphDataSet;
                $elementTableData[] = $elementTableDataSet;
            }
        } else {
            $elementGraphData = null;
            $elementTableData = null;
        }

        return new JsonResponse([
            'elementGraphData' => $elementGraphData,
            'elementTableData' => $elementTableData,
        ], 200);
    }

    public function oldGetElementGraphAction(Request $request, $entity, $actElmt, $participationType, $elmtId, $cName)
    {

        $em     = $this->em;
        $repoP = $em->getRepository(Participation::class);
        $repoG  = $em->getRepository(Grade::class);
        $repoU  = $em->getRepository(User::class);

        if ($entity == 'user') {
            $repoE           = $em->getRepository(User::class);
            $repoR           = $em->getRepository(Result::class);
            $elementProperty = 'usrId';
            $elementValue    = $elmtId;
        } else {
            $repoE           = $em->getRepository(Team::class);
            $repoR           = $em->getRepository(ResultTeam::class);
            $elementId       = 'teaId';
            $elementProperty = 'team';
            $elementValue    = $repoE->find($elmtId);
        }

        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $hasContribActivities = false;
        switch ($participationType) {
            case -1:
                $participationTypes = [-1, 0, 1];
                break;
            case 0:
                $participationTypes = [0];
                break;
            case 1:
                $participationTypes = [-1, 1];
                break;
        }
        $elementParticipations    = $repoP->findBy([$elementProperty => $elementValue, 'status' => [3, 4], 'type' => $participationTypes], ['inserted' => 'ASC']);
        $nbGradedActivities       = 0;
        $nbGradedStages           = 0;
        $nbGradedCriteria         = 0;
        $nbGradedActivitiesStages = 0;
        $genResults               = [];
        $actElementResults        = new ArrayCollection;
        $criterionResults         = new ArrayCollection;
        $stageResults             = new ArrayCollection;
        $activityResults          = new ArrayCollection;

        if ($currentUser->getRole() == 3) {

            switch ($participationType) {
                case -1:
                    $allElementResults = new ArrayCollection($repoR->findBy([$elementProperty => $elementValue], ['activity' => 'ASC', 'stage' => 'ASC', 'criterion' => 'ASC']));
                    break;
                case 0:
                    $allElementResults = new ArrayCollection($repoR->findBy([$elementProperty => $elementValue, 'weightedRelativeResult' => null], ['activity' => 'ASC', 'stage' => 'ASC', 'criterion' => 'ASC']));
                    break;
                case 1:
                    $allElementResults = new ArrayCollection($repoR->findBy([$elementProperty => $elementValue, 'weightedDistanceRatio' => null], ['activity' => 'ASC', 'stage' => 'ASC', 'criterion' => 'ASC']));
                    break;
            }
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
        } else {
            $elementResults = new ArrayCollection($repoR->findBy([$elementProperty => $elementValue], ['activity' => 'ASC']));
        }

        foreach ($elementResults as $elementResult) {

            if ($cName != 0) {
                if ($elementResult->getCriterion() != null && $elementResult->getCriterion()->getCName()->getId() == $cName) {
                    $criterionResults->add($elementResult);
                }
            } else {
                if ($elementResult->getCriterion() != null) {
                    $criterionResults->add($elementResult);
                }
            }
        }

        $iterator = $criterionResults->getIterator();

        $iterator->uasort(function ($first, $second) {
            return ($first->getStage()->getEnddate() >= $second->getStage()->getEnddate()) ? 1 : -1;
        });

        $criterionResults = new ArrayCollection(iterator_to_array($iterator));

        $hasResults = false;
        $hasStdDev  = false;
        foreach ($criterionResults as $criterionResult) {
            if (!$hasResults && $criterionResult->getWeightedRelativeResult() !== null) {
                $hasResults = true;
            }
            if (!$hasStdDev && $criterionResult->getWeightedDevRatio() !== null) {
                $hasStdDev = true;
            }
            if ($hasResults && $hasStdDev) {
                break;
            }
        }

        $specificCriterionGraphData   = [];
        $specificCriterionGraphData[] = ($hasResults && $hasStdDev) ?
            ['Criteria', 'Performance', 'Mean performance', 'Grading distance', 'Mean grading distance'] :
            (
            (!$hasResults && $hasStdDev) ?
                ['Criteria', 'Grading distance', 'Mean grading distance'] :
                ['Criteria', 'Performance', 'Mean performance']
            );

        foreach ($criterionResults as $criterionResult) {

            if ($criterionResults) {
                $specificCriterionGraphDataSet = [];
            }

            $specificCriterionGraphDataSet[] = ($criterionResult->getCriterion()->getStage()->getName() == $criterionResult->getCriterion()->getStage()->getActivity()->getName()) ?
                $criterionResult->getCriterion()->getStage()->getName() :
                $criterionResult->getCriterion()->getStage()->getName() . ' (' . $criterionResult->getCriterion()->getStage()->getActivity()->getName() . ')';
            if ($hasResults) {
                // We do not push general perf result, if user/team has not own perf
                $weightedRR                      = $criterionResult->getWeightedRelativeResult();
                $specificCriterionGraphDataSet[] = $criterionResult->getWeightedRelativeResult();
                $specificCriterionGraphDataSet[] = $weightedRR ? $criterionResult->getCriterion()->getGlobalRelWeightedResult() : null;
            }
            if ($hasStdDev) {
                $specificCriterionGraphDataSet[] = $criterionResult->getWeightedDevRatio();
                $specificCriterionGraphDataSet[] = $criterionResult->getCriterion()->getWeightedDistanceRatio();
            }
            $specificCriterionGraphData[] = $specificCriterionGraphDataSet;
        }

        return new JsonResponse(['userGraphData' => $specificCriterionGraphData], 200);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $entity
     * @param $elmtId
     * @param $type
     * @param $cName
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/report/save/{entity}_report/{elmtId}/{type}/{cName}", name="saveImageElementReport")
     */
    public function saveImageElementReportAction(Request $request, $entity, $elmtId, $type, $cName)
    {
        $em            = $this->em;
        $repoI         = $em->getRepository(GeneratedImage::class);
        $criterionName = null;
        if ($cName != 0) {
            $repoCN        = $em->getRepository(CriterionName::class);
            $criterionName = $repoCN->find($cName);
        }

        $elementProperty = ($entity == 'user') ? 'usrId' : 'teaId';

        $repoI          = $em->getRepository(GeneratedImage::class);
        $generatedImage = $repoI->findOneBy([$elementProperty => $elmtId, 'type' => $type, 'cName' => $criterionName]) ?: new GeneratedImage;
        $generatedImage->setType($type)->setValue($_POST['URI_value'])->setCName($criterionName);
        ($entity == 'user') ? $generatedImage->setUsrId($elmtId) : $generatedImage->setTeaId($elmtId);
        $em->persist($generatedImage);
        $em->flush();
        return true;
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $entity
     * @param $elmtId
     * @return mixed
     * @throws Exception
     * @Route("/settings/report/{entity}/generate/{elmtId}", name="generateElementReport")
     */
    public function generateElementReportAction(Request $request, $entity, $elmtId)
    {

        $reportSingleElement = (isset($_POST['settings_graphs']) && !isset($_POST['settings_comments']) && !isset($_POST['settings_results_tables'])) ?
            '_graphs' : (((!isset($_POST['settings_graphs']) && isset($_POST['settings_comments']) && !isset($_POST['settings_results_tables'])) ?
                '_feedbacks' : ((!isset($_POST['settings_graphs']) && !isset($_POST['settings_comments']) && isset($_POST['settings_results_tables'])) ?
                    '_detailed_tables' : '')));

        $em     = $this->em;
        $repoP = $em->getRepository(Participation::class);
        $repoG  = $em->getRepository(Grade::class);
        $repoI  = $em->getRepository(GeneratedImage::class);

        $repoT = $em->getRepository(Team::class);

        if ($entity == 'user') {
            $repoU                     = $em->getRepository(User::class);
            $repoR                     = $em->getRepository(Result::class);
            $user                      = $repoU->find($elmtId);
            $element                   = $user;
            $resultParticipantProperty = 'usrId';
            $imageProperty             = 'usrId';
            $resultParticipantValue    = $elmtId;
            $gradedElmtId              = 'gradedUsrId';
        } else {
            $repoR                     = $em->getRepository(ResultTeam::class);
            $team                      = $repoT->find($elmtId);
            $element                   = $team;
            $imageProperty             = 'teaId';
            $resultParticipantProperty = 'team';
            $resultParticipantValue    = $team;
            $gradedElmtId              = 'gradedTeaId';
        }

        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $hasContribActivities     = false;
        $elementParticipations    = $repoP->findBy([$resultParticipantProperty => $resultParticipantValue, 'status' => [3, 4]], ['inserted' => 'ASC']);
        $nbGradedActivities       = 0;
        $nbGradedStages           = 0;
        $nbGradedCriteria         = 0;
        $nbGradedActivitiesStages = 0;
        $genResults               = [];
        $criterionResults         = new ArrayCollection;
        $stageResults             = new ArrayCollection;
        $activityResults          = new ArrayCollection;

        if ($currentUser->getRole() == 3) {

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
                    if ($elementResult->getStage() == $unreleasedStage || $elementResult->getActivity() == $unreleasedStage->getActivity() && $userResult->getStage() == null) {
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
            //;

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

            $elementResults = new ArrayCollection($repoR->findBy([$resultParticipantProperty => $resultParticipantValue], ['activity' => 'ASC']));

            $activityResults = $elementResults->matching(
                Criteria::create()->where(Criteria::expr()->eq("stage", null))
                    ->andWhere(Criteria::expr()->eq("criterion", null))
            //->andWhere(Criteria::expr()->neq("weightedStdDev", null))
            );

            $stageResults = $elementResults->matching(
                Criteria::create()->where(Criteria::expr()->neq("stage", null))
                    ->andWhere(Criteria::expr()->eq("criterion", null))
            //->andWhere(Criteria::expr()->neq("weightedStdDev", null))
            );
            $criterionResults = $elementResults->matching(
                Criteria::create()->where(Criteria::expr()->neq("stage", null))
                    ->andWhere(Criteria::expr()->neq("criterion", null))
            //->andWhere(Criteria::expr()->neq("weightedStdDev", null))
            );

            $nbGradedActivities = count($activityResults);
            $nbGradedStages     = count($stageResults);
            $nbGradedCriteria   = count($criterionResults);

            $nbResultsElements = count($elementResults);
            $activity          = null;

            if (!isset($_POST['settings_results_tables'])) {
                $activityResults  = null;
                $stageResults     = null;
                $criterionResults = null;
            }

            // Determining stage & activity general results
            foreach ($elementResults as $elementResult) {

                if ($elementResult->getActivity() != $activity) {

                    $activity = $elementResult->getActivity();

                    $actStagesResults = $repoR->findBy(['activity' => $activity, 'criterion' => null, $resultParticipantProperty => null]);
                    foreach ($actStagesResults as $actStagesResult) {

                        if ($actStagesResult->getStage() != null) {
                            $stageResults->add($actStagesResult);
                        } else {
                            $activityResults->add($actStagesResult);
                        }
                        $genResults[] = $actStagesResult;
                    }

                    //    $nbGradedActivitiesStages += count($actStagesResults);
                    //    $nbGradedActivities++;

                } else {
                    continue;
                }
            }

            //$nbGradedStages = $nbGradedActivitiesStages - $nbGradedActivities;
            //$nbGradedCriteria = $nbResultsElements - $nbGradedStages - $nbGradedActivities;

            $elementGrades = $repoG->findBy([$gradedElmtId => $elmtId]);

        }

        if ($elementGrades !== null) {
            $elementGrades = new ArrayCollection($elementGrades);
            $elementGrades = $elementGrades->matching(Criteria::create()->where(Criteria::expr()->neq("comment", null))->andWhere(Criteria::expr()->neq("comment", "")));
        }

        $iterator = $criterionResults->getIterator();

        $iterator->uasort(function ($first, $second) {
            return ($first->getStage()->getEnddate() >= $second->getStage()->getEnddate()) ? 1 : -1;
        });

        $criterionResults = new ArrayCollection(iterator_to_array($iterator));

        $iterator = $stageResults->getIterator();

        $iterator->uasort(function ($first, $second) {
            return ($first->getStage()->getEnddate() >= $second->getStage()->getEnddate()) ? 1 : -1;
        });

        $stageResults = new ArrayCollection(iterator_to_array($iterator));

        $iterator = $activityResults->getIterator();

        $iterator->uasort(function ($first, $second) {
            return ($first->getActivity()->getLatestStage()->getEnddate() >= $second->getActivity()->getLatestStage()->getEnddate()) ? 1 : -1;
        });

        $activityResults = new ArrayCollection(iterator_to_array($iterator));

        $elmtName = ($entity == 'user') ? $element->getFirstname() . ' ' . $element->getLastname() : $element->getName();

        $html2 = $this->render('element_report.html.twig', [
            'criterionResults'     => $criterionResults,
            'stageResults'         => $stageResults,
            'activityResults'      => $activityResults,
            //'results' => $elementResults,
            'grades'               => $elementGrades,
            'elmtType'             => $entity,
            'elmtName'             => $elmtName,
            'printAll'             => isset($_POST['print_-1']),
            'printActivities'      => isset($_POST['print_0']),
            'printStages'          => isset($_POST['print_1']),
            'printCriteria'        => isset($_POST['print_2']),
            'elmtCreatedDate'      => $element->getInserted(),
            'crt_graph'            => (isset($_POST['settings_graphs']) && (isset($_POST['print_-1']) || isset($_POST['print_2']))) ? $repoI->findOneBy(['actId' => null, $imageProperty => $elmtId, 'type' => 0])->getValue() : null,
            'stg_graph'            => (isset($_POST['settings_graphs']) && (isset($_POST['print_-1']) || isset($_POST['print_1']))) ? $repoI->findOneBy(['actId' => null, $imageProperty => $elmtId, 'type' => 1])->getValue() : null,
            'act_graph'            => (isset($_POST['settings_graphs']) && (isset($_POST['print_-1']) || isset($_POST['print_0']))) ? $repoI->findOneBy(['actId' => null, $imageProperty => $elmtId, 'type' => 2])->getValue() : null,
            'hasContribActivities' => $hasContribActivities,
            'nbGradedActivities'   => $nbGradedActivities,
            'nbGradedStages'       => $nbGradedStages,
            'nbGradedCriteria'     => $nbGradedCriteria,
        ]);

        //$mpdf = new Mpdf;
        $domPdf = new Dompdf;
        $domPdf->loadHtml($html2);
        // (Optional) Setup the paper size and orientation
        $domPdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $domPdf->render();

        // Output the generated PDF to Browser

        $d = new DateTime;

        $reportType = isset($_POST['print_-1']) ? 'Full' : (isset($_POST['print_0']) ? 'Activity' : (isset($_POST['print_1']) ? '|' : 'Criterion'));

        return $domPdf->stream($d->format("Ymd") . '_' . $reportType . '_report_' . str_replace(" ", "_", $elmtName) . $reportSingleElement . '.pdf');

        return $html2;

    }

    /**
     * @return \TFox\MpdfPortBundle\Service\PDFService
     */
    private function getMpdfService()
    {
        return $this->get('t_fox_mpdf_port.pdf');
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $usrId
     * @return mixed
     * @Route("/settings/report/user/{usrId}", name="viewUserReport")
     */
    public function viewUserReportAction(Request $request, $usrId)
    {

        $em                   = $this->em;
        $repoP               = $em->getRepository(Participation::class);
        $repoG                = $em->getRepository(Grade::class);
        $repoU                = $em->getRepository(User::class);
        $repoR                = $em->getRepository(Result::class);
        $user                 = $repoU->find($usrId);
        $hasContribActivities = false;
        $userResults          = $repoR->findByUsrId($usrId);

        foreach ($userResults as $userResult) {
            if ($userResult->getCriterion()) {
                if ($userResult->getCriterion()->getType() == 0) {
                    $hasContribActivities = true;
                    break;
                }
            }
        }

        $nbGradedCriteriaActivitiesStages = count($userResults);

        $userGrades = $repoG->findBy(['gradedUsrId' => $usrId]);

        //Adding general results (stage and activity)

        $activity   = null;
        $genResults = [];

        $nbGradedActivities       = 0;
        $nbGradedStages           = 0;
        $nbGradedActivitiesStages = 0;

        foreach ($userResults as $userResult) {
            if ($userResult->getActivity() != $activity) {
                $activity     = $userResult->getActivity();
                $genResults[] = $repoR->findBy(['activity' => $activity, 'criterion' => null, 'usrId' => null]);
                $nbGradedActivitiesStages += count($repoR->findBy(['activity' => $activity, 'criterion' => null, 'usrId' => null]));
                $nbGradedActivities++;
            } else {
                continue;
            }
        }

        $nbGradedStages   = $nbGradedActivitiesStages - $nbGradedActivities;
        $nbGradedCriteria = $nbGradedCriteriaActivitiesStages - $nbGradedStages - $nbGradedActivities;

        for ($k = 0; $k < $nbGradedActivities; $k++) {
            foreach ($genResults[$k] as $activityElmt) {
                $userResults[] = $activityElmt;
            }
        }

        return $this->render('user_report.html.twig', [
            'results'              => $userResults,
            'grades'               => $userGrades,
            'user'                 => $user,
            'hasContribActivities' => $hasContribActivities,
            'nbGradedActivities'   => $nbGradedActivities,
            'nbGradedStages'       => $nbGradedStages,
            'nbGradedCriteria'     => $nbGradedCriteria,
            'crt_graph'            => $_POST['chart_input_crt'],
            'stg_graph'            => $_POST['chart_input_stg'],
            'act_graph'            => $_POST['chart_input_act'],
        ]);
    }

    public function waitSomeSeconds(Request $request, $nbSeconds)
    {
        sleep($nbSeconds);
    }

    // Admin user deletion function

    /**
     * @param Request $request
     * @param Application $app
     * @param $usrId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteUserAction(Request $request, $usrId)
    {
        $em          = $this->em;
        $repoP      = $em->getRepository(Participation::class);
        $repoU       = $em->getRepository(User::class);
        $repoO       = $em->getRepository(Organization::class);
        $repoD       = $em->getRepository(Department::class);
        $repoP       = $em->getRepository(Position::class);
        $repoW       = $em->getRepository(Weight::class);
        $user        = $repoU->find($usrId);
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $currentUserOrgId = $currentUser->getOrgId();
        if ($currentUserOrgId != $user->getOrgId()) {
            return new JsonResponse(['message' => 'Error'], 500);
        }
        $organization = $repoO->find($currentUserOrgId);
        $deleteOrg    = false;
        $userOrgId    = $user->getOrgId();
        $posId        = $user->getPosId();
        $dptId        = $user->getDptId();
        $wgtId        = $user->getWgtId();

        if ($posId != null) {
            $position      = $repoP->find($posId);
            $positionUsers = $position->getUsers($app);
            if (count($positionUsers) == 1) {
                $position->setDeleted(new DateTime);
                $em->persist($position);
            }
        }

        if ($dptId != null) {
            $department      = $repoD->find($dptId);
            $departmentUsers = $department->getUsers($app);
            if (count($departmentUsers) == 1) {
                $department->setDeleted(new DateTime);
                $em->persist($departement);
            }
        }

        // We remove completely the user if he did not participate to anything, otherwise we keep it in the DB in order to track his previous actions
        // (we should consider its anonymation)
        if ($repoP->findOneByUsrId($user->getId()) == null) {
            $em->remove($user);
        } else {
            $user->setDeleted(new DateTime);
            $em->persist($user);
        }
        $em->flush();

        return new JsonResponse(['message' => 'Success!'], 200);
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
        $isDeletable = true;
        $orgUsers = $clientOrganization->getUsers($app);

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
        return $app->json(['status' => 'done'], 200);
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
        $externalUserParticipations = $repoP->findBy(['activity' => $organization->getActivities()->getValues(), 'usrId' => $relatedInternalUser->getId()]);

        if(sizeof($externalUserParticipations) == 0){
            $em->remove($externalUser);
        } else {
            $externalUser->setDeleted(new DateTime);
            $em->persist($externalUser);
        }

        if($relatedInternalUser->getLastConnected() == null && sizeof($relatedInternalUser->getExternalUsers()) == 1){
            $em->remove($relatedInternalUser);
        }

        $em->flush();
        return $app->json(['status' => 'done'], 200);
    }

    //Adds team(s) to current organization (limited to HR)

    /**
     * @param Request $request
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
        $team->setCurrentUser($this->user);
        $currentUser = $this->user;
        if (!$team->isModifiable()) {
            return $this->render('errors/403.html.twig');
        }

        if($teaId === null){
            $organization = $currentUser->getOrganization();
            $team->setOrganization($organization);
            $team->setName($organization->getTeams()->count() + 1);
        }
        
        $manageTeamForm = $this->createForm(AddTeamForm::class, $team, ['standalone' => true, 'currentUser' => $this->user]);
        $manageTeamForm->handleRequest($request);
        if($manageTeamForm->isSubmitted() && $manageTeamForm->isValid()){
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
                    $teamUser  = new TeamUser;
                    $teamUser->setTeam($team)->setUsrId($usrId);
                    $em->persist($teamUser);
                }
            }
            $em->flush();

            $mailableUsers = $repoU->findById($usersId);
            $recipients    = [];
            foreach ($mailableUsers as $mailableUser) {
                $recipients[] = $mailableUser;
            }

            $settings['team'] = $team;

            self::sendMail($app, $recipients, 'teamCreation', $settings);

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
        $orgId         = $currentUser->getOrgId();
        $em            = $this->em;
        $repoO         = $em->getRepository(Organization::class);
        $repoT         = $em->getRepository(Team::class);
        $team          = $repoT->find($teaId);
        $organization  = $repoO->find($orgId);

        if ($currentUser->getRole() != 4 && $team->getOrganization() != $organization) {
            return $this->render('/errors/403.html.twig');
        }

        $teamUsers   = $team->getActiveTeamUsers();
        $teamUsersId = [];
        foreach ($teamUsers as $teamUser) {
            $teamUsersId[] = $teamUser->getUser($app)->getId();
        }

        return $this->render('team_manage.html.twig',
            [
                'team'         => $team,
                'organization' => $organization,
                'teamUsersId'  => $teamUsersId,
                'delete'       => true,
            ]);

    }

    public function updateAjaxTeamAction(Request $request, $teaId)
    {

        $em          = $this->em;
        $repoU       = $em->getRepository(User::class);
        $repoO       = $em->getRepository(Organization::class);
        $repoT       = $em->getRepository(Team::class);
        $repoP      = $em->getRepository(Participation::class);
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

            $teamUsers = clone $team->getTeamUsers();
            foreach ($teamUsers as $teamUser) {
                $teamUserId       = $teamUser->getUser($app)->getId();
                $allTeamUsersId[] = $teamUserId;

                if ($teamUser->isDeleted() == false) {
                    $livingTeamUsersId[] = $teamUserId;
                } else {
                    $deletedTeamUsersId[] = $teamUserId;
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
                            foreach ($teamUsers as $teamUser) {
                                if ($teamUser->getUsrId() == $usrId) {
                                    $deletedTeamUser = $teamUser;
                                    break;
                                }
                            }
                            $deletedTeamUser->setIsDeleted(false);
                            $em->persist($deletedTeamUser);

                        } else {

                            $teamUser = new TeamUser;
                            $teamUser->setTeam($team)->setUsrId($usrId);
                            $em->persist($teamUser);

                        }

                        // Team joiners (new or former) will receive a team welcome mail
                        $addedRecipients[] = $addedUser;
                    }
                    $usersId[] = $usrId;
                }
            }

            if (count($addedRecipients) > 0) {
                $settings['team'] = $team;
                self::sendMail($app, $addedRecipients, 'teamCreation', $settings);
            }

            // 2 - ... and we look whether there are team leavers

            foreach ($teamUsers as $key => $teamUser) {
                if (array_search($allTeamUsersId[$key], $usersId) === false) {

                    // We delete all his participations on activities which have not started.
                    //If one activity has started, we remove all his participations on future stages, and keep ongoing ones.
                    $deletedUsrIds[] = $allTeamUsersId[$key];
                    $teamUser->setDeleted(new DateTime);
                    $teamUser->setIsDeleted(true);
                    $removedRecipients[] = $repoU->find($teamUser->getUsrId());
                    $em->persist($teamUser);
                }
            }

            if (count($removedRecipients) > 0) {
                $settings['team'] = $team;
                self::sendMail($app, $removedRecipients, 'teamUserRemoval', $settings);
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
                $teamUserParticipations = new ArrayCollection($repoP->findBy(['usrId' => $deletedUsrIds, 'team' => $team], ['activity' => 'ASC']));
                $definedActivity        = null;
                foreach ($teamUserParticipations as $teamUserParticipation) {
                    $activity = $teamUserParticipation->getActivity();
                    if ($activity != $definedActivity) {
                        $definedActivity = $activity;

                        if ($definedActivity->getStatus() < 1) {
                            $deletableTeamUserParticipations = $teamUserParticipations->matching(Criteria::create()->where(Criteria::expr()->eq("activity", $activity)));
                            foreach ($deletableTeamUserParticipations as $deletableTeamUserParticipation) {
                                $activity->removeGrade($deletableTeamUserParticipation);
                            }
                            $em->persist($activity);
                        } else if ($definedActivity->getStatus() == 1) {
                            foreach ($activity->getActiveStages() as $stage) {

                                $deletableTeamUserParticipations = $teamUserParticipations->matching(Criteria::create()->where(Criteria::expr()->eq("stage", $stage)));
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

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/organizations/new", name="userCreateOrganization")
     */
    public function addUserOrganizationAction(Request $request)
    {

        $locale = 'fr';

        $em               = $this->em;
        
        $organizationForm = $this->createForm(AddOrganizationForm::class, null, ['standalone' => true, 'orgId' => 0, 'app' => $app, 'isFromClient' => true]);
        $organizationForm->handleRequest($request);
        $errorMessage = '';
        $organization = new Organization;
        $user         = new User;
        $department   = new Department;
        $position     = new Position;
        $repoO        = $em->getRepository(Organization::class);
        $repoU        = $em->getRepository(User::class);

        if ($organizationForm->isSubmitted()) {

            if ($repoO->findOneByCommname($organizationForm->get('commercialName')->getData())) {
                $organizationForm->get('commercialName')->addError(new FormError('You already have created an organization with such legalname. Make sure you correctly provided the data'));
            }

            if ($repoU->findOneByEmail($organizationForm->get('email')->getData())) {
                $organizationForm->get('email')->addError(new FormError('Ooopsss ! There is already a user registered with this email address. Please make sure you have now already created an organization with such address'));
            }

            if ($organizationForm->isValid()) {
                $email             = $organizationForm->get('email')->getData();
                $firstname         = $organizationForm->get('firstname')->getData();
                $lastname          = $organizationForm->get('lastname')->getData();
                $fullName          = $firstname . ' ' . $lastname;
                $positionName      = $organizationForm->get('position')->getData();
                $departmentName    = $organizationForm->get('department')->getData();
                $position          = new Position;
                $department        = new Department;
                $repoON            = $em->getRepository(OptionName::class);
                $options           = $repoON->findAll();
                $token             = md5(rand());
                $orgCommercialName = $organizationForm->get('commercialName')->getData();
                $organization->setCommname($orgCommercialName)
                    ->setLegalname($orgCommercialName)
                    ->setIsClient(false)
                    ->setMasterUserId(0)
                    ->setExpired(new DateTime("+1 month"));
                $em->persist($organization);

                // Setting organization options
                foreach ($options as $option) {

                    if($option->getName() == 'activitiesAccessAndResultsView')
                    {
                        /** @var OrganizationUserOption */
                        $adminOption = new OrganizationUserOption();
                        $adminOption->setRole(1)->setOptionTrue(true)->setOptionFValue(1)->setOptionIValue(1)->setOptionSecondaryIValue(2)->setEnabled(false);
                        $amOption = new OrganizationUserOption();
                        $amOption->setRole(2)->setOptionTrue(true)->setOptionFValue(1)->setOptionIValue(1)->setOptionSecondaryIValue(2)->setEnabled(false);
                        $collabOption = new OrganizationUserOption();
                        $amOption->setRole(2)->setOptionTrue(true)->setOptionFValue(1)->setOptionIValue(1)->setOptionSecondaryIValue(3)->setEnabled(false);
                        $em->persist($adminOption);
                        $em->persist($amOption);
                        $em->persist($collabOption);

                    } else {
                        $optionValid = new OrganizationUserOption;
                        $optionValid->setOName($repoON->find($option))->setOrganization($organization);
                        $em->persist($optionValid);
                    }
                    $em->flush();
                }

                //if ($organizationForm->get('isFirm')->getData()) {

                $department->setName($departmentName);
                $organization->addDepartment($department);
                //$em->flush();

                //$position->setDepartment($department->getId());
                $position
                    ->setName($positionName);
                $department->addPosition($position);
                $organization->addPosition($position);

                /*} else {
                $department->setOrganization($organization);
                $department->setName("Administration");
                $em->persist($department);

                $department2 = new Department;

                $department2->setOrganization($organization);
                $department2->setName("Students");
                $em->persist($department2);

                $position->setDepartment($department);
                $position->setName($organizationForm->get('position')->getData());
                }*/

                //Field is redundant and unecessary but except destroying it in DB, we put 0 to it

                $em->persist($organization);
                $em->flush();

                $user->setFirstname($firstname)
                    ->setLastname($lastname)
                    ->setEmail($email)
                    ->setRole(1)
                    ->setToken($token)
                    ->setPosId($position->getId())
                    ->setDptId($department->getId())
                    ->setWeightIni(100)
                    ->setOrgId($organization->getId());

                $em->persist($user);
                $em->flush();

                $weight = new Weight;
                $weight->setUsrId($user->getId())->setInterval(0)->setTimeframe('D')->setValue(100)->setCreatedBy($this->user->getId());
                $position->addWeight($weight);
                $em->persist($position);
                $organization
                    ->setMasterUserId($user->getId())
                    ->addWeight($weight);

                $em->persist($organization);
                $em->flush();

                $user->setWgtId($weight->getId());
                $em->persist($user);
                $em->flush();

                $repoCN          = $em->getRepository(CriterionName::class);
                $criterionGroups = [
                    1 => new CriterionGroup('Hard skills', $organization),
                    2 => new CriterionGroup('Soft skills', $organization),
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
                    $cg   = $criterionGroups[$type];
                    $criterion
                        ->setOrganization($organization)
                        ->setCriterionGroup($cg);

                    $cg->addCriterion($criterion);
                    $em->persist($criterion);
                }
                $em->flush();

                //Sending mail to Serpico root users and administrators
                $serpicoOrg           = $repoO->findOneByCommname('Serpico');
                $serpiValidatingUsers = $repoU->findBy(['role' => 4, 'orgId' => $serpicoOrg->getId()]);

                $settings['orgId']                = $organization->getId();
                $settings['orgName']              = $orgCommercialName;
                $settings['masterUserFullName']   = $fullName;
                $settings['masterUserEmail']      = $email;
                $settings['masterUserDepartment'] = $departmentName;
                $settings['masterUserPosition']   = $positionName;

                self::sendMail($app, $serpiValidatingUsers, 'validateOrgSubscription', $settings);

                //Sending mail acknowledgment receipt to the requester
                $recipients          = [];
                $recipients[]        = $user;
                $settings            = [];
                $settings['orgName'] = $orgCommercialName;
                self::sendMail($app, $recipients, 'subscriptionAcknowledgmentReceipt', $settings);

                return $this->redirectToRoute('login');
            }

        }

        return $this->render('organization_add.html.twig',
            [
                'form'        => $organizationForm->createView(),
                'message'     => $errorMessage,
                'noFooter'    => true,
                'request'     => $request,
                'addFromUser' => true,
            ]);

    }

    /**
     * @param Application $app
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/process/request/reject", name="rejectProcessRequest")
     */
    public function rejectProcessRequestAction(Application $app, Request $request){ 
        $id = $request->get('id');
        $type = $request->get('type');
        $element = $type == 'p' ? $this->em->getRepository(Process::class)->find($id) : $this->em->getRepository(InstitutionProcess::class)->find($id);
        if($type == 'i'){
            if($element->getActivities()->count() > 0){
                foreach($element->getActivities() as $activity){
                    $element->removeActivity($activity);
                    $activity->setInstitutionProcess(null);
                    $this->em->persist($activity);
                }
                //$this->em->flush();
            }
        } else {
            if($element->getInstitutionProcesses()->count() > 0){
                foreach($element->getInstitutionProcesses() as $IProcess){
                    $element->removeProcess($IProcess);
                    $IProcess->setProcess(null);
                    $this->em->persist($IProcess);
                }
                //$this->em->flush();
            }
        }
        //$this->em->persist($element);
        //$this->em->flush();
        $this->em->remove($element);
        $this->em->flush();
        return new JsonResponse(['msg' => 'success'],200);
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{entity}/request/validate", name="validateProcessRequest")
     */
    public function validateProcessRequestAction(Application $app, Request $request){
        $id = $request->get('id');
        $type = $request->get('type');
        $organization = MasterController::getAuthorizedUser()->getOrganization();
        
        /** @var InstitutionProcess|Process */
        $element = $type == 'p' ? $this->em->getRepository(Process::class)->find($id) : $this->em->getRepository(InstitutionProcess::class)->find($id);
        $elementInitialName = $element->getName();
        $elementInitialParent = $element->getParent();
        $entity = $type == 'p' ? 'process' : 'iprocess';
        $validateProcessForm = $this->createForm(AddProcessForm::class, $element, ['standalone' => true, 'organization' => $organization, 'elmt' => $entity]);
        $validateProcessForm->handleRequest($request);
        if($validateProcessForm->isValid()){
            $element->setApprovable(false);
            $this->em->persist($element);
            $this->em->flush();
            $settings['process'] = $element;
            $settings['initialProcessName'] = $elementInitialName;
            $settings['initialProcessParent'] = $elementInitialParent;
            $requester = [$this->em->getRepository(User::class)->find($element->getCreatedBy())];
            MasterController::sendMail($app, $requester, 'validateProcess', $settings);

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
                $repoU = $this->em->getRepository(Participation::class);
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

//                        self::sendMail($app, $recipients, 'activityParticipation', $mailSettings);
                        $this->em->flush();
                    }


                    // We need to update activity/stage o- and p- statuses accordingly
                    foreach($element->getActiveModifiableStages() as $stage){
                        //Output status
                        if($stage->isComplete()){
                            $stage->setStatus((int) ($stage->getGStartDate() <= new DateTime));
                        } else {
                            $stage->setStatus($stage::STAGE_INCOMPLETE);
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
                            return $s->getStatus() === $s::STAGE_UNSTARTED;
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
            ]
        );
    }

}
