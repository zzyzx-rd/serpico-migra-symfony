<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StageRepository;
use DateInterval;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=StageRepository::class)
 */
class Stage extends DbObject
{
    public const STAGE_INCOMPLETE = -1;
    public const STAGE_UNSTARTED = 0;
    public const STAGE_ONGOING = 1;
    public const STAGE_COMPLETED = 2;
    public const STAGE_PUBLISHED = 3;

    public const PROGRESS_STOPPED = -5;
    public const PROGRESS_POSTPONED = -4;
    public const PROGRESS_SUSPENDED = -3;
    public const PROGRESS_REOPENED = -2;
    public const PROGRESS_UNSTARTED = -1;
    public const PROGRESS_UPCOMING = 0;
    public const PROGRESS_ONGOING = 1;
    public const PROGRESS_COMPLETED = 2;
    public const PROGRESS_FINALIZED = 3;

    public const VISIBILITY_public = 0;
    public const VISIBILITY_UNLISTED = 1;
    public const VISIBILITY_PUBLIC = 2;

    public const GRADED_STAGE = 0;
    public const GRADED_PARTICIPANTS = 1;
    public const GRADED_STAGE_PARTICIPANTS = 2;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="stg_id", type="integer",nullable=false, length=10)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="stg_complete", type="boolean", nullable=true)
     */
    public bool $complete;

    /**
     * @ORM\Column(name="stg_name", type="string", length=255, nullable=true)
     */
    public string $name;

    /**
     * @ORM\Column(name="stg_mode", type="integer", nullable=true)
     */
    public int $mode;

    /**
     * @ORM\Column(name="stG_visibility", type="integer", nullable=true)
     */
    public int $visibility;

    /**
     * @ORM\Column(name="stg_access_link", type="string", length=255, nullable=true)
     */
    public ?string $accessLink;

    /**
     * @ORM\Column(name="stg_status", type="integer", nullable=true)
     */
    public int $status;

    /**
     * @ORM\Column(name="stg_desc", type="string", length=255, nullable=true)
     */
    public ?string $description;

    /**
     * @ORM\Column(name="stg_progress", type="integer", nullable=true)
     */
    public int $progress;

    /**
     * @ORM\Column(name="stg_weight", type="float", nullable=true)
     */
    public float $weight;

    /**
     * @ORM\Column(name="stg_definite_dates", type="boolean", nullable=true)
     */
    public bool $definiteDates;

    /**
     * @ORM\Column(name="stg_dperiod", type="integer", nullable=true)
     */
    public int $dPeriod;

    /**
     * @ORM\Column(name="stg_dfrequency", type="string", length=255, nullable=true)
     */
    public string $dFrequency;

    /**
     * @ORM\Column(name="stg_dorigin", type="integer", nullable=true)
     */
    public int $dOrigin;

    /**
     * @ORM\Column(name="stg_fperiod",type="integer", nullable=true)
     */
    public int $fPeriod;

    /**
     * @ORM\Column(name="stg_ffrequency", type="string", length=255, nullable=true)
     */
    public string $fFrequency;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public int $fOrigin;

    /**
     * @ORM\Column(name="stg_startdate", type="datetime", nullable=true)
     */
    public DateTime $startdate;

    /**
     * @ORM\Column(name="stg_enddate", type="datetime", nullable=true)
     */
    public DateTime $enddate;

    /**
     * @ORM\Column(name="stg_gstartdate", type="datetime", nullable=true)
     */
    public DateTime $gstartdate;

    /**
     * @ORM\Column(name=" $stg_genddate", type="datetime", nullable=true)
     */
    public DateTime $genddate;

    /**
     * @ORM\Column(name="stg_dealine_nb_days", type="integer", nullable=true)
     */
    public int $deadlineNbDays;

    /**
     * @ORM\Column(name="stg_deadline_mailSent", type="boolean", nullable=true)
     */
    public ?bool $deadlineMailSent;

    /**
     * @ORM\Column(name="stg_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="stg_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="stg_reopened", type="boolean", nullable=true)
     */
    public bool $reopened;

    /**
     * @ORM\Column(name="stg_last_reopened", type="datetime", nullable=true)
     */
    public ?DateTime $lastReopened;

    /**
     * @ORM\Column(name="stg_unstarted_notif", type="boolean", nullable=true)
     */
    public ?bool $unstartedNotif;

    /**
     * @ORM\Column(name="stg_uncompleted_notif", type="boolean", nullable=true)
     */
    public ?bool $uncompletedNotif;

    /**
     * @ORM\Column(name="stg_unfinished_notif", type="boolean", nullable=true)
     */
    public ?bool $unfinishedNotif;

    /**
     * @ORM\Column(name="stg_isFinalized", type="boolean", nullable=true)
     */
    public ?bool $isFinalized;

    /**
     * @Column(name="stg_finalized", type="datetime", nullable=true)
     * @var DateTime
     */
    protected ?DateTime $finalized;

    /**
     * @ORM\Column(name="stg_deleted", type="datetime", nullable=true)
     */
    public ?DateTime $deleted;

    /**
     * @ORM\Column(name="stg_gcompleted", type="datetime", nullable=true)
     */
    public ?DateTime $gcompleted;

    /**
     * @OneToOne(targetEntity="Survey")
     * @JoinColumn(name="survey_sur_id", referencedColumnName="sur_id",nullable=true)
     * @var Survey
     */
    protected ?Survey $survey;

    /**
     * @ManyToOne(targetEntity=Activity::class, inversedBy="stages")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id",nullable=false)
     */
    protected $activity;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="stages")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=true)
     * @var Organization
     */
    protected Organization $organization;

    /**
     * @OneToMany(targetEntity="Criterion", mappedBy="stage", cascade={"persist", "remove"}, orphanRemoval=true)
     */
