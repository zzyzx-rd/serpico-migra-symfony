<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StageRepository;
use Doctrine\Common\Collections\Collection;
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
class Stage
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

    const VISIBILITY_PRIVATE = 0;
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
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stg_complete;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_mode;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_visibility;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_access_link;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_desc;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_progress;

    /**
     * @ORM\Column(type="float")
     */
    private $stg_weight;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stg_definite_dates;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_dperiod;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_dfrequency;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_dorigin;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_fperiod;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_ffrequency;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_forigin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_startdate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_enddate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_gstartdate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_genddate;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_dealine_nb_days;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $stg_deadline_mailSent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stg_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $stg_inserted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stg_reopened;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_last_reopened;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stg_unstarted_notif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stg_uncompleted_notif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stg_unfinished_notif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stg_isFinalized;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_deleted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_gcompleted;

    /**
     * @OneToOne(targetEntity="Survey")
     * @JoinColumn(name="survey_sur_id", referencedColumnName="sur_id",nullable=true)
     * @var Survey
     */
    protected $survey;

    /**
     * @ManyToOne(targetEntity="Activity")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id",nullable=false)
     */
    protected $activity;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=true)
     * @var Organization
     */
    protected $organization;

    /**
     * @OneToMany(targetEntity="Criterion", mappedBy="stage", cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"weight" = "DESC"})
     * @var Collection
     */
    private $criteria;

    /**
     * @OneToMany(targetEntity="ActivityUser", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"team" = "ASC"})
     */
    private $participants;

    /**
     * @OneToMany(targetEntity="Decision", mappedBy="stage",cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $decisions;

    /**
     * @OneToMany(targetEntity="Grade", mappedBy="stage",cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $grades;

    /**
     * @OneToMany(targetEntity="ResultProject", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $projectResults;

    /**
     * @OneToMany(targetEntity="Result", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $results;

    /**
     * @OneToMany(targetEntity="ResultTeam", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $resultTeams;

    /**
     * @OneToMany(targetEntity="Ranking", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $rankings;

    /**
     * @OneToMany(targetEntity="RankingTeam", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $rankingTeams;

    /**
     * @OneToMany(targetEntity="RankingHistory", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $historicalRankings;

    /**
     * @OneToMany(targetEntity="RankingTeamHistory", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $historicalRankingTeams;

    /**
     * @OneToOne(targetEntity="Template", mappedBy="stage",cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $template;

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

}
