<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ActivityRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ActivityRepository", repositoryClass=ActivityRepository::class)
 */
class Activity extends DbObject
{
    public const STATUS_CANCELLED = -5;
    public const STATUS_REJECTED = -4;
    public const STATUS_REQUESTED = -3;
    public const STATUS_AWAITING_CREATION = -2;
    public const STATUS_INCOMPLETE = -1;
    public const STATUS_FUTURE = 0;
    public const STATUS_ONGOING = 1;
    public const STATUS_FINALIZED = 2;
    public const STATUS_PUBLISHED = 3;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @Column(name="act_id", type="integer", nullable=false)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="act_complete", type="boolean", nullable=false)
     */
    public bool $complete;

    /**
     * @ORM\Column(name="act_magnitude", type="integer", nullable=true)
     */
    public int $magnitude;

    /**
     * @ORM\Column(name="act_simplified", type="boolean", nullable=false)
     */
    public bool $simplified;

    /**
     * @ORM\Column(name="act_name", type="string", length=255, nullable=true)
     */
    public string $name;

    /**
     * @ORM\Column(name="act_visibility", type="string", length=255, nullable=true)
     */
    public string $visibility;

    /**
     * @ORM\Column(name="act_startDate", type="datetime", nullable=true)
     */
    public ?DateTime $startdate;

    /**
     * @ORM\Column(name="act_endDate", type="datetime", nullable=true)
     */
    public ?DateTime $enddate;

    /**
     * @ORM\Column(name="act_objectives", type="string", length=255, nullable=true)
     */
    public string $objectives;

    /**
     * @ORM\Column(name="act_status", type="integer", nullable=true)
     */
    public ?int $status;

    /**
     * @ManyToOne(targetEntity="Process", inversedBy="activities")
     * @JoinColumn(name="process_pro_id", referencedColumnName="pro_id", nullable=true)
     */
    protected ?Process $process;

    /**
     * @ORM\ManyToOne(targetEntity=InstitutionProcess::class, inversedBy="activities")
     * @JoinColumn(name="institution_process_id", referencedColumnName="inp_id", nullable=true)
     */
    public ?InstitutionProcess $institutionProcess;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="activities")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=false)
     */
    protected ?Organization $organization;
    /**
     * @OneToMany(targetEntity="Stage", mappedBy="activity", cascade={"persist", "remove"}, orphanRemoval=true)
     */