//     * @OrderBy({"weight" = "DESC"})
    public $criteria;

    /**
     * @OneToMany(targetEntity="Participation", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"team" = "ASC"})
     */
    public $participants;

    /**
     * @OneToMany(targetEntity="Decision", mappedBy="stage",cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $decisions;

    /**
     * @OneToMany(targetEntity="Grade", mappedBy="stage",cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $grades;

    /**
     * @OneToMany(targetEntity="ResultProject", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $projectResults;

    /**
     * @OneToMany(targetEntity="Result", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $results;

    /**
     * @OneToMany(targetEntity="ResultTeam", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $resultTeams;

    /**
     * @OneToMany(targetEntity="Ranking", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $rankings;

    /**
     * @OneToMany(targetEntity="RankingTeam", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $rankingTeams;

    /**
     * @OneToMany(targetEntity="RankingHistory", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $historicalRankings;

    /**
     * @OneToMany(targetEntity="RankingTeamHistory", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $historicalRankingTeams;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="stagesWhereMaster")
     * @JoinColumn(name="usr_id", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $masterUser;

    public ?User $currentUser = null;
    /**
     * Stage constructor.
     * @param ?int$id
     * @param bool $complete
     * @param null $activity
     * @param int $visibility
     * @param null $masterUser
     * @param string $name
     * @param string $accessLink
     * @param int $status
     * @param string $description
     * @param int $progress
     * @param float $weight
     * @param int $deadlineNbDays
     * @param bool $deadlineMailSent
     * @param int $createdBy
     * @param DateTime $finalized
     * @param DateTime $lastReopened
     * @param DateTime $deleted
     * @param DateTime $gcompleted
     */
    public function __construct(
        $id = 0,
        $complete = false,
        $activity = null,
        $visibility = 3,
        $masterUser = null,
        $name = '',
        $accessLink = null,
        $status = 0,
        $description = null,
        $progress = -1,
        $weight = 0.0,
        $deadlineNbDays = 3,
        $deadlineMailSent = null,
        $createdBy = null,
        $finalized = null,
        $lastReopened = null,
        $deleted = null,
        $gcompleted = null)
    {
        parent::__construct($id, $createdBy, new DateTime);
        $this->complete = $complete;
        $this->activity = $activity;
        $this->masterUser = $masterUser;
        $this->name = $name;
        $this->mode = 1;
        $this->visibility = $visibility;
        $this->definiteDates = true;
        $this->dPeriod = 15;
        $this->dFrequency = 'D';
        $this->dOrigin = 0;
        $this->fPeriod = 7;
        $this->fFrequency = 'D';
        $this->fOrigin = 2;
        $this->accessLink = $accessLink;
        $this->status = $status;
        $this->description = $description;
        $this->progress = $progress;
        $this->weight = $weight;
        $this->startdate = new DateTime;
        $this->enddate = new DateTime;
        $this->gstartdate = new DateTime;
        $this->genddate = new DateTime;
        $this->deadlineNbDays = $deadlineNbDays;
        $this->deadlineMailSent = $deadlineMailSent;
        $this->isFinalized = false;
        $this->finalized = $finalized;
        $this->gcompleted = $gcompleted;
        $this->deleted = $deleted;
        $this->reopened = false;
        $this->lastReopened = $lastReopened;
        $this->criteria = new ArrayCollection;
        $this->participants = new ArrayCollection;
        $this->decisions = new ArrayCollection;
        $this->grades = new ArrayCollection;
        $this->results = new ArrayCollection;
        $this->projectResults = new ArrayCollection;
        $this->rankings = new ArrayCollection;
        $this->historicalRankings = new ArrayCollection;
        $this->resultTeams = new ArrayCollection;
        $this->rankingTeams = new ArrayCollection;
        $this->historicalRankingTeams = new ArrayCollection;
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMode(): ?int
    {
        return $this->mode;
    }

    public function setMode(int $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getVisibility(): ?int
    {
        return $this->visibility;
    }

    public function setVisibility(int $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getAccessLink(): ?string
    {
        return $this->accessLink;
    }

    public function setAccessLink(string $accessLink): self
    {
        $this->accessLink = $accessLink;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProgress(): ?int
    {
        return $this->progress;
    }

    public function setProgress(int $progress): self
    {
        $this->progress = $progress;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }


    public function getStartdate(): ?DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getEnddate(): ?DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getGstartdate(): ?DateTimeInterface
    {
        return $this->gstartdate;
    }

    public function setGstartdate(DateTimeInterface $gstartdate): self
    {
        $this->gstartdate = $gstartdate;

        return $this;
    }

    public function getGenddate(): ?DateTimeInterface
    {
        return $this->genddate;
    }

    public function setGenddate(DateTimeInterface $genddate): self
    {
        $this->genddate = $genddate;

        return $this;
    }

    public function getInserted(): ?DateTimeInterface
    {
        return $this->inserted;
    }

    public function setInserted(?DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

        return $this;
    }

    public function isReopened(): ?bool
    {
        return $this->reopened;
    }

    public function setReopened(bool $reopened): self
    {
        $this->reopened = $reopened;

        return $this;
    }

    public function getIsFinalized(): ?bool
    {
        return $this->isFinalized;
    }

    public function setIsFinalized(bool $isFinalized): self
    {
        $this->isFinalized = $isFinalized;

        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(DateTimeInterface $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getGcompleted(): ?DateTimeInterface
    {
        return $this->gcompleted;
    }

    public function setGcompleted(DateTimeInterface $gcompleted): self
    {
        $this->gcompleted = $gcompleted;

        return $this;
    }

    /**
     * @return Survey
     */
    public function getSurvey(): ?Survey
    {
        return $this->survey;
    }

    /**
     * @param Survey $survey
     */
    public function setSurvey(Survey $survey)
    {
        $this->survey = $survey; return $this;
    }

    /**
     * @return mixed
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param mixed $activity
     */
    public function setActivity($activity)
    {
        $this->activity = $activity; return $this;
    }

    /**
     * @return Organization
     */
    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    /**
     * @param Organization $organization
     */
    public function setOrganization(Organization $organization)
    {
        $this->organization = $organization; return $this;
    }

    /**
     * @return Collection
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param Collection $criteria
     */
    public function setCriteria(Collection $criteria)
    {
        $this->criteria = $criteria; return $this;
    }

    /**
     * @return mixed
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param mixed $participants
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants; return $this;
    }

    /**
     * @return mixed
     */
    public function getDecisions()
    {
        return $this->decisions;
    }

    /**
     * @param mixed $decisions
     */
    public function setDecisions($decisions)
    {
        $this->decisions = $decisions; return $this;
    }

    /**
     * @return mixed
     */
    public function getGrades()
    {
        return $this->grades;
    }

    /**
     * @param mixed $grades
     */
    public function setGrades($grades)
    {
        $this->grades = $grades; return $this;
    }

    /**
     * @return mixed
     */
    public function getProjectResults()
    {
        return $this->projectResults;
    }

    /**
     * @param mixed $projectResults
     */
    public function setProjectResults($projectResults)
    {
        $this->projectResults = $projectResults; return $this;
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param mixed $results
     */
    public function setResults($results)
    {
        $this->results = $results; return $this;
    }

    /**
     * @return mixed
     */
    public function getResultTeams()
    {
        return $this->resultTeams;
    }

    /**
     * @param mixed $resultTeams
     */
    public function setResultTeams($resultTeams)
    {
        $this->resultTeams = $resultTeams; return $this;
    }

    /**
     * @return mixed
     */
    public function getRankings()
    {
        return $this->rankings;
    }

    /**
     * @param mixed $rankings
     */
    public function setRankings($rankings)
    {
        $this->rankings = $rankings; return $this;
    }

    /**
     * @return mixed
     */
    public function getRankingTeams()
    {
        return $this->rankingTeams;
    }

    /**
     * @param mixed $rankingTeams
     */
    public function setRankingTeams($rankingTeams)
    {
        $this->rankingTeams = $rankingTeams; return $this;
    }

    /**
     * @return mixed
     */
    public function getHistoricalRankings()
    {
        return $this->historicalRankings;
    }

    /**
     * @param mixed $historicalRankings
     */
    public function setHistoricalRankings($historicalRankings)
    {
        $this->historicalRankings = $historicalRankings; return $this;
    }

    /**
     * @return mixed
     */
    public function getHistoricalRankingTeams()
    {
        return $this->historicalRankingTeams;
    }

    /**
     * @param mixed $historicalRankingTeams
     */
    public function setHistoricalRankingTeams($historicalRankingTeams)
    {
        $this->historicalRankingTeams = $historicalRankingTeams; return $this;
    }

    /**
     * @return bool
     */
    public function isDefiniteDates(): bool
    {
        return $this->definiteDates;
    }

    /**
     * @param bool $definiteDates
     * @return Stage
     */
    public function setDefiniteDates(bool $definiteDates): Stage
    {
        $this->definiteDates = $definiteDates;
        return $this;
    }

    /**
     * @return int
     */
    public function getDPeriod(): int
    {
        return $this->dPeriod;
    }

    /**
     * @param int $dPeriod
     * @return Stage
     */
    public function setDPeriod(int $dPeriod): Stage
    {
        $this->dPeriod = $dPeriod;
        return $this;
    }

    /**
     * @return string
     */
    public function getDFrequency(): string
    {
        return $this->dFrequency;
    }

    /**
     * @param string $dFrequency
     * @return Stage
     */
    public function setDFrequency(string $dFrequency): Stage
    {
        $this->dFrequency = $dFrequency;
        return $this;
    }

    /**
     * @return int
     */
    public function getDOrigin(): int
    {
        return $this->dOrigin;
    }

    /**
     * @param int $dOrigin
     * @return Stage
     */
    public function setDOrigin(int $dOrigin): Stage
    {
        $this->dOrigin = $dOrigin;
        return $this;
    }

    /**
     * @return int
     */
    public function getFPeriod(): int
    {
        return $this->fPeriod;
    }

    /**
     * @param int $fPeriod
     * @return Stage
     */
    public function setFPeriod(int $fPeriod): Stage
    {
        $this->fPeriod = $fPeriod;
        return $this;
    }

    /**
     * @return string
     */
    public function getFFrequency(): string
    {
        return $this->fFrequency;
    }

    /**
     * @param string $fFrequency
     * @return Stage
     */
    public function setFFrequency(string $fFrequency): Stage
    {
        $this->fFrequency = $fFrequency;
        return $this;
    }

    /**
     * @return int
     */
    public function getFOrigin(): int
    {
        return $this->fOrigin;
    }

    /**
     * @param int $fOrigin
     * @return Stage
     */
    public function setFOrigin(int $fOrigin): Stage
    {
        $this->fOrigin = $fOrigin;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template)
    {
        $this->template = $template; return $this;
    }

    public function getMasterUser(): ?User
    {
        return $this->masterUser;
    }

    public function setMasterUser(?User $masterUser): self
    {
        $this->masterUser = $masterUser;

        return $this;
    }
    public function getActiveWeight(){
        $sumWeightCompletedStages = 0;
        /** @var ArrayCollection|Stage[] */
        $completedStages = $this->activity->getOCompletedStages();
        foreach($completedStages as $completedStage){
            $sumWeightCompletedStages += $completedStage->weight;
        }
        return ($sumWeightCompletedStages === 0) ? $this->weight : $this->weight / $sumWeightCompletedStages;
    }

    public function setActiveWeight($activeWeight): void
    {
        $sumWeightCompletedStages = 0;
        /** @var ArrayCollection|Stage[] */
        $completedStages = $this->activity->getOCompletedStages();
        foreach($completedStages as $completedStage){
            $sumWeightCompletedStages += $completedStage->weight;
        }
        $this->weight = round((1 - $sumWeightCompletedStages) * $activeWeight, 3);
    }

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getIndependantUniqueParticipations()
    {
        $eligibleParticipants = null;
        $uniqueParticipants = new ArrayCollection;
        $teams = [];


        $eligibleParticipants = count($this->criteria) === 0 ? $this->participants : $this->criteria->first()->getParticipants();


        foreach ($eligibleParticipants as $eligibleParticipant) {
            $team = $eligibleParticipant->getTeam();
            if ($team === null) {
                $uniqueParticipants->add($eligibleParticipant);
            } else {
                if (!in_array($team, $teams)) {
                    $uniqueParticipants->add($eligibleParticipant);
                    $teams[] = $team;
                }
            }
        }
        return $uniqueParticipants;
    }
    /**
     * @return ArrayCollection|Participation[]
     */
    public function getUniqueTeamParticipations()
    {
        $myParticipations = $this->getSelfParticipations();
        $myTeam = $myParticipations->count() === 0 ? null : $myParticipations->first()->getTeam();
        return $this->getUniqueParticipations()->filter(static function(Participation $p) use ($myTeam){
            return $p->getTeam() !== null && $p->getTeam() != $myTeam;
        });
    }
    /**
     * @return ArrayCollection|Participation[]
     */
    public function getUserGradableParticipations()
    {
        // We get all non-third party user participations, except those of people who are part of a team we don't belong to
        if ($this->mode == STAGE::GRADED_STAGE) {
            return null;
        } else {

            $userGradableParticipations = new ArrayCollection;

            $unorderedGradableParticipations = $this->getUniqueIndivParticipations()->filter(function(Participation $p){
                return $p->getType() != Participation::PARTICIPATION_THIRD_PARTY;
            });

            foreach($unorderedGradableParticipations as $unorderedGradableParticipation){
                $userGradableParticipations->add($unorderedGradableParticipation);
            }

            return $userGradableParticipations;
        }
    }
    public function addTeamGradableParticipation(Participation $participant): Stage
    {
        $this->participants->add($participant);
        return $this;
    }

    public function removeTeamGradableParticipation(Participation $participant): Stage
    {
        $this->participants->removeElement($participant);
        return $this;
    }

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getUniqueGradableParticipations()
    {
        return $this->getUniqueParticipations()->matching(Criteria::create()->where(Criteria::expr()->neq("type", 0)));
    }

    public function addUniqueGradableParticipation(Participation $participant): Stage
    {
        if ($this->participants->exists(function (Participation $u) use ($participant) {
            return $u->getUser()->getId() === $participant->getUser()->getId();
        })) {
            return $this;
        }

        foreach ($this->criteria as $criterion) {
            $criterion->addParticipant($participant);
            $participant->setCriterion($criterion)->setStage($this);
        }
        return $this;
    }

    public function removeUniqueGradableParticipation(Participation $participant): Stage
    {
        foreach ($this->criteria as $criterion) {
            $criterion->participants->removeElement($participant);
        }
        return $this;
    }

    public function getUniqueGradingParticipants()
    {
        return count($this->getUniqueParticipations()->matching(
            Criteria::create()->where(Criteria::expr()->neq("type", -1))
        ));
    }
    public function addGrade(Grade $grade): Stage
    {
        $this->grades->add($grade);
        return $this;
    }

    public function removeGrade(Grade $grade): Stage
    {
        $this->grades->removeElement($grade);
        return $this;
    }

    public function addCriterion(Criterion $criterion): Stage
    {
        //The below line is to prevent adding a criterion already submitted (because of activeStages/stages).
        // However as stage criteria are built in advance for recurring activities, we also need to take into account this exception

        //if(!$criterion->getStage() || $criterion->getStage()->getActivity()->getRecurring()){
        $this->criteria->add($criterion);
        $criterion->setStage($this);
        return $this;
        //}
    }
    public function userCanSeeOutput(User $u): bool
    {
        return ($this->status == self::STAGE_ONGOING) && $this->userHasGivenOutput($u);
    }

    public function userHasGivenOutput(User $u): ?bool
    {
        if($this->participants->isEmpty()){
            return false;
        }

        $userParticipations = $this->participants->filter(static function(Participation $p) use ($u){return $p->getDirectUser() === $u;});
        if($userParticipations->count() > 0){
            return $userParticipations->forAll(static function(int $i, Participation $p) {
                return $p->getStatus() >= 3;
            });
        }

        return false;
    }

    public function addParticipant(Participation $participant): Stage
    {

        $this->participants->add($participant);
        $participant->setStage($this);
        return $this;
    }

    public function removeParticipant(Participation $participant): Stage
    {
        $this->participants->removeElement($participant);
        return $this;
    }

    public function addUniqueParticipation(Participation $participant): Stage
    {
        if (count($this->criteria) !== 0) {
            foreach ($this->criteria as $criterion) {
                $criterion->addParticipant($participant);
                $participant->setCriterion($criterion)->setStage($this)->setActivity($this->getActivity());
            }
        } else {
            $this->addParticipant($participant);
        }
        return $this;
    }

    public function removeUniqueParticipation(Participation $participant): Stage
    {
        $participantUsrId = $participant->getUsrId();
        $participantTeam = $participant->getTeam();
        foreach ($this->participants as $theParticipant) {
            if ($participantUsrId == $theParticipant->getUsrId() || $participantTeam == $theParticipant->getTeam()) {
                $this->removeParticipant($theParticipant);
            }
        }
        return $this;
    }

    public function addIndependantUniqueParticipation(Participation $participant): Stage
    {
        return $this->addUniqueParticipation($participant);
    }

    public function removeIndependantUniqueParticipation(Participation $participant): Stage
    {
        return $this->removeUniqueParticipation($participant);
    }

    public function addUniqueIntParticipation(Participation $participant): Stage
    {
        $this->addUniqueParticipation($participant);
        return $this;
    }

    public function removeUniqueIntParticipation(Participation $participant): Stage
    {
        $this->removeUniqueParticipation($participant);
        return $this;
    }

    public function addIndependantUniqueIntParticipation(Participation $participant): Stage
    {
        $this->addIndependantUniqueParticipation($participant);
        return $this;
    }

    public function removeIndependantUniqueIntParticipation(Participation $participant): Stage
    {
        $this->removeIndependantUniqueParticipation($participant);
        return $this;
    }

    public function addUniqueExtParticipation(Participation $participant): Stage
    {
        $this->addUniqueParticipation($participant);
        return $this;
    }

    public function removeUniqueExtParticipation(Participation $participant): Stage
    {
        $this->removeUniqueParticipation($participant);
        return $this;
    }

    public function addIndependantUniqueExtParticipation(Participation $participant): Stage
    {
        $this->addIndependantUniqueExtParticipation($participant);
        return $this;
    }

    public function removeIndependantUniqueExtParticipation(Participation $participant): Stage
    {
        $this->removeIndependantUniqueParticipation($participant);
        return $this;
    }

    public function addUniqueTeamParticipation(Participation $participant): Stage
    {
        $this->addUniqueParticipation($participant);
        return $this;
    }

    public function removeUniqueTeamParticipation(Participation $participant): Stage
    {
        $this->removeUniqueParticipation($participant);
        return $this;
    }

    public function addIndependantUniqueTeamParticipation(Participation $participant): Stage
    {
        $this->addUniqueTeamParticipation($participant);
        return $this;
    }

    public function removeIndependantUniqueTeamParticipation(Participation $participant): Stage
    {
        $this->removeUniqueTeamParticipation($participant);
        return $this;
    }

    public function addDecision(Decision $decision): Stage
    {

        $this->decisions->add($decision);
        $decision->setStage($this);
        return $this;
    }

    public function removeDecision(Decision $decision): Stage
    {
        $this->decisions->removeElement($decision);
        return $this;
    }

    public function addResult(Result $result): Stage
    {
        $this->results->add($result);
        $result->setStage($this);
        return $this;
    }

    public function removeResult(Result $result): Stage
    {
        $this->results->removeElement($result);
        return $this;
    }

    public function addRankings(Ranking $ranking): Stage
    {
        $this->rankings->add($ranking);
        $ranking->setActivity($this);
        return $this;
    }

    public function removeRanking(Ranking $ranking): Stage
    {
        $this->rankings->removeElement($ranking);
        return $this;
    }

    public function addHistoricalRankings(RankingHistory $historicalRanking): Stage
    {
        $this->historicalRankings->add($historicalRanking);
        $historicalRanking->setActivity($this);
        return $this;
    }

    public function removeHistoricalRanking(RankingHistory $ranking): Stage
    {
        $this->rankings->removeElement($ranking);
        return $this;
    }

    public function addResultTeam(ResultTeam $resultTeam): Stage
    {
        $this->resultTeams->add($resultTeam);
        $resultTeam->setStage($this);
        return $this;
    }

    public function removeResultTeam(ResultTeam $resultTeam): Stage
    {
        $this->resultTeams->removeElement($resultTeam);
        return $this;
    }

    public function addRankingTeam(RankingTeam $rankingTeam): Stage
    {
        $this->rankingTeams->add($rankingTeam);
        $rankingTeam->setActivity($this);
        return $this;
    }

    public function removeRankingTeam(RankingTeam $rankingTeam): Stage
    {
        $this->rankingTeams->removeElement($rankingTeam);
        return $this;
    }

    public function addHistoricalRankingTeam(RankingTeamHistory $historicalRankingTeam): Stage
    {
        $this->historicalRankingTeams->add($historicalRankingTeam);
        $historicalRankingTeam->setActivity($this);
        return $this;
    }

    public function removeHistoricalRankingTeam(RankingTeamHistory $historicalRankingTeam): Stage
    {
        $this->historicalRankingTeams->removeElement($historicalRankingTeam);
        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }

    // Note : to get stage progress, we only consider participants who have validated their grades
    // (saving grades is not sufficient)
    public function getGradingProgress()
    {
        $k = 0;
        $l = 0;
        if (count($this->participants) > 0) {
            foreach ($this->participants as $participant) {
                if ($participant->getStatus() >= 3) {
                    $k++;
                }
                if ($participant->getType() !== -1) {
                    $l++;
                }
            }
            if ($l > 0) {
                return $k / $l;
            }
        }

        return 0;
    }
    // Defines which users can grade
    /**
     * @return Collection
     */
    public function getGraderUsers()
    {
        return $this->getUniqueGraderParticipations()->map(
            static function (Participation $p) {
                return $p->getDirectUser();
            }
        );
    }

    // Defines nb of evaluating criteria
    /**
     * @return int
     */
    public function getNbEvaluatingCriteria(): int
    {
        return count($this->getCriteria()->matching(Criteria::create()->where(Criteria::expr()->eq("type", 1))));
    }
    public function getSelfGrades()
    {
        return $this->getAllSelfGrades()->matching(Criteria::create()->where(Criteria::expr()->in("participant", $this->getSelfParticipations()->getValues())));
    }

    public function addSelfGrade(Grade $grade): Stage
    {
        $this->grades->add($grade);
        $grade->setStage($this);
        return $this;
    }

    public function removeSelfGrade(Grade $grade): Stage
    {
        $this->grades->removeElement($grade);
        return $this;
    }
    public function isFeedbackTypeSettled(): bool
    {
        return $this->criteria->count() || $this->getSurvey();
    }

    public function userCanGiveOutput(User $u)
    {
        if($this->status == $this::STAGE_ONGOING){
            return $this->getUniqueGraderParticipations()->exists(function (int $i,Participation $p) use ($u) {
                return $p->getDirectUser() === $u && $p->getType() !== -1 && $p->getStatus() < 3;
            }
            );
        }

        return false;

    }

    public function getOwnerUserId(){
        $uniqueIntParticipations = $this->getUniqueIntParticipations();
        if($uniqueIntParticipations){
            foreach($uniqueIntParticipations as $uniqueIntParticipation){
                if($uniqueIntParticipation->isLeader()){
                    return $uniqueIntParticipation->getUsrId();
                }
            }
            return null;
        }
        return null;
    }


    public function hasMinimumOutputConfig(): bool
    {
        return $this->getSurvey() !== null || $this->getCriteria()->count() > 0;
    }

    public function hasFeedbackExpired(): bool
    {
        $yesterdayDate = new DateTime;
        $yesterdayDate->sub(new DateInterval('P1D'));
        return $this->genddate <= $yesterdayDate && $this->status < STAGE::STAGE_COMPLETED;
    }

    public function isComplete(): bool
    {
        $yesterdayDate = new DateTime;
        $yesterdayDate->sub(new DateInterval('P1D'));
        return $this->hasMinimumOutputConfig() && $this->hasMinimumParticipationConfig() && !$this->hasFeedbackExpired();
    }

    public function hasCompletedOutput(): int
    {
        return (int) $this->participants->exists(static function(int $i, Participation $p){
            return $p->getStatus() === 3;
        });
    }
    /**
     * @return ArrayCollection|Client[]
     */
    public function getIndependantUniqueParticipatingClients()
    {

        return $this->getActivity()->getOrganization()->getClients()->filter(function(Client $c){
            return $c->getExternalUsers()->exists(function(int $i, ExternalUser $e) {

                $contains = false;
                foreach($this->participants as $participant){
                    if($participant->getExtUsrId() == $e->getId()){
                        $contains = true;
                        break;
                    }
                }
                return $contains;

                /*return $this->participants->exists(function(int $i, Participation $p) use ($e){
                    $p->getExtUsrId() == $e->getId();
                });
                */
            });
        });
    }

    public function isModifiable(Stage $stage): bool
    {
        $connectedUser = $this->currentUser;
        $connectedUserRole = $connectedUser->getRole();
        if ($connectedUserRole === 4) {
            return true;
        }

        if ($stage->status >= 2) {
            return false;
        }

        if ($connectedUserRole === 1) {
            return true;
        }

        if ($stage->getMasterUser() == $connectedUser && ($stage->getUniqueGraderParticipations() === null || !$stage->getUniqueGraderParticipations()->exists(static function(int $i, Participation $p){return $p->isLeader();}))) {
            return true;
        }

        return $stage->getUniqueGraderParticipations()->exists(
            static function (int $i, Participation $p) use ($connectedUser) { return $p->getUser() === $connectedUser && $p->isLeader(); }
        );
    }

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getUniqueGraderParticipations()
    {
        return $this->getUniqueParticipations()->matching(Criteria::create()->where(Criteria::expr()->neq("type", -1)));
    }

    public function hasMinimumParticipationConfig(): bool
    {
        return $this->getUniqueGraderParticipations()->count() > 0 && $this->getUniqueGradableParticipations()->count() > 0;
    }
    public function getUniqueParticipations(): ArrayCollection
    {

        // Depends on whether current user is part of a team
        $eligibleParticipants = null;
        $uniqueParticipants = new ArrayCollection;
        $teams = [];

        $eligibleParticipants = count($this->criteria) === 0 ? $this->participants : $this->criteria->first()->getParticipants();

        $myParticipations = $this->getSelfParticipations();
        $myTeam = $myParticipations->count() === 0 ? null : $myParticipations->first()->getTeam();

        foreach ($eligibleParticipants as $eligibleParticipant) {
            $currentTeam = $eligibleParticipant->getTeam();
            if ($currentTeam === null || $currentTeam == $myTeam) {
                $uniqueParticipants->add($eligibleParticipant);
            } else if (!in_array($currentTeam, $teams, true)) {
                $uniqueParticipants->add($eligibleParticipant);
                $teams[] = $currentTeam;
            }
        }

        return $uniqueParticipants;
    }

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getUniqueIndivParticipations()
    {
        $indivParticipants = new ArrayCollection;
        $myParticipations = $this->getSelfParticipations();
        $myTeam = $myParticipations->count() === 0 ? null : $myParticipations->first()->getTeam();

        foreach ($this->getUniqueParticipations() as $participant) {
            $team = $participant->getTeam();
            if ($team === null || $team == $myTeam) {
                $indivParticipants->add($participant);
            }
        };
        return count($indivParticipants) > 0 ? $indivParticipants : null;
    }

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getIndependantUniqueIntParticipations()
    {
        $uniqueIndivParticipations = $this->getUniqueIndivParticipations();

        return $uniqueIndivParticipations === null ?
            null :
            new ArrayCollection(array_values(array_filter($this->getUniqueIndivParticipations()->toArray(),
                    function(Participation $p){
                        return $p->getUser()->getOrganization() == $this->currentUser->getOrganization() && $p->getTeam() === null;
                    })
            ));
    }
    public function getSelfParticipations(): ArrayCollection
    {
        return $this->participants->filter(function(Participation $p){
            return $p->getUser() == $this->currentUser;
        });
    }

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getUniqueExtParticipations()
    {
        $uniqueIndivParticipations = $this->getUniqueIndivParticipations();

        if ($uniqueIndivParticipations === null) {
            return null;
        }

        return new ArrayCollection(
            array_values(
                array_filter($this->getUniqueIndivParticipations()->toArray(), function (Participation $p) {
                    return $p->getUser()->getOrganization() != $this->currentUser->getOrganization();
                })
            )
        );
    }

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getIndependantUniqueExtParticipations()
    {

        $uniqueIndivParticipations = $this->getUniqueIndivParticipations();

        return $uniqueIndivParticipations === null ?
            null :
            new ArrayCollection(array_values(array_filter($this->getUniqueIndivParticipations()->toArray(),
                function(Participation $p){
                    return $p->getUser()->getOrganization() != $this->currentUser->getOrganization() && $p->getTeam() === null;
                })
            ));
    }

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getIndependantUniqueTeamParticipations()
    {
        $teamParticipants = new ArrayCollection;
        $currentTeam = null;
        foreach ($this->getIndependantUniqueParticipations() as $participant) {
            $pTeam = $participant->getTeam();
            if ($pTeam !== null && $pTeam != $currentTeam) {
                $currentTeam = $pTeam;
                $teamParticipants->add($participant);
            }
        };
        return $teamParticipants;
    }

    //TODO remove CRitetrion et isModifiable SelfParticipation et getAllSelfGrades
    //TODO indivParticipation

}
