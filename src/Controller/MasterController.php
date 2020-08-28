<?php

namespace App\Controller;

use ArrayIterator;
use DateInterval;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException as ORMExceptionAlias;
use Exception;
use App\Entity\Activity;
use App\Entity\Participation;
use App\Entity\Criterion;
use App\Entity\Department;
use App\Entity\Mail;
use App\Entity\OptionName;
use App\Entity\Organization;
use App\Entity\OrganizationUserOption;
use App\Entity\Position;
use App\Entity\Ranking;
use App\Entity\RankingHistory;
use App\Entity\RankingTeam;
use App\Entity\RankingTeamHistory;
use App\Entity\Recurring;
use App\Entity\Result;
use App\Entity\ResultProject;
use App\Entity\ResultTeam;
use App\Entity\Stage;
use App\Entity\User;
use App\Repository\ParticipationRepository;
use App\Repository\UserRepository;
use RuntimeException;
use Swift_Image;
use Swift_Mailer;
use Swift_Message;
use Swift_SpoolTransport;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Constraints\DateTime;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;

abstract class MasterController extends AbstractController
{
    public const EVALUATION_COMMENT = 1;
    public const COMMENT = 2;
    public const CRITERIA = 1;
    public const STAGE = 2;
    public const ACTIVITY = 2;
    public const STAGE_ONLY = 0;
    public const USERS_ONLY = 1;
    public const LOCAL_URL_PREFIXE = "http://0.0.0.0:5000";
    public const SERVEUR_URL_PREFIXE = "http://51.15.121.241:5000";
    public const MAIN_CRITERIA_COMPUTATION = "/main/criteriaComputation";
    /**
     * @var EntityManager
     */
    public $em;
    /**
     * @var Security
     */
    protected $security;
     /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;
    /**
     * @var RequestStack
     */
    public $stack;
    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var User
     */
    protected $user;
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * MasterController constructor.
     * @param EntityManagerInterface $em
     * @param Security $security
     * @param RequestStack $stack
     */
    public function __construct(EntityManagerInterface $em, Security $security, RequestStack $stack, UserPasswordEncoderInterface $encoder, Environment $twig, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->security = $security;
        $this->stack = $stack;
        $this->user = $security->getUser();
        $this->encoder = $encoder;
        $this->twig = $twig;
        if ($this->user){
            $this->org = $this->user->getOrganization();
        }
        $this->activityRepo = $this->em->getRepository(Activity::class);
        $this->em = $em;
        $this->security = $security;
        $this->mailer = $mailer;
    }


    /**
     * @return EntityManager
     */
    public  function getEntityManager()
    {
        return $this->em;
    }

    public function hideResultsFromStages(Collection $stages): array
    {
        $currentUser = $this->user;
        if (!$currentUser) {
            throw new RuntimeException('unauthorized');
        }

        $role = $currentUser->getRole();
        if ($role == 4) {
            // root has no restrictions
            return [];
        }

        $id = $currentUser->getId();
        $org = $currentUser->getOrganization();
        $optNameRepo = $this->em->getRepository(OptionName::class);
        $orgUsrOptRepo = $this->em->getRepository(OrganizationUserOption::class);

        /** @var OptionName|null */
        $activitiesAccessAndResultsView = $optNameRepo->findOneBy([
            'name' => 'activitiesAccessAndResultsView', 'enabled' => true,
        ]);
        if (!$activitiesAccessAndResultsView) {
            return [];
        }

        /** @var OrganizationUserOption|null */
        $resultsVisibility = $orgUsrOptRepo->findOneBy([
            'organization' => $org,
            'oName' => $activitiesAccessAndResultsView,
            'role' => $role,
        ]);
        if (!$resultsVisibility) {
            return [];
        }

        $cond = $resultsVisibility->getOptionSValue();
        $statusAccess = $resultsVisibility->getOptionSecondaryIValue();

        if ($cond == 'participant') {
            return $stages->filter(function (Stage $s) use ($id) {
                return $s->getParticipants()->forAll(function (int $i, Participation $p) use ($id) {
                    return $p->getUsrId() != $id;
                });
            })->map(function (Stage $s) {
                return $s->getId();
            })->getValues();
        }
        if ($cond == 'owner') {

            // This option is the same as if participating, the differenciation being if stage is unreleased
            $removableStageIds = [];
            foreach ($stages as $stage) {
                $isParticipating = false;
                foreach ($stage->getUniqueParticipations() as $participant) {
                    if ($participant->getUsrId() == $id) {
                        $isParticipating = true;
                        break;
                    }
                }
                if (!$isParticipating || $statusAccess > $stage->getStatus()) {
                    $removableStageIds[] = $stage->getId();
                }
            }
            return $removableStageIds;
        }

        return [];
    }

    public function getAuthorizedUser(): ?\Symfony\Component\Security\Core\User\UserInterface
    {
        return $this->user;
    }


    public  function getRememberedUser($token)
    {
        $repoU = $this->em->getRepository(User::class);
        return $repoU->findOneByRememberMeToken($token);
    }

    public static function updateProgressStatus($forAllFirms = false){

        $em = self::getEntityManager();
        $repoS = $em->getRepository(Stage::class);

        //$stages = new ArrayCollection($repoS->findAll());
        $organization = self::getAuthorizedUser()->getOrganization();

        $lastRoutineCheck = $organization->getRoutinePStatus();

        if($lastRoutineCheck){
            $triggerRoutineCheck = clone $lastRoutineCheck;
            $triggerRoutineCheck->add(New DateInterval('P1D'));
        }

        if(!$lastRoutineCheck || $triggerRoutineCheck < new \DateTime()){

            $stages = $organization->getStages();


            $now = new \DateTime();
            $triggerDate = clone $now->sub(new DateInterval('P5D'));

            $notifyingUnstartedStages = $stages->filter(function(Stage $s) use ($triggerDate){
                return $s->getProgress() == STAGE::PROGRESS_UNSTARTED && $s->getStartdate() < $triggerDate && !$s->isUnstartedNotified();
            })->getValues();

            $notifyingUncompletedStages = $stages->filter(function(Stage $s) use ($triggerDate){
                return $s->getProgress() == STAGE::PROGRESS_SUSPENDED && $s->getEnddate() < $triggerDate && !$s->isUncompletedNotified();
            })->getValues();


            $notifyingUnfinishedStages = $stages->filter(function(Stage $s) use ($triggerDate){
                $lastReopenedDate = $s->getLastReopened();
                if(!$s->getLastReopened()){return false;}
                $triggerDate = clone $lastReopenedDate->sub(new DateInterval('P5D'));
                return $s->isReopened() && $s->getProgress() == STAGE::PROGRESS_SUSPENDED && $triggerDate < new \DateTime() && !$s->isUnfinishedNotified();
            })->getValues();

            $notifyingGroupStages = [];
            $notifyingGroupStages[] = $notifyingUnstartedStages;
            $notifyingGroupStages[] = $notifyingUncompletedStages;
            $notifyingGroupStages[] = $notifyingUnfinishedStages;

            foreach ($notifyingGroupStages as $key => $notifyingGroupStage){

                    // Checking unstarted stages
                    $settings = [];
                    $notifiedUsers = new ArrayCollection;
                    $settings['stages'] = [];
                    foreach($notifyingGroupStage as $notifyingStage){
                        $owner = $notifyingStage->getUniqueParticipations()->filter(function(Participation $p){
                            return $p->isLeader();
                        })->first();

                        if($owner){
                            $notifiedUser = $owner->getDirectUser();
                        } else {
                            $repoU = $em->getRepository(User::class);
                            $notifiedUser = $repoU->find($notifyingStage->getMasterUserId());
                        }
                        if($notifiedUser && !$notifiedUsers->contains($notifiedUser)){
                            $settings['stages'][] = $notifyingStage;
                            $notifiedUsers->add($notifiedUser);
                        }
                        switch($key){
                            case 0: $notifyingStage->setUnstartedNotified(true);$settings['case'] = 'unstarted';break;
                            case 1: $notifyingStage->setUncompletedNotified(true);$settings['case'] = 'uncompleted';break;
                            case 2: $notifyingStage->setUnfinishedNotified(true);$settings['case'] = 'unfinished';break;
                        }
                        $em->persist($notifyingStage);
                    }

                    self::sendMail(null, $notifiedUsers->toArray(), 'updateProgressStatus', $settings);

            }


            $unstartedStages = $stages->filter(function(Stage $s){
                return $s->getProgress() == STAGE::PROGRESS_UPCOMING && $s->getStartdate() < new \DateTime();
            });
            foreach($unstartedStages as $unstartedStage){
                $unstartedStage->setProgress(STAGE::PROGRESS_UNSTARTED);
                $em->persist($unstartedStage);
            }

            $uncompletedStages = $stages->filter(function(Stage $s){
                return $s->getProgress() == STAGE::PROGRESS_ONGOING && $s->getEnddate() < new \DateTime();
            });

            foreach($uncompletedStages as $uncompletedStage){
                $uncompletedStage->setProgress(STAGE::PROGRESS_SUSPENDED);
                $em->persist($uncompletedStage);
            }

            $unfinishedStages = $stages->filter(function(Stage $s){
                return $s->isReopened() && $s->getProgress() < STAGE::PROGRESS_COMPLETED && $s->getStartdate() < new \DateTime();
            });
            foreach($unfinishedStages as $unfinishedStage){
                $unfinishedStage->setProgress(STAGE::PROGRESS_SUSPENDED);
                $em->persist($unfinishedStage);
            }

            $organization->setRoutinePStatus(new \DateTime());
            $em->persist($organization);
            $em->flush();

        }

        return true;

    }


