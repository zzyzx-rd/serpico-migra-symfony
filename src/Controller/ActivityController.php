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
use App\Repository\UserRepository;
use RuntimeException;
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

        $activity
            ->setName($activityName)
            ->setOrganization($currentUser->getOrganization())
            ->setMasterUser($currentUser)
            ->setCreatedBy($currentUser->getId())
            ->addStage($stage);

        if ($inpId !== 0) {
            $activity->setInstitutionProcess($this->em->getRepository(InstitutionProcess::class)->find($inpId));
        }

        $stage
            ->setName($stageName)
            ->setMasterUser($currentUser)
            ->setWeight(1)
            ->setStartdate($activityStartDate)
            ->setEnddate($activityEndDate)
            ->setGstartdate($activityGStartDate)
            ->setGenddate($activityGEndDate)
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
//        return $this->redirectToRoute('manageActivityElement', ['entity' => 'activity', 'elmtId' => $activity->getId()] );
        return $this->json(['message' => 'success to create activity', 'redirect' => $this->generateUrl('manageActivityElement', ['entity' => 'activity', 'elmtId' => $activity->getId()])], 200);
        //return $this->redirectToRoute('manageActivityElement', ['entity' => 'activity', 'elmtId' => $activity->getId()]);
        //return new JsonResponse(['message' => 'success to create activity', 'redirect' => $this->redirectToRoute('manageActivityElement', ['entity' => 'activity', 'elmtId' => $activity->getId()])], 200);
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
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/activity/request", name="activityRequest")
     */
    public function requestActivityAction(Request $request)
    {
        $em = $this->em;
        $repoU = $em->getRepository(User::class);
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }
        $organization = $em->getRepository(Organization::class)->find($currentUser->getOrgId());
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
            $activity->setMasterUserId(0);
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