//     * @OrderBy({"stg_startdate" = "ASC", "inserted" = "ASC"})
    public $stages;
    /**
     * @OneToMany(targetEntity="Decision", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $decisions;
    /**
     * @OneToMany(targetEntity="Grade", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Grade[]
     */
    public $grades;
    /**
     * @OneToMany(targetEntity="Result", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $results;
    /**
     * @OneToMany(targetEntity="ResultProject", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $projectResults;
    /**
     * @OneToMany(targetEntity="ResultTeam", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $resultTeams;
    /**
     * @OneToMany(targetEntity="Ranking", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $rankings;
    /**
     * @OneToMany(targetEntity="RankingTeam", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $rankingTeams;
    /**
     * @OneToMany(targetEntity="RankingHistory", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $historicalRankings;
    /**
     * @OneToMany(targetEntity="RankingTeamHistory", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $historicalRankingTeams;

    /**
     * @OneToMany(targetEntity="Participation", mappedBy="activity", cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"team" = "ASC"})
     */
    public $participations;

    /**
     * @OneToMany(targetEntity="Event", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $events;

    /**
     * @Column(name="act_progress", type="float", nullable=false)
     * @var float
     */
    protected float $progress;
    /**
     * @Column(name="act_isRewarding", type="boolean", nullable=true)
     * @var bool
     */
    protected bool $isRewarding;
    /**
     * @Column(name="act_distrAmount", length=10, type="float", nullable=true)
     * @var float
     */
    protected float $distrAmount;
    /**
     * @Column(name="act_res_inertia", length= 10, type="float", nullable=true)
     * @var int
     */
    protected int $res_inertia;
    /**
     * @Column(name="act_res_benefit_eff", length= 10, type="float", nullable=true)
     * @var int
     */
    protected int $res_benefit_eff;
    /**
     * @Column(name="act_created_by", type="integer", nullable=true)
     * @var int
     */
    protected ?int $createdBy;
    /**
     * @Column(name="act_inserted", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     * @var DateTime
     */
    protected DateTime $inserted;
    /**
     * @Column(name="act_saved", type="datetime", nullable=true)
     * @var DateTime
     */
    protected ?DateTime $saved;
    /**
     * @Column(name="act_isFinalized", type="boolean", nullable=false)
     * @var bool
     */
    protected bool $isFinalized;
    /**
     * @Column(name="act_finalized", type="datetime", nullable=true)
     * @var DateTime
     */
    protected ?DateTime $finalized;
    /**
     * @Column(name="act_deleted", type="datetime", nullable=true)
     * @var DateTime
     */
    protected ?DateTime $deleted;
    /**
     * @Column(name="act_completed", type="datetime", nullable=true)
     * @var DateTime
     */
    protected ?DateTime $completed;
    /**
     * @Column(name="act_released", type="datetime", nullable=true)
     * @var DateTime
     */
    protected ?DateTime $released;
    /**
     * @Column(name="act_archived", type="datetime", nullable=true)
     * @var DateTime
     */
    protected ?DateTime $archived;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="act_master_usr_id", referencedColumnName="usr_id", nullable=false)
     */
    public ?User $masterUser;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    public bool $diffCriteria;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    public bool $diffParticipants;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    public int $nbParticipants;
    /**
     * @var DateTime
     */
    public DateTime $act_gEndDate;
    public $act_gStartDate;
    public ?User $currentUser = null;

    public function __toString()
    {
        return (string) $this->id;
    }

    /**
     * Activity constructor.
     * @param int $id
     * @param int $magnitude
     * @param bool $complete
     * @param bool $simplified
     * @param string $name
     * @param string $visibility
     * @param DateTime|null $startdate
     * @param DateTime|null $enddate
     * @param bool $diffCriteria
     * @param bool $diffParticipants
     * @param int $nbParticipants
     * @param string $objectives
     * @param int|null $status
     * @param float $progress
     * @param bool $isRewarding
     * @param float $distrAmount
     * @param int $res_inertia
     * @param int $res_benefit_eff
     * @param int|null $createdBy
     * @param DateTime|null $deleted
     * @param DateTime|null $completed
     * @param DateTime|null $saved
     * @param bool $isFinalized
     * @param DateTime|null $finalized
     * @param DateTime|null $released
     * @param DateTime|null $archived
     */
    public function __construct(
        int $id = 0,
        int $magnitude = 1,
        bool $complete = false,
        bool $simplified = true,
        string $name = '',
        string $visibility = 'public',
        DateTime $startdate = null,
        DateTime $enddate = null,
        bool $diffCriteria = false,
        bool $diffParticipants = false,
        int $nbParticipants = 0,
        string $objectives = '',
        int $status = null,
        float $progress = 0.0,
        bool $isRewarding = false,
        float $distrAmount = 0.0,
        int $res_inertia = 0,
        int $res_benefit_eff = 0,
        int $createdBy = null,
        DateTime $deleted = null,
        DateTime $completed = null,
        DateTime $saved = null,
        bool $isFinalized = false,
        DateTime $finalized = null,
        DateTime $released = null,
        DateTime $archived = null
    ) {
        parent::__construct($id, $createdBy, new DateTime());
        $this->magnitude = $magnitude;
        $this->complete = $complete;
        $this->simplified = $simplified;
        $this->name = $name;
        $this->visibility = $visibility;
        $this->startdate = $startdate;
        $this->enddate = $enddate;
        $this->diffCriteria = $diffCriteria;
        $this->diffParticipants = $diffParticipants;
        $this->nbParticipants = $nbParticipants;
        $this->objectives = $objectives;
        $this->status = $status;
        $this->progress = $progress;
        $this->isRewarding = $isRewarding;
        $this->distrAmount = $distrAmount;
        $this->res_inertia = $res_inertia;
        $this->res_benefit_eff = $res_benefit_eff;
        $this->deleted = $deleted;
        $this->completed = $completed;
        $this->saved = $saved;
        $this->isFinalized = $isFinalized;
        $this->finalized = $finalized;
        $this->saved = $saved;
        $this->released = $released;
        $this->archived = $archived;
        $this->participants = new ArrayCollection;
        $this->stages = new ArrayCollection;
        $this->decisions = new ArrayCollection;
        $this->projectResults = new ArrayCollection;
        $this->results = new ArrayCollection;
        $this->rankings = new ArrayCollection;
        $this->historicalRankings = new ArrayCollection;
        $this->resultTeams = new ArrayCollection;
        $this->rankingTeams = new ArrayCollection;
        $this->historicalRankingTeams = new ArrayCollection;
        $this->createdBy = $createdBy;
        $this->act_gEndDate = new DateTime('1990-01-01');
        //TODO Set la date et autres dans les controlleurs
        foreach ($this->stages as $stage) {
            if ($stage->getGEnddate() > $this->act_gEnddate) {
                $this->act_gEnddate = $stage->getGEnddate();
            }
        }
        $this->act_gStartDate = new DateTime('2099-01-01');
        foreach ($this->stages as $stage) {
            if ($stage->getStartdate() <  $this->act_gStartDate) {
                $this->act_gStartDate = $stage->getStartDate();
            }
        }
    }

    public function getComplete(): ?bool
    {
        return $this->complete;
    }

    public function setComplete(bool $complete): self
    {
        $this->complete = $complete;

        return $this;
    }


    public function getMagnitude(): ?int
    {
        return $this->magnitude;
    }

    public function setMagnitude(int $magnitude): self
    {
        $this->magnitude = $magnitude;

        return $this;
    }

    public function getSimplified(): ?bool
    {
        return $this->simplified;
    }

    public function setSimplified(bool $simplified): self
    {
        $this->simplified = $simplified;

        return $this;
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

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(string $visibility): self
    {
        $this->visibility = $visibility;

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

    public function setEnddate(DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getObjectives(): ?string
    {
        return $this->objectives;
    }

    public function setObjectives(string $objectives): self
    {
        $this->objectives = $objectives;

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

    public function getProcess(): ?Process
    {
        return $this->process;
    }

    public function setProcess(?Process $process): self
    {
        $this->process = $process;

        return $this;
    }

    public function getInstitutionProcess(): ?InstitutionProcess
    {
        return $this->institutionProcess;
    }

    public function setInstitutionProcess(?InstitutionProcess $institutionProcess): self
    {
        $this->institutionProcess = $institutionProcess;

        return $this;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStages()
    {
        return $this->stages;
    }

    /**
     * @param mixed $stages
     */
    public function setStages($stages): void
    {
        $this->stages = $stages;
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
     * @return Grade[]|ArrayCollection
     */
    public function getGrades()
    {
        return $this->grades;
    }

    /**
     * @param Grade[]|ArrayCollection $grades
     */
    public function setGrades($grades): void
    {
        $this->grades = $grades;
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

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getParticipations()
    {
        return $this->participants;
    }

    /**
     * @return float
     */
    public function getProgress(): float
    {
        return $this->progress;
    }

    /**
     * @param float $progress
     */
    public function setProgress(float $progress): void
    {
        $this->progress = $progress;
    }

    /**
     * @return bool
     */
    public function isRewarding(): bool
    {
        return $this->isRewarding;
    }

    /**
     * @param bool $isRewarding
     */
    public function setIsRewarding(bool $isRewarding): void
    {
        $this->isRewarding = $isRewarding;
    }

    /**
     * @return float
     */
    public function getDistrAmount(): float
    {
        return $this->distrAmount;
    }

    /**
     * @param float $distrAmount
     */
    public function setDistrAmount(float $distrAmount): void
    {
        $this->distrAmount = $distrAmount;
    }

    /**
     * @return int
     */
    public function getResInertia(): int
    {
        return $this->res_inertia;
    }

    /**
     * @param int $res_inertia
     */
    public function setResInertia(int $res_inertia): void
    {
        $this->res_inertia = $res_inertia;
    }

    /**
     * @return int
     */
    public function getResBenefitEff(): int
    {
        return $this->res_benefit_eff;
    }

    /**
     * @param int $res_benefit_eff
     */
    public function setResBenefitEff(int $res_benefit_eff): void
    {
        $this->res_benefit_eff = $res_benefit_eff;
    }


    public function setInserted(?DateTimeInterface $inserted): void
    {
        $this->inserted = $inserted;
    }

    /**
     * @return DateTime
     */
    public function getSaved(): DateTime
    {
        return $this->saved;
    }

    /**
     * @param DateTime $saved
     */
    public function setSaved(DateTime $saved): void
    {
        $this->saved = $saved;
    }

    /**
     * @return bool
     */
    public function isFinalized(): bool
    {
        return $this->isFinalized;
    }

    /**
     * @param bool $isFinalized
     */
    public function setIsFinalized(bool $isFinalized): void
    {
        $this->isFinalized = $isFinalized;
    }

    /**
     * @return DateTime
     */
    public function getFinalized(): DateTime
    {
        return $this->finalized;
    }

    /**
     * @param DateTime $finalized
     */
    public function setFinalized(DateTime $finalized): void
    {
        $this->finalized = $finalized;
    }

    /**
     * @return DateTime
     */
    public function getDeleted(): DateTime
    {
        return $this->deleted;
    }

    /**
     * @param DateTime $deleted
     * @return Activity
     */
    public function setDeleted(DateTime $deleted): Activity
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCompleted(): DateTime
    {
        return $this->completed;
    }

    /**
     * @param DateTime $completed
     */
    public function setCompleted(DateTime $completed): void
    {
        $this->completed = $completed;
    }

    /**
     * @return DateTime
     */
    public function getReleased(): DateTime
    {
        return $this->released;
    }

    /**
     * @param DateTime $released
     */
    public function setReleased(DateTime $released): void
    {
        $this->released = $released;
    }

    /**
     * @return DateTime
     */
    public function getArchived(): ?DateTime
    {
        return $this->archived;
    }

    /**
     * @param DateTime $archived
     */
    public function setArchived(DateTime $archived): void
    {
        $this->archived = $archived;
    }

    public function getMasterUser(): ?User
    {
        return $this->masterUser;
    }

    public function setMasterUser(?User $master_usr): self
    {
        $this->masterUser = $master_usr;

        return $this;
    }

    public function getDiffCriteria(): ?bool
    {
        return $this->diffCriteria;
    }

    public function setDiffCriteria(bool $diffCriteria): self
    {
        $this->diffCriteria = $diffCriteria;

        return $this;
    }

    public function getDiffParticipants(): ?bool
    {
        return $this->diffParticipants;
    }

    public function setDiffParticipants(bool $diffParticipants): self
    {
        $this->diffParticipants = $diffParticipants;

        return $this;
    }

    public function getNbParticipants(): ?int
    {
        return $this->nbParticipants;
    }

    public function setNbParticipants(int $nbParticipants): self
    {
        $this->nbParticipants = $nbParticipants;

        return $this;
    }
    public function getStartdateDay(): int
    {
        $startDate = new DateTime('2099-01-01');
        foreach ($this->stages as $stage) {
            if ($stage->getStartdate() < $startDate) {
                $startDate = $stage->getStartDate();
            }
        }
        $year=$startDate->format('Y');
        $month=$startDate->format('m');
        $day=$startDate->format('d');
        return (int) date("z",mktime("12","00","00",(int)$month,(int)$day,(int)$year));
    }
    public function getPeriod(): void
    {

        $startDate = new DateTime('2099-01-01');
        $enddate = new DateTime('2000-01-01');

        foreach ($this->stages as $stage) {
            if ($stage->getStartdate() < $startDate) {
                $startDate = $stage->getStartDate();
            }
            if ($stage->getEnddate() > $enddate) {
                $enddate = $stage->getEnddate();
            }
        }

        $diff = $startDate->diff($enddate)->format("%a");
        echo $diff;


    }

    public function getEnddate()
    {
        $endDate = new DateTime('2000-01-01');
        foreach ($this->getStages() as $stage) {
            if ($stage->getEnddate() > $endDate) {
                $endDate = $stage->getEnddate();
            }
        }
        return $endDate;
    }
    /**
     * @return ArrayCollection|Stage[]
     */
    public function getActiveStages()
    {
        $activeStages = new ArrayCollection;
        $concernedStages = $this->stages->filter(
            static function (Stage $s) { return $s->getStatus() < 2; }
        );
        foreach($concernedStages as $concernedStage){
            $activeStages->add($concernedStage);
        }
        return $activeStages;
    }

    /**
     * @param User $u
     * @return ArrayCollection|Stage[]
     */
    public function getUserActiveGradableStages(User $u)
    {
        // We retrieve all stages where current user can grade
        return $this->getActiveStages()->filter(static function(Stage $s) use ($u){
            $participations = $s->getIntParticipants();
            return $participations && $participations->exists(static function(int $i, Participation $p) use ($s, $u){
                    return ($p->getUser() == $u) && ($s->getGradableParticipants()->count() > 0);
                });
        });
    }
    /**
     * @return ArrayCollection|Stage[]
     */
    public function getActiveGradableStages()
    {
        $activeGradableStages = new ArrayCollection;
        $activeStages = $this->getActiveStages();
        foreach ($activeStages as $activeStage) {
            if ($activeStage->getUserGradableParticipations() !== null || $activeStage->getTeamGradableParticipations() !== null) {
                $activeGradableStages->add($activeStage);
            }
        }
        return $activeGradableStages;
    }

    public function getCompletedStagesWithSurvey()
    {
        return $this->getOCompletedStages()->filter(static function (Stage $s) {
            return $s->getSurvey();
        });
    }
    /**
     * @return ArrayCollection|Stage[]
     */
    public function getOCompletedStages()
    {
        $stages = $this->stages;
        return $stages->filter(static function (Stage $stage) {
            return $stage->getStatus() >= STAGE::STAGE_COMPLETED;
        });
    }

    /**
     * @return ArrayCollection|Stage[]
     */
    public function getPCompletedStages()
    {
        $stages = $this->stages;
        return $stages->filter(static function (Stage $stage) {
            return $stage->getProgress() >= STAGE::PROGRESS_COMPLETED;
        });
    }

    /**
     * @return Stage
     */
    public function getCurrentStage(): Stage
    {
        $today = new DateTime('now');

        foreach ($this->getStages() as $stage) {
            if ($stage->getStartdate() < $today && $today < $stage->getEnddate()) {
                break;
            }
        }

        return $stage;
    }
    public function addRanking(Ranking $ranking): Activity
    {
        $this->rankings->add($ranking);
        $ranking->setActivity($this);
        return $this;
    }
    public function removeRanking(Ranking $ranking): Activity
    {
        $this->rankings->removeElement($ranking);
        return $this;
    }

    public function addHistoricalRanking(RankingHistory $historicalRanking): Activity
    {
        $this->historicalRankings->add($historicalRanking);
        $historicalRanking->setActivity($this);
        return $this;
    }

    public function removeHistoricalRanking(RankingHistory $ranking): Activity
    {
        $this->rankings->removeElement($ranking);
        return $this;
    }

    public function addResultTeam(ResultTeam $resultTeam): Activity
    {
        $this->resultTeams->add($resultTeam);
        $resultTeam->setActivity($this);
        return $this;
    }

    public function removeResultTeam(ResultTeam $resultTeam): Activity
    {
        $this->resultTeams->removeElement($resultTeam);
        return $this;
    }

    public function addRankingTeam(RankingTeam $rankingTeam): Activity
    {
        $this->rankingTeams->add($rankingTeam);
        $rankingTeam->setActivity($this);
        return $this;
    }

    public function removeRankingTeam(RankingTeam $rankingTeam): Activity
    {
        $this->rankingTeams->removeElement($rankingTeam);
        return $this;
    }

    public function addHistoricalRankingTeam(RankingTeamHistory $historicalRankingTeam): Activity
    {
        $this->historicalRankingTeams->add($historicalRankingTeam);
        $historicalRankingTeam->setActivity($this);
        return $this;
    }

    public function removeHistoricalRankingTeam(RankingTeamHistory $historicalRankingTeam): Activity
    {
        $this->historicalRankingTeams->removeElement($historicalRankingTeam);
        return $this;
    }
    public function isSimplified(): bool
    {
        return $this->simplified;
    }

    public function getLatestStage()
    {
        $returnedStage = $this->stages->first();
        foreach ($this->stages as $stage) {
            if ($stage->getEnddate() > $returnedStage) {
                $returnedStage = $stage;
            }
        }
        return $returnedStage;
    }

    public function hasParticipants(): bool
    {
        foreach ($this->getActiveStages() as $stage) {
            if (count($stage->getParticipants()) > 0) {
                return true;
            }
        }
        return false;
    }

    public function hasParticipant(User $u)
    {
        return $this->participants->exists(
            static function (int $i, Participation $p) use ($u) { return $p->getUser() == $u; }
        );
    }

    public function userIsGrader(User $u): bool
    {
        return $this->getStages()->exists(static function(int $i, Stage $s) use ($u) {
            $graders = $s->getGraderUsers();

            return $graders->exists(static function (int $i, User $p) use ($u) {
                return $p === $u;
            });
        });
    }

    public function userCanGiveOutput(User $u): bool
    {
        return $this->getStages()->exists(static function(int $i, Stage $s) use ($u) {
            return $s->userCanGiveOutput($u);
        });
        //return $this->status == self::STATUS_ONGOING && $this->userIsGrader($u);
    }

    public function userCanSeeOutput(User $u): bool
    {
        return $this->getStages()->exists(static function(int $i, Stage $s) use ($u) {
            return $s->userCanSeeOutput($u);
        });
    }

    public function userCanAnswerSurvey(User $u): bool
    {
        return $this->status == self::STATUS_ONGOING
            && $this->getStages()->exists(static function(int $i, Stage $s) use ($u) {
                $survey = $s->getSurvey();

                if (!$survey) {
                    return false;
                }

                return $survey->getParticipants()->exists(static function (int $i, Participation $p) use ($u) {
                    if($p->getUser() === $u){
                        return $p->getStatus() == 2 || $p->getStatus() == 1;
                    }



                });
            });
    }
    public function userCanSendAnswerSurvey(User $u): bool
    {
        return $this->status == self::STATUS_ONGOING
            && $this->getStages()->exists(function(int $i,Stage $s) use ($u) {
                $survey = $s->getSurvey();

                if (!$survey) {
                    return false;
                }

                return $survey->getParticipants()->exists(function (int $i,Participation $p) use ($u) {
                    if($p->getUser() === $u){
                        if($p->getStatus()!=2){
                            return false;
                        }
                        return !empty($p->getAnswers());
                    }

                    return false;
                });
            });
    }

    public function userCanViewResults(User $u)
    {

        return $this->userCanSeeResults($u);
    }

    public function userCanAccessInfo(User $u): bool
    {
        return true;
    }

    //TODO fix le coup du subordinate
    public function userCanSeeResults(User $u): bool
    {

        if ($this->status < self::STATUS_FINALIZED) {

            return false;
        }

        $role = $u->getRole();

        if ($role == 4) {
            return true;
        }

        if (!$this->stages->exists(static function (int $i, Stage $s) { return $s->getCriteria()->count(); })) {
            // no stage with criteria. if none of them got replies (as they are surveys)
            // results are not available


            if (

            $this->stages->forAll(static function (int $i, Stage $s) {
                $survey = $s->getSurvey();
                if (!$survey) {

                    return false;
                }

                return $survey->getAnswers()->isEmpty();
            })
            ) {


                return false;
            }
        }

        if (($role == 1) && $u->getOrganization() != $this->getOrganization()) {
            return false;
        }

        if ($this->hasParticipant($u)) {
            return true;
        }
        $subordinates = $u->getSubordinatesRecursive();
        $department = $u->getDepartment();
        $departmentUsers = $department ? $department->getUsers() : [];

        foreach ($this->participants as $p) {
            if ($p->getType() != self::STATUS_FUTURE) {
                if (in_array($p->getDirectUser(), $subordinates)) {
                    return true;
                } elseif (in_array($p->getDirectUser(), $departmentUsers) and $this->status == self::STATUS_PUBLISHED) {
                    return true;
                }
            }
        }

        return false;
    }
    public function userCanSeeDetailedFeedback(User $u): bool
    {
        $canSeeResults = $this->userCanSeeResults($u);
        $role = $u->getRole();

        if ($role == 4 || $role == 1) {
            return $canSeeResults;
        }

        return $canSeeResults && $this->participants->exists(
                static function (int $i, Participation $p) use ($u) { return $p->getUser() == $u && $p->isLeader(); }
            );
    }

    public function isComplete(){
        return $this->getActiveStages()->exists(static function(int $i, Stage $s){
            return $s->isComplete();
        });
    }
    public function getIndependantUniqueParticipatingClients(): ArrayCollection
    {
        $actIndependantUniqueParticipatingClients = new ArrayCollection;
        $activityIndependantParticipatingClients = [];
        foreach($this->stages as $stage){
            foreach($stage->getIndependantUniqueParticipatingClients() as $client){
                $activityIndependantParticipatingClients[] = $client;
            }
        }
        $clientIds = [];
        foreach($activityIndependantParticipatingClients as $activityIndependantParticipatingClient){
            if(in_array($activityIndependantParticipatingClient->getId(), $clientIds, true) === false){
                $actIndependantUniqueParticipatingClients->add($activityIndependantParticipatingClient);
            }
        }
        return $actIndependantUniqueParticipatingClients;
    }

    public function addStage(Stage $stage): self
    {
        $this->stages->add($stage);
        $stage->setActivity($this);
        return $this;
    }

    public function removeStage(Stage $stage): Activity
    {
        $this->stages->removeElement($stage);
        return $this;
    }

    public function hasFeedbackExpired(): bool
    {
        return $this->getActiveModifiableStages()->forAll(static function(int $i, Stage $s){
            return $s->hasFeedbackExpired() === 1;
        });
    }

    public function hasMinimumOutputConfig(): bool
    {
        return $this->getActiveModifiableStages()->forAll(static function(int $i, Stage $s){
            return $s->hasMinimumOutputConfig() === 1;
        });
    }

    public function hasMinimumParticipationConfig(): bool
    {
        return $this->getActiveModifiableStages()->forAll(function(int $i, Stage $s){
            $stageM = new StageM($this->em, $this->requestStack, $this->security);
            return $stageM->hasMinimumParticipationConfig($s) === 1;
        });
    }


    public function getActiveConfiguredStages(){
        return $this->getActiveModifiableStages()->filter(static function(Stage $s){
            return $s->hasMinimumOutputConfig() === 1 && $s->hasMinimumParticipationConfig() === 1;
        });
    }


    /**
     * @return ArrayCollection|Stage[]
     */
    public function getActiveModifiableStages()
    {
        if ($this->currentUser === null) {
            return new ArrayCollection();
        }

        $activeStages = $this->getActiveStages();
        foreach ($activeStages as $activeStage) {
            $activeStage->currentUser = $this->currentUser;
            if (!$activeStage->isModifiable($activeStage)) {
                $activeStages->removeElement($activeStage);
            }
        }
        return $activeStages;
    }

    public function addActiveModifiableStage(Stage $stage)
    {
        if (!$stage->getActivity()) {
            $this->stages->add($stage);
            $stage->setActivity($this);
        }
        return $this;
    }

    public function removeActiveModifiableStage(Stage $stage)
    {
        $this->stages->removeElement($stage);
        return $this;
    }



    /**
    * @return ArrayCollection|Event[]
    */
    public function getEvents()
    {
        return $this->events;
    }

    public function addEvent(Event $event): Activity
    {
        $this->events->add($event);
        $event->setActivity($this);
        return $this;
    }

    public function removeEvent(Event $event): Activity
    {
        $this->events->removeElement($event);
        return $this;
    }

}