    // Compute (user) rankings
    public static function computeRankingAction(Request $request, $entityType, $dType, $wType, $orgId, $elmtType, $maxLastResults = 1000000, $from = '2018-01-01 00:00', $to = null, $period = 0, $frequency = 'D')
    {
        $em = self::getEntityManager();
        $repoO = $em->getRepository(Organization::class);

        if ($entityType == 'users') {
            $repoU = $em->getRepository(User::class);
            $repoRH = $em->getRepository(RankingHistory::class);
            $consideredEntity = 'App\Entity\Ranking';
            $consideredEntityElmt = 'usrId';
        } else {
            $repoT = $em->getRepository(Team::class);
            $repoRTH = $em->getRepository(RankingTeamHistory::class);
            $consideredEntity = 'App\Entity\RankingTeam';
            $consideredEntityElmt = 'team';
        }

        $organization = $repoO->find($orgId);

        $population = ($entityType == 'users') ?
            $repoU->findBy(['orgId' => $orgId, 'deleted' => null, 'internal' => 1]) :
            $repoT->findBy(['organization' => $organization, 'deleted' => null]);

        if (strpos(get_class($elmtType), 'Activity') !== false) {
            $rType = 'A';
        } else if (strpos(get_class($elmtType), 'Stage') !== false) {
            $rType = 'S';
        } else {
            $rType = 'C';
        }

        $activity = ($rType == 'A') ? $entity : (($rType == 'S') ? $elmtType->getActivity() : $elmtType->getStage()->getActivity());
        $stage = ($rType == 'A') ? null : (($rType == 'S') ? $entity : $elmtType->getStage());
        $criterion = ($rType == 'C') ? $entity : null;

        foreach ($population as $popElmt) {
            $popData = $popElmt->getAverage($app, true, $dType, $wType, $rType, $maxLastResults, $from, $to)[0];
            $popResultsAvg[] = $popData['avgResult'];
            $popResultsCount[] = $popData['countResult'];
        }

        $qualifiedPopResultsAvg = array_filter($popResultsAvg);
        $qualifiedPopResultsCount = array_filter($popResultsCount);

        // If dataType is a performance, then higher percentage means higher rank, which is the contrary when addressing deviation
        ($dType == 'P') ? arsort($qualifiedPopResultsAvg) : asort($qualifiedPopResultsAvg);
        ($dType == 'P') ? arsort($qualifiedPopResultsCount) : asort($qualifiedPopResultsCount);
        $k = 1;

        foreach ($qualifiedPopResultsAvg as $key => $rankingElmt) {
            //TODO : could certainly be optimized...

            $qb = $em->createQueryBuilder();

            switch ($rType) {
                case 'A':
                    if (($key == 0)) {
                        $consideredRankingQuery = $qb
                            ->select('rnk')
                            ->from($consideredEntity, 'rnk')
                            ->where('rnk.dType = \'' . $dType . '\'')
                            ->andWhere('rnk.wType = \'' . $wType . '\'')
                            ->andWhere($qb->expr()->isNull('rnk.stage'))
                            ->andWhere($qb->expr()->isNull('rnk.criterion'))
                            ->andWhere('rnk.' . $consideredEntityElmt . ' = ' . (($consideredEntityElmt == 'usrId') ? $population[$key]->getId() : $population[$key]))
                            ->getQuery();
                    } else {
                        $consideredRankingQuery = $qb
                            ->select('rnk')
                            ->from($consideredEntity, 'rnk')
                            ->where('rnk.dType = \'' . $dType . '\'')
                            ->andWhere('rnk.wType = \'' . $wType . '\'')
                            ->andWhere($qb->expr()->isNull('rnk.stage'))
                            ->andWhere($qb->expr()->isNull('rnk.criterion'))
                            ->andWhere('rnk.' . $consideredEntityElmt . ' = ' . (($consideredEntityElmt == 'usrId') ? $population[$key]->getId() : $population[$key]))
                            ->getQuery();
                    }
                    break;
                case 'S':
                    if ($key == 0) {
                        $consideredRankingQuery = $qb
                            ->select('rnk')
                            ->from($consideredEntity, 'rnk')
                            ->where('rnk.dType = \'' . $dType . '\'')
                            ->andWhere('rnk.wType = \'' . $wType . '\'')
                            ->andWhere($qb->expr()->isNotNull('rnk.stage'))
                            ->andWhere($qb->expr()->isNull('rnk.criterion'))
                            ->andWhere('rnk.' . $consideredEntityElmt . ' = ' . (($consideredEntityElmt == 'usrId') ? $population[$key]->getId() : $population[$key]))
                            ->getQuery();
                    } else {
                        $consideredRankingQuery = $qb
                            ->select('rnk')
                            ->from($consideredEntity, 'rnk')
                            ->where('rnk.dType = \'' . $dType . '\'')
                            ->andWhere('rnk.wType = \'' . $wType . '\'')
                            ->andWhere($qb->expr()->isNotNull('rnk.stage'))
                            ->andWhere($qb->expr()->isNull('rnk.criterion'))
                            ->andWhere('rnk.' . $consideredEntityElmt . ' = ' . (($consideredEntityElmt == 'usrId') ? $population[$key]->getId() : $population[$key]))
                            ->getQuery();
                    }
                    break;
                case 'C':
                    if ($key == 0) {
                        $consideredRankingQuery = $qb
                            ->select('rnk')
                            ->from($consideredEntity, 'rnk')
                            ->where('rnk.dType = \'' . $dType . '\'')
                            ->andWhere('rnk.wType = \'' . $wType . '\'')
                            ->andWhere($qb->expr()->isNotNull('rnk.criterion'))
                            ->andWhere('rnk.' . $consideredEntityElmt . ' = ' . (($consideredEntityElmt == 'usrId') ? $population[$key]->getId() : $population[$key]))
                            ->getQuery();
                    } else {
                        $consideredRankingQuery = $qb
                            ->select('rnk')
                            ->from($consideredEntity, 'rnk')
                            ->where('rnk.dType = \'' . $dType . '\'')
                            ->andWhere('rnk.wType = \'' . $wType . '\'')
                            ->andWhere($qb->expr()->isNotNull('rnk.criterion'))
                            ->andWhere('rnk.' . $consideredEntityElmt . ' = ' . (($consideredEntityElmt == 'usrId') ? $population[$key]->getId() : $population[$key]))
                            ->getQuery();
                    }
            }

            $consideredRanking = $consideredRankingQuery->getResult();

            if ($entityType == 'users') {
                $ranking = ($consideredRanking) ? $consideredRanking[0] : new Ranking;
                $historicalRanking = ($repoRH->findOneBy(['usrId' => $population[$key]->getId(), 'dType' => $dType, 'wType' => $wType, 'criterion' => $criterion, 'stage' => $stage, 'activity' => $activity])) ?: new RankingHistory;
                $ranking->setUsrId($population[$key]->getId());
                $historicalRanking->setUsrId($population[$key]->getId());

            } else {
                $ranking = ($consideredRanking) ? $consideredRanking[0] : new RankingTeam;
                $historicalRanking = ($repoRTH->findOneBy(['team' => $population[$key], 'dType' => $dType, 'wType' => $wType, 'criterion' => $criterion, 'stage' => $stage, 'activity' => $activity])) ?: new RankingTeamHistory;
                $ranking->setTeam($population[$key]);
                $historicalRanking->setTeam($population[$key]);
            }

            $ranking->setActivity($activity)->setStage($stage)->setCriterion($criterion)->setDType($dType)->setWType($wType)->setPeriod($period)->setFrequency($frequency)->setOrganization($organization)->setAbsResult($k)->setRelResult(($k) / count($qualifiedPopResultsAvg))->setValue($rankingElmt)->setSeriesPopulation($qualifiedPopResultsCount[$key])->setUpdated(new DateTime);
            $historicalRanking->setActivity($activity)->setStage($stage)->setCriterion($criterion)->setDType($dType)->setWType($wType)->setPeriod($period)->setFrequency($frequency)->setOrganization($organization)->setAbsResult($k)->setRelResult(($k) / count($qualifiedPopResultsAvg))->setValue($rankingElmt)->setSeriesPopulation($qualifiedPopResultsCount[$key]);
            $em->persist($ranking);
            $em->persist($historicalRanking);
            $k++;
        }
        try {
            $em->flush();
        } catch (OptimisticLockException $e) {
        } catch (ORMExceptionAlias $e) {
        }
    }

