<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\Organization;
use App\Entity\Position;
use App\Entity\User;
use App\Entity\UserGlobal;
use App\Entity\Weight;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DeprecatedController extends MasterController
{

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
        
        $organizationForm = $this->createForm(AddOrganizationForm::class, null, ['standalone' => true, 'orgId' => 0, 'em' => $em, 'isFromClient' => true]);
        $organizationForm->handleRequest($request);
        $errorMessage = '';
        $organization = new Organization();
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
                $now = new DateTime();
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
                    ->setExpired($now->add(new DateInterval('P21D')));
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

                $department->setName($departmentName);
                $organization->addDepartment($department);

                $position
                    ->setName($positionName);
                $department->addPosition($position);
                $organization->addPosition($position);

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

                $userMaster = new UserMaster();
                $userMaster->setUser($user);

                $organization
                    ->addUserMaster($userMaster)
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
                $serpicoOrg = $repoO->findOneByCommname('Serpico');
                $recipients = $repoU->findBy(['role' => 4, 'organization' => $serpicoOrg]);

                $settings['orgId']                = $organization->getId();
                $settings['orgName']              = $orgCommercialName;
                $settings['masterUserFullName']   = $fullName;
                $settings['masterUserEmail']      = $email;
                $settings['masterUserDepartment'] = $departmentName;
                $settings['masterUserPosition']   = $positionName;

                $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'validateOrgSubscription']);

                //Sending mail acknowledgment receipt to the requester
                $recipients          = [];
                $recipients[]        = $user;
                $settings            = [];
                $settings['orgName'] = $orgCommercialName;

                $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'subscriptionAcknowledgmentReceipt']);

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

    /**
     * @return \TFox\MpdfPortBundle\Service\PDFService
     */
    private function getMpdfService()
    {
        return $this->get('t_fox_mpdf_port.pdf');
    }
    
    public function waitSomeSeconds(Request $request, $nbSeconds)
    {
        sleep($nbSeconds);
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
            foreach ($team->getMembers() as $member) {
                $teamUsrIds[] = $member->getUser($app)->getId();
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
                if ($repoU->findOneBy(['email' => $clientUser->get('email')->getData(), 'organization' => $repoO->find($clientUser->get('orgId')->getData())])) {
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
                    $now = new DateTime();
                    $clientOrganization
                        ->setType('I')
                        ->setIsClient(false)
                        ->setCommname($clientUser['firstname']->getData() . ' ' . $clientUser['lastname']->getData())
                        ->setWeight_type('role')
                        ->setCreatedBy($currentUser->getId())
                        ->setExpired($now->add(new DateInterval('P21D')));
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
                    $userMaster = new UserMaster();
                    $userMaster->setUser($newClientUser);
                    $clientOrganization->addUserMaster($userMaster);
                    $newClientUser->setOrganization($clientOrganization);
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
                $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'externalInvitation']);
            }
            return new JsonResponse(['message' => 'Success!'], 200);

        } else {
            $errors = $this->buildErrorArray($clientUserForm);
            return $errors;
        }

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

                if ($repoU->findOneBy(['email' => $userData['email']->getData(), 'organization' => $organization])) {

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

            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'registration']);

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
            if ($repoU->findOneBy(['firstname' => $firstName, 'lastname' => $lastName, 'email' => $email, 'organization' => $organization]) == null) {

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
                        $superior          = $repoU->findOneBy(['firstname' => $superiorFirstName, 'lastname' => $superiorLastName, 'organization' => $organization]);
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
                            $weight = new Weight();
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
        return new JsonResponse(['message' => 'Success!'], 200);
        unlink($filePath);

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/organization/{orgId}/update", name="updateOrganization")
     */
    public function updateOrganizationAction(Request $request, $orgId){

        $em = $this->em;
        $repoO = $em->getRepository(Organization::class);
        
        $organizationForm = $this->createForm(AddOrganizationForm::class, null,['standalone' => true, 'orgId' => $orgId, 'app' => $app]);
        $organizationForm->handleRequest($request);
        $errorMessage = '';
        $organization = $repoO->findOneById($orgId);
        $user = new User();
        $department = new Department();
        $position = new Position();

        if ($organizationForm->isSubmitted()) {
            if ($organizationForm->isValid()) {
                $email = $organizationForm->get('email')->getData();
                $token = md5(rand());

                $organization->setCommname($organizationForm->get('commname')->getData());
                $organization->setLegalname($organizationForm->get('legalname')->getData());
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
                $userGlobal = new UserGlobal();
                $userGlobal->setUsername($organizationForm->get('firstname')->getData() .' '. $organizationForm->get('lastname')->getData())
                    ->addUserAccount($user);
                $em->persist($userGlobal);

                $organization->setMasterUserId($user->getId());
                $em->persist($organization);
                $em->flush();


                $recipients = [];
                $recipients[] = $user;
                $recipients[] = $user;
                $settings = [];

                $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'orgMasterUserChange']);

                return $this->redirectToRoute('manageOrganizations');
            }

        }

        return $this->render('organization_add.html.twig',
            [
                'form' => $organizationForm->createView(),
                'message' => $errorMessage,
                'update' => true,
                'orgId' => $organization->getId()
            ]);

    }

    //Adds organization (limited to root master)

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/organization/create", name="createOrganization")
     */
    public function addOrganizationAction(Request $request)
    {
        $em = $this->em;
        /** @var FormFactory */
        $organizationForm = $this->createForm(AddOrganizationForm::class, null, ['standalone' => true, 'orgId' => 0, 'em' => $em, 'isFromClient' => false]);
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
        $wfiId = $request->get('wfiId');

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
                /** @var string */
                $orgType = $organizationForm->get('type')->getData();
                /** @var WorkerFirm */
                $workerFirm = $wfiId ? $repoWF->find($wfiId): new WorkerFirm;

                if(!$wfiId){
                    $workerFirm->setName($organizationForm->get('commname')->getData())
                    ->setCommonName($organizationForm->get('commname')->getData());
                    $em->persist($workerFirm);
                    $em->flush();    
                }

                $now = new DateTime();

                $organization
                    ->setCommname($workerFirm->getName())
                    ->setLegalname($workerFirm->getName())
                    ->setIsClient(true)
                    ->setType($orgType)
                    ->setWeightType('role')
                    ->setExpired($now->add(new DateInterval('P21D')))
                    ->setWorkerFirm($workerFirm);

                $this->forward('App\Controller\OrganizationController::updateOrgFeatures', ['organization' => $organization, 'nonExistingOrg' => true, 'addedAsClient' => false]);
                
                /** @var string */
                $positionName = $organizationForm->get('position')->getData();
                /** @var string */
                $departmentName = $organizationForm->get('department')->getData();

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
                        ->setWeightIni(100);
                    $em->persist($user);
                    //$em->flush();
                }

                if($user){

                    $userGlobal = new UserGlobal();
                    $userGlobal->setUsername("$firstname $lastname")
                        ->addUserAccount($user);
                    $em->persist($userGlobal);

                    $userMaster = new UserMaster();
                    $userMaster->setUser($user);

                    if($departmentName != "") {
                        $department = new Department;
                        $department->setName($departmentName)
                            ->addUserMaster($userMaster)
                            ->addUser($user);
                        $organization->addDepartment($department);
                    }

                    if($positionName != "") {
                        $position = new Position;
                        $position->setName($positionName);
                        $position->addUser($user);
                        $organization->addPosition($position);
                    }

                    $user
                        ->setDepartment($department)
                        ->setPosition($position)
                        ->setWeight($organization->getDefaultWeight());
                        
                    //$em->persist($user);
                    $organization->addUser($user);
                    if($user){
                        $organization->addUserMaster($userMaster);
                    }
                    $em->persist($organization);
                    $em->flush();

                }

                // Sending mail to created firm master user, if such user exists
                if($user){
                    $settings['tokens'][] = $token;
                    $recipients = [];
                    $recipients[] = $user;
                    $settings['rootCreation'] = true;
                    $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'registration']);

                }

                return $this->redirectToRoute('manageOrganizations');
            }

        }

        return $this->render('organization_add.html.twig',
            [
                'form' => $organizationForm->createView(),
                'message' => $errorMessage,
                //'nbweights'=> $nbWeights,
                //'totalweights' => $totalWeights
            ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/organizations/{orgId}/validate", name="validateOrganization")
     */
    public function validateOrganizationAction(Request $request, $orgId){

        $currentUser = self::getAuthorizedUser($app);
        if($currentUser->getRole() != 4){
            return $this->render('errors/404.html.twig');
        }

        $em = $this->em;
        $repoO = $em->getRepository(Organization::class);
        $repoU = $em->getRepository(User::class);
        $organization = $repoO->findOneById($orgId);
        
        $organizationForm = $this->createForm(AddOrganizationForm::class, null,['standalone' => true, 'orgId' => $orgId, 'app' => $app, 'toValidate' => true]);
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

                $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'subscriptionConfirmation']);


            } else {

                $em->remove($masterUser);
                $em->remove($organization);
            }

            $em->flush();
            return $this->redirectToRoute('manageOrgazations');
        }

        return $this->render('organization_add.html.twig',
            [
                'form' => $organizationForm->createView(),
                'toValidate' => true,
            ]);
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
        $addFirstAdminForm = $this->createForm(AddFirstAdminForm::class,$user);
        $addFirstAdminForm->handleRequest($request);
        $user = new User;
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
}