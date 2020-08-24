<?php

namespace App\Controller;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use App\Entity\Activity;
use App\Entity\Participation;
use App\Entity\Criterion;
use App\Entity\DbObject;
use App\Entity\Grade;
use App\Entity\OrganizationUserOption;
use App\Entity\Result;
use App\Entity\ResultProject;
use App\Entity\Stage;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class ActivityResultsController extends MasterController
{
    private static $gradeRepo;
    private static $stageRepo;
    private static $activityRepo;
    private static $criterionRepo;
    private static $resultProjectRepo;

    private const globalAvgColorCode = '#3366cc';
    private const elementAvgColorCode = '#dc3912';
    private const colors = [
        '#ff9900',
        '#109618',
        '#990099',
        '#0099c6',
        '#dd4477',
        '#66aa00',
    ];

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
        self::$gradeRepo = self::$em->getRepository(Grade::class);
        self::$stageRepo = self::$em->getRepository(Stage::class);
        self::$activityRepo = self::$em->getRepository(Activity::class);
        self::$criterionRepo = self::$em->getRepository(Criterion::class);
        self::$resultProjectRepo = self::$em->getRepository(ResultProject::class);
    }

    /**
     * @template T
     * @param \Generator<T> $generator
     * @return T
     */
    public static function getNext(\Generator $generator)
    {
        $return = $generator->current();
        $generator->next();
        return $return;
    }

    /**
     * @template T
     * @param array<T> $arr
     * @return \Generator<T>
     */
    public static function arrayRepeatGenerator(array $arr): \Generator
    {
        $count = count($arr);
        $i = 0;

        while (true) {
            $i = $i == $count ? 0 : $i;
            yield $arr[$i++];
        }
    }

    public static function userCanPublish(DbObject $actOrStage, User $user)
    {
        if (!($actOrStage instanceof Activity || $actOrStage instanceof Stage)) {
            return false;
        }

        $role = $user->getRole();

        if ($role == 4 || $role == 1) {
            return true;
        }

        return $actOrStage->getParticipants()->filter(
            fn(Participation $e) => $e->getDirectUser() == $user
        )->exists(
            fn(int $i, Participation $e) => $e->isLeader()
        );
    }


    public function getPerfResultsChart(int $actId, int $stgId = null, int $crtId = null, bool $aggregated = false): JsonResponse
    {
        $chartJs = ['datasets' => []];
        $canViewDetailedResults = true;
        $results = self::getPerfResults($actId, $stgId, $crtId, $aggregated);
        $globalAvgColorCode = '#3366cc';
        $elementAvgColorCode = '#dc3912';
        /** @var string[] */
        $colors = [
            '#ff9900',
            '#109618',
            '#990099',
            '#0099c6',
            '#dd4477',
            '#66aa00',
        ];

        $colorsGenerator = self::arrayRepeatGenerator($colors);

        // results of criterion
        if ($crtId) {
            /** @var int */
            $max = $results['max'];
            /** @var string */
            $criterionAvg = $results['average'];
            $percentCharacter = $criterionAvg == 100 ? ' %' : '';
            /** @var array */
            $participants = $results['participants'];
            $participantsNames = array_keys($participants);
            $participantsAvgs = array_values(array_map(function (array $e): string {
                return $e['average'];
            }, $participants));
            $gradeeCount = count($participantsNames);
            /** @var string[] */
            $graders = array_keys($participants[$participantsNames[0]]['graders']);
            $chartJs['labels'] = $participantsNames;

            // criterion avg result
            $chartJs['datasets'][] = [
                'type' => 'line',
                'backgroundColor' => $globalAvgColorCode,
                'borderColor' => $globalAvgColorCode,
                'fill' => false,
                'label' => "Moyenne du critère ($criterionAvg$percentCharacter)",
                'pointHoverRadius' => '0',
                'pointRadius' => '0',
                'data' => array_fill(0, $gradeeCount, $criterionAvg),
            ];

            if (!$canViewDetailedResults) {
                // detailed results are not available: per-user averages are represented with bars
                $chartJs['datasets'][] = [
                    'type' => 'bar',
                    'backgroundColor' => $elementAvgColorCode,
                    'borderColor' => $elementAvgColorCode,
                    'fill' => true,
                    'label' => 'Moyenne individuelle',
                    'data' => $participantsAvgs,
                ];
            } else {
                $chartJs['datasets'][] = [
                    'type' => 'line',
                    'backgroundColor' => $elementAvgColorCode,
                    'borderColor' => $elementAvgColorCode,
                    'fill' => false,
                    'label' => 'Moyenne individuelle',
                    'pointHoverRadius' => '10',
                    'pointRadius' => '5',
                    'data' => $participantsAvgs,
                ];

                foreach ($graders as $grader) {
                    // for every grader there is a dataset, and the notes they've given to participants
                    // are inserted in the order in which they appear in the label array
                    $perGradeeData = array_values(array_map(function (array $participant) use ($grader): string {
                        return $participant['graders'][$grader];
                    }, $participants));

                    $color = self::getNext($colorsGenerator);
                    $chartJs['datasets'][] = [
                        'type' => 'bar',
                        'backgroundColor' => $color,
                        'borderColor' => $color,
                        'fill' => true,
                        'label' => $grader,
                        'data' => $perGradeeData,
                    ];
                }
            }
        }
        // results of stage
        elseif ($stgId) {
            /** @var int */
            $max = $results['max'];
            /** @var string */
            $stageAvg = $results['average'];
            /** @var string[] */
            $participants = $results['participants'];

            // stage avg result (without data)
            $chartJs['datasets'][] = [
                'type' => 'line',
                'backgroundColor' => $globalAvgColorCode,
                'borderColor' => $globalAvgColorCode,
                'fill' => false,
                'label' => "Moyenne du stage ($stageAvg %)",
                'pointHoverRadius' => '0',
                'pointRadius' => '0',
            ];

            if ($aggregated) {
                /** @var array */
                $criteria = $results['criteria'];
                $criteriaCount = count($criteria);
                $criteriaAvgs = array_map(function (array $criterion): string {
                    return $criterion['average'];
                }, $criteria);

                $chartJs['labels'] = array_keys($criteria);

                // add data property in stage avg
                $chartJs['datasets'][0]['data'] = array_fill(0, $criteriaCount, $stageAvg);

                // averages of every criterion
                $chartJs['datasets'][] = [
                    'type' => 'line',
                    'backgroundColor' => $elementAvgColorCode,
                    'borderColor' => $elementAvgColorCode,
                    'fill' => false,
                    'label' => 'Moyenne des critères',
                    'pointHoverRadius' => '10',
                    'pointRadius' => '5',
                    'data' => $criteriaAvgs,
                ];

                foreach ($participants as $participant) {
                    // for every participant there is a dataset, and the notes they've given to participants
                    // are inserted in the order in which they appear in the label array
                    $perCriterionData = array_values(array_map(function (array $criterion) use ($participant): string {
                        return $criterion['participants'][$participant];
                    }, $criteria));

                    $color = self::getNext($colorsGenerator);
                    $chartJs['datasets'][] = [
                        'type' => 'bar',
                        'backgroundColor' => $color,
                        'borderColor' => $color,
                        'fill' => true,
                        'label' => $participant,
                        'data' => $perCriterionData,
                    ];
                }
            } else {
                $participantsCount = count($participants);
                $participantsValues = array_values($participants);
                $chartJs['labels'] = array_keys($participants);

                // add data property in stage avg
                $chartJs['datasets'][0]['data'] = array_fill(0, $participantsCount, $stageAvg);

                $chartJs['datasets'][] = [
                    'type' => 'bar',
                    'backgroundColor' => $elementAvgColorCode,
                    'borderColor' => $elementAvgColorCode,
                    'fill' => true,
                    'label' => 'Moyenne individuelle',
                    'data' => $participantsValues,
                ];
            }
        }
        // results of activity
        else {
            /** @var int */
            $max = $results['max'];
            /** @var string */
            $activityAvg = $results['average'];
            /** @var string[] */
            $participants = $results['participants'];

            if ($aggregated) {
                /** @var array */
                $stages = $results['stages'];
                $stagesCount = count($stages);
                $stagesAvgs = array_values(array_map(function (array $stage): string {
                    return $stage['average'];
                }, $stages));

                $chartJs['labels'] = array_keys($stages);

                // activity avg result
                $chartJs['datasets'][] = [
                    'type' => 'line',
                    'backgroundColor' => $globalAvgColorCode,
                    'borderColor' => $globalAvgColorCode,
                    'fill' => false,
                    'label' => "Moyenne de l'activité ($activityAvg %)",
                    'pointHoverRadius' => '0',
                    'pointRadius' => '0',
                    'data' => array_fill(0, $stagesCount, $activityAvg),
                ];

                // averages of every stage
                $chartJs['datasets'][] = [
                    'type' => 'line',
                    'backgroundColor' => $elementAvgColorCode,
                    'borderColor' => $elementAvgColorCode,
                    'fill' => false,
                    'label' => 'Moyenne des phases',
                    'pointHoverRadius' => '10',
                    'pointRadius' => '5',
                    'data' => $stagesAvgs,
                ];

                foreach ($participants as $participant) {
                    // for every participant there is a dataset, and their notes
                    // are inserted in the order in which they appear in the label array
                    $perStageData = array_values(array_map(function (array $stage) use ($participant): string {
                        return $stage['participants'][$participant];
                    }, $stages));

                    $color = self::getNext($colorsGenerator);
                    $chartJs['datasets'][] = [
                        'type' => 'bar',
                        'backgroundColor' => $color,
                        'borderColor' => $color,
                        'fill' => true,
                        'label' => $participant,
                        'data' => $perStageData,
                    ];
                }
            } else {
                $participantsCount = count($participants);
                $participantsValues = array_values($participants);

                $chartJs['labels'] = array_keys($participants);

                // activity avg result
                $chartJs['datasets'][] = [
                    'type' => 'line',
                    'backgroundColor' => $globalAvgColorCode,
                    'borderColor' => $globalAvgColorCode,
                    'fill' => false,
                    'label' => "Moyenne de l'activité ($activityAvg %)",
                    'pointHoverRadius' => '0',
                    'pointRadius' => '0',
                    'data' => array_fill(0, $participantsCount, $activityAvg),
                ];

                $chartJs['datasets'][] = [
                    'type' => 'bar',
                    'backgroundColor' => $elementAvgColorCode,
                    'borderColor' => $elementAvgColorCode,
                    'fill' => true,
                    'label' => 'Moyenne individuelle',
                    'data' => $participantsValues,
                ];
            } // end if criterion/stage/activity
        }

        return new JsonResponse([
            'data' => $chartJs,
            'max' => $max,
        ]);
    }

    /**
     * @param int $actId
     * @param int|null $stgId
     * @param int|null $crtId
     * @param bool $aggregated
     * @return JsonResponse
     * @throws Exception
     * @Route("/perfresults/{actId}/{stgId}/{crtId}/{aggregated}/")
     */
    public function getPerfResultsJson(int $actId, int $stgId = null, int $crtId = null, bool $aggregated = false): JsonResponse
    {
        return new JsonResponse(self::getPerfResults($actId, $stgId, $crtId, $aggregated));
    }

    /**
     * Computes results for an activity and returns adequate data.
     * If both stage and criterion IDs are specified, results for a specific criterion of a specific stage will be returned.
     * If only stage ID is specified, averaged results for a specific stage will be returned.
     * If none of these are specified, averaged results for the entire activity will be returned.
     * The optional boolean parameter `aggregated` can be set to true to obtain aggregated (e.g. per-user for every stage/criterion) results.
     * Note that this parameter is ignored if criterion results are requested (both stage and criterion IDs are specified) as there is nothing to aggregate.
     *
     * @param int $actId ID of activity
     * @param int $stgId ID of stage
     * @param int $crtId ID of criteria
     * @param bool $aggregated
     * @return array
     * @throws Exception
     */
    private static function getPerfResults(int $actId, int $stgId = null, int $crtId = null, bool $aggregated = false): array
    {
        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            throw new Exception('unauthorized');
        }

        // $resultsView = self::getActivitiesAccessAndResultsView();
        $canViewDetailedResults = true;
        $em = self::getEntityManager();
        $activityRepo = $em->getRepository(Activity::class);
        $stageRepo = $em->getRepository(Stage::class);
        $criterionRepo = $em->getRepository(Criterion::class);

        // results of criterion
        if ($crtId) {
            /** @var Criterion|null */
            $criterion = $criterionRepo->find($crtId);
            if (!$criterion) {
                throw new Exception('criterion not found');
            }

            return self::getCriterionResults($criterion, $canViewDetailedResults);
        }
        // results of stage
        elseif ($stgId) {
            /** @var Stage|null */
            $stage = $stageRepo->find($stgId);
            if (!$stage) {
                throw new Exception('stage not found');
            }

            return self::getStageResults($stage, $aggregated);
        }
        // results of activity
        else {
            /** @var Activity|null */
            $activity = $activityRepo->find($actId);
            if (!$activity) {
                throw new Exception('activity not found');
            }

            return self::getActivityResults($activity, $aggregated);
        }
    }

    private static function getActivityResults(Activity $activity, bool $aggregated = false): array
    {
        $em = self::getEntityManager();
        $resultRepo = $em->getRepository(Result::class);

        $scale = self::getScale($activity);
        $relative = !$scale->sameScale;
        $upperbound = $scale->upperbound;

        /** @var User[] */
        $participants = $activity->getParticipants()->map(function (Participation $p) {
            return $p->getUser();
        })->getValues();

        /** @var Result|null */
        $activityAvgResult = $resultRepo->findOneBy(['activity' => $activity, 'stage' => null, 'criterion' => null, 'usrId' => null]);
        if (!$activityAvgResult) {
            throw new Exception('activityAvgResult is null');
        }
        $activityAverage = $relative
        ? $activityAvgResult->getWeightedRelativeResult() * 100
        : $activityAvgResult->getWeightedAbsoluteResult();

        // build activity results data structure
        $activityResults = [
            'average' => self::toFixed($activityAverage, 2),
            'max' => $upperbound,
        ];

        if ($aggregated) {
            $activityResults['stages'] = [];
            $activityResults['participants'] = array_values(array_unique(array_map(function (User $u): string {
                return $u->getFullName();
            }, $participants)));

            $stages = $activity->getStages();
            if (!($stages instanceof Collection)) {
                throw new Exception('stages not a Collection');
            }
            $stagesValues = $stages->getValues();

            foreach ($stagesValues as $stage) {
                $stageName = $stage->getName();

                $activityResults['stages'][$stageName] = self::getStageResults($stage, false, $relative);
            }
        } else {
            $activityResults['participants'] = [];

            foreach ($participants as $p) {
                $usrId = $p->getId();
                $name = $p->getFullName();
                /** @var Result|null */
                $result = $resultRepo->findOneBy(['activity' => $activity, 'stage' => null, 'criterion' => null, 'usrId' => $usrId]);
                if (!$result) {
                    throw new Exception('result is null');
                }
                $average = $relative
                ? $result->getWeightedRelativeResult() * 100
                : $result->getWeightedAbsoluteResult();

                $activityResults['participants'][$name] = self::toFixed($average, 2);
            }
        }

        return $activityResults;
    }

    private static function getStageResults(Stage $stage, bool $aggregated = false, bool $_relative = null): array
    {
        $em = self::getEntityManager();
        $resultRepo = $em->getRepository(Result::class);

        /** @var int $upperbound */
        if ($_relative !== null) {
            $relative = $_relative;
            $upperbound = 100;
        } else {
            $scale = self::getScale($stage);
            $relative = !$scale->sameScale;
            $upperbound = $scale->upperbound;
        }

        $participants = $stage->getGradableUsers();
        if (!($participants instanceof Collection)) {
            throw new Exception('participants not a Collection');
        }
        /** @var User[] */
        $participantsValues = $participants->getValues();

        /** @var Result|null */
        $stageAvgResult = $resultRepo->findOneBy(['stage' => $stage, 'criterion' => null, 'usrId' => null]);
        if (!$stageAvgResult) {
            throw new Exception('stageAvgResult is null');
        }
        $stageAverage = $relative
        ? $stageAvgResult->getWeightedRelativeResult() * 100
        : $stageAvgResult->getWeightedAbsoluteResult();

        // build stage results data structure
        $stageResults = [
            'average' => self::toFixed($stageAverage, 2),
            'max' => $upperbound,
        ];

        if ($aggregated) {
            $stageResults['participants'] = array_map(function (User $u): string {
                return $u->getFullName();
            }, $participantsValues);
            $stageResults['criteria'] = [];

            $criteria = $stage->getCriteria();
            if (!($criteria instanceof Collection)) {
                throw new Exception('criteria not a Collection');
            }
            /** @var Criterion[] */
            $criteriaValues = $criteria->getValues();

            foreach ($criteriaValues as $criterion) {
                $criterionName = $criterion->getCName()->getName();

                $stageResults['criteria'][$criterionName] = self::getCriterionResults($criterion, false, $relative);
            }
        } else {
            foreach ($participantsValues as $p) {
                $usrId = $p->getId();
                $name = $p->getFullName();
                /** @var Result|null */
                $result = $resultRepo->findOneBy(['stage' => $stage, 'criterion' => null, 'usrId' => $usrId]);
                if (!$result) {
                    throw new Exception('result is null');
                }
                $average = $relative
                ? $result->getWeightedRelativeResult() * 100
                : $result->getWeightedAbsoluteResult();

                $stageResults['participants'][$name] = self::toFixed($average, 2);
            }
        }

        return $stageResults;
    }

    private static function getCriterionResults(Criterion $criterion, bool $graders = true, bool $relative = false): array
    {
        $em = self::getEntityManager();
        $resultRepo = $em->getRepository(Result::class);

        $stage = $criterion->getStage();
        $grades = $criterion->getGrades();
        if (!($grades instanceof Collection)) {
            throw new Exception('grades not a Collection');
        }

        $showGraders = $graders;

        /** @var Result|null */
        $result = $resultRepo->findOneBy(['criterion' => $criterion, 'usrId' => null]);
        if (!$result) {
            throw new Exception('result is null');
        }
        $criterionAvg = $relative
        ? $result->getWeightedRelativeResult() * 100
        : $result->getWeightedAbsoluteResult();

        // get users that can be graded (e.g. are not third-party)
        $gradableUsers = $stage->getGradableUsers();

        // filter criterion grades, discard third-party users
        /** @var Grade[] */
        $filteredGrades = $grades->filter(function (Grade $g) use ($gradableUsers): bool {
            $graded = $g->getGradedUser();
            return $gradableUsers->contains($graded);
        })->getValues();

        // build individual results data structure
        /**
         * @var array gradee name => empty array to contain (grader name => grade) pairs
         */
        $criterionResults = [
            'id' => $criterion->getId(),
            'average' => self::toFixed($criterionAvg, 2),
            'participants' => [],
        ];

        if (!$relative) {
            $criterionResults['max'] = $criterion->getUpperbound();
        }

        /** @var User[] */
        $gradableUsersValues = $gradableUsers->getValues();

        foreach ($gradableUsersValues as $user) {
            /** @var Result|null */
            $result = $resultRepo->findOneBy(['criterion' => $criterion, 'usrId' => $user->getId()]);
            if (!$result) {
                throw new Exception('result not found');
            }

            $userName = $user->getFullName();
            $average = $relative
            ? $result->getWeightedRelativeResult() * 100
            : $result->getWeightedAbsoluteResult();

            $roundedAvg = self::toFixed($average, 2);
            $criterionResults['participants'][$userName] = $showGraders
            ? ['average' => $roundedAvg]
            : $roundedAvg;
        }

        if ($showGraders) {
            foreach ($filteredGrades as $grade) {
                $gradee = $grade->getGradedUser();
                $grader = $grade->getParticipant()->getUser();
                $gradeeUserName = $gradee->getFullName();
                $graderUserName = $grader->getFullName();

                $value = (string) $grade->getValue();

                $criterionResults['participants'][$gradeeUserName]['graders'][$graderUserName] = $value;
            }
        }

        return $criterionResults;
    }

    private static function getScale(DbObject $e): Scale
    {
        /** @var bool */
        $sameScale = true;

        // Activity
        if ($e instanceof Activity) {
            $stages = $e->getStages();
            if (!($stages instanceof Collection)) {
                throw new Exception('stages not a Collection');
            }

            /** @var Stage $stageLast */
            $stageLast = $stages->last();

            $i = $stages->count() - 1;
            $firstStageGetScale = self::getScale($stageLast);
            $someUpperbound = $firstStageGetScale->upperbound;

            while ($sameScale and $i) {
                /** @var Stage */
                $stage = $stages->get(--$i);
                $stageScale = self::getScale($stage);
                $sameScale = $stageScale->sameScale;
            }
        }
        // Stage
        elseif ($e instanceof Stage) {
            $criteria = $e->getCriteria();
            if (!($criteria instanceof Collection)) {
                throw new Exception('criteria not a Collection');
            }

            /** @var Criterion */
            $criterionLast = $criteria->last();

            $i = $criteria->count() - 1;
            $someUpperbound = (int) $criterionLast->getUpperbound();
            while ($sameScale and $i) {
                /** @var Criterion */
                $criterion = $criteria->get(--$i);
                $sameScale = $someUpperbound == (int) $criterion->getUpperbound();
            }
        }
        // anything else
        else {
            throw new Exception('invalid argument');
        }

        return new Scale(
            $sameScale ? $someUpperbound : 100,
            $sameScale,
        );
    }

    private static function getActivitiesAccessAndResultsView(): array
    {
        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            throw new Exception('unauthorized');
        }

        $organization = $currentUser->getOrganization();
        $userRole = $currentUser->getRole();
        $options = $organization->getOptions();
        if (!($options instanceof Collection)) {
            throw new Exception('options not a Collection');
        }

        $optionResultsView = $options->filter(function (OrganizationUserOption $option) use ($userRole): bool {
            return $option->getOName()->getName() == 'activitiesAccessAndResultsView' && $option->isEnabled() && $option->getRole() == $userRole;
        })->first();

        return [
            'access' => (int) $optionResultsView->getOptionIValue(),
            'scope' => (int) $optionResultsView->isOptionTrue(),
            'detail' => (int) $optionResultsView->getOptionFValue(),
            'participationCondition' => $optionResultsView->getOptionSValue(),
        ];
    }

    public function getActivityPerfResultsPerStage(int $actId)
    {
        $currentUser = self::getAuthorizedUser();
        $activity = self::$activityRepo->find($actId);

        if (!($activity instanceof Activity)) {
            throw new NotFoundHttpException;
        }

        if (!$currentUser or !$activity->userCanSeeResults($currentUser)) {
            throw new Exception;
        }

        $globalAvgColorCode = '#3366cc';
        $elementAvgColorCode = '#dc3912';

        $scale = self::getScale($activity);
        $stages = $activity->getStages();

        /** @var ResultProject|null $activityGlobalResult */
        $activityGlobalResult = self::$resultProjectRepo->findOneBy(['activity' => $activity, 'stage' => null, 'criterion' => null]);
        $activityAvg = $activityGlobalResult->getWeightedRelativeResult() * 100;

        $labels = [];
        $stageAvgs = [];
        foreach ($stages as $stage) {
            /** @var ResultProject|null $result */
            $result = self::$resultProjectRepo->findOneBy(['stage' => $stage, 'criterion' => null]);
            if (!$result) {
                continue;
            }

            $labels[] = $stage->getName();
            $stageAvgs[] = $result->getWeightedRelativeResult() * 100;
        }

        $datasets = [
            [
                'type' => 'line',
                'backgroundColor' => $globalAvgColorCode,
                'borderColor' => $globalAvgColorCode,
                'fill' => false,
                'label' => "Moyenne de l'activité ($activityAvg %)",
                'pointHoverRadius' => 0,
                'pointRadius' => 0,
                'data' => array_fill(0, count($labels), $activityAvg),
            ],
            [
                'type' => 'bar',
                'backgroundColor' => $elementAvgColorCode,
                'borderColor' => $elementAvgColorCode,
                'label' => 'Moyenne de phase',
                'data' => $stageAvgs,
            ],
        ];

        return new JsonResponse([
            'data' => ['datasets' => $datasets, 'labels' => $labels],
            'max' => $scale->upperbound,
            'isPublished' => $activity->getStatus() == 3,
            'isPublishable' => self::userCanPublish($activity, $currentUser),
        ]);
    }

    /**
     * @param int $crtId
     * @return JsonResponse
     * @throws Exception
     * @Route("/perfresults/{actId}/{stgId}/{crtId}/{aggregated}/chartjs/")
     */
    public function getCriterionPerfResultsChart(int $crtId)
    {
        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            throw new Exception;
        }

        $criterion = self::$criterionRepo->find($crtId);
        if (!($criterion instanceof Criterion)) {
            throw new NotFoundHttpException;
        }

        $stage = $criterion->getStage();

        if (!$stage->getActivity()->userCanSeeResults($currentUser)) {
            throw new Exception;
        }

        $stageName = $stage->getName();
        $max = $criterion->getUpperbound();
        $colorsGenerator = self::arrayRepeatGenerator(self::colors);

        /** @var Grade[] */
        $criterionGrades = $criterion->getGrades()->filter(
            fn(Grade $g) => !($g->getGradedUsrId() or $g->getGradedTeaId())
        )->getValues();
        $grades = [];
        $feedback = [];
        $gradesSum = 0;

        $stageMin = 0;
        $stageMax = 0;

        foreach ($criterionGrades as $g) {
            $grader = $g->getParticipant()->getDirectUser();
            $color = self::getNext($colorsGenerator);
            $gradeValue = $g->getValue();
            $min = $criterion->getLowerbound();
            $max = $criterion->getUpperbound();

            $stageMin = min($min, $stageMin);
            $stageMax = max($max, $stageMax);

            $grades[] = [
                'type' => 'bar',
                'backgroundColor' => $color,
                'borderColor' => $color,
                'label' => $grader->getFullName(),
                'data' => [$gradeValue],
            ];
            $feedback[] = [
                'from' => $grader->getFullName(),
                'criterion' => $criterion->getCName()->getName(),
                'grade' => "$gradeValue/$max",
                'comment' => $g->getComment(),
            ];

            $gradesSum += $gradeValue;
        }

        $criterionAvg = round($gradesSum / count($grades), 1);

        $datasets = [
            [
                'type' => 'line',
                'backgroundColor' => self::globalAvgColorCode,
                'borderColor' => self::globalAvgColorCode,
                'fill' => false,
                'label' => "Moyenne du critère ($criterionAvg/$max)",
                'pointHoverRadius' => 10,
                'pointRadius' => 10,
                'data' => [$criterionAvg],
            ],
            ...$grades,
        ];

        return new JsonResponse([
            'data' => [
                'datasets' => $datasets,
                'labels' => ["$stageName (phase en tant que telle)"],
            ],
            'feedback' => $feedback,
            'min' => $stageMin,
            'max' => $stageMax,
            'isPublished' => $stage->getStatus() == 3,
            'isPublishable' => self::userCanPublish($stage, $currentUser),
        ]);
    }

    public function getStagePerfResultsChart(int $stgId, bool $aggregate = true)
    {
        $currentUser = self::getAuthorizedUser();
        if (!$currentUser) {
            throw new Exception;
        }

        $stage = self::$stageRepo->find($stgId);

        if (!($stage instanceof Stage)) {
            throw new NotFoundHttpException;
        }
        if (!$stage->getActivity()->userCanSeeResults($currentUser)) {
            throw new Exception;
        }

        /** @var ResultProject|null $stageAvgResult */
        $stageAvgResult = self::$resultProjectRepo->findOneBy(['stage' => $stage, 'criterion' => null]);
        if (!$stageAvgResult) {
            return new Response(null, Response::HTTP_NO_CONTENT);
        }

        $scale = self::getScale($stage);
        $sameScale = $scale->sameScale;
        $upperbound = $scale->upperbound;
        // absolute results are appended with /upperbound, relative results with %
        $rpart = $sameScale ? "/$upperbound" : ' %';
        // call -relative or -absolute functions accordingly
        $weightedAvg_methodName = $sameScale ? 'getWeightedAbsoluteResult' : 'getWeightedRelativeResult';
        $multiplicator = $sameScale ? 1 : 100;

        $criteria = $stage->getCriteria();
        $stageAvg = $stageAvgResult->$weightedAvg_methodName() * $multiplicator;
        $stageName = $stage->getName();
        $stageAvgLabel = "$stageName (phase en tant que telle)";
        $stageAvgDatasetLabel = "Moyenne de la phase ($stageAvg$rpart)";

        $labels = [];
        $feedback = [];
        $criterionResults = [];
        $stageMax = 0;
        $stageMin = 0;

        foreach ($criteria as $criterion) {
            $cName = $criterion->getCName();
            $labels[] = $cName->getName();
            $min = $criterion->getLowerbound();
            $max = $criterion->getUpperbound();

            $stageMin = min($min, $stageMin);
            $stageMax = max($max, $stageMax);

            /** @var ResultProject|null $criterionAvg */
            $criterionAvg = self::$resultProjectRepo->findOneBy(['criterion' => $criterion]);
            $criterionResults[] = $criterionAvg ? $criterionAvg->$weightedAvg_methodName() * $multiplicator : 0;

            /** @var Grade[] */
            $projectGrades = $criterion->getGrades()->filter(
                fn(Grade $g) => !($g->getGradedUsrId() or $g->getGradedTeaId())
            )->getValues();

            foreach ($projectGrades as $g) {
                $grader = $g->getParticipant()->getDirectUser();
                $gradeValue = $g->getValue();

                if ($g->getGradedUsrId() or $g->getGradedTeaId()) {
                    continue;
                }

                $feedback[] = [
                    'id' => $g->getId(),
                    'from' => $grader->getFullName(),
                    'criterion' => $criterion->getCName()->getName(),
                    'grade' => "$gradeValue/$max",
                    'comment' => $g->getComment(),
                ];
            }
        }

        if ($aggregate) {
            $datasets = [
                [
                    'type' => 'line',
                    'backgroundColor' => self::globalAvgColorCode,
                    'borderColor' => self::globalAvgColorCode,
                    'fill' => false,
                    'label' => $stageAvgDatasetLabel,
                    'pointHoverRadius' => 0,
                    'pointRadius' => 0,
                    'data' => array_fill(0, count($labels), $stageAvg),
                ],
                [
                    'type' => 'bar',
                    'backgroundColor' => self::elementAvgColorCode,
                    'borderColor' => self::elementAvgColorCode,
                    'fill' => false,
                    'label' => 'Moyenne de critère',
                    'pointHoverRadius' => 10,
                    'pointRadius' => 5,
                    'data' => $criterionResults,
                ],
            ];
        } else {
            $labels = [$stageAvgLabel];

            $datasets = [
                [
                    'type' => 'bar',
                    'backgroundColor' => self::globalAvgColorCode,
                    'borderColor' => self::globalAvgColorCode,
                    'label' => $stageAvgDatasetLabel,
                    'data' => [$stageAvg],
                ],
            ];
        }

        return new JsonResponse([
            'data' => ['datasets' => $datasets, 'labels' => $labels],
            'min' => $sameScale ? $stageMin : 0,
            'max' => $sameScale ? $stageMax : $upperbound,
            'feedback' => $feedback,
            'isPublished' => $stage->getStatus() == 3,
            'isPublishable' => self::userCanPublish($stage, $currentUser),
        ]);
    }

    public function getActivityDistResultsChart(int $actId)
    {
        $activity = self::$activityRepo->find($actId);
        if (!($activity instanceof Activity)) {
            throw new NotFoundHttpException;
        }

        /** @var ResultProject|null $globalActivityResults */
        $globalActivityResults = self::$resultProjectRepo->findOneBy([
            'activity' => $activity, 'stage' => null, 'criterion' => null,
        ]);
        $activityWdr = $globalActivityResults->getWeightedDistanceRatio() * 100;

        $stages = $activity->getStages();
        /** @var ResultProject|null[] $stageResults */
        $stageResults = [];
        $labels = [];

        foreach ($stages as $s) {
            /** @var ResultProject|null $result */
            $result = self::$resultProjectRepo->findOneBy([
                'stage' => $s, 'criterion' => null,
            ]);

            if ($result) {
                $stageResults[] = $result;
            }

            $labels[] = $s->getName();
        }

        $stageAvgs = array_map(
            fn(ResultProject $r) => [
                'x' => $r->getWeightedDistanceRatio() * 100,
                'y' => $r->getStage()->getName(),
            ],
            $stageResults
        );

        $datasets = [
            [
                'type' => 'line',
                'backgroundColor' => self::globalAvgColorCode,
                'borderColor' => self::globalAvgColorCode,
                'fill' => false,
                'label' => "Moyenne de l'activité ($activityWdr %)",
                'pointHoverRadius' => 0,
                'pointRadius' => 0,
                'data' => array_map(fn($l) => ['x' => $activityWdr, 'y' => $l], $labels),
            ],
            [
                'type' => 'horizontalBar',
                'backgroundColor' => self::elementAvgColorCode,
                'borderColor' => self::elementAvgColorCode,
                'label' => 'Moyenne de phase',
                'data' => $stageAvgs,
            ],
        ];

        return new JsonResponse(['datasets' => $datasets, 'labels' => $labels]);
    }

    public function getStageDistResultsChart(int $stgId)
    {
        $user = self::getAuthorizedUser();
        if (!$user) {
            throw new Exception;
        }

        $stage = self::$stageRepo->find($stgId);
        if (!($stage instanceof Stage)) {
            throw new NotFoundHttpException;
        }
        if (!$stage->getActivity()->userCanSeeResults($user)) {
            throw new Exception;
        }

        $labels = [];

        $criteria = $stage->getCriteria();

        /** @var ResultProject|null $stageAvgResult */
        $stageAvgResult = self::$resultProjectRepo->findOneBy(['stage' => $stage, 'criterion' => null]);
        /** @var ResultProject|null $criterionAvgs */
        $results = [];

        foreach ($criteria as $criterion) {
            $cName = $criterion->getCName();
            $labels[] = $cName->getName();

            /** @var ResultProject|null $criterionAvg */
            $criterionAvg = self::$resultProjectRepo->findOneBy(['criterion' => $criterion]);

            $results[] = $criterionAvg->getWeightedStdDev();
        }

        $stageAvg = round($stageAvgResult->getWeightedStdDev(), 1);

        return new JsonResponse([
            'datasets' => [
                [
                    'type' => 'horizontalBar',
                    'backgroundColor' => self::globalAvgColorCode,
                    'borderColor' => self::globalAvgColorCode,
                    'label' => "Écart moyen ($stageAvg %)",
                    'data' => [$stageAvg],
                ],
            ],
            'labels' => ['Phase'],
        ]);
    }
}

class Scale
{
    public $upperbound;
    public $sameScale;

    public function __construct(int $upperbound, bool $sameScale)
    {
        $this->upperbound = $upperbound;
        $this->sameScale = $sameScale;
    }
}
