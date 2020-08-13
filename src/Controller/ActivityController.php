<?php

namespace App\Controller;

use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Dompdf\Dompdf;
use Exception;
use Form\ActivityReportForm;
use Form\AddActivityCriteriaForm;
use Form\AddActivityForm;
use Form\AddCriterionForm;
use Form\AddStageForm;
use Form\AddSurveyForm;
use Form\AddTemplateForm;
use Form\CreateCriterionForm;
use Form\DelegateActivityForm;
use Form\ManageStageParticipantsForm;
use Form\RequestActivityForm;
use Form\Type\StageUniqueParticipationsType;
use Model\Activity;
use Model\ActivityUser;
use Model\Answer;
use Model\Client;
use Model\Criterion;
use Model\CriterionName;
use Model\DbObject;
use Model\Decision;
use Model\Department;
use Model\ExternalUser;
use Model\GeneratedImage;
use Model\Grade;
use Model\Icon;
use Model\InstitutionProcess;
use Model\IProcessActivityUser;
use Model\IProcessCriterion;
use Model\IProcessStage;
use Model\Organization;
use Model\OrganizationUserOption;
use Model\ProcessStage;
use Model\Recurring;
use Model\Result;
use Model\Stage;
use Model\Survey;
use Model\Target;
use Model\Team;
use Model\TeamUser;
use Model\TemplateActivity;
use Model\TemplateActivityUser;
use Model\TemplateCriterion;
use Model\TemplateStage;
use Model\User;
use Repository\UserRepository;
use RouteDumper;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends MasterController
{
    // Creating activity V1 : attributing leadership to current user and redirecting to parameters
    /**
     * @param string $elmtType
     * @param int $inpId
     * @param string $actName
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{elmt}/create",name="activityInitialisation")
     */
    public function addActivityId(string $elmtType, $inpId = 0, $actName = '')
    {
        $currentUser = $this->getAuthorizedUser();
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        $usrId = $currentUser->getId();
        $usrOrg = $currentUser->getOrganization();
        $em = $this::getEntityManager();
        $isActivity = $elmtType == 'activity';

        $activity = $isActivity ? new Activity : new TemplateActivity;
        $stage = $isActivity ? new Stage : new TemplateStage;
        //$criterion = $isActivity ? new Criterion : new TemplateCriterion;
        //$participant = $isActivity ? new ActivityUser : new TemplateActivityUser;

        $startDate = new \DateTime;
        $activityStartDate = clone $startDate;
        $activityEndDate = clone $startDate;
        $activityGStartDate = clone $startDate;
        $activityGEndDate = clone $startDate;

        $activityCount = $usrOrg->getActivities()->count();
        $nextActIndex = $activityCount + 1;
        $actDefaultName = "Activity $nextActIndex";
        $stgDefaultName = "Stage 1";
        $activityName = $actName != '' ? $actName : $actDefaultName;
        $stageName = $actName != '' ? $actName : $stgDefaultName;

        $activity
            ->setName($activityName)
            ->setOrganization($currentUser->getOrganization())
            ->setMasterUserId($usrId)
            ->setCreatedBy($currentUser->getId())
            ->addStage($stage);

        if ($inpId != 0) {
            $activity->setInstitutionProcess($em->getRepository(InstitutionProcess::class)->find($inpId));
        }

        $stage
            ->setName($stageName)
            ->setMasterUserId($usrId)
            ->setWeight(1)
            ->setStartdate($activityStartDate)
            ->setEnddate($activityEndDate)
            ->setGstartdate($activityGStartDate)
            ->setGenddate($activityGEndDate)
            ->setMode(1)
            ->setProgress(STAGE::PROGRESS_ONGOING)
            ->setCreatedBy($usrId);
            /*->addCriterion($criterion);
        $criterion
            ->setCName($usrOrg->getCriterionNames()->first());
        $participant
            ->setType(1)
            ->setLeader(true)
            ->setUsrId($usrId)
            ->setActivity($activity)
            ->setStage($stage);
        */
        if ($isActivity) {
            $activity
                ->setStatus(-1);
            $stage
                ->setOrganization($usrOrg);
        }

        $em->persist($activity);
        $em->flush();

        /*if ($inpId) {*/
        // Has been launched from iprocess panel, expecting JSON response
        //TODO
//        return new JsonResponse(['message' => 'success to create activity', 'redirect' => $app['url_generator']->generate('manageActivityElement', ['elmtType' => 'activity', 'elmtId' => $activity->getId()])], 200);
        //TODO END
        /*} else {

            return self::redirectToRoute(
                'manageActivityElement',
                [
                    'elmtType' => $elmtType,
                    'elmtId' => $activity->getId(),
                ]
            );

        }*/
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
        $currentUser = $this->getAuthorizedUser();
        if (!$currentUser) {
            throw new Exception('unauthorized');
        }
        $currentUserId = $currentUser->getId();
        $em = self::getEntityManager();
        /** @var UserRepository */
        $repoU = $em->getRepository(User::class);
        $repoCN = $em->getRepository(CriterionName::class);
        $repoO = $em->getRepository(Organization::class);

        /** @var FormFactory */
        $formFactory = $app['form.factory'];

        $delegateActivityForm = $formFactory->create(
            DelegateActivityForm::class,
            null,
            [
                'app' => $app,
                'standalone' => true,
            ]
        )->handleRequest($request);

        if ($delegateActivityForm->isValid()) {
            // 1 - Create Activity (similar to addActivityId without redirection)
            $startDate = new \DateTime;
            $activityName = $delegateActivityForm->get('activityName')->getData();
            /** @var User */
            $activityLeader = $delegateActivityForm->get('activityLeader')->getData();
            $activityDescription = $delegateActivityForm->get('activityDescription')->getData();

            /** @var CriterionName */
            $defaultCriterionName = $repoCN->findOneBy(['organization' => $currentUser->getOrganization()]);

            $activity = (new Activity)
                ->setName($activityName)
                ->setMasterUserId($activityLeader->getId())
                ->setOrganization($currentUser->getOrganization())
                ->setObjectives($activityDescription)
                ->setStatus(-2)
                ->setCreatedBy($currentUserId);
            $em->persist($activity);
            $em->flush();

            $stage = (new Stage)
                ->setName($activityName)
                ->setMasterUserId($activityLeader->getId())
                ->setWeight(1)
                ->setMode(1)
                ->setStartdate(clone $startDate)
                ->setEnddate(clone $startDate)
                ->setGstartdate(clone $startDate)
                ->setGenddate(clone $startDate)
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
     * @param Application $app
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/activity/request", name="activityRequest")
     */
    public function requestActivityAction(Request $request, Application $app)
    {
        $em = self::getEntityManager();
        $repoU = $em->getRepository(User::class);
        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }
        $organization = $em->getRepository(Organization::class)->find($currentUser->getOrgId());
        /** @var FormFactory */
        $formFactory = $app['form.factory'];
        $requestActivityForm = $formFactory->create(RequestActivityForm::class, null, ['app' => $app, 'standalone' => true]);
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
            $activity->setMasterUserId(0);
            $activity->setOrganization($organization);
            $activity->setObjectives($activityObjectives);
            $activity->setStatus(-3);

            $em->persist($activity);
            //$em->flush();
            $stage = new Stage;
            $startDate = new \DateTime;
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
     * @param Application $app
     * @param $actId
     * @param $action
     * @return bool|JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/activity/request/{actId}/{action}", name="activityResolveRequest")
     */
    public function resolveActivityRequest(Request $request, Application $app, $actId, $action)
    {

        $em = self::getEntityManager();
        $repoU = $em->getRepository(User::class);
        $currentUser = self::getAuthorizedUser();
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
        $decision->setDecided(new \DateTime);

        // Here we consider that the activity is validated or discarded thanks to a single person.
        // But in a enhanced approach, we could consider the approval of a certain number of users,
        // thus we should leave validated field to NULL until conditions are met
        $decision->setValidated(new \DateTime);
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

            $formFactory = $app['form.factory'];
            $validateRequestForm = $formFactory->create(DelegateActivityForm::class, null, ['app' => $app, 'standalone' => true, 'request' => true]);
            $validateRequestForm->handleRequest($request);

            if ($validateRequestForm->isValid()) {

                //1 - Create Activity (similar to addActivityId without redirection)
                $activityName = $validateRequestForm->get('activityName')->getData();
                $activityDescription = $validateRequestForm->get('activityDescription')->getData();

                if ($validateRequestForm->get('ownCreation')->getData() == false) {
                    $activityLeaderId = $validateRequestForm->get('activityLeader')->getData();
                    $activityLeader = $repoU->find($activityLeaderId);
                } else {
                    $activityLeaderId = $currentUser->getId();
                }

                $activity
                    ->setName($activityName)
                    ->setObjectives($activityDescription)
                    ->setMasterUserId($activityLeaderId);
                $stage = $activity->getStages()->first();
                $stage->setMasterUserId($activityLeaderId);
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

    // ACTIVITY CREATION (MVP)

    // 1st step - criterion definition (limited to activity manager)

    /**
     * @param Request $request
     * @param Application $app
     * @param $elmt
     * @param $elmtId
     * @return RedirectResponse
     * @Route("/{elmt}/{elmtId}/parameters", name="oldActivityDefinition")
     */
    public function oldAddActivityDefinition(Request $request, Application $app, $elmt, $elmtId)
    {
        $user = self::getAuthorizedUser();
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $em = self::getEntityManager();
        $userRole = $user->getRole();
        $organization = $user->getOrganization();
        /** @var Activity|TemplateActivity|null */
        $activity = $em->getRepository(
            $elmt === 'activity' ? Activity::class : TemplateActivity::class
        )->find($elmtId);
        $userIsNotRoot = $userRole != 4;
        $userIsAdmin = $userRole == 1;
        $userIsAM = $userRole == 2;
        $userIsCollab = $userRole == 3;
        $hasPageAccess = true;

        if (!$activity) {
            $errorMsg = 'activityDoNotExist';
            $hasPageAccess = false;
        } else {
            $simplifiedActivity = count($activity->getStages()) == 1 && count($activity->getStages()->first()->getCriteria()) == 1;
            $actOrganization = $activity->getOrganization();
            $actBelongsToDifferentOrg = $organization != $actOrganization;

            if ($activity instanceof Activity) {
                $activeModifiableStages = $activity->getActiveModifiableStages();
                $actHasNoActiveModifiableStages = count($activeModifiableStages) == 0;

                if ($userIsNotRoot and ($actBelongsToDifferentOrg or !$userIsAdmin and $actHasNoActiveModifiableStages)) {
                    if ($userIsNotRoot || $actBelongsToDifferentOrg) {
                        $errorMsg = 'externalViolation';
                    } else {
                        $errorMsg = 'unmodifiableActivity';
                    }
                    $hasPageAccess = false;
                }
            } else {
                if ($userIsCollab or ($userIsAM or $userIsAdmin) and $actBelongsToDifferentOrg) {
                    $hasPageAccess = false;
                }
            }
        }

        if (!$hasPageAccess) {
            return $app['twig']->render('errors/403.html.twig', [
                'errorMsg' => $errorMsg,
                'returnRoute' => 'myActivities',
            ]);
        } else {
            /** @var FormFactory */
            $formFactory = $app['form.factory'];
            $incomplete = $activity->getStages()->first() == null;
            $parametersForm = $formFactory->create(
                AddActivityCriteriaForm::class,
                null,
                [
                    'standalone' => true,
                    'activity' => $activity,
                    'incomplete' => $incomplete,
                    'organization' => $organization,
                ]
            );
            $parametersForm->handleRequest($request);
            $createTemplateForm = null;
            $createCriterionForm = null;
            if ($simplifiedActivity) {
                $createCriterionForm = $formFactory->create(CreateCriterionForm::class, null, ['standalone' => true]);
                $createCriterionForm->handleRequest($request);
            }
            if ($elmt == 'activity' && $activity->getTemplate() == null) {
                $createTemplateForm = $formFactory->create(AddTemplateForm::class, null, ['standalone' => true]);
                $createTemplateForm->handleRequest($request);
            }

            return $app['twig']->render('activity_create_definition_old.twig',
                [
                    'form' => $parametersForm->createView(),
                    'activity' => $activity,
                    'incomplete' => $incomplete,
                    'createTemplateForm' => ($createTemplateForm === null) ?: $createTemplateForm->createView(),
                    'createCriterionForm' => ($createCriterionForm === null) ?: $createCriterionForm->createView(),
                    'icons' => $em->getRepository(Icon::class)->findAll(),
                ]
            );
        }
    }

    // Change activity complexity after user has clicked on the add phases/stages button

    /**
     * @param Request $request
     * @param Application $app
     * @param $actId
     * @return false|int|string
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/activity/{actId}/complexify", name="activityComplexify")
     */
    public function complexifyActivity(Request $request, Application $app, $actId)
    {
        $elmt = strpos($_SERVER['HTTP_REFERER'], 'activity');
        $em = self::getEntityManager();
        if ($elmt !== false) {
            $activity = $em->getRepository(Activity::class)->find($actId);

        } else {
            $elmt = 'hello';
            $activity = $em->getRepository(TemplateActivity::class)->find($actId);
        }
        $activity->setSimplified(false);
        $em->persist($activity);
        $em->flush();
        return $elmt;
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $elmt
     * @param $elmtId
     * @param $actionType
     * @param bool $returnJSON
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/{elmt}/{elmtId}/parameters/{actionType}", name="oldActivityDefinitionAJAX")
     */
    public function oldAddActivityDefinitionAJAX(Request $request, Application $app, $elmt, $elmtId, $actionType, $returnJSON = true)
    {
        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        /**
         * @param Activity|TemplateActivity $activity
         */
        function giveActNameToStage(DbObject $activity, string $name)
        {
            $stages = $activity->getStages();
            if ($stages->count() === 1) {
                $stage = $stages->get(0);
                $stage->setName($name);
            }

            $em = MasterController::getEntityManager();
            $em->persist($stage);
        }

        $em = self::getEntityManager();
        /** @var FormFactory */
        $formFactory = $app['form.factory'];

        $activity = $elmt == 'activity'
        ? $em->getRepository(Activity::class)->find($elmtId)
        : $em->getRepository(TemplateActivity::class)->find($elmtId);

        $simplifiedActivity = count($activity->getStages()) == 1 && count($activity->getStages()->first()->getCriteria()) == 1;
        $incomplete = $activity->getStages()->first() === null;

        $repoO = $em->getRepository(Organization::class);
        $repoCN = $em->getRepository(CriterionName::class);
        $organization = $currentUser->getOrganization();
        $parametersForm = $formFactory->create(
            AddActivityCriteriaForm::class,
            null,
            [
                'standalone' => true,
                'activity' => $activity,
                'incomplete' => $incomplete,
                'organization' => $organization,
            ]
        );
        $parametersForm->handleRequest($request);

        if ($actionType == 'next') {

            if ($parametersForm->isValid()) {

                $name = $parametersForm->get('name')->getData();
                // $magnitude = $parametersForm->get('magnitude')->getData();

                $stage = ($activity->getStages()->first()) ?: new Stage;
                $stage->setCreatedBy($currentUser->getId());
                $criterion = ($stage->getCriteria()->first()) ?: new Criterion;

                if ($simplifiedActivity) {

                    $type = $parametersForm->get('type')->getData();
                    $lowerbound = ($parametersForm->get('type')->getData() == 3) ? 0 : $parametersForm->get('lowerbound')->getData();
                    $upperbound = ($parametersForm->get('type')->getData() == 3) ? 1 : $parametersForm->get('upperbound')->getData();
                    $step = $parametersForm->get('step')->getData();

                } else {

                    //$stage->setName($name)->setWeight(1);
                    //$criterion->setName('General')->setWeight(1);
                    //$em->persist($stage);
                    //$em->persist($criterion);
                    //$em->flush();
                }

                // 1 - Create activity parameters and flush...
                $activity
                    ->setVisibility($parametersForm->get('visibility')->getData())
                    ->setObjectives($parametersForm->get('objectives')->getData())
                    ->setName($name)
                    ->setMagnitude(1);
                $em->persist($activity);
                $em->flush();

                /*if ($parametersForm->get('recurringChoice')->getData() == 1) {

                $recurring = ($activity->getRecurring()) ?: new Recurring;

                $frequency = $parametersForm->get('recurringFrequency')->getData();
                $timeFrame = $parametersForm->get('recurringTimeFrame')->getData();
                $recStartdate = $parametersForm->get('recurringStartDate')->getData();
                $recEnddate = $parametersForm->get('recurringEndDate')->getData();
                $gStartDateInterval = $parametersForm->get('recurringGStartDateInterval')->getData();
                $gStartDateTimeFrame = $parametersForm->get('recurringGStartDateTimeFrame')->getData();
                $gEndDateInterval = $parametersForm->get('recurringGEndDateInterval')->getData();
                $gEndDateTimeFrame = $parametersForm->get('recurringGEndDateTimeFrame')->getData();
                if (!$activity->getRecurring()) {$recurring->setStatus(-1);}
                $recurring->setName($name)->setMasterUserId($activity->getMasterUserId())->setFrequency($frequency)->setTimeFrame($timeFrame)->setGStartDateInterval($gStartDateInterval)->setGStartDateTimeFrame($gStartDateTimeFrame)->setGEndDateInterval($gEndDateInterval)->setGEndDateTimeFrame($gEndDateTimeFrame)->setStartdate($recStartdate)->setEnddate($recEnddate)->setOrganization($organization);
                $em->persist($recurring);

                if (!$activity->getRecurring()) {
                $activity->setRecurring($recurring);
                $recurring->addActivity($activity);
                $em->persist($recurring);
                $em->persist($activity);
                $em->flush();
                }

                MasterController::createRecurringActivities($app,$organization,$recurring,$user,$name,$frequency,$recStartdate,$timeFrame,$gStartDateInterval,$gStartDateTimeFrame,$gEndDateInterval,$gEndDateTimeFrame,$type,$lowerbound,$upperbound,$step,'1Y',$recEnddate);

                }*/

                // Stage and associated criterion are persisted and recorded only if activity is simplified
                // as these fields are "reported" to the other activities steps

                if (!$activity->getRecurring()) {

                    if ($simplifiedActivity) {

                        // 2 - ... to define then stage parameters...
                        $stage->setName($name)
                            ->setMode($parametersForm->get('mode')->getData())
                            ->setStartdate($parametersForm->get('startdate')->getData())
                            ->setEnddate($parametersForm->get('enddate')->getData())
                            ->setGstartdate($parametersForm->get('gstartdate')->getData());
                        $activity->setStartdate($parametersForm->get('startdate')->getData())->setEnddate($parametersForm->get('enddate')->getData());
                        $em->persist($activity);

                        $nextDayDate = new \DateTime;
                        //$nextDayDate->add(new \DateInterval('P1D'));

                        // Check if activity is complete, change status in this case (in case we manipulate a non-template activity)
                        if ($elmt == 'activity') {

                            if ($parametersForm->get('gstartdate')->getData() > $nextDayDate) {
                                $stage->setStatus(0);
                            } else {
                                $stage->setStatus(1);
                            }

                            if ($activity->getIsFinalized() == true) {
                                $activity->setStatus($stage->getStatus());
                            } else {
                                $activity->setStatus(-1);
                            }

                        }

                        $stage->setGenddate($parametersForm->get('genddate')->getData());
                        $stage->setActivity($activity);
                        $stage->setWeight(1);

                        // By default, stage master user is similar to the one of the related activity
                        $stage->setMasterUserId($activity->getMasterUserId());
                        //$em->persist($stage);

                        //3 - ... before finally creating criteria parameters
                        //if ($criterion->getCName() == null) {
                        //    $criterion->setCName('General');
                        //    $criterion->setStage($stage);
                        $criterion->setType($type)->setWeight(1)->setCName($repoCN->find($parametersForm->get('cName')->getData()));
                        ($type == 0) ? $criterion->setLowerbound(0)->setUpperbound(100)->setStep(1) : $criterion->setLowerbound($lowerbound)->setUpperbound($upperbound)->setStep($step);

                        if ($parametersForm->get('forceCommentCompare')->getData()) {
                            $criterion->setForceCommentSign($parametersForm->get('forceCommentSign')->getData())
                                ->setForceCommentValue($parametersForm->get('forceCommentValue')->getData())
                                ->setForceCommentCompare(true);
                        } else {
                            $criterion->setForceCommentCompare(false)->setForceCommentSign(null)->setForceCommentValue(null);
                        }

                        /*
                        if ($parametersForm->get('target')->getData() != null) {
                            if ($criterion->getTarget() != null) {
                                $target = $criterion->getTarget();
                            } else {
                                $target = new Target;
                                $target->setCriterion($criterion);
                                $criterion->setTarget($target);
                            }
                            $target->setValue($parametersForm->get('target')->getData());
                        } else {
                            $target = $criterion->getTarget();
                            if ($target != null) {
                                $em->remove($target);
                            }
                        }


                        $parametersForm->get('comment') == "" ? $criterion->setComment(null) : $criterion->setComment($parametersForm->get('comment')->getData());
                        */
                        if ($elmt == 'activity') {
                            $criterion->setComplete(true);
                        }
                        $em->persist($criterion);
                        //}

                        $em->flush();
                    }
                }

                $message = (count($activity->getStages()) > 1 || count($activity->getStages()->first()->getCriteria()) > 1) ? 'stages' : 'participants';
                return new JsonResponse(['message' => $message], 200);

            } else {

                $errors = $this->buildErrorArray($parametersForm);
                return $errors;

            }

        } else {
            $name = $parametersForm->get('name')->getData();
            $actionType == 'enrich' && giveActNameToStage($activity, $name);

            //Index used to test entity completeness when form is not submitted
            $j = 0;
            $k = 0;
            $l = 0;

            //User chose to get back
            $stage = $activity->getStages()->first();
            $criterion = $stage->getCriteria()->first();

            // We will at least save what is valid
            // 1 - Create activity parameters and flush...
            if ($parametersForm->get('visibility')->isValid()) {$activity->setVisibility($parametersForm->get('visibility')->getData());} else {if (!$activity->getVisibility()) {$j++;}}
            if ($parametersForm->get('objectives')->isValid()) {$activity->setObjectives($parametersForm->get('objectives')->getData());}
            // if ($parametersForm->get('magnitude')->isValid()) {$activity->setMagnitude($parametersForm->get('magnitude')->getData());}
            if ($parametersForm->get('name')->isValid()) {
                $activity->setName($name);
                $activityFirstStage = $activity->getStages()->first();
                if ($activityFirstStage->getName() == null) {
                    $activityFirstStage->setName($name);
                }
            } else {
                if (!$activity->getName()) {
                    if ($elmt == 'activity') {
                        $activity->setName('Activity ' . ($organization->getActivities()->indexOf($activity) + 1));
                    } else {
                        $activity->setName('Template ' . ($organization->getTemplateActivities()->indexOf($activity) + 1));
                    }
                    $activityFirstStage = $activity->getStages()->first();
                    if ($activityFirstStage->getName() == null) {
                        if (count($activity->getStages()) > 1) {
                            $activityFirstStage->setName('Stage 1');
                        } else {
                            if ($elmt == 'activity') {
                                $activityFirstStage->setName('Activity ' . ($organization->getActivities()->indexOf($activity) + 1));
                            } else {
                                $activityFirstStage->setName('Template ' . ($organization->getTemplateActivities()->indexOf($activity) + 1));
                            }
                        }
                    }
                    $j++;
                }
            }

            $em->persist($activity);
            //2 - ... to define then stage parameters...

            //if ($parametersForm->get('name')->isValid()) {$stage->setName($parametersForm->get('name')->getData());} else {if (!$stage->getName()) {$k++;}}

            if ($simplifiedActivity) {

                if ($parametersForm->get('startdate')->isValid()) {$stage->setStartdate($parametersForm->get('startdate')->getData());}{if (!$stage->getStartdate()) {$k++;}}
                if ($parametersForm->get('enddate')->isValid()) {$stage->setEnddate($parametersForm->get('enddate')->getData());}{if (!$stage->getEnddate()) {$k++;}}
                if ($parametersForm->get('gstartdate')->isValid()) {$stage->setGstartdate($parametersForm->get('gstartdate')->getData());}{if (!$stage->getGStartdate()) {$k++;}}
                if ($parametersForm->get('genddate')->isValid()) {$stage->setGenddate($parametersForm->get('genddate')->getData());}{if (!$stage->getGEnddate()) {$k++;}}
                if ($parametersForm->get('mode')->isValid()) {$stage->setMode($parametersForm->get('mode')->getData());}{if (!$stage->getMode()) {$k++;}}

                $stage->setActivity($activity);
                $stage->setWeight(1);
                $em->persist($stage);
                //3 - ... before finally creating criteria parameters
                $criterion->setName('General');
                $criterion->setStage($stage);

                if ($parametersForm->get('type')->isValid()) {$criterion->setType($parametersForm->get('type')->getData());} else {if (!$criterion->getType()) {$l++;}}
                if ($parametersForm->get('lowerbound')->isValid()) {$criterion->setLowerbound($parametersForm->get('lowerbound')->getData());} else {if (!$criterion->getLowerbound()) {$l++;}}
                if ($parametersForm->get('upperbound')->isValid()) {$criterion->setUpperbound($parametersForm->get('upperbound')->getData());} else {if (!$criterion->getUpperbound()) {$l++;}}
                if ($parametersForm->get('step')->isValid()) {$criterion->setStep($parametersForm->get('step')->getData());} else {if (!$criterion->getStep()) {$l++;}}
                if ($parametersForm->get('cName')->isValid()) {$criterion->setCName($parametersForm->get('cName')->getData());} else {if (!$criterion->getCName()) {$l++;}}
                //$criterion->setWeight($parametersForm->get('weight')->getData());
                if ($parametersForm->get('forceCommentCompare')->getData()) {
                    if ($parametersForm->get('forceCommentSign')->isValid()) {$criterion->setForceCommentSign($parametersForm->get('forceCommentSign')->getData());}
                    if ($parametersForm->get('forceCommentValue')->isValid()) {$criterion->setForceCommentValue($parametersForm->get('forceCommentValue')->getData());}
                    $criterion->setForceCommentCompare(true);
                } else {
                    $criterion->setForceCommentCompare(false)->setForceCommentSign(null)->setForceCommentValue(null);
                }
                $criterion->setWeight(1);
                if ($elmt == 'activity') {
                    ($l == 0) ? $criterion->setComplete(true) : $criterion->setComplete(false);
                }
                $em->persist($criterion);

                // Set activity status, in case we manipulate a non-template activity
                $now = new \DateTime;

                if ($elmt == 'activity') {

                    if ($j != 0 || $k != 0 || $l != 0 || count($activity->getStages()->first()->getCriteria()->first()->getParticipants()) < 1) {
                        $activity->setStatus(-1);
                    }

                    if ($activity->getIsFinalized() == true) {
                        if ($parametersForm->get('gstartdate')->getData() > $now) {
                            $stage->setStatus(0);
                            $activity->setStatus(0);
                        } else {
                            $stage->setStatus(1);
                            $activity->setStatus(1);
                        }
                    } else {
                        $stage->setStatus(-1);
                        $activity->setStatus(-1);
                    }

                }
            }

            $activity->setSaved(new \DateTime);
            $em->persist($activity);
            $em->flush();

            switch ($actionType) {
                case 'enrich':
                case 'criterion':
                    return new JsonResponse(['message' => 'criteria'], 200);
                case 'parameter':
                case 'stage':
                    return new JsonResponse(['message' => 'stages'], 200);
                case 'participant':
                    return new JsonResponse(['message' => 'participants'], 200);
                case 'save':
                case 'back':
                    return new JsonResponse(['message' => 'goBack'], 200);
            }

            /*if ($returnJSON) {
            return new JsonResponse(['message' => 'goBack'], 200);
            }*/

            /*} elseif ($actionType == 'back') {

        return new JsonResponse(['message' => 'goBack'], 200);
        }*/
        }
    }

    // 2 - Display participants to be added

    public function oldAddActivityParticipant(Request $request, Application $app, $elmt, $elmtId)
    {

        // Get all participants (users)

        $em = self::getEntityManager();
        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }
        $usrId = $currentUser->getId();
        $orgId = $currentUser->getOrgId();
        $repoO = $em->getRepository(Organization::class);
        $organization = $repoO->find($orgId);
        $orgEnabledCreatingUser = false;
        $activity = ($elmt == 'activity') ?
        $em->getRepository(Activity::class)->find($elmtId) :
        $em->getRepository(TemplateActivity::class)->find($elmtId);
        $activeModifiableStages = $activity->getActiveModifiableStages();

        // Only administrators or roots can create/update users who have the ability to create users themselves

        $orgOptions = $organization->getOptions();
        foreach ($orgOptions as $orgOption) {
            if ($orgOption->getOName()->getName() == 'enabledUserCreatingUser') {
                $orgEnabledCreatingUser = $orgOption->isOptionTrue();
            }
        }

        $user = $currentUser;
        if ($elmt == 'activity') {
            $activity = $em->getRepository(Activity::class)->find($elmtId);
            $stages = $activity->getActiveModifiableStages();
        } else {
            $activity = $em->getRepository(TemplateActivity::class)->find($elmtId);
            $stages = $activity->getStages();
        }
        $actOrganization = $activity->getOrganization();
        $createTemplateForm = null;
        if ($elmt == 'activity' && $activity->getTemplate() == null) {
            $formFactory = $app['form.factory'];
            $createTemplateForm = $formFactory->create(AddTemplateForm::class, null, ['standalone' => true]);
            $createTemplateForm->handleRequest($request);
        }

        /* Page is fordidden if, by order of importance :
         * 1. User is not root
         * 2. User is not belonging to the same activity organization
         * 3. User is not administrator
         * 4. User is not leader
         * 5. User is not a participant manager
         */

        $hasPageAccess = true;
        if ($elmt == 'activity') {
            if ($user->getRole() != 4 && ($organization != $actOrganization || $user->getRole() != 1 && count($activeModifiableStages) == 0)) {
                $hasPageAccess = false;
            }
        } else {
            if ($user->getRole() != 4 && ($organization != $actOrganization || $user->getRole() != 1)) {
                $hasPageAccess = false;
            }
        }

        if (!$hasPageAccess) {
            return $app['twig']->render('errors/403.html.twig');
        } else {
            /** @var UserRepository $repoU */
            $repoU = $em->getRepository(User::class);
            $repoT = $em->getRepository(Team::class);

            $results = [];
            $orgUsers = $repoU->findAllActiveByOrgId($orgId);
            $clients = $organization->getClients();
            $clientUsers = [];

            foreach ($clients as $client) {
                foreach ($client->getClientOrganization()->getUsers($app) as $clientUser) {
                    $clientUsers[] = $clientUser;
                }
            }

            $orgTeams = $repoT->findBy(['organization' => $organization]);

            foreach ($stages as $stage) {

                // Get all users (from organization)
                $internalUsers = [];
                $externalUsers = [];
                $teams = [];
                foreach ($clientUsers as $user) {
                    $currentUser = $user->toArray($app);
                    $currentUser['participant'] = 0;
                    $currentUser['precomment'] = "";
                    $currentUser['type'] = 1;
                    $currentUser['leader'] = false;
                    $externalUsers[] = $currentUser;
                }

                foreach ($orgUsers as $user) {
                    $currentUser = $user->toArray($app);
                    $currentUser['participant'] = 0;
                    $currentUser['precomment'] = "";
                    $currentUser['type'] = 1;
                    $currentUser['leader'] = false;
                    $internalUsers[] = $currentUser;
                }
                foreach ($orgTeams as $team) {
                    $currentTeam = $team->toArray($app);
                    $currentTeam['participant'] = 0;
                    $currentTeam['precomment'] = "";
                    $currentTeam['type'] = 1;
                    $currentTeam['leader'] = false;
                    $currentTeam['nbRemaining'] = 0;
                    $k = 0;
                    $firstnames = '';
                    $teamUsers = $team->getActiveTeamUsers();
                    $nbTeamUsers = count($teamUsers);
                    foreach ($teamUsers as $teamUser) {
                        if ($k <= $nbTeamUsers - 1 && $k < 3) {
                            $firstnames = ($k == 0) ? $repoU->find($teamUser->getUsrId())->getFirstname() : $firstnames . ', ' . $repoU->find($teamUser->getUsrId())->getFirstname();
                        } elseif ($k == 3 and $nbTeamUsers >= 4) {
                            $currentTeam['nbRemaining'] = $nbTeamUsers - 3;
                            break;
                        }
                        $k++;
                    }
                    $currentTeam['firstnames'] = $firstnames;
                    $teams[] = $currentTeam;
                }

                // Get all participants and associated status
                foreach ($stage->getParticipants() as $participant) {

                    if ($elmt == 'template') {
                        $terminalCondition = true;
                    } else if ($participant->getDeleted() == null) {
                        $terminalCondition = true;
                    } else {
                        $terminalCondition = false;
                    }

                    if ($participant->getTeam()) {

                        foreach ($teams as $key => $value) {

                            if (($participant->getTeam()->getId() == $teams[$key]['id']) && $terminalCondition) {
                                $teams[$key]['participant'] = 1;
                                $teams[$key]['precomment'] = $participant->getPrecomment();
                                $teams[$key]['type'] = $participant->getType();
                                $teams[$key]['leader'] = $participant->isLeader();

                                break;
                            }
                        }

                    } else {

                        foreach ($internalUsers as $key => $value) {

                            if (($participant->getUsrId() == $internalUsers[$key]['id']) && $terminalCondition) {
                                $internalUsers[$key]['participant'] = 1;
                                $internalUsers[$key]['precomment'] = $participant->getPrecomment();
                                $internalUsers[$key]['type'] = $participant->getType();
                                $internalUsers[$key]['leader'] = $participant->isLeader();
                                break;
                            }
                        }

                        foreach ($externalUsers as $key => $value) {

                            if (($participant->getUsrId() == $externalUsers[$key]['id']) && $terminalCondition) {
                                $externalUsers[$key]['participant'] = 1;
                                $externalUsers[$key]['precomment'] = $participant->getPrecomment();
                                $externalUsers[$key]['type'] = $participant->getType();
                                $externalUsers[$key]['leader'] = $participant->isLeader();
                                break;
                            }
                        }

                    }
                }

                // Place activity participants above (sort on the participant subkey)
                MasterController::sksort($internalUsers, 'participant');
                if ($externalUsers != null) {
                    MasterController::sksort($externalUsers, 'participant');
                }

                $results[] = [
                    'id' => $stage->getId(),
                    'name' => $stage->getName(),
                    'internalUsers' => $internalUsers,
                    'teams' => $teams,
                    'externalUsers' => $externalUsers,
                    'mode' => $stage->getMode(),
                ];

            }

            try {
                return $app['twig']->render('participants_list.html.twig',
                    [
                        'stages' => $results,
                        'actName' => $activity->getName(),
                        'activity' => $activity,
                        'elmt' => $elmt,
                        'app' => $app,
                        'createTemplateForm' => ($createTemplateForm === null) ?: $createTemplateForm->createView(),
                        'orgEnabledCreatingUser' => $orgEnabledCreatingUser,
                    ]);} catch (\Exception $e) {
                print_r($e->getMessage());
                die;
            }
        }
    }
    /*
    try {

    } catch (\Eception $e) {
    print_r($e->getMessage());
    die;
    }
     */

    /**
     * @param Request $request
     * @param Application $app
     * @param $elmt
     * @param $elmtId
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{elmt}/{elmtId}/participants", name="activityParticipants")
     */
    public function newAddActivityParticipant(Request $request, Application $app, $elmt, $elmtId)
    {
        $user = self::getAuthorizedUser();
        if (!$user) {
            return $this->redirectToRoute('login');
        }
        $userRole = $user->getRole();
        $em = self::getEntityManager();
        $elmtIsActivity = $elmt === 'activity';
        /** @var Activity|TemplateActivity */
        $activity = $em->getRepository(
            $elmt === 'activity' ? Activity::class : TemplateActivity::class
        )->find($elmtId);

        $organization = $user->getOrganization();
        /** @var Organization */
        $actOrganization = $activity->getOrganization();
        $activeModifiableStages = $activity->getActiveModifiableStages();

        $userIsNotRoot = $userRole != 4;
        $userIsAdmin = $userRole == 1;
        $userIsAM = $userRole == 2;
        $userIsCollab = $userRole == 3;
        $actBelongsToDifferentOrg = $organization != $actOrganization;
        $actHasNoActiveModifiableStages = count($activeModifiableStages) == 0;
        $hasPageAccess = true;
        if ($elmtIsActivity) {
            if ($userIsNotRoot and ($actBelongsToDifferentOrg or !$userIsAdmin and $actHasNoActiveModifiableStages)) {
                $hasPageAccess = false;
            }
        } else {
            if ($userIsCollab or ($userIsAM or $userIsAdmin) and $actBelongsToDifferentOrg) {
                $hasPageAccess = false;
            }
        }

        if (!$hasPageAccess) {
            return $app['twig']->render('errors/403.html.twig');
        }

        $mailSettings = [];

        /** @var FormFactory */
        $formFactory = $app['form.factory'];
        $manageStageParticipantsForm = $formFactory->create(ManageStageParticipantsForm::class, $activity, ['standalone' => true, 'elmt' => $elmt, 'organization' => $organization]);
        $manageStageParticipantsForm->handleRequest($request);
        $createTemplateForm = null;
        if ($elmt == 'activity' && $activity->getTemplate() == null) {
            $createTemplateForm = $formFactory->create(AddTemplateForm::class, null, ['standalone' => true]);
            $createTemplateForm->handleRequest($request);
        }

        if ($manageStageParticipantsForm->isSubmitted()) {

            // If user has clicked in a button, but activity has been finalized (due to participant deletion)
            if ($elmt == 'activity' && $activity->getStatus() >= 2) {
                return $this->redirectToRoute('myActivities');
            }

            if ($manageStageParticipantsForm->get('finalize')->isClicked() && $elmt == 'activity') {

                $nbTotalStages = count($activity->getStages());

                foreach ($activity->getActiveModifiableStages() as $stage) {
                    // add newly added people that were not AJAX-added
                    $newlyAddedParticipantsCollection = $stage->getUniqueParticipations()->filter(function (ActivityUser $u) {
                        return !$u->getId();
                    });
                    /** @var ActivityUser[] */
                    $newlyAddedParticipants = $newlyAddedParticipantsCollection->getValues();

                    foreach ($newlyAddedParticipants as $participant) {
                        foreach ($stage->getCriteria() as $c) {
                            $theParticipant = clone $participant;
                            $theParticipant->setCriterion($c);
                            $em->persist($theParticipant);
                        }
                    }
                    $em->flush();

                    // 1 - Sending participants mails if necessary
                    // Parameter for subject mail title
                    if ($nbTotalStages > 1) {
                        $mailSettings['stage'] = $stage;
                    } else {
                        $mailSettings['activity'] = $activity;
                    }

                    $notYetMailedParticipants = $stage->getUniqueParticipations()->filter(function (ActivityUser $u) {
                        return !$u->getisMailed();
                    });
                    /** @var ActivityUser[] */
                    $participants = $notYetMailedParticipants->getValues();

                    foreach ($participants as $participant) {
                        self::sendMail($app, [$participant->getDirectUser()], 'activityParticipation', $mailSettings);
                        $participant->setIsMailed(true);
                        $em->persist($participant);
                    }
                    $em->flush();
                }

                if ($activity->getIsFinalized() == false) {
                    $activity->setIsFinalized(true);
                    $em->persist($activity);
                }
                $em->flush();

            }

            if ($elmt == 'activity') {
                if ($activity->getIsFinalized()) {

                    // 2 - Updating activity status if necessary

                    $tomorrowDate = new \DateTime;
                    $tomorrowDate->add(new \DateInterval('P1D'));

                    $yesterdayDate = new \DateTime;
                    $yesterdayDate->sub(new \DateInterval('P1D'));
                    $k = 0;
                    $p = 0;

                    foreach ($activity->getActiveStages() as $stage) {
                        if ($stage->getGStartDate() > $tomorrowDate) {
                            $k++;
                        }
                        if ($stage->getGEndDate() <= $yesterdayDate) {
                            $p++;
                        }
                    }

                    $nbActiveStages = count($activity->getActiveStages());

                    // If every grading stage starts in the future...
                    if ($k == $nbActiveStages) {
                        $activity->setStatus(0);
                    } else {
                        //..else if not every grading stage ends in the past...
                        if ($p != $nbActiveStages) {
                            $activity->setStatus(1);
                        } else {
                            $activity->setStatus(-1);
                        }
                    }

                }
            }

            $em->flush();

            $parameters = ['elmt' => $elmt, 'elmtId' => $elmtId];
            if (array_key_exists('participant', $_POST['manage_stage_participants_form'])) {
                $path = 'activityParticipants';
            } else if (array_key_exists('parameter', $_POST['manage_stage_participants_form'])) {
                $path = 'oldActivityDefinition';
            } else if ($manageStageParticipantsForm->get('back')->isClicked() || array_key_exists('save', $_POST['manage_stage_participants_form']) || $manageStageParticipantsForm->get('finalize')->isClicked()) {
                $path = ($elmt == 'activity') ? 'myActivities' : 'manageTemplates';
                $parameters = [];
            } else if (array_key_exists('stage', $_POST['manage_stage_participants_form'])) {
                $path = 'activityStages';
            } else if ($manageStageParticipantsForm->get('previous')->isClicked() || array_key_exists('criterion', $_POST['manage_stage_participants_form'])) {
                $path = 'activityCriteria';
            }

            return $app->redirect($app['url_generator']->generate($path, $parameters));
        }

        /** @var UserRepository */
        $userRepo = $em->getRepository(User::class);
        $usersWithPic[0] = '/lib/img/no-picture.png';
        foreach ($userRepo->usersWithPicture() as $u) {
            $id = $u->getId();
            $pic = $u->getPicture();
            $usersWithPic[$id] = "/lib/img/$pic";
        }

        return $app['twig']->render(
            'activity_define_participants.twig',
            [
                'form' => $manageStageParticipantsForm->createView(),
                'createTemplateForm' => ($createTemplateForm === null) ?: $createTemplateForm->createView(),
                'userPics' => json_encode($usersWithPic),
            ]
        );
    }

    /**
     * @param Application $app
     * @param Request $request
     * @param int $stgId
     * @param string $elmtType
     * @param int $elmtId
     * @return JsonResponse|void
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{elmtType}/stage/{stgId}/participant/validate/{elmtId}", name="validateParticipant")
     */
    public function validateParticipantAction(
        Application $app,
        Request $request,
        int $stgId,
        string $elmtType,
        int $elmtId
    ) {
        $currentUser = self::getAuthorizedUser();
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
        $pElmtType = $request->get('pElmtType');
        /** @var int */
        $pElmtId = $request->get('pElmtId');

        $em = self::getEntityManager();
        switch($elmtType){
            case 'iprocess' :
                $repoS = $em->getRepository(IProcessStage::class);
                $repoAU = $em->getRepository(IProcessActivityUser::class);
                break;
            case 'activity' :
                $repoS = $em->getRepository(Stage::class);
                $repoAU = $em->getRepository(ActivityUser::class);
                break;
            case 'template' :
                $repoS = $em->getRepository(TemplateStage::class);
                $repoAU = $em->getRepository(TemplateActivityUser::class);
                break;
        }
        $repoU = $em->getRepository(User::class);
        $repoPEntity = $pElmtType == 'user' ? $repoU : $em->getRepository(Team::class);
        /** @var User|Team */
        $pElement = $repoPEntity->find($pElmtId);

        $repoG = $em->getRepository(Grade::class);

        /** @var Stage|TemplateStage|IProcessStage */
        $stage = $repoS->find($stgId);
        /** @var Activity|TemplateActivity|InstitutionProcess */
        $element = $elmtType != 'iprocess' ? $stage->getActivity() : $stage->getInstitutionProcess();
        $activityOrganization = $element->getOrganization();

        if (!$stage->isModifiable()) {
            throw new Exception('unauthorized');
        }

        /** @var ActivityUser|IProcessActivityUser|TemplateActivityUser|null */
        $participant = $repoAU->find($elmtId);


        if (!$participant) {

            // Checking if there is compatibility user/team, otherwise return exception
            if($pElmtType == 'user'){

                $doublonParticipant = $stage->getParticipants()->filter(function($p) use ($pElmtId){
                    return $p->getUsrId() == $pElmtId;
                })->first();

                if($doublonParticipant){
                    return new JsonResponse(['msg' => 'duplicateWithTeam', 'name' => $doublonParticipant->getTeam()->getName()],500);
                }

            } else {

                $doublonParticipant = $stage->getParticipants()->filter(function($p) use ($pElement){
                        return $pElement->getTeamUsers()->exists(function(int $i, TeamUser $tu) use ($p){
                            return $tu->getUsrId() == $p->getUsrId();
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
                'gradedUsrId' => $participant->getUsrId(),
                'gradedTeaId' => $participant->getTeam() ? $participant->getTeam()->getId() : null
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
            if($pElmtType == 'user'){
                /** @var ActivityUser[]|IProcessActivityUser[]|TemplateActivityUser[] */
                $participations = $repoAU->findBy([
                    'stage' => $stage,
                    'usrId' => $participant->getUsrId(),
                    'team' => null,
                ]);
            } else {
                 /** @var ActivityUser[]|IProcessActivityUser[]|TemplateActivityUser[] */
                 $participations = $repoAU->findBy([
                    'stage' => $stage,
                    'team' => $participant->getTeam(),
                ]);
            }
            $iterableElements = $participations;
        }

        if($elmtType == 'activity' && ($participant == null || $participant->getType() == 0 && $type != 0)){

            // Checking if we need to unvalidate participations (we decide to unlock all stage participations and not only the modified one)
            $completedStageParticipations = $stage->getParticipants()->filter(function(ActivityUser $p){
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
            if ($iterableElement instanceof ActivityUser || $iterableElement instanceof IProcessActivityUser || $iterableElement instanceof TemplateActivityUser) {
                $participation = $iterableElement;
                $criterion = $participation->getCriterion();
                $consideredParticipations[] = $participation;

            } else {

                $consideredParticipations = [];

                switch($elmtType){
                    case 'activity' :
                        if($pElmtType == 'user'){
                            $participation = new ActivityUser;
                            $consideredParticipations[] = $participation;
                        } else {
                            foreach($pElement->getCurrentTeamUsers() as $currentTeamUser){
//                                var_dump($currentTeamUser->getUsrId());
                                $participation = new ActivityUser;
                                $participation->setUsrId($currentTeamUser->getUsrId())
                                    ->setExtUsrId($currentTeamUser->getExtUsrId());
                                $consideredParticipations[] = $participation;
                            }
                        }
                        break;
                    case 'iprocess' :
                        if($pElmtType == 'user'){
                            $participation = new IProcessActivityUser;
                            $consideredParticipations[] = $participation;
                        } else {
                            foreach($pElement->getCurrentTeamUsers() as $currentTeamUser){
                                $participation = new IProcessActivityUser;
                                $participation->setUsrId($currentTeamUser->getUsrId())
                                    ->setExtUsrId($currentTeamUser->getExtUsrId());
                                $consideredParticipations[] = $participation;
                            }
                        }
                        break;
                    case 'template' :
                        if($pElmtType == 'user'){
                            $participation = new TemplateActivityUser;
                            $consideredParticipations[] = $participation;
                        } else {
                            foreach($pElement->getCurrentTeamUsers() as $currentTeamUser){
                                $participation = new TemplateActivityUser;
                                $participation->setUsrId($currentTeamUser->getUsrId())
                                    ->setExtUsrId($currentTeamUser->getExtUsrId());
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

                if($pElmtType == 'team'){
                    $consideredParticipation->setTeam($pElement);
                } else {
                    $consideredParticipation->setUsrId($pElement->getId());
                }


                $userOrganization = $pElement->getOrganization();
                $currentUserOrganization = $currentUser->getOrganization();

                if($userOrganization != $currentUserOrganization){
                    /** @var Client */
                    $client = $currentUserOrganization->getClients()->filter(function(Client $c) use ($userOrganization){
                        return $c->getClientOrganization() == $userOrganization;
                    })->first();
                    $externalUsrId = $client->getExternalUsers()->filter(function(ExternalUser $e) use ($pElement){
                        return $e->getUser() == $pElement;
                    })->first()->getId();

                    $consideredParticipation->setExtUsrId($externalUsrId);
                }


                if($leader){$consideredParticipation->setLeader($leader);}

                if ($consideredParticipation instanceof IProcessActivityUser) {
                    $consideredParticipation->setInstitutionProcess($element);
                } else {
                    $consideredParticipation->setActivity($element);
                }

                if ($precomment) {
                    $consideredParticipation->setPrecomment($precomment);
                } else if ($consideredParticipation->getPrecomment() !== null) {
                    $consideredParticipation->setPrecomment(null);
                }

                if ($consideredParticipation instanceof ActivityUser) {
                    //$consideredParticipation->setIsMailed(false);

                    if ($leader) {
                        // Removing leadership to all old previous (criterion) leading participations

                        /** @var Criterion|Stage */
                        $queryableElmt = $criterion ?: $stage;

                        $previousOwningParticipants = $queryableElmt->getParticipants()->filter(function(ActivityUser $p) use ($consideredParticipation){
                            return ($consideredParticipation->getTeam() ?
                                $p->getTeam() != $consideredParticipation->getTeam() :
                                $p->getUsrId() != $consideredParticipation->getUsrId())
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

                $criterion ? $criterion->addParticipant($consideredParticipation) : $stage->addParticipant($consideredParticipation);

            }

            if($iterableElement instanceof Criterion){
                $stage->addCriterion($criterion);
            }

        }

        /*
        $mailed = null;
        $finalizable = (
            $elmtType == 'activity'
            && $participation && $participation->getType() != 0
            && (count($stage->getCriteria()) > 0 || count($stage->getCriteria()) > 0)
        );
        */

        if ($elmtType == 'activity' and $element->getStatus() === 1) {
            $mailedUsrIds = [];
            $recipients = [];

            foreach($consideredParticipations as $consideredParticipation){


                if(!$consideredParticipation->getisMailed()){

                    $participationUsrId = $consideredParticipation->getUsrId();

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
                'picture' => $pElmtType == 'user' ?
                    ($pElement->getPicture() ?? 'no-picture.png') :
                    ($pElement->getPicture() ? 'team/'.$pElement->getPicture() : 'team/no-picture.png')
            ],
            'canSetup' => !$element->getActiveModifiableStages()->isEmpty(),
        ], 200);
    }

    /**
     * @param Application $app
     * @param Request $request
     * @param int $teaId
     * @param int $tusId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/team/{teaId}/team-user/validate/{tusId}", name="validateTeamUser")
     */
    public function validateTeamUserAction(
        Application $app,
        Request $request,
        int $teaId,
        int $tusId
    ) {
        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            throw new Exception('current user is null');
        }

        /** @var int */
        $usrId = $request->get('user');
        /** @var bool */
        $leader = (bool) $request->get('leader');

        $em = self::getEntityManager();
        $repoU = $em->getRepository(User::class);
        $repoT = $em->getRepository(Team::class);
        $repoTU = $em->getRepository(TeamUser::class);
        $repoAU = $em->getRepository(ActivityUser::class);

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

        /** @var TeamUser|null */
        $teamUser = null;

        if($tusId != 0){
            $teamUser = $repoTU->find($tusId);
        } else {
            $teamUser = $repoTU->findOneBy(['usrId' => $usrId, 'team' => $team]) ?: new TeamUser;
        }

        /** @var User */
        $addedUser = $repoU->find($usrId);

        if($tusId == 0 || $teamUser->isDeleted()){

            // Sending a welcome to the new team joiner
            $settings = [];
            $addedRecipients = [$addedUser];
            if (count($addedRecipients) > 0) {
                $settings['team'] = $team;
                self::sendMail($app, $addedRecipients, 'teamCreation', $settings);
            }

            //We unvalidate team user participations of non-completed activities, in order for them to grade team newcomer
            $teamParticipations = new ArrayCollection($repoAU->findBy(['team' => $teaId], ['activity' => 'ASC']));
            $uncompletedTeamParticipations = $teamParticipations->filter(function (ActivityUser $participation) {
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
                        $activityUser = new ActivityUser;
                        $activityUser->setLeader($line->isLeader())
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
                        $em->persist($activityUser);
                    }
                }
            }
        }

        $teamUser->setLeader($leader)
        ->setUsrId($usrId)
        ->setCreatedBy($currentUser->getId());

        if($teamUser->isDeleted()){
            $teamUser->toggleIsDeleted();
        }

        if($addedUser->getOrgId() != $currentUser->getOrgId()){
            $repoC = $em->getRepository(Client::class);
            $externalUser = $repoC->findOneBy(['organization' => $currentUser->getOrganization(), 'clientOrganization' => $addedUser->getOrganization()])
                ->getExternalUsers()->filter(function(ExternalUser $e) use ($addedUser){
                    return $e->getUser() == $addedUser;
                })->first();
            $teamUser->setExtUsrId($externalUser->getId());
        }

        $team->addTeamUser($teamUser);
        $em->persist($team);
        $em->flush();

        return new JsonResponse([
            'tid' => $team->getId(),
            'eid' => $teamUser->getId(),
            'user' => [
                'picture' => $addedUser->getPicture() ?? 'no-picture.png'
            ],
            'canSetup' => $team->isModifiable(),
        ], 200);

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $stgId
     * @param $elmtType
     * @param $elmtId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{_locale}/{elmtType}/stage/{stgId}/participant/delete/{elmtId}", name="deleteParticipant")
     */
    public function deleteParticipantAction(Request $request, Application $app, $stgId, $elmtType, $elmtId)
    {

        $em = self::getEntityManager();
        $repoO = $em->getRepository(Organization::class);

        switch ($elmtType) {
            case 'activity':
                $repoS = $em->getRepository(Stage::class);
                $repoAU = $em->getRepository(ActivityUser::class);
                $stage = $repoS->find($stgId);
                $stageOrganization = $stage->getActivity()->getOrganization();
                break;
            case 'template':
                $repoS = $em->getRepository(TemplateStage::class);
                $repoAU = $em->getRepository(TemplateActivityUser::class);
                $stage = $repoS->find($stgId);
                $stageOrganization = $stage->getActivity()->getOrganization();
                break;
            case 'iprocess':
                $repoS = $em->getRepository(IProcessStage::class);
                $repoAU = $em->getRepository(IProcessActivityUser::class);
                $stage = $repoS->find($stgId);
                $stageOrganization = $stage->getInstitutionProcess()->getOrganization();
                break;
        }
        $currentUser = self::getAuthorizedUser();
        $currentUserOrganization = $repoO->find($currentUser->getOrgId());
        $stageLeader = $repoAU->findOneBy(['stage' => $stage,'leader' => true]);
        $userStageLeader = $repoAU->findOneBy(['stage' => $stage,'leader' => true, 'usrId' => $currentUser->getId()]);
        $hasUserInfGrantedRights = ($stageLeader && $userStageLeader || !$stageLeader && $stage->getMasterUserId() == $currentUser->getId());
        $hasPageAccess = true;

        if ($currentUser->getRole() != 4 && ($stageOrganization != $currentUserOrganization || $currentUser->getRole() != 1 && !$hasUserInfGrantedRights)) {
            $hasPageAccess = false;
        }

        if (!$hasPageAccess) {
            return $app['twig']->render('errors/403.html.twig');
        } else {

            $participant = $repoAU->find($elmtId);
            $stage->removeUniqueParticipation($participant);
            $em->persist($stage);
            $em->flush();
            if ($elmtType == 'activity') {
                $activeParticipants = $stage->getParticipants()->filter(function (ActivityUser $p) {
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
     * @param Application $app
     * @param $tusId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/remove-team-user/{tusId}", name="deleteTeamUser")
     */
    public function deleteTeamUserAction(Request $request, Application $app, $tusId)
    {

        $em = self::getEntityManager();
        $repoO = $em->getRepository(Organization::class);
        $repoU = $em->getRepository(User::class);
        $repoTU = $em->getRepository(TeamUser::class);
        /** @var TeamUser */
        $teamUser = $repoTU->find($tusId);
        $teamUserUsrId = $teamUser->getUsrId();
        /** @var Team */
        $team = $teamUser->getTeam();
        $teamOrganization = $team->getOrganization();
        $currentUser = self::getAuthorizedUser();
        $currentUserOrganization = $repoO->find($currentUser->getOrgId());
        $teamLeader = $repoTU->findOneBy(['team' => $team,'leader' => true]);
        $userTeamLeader = $repoTU->findOneBy(['team' => $team, 'leader' => true, 'usrId' => $currentUser->getId()]);
        $hasUserInfGrantedRights = ($teamLeader && $userTeamLeader || !$teamLeader && $team->getCreatedBy() == $currentUser->getId());
        $hasPageAccess = true;

        if ($currentUser->getRole() != 4 && ($teamOrganization != $currentUserOrganization || $currentUser->getRole() != 1 && !$hasUserInfGrantedRights)) {
            $hasPageAccess = false;
        }

        if (!$hasPageAccess) {
            return $app['twig']->render('errors/403.html.twig');
        } else {

            $teamUser
                ->setDeleted(new \DateTime)
                ->setIsDeleted(true);
            $em->persist($teamUser);
            $em->flush();

            $settings['team'] = $team;
            $removedUser = $repoU->find($teamUserUsrId);
            self::sendMail($app, [$removedUser], 'teamUserRemoval', $settings);

            return new JsonResponse(['message' => 'Success!'], 200);
        }

    }



    public function newInsertParticipantsAction(Request $request, Application $app, $elmt, $elmtId, $actionType, $returnJSON = true)
    {

        // Get all participants (users)
        $em = self::getEntityManager();
        $repoU = $em->getRepository(User::class);
        $repoT = $em->getRepository(Team::class);

        if ($elmt == 'activity') {
            $activity = $em->getRepository(Activity::class)->find($elmtId);
            $repoAU = $em->getRepository(ActivityUser::class);
            $repoG = $em->getRepository(Grade::class);
        } else {
            $activity = $em->getRepository(TemplateActivity::class)->find($elmtId);
            $repoAU = $em->getRepository(TemplateActivityUser::class);
        }

        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }
        $mailSettings = [];
        $mailSettings['addedParticipants'] = [];

        // Insert saved timestamp in case activity is saved
        if ($actionType == 'save') {
            $activity->setSaved(new \DateTime);
            $em->persist($activity);
        }
    }

    //AJAX call which inserts users in created stages

    /**
     * @param Request $request
     * @param Application $app
     * @param $elmt
     * @param $elmtId
     * @param $actionType
     * @param bool $returnJSON
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/{elmt}/{elmtId}/participants/{actionType}", name="ajaxParticipantsAdd")
     */
    public function oldInsertParticipantsAction(Request $request, Application $app, $elmt, $elmtId, $actionType, $returnJSON = true)
    {
        $firstFinalization = false;
        if ($actionType != 'back' && $actionType != 'previous') {

            // Get all participants (users)
            $em = self::getEntityManager();
            $repoU = $em->getRepository(User::class);
            $repoT = $em->getRepository(Team::class);

            if ($elmt == 'activity') {
                $activity = $em->getRepository(Activity::class)->find($elmtId);
                $repoAU = $em->getRepository(ActivityUser::class);
                $repoG = $em->getRepository(Grade::class);
            } else {
                $activity = $em->getRepository(TemplateActivity::class)->find($elmtId);
                $repoAU = $em->getRepository(TemplateActivityUser::class);
            }

            $currentUser = self::getAuthorizedUser();
            if (!$currentUser) {
                return $this->redirectToRoute('login');
            }
            $mailSettings = [];
            $mailSettings['addedParticipants'] = [];

            // Insert saved timestamp in case activity is saved
            if ($actionType == 'save') {
                $activity->setSaved(new \DateTime);
                $em->persist($activity);
            }

            //print_r($_POST['stages']);
            //die;

            $activityData = json_decode($_POST['activity'], true);

            $unvalidatedElmts = [];
            $nbStageParticipantsPbs = 0;

            foreach ($activityData['stages'] as $stageDataJSON) {

                $addedStageUsers = [];

                $stageData = json_decode($stageDataJSON, true);

                $stgId = $stageData['stgId'];

                // Get submitted stage
                $stage = ($elmt == 'activity') ?
                $em->getRepository(Stage::class)->find($stgId) :
                $em->getRepository(TemplateStage::class)->find($stgId);
                $k = 0;

                $usersData = [];
                $teamsData = [];
                $teams = [];
                $teamsUsrIds = [];
                $allParticipants = [];
                $teamUsersIds = [];
                $gradingParticipantsTrigger = false;
                $validatedParticipantTypes = false;
                $hasTPs = false;
                $hasT = false;
                $hasP = false;
                $mode = $stageData['mode'];

                // Get submitted teams

                foreach ($stageData['participants'][1] as $teamJSON) {

                    // If team has no grading type, means that it's a pure phase grading, we set its type as TP
                    if (!isset($teamData['ttype'])) {
                        $teamData['ttype'] = 0;
                    }

                    $teamData = json_decode($teamJSON, true);
                    $teamsData[] = $teamData;
                    $allParticipants[] = $teamData;
                    if (!$hasTPs && $teamData['ttype'] == 0) {
                        $hasTPs = true;
                    }
                    if ($teamData['ttype'] == 1 || ($teamData['ttype'] == -1 && $hasTPs)) {
                        $gradingParticipantsTrigger = true;
                    }
                    $team = $repoT->find($teamData['teaId']);
                    $teams[] = $team;
                    foreach ($team->getActiveTeamUsers() as $teamUser) {

                        if (in_array($teamUser->getUsrId(), $teamsUsrIds)) {
                            $user = $teamUser->getUser();
                            $team1 = $teamUser->getTeam()->getName();
                            $team2 = $em->getRepository(TeamUser::class)->find($teamUsersIds[array_search($teamUser->getUsrId(), $teamsUsrIds)])->getTeam()->getName();
                            return new JsonResponse(['message' => 'duplicityTeamError', 'firstname' => $user->getFirstName(), 'lastname' => $user->getLastName(), 'team1' => $team1, 'team2' => $team2, 'stageName' => $stage->getName()], 200);
                        }

                        $teamsUsrIds[] = $teamUser->getUsrId();
                        $teamUsersIds[] = $teamUser->getId();

                    }
                }

                // Get submitted participants

                foreach ($stageData['participants'][0] as $userJSON) {

                    $userData = json_decode($userJSON, true);

                    // If user has no grading type, means that it's a pure phase grading, we set its type as TP
                    if (!isset($userData['utype'])) {
                        $userData['utype'] = 0;
                    }
                    //return $userData;
                    $usersData[] = $userData;
                    $allParticipants[] = $userData;
                    if (!$hasTPs && $userData['utype'] == 0) {
                        $hasT = true;
                    }
                    if (!$hasTPs && $userData['utype'] == -1) {
                        $hasP = true;
                    }
                    if ($hasP && $hasT) {
                        $hasTPs = true;
                    }
                    if ($userData['utype'] == 1 || ($userData['utype'] <= 0 && $hasTPs) || $mode == 0) {
                        $gradingParticipantsTrigger = true;
                    }
                }

                if ($elmt == 'activity' && count($teamsData) + count($usersData) < 1) {
                    if ($actionType == 'next') {
                        return new JsonResponse(['message' => 'missingParticipants', 'stageName' => $stage->getName()], 200);
                    }
                }

                if (!$gradingParticipantsTrigger) {
                    if ($actionType == 'next') {
                        return new JsonResponse(['message' => 'missingGradingParticipants', 'stageName' => $stage->getName()], 200);
                    }
                }

                // Test if there is a possible duplicate, acting as team member and individual
                if (MasterController::array_doublon($teamsUsrIds)) {
                    $user = $repoU->find(MasterController::array_doublon($teamsUsrIds)[0]);
                    return new JsonResponse(['message' => 'duplicityError', 'firstname' => $user->getFirstName(), 'lastname' => $user->getLastName()], 200);
                }

                foreach ($teams as $team) {
                    foreach ($team->getActiveTeamUsers() as $teamUsr) {
                        foreach ($usersData as $userData) {
                            if ($userData['usrId'] == $teamUsr->getUsrId()) {
                                $user = $repoU->find($userData['usrId']);
                                return new JsonResponse(['message' => 'duplicityError', 'firstname' => $user->getFirstName(), 'lastname' => $user->getLastName()], 200);
                            }
                        }
                    }
                }

                //Set deleted date for removed participants
                foreach ($stage->getParticipants() as $existingParticipant) {
                    if ($existingParticipant->getTeam() == null) {

                        $deleted = 1;

                        foreach ($usersData as $userData) {
                            if ($userData['usrId'] == $existingParticipant->getUsrId()) {
                                $deleted = 0;
                                break;
                            }
                        }
                        if ($deleted == 1) {

                            foreach ($stage->getCriteria() as $criterion) {

                                // Remove deleted user (and associated grades intented to the participant)

                                if ($elmt == 'activity') {

                                    $removableGrades = $repoG->findBy(['criterion' => $criterion, 'gradedUsrId' => $existingParticipant->getUsrId()]);

                                    foreach ($removableGrades as $removableGrade) {
                                        $criterion->removeGrade($removableGrade);
                                    }
                                }

                                $criterion->removeParticipant($existingParticipant);
                                $em->persist($criterion);
                            }
                            $em->flush();
                        }
                    } else {
                        $deleted = 1;
                        foreach ($teams as $team) {
                            if ($team->getId() == $existingParticipant->getTeam()->getId()) {
                                $deleted = 0;
                                break;
                            }
                        }
                        if ($deleted == 1) {

                            foreach ($stage->getCriteria() as $criterion) {

                                // Remove deleted team user (and associated grades intented to the participant)

                                $removableTeamGrades = new ArrayCollection($repoG->findBy(['criterion' => $criterion, 'gradedTeaId' => $existingParticipant->getTeam()->getId()]));
                                if (!$removableTeamGrades->isEmpty()) {
                                    foreach ($removableTeamGrades as $removableTeamGrade) {
                                        $criterion->removeGrade($removableTeamGrade);
                                    }
                                    $em->persist($criterion);
                                }

                                $removableGrades = $repoG->findBy(['criterion' => $criterion, 'gradedUsrId' => $existingParticipant->getUsrId()]);

                                foreach ($removableGrades as $removableGrade) {
                                    $criterion->removeGrade($removableGrade);
                                }

                                $criterion->removeParticipant($existingParticipant);
                                $em->persist($criterion);

                            }
                        }
                    }

                    // Check whether the stage is computable after these removals
                    if ($elmt == 'activity') {
                        $this->checkStageComputability($request, $app, $stage);
                    }
                }

                $em->flush();

                $nbLeaders = 0;

                // We ensure that there is at least one stage leader
                foreach ($allParticipants as $participant) {
                    if (isset($participant['uleader'])) {
                        if ($participant['uleader']) {
                            $nbLeaders = 1;
                            break;
                        }
                    } else {
                        if ($participant['tleader']) {
                            $nbLeaders = 1;
                            break;
                        }
                    }
                }

                // No participants were added in any stages, meaning that the stage has just been created

                if ($actionType == 'next' && $nbLeaders == 0) {
                    return new JsonResponse(['message' => 'missingLeader'], 200);
                }

                $unvalidatedElmt = [];

                foreach ($stage->getCriteria() as $criterion) {

                    $criterionParticipants = $criterion->getParticipants();
                    $unvalidatedParticipantUserIds = [];
                    $unvalidatedParticipantTeamUserIds = [];

                    // Get submitted participants

                    $unvalidateGradesTrigger = false;
                    $unvalidateTeamGradesTrigger = false;

                    foreach ($usersData as $userData) {

                        // $participant = json_decode($participantJSON, true);
                        $j = 0;

                        foreach ($criterionParticipants as $existingParticipant) {
                            if ($existingParticipant->getUsrId() == $userData['usrId'] && $existingParticipant->getCriterion() == $criterion) {
                                $j = 1;
                                $k = 1;
                                if ($elmt == 'activity') {
                                    if ($existingParticipant->getType() != -1 && $existingParticipant->getIsMailed() == false) {
                                        $addedStageUsers[] = $repoU->find($userData['usrId']);
                                        $mailSettings['addedParticipants'][] = $existingParticipant;
                                    }
                                }
                                break;
                            }
                        }

                        if ($j == 1) {
                            $participant = $existingParticipant;
                        } else {
                            if ($elmt == 'template') {
                                $participant = new TemplateActivityUser;
                            } else {
                                $participant = new ActivityUser;
                                $stageUser = $repoU->find($userData['usrId']);
                                if ($stageUser->getEmail() !== null) {
                                    $addedStageUsers[] = $repoU->find($userData['usrId']);
                                    $mailSettings['addedParticipants'][] = $participant;
                                    $participant->setIsMailed(false);
                                }

                                // If a nonTP participant is added while a participant has already locked his grades, we "unlock" his participation (note he will only be able to grade these fresh new added participants)
                                if (!$unvalidateGradesTrigger && $userData['utype'] == 1) {
                                    $unvalidateGradesTrigger = true;
                                    $validatedParticipations = $criterionParticipants->matching(Criteria::create()->where(Criteria::expr()->eq("status", 3)));
                                    if ($validatedParticipations != null) {

                                        foreach ($validatedParticipations as $validatedParticipation) {
                                            $unvalidatedParticipantUserIds[] = $validatedParticipation->getUsrId();
                                            $validatedParticipation->setStatus(2);
                                            $em->persist($validatedParticipation);
                                        }
                                    }
                                }
                            }
                        };
                        $participant->setActivity($activity)->setStage($stage)->setUsrId($userData['usrId'])->setType($userData['utype'])->setLeader($userData['uleader'])->setCriterion($criterion);

                        if ($elmt == 'activity') {
                            $grades = $repoG->findBy(['criterion' => $criterion, 'gradedUsrId' => $userData['usrId']]);
                            foreach ($grades as $grade) {
                                $grade->setType($userData['utype']);
                                $em->persist($grade);
                            }
                        }

                        $participant->setPrecomment((isset($userData['uprecomment'])) ? $userData['uprecomment'] : null);

                        $criterion->addParticipant($participant);

                        //$criterion->addParticipant($participant);
                        //$em->persist($criterion);

                    }

                    $mailTeamSettings['addedParticipants'] = [];
                    $addedStageTeamUsers = [];

                    // TODO : corriger la fonction add Team Participants
                    foreach ($teamsData as $teamData) {

                        //$teamData = json_decode($teamJSON, true);
                        $teamId = $teamData['teaId'];
                        $team = $repoT->find($teamId);
                        $concernedTeam = null;
                        $teamPrecomment = isset($teamData['tprecomment']) ? $teamData['tprecomment'] : null;
                        $teamType = $teamData['ttype'];
                        $teamLeader = $teamData['tleader'];
                        $j = 0;

                        // Modifying existing teams

                        foreach ($criterionParticipants as $existingParticipant) {

                            if ($existingParticipant->getTeam() != null) {

                                if ($existingParticipant->getCriterion() == $criterion && $team != $concernedTeam) {

                                    $concernedTeam = $team;
                                    $teamUserParticipants = new ArrayCollection;

                                    foreach ($team->getActiveTeamUsers() as $teamUser) {
                                        if ($teamUser->isDeleted() == false) {
                                            $user = $teamUser->getUser();
                                            $teamUserId = $user->getId();
                                            $teamUserParticipant = $repoAU->findOneBy(['criterion' => $criterion, 'team' => $team, 'usrId' => $teamUserId]);

                                            if ($elmt == 'activity') {
                                                if ($teamUserParticipant == null) {
                                                    $teamUserParticipant = new ActivityUser;
                                                    $addedStageTeamUsers[] = $user;
                                                    // If a user has recently been added to a team while some team members have already locked their grades, we "unlock" their participation (note they will only be able to grade these fresh new added team users)
                                                    if (!$unvalidateTeamGradesTrigger && $teamData['ttype'] == 1) {
                                                        $unvalidateTeamGradesTrigger = true;
                                                        $validatedTeamParticipations = $criterionParticipants->matching(Criteria::create()->where(Criteria::expr()->eq("status", 3))->andWhere(Criteria::expr()->eq("team", $team)));
                                                        if ($validatedTeamParticipations != null) {

                                                            foreach ($validatedTeamParticipations as $validatedTeamParticipation) {
                                                                $validatedParticipantTeamUserIds[] = $validatedParticipation->getUsrId();
                                                                $validatedTeamParticipation->setStatus(2);
                                                                $em->persist($validatedTeamParticipation);
                                                            }
                                                        }
                                                    }
                                                    $teamUserParticipant->setIsMailed(false);
                                                    $mailTeamSettings['addedParticipants'][] = $user;
                                                    $mailTeamSettings['teamParticipation'] = true;

                                                    $criterion->addParticipant($teamUserParticipant);

                                                } else {
                                                    if ($teamUserParticipant->getType() != -1 && $teamUserParticipant->getIsMailed() == false) {
                                                        $addedStageTeamUsers[] = $user;
                                                        $mailTeamSettings['addedParticipants'][] = $user;
                                                        $mailTeamSettings['teamParticipation'] = true;
                                                    }
                                                }

                                            } else {
                                                if ($teamUserParticipant == null) {
                                                    $teamUserParticipant = new TemplateActivityUser;
                                                }
                                            }

                                            $teamUserParticipant
                                                ->setTeam($team)
                                                ->setStage($stage)
                                                ->setActivity($activity)
                                                ->setUsrId($teamUserId)
                                                ->setCreatedBy($currentUser->getId())
                                                ->setType($teamType)
                                                ->setLeader($teamLeader);

                                            if ($elmt == 'activity') {
                                                $grades = $repoG->findBy(['criterion' => $criterion, 'gradedTeaId' => $teamUserId]);
                                                foreach ($grades as $grade) {
                                                    $grade->setType($teamData['ttype']);
                                                    $em->persist($grade);
                                                }
                                            }

                                            if ($teamPrecomment != "") {
                                                $teamUserParticipant->setPrecomment(($teamPrecomment != "") ? $teamData['tprecommment'] : null);
                                            }

                                            $teamUserParticipants->add($teamUserParticipant);
                                        }
                                    }

                                    $existingTeamUserParticipants = new ArrayCollection($repoAU->findBy(['criterion' => $criterion, 'team' => $team]));
                                    $removableTeamUserParticipants = clone $existingTeamUserParticipants;
                                    // Removing extra team participants

                                    foreach ($teamUserParticipants as $teamUserParticipant) {
                                        if ($removableTeamUserParticipants->contains($teamUserParticipant)) {
                                            $removableTeamUserParticipants->removeElement($teamUserParticipant);
                                        }
                                    }

                                    foreach ($removableTeamUserParticipants as $removableTeamUserParticipant) {
                                        $criterion->removeParticipant($removableTeamUserParticipant);
                                    }

                                    $em->persist($criterion);
                                }
                            }
                        }

                        if ($team != $concernedTeam) {

                            $concernedTeam = $team;
                            foreach ($team->getActiveTeamUsers() as $teamUser) {
                                $user = $teamUser->getUser();
                                $addedStageTeamUsers[] = $user;
                                $teamUserId = $user->getId();

                                if ($elmt == 'activity') {

                                    $teamUserParticipant = new ActivityUser;

                                    // If a nonTP participant team is added while a participant has already locked his grades, we "unlock" his participation (note he will only be able to grade these fresh new added participants)
                                    if (!$unvalidateGradesTrigger && $teamData['ttype'] == 1) {
                                        $unvalidateGradesTrigger = true;
                                        $validatedParticipations = $criterionParticipants->matching(Criteria::create()->where(Criteria::expr()->eq("status", 3)));
                                        if ($validatedParticipations != null) {

                                            foreach ($validatedParticipations as $validatedParticipation) {
                                                $unvalidatedParticipantUserIds[] = $validatedParticipation->getUsrId();
                                                $validatedParticipation->setStatus(2);
                                                $em->persist($validatedParticipation);
                                            }
                                        }
                                    }

                                    $mailTeamSettings['addedParticipants'][] = $user;

                                } else {
                                    $teamUserParticipant = new TemplateActivityUser;
                                }

                                $teamUserParticipant->setTeam($team)->setStage($stage)->setActivity($activity)->setUsrId($teamUserId)->setType($teamType)->setLeader($teamLeader);

                                if ($elmt == 'activity') {
                                    $teamUserParticipant->setIsMailed(false);
                                    $grades = $repoG->findBy(['criterion' => $criterion, 'gradedTeaId' => $teamUserId]);
                                    foreach ($grades as $grade) {
                                        $grade->setType($teamData['ttype']);
                                        $em->persist($grade);
                                    }
                                }

                                if ($teamPrecomment != null) {
                                    $teamUserParticipant->setPrecomment($teamData['tprecomment']);
                                }
                                $criterion->addParticipant($teamUserParticipant);
                            }

                        }

                        //$em->flush();
                    }

                    $em->persist($criterion);
                    $em->flush();

                    // Encapsulating unvalidated users/teams in array
                    if (count($unvalidatedParticipantUserIds) + count($unvalidatedParticipantTeamUserIds) > 0) {
                        $unvalidatedElmt['stage'] = $stage;
                        $unvalidatedElmt['usrIds'] = array_unique($unvalidatedParticipantUserIds);
                        $unvalidatedElmt['teaIds'] = array_unique($unvalidatedParticipantTeamUserIds);
                        $unvalidatedElmts[] = $unvalidatedElmt;
                    }

                }

                if ($gradingParticipantsTrigger == false) {
                    $nbStageParticipantsPbs++;
                }
            }

            if ($elmt == 'activity') {

                $mailSettings['stage'] = null;
                $mailSettings['activity'] = null;
                $mailTeamSettings['activity'] = null;
                $mailTeamSettings['stage'] = null;

                /* Sending mails to participants */

                if ($actionType == 'next') {
                    $sendingUsers = array_unique($addedStageUsers);
                    if ($sendingUsers) {
                        if (count($activity->getStages()) > 1) {
                            foreach ($activity->getActiveStages() as $stage) {
                                $mailSettings['stage'] = $stage;
                                MasterController::sendMail($app, $sendingUsers, 'activityParticipation', $mailSettings);
                            }
                        } else {
                            $mailSettings['activity'] = $activity;
                            MasterController::sendMail($app, $sendingUsers, 'activityParticipation', $mailSettings);
                        }
                    }

                    $sendingTeamUsers = array_unique($addedStageTeamUsers);
                    if ($sendingTeamUsers) {
                        $mailTeamSettings['teamParticipation'] = true;
                        if (count($activity->getStages()) > 1) {
                            foreach ($activity->getActiveStages() as $stage) {
                                $mailTeamSettings['stage'] = $stage;
                                MasterController::sendMail($app, $sendingTeamUsers, 'activityParticipation', $mailTeamSettings);
                            }
                        } else {
                            $mailTeamSettings['activity'] = $activity;
                            MasterController::sendMail($app, $sendingTeamUsers, 'activityParticipation', $mailTeamSettings);
                        }
                    }

                    // Set mail parameter to true to all participants (except passive participants) as they will not be spammed again

                    foreach ($stage->getCriteria() as $stageCriterion) {
                        foreach ($stageCriterion->getParticipants() as $criterionParticipant) {
                            if ($criterionParticipant->getType() != -1 && $criterionParticipant->getIsMailed() == false) {
                                $criterionParticipant->setIsMailed(true);
                                $em->persist($criterionParticipant);
                            }
                        }
                    }

                    // foreach ($mailSettings['addedParticipants'] as $participant) {
                    //     $participant->setIsMailed(true);
                    //     $em->persist($participant);
                    // }

                    // foreach ($mailTeamSettings['addedParticipants'] as $teamParticipant) {
                    //     $teamParticipant->setIsMailed(true);
                    //     $em->persist($teamParticipant);
                    // }

                }

                if (($actionType == 'next' || $actionType == 'save') && count($unvalidatedElmts) > 0) {

                    if (count($activity->getStages()) > 1) {
                        $unvalidatingMailSettings['stage'] = $unvalidatedElmt['stage'];
                        $unvalidatingTeamMailSettings['stage'] = $unvalidatedElmt['stage'];
                    } else {
                        $unvalidatingMailSettings['activity'] = $unvalidatedElmt['stage']->getActivity();
                        $unvalidatingTeamMailSettings['activity'] = $unvalidatedElmt['stage']->getActivity();

                    }

                    foreach ($unvalidatedElmts as $unvalidatedElmt) {

                        $userRecipients = [];
                        $teamUserRecipients = [];

                        if (count($unvalidatedElmt['usrIds']) > 0) {
                            $userRecipients = $repoU->findBy(['id' => $unvalidatedElmt['usrIds']]);
                            $unvalidatingMailSettings['newTeamJoiner'] = false;
                            MasterController::sendMail($app, $userRecipients, 'unvalidatedGradesStageJoiner', $unvalidatingMailSettings);
                        }

                        if (count($unvalidatedElmt['teaIds']) > 0) {
                            $teamUserRecipients = $repoU->findBy(['id' => $unvalidatedElmt['teaIds']]);
                            $unvalidatingTeamMailSettings['newTeamJoiner'] = true;
                            MasterController::sendMail($app, $teamUserRecipients, 'unvalidatedGradesStageJoiner', $unvalidatingTeamMailSettings);
                        }
                    }
                }
            }

            //TODO : finir la copie des participants pour toutes les activités
            if ($activity->getRecurring()) {
                if ($_POST['replicate']) {

                    $firstActiveRecurringActivity = $activity->getRecurring()->getOngoingFutCurrActivities()->first();

                    foreach ($firstActiveRecurringActivity->getStages() as $stageKey => $firstActiveRecurringActivityStage) {
                        foreach ($firstActiveRecurringActivityStage->getCriteria() as $criterionKey => $firstActiveRecurringActivityStageCriterion) {

                            //Submitted participants
                            $firstActiveRecurringActivityStageCriterionParticipants = $firstActiveRecurringActivityStageCriterion->getParticipants();

                            $participantstoAdd = new ArrayCollection;
                            $keysParticipantsToRemove = [];
                            $toCopy = false;

                            foreach ($activity->getRecurring()->getOngoingFutCurrActivities() as $ongoingFutCurrActivity) {

                                // For all activities following first one, we copy participants of each stage
                                if ($ongoingFutCurrActivity != $firstActiveRecurringActivity) {

                                    //2 cases : no added participants to criterion yet, or existing participants already
                                    $ongoingFutCurrActivityStageCriterion = $ongoingFutCurrActivity->getStages()->get($stageKey)->getCriteria()->get($criterionKey);

                                    if (count($ongoingFutCurrActivityStageCriterion->getParticipants()) == 0) {

                                        foreach ($firstActiveRecurringActivityStageCriterionParticipants as $participantKey => $participant) {

                                            $newParticipant = clone $participant;
                                            $newParticipant->setCriterion($ongoingFutCurrActivityStageCriterion);
                                            $ongoingFutCurrActivityStageCriterion->getParticipants()->add($newParticipant);
                                        }

                                        //$em->persist($ongoingFutCurrActivityStageCriterion);
                                        //$em->flush();

                                    } else {
                                        //In case current FutCurrActStage criterion has already some participants...
                                        if (!$toCopy) {

                                            //.. then we fall into 3 cases : or submitted participant is new, or exists, or has been removed

                                            $copiedFutCurrActivityStageCriterionParticipants = clone $ongoingFutCurrActivityStageCriterion->getParticipants();

                                            //return count($copiedFutCurrActivityStageCriterionParticipants);

                                            foreach ($firstActiveRecurringActivityStageCriterionParticipants as $participantKey => $participant) {
                                                $partExists = false;
                                                foreach ($ongoingFutCurrActivityStageCriterion->getParticipants() as $ongoingFutCurrActivityStageCriterionParticipant) {
                                                    if ($ongoingFutCurrActivityStageCriterionParticipant->getUsrId() == $participant->getUsrId()) {
                                                        $partExists = true;
                                                        $copiedFutCurrActivityStageCriterionParticipants->removeElement($ongoingFutCurrActivityStageCriterionParticipant);
                                                        break;
                                                    }
                                                }
                                                if ($partExists == false) {
                                                    //It means that it is a new element to add
                                                    $participantstoAdd->add($participant);
                                                }

                                            }
                                            //Defining keys of which participants to remove, if necessary
                                            foreach ($copiedFutCurrActivityStageCriterionParticipants as $participantToRemove) {
                                                $keysParticipantsToRemove[] = $copiedFutCurrActivityStageCriterionParticipants->key($participantToRemove);
                                            }

                                            foreach ($participantstoAdd as $participantToAdd) {
                                                $newParticipant = clone $participantToAdd;
                                                $newParticipant->setCriterion($ongoingFutCurrActivityStageCriterion);
                                                $em->persist($newParticipant);
                                            }

                                            foreach ($keysParticipantsToRemove as $participantKey) {
                                                $ongoingFutCurrActivityStageCriterion->getParticipants()->remove($participantKey);
                                            }

                                            $toCopy = true;
                                        } else {

                                            foreach ($keysParticipantsToRemove as $participantKey) {
                                                $ongoingFutCurrActivityStageCriterion->getParticipants()->remove($participantKey);
                                            }

                                            foreach ($participantstoAdd as $participantToAdd) {

                                                $newParticipant = clone $participantToAdd;
                                                $newParticipant->setCriterion($ongoingFutCurrActivityStageCriterion);
                                                $em->persist($newParticipant);
                                                //$ongoingFutCurrActivityStageCriterion->getParticipants()->add($newParticipant);
                                            }
                                        }
                                    }
                                }

                                $em->persist($ongoingFutCurrActivity);

                                /*
                                if (count($keysParticipantsToRemove) == 0 )

                                $ongoingFutCurrActivityStageCriterion = $ongoingFutCurrActivity->getStages()->get($stageKey)->getCriteria()->get($criterionKey);

                                if ($ongoingFutCurrActivityStageCriterionParticipant->getUsrId() != )

                                if ($ongoingFutCurrActivityStageCriterionParticipant->getUsrId() == $participant->getUsrId()) {
                                $partExists = true;
                                break;
                                }
                                }

                                if ($partExists) {continue;} else {}

                                if ($ongoingFutCurrActivity->getStages()->get($stageKey))
                                $clonedParticipant = clone $participant;
                                $participant->removeCriterion($participant->getCriterion());
                                $ongoingFutCurrActivityStageCriterion->addParticipant($clonedParticipant);
                                $em->persist($ongoingFutCurrActivityStageCriterion);
                                }
                                }
                                }
                                }*/

                                if ($ongoingFutCurrActivity->getStatus() == -1) {

                                    $ongoingFutCurrActivity->setStatus(0);
                                    $em->persist($ongoingFutCurrActivity);
                                }

                            }

                        }

                    }

                    $em->flush();
                }

                /*

                //$firstStageOngoingFutCurrActivity = $ongoingFutCurrActivity->getStages()->first();

                $participants = $activity->getRecurring()->getOngoingFutCurrActivities()->first()->getStages()->first()->

                foreach ($participants as $participant) {

                $firstStageOngoingFutCurrActivity->addParticipant($participant);
                $em->persist($firstStageOngoingFutCurrActivity);*/
                /*
                $participant->setStage($ongoingFutCurrActivity->getStages()->first());
                $em->persist($participant);
                $em->flush();*/

                //        }
                //    }

                //$participants = clone $ongoingFutCurrActivity->getStages()->first()->getParticipants();
                //$participants = new ArrayCollection;
                //foreach ($ongoingFutCurrActivity->getStages()->first()->getParticipants() as $participant) {
                //    $participants->add(clone $participant);
                //}
                //print_r(count($participants));

                //Activity has now all parameters, we can put its status to 1

                //}

                //}

            }

            if ($elmt == 'activity') {

                // Sending creation mail if first time activity creator finalizes the activity

                $mailSettings['activity'] = null;
                $mailSettings['stage'] = null;

                if ($actionType == 'next') {

                    if (count($activity->getStages()) > 1) {

                        foreach ($activity->getActiveStages() as $stage) {

                            if ($stage->getIsFinalized() == false) {
                                $mailSettings['stage'] = $stage;
                                $mailSettings['isCreatorAdministrator'] = false;
                                $recipients = [];
                                $recipients[] = $currentUser;
                                $firmAdministrators = $repoU->findBy(['orgId' => $currentUser->getOrgId(), 'role' => 1, 'deleted' => null]);
                                foreach ($firmAdministrators as $firmAdministrator) {
                                    if ($firmAdministrator != $currentUser) {
                                        // $recipients[] = $firmAdministrator;
                                    } else {
                                        $mailSettings['isCreatorAdministrator'] = true;
                                    }
                                }

                                MasterController::sendMail($app, $recipients, 'activityCreation', $mailSettings);
                                $stage->setFinalized(new \DateTime)->setIsFinalized(true);
                                if ($activity->getIsFinalized() === false) {
                                    $activity->setFinalized(new \DateTime)->setIsFinalized(true);
                                    $firstFinalization = true;
                                }
                                $em->persist($stage);
                            }
                        }

                    } else {

                        $mailSettings['activity'] = $activity;
                        $mailSettings['isCreatorAdministrator'] = false;
                        $recipients = [];
                        $recipients[] = $currentUser;
                        $firmAdministrators = $repoU->findBy(['orgId' => $currentUser->getOrgId(), 'role' => 1, 'deleted' => null]);
                        foreach ($firmAdministrators as $firmAdministrator) {
                            if ($firmAdministrator != $currentUser) {
                                // $recipients[] = $firmAdministrator;
                            } else {
                                $mailSettings['isCreatorAdministrator'] = true;
                            }
                        }

                        if ($activity->getIsFinalized() === false) {

                            MasterController::sendMail($app, $recipients, 'activityCreation', $mailSettings);
                            $activity->setFinalized(new \DateTime)->setIsFinalized(true);
                            $firstFinalization = true;
                        }

                    }
                }
            }

            $tomorrowDate = new \DateTime;
            $tomorrowDate->add(new \DateInterval('P1D'));

            $yesterdayDate = new \DateTime;
            $yesterdayDate->sub(new \DateInterval('P1D'));

            $k = 0;
            $p = 0;

            foreach ($activity->getActiveStages() as $stage) {
                if ($stage->getGStartDate() > $tomorrowDate) {
                    $k++;
                }
                if ($stage->getGEndDate() <= $yesterdayDate) {
                    $p++;
                }
            }

            if ($elmt == 'activity') {

                $nbActiveStages = count($activity->getActiveStages());
                $nbActiveModifiableStages = count($activity->getActiveModifiableStages());

                if ($nbActiveStages != 0) {

                    // Updating activity status, only processed if remaining active stages

                    // Activity is not considered incomplete if at least one active stage has a participant
                    if ($nbStageParticipantsPbs != $nbActiveModifiableStages) {

                        if ($firstFinalization || $activity->getIsFinalized() == true) {

                            // If every grading stage starts in the future...
                            if ($k == $nbActiveStages) {
                                $activity->setStatus(0);
                            } else {
                                //..else if not every grading stage ends in the past...
                                if ($p != $nbActiveStages) {
                                    $activity->setStatus(1);
                                } else {
                                    $activity->setStatus(-1);
                                }
                            }
                        }

                    } else {
                        $activity->setStatus(-1);
                    }

                    $em->persist($activity);
                    $em->flush();
                }
                if ($returnJSON) {
                    if ($actionType != 'enrich') {
                        return new JsonResponse(['message' => 'validate', 'savedAsIncomplete' => ($activity->getFinalized() === null)], 200);
                    } else {
                        return new JsonResponse(['message' => 'enrich'], 200);
                    }
                }

            } else {

                $em->persist($activity);
                $em->flush();
                if ($returnJSON) {
                    if ($actionType != 'enrich') {
                        return new JsonResponse(['message' => 'validate'], 200);
                    } else {
                        return new JsonResponse(['message' => 'enrich'], 200);
                    }
                }
            }
        } else if ($actionType == 'previous') {
            return new JsonResponse(['message' => 'goPrev'], 200);
        } else if ($actionType == 'back') {
            return new JsonResponse(['message' => 'goBack'], 200);
        }

    }

    public function addStageCompletedActivity(Request $request, Application $app, $actId)
    {

        $em = self::getEntityManager();
        $activity = $em->getRepository(Activity::class)->find($actId);

        $originalStages = new ArrayCollection;

        // We get the collection of current stages objects in the database
        foreach ($activity->getStages() as $stage) {
            $originalStages->add($stage);

            //We create at least a criterion per stage in case there are none

            if ($stage->getCriteria()->isEmpty()) {
                $criterion = new Criterion;
                $criterion->setStage($stage);
                $criterion->setWeight(1);
                $criterion->setName('General evaluation');
                $criterion->setCreatedBy($currentUser->getId());
                $em->persist($criterion);
            }
        }

        $formFactory = $app['form.factory'];
        $stageForm = $formFactory->create(AddStageForm::class, $activity, ['standalone' => true]);
        $stageForm->handleRequest($request);

        if ($stageForm->isSubmitted()) {
            if ($stageForm->isValid()) {

                //In case a stage has been removed, we remove its relationship with its related activity
                $submittedStages = $stageForm->getData()->getStages();

                foreach ($originalStages as $stage) {
                    if ($submittedStages->contains($stage) === false) {
                        $stage->setActivity(null);
                        $stage->setCreatedBy($currentUser->getId());
                        $em->persist($stage);
                    }
                }

                $em->persist($activity);
                $em->flush();

                return $app->redirect($app['url_generator']->generate('activityCriterion', ['actId' => $actId]));
            }
        }

        return $app['twig']->render('activity_define_stages.twig',
            [
                'form' => $stageForm->createView(),
            ]);

    }

    // ACTIVITY CREATION (V1)

    // 1st step - Activity definition (limited to activity manager)

    public function addActivityDefinition(Request $request, Application $app, $actId)
    {

        $em = self::getEntityManager();
        $activity = $em->getRepository(Activity::class)->find($actId);

        $formFactory = $app['form.factory'];
        $activityForm = $formFactory->create(AddActivityForm::class, $activity, ['standalone' => true]);
        $activityForm->handleRequest($request);

        if ($activityForm->isSubmitted() && $activityForm->isValid()) {

            //  Feeding data for first stage
            $activityEndDate = $activityForm->get('enddate')->getData();
            $modifActivityEndDate = clone $activityEndDate;
            $gradingEndDate = $modifActivityEndDate->add(new \DateInterval('P7D'));

            // If user has not already sent the data once (doing a back/forth submission) we need to update some stage values
            // when necessary
            if (!$activity->getStages()->isEmpty()) {

                $stage = $activity->getStages()->first();

                if ($activity->getStartdate() != $stage->getStartdate()) {
                    $stage->setStartdate($activity->getStartdate());
                    $stage->setCreatedBy($currentUser->getId());
                    $em->persist($stage);
                }

                $stage = $activity->getStages()->last();

                if ($activity->getEnddate() != $stage->getEnddate()) {
                    $stage->setEnddate($activity->getEnddate());
                    $stage->setGstartdate($activityEndDate);
                    $stage->setGenddate($gradingEndDate);
                    $stage->setCreatedBy($currentUser->getId());
                    $em->persist($stage);
                }

            } else {

                $stage = new Stage;
                $stage->setName($activityForm->get('name')->getData());
                $stage->setStartdate($activityForm->get('startdate')->getData());
                $stage->setEnddate($activityEndDate);
                $stage->setGstartdate($activityEndDate);
                $stage->setGenddate($gradingEndDate);
                $stage->setActivity($activity);
                $stage->setCreatedBy($currentUser->getId());
                $stage->setWeight(1);
                $em->persist($stage);
            }

            $em->persist($activity);
            $em->flush();

            //Subrequest to get to participants and keep param values with post method
            // as activity will not be inserted in DB till act mgr does not finish activity creation
            //$subrequest = Request::create($app['url_generator']->generate('activityStage'), 'POST', $_POST, $_COOKIE, $_FILES, $_SERVER);
            //$app->handle($subrequest,HttpKernelInterface::SUB_REQUEST);

            return $app->redirect($app['url_generator']->generate('activityStage', ['actId' => $actId]));
        }

        return $app['twig']->render('activity_create_definition.twig',
            [
                'form' => $activityForm->createView(),
            ]);
    }

    // 2 - Create stages

    /**
     * @param Request $request
     * @param Application $app
     * @param $elmt
     * @param $elmtId
     * @return RedirectResponse
     * @Route("/{elmt}/{elmtId}/stages", name="activityStages")
     */
    public function displayActivityStages(Request $request, Application $app, $elmt, $elmtId)
    {
        $user = self::getAuthorizedUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login');
        }
        $userRole = $user->getRole();
        $em = self::getEntityManager();
        $elmtIsActivity = $elmt === 'activity';
        /** @var Activity|TemplateActivity */
        $activity = $em->getRepository(
            $elmtIsActivity ? Activity::class : TemplateActivity::class
        )->find($elmtId);
        $organization = $user->getOrganization();
        $actOrganization = $activity->getOrganization();
        $createTemplateForm = null;
        $formFactory = $app['form.factory'];
        if ($elmt == 'activity' && $activity->getTemplate() == null) {
            $createTemplateForm = $formFactory->create(AddTemplateForm::class, null, ['standalone' => true]);
            $createTemplateForm->handleRequest($request);
        }

        $sumWeightModifiableStages = 0;
        $activeModifiableStages = $activity->getActiveModifiableStages();
        foreach ($activeModifiableStages as $activeModifiableStage) {
            $sumWeightModifiableStages += $activeModifiableStage->getWeight();
        }

        $userIsNotRoot = $userRole != 4;
        $userIsAdmin = $userRole == 1;
        $userIsAM = $userRole == 2;
        $userIsCollab = $userRole == 3;
        $actBelongsToDifferentOrg = $organization != $actOrganization;
        $actHasNoActiveModifiableStages = count($activeModifiableStages) == 0;
        $hasPageAccess = true;
        if ($elmtIsActivity) {
            if ($userIsNotRoot and ($actBelongsToDifferentOrg or !$userIsAdmin and $actHasNoActiveModifiableStages)) {
                $hasPageAccess = false;
            }
        } else {
            if ($userIsCollab or ($userIsAM or $userIsAdmin) and $actBelongsToDifferentOrg) {
                $hasPageAccess = false;
            }
        }

        if (!$hasPageAccess) {
            return $app['twig']->render('errors/403.html.twig');
        } else {
            $formFactory = $app['form.factory'];
            $stageForm = $formFactory->create(AddStageForm::class, $activity, ['standalone' => true, 'elmt' => $elmt]);
            $stageForm->handleRequest($request);

            return $app['twig']->render('activity_define_stages.twig',
                [
                    'form' => $stageForm->createView(),
                    'elmt' => $elmt,
                    'activity' => $activity,
                    'createTemplateForm' => ($createTemplateForm === null) ?: $createTemplateForm->createView(),
                    'sumWeightModifiableStages' => $sumWeightModifiableStages,
                ]);
        }

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $elmt
     * @param $elmtId
     * @param $actionType
     * @param bool $returnJSON
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/{elmt}/{elmtId}/stages/{actionType}", name=")
     */
    public function saveActivityStages(Request $request, Application $app, $elmt, $elmtId, $actionType, $returnJSON = true)
    {

        $em = self::getEntityManager();
        if ($elmt == 'activity') {
            $activity = $em->getRepository(Activity::class)->find($elmtId);
            $completedStages = $activity->getOCompletedStages();
            $sumWeightModifiableStages = 0;
            $activeModifiableStages = $activity->getActiveModifiableStages();
            foreach ($activeModifiableStages as $activeModifiableStage) {
                $sumWeightModifiableStages += $activeModifiableStage->getWeight();
            }

        } else {
            $activity = $em->getRepository(TemplateActivity::class)->find($elmtId);
        }

        $activityStages = $activity->getActiveModifiableStages();

        $theOriginalStages = clone $activityStages;
        $submittedStages = new ArrayCollection;

        $formFactory = $app['form.factory'];
        $stageForm = $formFactory->create(AddStageForm::class, $activity, ['standalone' => true, 'elmt' => $elmt]);
        $stageForm->handleRequest($request);

        $repoCN = $em->getRepository(CriterionName::class);
        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        $orgId = $currentUser->getOrgId();
        $repoO = $em->getRepository(Organization::class);
        $organization = $repoO->findOneById($orgId);

        if ($actionType == 'next' || $actionType == 'prev') {

            if ($stageForm->isValid()) {

                $formStages = $stageForm->getData()->getActiveModifiableStages();
                $k = 0;
                foreach ($formStages as $stage) {
                    //We create at least a criterion per stage in case there are none

                    if ($theOriginalStages->contains($stage) === false) {
                        $criterion = ($elmt == 'activity') ? new Criterion : new TemplateCriterion;
                        $criterion->setCName($organization->getCriterionNames()->first());
                        $criterion->setWeight(1);
                        $criterion->setName('General evaluation');
                        $stage->setActivity($activity);
                        $stage->setMasterUserId($currentUser->getId());
                        $stage->addCriterion($criterion);
                        $stage->setCreatedBy($currentUser->getId());
                        $em->persist($stage);
                    }
                    if ($elmt == 'activity') {
                        $stage->setWeight($stage->getWeight() * $sumWeightModifiableStages);
                    }
                }

                //In case a stage has been removed, we remove its relationship with its related activity

                // As the form is valid, the stage has been correctly inserted, we need to put the complete prop to true
                // to display it as by default, a created stage is incomplete

                //$totalWeight = 0;
                /*foreach ($submittedStages as $submittedStage) {

                //$totalWeight = $totalWeight + $submittedStage->getWeight();

                if ($submittedStage->getMasterUserId() == null) {
                $submittedStage->setMasterUserId(MasterController::getAuthorizedUser($app)->getId());
                }

                if ($activityStages->contains($submittedStage) === false) {
                $submittedStage->setWeight($submittedStage->getWeight() * (1 - $completedStagesWeight));
                }

                $em->persist($submittedStage);
                }*/

                /*foreach ($activityStages as $stage) {
                if ($submittedStages->contains($stage) === false) {
                $activity->removeStage($stage);
                }
                }*/

                // See if activity status needs to get updated
                if ($elmt == 'activity') {
                    if ($activity->getStatus() == 1) {
                        $k = 0;
                        foreach ($activityStages as $stage) {
                            if ($stage->getGStartDate() > new \DateTime) {
                                $stage->setStatus(0);
                                $k++;
                            } else {
                                $stage->setStatus(1);
                            }
                            $em->persist($stage);
                        }
                        if ($k == count($activity->getActiveModifiableStages())) {
                            $activity->setStatus(0);
                        }
                        if ($activity->getStatus() == 0) {

                            $tomorrowDate = new \DateTime;
                            $tomorrowDate->add(new \DateInterval('P1D'));
                            foreach ($activity->getActiveModifiableStages() as $stage) {
                                if ($stage->getGStartDate() < $tomorrowDate) {
                                    $activity->setStatus(1);
                                    break;
                                }
                            }
                        }
                    }
                }

                $em->persist($activity);
                $em->flush();

                /*
                if ($totalWeight > 1 || $totalWeight < 1) {

                return new JsonResponse(['message' => 'Total Weight is below or above 1']);
                }
                 */

                return new JsonResponse(['message' => 'goNext'], 200);

            } else {
                $errors = $this->buildErrorArray($stageForm);
                return $errors;
            }

        } else {

            $k = 0;
            $activity->setSaved(new \DateTime);
            $em->persist($activity);
            $totalWeight = 0;
            // We try to save stages which are correctly inserted
            foreach ($stageForm->get('activeModifiableStages') as $individualStageForm) {
                $totalWeight = $totalWeight + $individualStageForm->get('weight')->getData();
            }

            foreach ($stageForm->get('activeModifiableStages') as $individualStageForm) {

                $stage = $individualStageForm->getData();

                if ($individualStageForm->get('name')->isValid()) {$stage->setName($individualStageForm->get('name')->getData());} else {
                    if ($stage->getName() == null) {
                        $stage->setName('Phase ' . ($activity->getStages()->indexOf($stage) + 1));
                    }
                    $k++;
                };
                if ($individualStageForm->get('startdate')->isValid()) {$stage->setStartdate($individualStageForm->get('startdate')->getData());} else { $k++;};
                if ($individualStageForm->get('enddate')->isValid()) {$stage->setEnddate($individualStageForm->get('enddate')->getData());} else { $k++;};
                if ($individualStageForm->get('gstartdate')->isValid()) {$stage->setGstartdate($individualStageForm->get('gstartdate')->getData());} else { $k++;};
                if ($individualStageForm->get('genddate')->isValid()) {$stage->setGenddate($individualStageForm->get('genddate')->getData());} else { $k++;};
                if ($totalWeight == 100) {$stage->setGenddate($individualStageForm->get('weight')->getData());} else { $k++;};

                if ($elmt == 'activity' && $stage->setOrganization() == null) {$stage->setOrganization($organization);}
                if ($stage->getMasterUserId() == null) {$stage->setMasterUserId($currentUser->getId());}

                if (count($stage->getCriteria()) == 0) {
                    $criterion = ($elmt == 'activity') ? new Criterion : new TemplateCriterion;
                    $criterion->setCName($organization->getCriterionNames()->first());
                    $stage->addCriterion($criterion);
                }

                $em->persist($stage);

            }

            // See if activity status needs to be updated, will only be if activity is not incomplete
            if ($elmt == 'activity') {
                if ($activity->getStatus() != -1) {
                    $k = 0;
                    foreach ($activityStages as $stage) {
                        if ($stage->getGStartDate() > new \DateTime) {
                            $stage->setStatus(0);
                            $k++;
                        } else {
                            $stage->setStatus(1);
                        }
                        $em->persist($stage);
                    }
                    if ($k == count($activity->getActiveModifiableStages())) {
                        $activity->setStatus(0);
                    }
                    if ($activity->getStatus() == 0) {

                        $tomorrowDate = new \DateTime;
                        $tomorrowDate->add(new \DateInterval('P1D'));
                        foreach ($activity->getActiveModifiableStages() as $stage) {
                            if ($stage->getGStartDate() < $tomorrowDate) {
                                $activity->setStatus(1);
                                break;
                            }
                        }
                    }
                }
            }

            $em->persist($activity);
            $em->flush();

            switch ($actionType) {
                case 'back':
                case 'save':
                    $message = 'goBack';
                    break;
                case 'stage':
                    $message = 'stages';
                    break;
                case 'parameter':
                    $message = 'parameters';
                    break;
                case 'criterion':
                    $message = 'criteria';
                    break;
                case 'participant':
                    $message = 'participants';
                    break;
            }

            return new JsonResponse(['message' => $message], 200);

        }

    }

    // 3 - Create criteria

    /**
     * @param Request $request
     * @param Application $app
     * @param $elmt
     * @param $elmtId
     * @return RedirectResponse
     * @Route("/{elmt}/{elmtId}/criteria", name="activityCriteria")
     */
    public function addActivityCriterion(Request $request, Application $app, $elmt, $elmtId)
    {
        $user = self::getAuthorizedUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login');
        }
        $userRole = $user->getRole();
        $em = self::getEntityManager();
        $elmtIsActivity = $elmt === 'activity';
        /** @var Activity|TemplateActivity */
        $activity = $em->getRepository(
            $elmtIsActivity ? Activity::class : TemplateActivity::class
        )->find($elmtId);
        $repoI = $em->getRepository(Icon::class);
        $icons = $repoI->findAll();
        $organization = $user->getOrganization();
        $actOrganization = $activity->getOrganization();
        $activeModifiableStages = $activity->getActiveModifiableStages();

        $userIsNotRoot = $userRole != 4;
        $userIsAdmin = $userRole == 1;
        $userIsAM = $userRole == 2;
        $userIsCollab = $userRole == 3;
        $actBelongsToDifferentOrg = $organization != $actOrganization;
        $actHasNoActiveModifiableStages = count($activeModifiableStages) == 0;
        $hasPageAccess = true;
        if ($elmtIsActivity) {
            if ($userIsNotRoot and ($actBelongsToDifferentOrg or !$userIsAdmin and $actHasNoActiveModifiableStages)) {
                $hasPageAccess = false;
            }
        } else {
            if ($userIsCollab or ($userIsAM or $userIsAdmin) and $actBelongsToDifferentOrg) {
                $hasPageAccess = false;
            }
        }

        if (!$hasPageAccess) {
            return $app['twig']->render('errors/403.html.twig');
        } else {
            $stages = ($elmt == 'activity') ?
            $activity->getActiveStages() :
            $activity->getStages();
            $diffStagesCriteria = true;
            // We get the collection of current stages objects in the database (names and associated criteria)
            // We also check whether criteria are differentiated among stages

            $multActiveStages = (count($stages) > 1) ?: false;

            $formFactory = $app['form.factory'];
            $createCriterionForm = $formFactory->create(CreateCriterionForm::class, null, ['standalone' => true]);
            $addCriterionForm = $formFactory->create(
                AddCriterionForm::class,
                $activity,
                [
                    'standalone' => true,
                    'multiple_active_stages' => $multActiveStages,
                    'diff_stages_criteria' => $diffStagesCriteria,
                    'app' => $app,
                    'elmt' => $elmt,
                    'attr' => [
                        'autocomplete' => 'off',
                    ],
                ]
            );
            $createCriterionForm->handleRequest($request);
            $addCriterionForm->handleRequest($request);
            $createTemplateForm = null;
            if ($elmt == 'activity' && $activity->getTemplate() == null) {
                $createTemplateForm = $formFactory->create(AddTemplateForm::class, null, ['standalone' => true]);
                $createTemplateForm->handleRequest($request);
            }

            $csrfToken = $app['csrf.token_manager']->getToken('token_id');

            return $app['twig']->render('activity_define_criteria.twig',
                [
                    'createCriterionForm' => $createCriterionForm->createView(),
                    'form' => $addCriterionForm->createView(),
                    'elmt' => $elmt,
                    'activity' => $activity,
                    'diffStagesCriteria' => $diffStagesCriteria,
                    'createTemplateForm' => ($createTemplateForm === null) ?: $createTemplateForm->createView(),
                    'icons' => $icons,
                ]);
        }
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return false|string|JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/criterion-name/add", name="addCriterionName")
     */
    public function createOrganizationCriterionAction(Request $request, Application $app)
    {
        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        $organization = $currentUser->getOrganization();
        $em = self::getEntityManager();
        /** @var FormFactory */
        $formFactory = $app['form.factory'];
        $repoCL = $em->getRepository(CriterionName::class);
        $repoI = $em->getRepository(Icon::class);

        $organizationCriteriaNames = $repoCL->findBy(['organization' => [null, $organization]]);
        $createCriterionForm = $formFactory->create(
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
     * @param Application $app
     * @param $elmt
     * @param $elmtId
     * @param $actionType
     * @param bool $returnJSON
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("}/ajax/{elmt}/{elmtId}/criteria/{actionType}", name="saveActivityCriteria")
     */
    public function saveActivityCriteria(Request $request, Application $app, $elmt, $elmtId, $actionType, $returnJSON = true)
    {

        $em = self::getEntityManager();
        $originalCriteria = new ArrayCollection;
        $stageNames = [];
        $repoO = $em->getRepository(Organization::class);
        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }
        $organization = $repoO->find($currentUser->getOrgId());

        // Get all submitted criteria

        $activity = ($elmt == 'activity') ?
        $em->getRepository(Activity::class)->find($elmtId) :
        $em->getRepository(TemplateActivity::class)->find($elmtId);
        $formFactory = $app['form.factory'];
        $activeStages = clone $activity->getActiveStages();
        $multActiveStages = (count($activity->getActiveStages()) > 1) ?: false;
        $criterionForm = $formFactory->create(AddCriterionForm::class, $activity, ['standalone' => true, 'multiple_active_stages' => $multActiveStages, 'diff_stages_criteria' => false, 'app' => $app, 'organization' => $organization, 'elmt' => $elmt]);
        $criterionForm->handleRequest($request);

        /** @var Stage[] */
        $criterionFormStages = $criterionForm->getData()->getActiveModifiableStages();

        foreach ($criterionForm->get('activeModifiableStages') as $activeStageForm) {
            foreach ($activeStageForm->get('criteria') as $activeStageCriterionForm) {
                $criteriaForm[] = $activeStageCriterionForm;
            }
        }

        if ($elmt == 'activity') {

            $mailRecipients = [];

            foreach ($criterionFormStages as $formStage) {
                $newCriteria = $formStage->getCriteria()->exists(function (int $i, Criterion $c) {
                    return $c->getId() === 0;
                });

                if ($newCriteria) {
                    // criteria were added, invalidate all grades from participants in the stage
                    // who have *confirmed* their grading (status == 3) and notify them by email
                    /** @var ActivityUser[] */
                    $participations = $formStage->getParticipants()->filter(function (ActivityUser $a) {
                        return $a->getStatus() === 3;
                    })->getValues();
                    foreach ($participations as $participation) {
                        $participation->setStatus(2); // 2 means saved but not confirmed
                    };
                }
            }

            if (count($mailRecipients)) {
                self::sendMail(
                    $app,
                    $mailRecipients,
                    'stageCriteriaAdded',
                    []
                );
            }
        }

        $activeStageCriteria = [];
        foreach ($activeStages as $activeStage) {
            $activeStageCriteria[] = $activeStage->getCriteria();
        }

        // We get the collection of current stages objects in the database (names and associated criteria)
        foreach ($activeStages as $stage) {
            foreach ($stage->getCriteria() as $criterion) {
                $originalCriteria->add($criterion);
            }
            $stageNames[] = $stage->getName();
        }

        if (($actionType == 'next') || ($actionType == 'prev')) {

            if ($criterionForm->isSubmitted()) {

                foreach ($criterionFormStages as $stage) {
                    $stageTotalCriteriaWeight = 0;
                    foreach ($stage->getCriteria() as $criterion) {
                        $stageTotalCriteriaWeight += $criterion->getWeight();
                    }

                    if (round($stageTotalCriteriaWeight, 4) != 1) {
                        return new JsonResponse(['message' => 'supHundredPct', 'stageName' => $stage->getName(), 'totalWeights' => round(100 * $stageTotalCriteriaWeight, 1)], 200);
                    }
                }
            }

            if ($criterionForm->isValid()) {

                // check values already set in DB and dissociate those which are different
                $submittedCriteria = new ArrayCollection;

                foreach ($criterionFormStages as $stage) {
                    foreach ($stage->getCriteria() as $criterion) {
                        $submittedCriteria->add($criterion);
                    }
                }

                //return [count($submittedCriteria),count($originalCriteria)];

                foreach ($submittedCriteria as $submittedCriterion) {
                    if ($submittedCriterion->getType() == 3) {
                        $submittedCriterion->setLowerbound(0)->setUpperbound(1);
                    }

                    foreach ($criteriaForm as $key => $criterionForm) {
                        if ($criterionForm->getData() == $submittedCriterion) {
                            $criterionKey = $key;
                            break;
                        }
                    }

                    $targetValueField = $criteriaForm[$criterionKey]->get('targetValue');
                    //return [$targetValueField->getData()];
                    $targetValue = ($targetValueField->getData() == null) ? null : $targetValueField->getData();

                    if ($targetValue != null) {
                        if ($submittedCriterion->getTarget() != null) {
                            $target = $submittedCriterion->getTarget();
                        } else {
                            $target = new Target;
                            $target->setCriterion($submittedCriterion);
                            $submittedCriterion->setTarget($target);
                        }
                        $target->setValue($targetValue);
                    } else {
                        $target = $submittedCriterion->getTarget();
                        if ($target !== null) {
                            $criterion->setTarget(null);
                            $target->setCriterion(null);
                            $removableTargets[] = $target;
                        }
                    }

                    $em->persist($submittedCriterion);
                }

                /*
                foreach ($originalCriteria as $originalCriterion) {
                if ($submittedCriteria->contains($originalCriterion) === false) {
                //$originalCriterion->setStage(null);
                $currentStage = $originalCriterion->getStage();
                $currentStage->removeCriterion($originalCriterion);
                //$originalCriterion->setDeleted(new \DateTime);
                $em->persist($currentStage);
                }
                }*/

                foreach ($submittedCriteria as $submittedCriterion) {

                    if (count($submittedCriterion->getParticipants()) == 0) {

                        foreach ($submittedCriteria as $theSubmittedCriterion) {
                            if (count($theSubmittedCriterion->getParticipants()) != 0 && $theSubmittedCriterion->getStage() == $submittedCriterion->getStage()) {
                                break;
                            }
                        }

                        foreach ($theSubmittedCriterion->getParticipants() as $participant) {
                            $newCriterionParticipant = clone $participant;
                            $newCriterionParticipant->setInserted(new \DateTime);
                            $submittedCriterion->addParticipant($newCriterionParticipant);
                            $em->persist($submittedCriterion);
                        }
                    }
                }

                $em->flush();
                if (isset($removableTargets)) {

                    foreach ($removableTargets as $removableTarget) {
                        $em->remove($removableTarget);
                        $em->flush();
                    }
                }

                $message = ($actionType == 'next') ? 'goNext' : 'goPrev';
                return new JsonResponse(['message' => $message], 200);

            } else {

                //print_r($criterionForm->getErrors());
                //die;

                $errors = $this->buildErrorArray($criterionForm);
                return $errors;
            }

        } else {

            foreach ($criterionFormStages as $stage) {
                $stageTotalCriteriaWeight = 0;
                foreach ($stage->getCriteria() as $criterion) {
                    $stageTotalCriteriaWeight += $criterion->getWeight();
                }

                if (round($stageTotalCriteriaWeight, 4) != 1) {
                    return new JsonResponse(['message' => 'supHundredPct', 'stageName' => $stage->getName(), 'totalWeights' => round(100 * $stageTotalCriteriaWeight, 1)], 200);
                }
            }

            //$k = 0;
            $activity->setSaved(new \DateTime);
            $em->persist($activity);

            // We insert each individual criteria which is correctly inputed
            // In case a already existing criterion has been modified and becomes unvalid, then we do not modify it

            $criterionGeneralFormElement = $criterionForm->get('activeModifiableStages');

            foreach ($criterionGeneralFormElement as $activeStageForm) {

                foreach ($activeStageForm->get('criteria') as $activeStageCriterionForm) {
                    $k = 0;
                    $criterion = $activeStageCriterionForm->getData();

                    if ($activeStageCriterionForm->get('cName')->isValid()) {
                        $repoCN = $em->getRepository(CriterionName::class);
                        $criterion->setCName($repoCN->find($activeStageCriterionForm->get('cName')->getData()));}
                    if ($activeStageCriterionForm->get('type')->isValid()) {$criterion->setType($activeStageCriterionForm->get('type')->getData());} else { $k++;}
                    if ($activeStageCriterionForm->get('lowerbound')->isValid()) {$criterion->setLowerbound($activeStageCriterionForm->get('lowerbound')->getData());} else { $k++;}
                    if ($activeStageCriterionForm->get('upperbound')->isValid()) {$criterion->setUpperbound($activeStageCriterionForm->get('upperbound')->getData());} else { $k++;}
                    if ($activeStageCriterionForm->get('step')->isValid()) {$criterion->setStep($activeStageCriterionForm->get('step')->getData());} else { $k++;}
                    if ($activeStageCriterionForm->get('weight')->isValid()) {$criterion->setWeight($activeStageCriterionForm->get('weight')->getData());} else { $k++;}
                    if ($elmt == 'activity') {
                        $criterion->setComplete(($k == 0));
                    }

                    // We only add new participants in case considered stage had participants

                    if (count($criterion->getParticipants()) == 0) {
                        $l = 0;
                        foreach ($activeStageForm->get('criteria')->getData() as $theSubmittedCriterion) {
                            if (count($theSubmittedCriterion->getParticipants()) != 0) {
                                $l = 1;
                                break;
                            }
                        }

                        // We only add new participants in case considered stage had participants

                        if ($l == 1) {
                            $existingCriterionParticipants = $theSubmittedCriterion->getParticipants();

                            foreach ($existingCriterionParticipants as $existingCriterionParticipant) {
                                $participant = clone $existingCriterionParticipant;
                                $participant->setInserted(new \DateTime);
                                $criterion->addParticipant($participant);
                            }
                        }
                    }

                    $em->persist($criterion);

                    /*if ($activeStageCriterionForm->get('cName')->isValid() && $activeStageCriterionForm->get('type')->isValid() && $activeStageCriterionForm->get('lowerbound')->isValid() && $activeStageCriterionForm->get('upperbound')->isValid() && $activeStageCriterionForm->get('step')->isValid() && $activeStageCriterionForm->get('weight')->isValid()) {

                $concernedCriterion = $activeStageCriterionForm->getData();

                $concernedCriterion->setStage($activeStageForm->getData())
                ->setCName($activeStageCriterionForm->get('cName')->getData())
                ->setType($activeStageCriterionForm->get('type')->getData())
                ->setLowerbound($activeStageCriterionForm->get('lowerbound')->getData())
                ->setUpperbound($activeStageCriterionForm->get('upperbound')->getData())
                ->setStep($activeStageCriterionForm->get('step')->getData())
                ->setWeight($activeStageCriterionForm->get('weight')->getData());

                if ($elmt == 'activity') {
                $concernedCriterion->setComplete(true);
                }

                if (count($concernedCriterion->getParticipants()) == 0) {
                $k = 0;
                foreach ($activeStageForm->get('criteria')->getData() as $theSubmittedCriterion) {
                if (count($theSubmittedCriterion->getParticipants()) != 0) {
                $k = 1;
                break;
                }
                }

                // We only add new participants in case considered stage had participants

                if ($k == 1) {
                $existingCriterionParticipants = $theSubmittedCriterion->getParticipants();

                foreach ($existingCriterionParticipants as $existingCriterionParticipant) {
                $participant = clone $existingCriterionParticipant;
                $participant->setInserted(new \DateTime);
                $concernedCriterion->addParticipant($participant);
                }
                }

                }

                $em->persist($concernedCriterion);

                }*/

                }

                //$k++;

            }

            $em->flush();

            switch ($actionType) {
                case 'back':
                case 'save':
                    $message = 'goBack';
                    break;
                case 'stage':
                    $message = 'stages';
                    break;
                case 'parameter':
                    $message = 'parameters';
                    break;
                case 'criterion':
                    $message = 'criteria';
                    break;
                case 'participant':
                    $message = 'participants';
                    break;
            }

            return new JsonResponse(['message' => $message], 200);

        }

        /*} elseif ($actionType == 'back') {

    return new JsonResponse(['message' => 'goBack'], 200);
    } elseif ($actionType == 'previous') {

    return new JsonResponse(['message' => 'goPrev'], 200);
    }*/
    }

    // 4 - Display participants to be added

    // Display all participants (after Activity Mgr sets activities parameters)
    public function addParticipantsAction(Request $request, Application $app, $elmt, $elmtId)
    {
        RouteDumper::dump($app);
        $em = self::getEntityManager();
        $repoU = $em->getRepository(User::class);
        $result = [];
        $orgId = $app['security.token_storage']->getToken()->getUser()->getOrgId();

        foreach ($repoU->findAllActiveByOrganization($orgId) as $user) {

            $result[] = $user->toArray();
            print_r($result);
            die;
        }

        return $app['twig']->render('participants_list.html.twig',
            [
                'stages' => $stages,
                'actId' => $actId,
                'participants' => $result,
            ]);

    }

    //Update activity (limited to activity manager)
    public function modifyActivityAction(Request $request, Application $app)
    {

    }

    /*********** ADDITION, MODIFICATION, DELETION AND DISPLAY OF PARTICIPANTS *****************/

    //Modify Action
    public function modifyAction(Request $request, Application $app, $actId)
    {
        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        $organization = $currentUser->getOrganization();

        //Get the data about the activity selected
        $activity = new Activity;
        $em = self::getEntityManager();
        $repoA = $em->getRepository(Activity::class);
        $activity = $repoA->find($actId);

        //Get the criteria of the activity
        $criterion = new Criterion;
        $em = self::getEntityManager();
        $repoC = $em->getRepository(Criterion::class);
        $criterion = $repoC->findOneByActId($actId);

        /** @var FormFactory */
        $formFactory = $app['form.factory'];
        $modifyActivityForm = $formFactory->create(
            AddActivityCriteriaForm::class,
            $criterion,
            [
                'standalone' => true,
                'organization' => $organization,
            ]
        );
        $modifyActivityForm->handleRequest($request);

        if ($modifyActivityForm->isSubmitted()) {
            //Update the activity
            $activity->setDeadline($modifyActivityForm->get('deadline')->getData());
            $activity->setVisibility($modifyActivityForm->get('visibility')->getData());
            $activity->setObjectives($modifyActivityForm->get('objectives')->getData());
            $activity->setName($modifyActivityForm->get('name')->getData());
            $em->persist($activity);
            $em->persist($criterion);
            $em->flush();

            $em = self::getEntityManager();
            $repository = $em->getRepository(\Model\User::class);
            $allUsers = [];
            foreach ($repository->findAll() as $user) {
                $allUsers[] = $user->toArray($app);
            }

            //Get the participants linked to the activity
            $sql = "SELECT usr_id FROM user INNER JOIN activity_user ON activity_user.user_usr_id=user.usr_id WHERE activity_user.activity_act_id=:actId";
            $pdoStatement = $app['db']->prepare($sql);
            $pdoStatement->bindValue(':actId', $actId);
            $pdoStatement->execute();
            $list = $pdoStatement->fetchAll();
            $activeId = [];
            foreach ($list as $key => $value) {
                $activeId[] = $value['usr_id'];
            }

            return $app['twig']->render('participants_list.html.twig',
                [
                    'participants' => $allUsers,
                    'activeId' => $activeId,
                    'actId' => $actId,
                    'update' => true,
                ]);
        }
        return $app['twig']->render('activity_modify.html.twig',
            [
                'form' => $modifyActivityForm->createView(),
            ]);

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $actId
     * @return JsonResponse
     * @Route("/activity/{actId}/gstages", name="getGradableStages")
     */
    public function getGradableStagesAction(Request $request, Application $app, $actId)
    {
        $em = self::getEntityManager();
        $currentUser = self::getAuthorizedUser();
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
     * @param Application $app
     * @param $stgId
     * @param null $usrId
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/activity/survey/form/answer/{stgId}", name="answerSurvey")
     * @Route("/activity/survey/form/answer/{stgId}", name="AnswerRequest")
     */
    public function answerRequestAction(Request $request, Application $app, $stgId, $usrId = null)
    {
        $error="";
        $em = $this->getEntityManager($app);
        $formFactory = $app['form.factory'];
        $repo0 = $em->getRepository(Survey::class);
        $repo2 = $em->getRepository(ActivityUser::class);
        $repo1 = $em->getRepository(Answer::class);
        $survey = $repo0->findOneBy(array('stage' => $stgId));
        $currentUser = MasterController::getAuthorizedUser();
        $surveyfield = $survey->getFields();
        $activity = $repo2->findOneBy(array('stage' => $survey->getStage(), 'usrId' => $currentUser->getId()));
        $tblId=[];

        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        if (!$survey->getStage()->getGraderUsers()->contains($currentUser)) {
            return $app['twig']->render('errors/403.html.twig');
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

        $answerForm = $formFactory->create(AddSurveyForm::class, $survey, ['edition' => false, 'survey' => $survey, 'user' => $currentUser]);


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



        return $app['twig']->render('answer_survey.html.twig',
            ['surId' => $survey->getId(),
                'survey' => $survey,
                'form' => $answerForm->createView(),
                'edition' => $edition

            ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $stgId
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("}/activity/{stgId}/grade", name="newStageGrade")
     */
    public function gradeAction(Request $request, Application $app, $stgId)
    {
        $em = self::getEntityManager();
        $formFactory = $app['form.factory'];
        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        $repoAU = $em->getRepository(ActivityUser::class);
        $repo1 = $em->getRepository(Answer::class);
        $stage = $em->getRepository(Stage::class)->find($stgId);
        if($stage->getSurvey()!=null){
            return $this->redirectToRoute('answerSurvey');
        }
        // Prevent access if current user is not a grader (i.e has none non-passive participations)
        if (!$stage->getGraderUsers()->contains($currentUser)) {
            return $app['twig']->render('errors/403.html.twig');
        } else {
            $this->updateStageGrades($stage);

            $userParticipations = $repoAU->findBy(['stage' => $stage, 'usrId' => $currentUser->getId()]);
            $formFactory = $app['form.factory'];
            $stageUniqueParticipationsForm = $formFactory->create(StageUniqueParticipationsType::class, $stage, ['standalone' => true, 'mode' => 'grade']);
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
                //return $app->redirect($app['url_generator']->generate('myActivities'));
                return $this->redirectToRoute('myActivities',['sortingType' => 'p']);
            }

            return $app['twig']->render('activity_grade_new.html.twig',
                [
                    'stage' => $stage,
                    'form' => $stageUniqueParticipationsForm->createView(),
                ]);
        }
    }

    public function newGradeAction(Request $request, Application $app, $stgId)
    {

        $em = self::getEntityManager();
        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }
        $repoO = $em->getRepository(Organization::class);
        $repoAU = $em->getRepository(ActivityUser::class);
        $repoG = $em->getRepository(Grade::class);
        $stage = $em->getRepository(Stage::class)->find($stgId);
        $organization = $repoO->find($currentUser->getOrgId());
        $id = $currentUser->getId();
        $actOrganization = $stage->getActivity()->getOrganization();
        $userIsParticipant = false;

        $existingParticipants = new ArrayCollection($repoAU->findBy(['stage' => $stage], ['criterion' => 'DESC', 'type' => 'DESC']));
        foreach ($existingParticipants as $existingParticipant) {
            if ($existingParticipant->getUsrId() == $currentUser->getId()) {
                $userIsParticipant = true;
                break;
            }
        }

        if (!$userIsParticipant) {
            return $app['twig']->render('errors/403.html.twig');
        } else {

            //Get all participants

            $existingParticipants = new ArrayCollection($repoAU->findBy(['stage' => $stage], ['criterion' => 'DESC', 'type' => 'DESC']));

            $existingTeamParticipants = $existingParticipants->matching(Criteria::create()->where(Criteria::expr()->neq("team", null))->orderBy(['team' => Criteria::ASC]));
            $existingUserParticipants = $existingParticipants->matching(Criteria::create()->where(Criteria::expr()->eq("team", null)));
            //$existingTeamParticipants = $repoAU->findBy(['stage' => $stage, 'usrId' => null], ['criterion' => 'DESC', 'type' => 'DESC']);
            //$existingUserParticipants = $repoAU->findBy(['stage' => $stage, 'team' => null], ['criterion' => 'DESC', 'type' => 'DESC']);
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
                        foreach ($selectedTeam->getActiveTeamUsers() as $participantTeamUser) {

                            if ($participantTeamUser->getUser() == $currentUser) {

                                foreach ($selectedTeam->getActiveTeamUsers() as $teamUser) {
                                    $existingParticipantUsersId['teaId'][] = $selectedTeam->getId();
                                    $existingParticipantUsersId['usrId'][] = $teamUser->getUsrId();
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
                            ->setParticipant($currentUserParticipation)
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
                                    ->setParticipant($currentUserParticipation)
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

            return $app['twig']->render('activity_grade.html.twig',
                    [
                        'stage' => $stage,
                        'currentUserPersonGrades' => $currentUserPersonGrades,
                        'currentUserTeamGrades' => $currentUserTeamGrades,
                    ]);


        }
    }

    public function updateStageGrades($stage)
    {

        global $app;
        $em = self::getEntityManager();
        $currentUser = self::getAuthorizedUser();
        $repoAU = $em->getRepository(ActivityUser::class);
        $repoG = $em->getRepository(Grade::class);

        //Get all participants

        $totalParticipants = new ArrayCollection($repoAU->findBy(['stage' => $stage], ['criterion' => 'DESC', 'type' => 'DESC']));
        $existingParticipants = $totalParticipants->matching(Criteria::create()->where(Criteria::expr()->neq("criterion", null))->orWhere(Criteria::expr()->neq("survey", null)));
        $existingTeamParticipants = $existingParticipants->matching(Criteria::create()->where(Criteria::expr()->neq("team", null))->orderBy(['team' => Criteria::ASC]));
        $existingUserParticipants = $existingParticipants->matching(Criteria::create()->where(Criteria::expr()->eq("team", null)));
        //$existingTeamParticipants = $repoAU->findBy(['stage' => $stage, 'usrId' => null], ['criterion' => 'DESC', 'type' => 'DESC']);
        //$existingUserParticipants = $repoAU->findBy(['stage' => $stage, 'team' => null], ['criterion' => 'DESC', 'type' => 'DESC']);
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
                    foreach ($selectedTeam->getActiveTeamUsers() as $participantTeamUser) {

                        if ($participantTeamUser->getUser() == $currentUser) {

                            foreach ($selectedTeam->getActiveTeamUsers() as $teamUser) {
                                $existingParticipantUsersId['teaId'][] = $selectedTeam->getId();
                                $existingParticipantUsersId['usrId'][] = $teamUser->getUsrId();
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

    //Get the results of an activity for intended users

    /**
     * @param Request $request
     * @param Application $app
     * @param $actId
     * @return RedirectResponse
     * @throws ORMException
     * @Route("/activity/{actId}/results", name="activityResults")
     */
    public function displayResultsAction(Request $request, Application $app, $actId)
    {
        set_time_limit(300);
        $em = $app['orm.em'];
        $repoA = $em->getRepository(Activity::class);
        $repoAU = $em->getRepository(ActivityUser::class);
        $repoGI = $em->getRepository(GeneratedImage::class);
        $user = self::getAuthorizedUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login');
        }

        $userId = $user->getId();
        $userRole = $user->getRole();

        //Get users associated to activity
        $activity = $repoA->find($actId);
        $organization = $user->getOrganization();
        $actOrganization = $activity->getOrganization();
        $participantStatuses = [];
        $stagesFnAbbr = [];
        $stagesData = [];

        if ($organization != $actOrganization && $user->getRole() != 4) {
            return $app['twig']->render('errors/403.html.twig');
        } else {

            $stagesSumContributiveWeights = [];
            $stagesSumEvaluationWeights = [];
            $stageSumBinaryWeights = [];


            foreach ($activity->getStages() as $stage) {

                if(count($stage->getCriteria()) > 1){

                if ($stage->getStatus() >= 2) {

                    $stagesAccess = [];
                    $stageHasResults = [];
                    $userStatus = [];
                    $totalData = [];
                    $stageBinaryWeights = 0;
                    $stageEvaluationWeights = 0;
                    $stageContributionWeights = 0;
                    $userParticipation = $repoAU->findOneBy(['criterion' => $stage->getCriteria()->first(), 'usrId' => $userId]);

                    // We get current participant status : null if is not involved in the process, otherwise its stage status
                    $participantStatus = $userParticipation ? $userParticipation->getStatus() : null;

                    $fnAbbr = [];
                    $this->computeStageResults($stage,1,false);
                    foreach ($stage->getCriteria() as $criterion) {
                        switch ($criterion->getType()) {
                            case 3:
                                $stageBinaryWeights += $criterion->getWeight();
                                break;
                            case 1:
                                $stageEvaluationWeights += $criterion->getWeight();
                                break;
                            case 0:
                                $stageContributionWeights += $criterion->getWeight();
                                break;
                            default:
                                break;
                        }
                    }

                    $stagesSumEvaluationWeights[] = $stageEvaluationWeights;
                    $stagesSumContributiveWeights[] = $stageContributionWeights;
                    $stageSumBinaryWeights[] = $stageBinaryWeights;
                    $participantStatuses[] = $participantStatus;

                } else {
                    $participantStatuses[] = null;
                }
            }

            }

            // Sort by desc participant status to display released stages in priority
            arsort($participantStatuses);
            $formFactory = $app['form.factory'];
            $activityReportForm = $formFactory->create(ActivityReportForm::class, null, ['standalone' => true, 'activity' => $activity]);
            $activityReportForm->handleRequest($request);

            try {
                return $app['twig']->render('activity_results.html.twig',
                    [
                        //'stagesAccess' => $stageAccess,
                        'participantStatus' => $participantStatuses,
                        'generatedImages' => $repoGI->findBy(['actId' => $activity->getId()]),
                        //'drawnStageIndex' => $drawnStageIndex,
                        'activity' => $activity,
                        //'data' => isset($stagesData) ? $stagesData : null,
                        //'fnAbbr' => $stagesFnAbbr,
                        'form' => $activityReportForm->createView(),
                        'stagesSumContributiveWeights' => $stagesSumContributiveWeights,
                        'stagesSumEvaluationWeights' => $stagesSumEvaluationWeights,
                        'stagesSumBinaryWeights' => $stageSumBinaryWeights,

                    ]
                );
            } catch (\Exception $e) {
                print_r($e->getFile() . ' ' . $e->getLine() . ': ' . $e->getMessage());
                die;
            }


        }
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $stgId
     * @param $usrId
     * @Route("/stage/{stgId}/request/{usrId}/results", name="requestResults")
     */
    public function requestResultsAction(Request $request, Application $app, $stgId, $usrId)
    {

        $em = self::getEntityManager();
        $repoS = $em->getRepository(Stage::class);

    }

    // Function which saves report PNG images for generated PDF
    // (by default, if graph is not concerning activity/stage/criteria, the related value remains to -1. Type equals 0 for result graph, and 1 for distance graph)

    /**
     * @param Request $request
     * @param Application $app
     * @param $actId
     * @param $stgId
     * @param $crtId
     * @param $type
     * @param $overview
     * @param $equalEntries
     * @return bool|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/report/save/{actId}/{stgId}/{crtId}/{type}/{overview}/{equalEntries}", name="saveImageActivityReport")
     */
    public function saveImageActivityReportAction(Request $request, Application $app, $actId, $stgId, $crtId, $type, $overview, $equalEntries)
    {

        $em = self::getEntityManager();
        $repoI = $em->getRepository(GeneratedImage::class);
        $actValue = ($actId != -1) ? $actId : null;
        $stgValue = ($stgId >= 0) ? $stgId : null;
        $crtValue = ($crtId >= 0) ? $crtId : null;
        $currUser = self::getAuthorizedUser();
        if (!$currUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $currUserRole = $currUser->getRole();

        if ($currUserRole == 4) {
            $currUserRole = 1;
        }

        /* create a /img/reports subdirectory of /web/
        change DTB type to string
        generate $fileName
        generate a $path with $filename we insert into the database
        save the file in the above pointed out location
        rewrite code in generateActivityReport to get the filepath and then the file from the create subdirectory
         */

        $generatedImage = $repoI->findOneBy(['actId' => $actValue, 'stgId' => $stgValue, 'crtId' => $crtValue, 'type' => $type, 'overview' => $overview, 'all' => $equalEntries, 'role' => $currUserRole]) ?: new GeneratedImage;
        $generatedImage->setActId($actValue)->setStgId($stgValue)->setCrtId($crtValue)->setType($type)->setOverview($overview)->setAll($equalEntries)->setRole($currUserRole)->setValue($_POST['URI_value']);
        if ($currUserRole == 3) {
            $generatedImage->setUsrId($currUser->getId());
        }
        $em->persist($generatedImage);
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
        $em = self::getEntityManager();
        $repoA = $em->getRepository(Activity::class);
        $repoAU = $em->getRepository(ActivityUser::class);
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
            $hasAccess = $activity->getParticipants()->exists(function(int $i, ActivityUser $p) use ($subordinates){return in_array($p->getDirectUser(), $subordinates);});
        } else {
            $stage = $activity->getStages()[$stgIndex];
            $hasAccess = $stage->getParticipants()->exists(function(int $i, ActivityUser $p) use ($subordinates){return in_array($p->getDirectUser(), $subordinates);});
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

            $isPublished = $repoAU->findBy(['activity' => $activity, 'status' => [0, 1, 2, 3]]) == null;
            // if ($crtIndex == -1) {
            //     $isViewable = $isPublished || $user->getRole() != 3 && $repoAU->findBy(['activity' => $activity, 'status' => [0,1,2], 'type' => [0,1]]) == null;
            // } else {
            //     $isViewable = ($user->getRole() != 3 && $oneComputedStage || $user->getRole() == 3 && $oneReleasedStage);
            // }
            $isViewable = true;

            if ($isViewable) {

                if ($crtIndex == -2) {
                    // Defining first row, being all activity unique non TP participants

                    $rowData = [];
                    $rowData[] = 'Participants';

                    $activityParticipants = $repoAU->findBy(['activity' => $activity], ['usrId' => 'ASC']);

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

                        $stageParticipants = $repoAU->findBy(['stage' => $stage], ['usrId' => 'ASC']);
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

                    $activityParticipants = $repoAU->findBy(
                        ['activity' => $activity],
                        ['usrId' => 'ASC']
                    );
                    $uniqueActivityParticipants = [];
                    $actualId = 0;
                    $nbTPs = 0;
                    $nbFBCriteria = 0;

                    foreach ($activityParticipants as $activityParticipant) {
                        if ($activityParticipant instanceof ActivityUser) {
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
                        if ($uniqueActivityParticipant instanceof ActivityUser) {
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

                    $stageParticipants = $repoAU->findBy(['stage' => $stage], ['usrId' => 'ASC']);

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

            function actManagerCanPublish(EntityRepository $repoAU, Activity $activity, User $user, int $stgIndex = -1)
            {
                if ($stgIndex == -1) {
                    $userId = $user->getId();

                    return $activity->getParticipants()->filter(function (ActivityUser $e) use ($userId) {
                        return $e->getUsrId() == $userId;
                    })->forAll(function (int $i, ActivityUser $e) {
                        return $e->isLeader();
                    });
                } else {
                    /** @var Stage */
                    $stage = $activity->getStages()->get($stgIndex);
                    /** @var ActivityUser */
                    $activityUser = $repoAU->findOneBy(['stage' => $stage, 'usrId' => $user->getId()]);

                    return $activityUser and $activityUser->isLeader();
                }
            }

            $data['isPublishable'] = (
                $userRole == 4// root
                 or
                $userRole == 1// admin
                 or
                ($userRole == 2 and actManagerCanPublish($repoAU, $activity, $user, $stgIndex)) // AM & is stage leader
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

    //public function generateTooltipContent(Application $app, )

    /**
     * @param Application $app
     * @param Request $request
     * @param $actId
     * @param $printedElmts
     * @param $isPDFVersion
     * @param $equalEntries
     * @return RedirectResponse
     * @Route("/settings/report/activity/{actId}/{stgIndex}/{crtIndex}/{isPDFVersion}/{equalEntries}",
     *     name="generateActivityReport")
     */
    public function generateActivityReportAction(Application $app, Request $request, $actId, $printedElmts, $isPDFVersion, $equalEntries)
    {

        //Get users associated to activity

        $user = self::getAuthorizedUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login');
        }
        $userId = $user->getId();
        $userRole = $user->getRole();

        //Accessing all repositories

        //Get users associated to activity
        $em = self::getEntityManager();
        $repoAU = $em->getRepository(ActivityUser::class);
        $repoA = $em->getRepository(Activity::class);
        $repoU = $em->getRepository(User::class);
        $repoG = $em->getRepository(Grade::class);
        $repoC = $em->getRepository(Criterion::class);
        $repoR = $em->getRepository(Result::class);
        $repoO = $em->getRepository(Organization::class);
        $repoI = $em->getRepository(GeneratedImage::class);

        $activity = $repoA->find($actId);

        $orgId = $user->getOrgId();
        $organization = $repoO->find($orgId);

        $actOrganization = $activity->getOrganization();
        $participantStatuses = [];
        $stagesFnAbbr = [];
        $stagesData = [];

        foreach ($activity->getStages() as $stage) {

            $stagesAccess = [];
            $stageHasResults = [];
            $userStatus = [];
            $totalData = [];
            //foreach ($stages as $stage) {
            $userParticipation = $repoAU->findOneBy(['criterion' => $stage->getCriteria()->first(), 'usrId' => $userId]);

            // We get current participant status : null if is not involved in the process, otherwise its stage status
            $participantStatus = $userParticipation ? $userParticipation->getStatus() : null;

            if ($stage->getStatus()) {

                //$stageHasResults[] = true;

                //getting all criteria (there is just one so findOneBy is enough)
                $criteria = $stage->getCriteria();
                $nbCriteriaResults = 0;
                $criteriaFnAbbr = [];
                $criteriaData = [];

                foreach ($criteria as $criterion) {

                    //getting all stage participants

                    //getting current participant status, and insert it in array for all stages
                    //$participantStatus = $repoAU->findOneBy(['criterion' => $criterion, 'usrId' => $userId])->getStatus();
                    //$participantStatuses[] = $participantStatus;
                    $commentsMatrix = [];
                    $gradesMatrix = [];
                    $comments = [];
                    $gradeValues = [];
                    $fnAbbr = [];

                    $ub = $criterion->getUpperbound();
                    $lb = $criterion->getLowerbound();
                    $range = $ub - $lb;

                    //getting all grades
                    $grades = $repoG->findByCriterion($criterion);

                    $criterionDataMatrix = [];
                    $gradesMatrix = [];
                    $criterionData = [];
                    $gradeValues = [];

                    //getting all activity users results (old : ordered by result, not by DB entry to keep same user at same place)
                    //$activityUsers = $repoAU->findBy(['criterion' => $criterion], ['absoluteWeightedResult' => 'DESC']);
                    $activityUsers = $repoAU->findBy(['criterion' => $criterion], ['type' => 'DESC']);

                    //$results = $repoR->find

                    //get participant id order in a array to sort criterionData by the same method used in sorting participants
                    $orderedIds = [];
                    foreach ($activityUsers as $activityUser) {
                        $orderedIds[] = $activityUser->getUsrId();
                    }

                    //Get participants feedbacks on his performance, sorted accordingly to retrieve relevant results
                    foreach ($activityUsers as $key => $activityUser) {

                        $userGrades = $repoG->findBy(
                            ['criterion' => $criterion,
                                'participant' => $activityUser],
                            ['tp' => 'ASC']);

                        $userOrderedGrades = [];
                        foreach ($orderedIds as $orderedId) {
                            foreach ($userGrades as $userGrade) {
                                if ($userGrade->getGradedUsrId() == $orderedId) {
                                    $userOrderedGrades[] = $userGrade;
                                    break;
                                }
                            }
                        }

                        //print_r($userOrderedGrades);
                        //die;

                        $criterionDataRowMatrix = [];
                        $gradesRowMatrix = [];
                        foreach ($userOrderedGrades as $gradeElmt) {
                            $commentsRowMatrix[] = $gradeElmt->getComment();
                            $gradesRowMatrix[] = $gradeElmt->getValue();
                        }

                        $commentsMatrix[] = $commentsRowMatrix;
                        $gradesMatrix[] = $gradesRowMatrix;
                    }

                    //print_r($commentsMatrix);
                    //die;

                    for ($i = 0; $i < count($activityUsers); $i++) {
                        $comments[$i] = [];
                        for ($j = 0; $j < count($activityUsers); $j++) {
                            $comments[$i][] = $commentsMatrix[$j][$i];
                            $gradeValues[$i][] = $gradesMatrix[$j][$i];
                        }
                    }

                    $renderedData = [];

                    foreach ($activityUsers as $key => $activityUser) {

                        $id = $activityUser->getUsrId();
                        $user = $repoU->find($id);
                        $firstname = $user->getFirstname();
                        $lastname = $user->getLastname();
                        $isNotTP = $activityUser->getType();
                        $wResult = $activityUser->getAbsoluteWeightedResult();
                        $eResult = $activityUser->getAbsoluteEqualResult();
                        $wDevRatio = $activityUser->getWeightedDevRatio();
                        $eDevRatio = $activityUser->getEqualDevRatio();

                        $renderedData[] =
                            [
                            'firstname' => $firstname,
                            'lastname' => $lastname,
                            'wResult' => $wResult,
                            'eResult' => $eResult,
                            'isNotTP' => $isNotTP,
                            'wDevRatio' => $wDevRatio,
                            'eDevRatio' => $eDevRatio,
                            'comments' => $comments[$key],
                            'grades' => $gradeValues[$key],
                        ];

                        //print_r($renderedData);
                        //die;

                        //Overall grade is computed (just for display)
                        /*
                        $avgActivityResult = 0;
                        for ($i = 0; $i < count($weights); $i++) {
                        $avgActivityResult += $weights[$i] * $results[$i] / array_sum($weights);
                        }
                         */
                        //Rearranging
                    }

                    $fnAbbr = [];
                    foreach ($renderedData as $participant) {
                        $fnAbbr[] = substr($participant['firstname'], 0, 1);
                    }

                    for ($m = 0; $m < count($fnAbbr) - 1; $m++) {
                        $p = 2;
                        for ($n = $m + 1; $n < count($fnAbbr); $n++) {
                            if ($fnAbbr[$n] == $fnAbbr[$m]) {
                                $fnAbbr[$n] = $fnAbbr[$n] . $p;
                                $p++;
                            }
                        }
                        if ($p > 2) {
                            $fnAbbr[$m] = $fnAbbr[$m] . '1';
                        }
                    }
                }

                $criteriaFnAbbr[] = $fnAbbr;
                $totalData[] = $renderedData;
                $wMeanGrade[] = isset($renderedData) ? $criterion->getGlobalAbsWeightedResult() : null;
                $eMeanGrade[] = isset($renderedData) ? $criterion->getGlobalAbsEqualResult() : null;
                $wMeanDevRatio[] = isset($renderedData) ? $criterion->getWeightedDistanceRatio() : null;
                $eMeanDevRatio[] = isset($renderedData) ? $criterion->getEqualDistanceRatio() : null;
                $lowerbound[] = isset($renderedData) ? $criterion->getLowerbound() : '';
                $upperbound[] = isset($renderedData) ? $criterion->getUpperbound() : '';
                $pureFB[] = ($criterion->getType() == 2) ?: false;
                $contrib[] = ($criterion->getType() == 0) ?: false;

            } else {

                $stageHasResults[] = false;

            }

            $stagesFnAbbr[] = $criteriaFnAbbr;
            $stagesData[] = $criteriaData;
            $participantStatuses[] = $participantStatus;
        }

        $graphData = self::provideGraphDataAction($app, $request, $activity->getId(), $stgIndex, $crtIndex, $equalEntries);

        $stgValue = ($stgIndex != -1) ? $activity->getStages()->get($stgIndex)->getId() : null;
        $crtValue = ($crtIndex != -1) ? (($stgIndex != -1) ? $activity->getStages()->get($stgIndex)->getCriteria()->get($crtIndex)->getId() : $activity->getStages()->first()->getCriteria()->get($crtIndex)->getId()) : null;

        /*  echo '<pre>'; print_r($actId . ':'  . $stgValue .  ':' . $crtValue); echo '</pre>';
        die; */
        $criterionType = $repoC->find($crtValue)->getType();
        if ($equalEntries == 1) {
            $perf_graph_2 = $repoI->findOneBy(['actId' => $actId, 'stgId' => $stgValue, 'crtId' => $crtValue, 'type' => 0, 'all' => 1])->getValue();
            $dist_graph_2 = ($criterionType == 3) ? null : $repoI->findOneBy(['actId' => $actId, 'stgId' => $stgValue, 'crtId' => $crtValue, 'type' => 1, 'all' => 1])->getValue();
        } else {
            $perf_graph_2 = null;
            $dist_graph_2 = null;
        }

        $commentCrtValue = [];
        if ($crtIndex == -1) {
            if ($stgIndex == -1) {
                foreach ($activity->getStages() as $stage) {
                    foreach ($stage->getCriteria() as $criterion) {
                        $commentCrtValue[] = $criterion->getId();
                    }
                }
            } else {
                foreach ($activity->getStages()->get($stgIndex)->getCriteria() as $criterion) {
                    $commentCrtValue[] = $criterion->getId();
                }
            }
        } else {
            $commentCrtValue[] = ($stgIndex == -1) ? $activity->getStages()->first()->getCriteria()->get($crtIndex) : $activity->getStages()->get($stgIndex)->getCriteria()->get($crtIndex);

        }
        //$commentCrtValue = ($crtIndex != -1) ? (($stgIndex != -1) ? $activity->getStages()->get($stgIndex)->getCriteria()->get($crtIndex)->getId() : $activity->getStages()->first()->getCriteria()->get($crtIndex)->getId()) : ($stgIndex != -1) ? $activity->getStages()->get($stgIndex)->getCriteria()->first()->getId() : null;

        $comments = $repoAU->findBy(['criterion' => $commentCrtValue]);

        try {
            $html = $app['twig']->render('activity_report.html.twig',
                [
                    //'stagesAccess' => $stageAccess,
                    'participantStatus' => $participantStatuses,
                    'activity' => $activity,
                    'perf_graph' => $repoI->findOneBy(['actId' => $actId, 'stgId' => $stgValue, 'crtId' => $crtValue, 'type' => 0, 'all' => 0])->getValue(),
                    'dist_graph' => ($criterionType == 3) ? null : $repoI->findOneBy(['actId' => $actId, 'stgId' => $stgValue, 'crtId' => $crtValue, 'type' => 1, 'all' => 0])->getValue(),
                    'perf_graph_2' => $perf_graph_2,
                    'dist_graph_2' => $dist_graph_2,
                    'data' => isset($stagesData) ? $stagesData : null,
                    'fnAbbr' => $stagesFnAbbr,
                    'graphData' => $graphData,
                    'comments' => $comments,
                ]
            );} catch (\Exception $e) {
            print_r($e->getMessage());
            die;
        }

        if ($isPDFVersion == 0) {
            return $html;
        } else {

            try {

                $domPdf = new Dompdf;

                $domPdf->loadHtml($html);
                // (Optional) Setup the paper size and orientation
                $domPdf->setPaper('A4', 'portrait');

                // Render the HTML as PDF
                $domPdf->render();

                // Output the generated PDF to Browser

                $d = new \DateTime;
                return $domPdf->stream($d->format("Ymd") . '_report_' . str_replace(" ", "_", $activity->getName()) . '.pdf');
            } catch (\Exception $e) {
                print_r($e->getMessage());
                die;
            }
            //return true;
        }

    }

    /**
     * @param Application $app
     * @param Request $request
     * @param $isPDFVersion
     * @return RedirectResponse
     * @Route("}/settings/report/activity/{isPDFVersion", name="generateNewActivityReport")
     */
    public function newGenerateActivityReportAction(Application $app, Request $request, $isPDFVersion)
    {

        $printingElmts = [];
        $em = self::getEntityManager();
        $repoA = $em->getRepository(Activity::class);
        $repoS = $em->getRepository(Stage::class);
        $repoC = $em->getRepository(Criterion::class);
        $repoI = $em->getRepository(GeneratedImage::class);
        $repoAU = $em->getRepository(ActivityUser::class);
        $repoG = $em->getRepository(Grade::class);
        $user = self::getAuthorizedUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login');
        }
        $userRole = $user->getRole();
        if ($userRole == 4) {
            $userRole = 1;
        }
        $nbActivityCriteria = 0;

        $activity = $repoA->find($_POST['aid']);
        $isPrintableActivity = ($activity->getStatus() >= 2 && $userRole != 3 || $activity->getStatus() == 3 && $userRole == 3);
        foreach ($activity->getStages() as $stage) {
            $nbActivityCriteria += count($stage->getCriteria());
        }
        $nbActivityParticipations = count($activity->getParticipants());

        $stgElmt = null;
        $stgElmts = [];
        $printAll = false;
        $printAllBelowActivity = false;
        $printAllBelowStage = false;
        $isAggregatedView = false;

        $actElmts = [];
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'print') !== false) {
                $actElmt = [];
                $inputNameElmts = explode("_", $key);
                $actElmt['stgIndex'] = $inputNameElmts[1];
                $actElmt['crtIndex'] = $inputNameElmts[2];
                $actElmts[] = $actElmt;
            }
        }

        $printedElementsHaveTeam = false;

        // Getting printing configuration

        foreach ($actElmts as $key => $actElmt) {

            $printingElmt = [];

            if ($actElmt['stgIndex'] == -2) {
                $existingImages = $repoI->findBy(['actId' => $activity->getId(), 'role' => $userRole, 'type' => 0]);
                $printAll = true;

            } else {

                if ($actElmt['stgIndex'] != $stgElmt) {
                    $beginningOfStage = true;
                    $stgElmt = $actElmt['stgIndex'];
                    $crtElmt = $actElmt['crtIndex'];

                    if ($stgElmt == -1) {
                        $comparedStageElmt = $stgElmt;
                        for ($j = $key + 1; $j < count($actElmts); $j++) {
                            if ($actElmts[$j]['stgIndex'] != $comparedStageElmt) {
                                $comparedStageElmt = $actElmts[$j]['stgIndex'];
                                $stgElmts[] = $actElmts[$j]['stgIndex'];
                            }
                        }

                        if (count($stgElmts) != count($activity->getStages())) {
                            $printAllBelowActivity = true;
                        }

                    } else {

                        $crtElmts = [];

                        for ($j = $key; $j < count($actElmts); $j++) {
                            if ($actElmts[$j]['stgIndex'] == $stgElmt) {
                                $crtElmts[] = $actElmts[$j]['crtIndex'];
                            }
                        }

                        if ($crtElmts[0] == (-1 ||   - 2)) {
                            $beginningOfStage = true;
                            $nbDiscardedElmts = 1;
                            if (count($crtElmts) > 1 && $crtElmts[1] == -2) {
                                $nbDiscardedElmts = 2;
                            }
                            if (count($crtElmts) - $nbDiscardedElmts == count($activity->getStages()->get($stgElmt)->getCriteria())) {
                                $printAllBelowStage = false;
                            } else {
                                $printAllBelowStage = true;
                            }
                        } else {
                            $beginningOfStage = false;
                            $printAllBelowStage = false;
                        }
                    }
                } else {
                    $beginningOfStage = false;
                }

                $stgValue = ($actElmt['stgIndex'] >= 0) ? $activity->getStages()->get($actElmt['stgIndex'])->getId() : null;
                $crtValue = ($actElmt['crtIndex'] >= 0) ? $activity->getStages()->get($actElmt['stgIndex'])->getCriteria()->get($actElmt['crtIndex'])->getId() : null;
                $stgImg = (count($activity->getStages()) == 1 && $stgValue != null) ? null : $stgValue;
                $isAggregatedView = ($actElmt['crtIndex'] == -2);
                $existingImages = $repoI->findBy(['actId' => $activity->getId(), 'stgId' => $stgImg, 'crtId' => $crtValue, 'role' => $userRole, 'type' => 0, 'overview' => $isAggregatedView]);
            }

            //selecting grades/participants, and printing images

            foreach ($existingImages as $existingImage) {

                $stage = (!$printAll && $stgImg === null) ? ($activity->getStages()->first()) : $repoS->find($existingImage->getStgId());
                $criterion = $repoC->find($existingImage->getCrtId());
                $stgValue = ($stage == null) ? null : $stage->getId();
                $crtValue = ($criterion == null) ? null : $criterion->getId();
                $printingElmt['stage'] = $stage;
                $printingElmt['criterion'] = $criterion;
                $printingElmt['aggregatedView'] = $isAggregatedView;
                $grades = null;
                $participants = null;

                switch ($userRole) {
                    case 1:
                    case 2:

                        if (!$printAll) {

                            if ($printAllBelowActivity) {
                                $trigger = false;
                                if ($criterion == null && $stage == null && $trigger == false) {
                                    $grades = (isset($_POST['settings_comments'])) ? $repoG->findBy(['activity' => $activity], ['team' => 'ASC', 'participant' => 'ASC', 'gradedTeaId' => 'ASC', 'gradedUsrId' => 'ASC']) : null;
                                    $participants = (isset($_POST['settings_objectives'])) ? $repoAU->findBy(['activity' => $activity], ['usrId' => 'ASC']) : null;
                                    $trigger = true;
                                }
                            } else {

                                if ($printAllBelowStage) {
                                    $trigger = false;
                                    if ($criterion == null && $trigger == false) {
                                        $grades = (isset($_POST['settings_comments'])) ? $repoG->findBy(['stage' => $stage], ['team' => 'ASC', 'participant' => 'ASC', 'gradedTeaId' => 'ASC', 'gradedUsrId' => 'ASC']) : null;
                                        $participants = (isset($_POST['settings_objectives'])) ? $repoAU->findBy(['stage' => $stage], ['usrId' => 'ASC']) : null;
                                        $trigger = true;
                                    }
                                } else {
                                    $grades = (isset($_POST['settings_comments'])) ? $repoG->findBy(['criterion' => $criterion], ['team' => 'ASC', 'participant' => 'ASC', 'gradedTeaId' => 'ASC', 'gradedUsrId' => 'ASC']) : null;
                                    $participants = (isset($_POST['settings_objectives'])) ? $repoAU->findBy(['criterion' => $criterion], ['usrId' => 'ASC']) : null;
                                }
                            }

                        } else {

                            if ($isPrintableActivity) {
                                if ($stage == null && $criterion == null) {
                                    $grades = (isset($_POST['settings_comments'])) ? $repoG->findBy(['activity' => $activity], ['team' => 'ASC', 'participant' => 'ASC', 'gradedTeaId' => 'ASC', 'gradedUsrId' => 'ASC']) : null;
                                    $participants = (isset($_POST['settings_objectives'])) ? $repoAU->findBy(['activity' => $activity], ['usrId' => 'ASC']) : null;
                                }
                            } else {
                                if ($criterion == null) {
                                    $grades = (isset($_POST['settings_comments'])) ? $repoG->findBy(['stage' => $stage], ['team' => 'ASC', 'participant' => 'ASC', 'gradedTeaId' => 'ASC', 'gradedUsrId' => 'ASC']) : null;
                                    $participants = (isset($_POST['settings_objectives'])) ? $repoAU->findBy(['stage' => $stage], ['usrId' => 'ASC']) : null;
                                }
                            }
                        }

                        $perfGraph = (isset($_POST['settings_perf'])) ? $repoI->findOneBy(['actId' => $activity->getId(), 'stgId' => $existingImage->getStgId(), 'crtId' => $existingImage->getCrtId(), 'role' => $userRole, 'type' => 0, 'overview' => $isAggregatedView])->getValue() : null;
                        if (isset($criterion) && ($criterion->getType() == 3 || count($criterion->getParticipants()) == 1) || isset($stage) && count($stage->getParticipants()) == count($stage->getCriteria()) || $nbActivityParticipations == $nbActivityCriteria) {
                            $distGraph = null;
                        } else {
                            $distGraph = (isset($_POST['settings_dist'])) ? $repoI->findOneBy(['actId' => $activity->getId(), 'stgId' => $existingImage->getStgId(), 'crtId' => $existingImage->getCrtId(), 'role' => $userRole, 'type' => 1, 'overview' => $isAggregatedView])->getValue() : null;
                        }
                        break;
                    case 3:

                        if (!$printAll) {

                            if ($printAllBelowActivity) {

                                $trigger = false;
                                if ($criterion == null && $stage == null && $trigger == false) {
                                    $participants = (isset($_POST['settings_objectives'])) ? $repoAU->findBy(['activity' => $activity, 'usrId' => $user->getId()], ['usrId' => 'ASC']) : null;

                                    $grades = (isset($_POST['settings_comments'])) ? (
                                        ($participants[0]->getTeam() == null) ?
                                        $repoG->findBy(['activity' => $activity, 'gradedUsrId' => $user->getId()]) :
                                        $repoG->findBy(['activity' => $activity, 'gradedTeaId' => $team->getId()])
                                    ) : null;
                                    $trigger = true;
                                }
                            } else {

                                if ($printAllBelowStage) {
                                    $trigger = false;
                                    if ($criterion == null && $trigger == false) {
                                        $participants = (isset($_POST['settings_objectives'])) ? $repoAU->findBy(['stage' => $stage, 'usrId' => $user->getId()], ['usrId' => 'ASC']) : null;
                                        $grades = (isset($_POST['settings_comments'])) ? (
                                            ($participants[0]->getTeam() == null) ?
                                            $repoG->findBy(['stage' => $stage, 'gradedUsrId' => $user->getId()]) :
                                            $repoG->findBy(['stage' => $stage, 'gradedTeaId' => $team->getId()])
                                        ) : null;
                                        $trigger = true;
                                    }
                                } else {
                                    $participants = (isset($_POST['settings_objectives'])) ? $repoAU->findBy(['criterion' => $criterion, 'usrId' => $user->getId()], ['usrId' => 'ASC']) : null;
                                    $grades = (isset($_POST['settings_comments'])) ? (
                                        ($participants[0]->getTeam() == null) ?
                                        $repoG->findBy(['criterion' => $criterion, 'gradedUsrId' => $user->getId()]) :
                                        $repoG->findBy(['criterion' => $criterion, 'gradedTeaId' => $team->getId()])
                                    ) : null;
                                }

                            }
                        } else {

                            $participants = (isset($_POST['settings_objectives']) && $criterion == null) ? $repoAU->findBy(['criterion' => $criterion, 'usrId' => $user->getId()], ['usrId' => 'ASC']) : null;
                            $grades = (isset($_POST['settings_comments']) && $criterion == null) ? (
                                ($participants[0]->getTeam() == null) ?
                                $repoG->findBy(['criterion' => $criterion, 'gradedUsrId' => $user->getId()]) :
                                $repoG->findBy(['criterion' => $criterion, 'gradedTeaId' => $team->getId()])
                            ) : null;
                        }

                        $perfGraph = (isset($_POST['settings_perf'])) ? $repoI->findOneBy(['actId' => $activity->getId(), 'stgId' => $existingImage->getStgId(), 'crtId' => $existingImage->getCrtId(), 'role' => $userRole, 'type' => 0, 'usrId' => $user->getId()])->getValue() : null;
                        if (isset($criterion) && ($criterion->getType() == 3 || count($criterion->getParticipants()) == 1) || isset($stage) && count($stage->getParticipants()) == count($stage->getCriteria()) || $nbActivityParticipations == $nbActivityCriteria) {
                            $distGraph = null;
                        } else {
                            $distGraph = (isset($_POST['settings_dist'])) ? $repoI->findOneBy(['actId' => $activity->getId(), 'stgId' => $existingImage->getStgId(), 'crtId' => $existingImage->getCrtId(), 'role' => $userRole, 'type' => 1, 'usrId' => $user->getId()])->getValue() : null;
                        }
                        break;

                    default:
                        break;
                }

                // Defining all other printing elements (participants, objectives, comments) thanks to selected grades/participants

                $participantsData = null;

                if (count($participants) > 0) {

                    $participantFN = null;
                    $participantLN = null;
                    $participantAFN = null;
                    $participantALN = null;
                    $participantUserId = null;
                    $collectionParticipants = new ArrayCollection($participants);
                    $participantAbbrs = [];
                    $participantsData = [];

                    foreach ($participants as $theParticipant) {

                        if ($theParticipant->getUsrId() != $participantUserId) {

                            $participantUserId = $theParticipant->getUsrId();
                            $user = $theParticipant->getUser();
                            $participantFN = $user->getFirstname();
                            $participantLN = $user->getLastname();

                            if (!in_array(substr($participantFN, 0, 1) . substr($participantLN, 0, 1), $participantAbbrs)) {
                                $participantAbbr = substr($participantFN, 0, 1) . substr($participantLN, 0, 1);
                            } else {
                                $participantAbbr = substr($participantFN, 0, 1) . substr($participantLN, 0, 2);
                            }

                            $participantAbbrs[] = $participantAbbr;
                            $participantName = $participantFN . ' ' . $participantLN;
                            $participant['name'] = $participantName;

                            if ($theParticipant->getTeam() !== null) {
                                $teamName = $theParticipant->getTeam()->getName();
                                $teamAbbrName = '';
                                $teamWords = explode(" ", $teamName);
                                foreach ($teamWords as $teamWord) {
                                    $teamAbbrName .= strtoupper($teamWord[0]);
                                }
                                $participant['teamName'] = $teamName . ' (' . $teamAbbrName . ')';

                            } else {
                                $participant['teamName'] = null;
                            }

                            $participationStage = null;
                            $userStageParticipations = [];
                            $userParticipations = $collectionParticipants->matching(Criteria::create()->where(Criteria::expr()->eq("usrId", $participantUserId))->orderBy(["stage" => Criteria::ASC, "criterion" => Criteria::ASC]));

                            foreach ($userParticipations as $userParticipation) {
                                $userStageParticipation = [];
                                if ($userParticipation->getStage() != $participationStage) {

                                    $participationStage = $userParticipation->getStage();
                                    $userStageParticipation['stgName'] = $participationStage->getName();
                                    $userStageParticipation['type'] = $userParticipation->getType();
                                    $userStageParticipations[] = $userStageParticipation;

                                }
                            }

                            $participant['participations'] = $userStageParticipations;
                            $participant['abbr'] = $participantAbbr;
                            $participantsData[] = $participant;

                        }

                    }

                }

                $printingElmt['perf'] = $perfGraph;
                $printingElmt['dist'] = $distGraph;
                $printingElmt['participants'] = $participantsData;
                $precommentedParticipants = ($participants != null) ?
                array_filter($participants, function ($item) {
                    if ($item->getPrecomment() !== null && $item->getPrecomment() != "") {
                        return true;
                    }
                    return false;
                }) :
                null;

                $objectives = null;

                if (count($precommentedParticipants) > 0) {

                    $objectives = [];

                    foreach ($precommentedParticipants as $precommentedParticipant) {

                        $objective['stgName'] = $precommentedParticipant->getStage()->getName();
                        $objective['crtName'] = $precommentedParticipant->getCriterion()->getCName()->getName();
                        $precommentedUser = $precommentedParticipant->getUser();
                        $objective['username'] = substr($precommentedUser->getFirstname(), 0, 1) . substr($precommentedUser->getLastname(), 0, 1);
                        $objective['value'] = $precommentedParticipant->getPrecomment();
                        $objectives[] = $objective;
                    }

                }

                $printingElmt['objectives'] = $objectives;

                $comments = null;

                $commentedGrades = ($grades != null) ?
                array_filter($grades, function ($item) {
                    if (($item->getComment()) !== null) {
                        return true;
                    }
                    return false;
                }) :
                null;

                if (count($commentedGrades) > 1) {

                    $comments = [];

                    foreach ($commentedGrades as $commentedGrade) {

                        $comment['stgName'] = $commentedGrade->getStage()->getName();
                        $comment['crtName'] = $commentedGrade->getCriterion()->getCName()->getName();
                        $graderUser = $commentedGrade->getParticipant()->getUser();
                        $comment['graderAbbrUsername'] = substr($graderUser->getFirstname(), 0, 1) . substr($graderUser->getLastname(), 0, 1);
                        if ($commentedGrade->getGradedUsrId() !== null) {
                            $gradedElement = $commentedGrade->getGradedUser($app);
                            $comment['gradedElement'] = 'user';
                            $comment['gradedAbbrElementName'] = substr($gradedElement->getFirstname(), 0, 1) . substr($gradedElement->getLastname(), 0, 1);
                        } else {
                            $teamWords = explode(" ", $commentedGrade->getGradedTeam($app)->getName());
                            $teamAbbrName = '';
                            foreach ($teamWords as $teamWord) {
                                $teamAbbrName .= strtoupper($teamWord[0]);
                            }
                            $comment['gradedAbbrElementName'] = $teamAbbrName;
                            $comment['gradedElement'] = 'team';
                            $printedElementsHaveTeam = true;
                        }
                        $comment['value'] = $commentedGrade->getComment();
                        $comments[] = $comment;
                    }
                }

                $printingElmt['comments'] = $comments;

                $printingElmts[] = $printingElmt;
            }

            $printingElmts = MasterController::array_msort($printingElmts, ['stage' => SORT_ASC, 'criterion' => SORT_ASC]);
        }

        $html = $app['twig']->render('activity_report_new.html.twig',
            [
                'printingElmts' => $printingElmts,
                'actName' => $activity->getName(),
                'actNbStages' => count($activity->getStages()),
                'printedElementsHaveTeam' => $printedElementsHaveTeam,
                //'activity' => $activity,
            ]
        );

        try {

            $domPdf = new Dompdf;

            $domPdf->loadHtml($html);
            // (Optional) Setup the paper size and orientation
            $domPdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $domPdf->render();

            // Output the generated PDF to Browser

            $d = new \DateTime;
            return $domPdf->stream($d->format("Ymd") . '_report_' . str_replace(" ", "_", $activity->getName()) . '.pdf');
        } catch (\Exception $e) {
            print_r($e->getMessage());
            die;
        }

        return $html;

        //return new JsonResponse(['message' => $html],200);

    }

    public function viewActivityReportAction(Application $app, Request $request, $actId)
    {

        //Get users associated to activity
        $em = self::getEntityManager();
        $repoAU = $em->getRepository(ActivityUser::class);
        $repoS = $em->getRepository(Stage::class);
        $repoG = $em->getRepository(Grade::class);
        $repoU = $em->getRepository(User::class);
        $stage = $repoS->find($stgId);
        foreach ($stage->getCriteria() as $criterion) {

            $commentsMatrix = [];
            $gradesMatrix = [];
            $gradesMatrix = [];
            $comments = [];
            $gradeValues = [];

            //getting all activity users results, ordered by result
            $activityUsers = $repoAU->findBy(['criterion' => $criterion], ['absoluteWeightedResult' => 'DESC']);

            //$results = $repoR->find

            //get participant id order in a array to sort comments by the same method used in sorting participants
            $orderedIds = [];
            foreach ($activityUsers as $activityUser) {
                $orderedIds[] = $activityUser->getUsrId();
            }

            //Get participants feedbacks on his performance, sorted accordingly to retrieve relevant results
            foreach ($activityUsers as $key => $activityUser) {

                $userGrades = $repoG->findBy(
                    ['criterion' => $criterion,
                        'participant' => $activityUser],
                    ['tp' => 'ASC']);

                $userOrderedGrades = [];
                foreach ($orderedIds as $orderedId) {
                    foreach ($userGrades as $userGrade) {
                        if ($userGrade->getGradedUsrId() == $orderedId) {
                            $userOrderedGrades[] = $userGrade;
                            break;
                        }
                    }
                }

                //print_r($userOrderedGrades);
                //die;

                $commentsRowMatrix = [];
                $gradesRowMatrix = [];
                foreach ($userOrderedGrades as $gradeElmt) {
                    $commentsRowMatrix[] = $gradeElmt->getComment();
                    $gradesRowMatrix[] = $gradeElmt->getValue();
                }

                $commentsMatrix[] = $commentsRowMatrix;
                $gradesMatrix[] = $gradesRowMatrix;
            }

            //print_r($commentsMatrix);
            //die;

            for ($i = 0; $i < count($activityUsers); $i++) {
                $comments[$i] = [];
                for ($j = 0; $j < count($activityUsers); $j++) {
                    $comments[$i][] = $commentsMatrix[$j][$i];
                    $gradeValues[$i][] = $gradesMatrix[$j][$i];
                }
            }

            $renderedData = [];

            foreach ($activityUsers as $key => $activityUser) {

                $id = $activityUser->getUsrId();
                $user = $repoU->find($id);
                $firstname = $user->getFirstname();
                $lastname = $user->getLastname();
                $isNotTP = $activityUser->getType();
                $wResult = $activityUser->getAbsoluteWeightedResult();
                $eResult = $activityUser->getAbsoluteEqualResult();
                $wDevRatio = $activityUser->getWeightedDevRatio();
                $eDevRatio = $activityUser->getEqualDevRatio();

                $renderedData[] =
                    [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'wResult' => $wResult,
                    'eResult' => $eResult,
                    'isNotTP' => $isNotTP,
                    'wDevRatio' => $wDevRatio,
                    'eDevRatio' => $eDevRatio,
                    'comments' => $comments[$key],
                    'grades' => $gradeValues[$key],
                ];
            }

            $fnAbbr = [];
            foreach ($renderedData as $participant) {
                $fnAbbr[] = substr($participant['firstname'], 0, 1);
            }

            for ($m = 0; $m < count($fnAbbr) - 1; $m++) {
                $p = 2;
                for ($n = $m + 1; $n < count($fnAbbr); $n++) {
                    if ($fnAbbr[$n] == $fnAbbr[$m]) {
                        $fnAbbr[$n] = $fnAbbr[$n] . $p;
                        $p++;
                    }
                }
                if ($p > 2) {
                    $fnAbbr[$m] = $fnAbbr[$m] . '1';
                }
            }

            $criteriaFnAbbr[] = $fnAbbr;
            $totalData[] = $renderedData;
            $wMeanGrade[] = isset($renderedData) ? $criterion->getGlobalAbsWeightedResult() : null;
            $eMeanGrade[] = isset($renderedData) ? $criterion->getGlobalAbsEqualResult() : null;
            $wMeanDevRatio[] = isset($renderedData) ? $criterion->getWeightedDistanceRatio() : null;
            $eMeanDevRatio[] = isset($renderedData) ? $criterion->getEqualDistanceRatio() : null;
            $lowerbound[] = isset($renderedData) ? $criterion->getLowerbound() : '';
            $upperbound[] = isset($renderedData) ? $criterion->getUpperbound() : '';
            $pureFB[] = ($criterion->getType() == 2) ?: false;
            $contrib[] = ($criterion->getType() == 0) ?: false;

        }

        return $app['twig']->render('activity_report.html.twig',
            [
                //'stagesAccess' => $stageAccess,
                'stage' => $stage,
                'data' => isset($totalData) ? $totalData : null,
                'wMeanGrade' => $wMeanGrade,
                'eMeanGrade' => $eMeanGrade,
                'wMeanDevRatio' => $wMeanDevRatio,
                'eMeanDevRatio' => $eMeanDevRatio,
                'lowerbound' => $lowerbound,
                'upperbound' => $upperbound,
                'pureFB' => $pureFB,
                'contrib' => $contrib,
                'fnAbbr' => $criteriaFnAbbr,
                'app' => $app,
            ]
        );
    }

    //Save user grades, determine whether the activity is computable

    /**
     * @param Request $request
     * @param Application $app
     * @param $action
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/myactivities/{action}", name="afterGrading")
     */
    public function newSaveGradesAction(Request $request, Application $app, $action)
    {

        $id = $app['security.token_storage']->getToken()->getUser()->getId();
        $em = self::getEntityManager();
        $repoG = $em->getRepository(Grade::class);
        $repoAU = $em->getRepository(ActivityUser::class);
        $repoU = $em->getRepository(User::class);
        $repoS = $em->getRepository(Stage::class);
        $repoC = $em->getRepository(Criterion::class);
        /*
        foreach ($_POST as $key => $value) {

        if ($key == "stgId") {
        $stgId = intval($value);
        }
        }*/

        // If it is the first time the users grades the activity

        //if ($_POST['update'] == "") {

        $k = 0;
        $l = 0;
        foreach ($_POST as $key => $value) {
            //TODO : case where grade

            $inputNameElmts = explode("_", $key);
            $criId = $inputNameElmts[0];
            $parId = $inputNameElmts[1];
            $criterion = $repoC->find($criId);
            $dataType = $inputNameElmts[2];
            $gradedElmtId = null;
            $gradedUsrId = null;
            if (count($inputNameElmts) > 3) {
                $gradedElmtId = $inputNameElmts[3];
            }
            if (count($inputNameElmts) > 4) {
                $gradedUsrId = $inputNameElmts[4];
            }

            if ($gradedElmtId != null) {
                $grade = ($dataType == 'value' || $dataType == 'comment' || $dataType == 'tpcomment') ?
                $repoG->findOneBy(['participant' => $repoAU->find($parId), 'criterion' => $criterion, 'gradedUsrId' => $gradedElmtId]) :
                $repoG->findOneBy(['participant' => $repoAU->find($parId), 'criterion' => $criterion, 'gradedTeaId' => $gradedElmtId, 'gradedUsrId' => $gradedUsrId]);
            } else {
                $grade = $repoG->findOneBy(['participant' => $repoAU->find($parId), 'criterion' => $criterion, 'gradedUsrId' => null, 'gradedTeaId' => null]);
            }

            if ($dataType == 'value' || $dataType == 'tvalue' || $dataType == 'svalue') {

                // Check if some comments are mandatory, send JSON errors accordingly
                if ($action == 'confirm') {

                    if ($criterion->isForceCommentCompare() == true) {

                        // The four kind of id we can face : 1/ comment to non-TP user, 2/ comment to TP user, 3/ comment to global team, 4/ comment to specific member of a team
                        $condition1 = (array_key_exists($criId . '_' . $parId . '_comment_' . $gradedElmtId, $_POST) && $_POST[$criId . '_' . $parId . '_comment_' . $gradedElmtId] == '');
                        $condition2 = (array_key_exists($criId . '_' . $parId . '_tpcomment_' . $gradedElmtId, $_POST) && $_POST[$criId . '_' . $parId . '_tpcomment_' . $gradedElmtId] == '');
                        $condition3 = (array_key_exists($criId . '_' . $parId . '_tcomment_' . $gradedElmtId, $_POST) && $_POST[$criId . '_' . $parId . '_tcomment_' . $gradedElmtId] == '');
                        $condition4 = (array_key_exists($criId . '_' . $parId . '_tcomment_' . $gradedElmtId . '_' . $gradedUsrId, $_POST) && $_POST[$criId . '_' . $parId . '_tcomment_' . $gradedElmtId . '_' . $gradedUsrId] == '');
                        $condition5 = (array_key_exists($criId . '_' . $parId . '_scomment', $_POST) && $_POST[$criId . '_' . $parId . '_scomment'] == '');

                        // Check if values are commented in case they have criterion forces comments below threshold
                        switch ($criterion->getType()) {

                            case 3:
                                if ($_POST[$criId . '_' . $parId . '_value_' . $gradedElmtId] == 0 && ($condition1 || $condition2 || $condition3 || $condition4)) {
                                    return new JsonResponse(['message' => 'missingComment', 'crtName' => $criterion->getCName()->getName(), 'crtThreshold' => 'No / NOK', 'crtSign' => 'égale à'], 500);
                                }
                                break;

                            default:
                                $threshold = $criterion->getForceCommentValue();

                                switch ($criterion->getForceCommentSign()) {
                                    case 'smaller':
                                        if ($value < $threshold && ($condition1 || $condition2 || $condition3 || $condition4)) {
                                            return new JsonResponse(['message' => 'missingComment', 'crtName' => $criterion->getCName()->getName(), 'crtThreshold' => $threshold, 'crtSign' => '<'], 500);
                                        }
                                        break;
                                    case 'smallerEqual':
                                        if ($value <= $threshold && ($condition1 || $condition2 || $condition3 || $condition4)) {
                                            return new JsonResponse(['message' => 'missingComment', 'crtName' => $criterion->getCName()->getName(), 'crtThreshold' => $threshold, 'crtSign' => '<='], 500);
                                        }
                                        break;
                                    default:
                                        break;
                                }
                                break;
                        }
                    }
                }

                $grade->setValue($value);
            } else {
                if ($value != "") {
                    $grade->setComment($value);
                } else {
                    if ($grade->getComment() != null) {
                        $grade->setComment(null);
                    }
                }

            }

            if (!isset($value)) {
                $k++;
            }
            // We do not persist the grade if it hasn't been provided, and we persist a single comment if provided to a third-party

            $em->persist($grade);
            $em->flush();
            $l++;

        }

        $participantStatus = ($k == 0) ? 2 : (($l > 0) ? 1 : 0);

        $participantStatus = ($action == 'confirm') ? 3 : $participantStatus;

        $stage = $criterion->getStage();

        // Set current user status
        $currentUserParticipations = $repoAU->findBy(['stage' => $stage, 'usrId' => $id]);
        foreach ($currentUserParticipations as $currentUserParticipation) {
            $currentUserParticipation->setStatus($participantStatus);
            if ($action == "confirm") {
                $currentUserParticipation->setConfirmed(new \DateTime);
            }
            $em->persist($currentUserParticipation);
        }

        $em->flush();

        $val = $this->checkStageComputability($request, $app, $stage);
        return new JsonResponse(['message' => 'validate', 'redirect' => $app['url_generator']->generate('myActivities')], 200);
        //$app->redirect($app['url_generator']->generate('myActivities'));
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

        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }
        $em = self::getEntityManager();

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
        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        $em = self::getEntityManager();
        $repoA = $em->getRepository(Activity::class);
        $repoAU = $em->getRepository(ActivityUser::class);

        /** @var Activity|null */
        $activity = $repoA->find($actId);
        if (!$activity) {
            throw new \Exception("activity with id $actId doesn't exist");
        }

        /** @var ActivityUser[] */
        $participants = $repoAU->findBy(['activity' => $activity, 'status' => 3]);
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

    /**
     * @param Request $request
     * @param Application $app
     * @param $actStep
     * @param $elmtId
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/templates/{actStep}/{elmtId}/save", name="saveTemplate")
     */
    public function saveTemplateAction(Request $request, Application $app, $actStep, $elmtId)
    {

        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }
        $em = self::getEntityManager();
        $repoO = $em->getRepository(Organization::class);
        $repoD = $em->getRepository(Department::class);
        $repoTA = $em->getRepository(TemplateActivity::class);
        $templateActivity = new TemplateActivity;
        $formFactory = $app['form.factory'];
        $createTemplateForm = $formFactory->create(AddTemplateForm::class, $templateActivity, ['standalone' => true]);
        $createTemplateForm->handleRequest($request);

        switch ($actStep) {
            case 'parameters':
                self::oldAddActivityDefinitionAJAX($request, $app, 'activity', $elmtId, 'save', false);
                break;
            case 'stages':
                self::saveActivityStages($request, $app, 'activity', $elmtId, 'save', false);
                break;
            case 'criteria':
                self::saveActivityCriteria($request, $app, 'activity', $elmtId, 'save', false);
                break;
            case 'participants':
                self::oldInsertParticipantsAction($request, $app, 'activity', $elmtId, 'save', false);
                break;
        }

        // Check if there is already a template with such name in user organization
        if ($repoTA->findOneBy(['organization' => $repoO->find($currentUser->getOrgId()), 'name' => $createTemplateForm->get('name')->getData()]) != null) {
            $createTemplateForm->get('name')->addError(new FormError('There is already a template with such name in your organization. Please choose another one or select this template'));
        }

        if ($createTemplateForm->isValid()) {

            $repoA = $em->getRepository(Activity::class);
            $copiedActivity = $repoA->find($elmtId);
            $department = $repoD->find($currentUser->getDptId());

            $templateActivity
                ->setOrganization($copiedActivity->getOrganization())
                ->setDepartment($department)
                ->setMasterUserId($copiedActivity->getMasterUserId())
                ->setSimplified($copiedActivity->isSimplified())
                ->setObjectives($copiedActivity->getObjectives());

            foreach ($copiedActivity->getStages() as $copiedStage) {

                $templateStage = new TemplateStage;
                $templateStage
                    ->setName($copiedStage->getName())
                    ->setDescription($copiedStage->getDescription())
                    ->setWeight($copiedStage->getWeight())
                    ->setStartdate($copiedStage->getStartdate())
                    ->setEnddate($copiedStage->getEnddate())
                    ->setGStartdate($copiedStage->getGStartdate())
                    ->setGEnddate($copiedStage->getGEnddate())
                    ->setDeadlineNbDays($copiedStage->getDeadlineNbDays())
                    //->setCreatedBy($currentUser->getId())
                ;

                foreach ($copiedStage->getCriteria() as $copiedCriterion) {

                    $templateCriterion = new TemplateCriterion;
                    $templateCriterion
                        ->setType($copiedCriterion->getType())
                        ->setCName($copiedCriterion->getCName())
                        ->setName($copiedCriterion->getCName()->getName())
                        ->setWeight($copiedCriterion->getWeight())
                        ->setForceCommentCompare($copiedCriterion->isForceCommentCompare())
                        ->setForceCommentValue($copiedCriterion->getForceCommentValue())
                        ->setForceCommentSign($copiedCriterion->getForceCommentSign())
                        ->setLowerbound($copiedCriterion->getLowerbound())
                        ->setUpperbound($copiedCriterion->getUpperbound())
                        ->setStep($copiedCriterion->getStep())
                        ->setComment($copiedCriterion->getComment())
                        ->setCreatedBy($currentUser->getId());

                    foreach ($copiedCriterion->getParticipants() as $copiedParticipant) {

                        $templateParticipant = new TemplateActivityUser;
                        $templateParticipant
                            ->setUsrId($copiedParticipant->getUsrId())
                            ->setTeam($copiedParticipant->getTeam())
                            ->setActivity($templateActivity)
                            ->setStage($templateStage)
                            ->setLeader($copiedParticipant->isLeader())
                            ->setType($copiedParticipant->getType())
                            ->setMWeight($copiedParticipant->getMWeight())
                            ->setPrecomment($copiedParticipant->getPrecomment())
                            ->setCreatedBy($currentUser->getId());

                        $templateCriterion->addParticipant($templateParticipant);

                    }

                    $templateStage->addCriterion($templateCriterion);

                }

                $templateActivity->addStage($templateStage);
            }

            $em->persist($templateActivity);

            $em->flush();
            return new JsonResponse(['message' => 'success', 'tName' => $templateActivity->getName()], 200);
        } else {
            $errors = $this->buildErrorArray($createTemplateForm);
            return $errors;

        }
    }

    // Ajax activity creation

    /**
     * @param Request $request
     * @param Application $app
     * @param $tmpId
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/activity/create/template/{tmpId}", name="createFromTemplate")
     */
    public function createFromTemplateAction(Request $request, Application $app, $tmpId)
    {

        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        $em = self::getEntityManager();
        $repoA = $em->getRepository(Activity::class);
        $organization = $currentUser->getOrganization();
        $repoTA = $em->getRepository(TemplateActivity::class);
        /** @var TemplateActivity */
        $templateActivity = $repoTA->find($tmpId);

        if ($templateActivity->getOrganization() != $organization) {
            return $app['twig']->render('errors/403.html.twig');
        } else {
            $templateActivities = $repoA->findBy(['template' => $templateActivity]);
            if ($templateActivities != null) {
                $firstCreatedActivityFromTemplate = $templateActivities[0];
                $copiedTemplateActivityName = $firstCreatedActivityFromTemplate->getName() . ' ' . (count($templateActivities) + 1);
            } else {
                $copiedTemplateActivityName = $templateActivity->getName();
            }
            $copiedTemplateActivity = new Activity;

            $copiedTemplateActivity
                ->setStatus(-1)
                ->setName($copiedTemplateActivityName)
                ->setMasterUserId($currentUser->getId())
                ->setSimplified($templateActivity->isSimplified())
                ->setVisibility($templateActivity->getVisibility())
                ->setOrganization($organization)
                ->setRecurring($templateActivity->getRecurring())
                ->setTemplate($templateActivity)
                ->setObjectives($templateActivity->getObjectives())
                ->setCreatedBy($currentUser->getId());

            foreach ($templateActivity->getStages() as $templateStage) {
                $now = new DateTime;
                if ($templateStage == $templateActivity->getStages()->first()) {
                    $diffDaysFromNow = date_diff($now, $templateStage->getStartdate())->days;
                }

                $tomorrow = (clone $now)->add(new DateInterval('P1D'));

                $stageGStartdate = $templateStage->getGStartdate();
                $stageGEndDate = $templateStage->getGEndDate();

                $newStartDate = $templateStage->getStartdate() /*->add(new DateInterval('P'.$diffDaysFromNow.'D'))*/;
                $newEndDate = $templateStage->getEnddate() /*->add(new DateInterval('P'.$diffDaysFromNow.'D'))*/;
                $newGStartDate = $stageGStartdate;
                $newGEndDate = $stageGEndDate < $tomorrow ? $tomorrow : $stageGEndDate;

                $copiedTemplateStage = new Stage;
                $copiedTemplateStage
                    ->setOrganization($organization)
                    ->setStartDate($newStartDate)
                    ->setEndDate($newEndDate)
                    ->setGStartdate($newGStartDate)
                    ->setGEnddate($newGEndDate)
                    ->setStatus(($newGStartDate > $now ? 0 : 1))
                    ->setName($templateStage->getName())
                    ->setMasterUserId($currentUser->getId())
                    ->setDescription($templateStage->getDescription())
                    ->setWeight($templateStage->getWeight())
                    ->setCreatedBy($currentUser->getId())
                    ->setMode(1)
                    ->setIsFinalized(false);
                $copiedTemplateActivity->addStage($copiedTemplateStage);

                foreach ($templateStage->getCriteria() as $templateCriterion) {
                    $copiedTemplateCriterion = new Criterion;
                    $copiedTemplateCriterion
                        ->setOrganization($organization)
                        ->setType($templateCriterion->getType())
                        ->setName($templateCriterion->getName())
                        ->setWeight($templateCriterion->getWeight())
                        ->setForceCommentCompare($templateCriterion->isForceCommentCompare())
                        ->setForceCommentSign($templateCriterion->getForceCommentSign())
                        ->setForceCommentValue($templateCriterion->getForceCommentValue())
                        ->setLowerbound($templateCriterion->getLowerbound())
                        ->setUpperbound($templateCriterion->getUpperbound())
                        ->setStep($templateCriterion->getStep())
                        ->setComment($templateCriterion->getComment())
                        ->setCName($templateCriterion->getCName())
                        ->setCreatedBy($currentUser->getId());
                    $copiedTemplateStage->addCriterion($copiedTemplateCriterion);

                    foreach ($templateCriterion->getParticipants() as $templateParticipant) {
                        $copiedTemplateParticipant = new ActivityUser;
                        $copiedTemplateParticipant
                            ->setStatus(0)
                            ->setActivity($copiedTemplateActivity)
                            ->setUsrId($templateParticipant->getUsrId())
                            ->setTeam($templateParticipant->getTeam())
                            ->setActivity($copiedTemplateActivity)
                            ->setStage($copiedTemplateStage)
                            ->setCriterion($copiedTemplateCriterion)
                            ->setLeader($templateParticipant->isLeader())
                            ->setType($templateParticipant->getType())
                            ->setMWeight($templateParticipant->getMWeight())
                            ->setPrecomment($templateParticipant->getPrecomment())
                            ->setCreatedBy($currentUser->getId());
                        $copiedTemplateStage->addParticipant($copiedTemplateParticipant);
                        $copiedTemplateCriterion->addParticipant($copiedTemplateParticipant);
                    }
                }
            }
            $em->persist($copiedTemplateActivity);
        }
        $em->flush();

        //if ($copiedTemplateActivity->isSimplified() == true) {
        return new JsonResponse(['actId' => $copiedTemplateActivity->getId()], 200);
        //return $app->redirect($app['url_generator']->generate('oldActivityDefinition', ['actId' => $copiedTemplateActivity->getId()]));
        /* } else {
    return $app->redirect($app['url_generator']->generate('oldActivityDefinition', ['actId' => $copiedTemplateActivity->getId()]));
    }*/
    }

    public function sendActivityMail(Application $app, User $user, $actElmt, $actionType, Team $team = null)
    {

        //TODO : get current language dynamically
        $locale = 'fr';

        if ($actionType == 'creation') {

            $url = "http://www.serpicoapp.com/web/index.php/$locale/activity/" . $actElmt->getId() . "/create/parameters";

            //Sending confirmation to activity manager
            if ($user->getRole() == 2) {
                $clientMessage = \Swift_Message::newInstance()
                    ->setSubject('Vous venez de créer l\'activité "' . $actElmt->getName() . '"')
                    ->setFrom(array('no-reply@serpico.com' => 'Serpico'))
                    ->setTo($user->getEmail())
                    ->setBody("Bonjour " . $user->getFirstname() . ",\n\nVous venez de créer l'activité " . $actElmt->getName() . " dans votre organisation." .
                        "Retrouvez l'ensemble de de cette activité, et gérez son déroulé jusqu'à l'évaluation via ce lien :\n\n$url\n\n" .
                        "En tant que Manager d'Activité, vous pouvez en modifier les paramètres en cours de vie, prendre part à la notation, puis enfin visualiser les résultats" .
                        "tant individuels que collectifs. Nous vous souhaitons un agréable usage de Serpico pour votre activité", 'text/plain');
                $app['mailer']->send($clientMessage);
            }

            //Sending creation notice to administrators
            $repoU = $app['orm.em']->getRepository(User::class);
            $administrators = $repoU->findBy(['orgId' => $user->getOrgId(), 'role' => 1]);
            foreach ($administrators as $administrator) {
                //If activity created by the administrator himself...
                if ($user->getRole() == 1) {
                    $clientMessage = \Swift_Message::newInstance()
                        ->setSubject('Vous venez de créer l\'activité "' . $actElmt->getName() . '"')
                        ->setFrom(array('no-reply@serpico.com' => 'Serpico'))
                        ->setTo($administrator->getEmail())
                        ->setBody("Bonjour " . $administrator->getFirstname() . ",\n\nVous venez de créer l'activité " . '"' . $actElmt->getName() . '"' . " au sein de votre organisation.\n\n" .
                            "Retrouvez l'ensemble de de cette activité, gérez son déroulé jusqu'à l'évaluation via ce lien :\n\n$url\n\n" .
                            "En tant qu'administrateur, vous pouvez en suivre le déroulement, puis prendre connaissance des résultats individuels et globaux, et plus des notes attribuées par chacun. " .
                            "Nous vous préviendrons tout au long l'avancée de l'activité, à bientôt !\n\n L'équipe Serpico\"", 'text/plain');
                    $app['mailer']->send($clientMessage);
                } // ...otherwise
                else {
                    $clientMessage = \Swift_Message::newInstance()
                        ->setSubject('Serpico - L\'activité "' . $actElmt->getName() . '" vient d\'être créée au sein de votre organisation')
                        ->setFrom(array('no-reply@serpico.com' => 'Serpico'))
                        ->setTo($administrator->getEmail())
                        ->setBody("Bonjour " . $administrator->getFirstname() . ",\n\nLe manager d'activité " . $user->getFirstname() . " " . $user->getLastname() . " vient de créer l'activité " . $actElmt->getName() . " dans votre organisation." .
                            " Vous pouvez la retrouver via ce lien direct ou sous votre portail d'activités du groupe :\n\n$url\n\n En tant qu'adminstrateur, vous pouvez en suivre le déroulement, puis prendre connaissance des résultats " .
                            "individuels et globaux, et plus des notes attribuées par chacun. Nous vous préviendrons de l'avancée de l'activité, d'ici là à bientôt !\n\n L'équipe Serpico", 'text/plain');
                    $app['mailer']->send($clientMessage);
                }
            }

        } elseif ($actionType == 'participation_as_user') {

            //Sending confirmation to participants

            $url = "http://www.serpicoapp.com/web/index.php/$locale/myactivities";
            $clientMessage = \Swift_Message::newInstance()
                ->setSubject('Vous participez à l\'activité "' . $actElmt->getName() . '"')
                ->setFrom(array('no-reply@serpico.com' => 'Serpico'))
                ->setTo($user->getEmail())
                ->setBody("Bonjour " . $user->getFirstname() . ",\n\nVous venez d'être ajouté à l'activité " . '"' . $actElmt->getName() . '"' . " par un membre de votre organisation. " .
                    "\n\n$url  \n\nEn tant que participant, vous prendrez part à l'évaluation une fois la période de notation démarrée (" . $actElmt->getStages()->first()->getGstartdate()->format('d-m-Y') . "). Bonne activité et évaluation... en équipe bien sûr !\n\nL'équipe Serpico", 'text/plain');
            $app['mailer']->send($clientMessage);
            $app['swiftmailer.spooltransport']
                ->getSpool()
                ->flushQueue($app['swiftmailer.transport']);
        } elseif ($actionType == 'participation_as_team') {

            //Sending confirmation to participants

            $url = "http://www.serpicoapp.com/web/index.php/$locale/myactivities";
            $clientMessage = \Swift_Message::newInstance()
                ->setSubject('Vous participez à l\'activité "' . $actElmt->getName() . '"')
                ->setFrom(array('no-reply@serpico.com' => 'Serpico'))
                ->setTo($user->getEmail())
                ->setBody("Bonjour " . $user->getFirstname() . ",\n\nVous venez d'être ajouté à l'activité " . '"' . $actElmt->getName() . '"' . " par un membre de votre organisation. " .
                    "Retrouvez cette activité depuis votre espace personnel :\n\n$url  \n\nEn tant que membre de l\équipe '" . $team->getName() . "', vous prendrez part à l'évaluation une fois la période de notation démarrée (" . $actElmt->getStages()->first()->getGstartdate()->format('d-m-Y') . ") et serez notés collectivement. Bonne activité et évaluation !\n\nL'équipe Serpico", 'text/plain');
            $app['mailer']->send($clientMessage);
            $app['swiftmailer.spooltransport']
                ->getSpool()
                ->flushQueue($app['swiftmailer.transport']);

        } elseif ($actionType == 'releasable') {

            $url = "http://www.serpicoapp.com/web/index.php/$locale/activity/" . $actElmt->getId() . "/results";
            $clientMessage = \Swift_Message::newInstance()
                ->setSubject('Résultats publiables pour l\'activité "' . $actElmt->getName() . '"')
                ->setFrom(array('no-reply@serpico.com' => 'Serpico'))
                ->setTo($user->getEmail())
                ->setBody("Bonjour " . $user->getFirstname() . ",\n\nL'ensemble des participants à l'activité " . $actElmt->getName() . " a procédé à l'évaluation, " .
                    "ce qui vous permet d'en publier les résultats afin qu'ils en prennent connaissance ! Pour ce faire, il suffit de cliquer sur le lien suivant : \n\n$url \n\nAvant vous aurez l'occasion de voir les résultats globaux et les retours sur chacun, allez vite y jeter un coup d'oeil, la communauté vous attend !" .
                    "\n \n A bientôt ! \n \n L'équipe Serpico", 'text/plain');
            $app['mailer']->send($clientMessage);
            $app['swiftmailer.spooltransport']
                ->getSpool()
                ->flushQueue($app['swiftmailer.transport']);

        } elseif ($actionType == 'releasedActivity') {

            $url = "http://www.serpicoapp.com/web/index.php/$locale/activity/" . $actElmt->getId() . "/results";
            $clientMessage = \Swift_Message::newInstance()
                ->setSubject('Résultats disponibles pour l\'activité "' . $actElmt->getName() . '"')
                ->setFrom(array('no-reply@serpico.com' => 'Serpico'))
                ->setTo($user->getEmail())
                ->setBody("Bonjour " . $user->getFirstname() . ",\n\nCa y est, vos résultats pour l'activité " . $actElmt->getName() . " sont disponibles ! " .
                    "Cliquez sur le lien suivant afin d'y accedér :\n\n$url \n\n Retrouvez votre note moyenne et les feedbacks qu'on vous a laissé, en espérant qu'ils vous satisfassent !\n \n" .
                    "A bientôt,\n \nL'équipe Serpico", 'text/plain');
            $app['mailer']->send($clientMessage);
            $app['swiftmailer.spooltransport']
                ->getSpool()
                ->flushQueue($app['swiftmailer.transport']);

        } elseif ($actionType == 'releasedStage') {

            $url = "http://www.serpicoapp.com/web/index.php/$locale/activity/" . $actElmt->getActivity()->getId() . "/results";
            $clientMessage = \Swift_Message::newInstance()
                ->setSubject('Résultats disponibles pour l\'activité "' . $actElmt->getActivity()->getName() . '" (' . $actElmt->getName() . ')')
                ->setFrom(array('no-reply@serpico.com' => 'Serpico'))
                ->setTo($user->getEmail())
                ->setBody("Bonjour " . $user->getFirstname() . ",\n\nCa y est, vos résultats pour la phase " . $actElmt->getName() . " de l'activité " . $actElmt->getActivity()->getName() . " sont disponibles ! " .
                    "Cliquez sur le lien suivant afin d'y accedér :\n\n$url \n\n Retrouvez votre note moyenne et les feedbacks qu'on vous a laissé, en espérant qu'ils vous satisfassent !\n \n" .
                    "A bientôt,\n \nL'équipe Serpico", 'text/plain');
            $app['mailer']->send($clientMessage);
            $app['swiftmailer.spooltransport']
                ->getSpool()
                ->flushQueue($app['swiftmailer.transport']);

        } elseif ($actionType == 'releasedActivityNotifReleaser') {

            $url = "http://www.serpicoapp.com/web/index.php/$locale/activity/" . $actElmt->getId() . "/results";
            $clientMessage = \Swift_Message::newInstance()
                ->setSubject('Vous venez de publier les résultats de l\'activité "' . $actElmt->getName() . '""')
                ->setFrom(array('no-reply@serpico.com' => 'Serpico'))
                ->setTo($user->getEmail())
                ->setBody("Bonjour " . $user->getFirstname() . ",\n\nL'ensemble des participants de l'activité " . '"' . $actElmt->getName() . '"' . " peut dorénavant consulter les résultats grâce à votre publication !\n\n" .
                    "A bientôt,\n \nL'équipe Serpico", 'text/plain');
            $app['mailer']->send($clientMessage);
            $app['swiftmailer.spooltransport']
                ->getSpool()
                ->flushQueue($app['swiftmailer.transport']);

        } elseif ($actionType == 'releasedStageNotifReleaser') {

            $url = "http://www.serpicoapp.com/web/index.php/$locale/activity/" . $actElmt->getActivity()->getId() . "/results";
            $clientMessage = \Swift_Message::newInstance()
                ->setSubject('Vous venez de publier les résultats de la phase d\'activité "' . $actElmt->getName() . '" (' . $actElmt->getActivity()->getName() . ')')
                ->setFrom(array('no-reply@serpico.com' => 'Serpico'))
                ->setTo($user->getEmail())
                ->setBody("Bonjour " . $user->getFirstname() . ",\n\nL'ensemble des participants de la phase d'activité " . '"' . $actElmt->getName() . '"' . " peut dorénavant consulter les résultats grâce à votre publication !\n\n" .
                    "A bientôt,\n \nL'équipe Serpico", 'text/plain');
            $app['mailer']->send($clientMessage);
            $app['swiftmailer.spooltransport']
                ->getSpool()
                ->flushQueue($app['swiftmailer.transport']);

        } elseif ($actionType == 'releasedActivityNotifAdministrator') {

            $url = "http://www.serpicoapp.com/web/index.php/$locale/activity/" . $actElmt->getId() . "/results";
            $clientMessage = \Swift_Message::newInstance()
                ->setSubject('Publication des résultats de l\'activité "' . $actElmt->getName() . '"')
                ->setFrom(array('no-reply@serpico.com' => 'Serpico'))
                ->setTo($user->getEmail())
                ->setBody("Bonjour " . $user->getFirstname() . ",\n\nLe responsable de l'activité " . '"' . $actElmt->getName() . '"' . " a publié les résultats aux participants !\n\n" .
                    "Vous pouvez aussi en prendre connaissance via le lien suivant :\n\n$url\n\n A bientôt,\n\nL'équipe Serpico", 'text/plain');

            $app['mailer']->send($clientMessage);
            $app['swiftmailer.spooltransport']
                ->getSpool()
                ->flushQueue($app['swiftmailer.transport']);

        } elseif ($actionType == 'releasedStageNotifAdministrator') {

            $url = "http://www.serpicoapp.com/web/index.php/$locale/activity/" . $actElmt->getActivity()->getId() . "/results";
            $clientMessage = \Swift_Message::newInstance()
                ->setSubject('Publication des résultats de la phase d\'activité "' . $actElmt->getName() . '" (' . $actElmt->getActivity()->getName() . ')')
                ->setFrom(array('no-reply@serpico.com' => 'Serpico'))
                ->setTo($user->getEmail())
                ->setBody("Bonjour " . $user->getFirstname() . ",\n\nLe responsable de la phase d'activité " . '"' . $actElmt->getName() . '"' . " a publié les résultats aux participants !\n\n" .
                    "Vous pouvez aussi en prendre connaissance via le lien suivant :\n\n$url\n\n A bientôt,\n\nL'équipe Serpico", 'text/plain');

            $app['mailer']->send($clientMessage);
            $app['swiftmailer.spooltransport']
                ->getSpool()
                ->flushQueue($app['swiftmailer.transport']);
        }

    }
}