//    /**
//     * @param Request $request
//     * @param Application $app
//     * @param $elmt
//     * @param $elmtId
//     * @return RedirectResponse
//     * @Route("/{elmt}/{elmtId}/parameters", name="oldActivityDefinition")
//     */
//    public function oldAddActivityDefinition(Request $request, $elmt, $elmtId)
//    {
//        $user = self::getAuthorizedUser();
//        if (!$user) {
//            return $this->redirectToRoute('login');
//        }
//
//        $em = $this->em;
//        $userRole = $user->getRole();
//        $organization = $user->getOrganization();
//        /** @var Activity|TemplateActivity|null */
//        $activity = $em->getRepository(
//            $elmt === 'activity' ? Activity::class : TemplateActivity::class
//        )->find($elmtId);
//        $userIsNotRoot = $userRole != 4;
//        $userIsAdmin = $userRole == 1;
//        $userIsAM = $userRole == 2;
//        $userIsCollab = $userRole == 3;
//        $hasPageAccess = true;
//
//        if (!$activity) {
//            $errorMsg = 'activityDoNotExist';
//            $hasPageAccess = false;
//        } else {
//            $simplifiedActivity = count($activity->getStages()) == 1 && count($activity->getStages()->first()->getCriteria()) == 1;
//            $actOrganization = $activity->getOrganization();
//            $actBelongsToDifferentOrg = $organization != $actOrganization;
//
//            if ($activity instanceof Activity) {
//                $activeModifiableStages = $activity->getActiveModifiableStages();
//                $actHasNoActiveModifiableStages = count($activeModifiableStages) == 0;
//
//                if ($userIsNotRoot and ($actBelongsToDifferentOrg or !$userIsAdmin and $actHasNoActiveModifiableStages)) {
//                    if ($userIsNotRoot || $actBelongsToDifferentOrg) {
//                        $errorMsg = 'externalViolation';
//                    } else {
//                        $errorMsg = 'unmodifiableActivity';
//                    }
//                    $hasPageAccess = false;
//                }
//            } else {
//                if ($userIsCollab or ($userIsAM or $userIsAdmin) and $actBelongsToDifferentOrg) {
//                    $hasPageAccess = false;
//                }
//            }
//        }
//
//        if (!$hasPageAccess) {
//            return $this->render('errors/403.html.twig', [
//                'errorMsg' => $errorMsg,
//                'returnRoute' => 'myActivities',
//            ]);
//        } else {
//            /** @var FormFactory */
//            
//            $incomplete = $activity->getStages()->first() == null;
//            $parametersForm = $this->createForm(
//                AddActivityCriteriaForm::class,
//                null,
//                [
//                    'standalone' => true,
//                    'activity' => $activity,
//                    'incomplete' => $incomplete,
//                    'organization' => $organization,
//                ]
//            );
//            $parametersForm->handleRequest($request);
//            $createTemplateForm = null;
//            $createCriterionForm = null;
//            if ($simplifiedActivity) {
//                $createCriterionForm = $this->createForm(CreateCriterionForm::class, null, ['standalone' => true]);
//                $createCriterionForm->handleRequest($request);
//            }
//            if ($elmt == 'activity' && $activity->getTemplate() == null) {
//                $createTemplateForm = $this->createForm(AddTemplateForm::class, null, ['standalone' => true]);
//                $createTemplateForm->handleRequest($request);
//            }
//
//            return $this->render('activity_create_definition_old.twig',
//                [
//                    'form' => $parametersForm->createView(),
//                    'activity' => $activity,
//                    'incomplete' => $incomplete,
//                    'createTemplateForm' => ($createTemplateForm === null) ?: $createTemplateForm->createView(),
//                    'createCriterionForm' => ($createCriterionForm === null) ?: $createCriterionForm->createView(),
//                    'icons' => $em->getRepository(Icon::class)->findAll(),
//                ]
//            );
//        }
//    }

    // Change activity complexity after user has clicked on the add phases/stages button

    /**
     * @param Request $request
     * @param $actId
     * @return false|int|string
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/activity/{actId}/complexify", name="activityComplexify")
     */
    public function complexifyActivity(Request $request, $actId)
    {
        $elmt = strpos($_SERVER['HTTP_REFERER'], 'activity');
        $em = $this->em;
        $activity = $em->getRepository(Activity::class)->find($actId);
        $activity->setSimplified(false);
        $em->persist($activity);
        $em->flush();
        return $elmt;
    }

    /**
     * @param Request $request
     * @param $elmt
     * @param $elmtId
     * @param $actionType
     * @param bool $returnJSON
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/{elmt}/{elmtId}/parameters/{actionType}", name="oldActivityDefinitionAJAX")
     */
    public function oldAddActivityDefinitionAJAX(Request $request, $elmt, $elmtId, $actionType, $returnJSON = true)
    {
        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        /**
         * @param DbObject $activity
         * @param string $name
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

        $em = $this->em;
        /** @var FormFactory */
        

        $activity = $elmt == 'activity'
        ? $em->getRepository(Activity::class)->find($elmtId)
        : $em->getRepository(TemplateActivity::class)->find($elmtId);

        $simplifiedActivity = count($activity->getStages()) == 1 && count($activity->getStages()->first()->getCriteria()) == 1;
        $incomplete = $activity->getStages()->first() === null;

        $repoO = $em->getRepository(Organization::class);
        $repoCN = $em->getRepository(CriterionName::class);
        $organization = $currentUser->getOrganization();
        $parametersForm = $this->createForm(
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

                        $nextDayDate = new DateTime;
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
                $now = new DateTime;

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

            $activity->setSaved(new DateTime);
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
        $type = $request->get('type');
        $precomment = $request->get('precomment');
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
        /** @var Activity|TemplateActivity|InstitutionProcess */
        $element = $entity != 'iprocess' ? $stage->getActivity() : $stage->getInstitutionProcess();
        $activityOrganization = $element->getOrganization();

        $stage->currentUser = $currentUser;
        $element->currentUser = $currentUser;

        if (!$stage->isModifiable()) {
            throw new RuntimeException('unauthorized');
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

                 $participations = $repoP->findBy([
                    'stage' => $stage,
                    'team' => $participation->getTeam(),
                ]);
            }
            $iterableElements = $participations;
        }

        if($entity == 'activity' && ($participation == null || ($participation->getType() == 0 && $type != 0))){

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

//                self::sendMail(
//                    $app,
//                    $mailRecipients,
//                    'unvalidateOutputDueToChange',
//                    ['stage' => $stage, 'actElmt' => 'participant']
//                );
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
                    $client = $currentUserOrganization->getClients()->filter(function(Client $c) use ($userOrganization){
                        return $c->getClientOrganization() == $userOrganization;
                    })->first();
                    $externalUsrId = $client->getExternalUsers()->filter(function(ExternalUser $e) use ($pElement){
                        return $e->getUser() == $pElement;
                    })->first()->getId();

                    $consideredParticipation->setExtUsrId($externalUsrId);
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

//            if(sizeof($recipients) > 0){
//                $sendMail = self::sendMail(
//                    $app,
//                    $recipients,
//                    'activityParticipation',
//                    [
//                        'activity' => $element,
//                        'stage' => $stage,
//                    ]
//                );
//            }
        }

        $this->em->persist($stage);
        $this->em->flush();

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
        $em = $this->getEntityManager($app);
        
        $repo0 = $em->getRepository(Survey::class);
        $repo2 = $em->getRepository(Participation::class);
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
                //return $app->redirect($app['url_generator']->generate('myActivities'));
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

    //Get the results of an activity for intended users

    /**
     * @param Request $request
     * @param $actId
     * @return RedirectResponse
     * @throws ORMException
     * @Route("/activity/{actId}/results", name="activityResults")
     */
    public function displayResultsAction(Request $request, $actId)
    {
        set_time_limit(300);
        $repoA = $em->getRepository(Activity::class);
        $repoP = $em->getRepository(Participation::class);
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
            return $this->render('errors/403.html.twig');
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
                    $userParticipation = $repoP->findOneBy(['criterion' => $stage->getCriteria()->first(), 'usrId' => $userId]);

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
            
            $activityReportForm = $this->createForm(ActivityReportForm::class, null, ['standalone' => true, 'activity' => $activity]);
            $activityReportForm->handleRequest($request);

            try {
                return $this->render('activity_results.html.twig',
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
    public function requestResultsAction(Request $request, $stgId, $usrId)
    {

        $em = $this->em;
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
    public function saveImageActivityReportAction(Request $request, $actId, $stgId, $crtId, $type, $overview, $equalEntries)
    {

        $em = $this->em;
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
        $em = $this->em;
        $repoP = $em->getRepository(Participation::class);
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
            $userParticipation = $repoP->findOneBy(['criterion' => $stage->getCriteria()->first(), 'usrId' => $userId]);

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
                    //$participantStatus = $repoP->findOneBy(['criterion' => $criterion, 'usrId' => $userId])->getStatus();
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
                    //$participations = $repoP->findBy(['criterion' => $criterion], ['absoluteWeightedResult' => 'DESC']);
                    $participations = $repoP->findBy(['criterion' => $criterion], ['type' => 'DESC']);

                    //$results = $repoR->find

                    //get participant id order in a array to sort criterionData by the same method used in sorting participants
                    $orderedIds = [];
                    foreach ($participations as $Participation) {
                        $orderedIds[] = $Participation->getUsrId();
                    }

                    //Get participants feedbacks on his performance, sorted accordingly to retrieve relevant results
                    foreach ($participations as $key => $Participation) {

                        $userGrades = $repoG->findBy(
                            ['criterion' => $criterion,
                                'participant' => $Participation],
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

                    for ($i = 0; $i < count($participations); $i++) {
                        $comments[$i] = [];
                        for ($j = 0; $j < count($participations); $j++) {
                            $comments[$i][] = $commentsMatrix[$j][$i];
                            $gradeValues[$i][] = $gradesMatrix[$j][$i];
                        }
                    }

                    $renderedData = [];

                    foreach ($participations as $key => $Participation) {

                        $id = $Participation->getUsrId();
                        $user = $repoU->find($id);
                        $firstname = $user->getFirstname();
                        $lastname = $user->getLastname();
                        $isNotTP = $Participation->getType();
                        $wResult = $Participation->getAbsoluteWeightedResult();
                        $eResult = $Participation->getAbsoluteEqualResult();
                        $wDevRatio = $Participation->getWeightedDevRatio();
                        $eDevRatio = $Participation->getEqualDevRatio();

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

        $comments = $repoP->findBy(['criterion' => $commentCrtValue]);

        try {
            $html = $this->render('activity_report.html.twig',
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

                $d = new DateTime;
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
        $em = $this->em;
        $repoA = $em->getRepository(Activity::class);
        $repoS = $em->getRepository(Stage::class);
        $repoC = $em->getRepository(Criterion::class);
        $repoI = $em->getRepository(GeneratedImage::class);
        $repoP = $em->getRepository(Participation::class);
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
                                    $participants = (isset($_POST['settings_objectives'])) ? $repoP->findBy(['activity' => $activity], ['usrId' => 'ASC']) : null;
                                    $trigger = true;
                                }
                            } else {

                                if ($printAllBelowStage) {
                                    $trigger = false;
                                    if ($criterion == null && $trigger == false) {
                                        $grades = (isset($_POST['settings_comments'])) ? $repoG->findBy(['stage' => $stage], ['team' => 'ASC', 'participant' => 'ASC', 'gradedTeaId' => 'ASC', 'gradedUsrId' => 'ASC']) : null;
                                        $participants = (isset($_POST['settings_objectives'])) ? $repoP->findBy(['stage' => $stage], ['usrId' => 'ASC']) : null;
                                        $trigger = true;
                                    }
                                } else {
                                    $grades = (isset($_POST['settings_comments'])) ? $repoG->findBy(['criterion' => $criterion], ['team' => 'ASC', 'participant' => 'ASC', 'gradedTeaId' => 'ASC', 'gradedUsrId' => 'ASC']) : null;
                                    $participants = (isset($_POST['settings_objectives'])) ? $repoP->findBy(['criterion' => $criterion], ['usrId' => 'ASC']) : null;
                                }
                            }

                        } else {

                            if ($isPrintableActivity) {
                                if ($stage == null && $criterion == null) {
                                    $grades = (isset($_POST['settings_comments'])) ? $repoG->findBy(['activity' => $activity], ['team' => 'ASC', 'participant' => 'ASC', 'gradedTeaId' => 'ASC', 'gradedUsrId' => 'ASC']) : null;
                                    $participants = (isset($_POST['settings_objectives'])) ? $repoP->findBy(['activity' => $activity], ['usrId' => 'ASC']) : null;
                                }
                            } else {
                                if ($criterion == null) {
                                    $grades = (isset($_POST['settings_comments'])) ? $repoG->findBy(['stage' => $stage], ['team' => 'ASC', 'participant' => 'ASC', 'gradedTeaId' => 'ASC', 'gradedUsrId' => 'ASC']) : null;
                                    $participants = (isset($_POST['settings_objectives'])) ? $repoP->findBy(['stage' => $stage], ['usrId' => 'ASC']) : null;
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
                                    $participants = (isset($_POST['settings_objectives'])) ? $repoP->findBy(['activity' => $activity, 'usrId' => $user->getId()], ['usrId' => 'ASC']) : null;

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
                                        $participants = (isset($_POST['settings_objectives'])) ? $repoP->findBy(['stage' => $stage, 'usrId' => $user->getId()], ['usrId' => 'ASC']) : null;
                                        $grades = (isset($_POST['settings_comments'])) ? (
                                            ($participants[0]->getTeam() == null) ?
                                            $repoG->findBy(['stage' => $stage, 'gradedUsrId' => $user->getId()]) :
                                            $repoG->findBy(['stage' => $stage, 'gradedTeaId' => $team->getId()])
                                        ) : null;
                                        $trigger = true;
                                    }
                                } else {
                                    $participants = (isset($_POST['settings_objectives'])) ? $repoP->findBy(['criterion' => $criterion, 'usrId' => $user->getId()], ['usrId' => 'ASC']) : null;
                                    $grades = (isset($_POST['settings_comments'])) ? (
                                        ($participants[0]->getTeam() == null) ?
                                        $repoG->findBy(['criterion' => $criterion, 'gradedUsrId' => $user->getId()]) :
                                        $repoG->findBy(['criterion' => $criterion, 'gradedTeaId' => $team->getId()])
                                    ) : null;
                                }

                            }
                        } else {

                            $participants = (isset($_POST['settings_objectives']) && $criterion == null) ? $repoP->findBy(['criterion' => $criterion, 'usrId' => $user->getId()], ['usrId' => 'ASC']) : null;
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

        $html = $this->render('activity_report_new.html.twig',
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

            $d = new DateTime;
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
        $em = $this->em;
        $repoP = $em->getRepository(Participation::class);
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
            $participations = $repoP->findBy(['criterion' => $criterion], ['absoluteWeightedResult' => 'DESC']);

            //$results = $repoR->find

            //get participant id order in a array to sort comments by the same method used in sorting participants
            $orderedIds = [];
            foreach ($participations as $Participation) {
                $orderedIds[] = $Participation->getUsrId();
            }

            //Get participants feedbacks on his performance, sorted accordingly to retrieve relevant results
            foreach ($participations as $key => $Participation) {

                $userGrades = $repoG->findBy(
                    ['criterion' => $criterion,
                        'participant' => $Participation],
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

            for ($i = 0; $i < count($participations); $i++) {
                $comments[$i] = [];
                for ($j = 0; $j < count($participations); $j++) {
                    $comments[$i][] = $commentsMatrix[$j][$i];
                    $gradeValues[$i][] = $gradesMatrix[$j][$i];
                }
            }

            $renderedData = [];

            foreach ($participations as $key => $Participation) {

                $id = $Participation->getUsrId();
                $user = $repoU->find($id);
                $firstname = $user->getFirstname();
                $lastname = $user->getLastname();
                $isNotTP = $Participation->getType();
                $wResult = $Participation->getAbsoluteWeightedResult();
                $eResult = $Participation->getAbsoluteEqualResult();
                $wDevRatio = $Participation->getWeightedDevRatio();
                $eDevRatio = $Participation->getEqualDevRatio();

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

        return $this->render('activity_report.html.twig',
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
    public function newSaveGradesAction(Request $request, $action)
    {

        $id = $app['security.token_storage']->getToken()->getUser()->getId();
        $em = $this->em;
        $repoG = $em->getRepository(Grade::class);
        $repoP = $em->getRepository(Participation::class);
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
                $repoG->findOneBy(['participant' => $repoP->find($parId), 'criterion' => $criterion, 'gradedUsrId' => $gradedElmtId]) :
                $repoG->findOneBy(['participant' => $repoP->find($parId), 'criterion' => $criterion, 'gradedTeaId' => $gradedElmtId, 'gradedUsrId' => $gradedUsrId]);
            } else {
                $grade = $repoG->findOneBy(['participant' => $repoP->find($parId), 'criterion' => $criterion, 'gradedUsrId' => null, 'gradedTeaId' => null]);
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
        $currentUserParticipations = $repoP->findBy(['stage' => $stage, 'usrId' => $id]);
        foreach ($currentUserParticipations as $currentUserParticipation) {
            $currentUserParticipation->setStatus($participantStatus);
            if ($action == "confirm") {
                $currentUserParticipation->setConfirmed(new DateTime);
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
    public function saveTemplateAction(Request $request, $actStep, $elmtId)
    {


        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }
        $em = $this->em;
        $repoO = $em->getRepository(Organization::class);
        $repoD = $em->getRepository(Department::class);
        $repoTA = $em->getRepository(TemplateActivity::class);
        $templateActivity = new TemplateActivity;
        
        $createTemplateForm = $this->createForm(AddTemplateForm::class, $templateActivity, ['standalone' => true]);
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
        if ($repoTA->findOneBy(['organization' => $currentUser->getOrganization(), 'name' => $createTemplateForm->get('name')->getData()]) != null) {
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

                        $templateParticipant = new TemplateParticipation;
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
    public function createFromTemplateAction(Request $request, $tmpId)
    {


        if (!$currentUser) {
            return $this->redirectToRoute('login');
        }

        $em = $this->em;
        $repoA = $em->getRepository(Activity::class);
        $organization = $currentUser->getOrganization();
        $repoTA = $em->getRepository(TemplateActivity::class);
        /** @var TemplateActivity */
        $templateActivity = $repoTA->find($tmpId);

        if ($templateActivity->getOrganization() != $organization) {
            return $this->render('errors/403.html.twig');
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
                        $copiedTemplateParticipant = new Participation;
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
