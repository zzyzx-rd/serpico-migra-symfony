<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
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
    const STAGE_INCOMPLETE = -1;
    const STAGE_UNSTARTED = 0;
    const STAGE_ONGOING = 1;
    const STAGE_COMPLETED = 2;
    const STAGE_PUBLISHED = 3;

    const PROGRESS_STOPPED = -5;
    const PROGRESS_POSTPONED = -4;
    const PROGRESS_SUSPENDED = -3;
    const PROGRESS_REOPENED = -2;
    const PROGRESS_UNSTARTED = -1;
    const PROGRESS_UPCOMING = 0;
    const PROGRESS_ONGOING = 1;
    const PROGRESS_COMPLETED = 2;
    const PROGRESS_FINALIZED = 3;

    const VISIBILITY_public = 0;
    const VISIBILITY_UNLISTED = 1;
    const VISIBILITY_PUBLIC = 2;

    const GRADED_STAGE = 0;
    const GRADED_PARTICIPANTS = 1;
    const GRADED_STAGE_PARTICIPANTS = 2;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="stg_id", type="integer",nullable=false, length=10)
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(type="boolean")
     */
    public $stg_complete;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $stg_name;

    /**
     * @ORM\Column(type="integer")
     */
    public $stg_mode;

    /**
     * @ORM\Column(type="integer")
     */
    public $stg_visibility;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $stg_access_link;

    /**
     * @ORM\Column(type="integer")
     */
    public $stg_status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $stg_desc;

    /**
     * @ORM\Column(type="integer")
     */
    public $stg_progress;

    /**
     * @ORM\Column(type="float")
     */
    public $stg_weight;

    /**
     * @ORM\Column(type="boolean")
     */
    public $stg_definite_dates;

    /**
     * @ORM\Column(type="integer")
     */
    public $stg_dperiod;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $stg_dfrequency;

    /**
     * @ORM\Column(type="integer")
     */
    public $stg_dorigin;

    /**
     * @ORM\Column(type="integer")
     */
    public $stg_fperiod;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $stg_ffrequency;

    /**
     * @ORM\Column(type="integer")
     */
    public $stg_forigin;

    /**
     * @ORM\Column(type="datetime")
     */
    public $stg_startdate;

    /**
     * @ORM\Column(type="datetime")
     */
    public $stg_enddate;

    /**
     * @ORM\Column(type="datetime")
     */
    public $stg_gstartdate;

    /**
     * @ORM\Column(type="datetime")
     */
    public $stg_genddate;

    /**
     * @ORM\Column(type="integer")
     */
    public $stg_dealine_nb_days;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $stg_deadline_mailSent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $stg_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $stg_inserted;

    /**
     * @ORM\Column(type="boolean")
     */
    public $stg_reopened;

    /**
     * @ORM\Column(type="datetime")
     */
    public $stg_last_reopened;

    /**
     * @ORM\Column(type="boolean")
     */
    public $stg_unstarted_notif;

    /**
     * @ORM\Column(type="boolean")
     */
    public $stg_uncompleted_notif;

    /**
     * @ORM\Column(type="boolean")
     */
    public $stg_unfinished_notif;

    /**
     * @ORM\Column(type="boolean")
     */
    public $stg_isFinalized;

    /**
     * @ORM\Column(type="datetime")
     */
    public $stg_deleted;

    /**
     * @ORM\Column(type="datetime")
     */
    public $stg_gcompleted;

    /**
     * @OneToOne(targetEntity="Survey")
     * @JoinColumn(name="survey_sur_id", referencedColumnName="sur_id",nullable=true)
     * @var Survey
     */
    protected $survey;

    /**
     * @ManyToOne(targetEntity="Activity", inversedBy="stages")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id",nullable=false)
     */
    protected $activity;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="stages")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=true)
     * @var Organization
     */
    protected $organization;

    /**
     * @OneToMany(targetEntity="Criterion", mappedBy="stage", cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"weight" = "DESC"})
     * @var Collection
     */
    public $criteria;

    /**
     * @OneToMany(targetEntity="ActivityUser", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
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
     * @OneToOne(targetEntity="Template", mappedBy="stage",cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $template;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="stagesWhereMaster")
     */
    public $stg_master_user;

    /**
     * Stage constructor.
     * @param int $id
     * @param $stg_complete
     * @param $stg_name
     * @param $stg_mode
     * @param $stg_visibility
     * @param $stg_access_link
     * @param $stg_status
     * @param $stg_desc
     * @param $stg_progress
     * @param $stg_weight
     * @param $stg_definite_dates
     * @param $stg_dperiod
     * @param $stg_dfrequency
     * @param $stg_dorigin
     * @param $stg_fperiod
     * @param $stg_ffrequency
     * @param $stg_forigin
     * @param $stg_startdate
     * @param $stg_enddate
     * @param $stg_gstartdate
     * @param $stg_genddate
     * @param $stg_dealine_nb_days
     * @param $stg_deadline_mailSent
     * @param $stg_createdBy
     * @param $stg_inserted
     * @param $stg_reopened
     * @param $stg_last_reopened
     * @param $stg_unstarted_notif
     * @param $stg_uncompleted_notif
     * @param $stg_unfinished_notif
     * @param $stg_isFinalized
     * @param $stg_deleted
     * @param $stg_gcompleted
     * @param Survey $survey
     * @param $activity
     * @param Organization $organization
     * @param Collection $criteria
     * @param $participants
     * @param $decisions
     * @param $grades
     * @param $projectResults
     * @param $results
     * @param $resultTeams
     * @param $rankings
     * @param $rankingTeams
     * @param $historicalRankings
     * @param $historicalRankingTeams
     * @param $template
     * @param $stg_master_user
     */
    public function __construct(
        int $id = 0,
        $stg_complete = false,
        $stg_name = '',
        $stg_mode = null,
        $stg_visibility = 3,
        $stg_access_link = null,
        $stg_status = 0,
        $stg_desc = null,
        $stg_progress = -1,
        $stg_weight = 0.0,
        $stg_definite_dates = null,
        $stg_dperiod = null,
        $stg_dfrequency = null,
        $stg_dorigin = null,
        $stg_fperiod = null,
        $stg_ffrequency = null,
        $stg_forigin = null,
        $stg_startdate = null,
        $stg_enddate = null,
        $stg_gstartdate = null,
        $stg_genddate = null,
        $stg_dealine_nb_days = 3,
        $stg_deadline_mailSent = null,
        $stg_createdBy = null,
        $stg_inserted = null,
        $stg_reopened = null,
        $stg_last_reopened = null,
        $stg_unstarted_notif = null,
        $stg_uncompleted_notif = null,
        $stg_unfinished_notif = null,
        $stg_isFinalized = null,
        $stg_deleted = null,
        $stg_gcompleted = null,
        Survey $survey = null,
        $activity = null,
        Organization $organization = null,
        Collection $criteria = null,
        $participants = null,
        $decisions = null,
        $grades = null,
        $projectResults = null,
        $results = null,
        $resultTeams = null,
        $rankings = null,
        $rankingTeams = null,
        $historicalRankings = null,
        $historicalRankingTeams = null,
        $template = null,
        $stg_master_user = null)
    {
        parent::__construct($id, $stg_createdBy, new DateTime());
        $this->stg_complete = $stg_complete;
        $this->stg_name = $stg_name;
        $this->stg_mode = $stg_mode;
        $this->stg_visibility = $stg_visibility;
        $this->stg_access_link = $stg_access_link;
        $this->stg_status = $stg_status;
        $this->stg_desc = $stg_desc;
        $this->stg_progress = $stg_progress;
        $this->stg_weight = $stg_weight;
        $this->stg_definite_dates = $stg_definite_dates;
        $this->stg_dperiod = $stg_dperiod;
        $this->stg_dfrequency = $stg_dfrequency;
        $this->stg_dorigin = $stg_dorigin;
        $this->stg_fperiod = $stg_fperiod;
        $this->stg_ffrequency = $stg_ffrequency;
        $this->stg_forigin = $stg_forigin;
        $this->stg_startdate = $stg_startdate;
        $this->stg_enddate = $stg_enddate;
        $this->stg_gstartdate = $stg_gstartdate;
        $this->stg_genddate = $stg_genddate;
        $this->stg_dealine_nb_days = $stg_dealine_nb_days;
        $this->stg_deadline_mailSent = $stg_deadline_mailSent;
        $this->stg_inserted = $stg_inserted;
        $this->stg_reopened = $stg_reopened;
        $this->stg_last_reopened = $stg_last_reopened;
        $this->stg_unstarted_notif = $stg_unstarted_notif;
        $this->stg_uncompleted_notif = $stg_uncompleted_notif;
        $this->stg_unfinished_notif = $stg_unfinished_notif;
        $this->stg_isFinalized = $stg_isFinalized;
        $this->stg_deleted = $stg_deleted;
        $this->stg_gcompleted = $stg_gcompleted;
        $this->survey = $survey;
        $this->activity = $activity;
        $this->organization = $organization;
        $this->criteria = $criteria;
        $this->participants = $participants;
        $this->decisions = $decisions;
        $this->grades = $grades;
        $this->projectResults = $projectResults;
        $this->results = $results;
        $this->resultTeams = $resultTeams;
        $this->rankings = $rankings;
        $this->rankingTeams = $rankingTeams;
        $this->historicalRankings = $historicalRankings;
        $this->historicalRankingTeams = $historicalRankingTeams;
        $this->template = $template;
        $this->stg_master_user = $stg_master_user;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStgComplete(): ?bool
    {
        return $this->stg_complete;
    }

    public function setStgComplete(bool $stg_complete): self
    {
        $this->stg_complete = $stg_complete;

        return $this;
    }

    public function getStgName(): ?string
    {
        return $this->stg_name;
    }

    public function setStgName(string $stg_name): self
    {
        $this->stg_name = $stg_name;

        return $this;
    }

    public function getStgMode(): ?int
    {
        return $this->stg_mode;
    }

    public function setStgMode(int $stg_mode): self
    {
        $this->stg_mode = $stg_mode;

        return $this;
    }

    public function getStgVisibility(): ?int
    {
        return $this->stg_visibility;
    }

    public function setStgVisibility(int $stg_visibility): self
    {
        $this->stg_visibility = $stg_visibility;

        return $this;
    }

    public function getStgAccessLink(): ?string
    {
        return $this->stg_access_link;
    }

    public function setStgAccessLink(string $stg_access_link): self
    {
        $this->stg_access_link = $stg_access_link;

        return $this;
    }

    public function getStgStatus(): ?int
    {
        return $this->stg_status;
    }

    public function setStgStatus(int $stg_status): self
    {
        $this->stg_status = $stg_status;

        return $this;
    }

    public function getStgDesc(): ?string
    {
        return $this->stg_desc;
    }

    public function setStgDesc(string $stg_desc): self
    {
        $this->stg_desc = $stg_desc;

        return $this;
    }

    public function getStgProgress(): ?int
    {
        return $this->stg_progress;
    }

    public function setStgProgress(int $stg_progress): self
    {
        $this->stg_progress = $stg_progress;

        return $this;
    }

    public function getStgWeight(): ?float
    {
        return $this->stg_weight;
    }

    public function setStgWeight(float $stg_weight): self
    {
        $this->stg_weight = $stg_weight;

        return $this;
    }

    public function getStgDefiniteDates(): ?bool
    {
        return $this->stg_definite_dates;
    }

    public function setStgDefiniteDates(bool $stg_definite_dates): self
    {
        $this->stg_definite_dates = $stg_definite_dates;

        return $this;
    }

    public function getStgDperiod(): ?int
    {
        return $this->stg_dperiod;
    }

    public function setStgDperiod(int $stg_dperiod): self
    {
        $this->stg_dperiod = $stg_dperiod;

        return $this;
    }

    public function getStgDfrequency(): ?string
    {
        return $this->stg_dfrequency;
    }

    public function setStgDfrequency(string $stg_dfrequency): self
    {
        $this->stg_dfrequency = $stg_dfrequency;

        return $this;
    }

    public function getStgDorigin(): ?int
    {
        return $this->stg_dorigin;
    }

    public function setStgDorigin(int $stg_dorigin): self
    {
        $this->stg_dorigin = $stg_dorigin;

        return $this;
    }

    public function getStgFperiod(): ?int
    {
        return $this->stg_fperiod;
    }

    public function setStgFperiod(int $stg_fperiod): self
    {
        $this->stg_fperiod = $stg_fperiod;

        return $this;
    }

    public function getStgFfrequency(): ?string
    {
        return $this->stg_ffrequency;
    }

    public function setStgFfrequency(string $stg_ffrequency): self
    {
        $this->stg_ffrequency = $stg_ffrequency;

        return $this;
    }

    public function getStgForigin(): ?int
    {
        return $this->stg_forigin;
    }

    public function setStgForigin(int $stg_forigin): self
    {
        $this->stg_forigin = $stg_forigin;

        return $this;
    }

    public function getStgStartdate(): ?\DateTimeInterface
    {
        return $this->stg_startdate;
    }

    public function setStgStartdate(\DateTimeInterface $stg_startdate): self
    {
        $this->stg_startdate = $stg_startdate;

        return $this;
    }

    public function getStgEnddate(): ?\DateTimeInterface
    {
        return $this->stg_enddate;
    }

    public function setStgEnddate(\DateTimeInterface $stg_enddate): self
    {
        $this->stg_enddate = $stg_enddate;

        return $this;
    }

    public function getStgGstartdate(): ?\DateTimeInterface
    {
        return $this->stg_gstartdate;
    }

    public function setStgGstartdate(\DateTimeInterface $stg_gstartdate): self
    {
        $this->stg_gstartdate = $stg_gstartdate;

        return $this;
    }

    public function getStgGenddate(): ?\DateTimeInterface
    {
        return $this->stg_genddate;
    }

    public function setStgGenddate(\DateTimeInterface $stg_genddate): self
    {
        $this->stg_genddate = $stg_genddate;

        return $this;
    }

    public function getStgDealineNbDays(): ?int
    {
        return $this->stg_dealine_nb_days;
    }

    public function setStgDealineNbDays(int $stg_dealine_nb_days): self
    {
        $this->stg_dealine_nb_days = $stg_dealine_nb_days;

        return $this;
    }

    public function getStgDeadlineMailSent(): ?bool
    {
        return $this->stg_deadline_mailSent;
    }

    public function setStgDeadlineMailSent(?bool $stg_deadline_mailSent): self
    {
        $this->stg_deadline_mailSent = $stg_deadline_mailSent;

        return $this;
    }

    public function getStgCreatedBy(): ?int
    {
        return $this->stg_createdBy;
    }

    public function setStgCreatedBy(?int $stg_createdBy): self
    {
        $this->stg_createdBy = $stg_createdBy;

        return $this;
    }

    public function getStgInserted(): ?\DateTimeInterface
    {
        return $this->stg_inserted;
    }

    public function setStgInserted(?\DateTimeInterface $stg_inserted): self
    {
        $this->stg_inserted = $stg_inserted;

        return $this;
    }

    public function getStgReopened(): ?bool
    {
        return $this->stg_reopened;
    }

    public function setStgReopened(bool $stg_reopened): self
    {
        $this->stg_reopened = $stg_reopened;

        return $this;
    }

    public function getStgLastReopened(): ?\DateTimeInterface
    {
        return $this->stg_last_reopened;
    }

    public function setStgLastReopened(\DateTimeInterface $stg_last_reopened): self
    {
        $this->stg_last_reopened = $stg_last_reopened;

        return $this;
    }

    public function getStgUnstartedNotif(): ?bool
    {
        return $this->stg_unstarted_notif;
    }

    public function setStgUnstartedNotif(bool $stg_unstarted_notif): self
    {
        $this->stg_unstarted_notif = $stg_unstarted_notif;

        return $this;
    }

    public function getStgUncompletedNotif(): ?bool
    {
        return $this->stg_uncompleted_notif;
    }

    public function setStgUncompletedNotif(bool $stg_uncompleted_notif): self
    {
        $this->stg_uncompleted_notif = $stg_uncompleted_notif;

        return $this;
    }

    public function getStgUnfinishedNotif(): ?bool
    {
        return $this->stg_unfinished_notif;
    }

    public function setStgUnfinishedNotif(bool $stg_unfinished_notif): self
    {
        $this->stg_unfinished_notif = $stg_unfinished_notif;

        return $this;
    }

    public function getStgIsFinalized(): ?bool
    {
        return $this->stg_isFinalized;
    }

    public function setStgIsFinalized(bool $stg_isFinalized): self
    {
        $this->stg_isFinalized = $stg_isFinalized;

        return $this;
    }

    public function getStgDeleted(): ?\DateTimeInterface
    {
        return $this->stg_deleted;
    }

    public function setStgDeleted(\DateTimeInterface $stg_deleted): self
    {
        $this->stg_deleted = $stg_deleted;

        return $this;
    }

    public function getStgGcompleted(): ?\DateTimeInterface
    {
        return $this->stg_gcompleted;
    }

    public function setStgGcompleted(\DateTimeInterface $stg_gcompleted): self
    {
        $this->stg_gcompleted = $stg_gcompleted;

        return $this;
    }

    /**
     * @return Survey
     */
    public function getSurvey(): Survey
    {
        return $this->survey;
    }

    /**
     * @param Survey $survey
     */
    public function setSurvey(Survey $survey): void
    {
        $this->survey = $survey;
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
    public function setActivity($activity): void
    {
        $this->activity = $activity;
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
    public function setOrganization(Organization $organization): void
    {
        $this->organization = $organization;
    }

    /**
     * @return Collection
     */
    public function getCriteria(): Collection
    {
        return $this->criteria;
    }

    /**
     * @param Collection $criteria
     */
    public function setCriteria(Collection $criteria): void
    {
        $this->criteria = $criteria;
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
    public function setParticipants($participants): void
    {
        $this->participants = $participants;
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
    public function setDecisions($decisions): void
    {
        $this->decisions = $decisions;
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
    public function setGrades($grades): void
    {
        $this->grades = $grades;
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
    public function setProjectResults($projectResults): void
    {
        $this->projectResults = $projectResults;
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
    public function setResults($results): void
    {
        $this->results = $results;
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
    public function setResultTeams($resultTeams): void
    {
        $this->resultTeams = $resultTeams;
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
    public function setRankings($rankings): void
    {
        $this->rankings = $rankings;
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
    public function setRankingTeams($rankingTeams): void
    {
        $this->rankingTeams = $rankingTeams;
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
    public function setHistoricalRankings($historicalRankings): void
    {
        $this->historicalRankings = $historicalRankings;
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
    public function setHistoricalRankingTeams($historicalRankingTeams): void
    {
        $this->historicalRankingTeams = $historicalRankingTeams;
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
    public function setTemplate($template): void
    {
        $this->template = $template;
    }

    public function getStgMasterUser(): ?User
    {
        return $this->stg_master_user;
    }

    public function setStgMasterUser(?User $stg_master_user): self
    {
        $this->stg_master_user = $stg_master_user;

        return $this;
    }
    public function getActiveWeight(){
        $sumWeightCompletedStages = 0;
        /** @var ArrayCollection|Stage[] */
        $completedStages = $this->activity->getOCompletedStages();
        foreach($completedStages as $completedStage){
            $sumWeightCompletedStages += $completedStage->weight;
        }
        return ($sumWeightCompletedStages == 0) ? $this->weight : $this->weight / $sumWeightCompletedStages;
    }

    public function setActiveWeight($activeWeight){
        $sumWeightCompletedStages = 0;
        /** @var ArrayCollection|Stage[] */
        $completedStages = $this->activity->getOCompletedStages();
        foreach($completedStages as $completedStage){
            $sumWeightCompletedStages += $completedStage->weight;
        }
        $this->weight = round((1 - $sumWeightCompletedStages) * $activeWeight, 3);
    }
    /**
     * @return Collection|ActivityUser[]
     */
    public function getUniqueParticipations()
    {

        // Depends on whether current user is part of a team
        $eligibleParticipants = null;
        $uniqueParticipants = new ArrayCollection;
        $teams = [];

        $eligibleParticipants = count($this->criteria) == 0 ? $this->participants : $this->criteria->first()->getParticipants();

        $myParticipations = $this->getSelfParticipations();
        $myTeam = $myParticipations->count() == 0 ? null : $myParticipations->first()->getTeam();

        foreach ($eligibleParticipants as $eligibleParticipant) {
            $currentTeam = $eligibleParticipant->getTeam();
            if ($currentTeam == null || $currentTeam == $myTeam) {
                $uniqueParticipants->add($eligibleParticipant);
            } else {
                if (!in_array($currentTeam, $teams)) {
                    $uniqueParticipants->add($eligibleParticipant);
                    $teams[] = $currentTeam;
                }
            }
        }

        return $uniqueParticipants;
    }
    /**
     * @return Collection|ActivityUser[]
     */
    public function getIndependantUniqueParticipations()
    {
        $eligibleParticipants = null;
        $uniqueParticipants = new ArrayCollection;
        $teams = [];


        $eligibleParticipants = count($this->criteria) == 0 ? $this->participants : $this->criteria->first()->getParticipants();


        foreach ($eligibleParticipants as $eligibleParticipant) {
            $team = $eligibleParticipant->getTeam();
            if ($team == null) {
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
     * @return Collection|ActivityUser[]
     */
    public function getUniqueTeamParticipations()
    {
        $myParticipations = $this->getSelfParticipations();
        $myTeam = $myParticipations->count() == 0 ? null : $myParticipations->first()->getTeam();
        return $this->getUniqueParticipations()->filter(function(ActivityUser $p) use ($myTeam){
            return $p->getTeam() != null && $p->getTeam() != $myTeam;
        });
    }
    /**
     * @return Collection|ActivityUser[]
     */
    public function getUserGradableParticipations()
    {
        // We get all non-third party user participations, except those of people who are part of a team we don't belong to
        if ($this->mode == STAGE::GRADED_STAGE) {
            return null;
        } else {

            $userGradableParticipations = new ArrayCollection;

            $unorderedGradableParticipations = $this->getUniqueIndivParticipations()->filter(function(ActivityUser $p){
                return $p->getType() != ACTIVITYUSER::PARTICIPATION_THIRD_PARTY;
            });

            foreach($unorderedGradableParticipations as $unorderedGradableParticipation){
                $userGradableParticipations->add($unorderedGradableParticipation);
            }

            return $userGradableParticipations;
        }
    }
    public function addTeamGradableParticipation(ActivityUser $participant)
    {
        $this->participants->add($participant);
        return $this;
    }

    public function removeTeamGradableParticipation(ActivityUser $participant)
    {
        $this->participants->removeElement($participant);
        return $this;
    }

    /**
     * @return Collection|ActivityUser[]
     */
    public function getUniqueGradableParticipations()
    {
        return $this->getUniqueParticipations()->matching(Criteria::create()->where(Criteria::expr()->neq("type", 0)));
    }

    /**
     * @return Collection|ActivityUser[]
     */
    public function getUniqueGraderParticipations()
    {
        return $this->getUniqueParticipations()->matching(Criteria::create()->where(Criteria::expr()->neq("type", -1)));
    }

    public function addUniqueGradableParticipation(ActivityUser $participant)
    {
        if ($this->participants->exists(function (ActivityUser $u) use ($participant) {
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

    public function removeUniqueGradableParticipation(ActivityUser $participant)
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
    public function addGrade(Grade $grade)
    {
        $this->grades->add($grade);
        return $this;
    }

    public function removeGrade(Grade $grade)
    {
        $this->grades->removeElement($grade);
        return $this;
    }

    public function addCriterion(Criterion $criterion)
    {
        //The below line is to prevent adding a criterion already submitted (because of activeStages/stages).
        // However as stage criteria are built in advance for recurring activities, we also need to take into account this exception

        //if(!$criterion->getStage() || $criterion->getStage()->getActivity()->getRecurring()){
        $this->criteria->add($criterion);
        $criterion->setStage($this);
        return $this;
        //}
    }
    public function userCanSeeOutput(User $u)
    {
        return $this->status == self::STAGE_ONGOING && $this->userHasGivenOutput($u);
    }

    public function userHasGivenOutput(User $u)
    {
        if($this->participants->isEmpty()){
            return false;
        } else {
            $userParticipations = $this->participants->filter(function(ActivityUser $p) use ($u){return $p->getDirectUser() === $u;});
            if($userParticipations->count() > 0){
                return $userParticipations->forAll(function(int $i, ActivityUser $p) {
                    return $p->getStatus() >= 3;
                });
            } else {
                return false;
            }
        }
    }

    public function addParticipant(ActivityUser $participant)
    {

        $this->participants->add($participant);
        $participant->setStage($this);
        return $this;
    }

    public function removeParticipant(ActivityUser $participant)
    {
        $this->participants->removeElement($participant);
        return $this;
    }

    public function addUniqueParticipation(ActivityUser $participant)
    {
        if (count($this->criteria) != 0) {
            foreach ($this->criteria as $criterion) {
                $criterion->addParticipant($participant);
                $participant->setCriterion($criterion)->setStage($this)->setActivity($this->getActivity());
            }
        } else {
            $this->addParticipant($participant);
        }
        return $this;
    }

    public function removeUniqueParticipation(ActivityUser $participant)
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

    public function addIndependantUniqueParticipation(ActivityUser $participant)
    {
        return $this->addUniqueParticipation($participant);
    }

    public function removeIndependantUniqueParticipation(ActivityUser $participant)
    {
        return $this->removeUniqueParticipation($participant);
    }

    public function addUniqueIntParticipation(ActivityUser $participant)
    {
        $this->addUniqueParticipation($participant);
        return $this;
    }

    public function removeUniqueIntParticipation(ActivityUser $participant)
    {
        $this->removeUniqueParticipation($participant);
        return $this;
    }

    public function addIndependantUniqueIntParticipation(ActivityUser $participant)
    {
        $this->addIndependantUniqueParticipation($participant);
        return $this;
    }

    public function removeIndependantUniqueIntParticipation(ActivityUser $participant)
    {
        $this->removeIndependantUniqueParticipation($participant);
        return $this;
    }

    public function addUniqueExtParticipation(ActivityUser $participant)
    {
        $this->addUniqueParticipation($participant);
        return $this;
    }

    public function removeUniqueExtParticipation(ActivityUser $participant)
    {
        $this->removeUniqueParticipation($participant);
        return $this;
    }

    public function addIndependantUniqueExtParticipation(ActivityUser $participant)
    {
        $this->addIndependantUniqueExtParticipation($participant);
        return $this;
    }

    public function removeIndependantUniqueExtParticipation(ActivityUser $participant)
    {
        $this->removeIndependantUniqueParticipation($participant);
        return $this;
    }

    public function addUniqueTeamParticipation(ActivityUser $participant)
    {
        $this->addUniqueParticipation($participant);
        return $this;
    }

    public function removeUniqueTeamParticipation(ActivityUser $participant)
    {
        $this->removeUniqueParticipation($participant);
        return $this;
    }

    public function addIndependantUniqueTeamParticipation(ActivityUser $participant)
    {
        $this->addUniqueTeamParticipation($participant);
        return $this;
    }

    public function removeIndependantUniqueTeamParticipation(ActivityUser $participant)
    {
        $this->removeUniqueTeamParticipation($participant);
        return $this;
    }

    public function addDecision(Decision $decision)
    {

        $this->decisions->add($decision);
        $decision->setStage($this);
        return $this;
    }

    public function removeDecision(Decision $decision)
    {
        $this->decisions->removeElement($decision);
        return $this;
    }

    public function addResult(Result $result)
    {
        $this->results->add($result);
        $result->setStage($this);
        return $this;
    }

    public function removeResult(Result $result)
    {
        $this->results->removeElement($result);
        return $this;
    }

    public function addRankings(Ranking $ranking)
    {
        $this->rankings->add($ranking);
        $ranking->setActivity($this);
        return $this;
    }

    public function removeRanking(Ranking $ranking)
    {
        $this->rankings->removeElement($ranking);
        return $this;
    }

    public function addHistoricalRankings(RankingHistory $historicalRanking)
    {
        $this->historicalRankings->add($historicalRanking);
        $historicalRanking->setActivity($this);
        return $this;
    }

    public function removeHistoricalRanking(RankingHistory $ranking)
    {
        $this->rankings->removeElement($ranking);
        return $this;
    }

    public function addResultTeam(ResultTeam $resultTeam)
    {
        $this->resultTeams->add($resultTeam);
        $resultTeam->setStage($this);
        return $this;
    }

    public function removeResultTeam(ResultTeam $resultTeam)
    {
        $this->resultTeams->removeElement($resultTeam);
        return $this;
    }

    public function addRankingTeam(RankingTeam $rankingTeam)
    {
        $this->rankingTeams->add($rankingTeam);
        $rankingTeam->setActivity($this);
        return $this;
    }

    public function removeRankingTeam(RankingTeam $rankingTeam)
    {
        $this->rankingTeams->removeElement($rankingTeam);
        return $this;
    }

    public function addHistoricalRankingTeam(RankingTeamHistory $historicalRankingTeam)
    {
        $this->historicalRankingTeams->add($historicalRankingTeam);
        $historicalRankingTeam->setActivity($this);
        return $this;
    }

    public function removeHistoricalRankingTeam(RankingTeamHistory $historicalRankingTeam)
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
                if ($participant->getType() != -1) {
                    $l++;
                }
            }
            if ($l > 0) {
                return $k / $l;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    // Defines which users can grade
    /**
     * @return ArrayCollection|User[]
     */
    public function getGraderUsers()
    {
        return $this->getUniqueGraderParticipations()->map(
            function (ActivityUser $p) {
                return $p->getDirectUser();
            }
        );
    }

    // Defines nb of evaluating criteria
    /**
     * @return int
     */
    public function getNbEvaluatingCriteria()
    {
        return count($this->getCriteria()->matching(Criteria::create()->where(Criteria::expr()->eq("type", 1))));
    }
    public function getSelfGrades()
    {
        return $this->getAllSelfGrades()->matching(Criteria::create()->where(Criteria::expr()->in("participant", $this->getSelfParticipations()->getValues())));
    }

    public function addSelfGrade(Grade $grade)
    {
        $this->grades->add($grade);
        $grade->setStage($this);
        return $this;
    }

    public function removeSelfGrade(Grade $grade)
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
            return $this->getUniqueGraderParticipations()->exists(function (int $i,ActivityUser $p) use ($u) {
                return $p->getDirectUser() === $u && $p->getType() != -1 && $p->getStatus() < 3;
            }
            );
        } else {
            return false;
        }

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

    public function hasMinimumParticipationConfig(){
        return $this->getUniqueGraderParticipations()->count() > 0 && $this->getUniqueGradableParticipations()->count() > 0;
    }

    public function hasMinimumOutputConfig(){
        return $this->getSurvey() != null || $this->getCriteria()->count() > 0;
    }

    public function hasFeedbackExpired(){
        $yesterdayDate = new \DateTime;
        $yesterdayDate->sub(new \DateInterval('P1D'));
        return $this->genddate <= $yesterdayDate && $this->status < STAGE::STAGE_COMPLETED;
    }

    public function isComplete(){
        $yesterdayDate = new \DateTime;
        $yesterdayDate->sub(new \DateInterval('P1D'));
        return $this->hasMinimumOutputConfig() && $this->hasMinimumParticipationConfig() && !$this->hasFeedbackExpired();
    }

    public function hasCompletedOutput(){
        return (int) $this->participants->exists(function(int $i, ActivityUser $p){
            return $p->getStatus() == 3;
        });
    }
    //TODO remove CRitetrion et isModifiable SelfParticipation et getAllSelfGrades
    //TODO indivParticipation
}