    public static function getOrgIdFromUser($app, User $user)
    {
        $repoP = $app['orm.em']->getRepository(Position::class);
        $repoD = $app['orm.em']->getRepository(Department::class);
        return $repoD->find($repoP->find($user->getPosition())->getDepartment())->getOrgId();
    }

    /**
     * @return null|array
     */
    public static function getAuthorizedUserAsArray($app)
    {
        // Get current authentication token
        $user = self::getAuthorizedUser($app);
        return $user ? $user->toArray() : null;
    }

    /*Get all the errors from a general form when sent with Ajax to get error JSON response, with 3 possible layers of depth*/
    public function buildErrorArray(FormInterface $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if ($child->all()) {
                foreach ($child->all() as $subchild) {
                    if ($subchild->all()) {
                        foreach ($subchild->all() as $subsubchild) {
                            if ($subsubchild->all()) {
                                foreach ($subsubchild->all() as $subsubsubchild) {
                                    if ($subsubsubchild->all()) {
                                        foreach ($subsubsubchild as $subsubsubsubchild) {
                                            if (!$subsubsubsubchild->isValid()) {
                                                $errors[$child->getName()][$subchild->getName()][$subsubchild->getName()][$subsubsubchild->getName()][$subsubsubsubchild->getName()] = str_replace('ERROR: ', '', (string) $subsubsubsubchild->getErrors(true, false));
                                            }
                                        }
                                    } else {
                                        if (!$subsubsubchild->isValid()) {
                                            $errors[$child->getName()][$subchild->getName()][$subsubchild->getName()][$subsubsubchild->getName()] = str_replace('ERROR: ', '', (string) $subsubsubchild->getErrors(true, false));
                                        }
                                    }
                                }
                            } else {
                                if (!$subsubchild->isValid()) {
                                    $errors[$child->getName()][$subchild->getName()][$subsubchild->getName()] = str_replace('ERROR: ', '', (string) $subsubchild->getErrors(true, false));
                                }
                            }
                        }
                    } else {
                        if (!$subchild->isValid()) {
                            $errors[$child->getName()][$subchild->getName()] = str_replace('ERROR: ', '', (string) $subchild->getErrors(true, false));
                        }
                    }
                }
            } else {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = str_replace('ERROR: ', '', (string) $child->getErrors(true, false));
                }
            }
        }
        return new JsonResponse($errors, 500);
    }

    public function sendStageDeadlineMails($forAllFirms = true)
    {
        $repoS = $this->em->getRepository(Stage::class);
        $repoP = $this->em->getRepository(Participation::class);
        $repoON = $this->em->getRepository(OptionName::class);
        $repoOP = $this->em->getRepository(OrganizationUserOption::class);
        $deadlineOption = $repoON->findOneByName('mailDeadlineNbDays');
        if($forAllFirms){
            $activeStages = new ArrayCollection($repoS->findBy(['status' => [0, 1]]));
        } else {
            $organization = self::getAuthorizedUser()->getOrganization();
            $activeStages = $organization->getStages()->filter(function(Stage $s){
                return $s->getStatus() == 0 || $s->getStatus() == 1;
            });
        }

        $potentialDeadlineStages = $activeStages->matching(Criteria::create()->where(Criteria::expr()->lte("gEnddate", new \DateTime))->andWhere(Criteria::expr()->neq("deadlineMailSent", true)));

        $iterator = new ArrayIterator($potentialDeadlineStages->getIterator());

        $iterator->uasort(function ($first, $second) {
            return ($first->getActivity()->getOrganization()->getId() < $second->getActivity()->getOrganization()->getId()) ? 1 : -1;
        });

        $potentialDeadlineStagesSortedByOrg = iterator_to_array($iterator);

        $currentOrg = null;

        $recipientMails = [];

        foreach ($potentialDeadlineStagesSortedByOrg as $stage) {

            $org = $stage->getActivity()->getOrganization();
            if ($org != $currentOrg) {
                if ($repoOP->findOneBy(['organization' => $org, 'oName' => $deadlineOption]) != null) {
                    $deadlineNbDays = (int)$repoOP->findOneBy(['organization' => $org, 'oName' => $deadlineOption])->getOptionFValue();
                } else {
                    $deadlineNbDays = 5;
                }
                $currentOrg = $org;
            }

            $gendDate = clone $stage->getGEnddate();
            $noticeDate = $gendDate->sub(new DateInterval('P' . $deadlineNbDays . 'D'));
            $deadlineDiff = date_diff(new \DateTime, $noticeDate);

            if ($deadlineDiff->invert == 1) {
                // We find by first criterion in order to avoid doublon
                $lateParticipants = $repoP->findBy(['criterion' => $stage->getCriteria()->first(), 'status' => [0, 1, 2]]);
                $recipients = [];
                foreach ($lateParticipants as $lateParticipant) {
                    $recipientUser = $lateParticipant->getUser();
                    if($recipientUser->getEmail() != null){
                        $recipients[] = $recipientUser;
                        $recipientMails[] = $recipientUser->getEmail();
                    }
                }
                if (sizeof($recipients) > 0) {
                    $settings['stage'] = $stage;
                    self::sendMail(null, $recipients, 'gradingDeadlineReminder', $settings);
                    $stage->setDeadlineMailSent(true);
                    $this->em->persist($stage);
                }
            }
        }
        try {
            $this->em->flush();
        } catch (OptimisticLockException $e) {
        } catch (ORMExceptionAlias $e) {
        }
        return true;
    }

    public function sendOrganizationTestingReminders()
    {
        $allOrganizations = new ArrayCollection($this->em->getRepository(Organization::class)->findAll());
        $expiringOrganizations =
            $allOrganizations->matching(
                Criteria::create()
                    ->where(Criteria::expr()->neq('expired', new \DateTime('2100-01-01 00:00:00')))
                    ->andWhere(Criteria::expr()->neq('expired', null))
                    ->andWhere(Criteria::expr()->neq('reminderMailSent', true)
                    )
            );
        $repoU = $this->em->getRepository(User::class);

        foreach ($expiringOrganizations as $expiringOrganization) {
            // Sending testing organization administration to motivate them onboard activities
            if (date_diff(new \DateTime, $expiringOrganization->getExpired())->days < 15) {
                $administrators = $repoU->findBy(['orgId' => $expiringOrganization->getId(), 'role' => [1, 4]]);
                $nonAdministrators = $repoU->findBy(['orgId' => $expiringOrganization->getId(), 'role' => [2, 3]]);
                $adminSettings['expiringDate'] = $expiringOrganization->getExpired();
                $nonAdminSettings['expiringDate'] = $expiringOrganization->getExpired();
                $adminSettings['forAdministrators'] = true;
                $nonAdminSettings['forAdministrators'] = false;
                self::sendMail(null, $administrators, 'firstMailReminderTPeriod', $adminSettings);
                self::sendMail(null, $nonAdministrators, 'firstMailReminderTPeriod', $nonAdminSettings);
                $expiringOrganization->setReminderMailSent(true);
                $this->em->persist($expiringOrganization);
            }
        }
        $this->em->flush();
        return true;
    }

    //Return an array with original array doublon(s) values only, otherwise null
    public static function array_doublon($array)
    {
        if (!is_array($array)) {
            return false;
        }

        $r_valeur = array();

        $array_unique = array_unique($array);

        if (count($array) - count($array_unique)) {
            for ($i = 0, $iMax = count($array); $i < $iMax; $i++) {
                if (!array_key_exists($i, $array_unique)) {
                    $r_valeur[] = $array[$i];
                }
            }
        }
        return $r_valeur;
    }

    // Converts to UTF-8 strings which are not correctly formatted (essential to make correct json_encode stuff)
    public function utf8ize($d)
    {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = $this->utf8ize($v);
            }
        } else if (is_string($d)) {
            return utf8_encode($d);
        }
        return $d;
    }

    // Function which sort a multi-dimensional array according to subkeys
    public function array_msort($array, $cols)
    {
        $colarr = array();
        foreach ($cols as $col => $order) {
            $colarr[$col] = array();
            foreach ($array as $k => $row) {$colarr[$col]['_' . $k] = strtolower($row[$col]);}
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\'' . $col . '\' ],' . $order . ',';
        }
        $eval = substr($eval, 0, -1) . ');';
        eval($eval);
        $ret = array();
        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k, 1);
                if (!isset($ret[$k])) {
                    $ret[$k] = $array[$k];
                }

                $ret[$k][$col] = $array[$k][$col];
            }
        }
        return $ret;
    }

    // Multidimensional array unique function

    public function unique_multidim_array($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }

    // Sort an array by its sub index

    public static function sksort(&$array, $subkey = "id", $sort_ascending = false)
    {
        if (count($array)) {
            $temp_array[key($array)] = array_shift($array);
        }

        foreach ($array as $key => $val) {
            $offset = 0;
            $found = false;

            foreach ($temp_array as $tmp_val) {
                if (!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey])) {
                    $temp_array = array_merge((array)array_slice($temp_array, 0, $offset),
                        array($key => $val),
                        array_slice($temp_array, $offset)
                    );
                    $found = true;
                }
                $offset++;
            }
            if (!$found) {
                $temp_array = array_merge($temp_array, array($key => $val));
            }
        }

        if ($sort_ascending) {
            $array = array_reverse($temp_array);
        } else {
            $array = $temp_array;
        }

    }

    // Function to create and update activities of recurring activities
    // TimeFrame can be either 'D', 'W', 'M', 'Y'
    public static function createRecurringActivities($app, Organization $organization, Recurring $recurring, User $masterUser, $name, $frequency, \DateTime $definedStartDate, $timeFrame, $gStartDateInterval, $gStartDateTimeFrame, $gEndDateInterval, $gEndDateTimeFrame, $type, $lowerbound = 0, $upperbound = 5, $step = 0.5, $maxTimeFrame = '1Y', \DateTime $definedEndDate = null)
    {

        if (!$currentUser instanceof User) {
            return new Response(null, 401);
        }

        $em = $app['orm.em'];
        $startDate = clone $definedStartDate;
        $endDate = ($definedEndDate) ?: clone $definedStartDate->add(new DateInterval('P' . $maxTimeFrame));
        $ongoingFutCurrActivities = $recurring->getOngoingFutCurrActivities();

        //1 - Count the the number of activities to be created in the system
        switch ($timeFrame) {
            case 'D':
                $nbActivitiesToCreate = floor(date_diff($endDate, $startDate)->days / $frequency);
                break;
            case 'W':
                $nbActivitiesToCreate = floor(date_diff($endDate, $startDate)->days / (7 * $frequency));
                break;
            case 'M':
                $nbActivitiesToCreate = floor(date_diff($endDate, $startDate)->m / $frequency);
                break;
            case 'Y':
                $nbActivitiesToCreate = floor(date_diff($endDate, $startDate)->y / $frequency);
                break;
        }

        if (count($ongoingFutCurrActivities) > 1) {
            $k = 0;

            while (count($ongoingFutCurrActivities) + $k < $nbActivitiesToCreate + 1) {

                $firstFutureActivity = $recurring->getOngoingFutCurrActivities()->first();

                $activity = new Activity;
                $activity->setRecurring($recurring)->setMasterUserId($masterUser->getId())->setOrganization($organization);
                $recurring->addActivity($activity);

                foreach ($firstFutureActivity->getStages() as $firstFutureActivityStage) {
                    $stage = clone $firstFutureActivityStage;
                    $stage->setActivity($activity);
                    $stage->setCreatedBy($currentUser->getId());
                    $activity->addStage($stage);

                    foreach ($firstFutureActivityStage->getCriteria() as $firstFutureActivityStageCriterion) {
                        $criterion = clone $firstFutureActivityStageCriterion;
                        $criterion->setStage($stage)->setType($type);
                        $criterion->setCreatedBy($currentUser->getId());
                        $stage->addCriterion($criterion);
                        $em->persist($criterion);

                    }

                    foreach ($firstFutureActivityStage->getParticipants() as $firstFutureActivityStageParticipant) {
                        $participant = clone $firstFutureActivityStageParticipant;
                        $participant->setStage($stage);
                        $participant->setCreatedBy($currentUser->getId());
                        $stage->addParticipant($participant);
                        $em->persist($participant);
                    }

                    $em->persist($stage);

                }
                /*
                if ($type == 1) {
                $criterion->setLowerbound($lowerbound)->setUpperbound($upperbound)->setStep($step);
                }
                 */
                $k++;

                $em->persist($activity);
                $em->flush();
            }
            $k = 0;
            while (count($ongoingFutCurrActivities) - $k > $nbActivitiesToCreate) {
                $activity = $ongoingFutCurrActivities->last();
                $recurring->removeActivity($activity);
                $em->persist($recurring);
                $k++;
                /*
            foreach ($recurring->getActivities() as $activity) {
            while (count($ongoingFutCurrActivities) > $nbActivitiesToCreate +1) {
            $recurring->removeActivity($activity);

            }
            }*/
            }
            $em->flush();

        } else {

            //$recurring->getActivities()->first()->setName(null)
            //->getStages()->first()->setStartDate(null)->setEndDate(null)->setGStartDate(null)->setGEndDate(null);

            for ($k = 1; $k <= $nbActivitiesToCreate; $k++) {
                $activity = new Activity;
                $activity->setRecurring($recurring)->setMasterUserId($masterUser->getId())->setOrganization($organization)->setStatus($recurring->getStatus());
                $recurring->addActivity($activity);

                $stage = new Stage;
                $stage->setActivity($activity);
                $stage->setCreatedBy($currentUser->getId());
                $activity->addStage($stage);

                $criterion = new Criterion;
                $criterion->setStage($stage)->setType($type)->setName('General');
                $criterion->setCreatedBy($currentUser->getId());
                $stage->addCriterion($criterion);

                $em->persist($recurring);
                $em->persist($activity);
                $em->persist($stage);
                $em->persist($criterion);

                if ($type == 1) {
                    $criterion->setLowerbound($lowerbound)->setUpperbound($upperbound)->setStep($step);
                }
            }
            $em->flush();
        }

        $ongoingFutCurrActivities = $recurring->getOngoingFutCurrActivities();

        for ($k = 1; $k <= count($ongoingFutCurrActivities); $k++) {
            $activity = ($k == 1) ? $ongoingFutCurrActivities->first() : $ongoingFutCurrActivities->next();

            $activityStartDate = clone $startDate;
            $activityStartDate2 = clone $startDate;
            $activityEndDate = clone $startDate->add(new \DateInterval('P' . $frequency . $timeFrame)) /*->sub(new \DateInterval('P1D'))*/;
            $activityGStartDate = clone $activityStartDate2->add(new \DateInterval('P' . $gStartDateInterval . $gStartDateTimeFrame));
            //$cloneGStartDate = clone $activityGStartDate;
            $cloneEndDate = clone $activityEndDate;
            $activityGEndDate = clone $cloneEndDate->add(new DateInterval('P' . $gEndDateInterval . $gEndDateTimeFrame));
            $activity->setName($name . /*' ('.$activityStartDate->format("j F y").' - '.$activityEndDate->format("j F y").')'*/ ' ' . $k)
                ->getStages()->first()->setName($name . /*' ('.$activityStartDate->format("j F y").' - '.$activityEndDate->format("j F y").')'*/ ' ' . $k)->setStartDate($activityStartDate)->setEndDate($activityEndDate)->setGStartDate($activityGStartDate)->setGEndDate($activityGEndDate)->setMasterUserId($masterUser->getId())
                ->getCriteria()->first()->setType($type)->setLowerbound(($type == 1) ? $lowerbound : null)->setUpperbound(($type == 1) ? $upperbound : null)->setStep(($type == 1) ? $step : null);
            $em->persist($recurring);
        }

        //$em->flush();

        $lastActEndDate = clone $definedEndDate;
        $lastActGStartDate = clone $definedEndDate->add(new DateInterval('P1D'));
        $lastActGEndDate = clone $definedEndDate->add(new DateInterval('P' . $gEndDateInterval . $gEndDateTimeFrame));
        $recurring->getOngoingFutCurrActivities()->last()->getStages()->first()->setEndDate($lastActEndDate)->setGStartDate($lastActGStartDate)->setGEndDate($lastActGEndDate);
        $em->persist($recurring);
        $em->flush();
    }

    // Function which checks if stage is computable, if it is the case sends mail to activity manager to access results
    public function checkStageComputability(Request $request, Stage $stage, bool $addInDb = true)
    {

        $em = self::getEntityManager();
        $entityManager = $this->getEntityManager($app);
        /** @var Participation[] */

        $uniqueNonPassiveParticipations = $stage->getUniqueGraderParticipations();
        $computable = (count($uniqueNonPassiveParticipations) > 0);
        $nbVoid = 0;
        $nbValidated = 0;
        $recipients = [];

        foreach ($uniqueNonPassiveParticipations as $uniqueNonPassiveParticipation) {
            if ($uniqueNonPassiveParticipation->getStatus() != 3) {
                $computable = false;
                if ($uniqueNonPassiveParticipation->getStatus() == 0) {
                    $nbVoid++;
                }
            } else {
                $nbValidated++;
                $user = $uniqueNonPassiveParticipation->getDirectUser();
                // Only receive mail about results being releasable, per order of importance :
                // 1 - Administrators participants, or activity managers who are leaders
                // 2 - Otherwise the department managers of collaborator leaders
                if ($user->getRole() == 1 || $user->getRole() == 4) {
                    $recipients[] = $user;
                } else {
                    if ($uniqueNonPassiveParticipation->isLeader()) {
                        if ($user->getRole() == 2) {
                            $recipients[] = $user;
                        } elseif ($user->getDepartment()) {
                            $headOfDptUser = $user->getDepartment()->getMasterUser();
                            if ($headOfDptUser) {
                                $recipients[] = $headOfDptUser;
                            }
                        }
                    }
                }
            }


        }

        $stageStatus = ($computable) ? 2 : (($nbVoid < count($uniqueNonPassiveParticipations)) ? 1 : 0);
        $stage->setStatus($stageStatus);
        $entityManager->persist($stage);
        $entityManager->flush();

        if ($computable) {
            $activity = $stage->getActivity();
            // 1 - Compute stage results for the users

            if(count($stage->getCriteria()) > 0){
                if ($stage->getMode() != MasterController::STAGE_ONLY) {
                    $valuesStage = $this->computeStageResults($stage, MasterController::USERS_ONLY, true);
                }

                // compute stage results for the stage
                if ($stage->getMode() != MasterController::USERS_ONLY) {
                    $valuesStage = $this->computeStageResults($stage, MasterController::STAGE_ONLY, true);
                }
            }
            // 2 - Set the activity as completed if all its stages are completed
            $completedActivity = true;
            foreach ($activity->getStages() as $actStage) {
                if ($actStage->getStatus() < 2) {
                    $completedActivity = false;
                    break;
                }
            }

            if ($completedActivity) {
                $activityStage = false;
                $Participation = false;
                // Recalculate all the data (but not update the database)
                if (count($stage->getCriteria()) > 0) {//
                    foreach ($activity->getStages() as $stage) {
                        if ($stage->getMode() != MasterController::STAGE_ONLY) {
                            $dataParticipation[$stage->getId()] = $this->computeStageResults($stage, MasterController::USERS_ONLY, false);
                            $Participation = true;
                        }
                        if ($stage->getMode() != MasterController::USERS_ONLY) {
                            $dataActivityStage[$stage->getId()] = $this->computeStageResults($stage, MasterController::STAGE_ONLY, false);
                            $activityStage = true;
                        }

                    }
                    if ($Participation) {
                        MasterController::computeActivityResult($activity, $dataParticipation, MasterController::USERS_ONLY, $addInDb);
                    }
                    if ($activityStage) {
                        MasterController::computeActivityResult($activity, $dataActivityStage, MasterController::STAGE_ONLY, $addInDb);
                    }
                    $activity->setStatus(2);
                    $activity->setReleased(new \DateTime);
                    try {
                        $entityManager->persist($activity);
                    } catch (ORMExceptionAlias $e) {
                    }

                }
            }
            try {
                $entityManager->flush();
            } catch (OptimisticLockException $e) {
            } catch (ORMExceptionAlias $e) {
            }

            // 3 - Send a mail to activity manager informing him that results can be released
            $settings['stage'] = $stage;
            MasterController::sendMail($app, $recipients, 'resultsReleasable', $settings);
            return true;
            }

        return true;

    }

    /**
     * @param Stage $stage
     * @param int $stageMode
     * @param bool $addInDB
     * @return bool
     *
     * set the data json for the criterias, and a stage, and will add all the results for one stage (the stage results,
     * and for all criterion in the stage
     * @throws ORMExceptionAlias
     */

    public static function computeStageResults(Stage $stage, int $stageMode, bool $addInDB)

    {
        $em = self::getEntityManager();
        # The repos to access the data in the database
        $repoP = $em->getRepository(Participation::class);
        # The user who created the activity
        $currentUser = $this->user;;
        $criteria = $stage->getCriteria();


        $activity = $stage->getActivity();
        $participations = new ArrayCollection($repoP->findBy(['criterion' => $criteria->first()], ['type' => 'ASC', 'team' => 'ASC']));
        # the data send to the framework for the criteria calculation
        $jsonData = [];
        $jsonData["userWeights"] = [];
        $jsonData["teams"] = [];
        $jsonData["teamWeights"] = [];
        $jsonData["criterias"] = [];
        # the data send to the framework for the stage
        $jsonGlobalDataStage = [];
        $jsonGlobalDataStage["stageId"] = $stage->getId();
        $jsonGlobalDataStage["user"] = [];
        $jsonGlobalDataStage["team"] = [];
        # Build the users and teams lists
        $concernedTeam = null;

        foreach ($participations as $p) {
            $jsonData["userWeights"][$au->getUsrId()] = $au->getDirectUser()->getWeight()->getValue();
            $jsonGlobalDataStage["user"][] = $au->getUsrId();
            # if new team
            if ($au->getTeam() !== null && $au->getTeam() != $concernedTeam && $stageMode != MasterController::STAGE_ONLY) {
                $concernedTeam = $au->getTeam();
                $jsonData["teams"][$concernedTeam->getId()] = [];
                $jsonData["teamWeights"][$concernedTeam->getId()] = 0;
                $jsonGlobalDataStage["team"][] = $concernedTeam->getId();
            }
            if ($au->getTeam() != null  && $stageMode != MasterController::STAGE_ONLY) {
                # add the team member id in the teams id
                $jsonData["teams"][$concernedTeam->getId()][] = $au->getUsrId();
                # add the weight of the member in the team weight
                $jsonData["teamWeights"][$concernedTeam->getId()] += $au->getDirectUser()->getWeight()->getValue();
            }
        }
        # If the stage is graded
        if ($stageMode != MasterController::USERS_ONLY) {
            $jsonData["userWeights"][-1] = 1;
            $jsonGlobalDataStage["user"][] = -1;
        }
        # build the criteria data
        $jsonData = MasterController::prepareJsonDataCriteria($stageMode, $criteria, $repoP, $jsonData);
        # call the microframework for the criteria results
        $jsonFile = json_encode($jsonData);
        $calculatedValues = MasterController::callFramework($jsonFile, MasterController::CRITERIA);



        $resultType = ($criteria->first()->getType() == 0) ? 0 : 1;



        # add all the calculated values in the database
        foreach ($criteria as $criterion) {
            $jsonGlobalDataStage[$criterion->getId()] = $calculatedValues[$criterion->getId()];
            $jsonGlobalDataStage[$criterion->getId()]["weight"] = $criterion->getWeight();
            if ($addInDB) {
                MasterController::addDataInDataBase($em, $activity, $stage, $criterion, $participations, $currentUser, $resultType, $calculatedValues, $stageMode);
            }
        }

        # fix the problem of absolute result if the bounds are different
        $jsonGlobalDataStage = MasterController::fixData($jsonGlobalDataStage, $criteria);
        # call the micro framework for the stage results
        $jsonFile = json_encode($jsonGlobalDataStage);
        # Do the request
        $calculatedValues = MasterController::callFramework($jsonFile, MasterController::STAGE);
        $calculatedValues["step"]["weight"] = $stage->getWeight();
        # Add all the calculated data about the stage in the data base

        if ($addInDB) {
            MasterController::addDataInDataBase($em, $activity, $stage, null, $participations, $currentUser, 0, $calculatedValues, $stageMode);
        }

        return $calculatedValues;
    }

    // Function which checks if stage is computable, if it is the case sends mail to activity manager to access results

    /**
     * @param int $stageMode value : USER_ONLY or STAGE_ONLY
     * @param $criteria criteria of a stage
     * @param $repoP
     * @param $jsonData jsondata in computeStageResult, in construction
     * @return mixed
     *
     * Add all information about the grades for all criterion in a stage, to be sent to the framework
     * Should be only called by computeStageResult
     */
    public static function prepareJsonDataCriteria(int $stageMode, $criteria, $repoP, $jsonData)
    {
        foreach ($criteria as $criterion) {
            $participations = new ArrayCollection($repoP->findBy(['criterion' => $criterion], ['type' => 'ASC', 'team' => 'ASC']));
            $jsonData["criterias"][$criterion->getId()] = [];
            $jsonData["criterias"][$criterion->getId()]["userGrades"] = [];
            $jsonData["criterias"][$criterion->getId()]["teamGrades"] = [];
            # Getting the bounds for the criteria
            if ($criterion->getType() == self::EVALUATION_COMMENT) {
                $jsonData["criterias"][$criterion->getId()]["lowerBound"] = $criterion->getLowerbound();
                $jsonData["criterias"][$criterion->getId()]["upperBound"] = $criterion->getUpperbound();
            }
            # Loop on the graders
            foreach ($participations as $p) {
                #  Loop on the graded
                $nbTeamMember = 0;
                foreach ($au->getGrades() as $grade) {
                    $gradedId = $grade->getGradedUsrId();
                    $gradedTeamId = $grade->getGradedTeaId();
                    # Case a user grade an other user
                    if ($gradedId != null && $stageMode != MasterController::STAGE_ONLY && $grade->getValue() != null) {
                        $jsonData["criterias"][$criterion->getId()]["userGrades"][$au->getUsrId()][$gradedId] = $grade->getValue();
                    }
                    # case a user grade a team that is not his own team
                    if ($gradedId == null && $gradedTeamId != null && $stageMode != MasterController::STAGE_ONLY && $grade->getValue() != null) {
                        $jsonData["criterias"][$criterion->getId()]["teamGrades"][$au->getUsrId()][$gradedTeamId] = $grade->getValue();
                    }
                    # case the stage is graded
                    if ($stageMode != MasterController::USERS_ONLY && $grade->getValue() != null) {
                        $jsonData["criterias"][$criterion->getId()]["userGrades"][$au->getUsrId()][-1] = $grade->getValue();
                    }
                    # case the user grades a member of his team
                    if ($gradedId != null  && $gradedTeamId != null && $stageMode != MasterController::STAGE_ONLY && $grade->getValue() != null){
                        # first member of the team to be graded
                        if ($nbTeamMember == 0){
                            $jsonData["criterias"][$criterion->getId()]["teamGrades"][$au->getUsrId()][$gradedTeamId] = $grade->getValue();
                            $nbTeamMember ++;
                        }
                        else{
                            $jsonData["criterias"][$criterion->getId()]["teamGrades"][$au->getUsrId()][$gradedTeamId] += $grade->getValue();
                            $nbTeamMember ++;
                        }
                    }
                }
                # case the user graded his team
                if ($nbTeamMember > 0){
                    $jsonData["criterias"][$criterion->getId()]["teamGrades"][$au->getUsrId()][$au->getTeam()->getId()] /= $nbTeamMember;
                }
            }
        }
        return $jsonData;
    }

    /**
     * @param String $jsonFile a jsonFile, with all data for the framework
     * @param int $requestType if the request concern criteria, or a stage/activity
     * @return mixed the values calculated by the framework
     */
    public static function callFramework(string $jsonFile, int $requestType)
    {
        if ($requestType == MasterController::CRITERIA) {
            $requestUrl = MasterController::SERVEUR_URL_PREFIXE . "" . self::MAIN_CRITERIA_COMPUTATION;
        } else {
            $requestUrl = MasterController::SERVEUR_URL_PREFIXE . "/main/stageComputation";
        }
        # tests with curl
        $curl = curl_init($requestUrl);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonFile);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $calculatedValues = json_decode(curl_exec($curl), true);
        curl_close($curl);
        return $calculatedValues;
    }

    /**
     * @param EntityManager $em
     * @param Activity $activity : current activity
     * @param Stage|null $stage : current stage, is null if the function compute the global data for an activity
     * @param Criterion|null $criterion : can be null if the function compute the global data for an activity or a stage
     * @param $participations : liste of the Participations, used for individual data computation
     * @param $currentUser : the one who created the activity
     * @param $resultType : ??
     * @param $calculatedValues : values send by the  framework of result calculation
     * @param int $stageMode
     * @throws ORMExceptionAlias This function take the results calculated with the framework (for ONE criteria, one stage or one activity), and
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * add all the values in the database (set the global data, and the individual with setIndividualData
     */
    public static function addDataInDataBase(
        EntityManager $em, Activity $activity, ?Stage $stage, ?Criterion $criterion, $participations, $currentUser, $resultType, $calculatedValues, int $stageMode
    )
    {
        if ($stageMode != MasterController::STAGE_ONLY) {
            # Compute the individual results for all users
            foreach ($participations as $user) {
                try {
                    MasterController::setIndividualData($em, $activity, $stage, $criterion, $currentUser, "users", $calculatedValues, $user->getUsrId(), $user->getType(),$user->getExtUsrId(), "user", $stageMode,);
                } catch (ORMExceptionAlias $e) {
                }
            }
            # Compute the teams results
            $concernedTeam = null;
            foreach ($participations as $p) {
                if ($au->getTeam() !== null && $au->getTeam() != $concernedTeam) {
                    $concernedTeam = $au->getTeam();
                    try {
                        MasterController::setIndividualData($em, $activity, $stage, $criterion, $currentUser, "team", $calculatedValues, $concernedTeam->getId(), $au->getType(), $concernedTeam,"team", $stageMode,);
                    } catch (ORMExceptionAlias $e) {
                    }
                }
            }
        }
        # the criteria must have an ID in the data from the framework, so set to "step" if criteria is null
        if ($criterion != null) {
            $criteriaId = $criterion->getId();
        } else {
            $criteriaId = "step";
        }

        # add the global results in the data base
        $resultProject = new ResultProject();
        $resultUsers = new Result();
        $resultTeams = new ResultTeam;
        if ($stageMode != MasterController::USERS_ONLY) {
            $resultProject
                ->setActivity($activity)
                ->setStage($stage)
                ->setCriterion($criterion)
                ->setWeightedAbsoluteResult($calculatedValues[$criteriaId]["averageUsersWeightedResult"])
                ->setEqualAbsoluteResult($calculatedValues[$criteriaId]["averageUsersEqualResult"])
                ->setWeightedRelativeResult($calculatedValues[$criteriaId]["averageUsersWeightedRelativeResult"])
                ->setEqualRelativeResult($calculatedValues[$criteriaId]["averageUsersEqualRelativeResult"])
                ->setWeightedStdDev($calculatedValues[$criteriaId]["averageUsersWeightedStdDev"])
                ->setEqualStdDev($calculatedValues[$criteriaId]["averageUsersEqualStdDev"])
                ->setWeightedStdDevMax($calculatedValues[$criteriaId]["maxUsersWeightedStdDev"])
                ->setEqualStdDevMax($calculatedValues[$criteriaId]["maxUsersEqualStdDev"])
                ->setWeightedInertia($calculatedValues[$criteriaId]["weightedUserInertia"])
                ->setEqualInertia($calculatedValues[$criteriaId]["equalUserInertia"])
                ->setWeightedInertiaMax($calculatedValues[$criteriaId]["maxWeightedUserInertia"])
                ->setEqualInertiaMax($calculatedValues[$criteriaId]["maxEqualUserInertia"])
                ->setWeightedDistanceRatio($calculatedValues[$criteriaId]["weightedUserDevRatio"])
                ->setEqualDistanceRatio($calculatedValues[$criteriaId]["equalUserDevRatio"])
                ->setType($resultType)
                ->setCreatedBy($currentUser->getId());
            $em->persist($resultProject);
        }
        # set the global results about the users in the database
        if ($stageMode != MasterController::STAGE_ONLY) {

            $resultUsers->setActivity($activity)
                ->setStage($stage)
                ->setCriterion($criterion)
                ->setWeightedAbsoluteResult($calculatedValues[$criteriaId]["averageUsersWeightedResult"])
                ->setEqualAbsoluteResult($calculatedValues[$criteriaId]["averageUsersEqualResult"])
                ->setWeightedRelativeResult($calculatedValues[$criteriaId]["averageUsersWeightedRelativeResult"])
                ->setEqualRelativeResult($calculatedValues[$criteriaId]["averageUsersEqualRelativeResult"])
                ->setWeightedStdDev($calculatedValues[$criteriaId]["averageUsersWeightedStdDev"])
                ->setEqualStdDev($calculatedValues[$criteriaId]["averageUsersEqualStdDev"])
                ->setWeightedStdDevMax($calculatedValues[$criteriaId]["maxUsersWeightedStdDev"])
                ->setEqualStdDevMax($calculatedValues[$criteriaId]["maxUsersEqualStdDev"])
                ->setWeightedInertia($calculatedValues[$criteriaId]["weightedUserInertia"])
                ->setEqualInertia($calculatedValues[$criteriaId]["equalUserInertia"])
                ->setWeightedInertiaMax($calculatedValues[$criteriaId]["maxWeightedUserInertia"])
                ->setEqualInertiaMax($calculatedValues[$criteriaId]["maxEqualUserInertia"])
                ->setWeightedDistanceRatio($calculatedValues[$criteriaId]["weightedUserDevRatio"])
                ->setEqualDistanceRatio($calculatedValues[$criteriaId]["equalUserDevRatio"])
                ->setType($resultType)
                ->setCreatedBy($currentUser->getId());
//            ob_flush();
//            ob_start();
//            var_dump( $em->getConnection()->getConfiguration()->getSQLLogger());
//            file_put_contents("../../result10", ob_get_flush());
            $em->persist($resultUsers);
            $em->flush();
//            ob_flush();
//            ob_start();
//            var_dump( $em->getConnection()->getConfiguration()->getSQLLogger());
//            file_put_contents("../../result20", ob_get_flush());
//            file_put_contents("../../result30", $resultUsers->getStage()==null? "stage null, erreur": $resultUsers->getStage()->getId());
//            file_put_contents("../../result50", $resultUsers->getCriterion()==null? "criterion null, ok": "erreur");
        }
        # set the global results about the teams in the database
        if ($calculatedValues[$criteriaId]["team"] != []) {
            $resultTeams->setCriterion($criterion)
                ->setStage($stage)
                ->setActivity($activity)
                ->setWeightedAbsoluteResult($calculatedValues[$criteriaId]["averageTeamsWeightedResult"])
                ->setEqualAbsoluteResult($calculatedValues[$criteriaId]["averageTeamsEqualResult"])
                ->setWeightedRelativeResult($calculatedValues[$criteriaId]["averageTeamsWeightedRelativeResult"])
                ->setEqualRelativeResult($calculatedValues[$criteriaId]["averageTeamsEqualRelativeResult"] )
                ->setWeightedStdDev($calculatedValues[$criteriaId]["averageTeamsWeightedStdDev"])
                ->setEqualStdDev($calculatedValues[$criteriaId]["averageTeamsEqualStdDev"])
                ->setWeightedStdDevMax($calculatedValues[$criteriaId]["maxTeamsWeightedStdDev"])
                ->setEqualStdDevMax($calculatedValues[$criteriaId]["maxTeamsEqualStdDev"])
                ->setWeightedInertia($calculatedValues[$criteriaId]["weightedTeamInertia"])
                ->setEqualInertia($calculatedValues[$criteriaId]["equalTeamInertia"])
                ->setWeightedInertiaMax($calculatedValues[$criteriaId]["maxWeightedTeamInertia"])
                ->setEqualInertiaMax($calculatedValues[$criteriaId]["maxEqualTeamInertia"])
                ->setWeightedDistanceRatio($calculatedValues[$criteriaId]["weightedTeamDevRatio"])
                ->setEqualDistanceRatio($calculatedValues[$criteriaId]["equalTeamDevRatio"])
                ->setType($resultType)
                ->setCreatedBy($currentUser->getId());
            # confirm the db query
            $em->persist($resultTeams);
        }

    }

    /**
     * @param EntityManager $em
     * @param Activity|null $activity
     * @param Stage|null $stage
     * @param Criterion|null $criteria
     * @param $currentUser : an "authorized user from master controller, for the cratedby
     * @param $resultType : 0 or 1 ??
     * @param $calculatedValues : the data calculated with the micro framework, for criteria
     * @param $entityId the id of the entity concerned by the result (it can be a team or a user id)
     * @param int $entityType : the type of the user/team (active, passive, third)
     * @param $extId : in user case, if of the external user, team case contains the team
     * TODO rename $extId
     * @param String $userType "user" or "team", if the user is a user or a team
     * @param int $stageMode : if the stage or the user is graded
     * @throws ORMExceptionAlias add the individual data of ONE user or Team in the data base (for one criteria, stage or activity)
     * add the results of an entity (a user or a team) in the data base, can be called for a criteria, a stage or an activity
     */
    public static function setIndividualData(
        EntityManager $em,
        Activity $activity,
        ?Stage $stage,
        ?Criterion $criteria,
        $currentUser,
        $resultType,
        $calculatedValues,
        $entityId,
        int $entityType,
        $extId,
        string $userType,
        int $stageMode
    )
    {
        if ($userType == "user" ) {
            $result = new Result;
            $result->setUsrId($entityId);
        } else {
            $result = new ResultTeam;
            $result->setTeam($extId);
        }
        # the criteria must have an ID in the data from the framework, so set to "step" if criteria is null
        if ($criteria != null) {
            $criteriaId = $criteria->getId();
        } else {
            $criteriaId = "step";
        }
        $result->setCreatedBy($currentUser->getId());
        # Add the results (weighted, equal, relative)
        if ($entityType != Participation::PARTICIPATION_THIRD_PARTY && $stageMode != MasterController::STAGE_ONLY) {
            $result->setCriterion($criteria)
                ->setStage($stage)
                ->setActivity($activity)
                ->setWeightedAbsoluteResult($calculatedValues[$criteriaId][$userType][$entityId]["weightedResult"])
                ->setEqualAbsoluteResult($calculatedValues[$criteriaId][$userType][$entityId]["equalResult"])
                ->setWeightedRelativeResult($calculatedValues[$criteriaId][$userType][$entityId]["weightedRelativeResult"])
                ->setEqualRelativeResult($calculatedValues[$criteriaId][$userType][$entityId]["equalRelativeResult"])
                ->setType($resultType)
                ->setCreatedBy($currentUser->getId());

        }
        # add the deviation information
        if ($entityType != Participation::PARTICIPATION_PASSIVE && $stageMode != MasterController::STAGE_ONLY) {
            $result->setCriterion($criteria)
                ->setStage($stage)
                ->setActivity($activity)
                ->setWeightedStdDev($calculatedValues[$criteriaId][$userType][$entityId]["weightedStdDev"])
                ->setEqualStdDev($calculatedValues[$criteriaId][$userType][$entityId]["equalStdDev"])
                ->setWeightedDevRatio($calculatedValues[$criteriaId][$userType][$entityId]["weightedDevRatio"])
                ->setEqualDevRatio($calculatedValues[$criteriaId][$userType][$entityId]["equalDevRatio"])
                ->setType($resultType)
                ->setCreatedBy($currentUser->getId());
        }
        if ($userType == "user"){
            $result->setExternalUsrId($extId);
        }
        $em->persist($result);
        $em->flush();
    }

    /**
     * @param $data
     * @param $criteria
     *
     * If the bounds are not the same for all criteria, the absolute result doesn't make sense, so the data about it are
     * set to null
     * @return mixed the data after the modifications
     */
    public static function fixData($data, $criteria)
    {
        # Test if the bound are the same for all the criteria
        $lb = $criteria->first()->getLowerBound();
        $ub = $criteria->first()->getUpperBound();
        $toFix = false;
        foreach ($criteria as $criterion) {
            if ($criterion->getLowerBound() != $lb or $criterion->getUpperBound() != $ub) {
                $toFix = true;
                break;
            }
        }
        # Fix data if necessary
        if ($toFix) {
            foreach ($data as $key => $element) {
                if ($key != "user" && $key != "team" && $key != "stageId") {
                    # global data
                    $data[$key]["averageUsersWeightedResult"] = null;
                    $data[$key]["averageUsersEqualResult"] = null;
                    $data[$key]["averageTeamsWeightedResult"] = null;
                    $data[$key]["averageTeamsEqualResult"] = null;
                    # user data
                    foreach ($data[$key]["user"] as $userkey => $user) {
                        $data[$key]["user"][$userkey]["weightedResult"] = null;
                        $data[$key]["user"][$userkey]["equalResult"] = null;
                    }
                    # team data
                    if (in_array("team", array_keys($data[$key]))) {
                        foreach ($data[$key]["team"] as $teamkey => $team) {
                            $data[$key]["team"][$teamkey]["weightedResult"] = null;
                            $data[$key]["team"][$teamkey]["equalResult"] = null;
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * @param Activity $activity
     * @param $dataActivity the data sent to the framework, calculated with computeStageResults and checkStageComputability
     * @param int $stageMode
     * @throws ORMExceptionAlias Calculate the global results for an activity, and add the results in the database
     * take the data from the stages of the activity, and calculate all the results for the activity
     * called once for the "stage" result, and once for the "user" result (the one graded)
     */
    public static function computeActivityResult(Activity $activity, $dataActivity, int $stageMode, bool $addInDb = true)
    {
        $em = self::getEntityManager();
        $repoP = $em->getRepository(Participation::class);
        $repoRT = $em->getRepository(ResultTeam::class);
        $participations = new ArrayCollection($repoP->findBy(['criterion' => $activity->getStages()->first()->getCriteria()->first()], ['type' => 'ASC', 'team' => 'ASC']));
        # refactor the dataActivity, to correspond to the framework
        foreach ($dataActivity as $key => $step) {
            $dataActivity[$key] = $step["step"];
        }
        # add missing data (the list of user and team)
        $dataActivity["user"] = [];
        $dataActivity["team"] = [];
        $concernedTeam = null;
        foreach ($participations as $p) {
            $dataActivity["user"][] = $au->getUsrId();
            # if new team
            if ($au->getTeam() !== null && $au->getTeam() != $concernedTeam && $stageMode != MasterController::STAGE_ONLY) {
                $concernedTeam = $au->getTeam();
                $dataActivity["team"][$concernedTeam->getId()] = [];
            }
        }
        # the one who created the activity
        $currentUser = $this->user;;
        $criteria = new ArrayCollection();
        # set the stages data correctly
        foreach ($activity->getStages() as $stage) {
            # function called for the "rated stage" stage, and the "user rated" stage. So we make the difference, and take only the stages graded correctly
            if (($stage->getMode() <= 1 && $stage->getMode() == $stageMode) || $stage->getMode() > 1) {
                foreach ($stage->getCriteria() as $criterion) {
                    $criteria->add($criterion);
                }
            }
        }
        # remove the results if the bounds are different
        $dataActivity = MasterController::fixData($dataActivity, $criteria);
        $jsonFile = json_encode($dataActivity);
        # Make the request to the framework
        $calculatedValues = MasterController::callFramework($jsonFile, MasterController::ACTIVITY);
        # Add the data in the database
        if ($addInDb){
            MasterController::addDataInDataBase($em, $activity, null, null, $participations, $currentUser, 0, $calculatedValues, $stageMode);
        }

    }
}
