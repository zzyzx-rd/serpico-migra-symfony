<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ActivityRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 */
class Activity
{
    const STATUS_CANCELLED = -5;
    const STATUS_REJECTED = -4;
    const STATUS_REQUESTED = -3;
    const STATUS_AWAITING_CREATION = -2;
    const STATUS_INCOMPLETE = -1;
    const STATUS_FUTURE = 0;
    const STATUS_ONGOING = 1;
    const STATUS_FINALIZED = 2;
    const STATUS_PUBLISHED = 3;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @Column(name="act_id", type="integer", nullable=false)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $act_complete;

    /**
     * @ORM\Column(type="integer")
     */
    private $act_master_usr_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $act_magnitude;

    /**
     * @ORM\Column(type="boolean")
     */
    private $act_simplified;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $act_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $act_visibility;

    /**
     * @ORM\Column(type="datetime")
     */
    private $act_startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $act_endDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $act_objectives;

    /**
     * @ORM\Column(type="integer")
     */
    private $act_status;

    /**
     * @ORM\ManyToOne(targetEntity=Process::class, inversedBy="activities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $process;

    /**
     * @ORM\ManyToOne(targetEntity=InstitutionProcess::class, inversedBy="activities")
     */
    private $institutionProcess;

    /**
     * @ORM\ManyToOne(targetEntity=Organization::class, inversedBy="activities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organization;
    /**
     * @OneToMany(targetEntity="Stage", mappedBy="activity", cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"startdate" = "ASC", "inserted" = "ASC"})
     */
    private $stages;
    /**
     * @OneToMany(targetEntity="Decision", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $decisions;
    /**
     * @OneToMany(targetEntity="Grade", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Grade[]
     */
    private $grades;
    /**
     * @OneToMany(targetEntity="Result", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $results;
    /**
     * @OneToMany(targetEntity="ResultProject", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $projectResults;
    /**
     * @OneToMany(targetEntity="ResultTeam", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $resultTeams;
    /**
     * @OneToMany(targetEntity="Ranking", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $rankings;
    /**
     * @OneToMany(targetEntity="RankingTeam", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $rankingTeams;
    /**
     * @OneToMany(targetEntity="RankingHistory", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $historicalRankings;
    /**
     * @OneToMany(targetEntity="RankingTeamHistory", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $historicalRankingTeams;
    /**
     * @ManyToOne(targetEntity="TemplateActivity")
     * @JoinColumn(name="template_activity_act_id", referencedColumnName="act_id",nullable=true)
     */
    private $template;
    /**
     * @OneToMany(targetEntity="ActivityUser", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"team" = "ASC"})
     * @var ArrayCollection|ActivityUser[]
     */
    private $participants;
    /**
     * @Column(name="act_progress", type="float")
     * @var float
     */
    protected $progress;
    /**
     * @Column(name="act_isRewarding", type="boolean")
     * @var bool
     */
    protected $isRewarding;
    /**
     * @Column(name="act_distrAmount", length=10, type="float")
     * @var float
     */
    protected $distrAmount;
    /**
     * @Column(name="act_res_inertia", length= 10, type="float")
     * @var int
     */
    protected $res_inertia;
    /**
     * @Column(name="act_res_benefit_eff", length= 10, type="float")
     * @var int
     */
    protected $res_benefit_eff;
    /**
     * @Column(name="act_createdBy", type="integer")
     * @var int
     */
    protected $createdBy;
    /**
     * @Column(name="act_inserted", type="datetime")
     * @var DateTime
     */
    protected $inserted;
    /**
     * @Column(name="act_saved", type="datetime")
     * @var DateTime
     */
    protected $saved;
    /**
     * @Column(name="act_isFinalized", type="boolean")
     * @var bool
     */
    protected $isFinalized;
    /**
     * @Column(name="act_finalized", type="datetime", nullable=true)
     * @var DateTime
     */
    protected $finalized;
    /**
     * @Column(name="act_deleted", type="datetime")
     * @var DateTime
     */
    protected $deleted;
    /**
     * @Column(name="act_completed", type="datetime")
     * @var DateTime
     */
    protected $completed;
    /**
     * @Column(name="act_released", type="datetime")
     * @var DateTime
     */
    protected $released;
    /**
     * @Column(name="act_archived", type="datetime")
     * @var DateTime
     */
    protected $archived;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $act_master_usr;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActComplete(): ?bool
    {
        return $this->act_complete;
    }

    public function setActComplete(bool $act_complete): self
    {
        $this->act_complete = $act_complete;

        return $this;
    }

    public function getActMasterUsrId(): ?int
    {
        return $this->act_master_usr_id;
    }

    public function setActMasterUsrId(int $act_master_usr_id): self
    {
        $this->act_master_usr_id = $act_master_usr_id;

        return $this;
    }

    public function getActMagnitude(): ?int
    {
        return $this->act_magnitude;
    }

    public function setActMagnitude(int $act_magnitude): self
    {
        $this->act_magnitude = $act_magnitude;

        return $this;
    }

    public function getActSimplified(): ?bool
    {
        return $this->act_simplified;
    }

    public function setActSimplified(bool $act_simplified): self
    {
        $this->act_simplified = $act_simplified;

        return $this;
    }

    public function getActName(): ?string
    {
        return $this->act_name;
    }

    public function setActName(string $act_name): self
    {
        $this->act_name = $act_name;

        return $this;
    }

    public function getActVisibility(): ?string
    {
        return $this->act_visibility;
    }

    public function setActVisibility(string $act_visibility): self
    {
        $this->act_visibility = $act_visibility;

        return $this;
    }

    public function getActStartDate(): ?\DateTimeInterface
    {
        return $this->act_startDate;
    }

    public function setActStartDate(\DateTimeInterface $act_startDate): self
    {
        $this->act_startDate = $act_startDate;

        return $this;
    }

    public function getActEndDate(): ?\DateTimeInterface
    {
        return $this->act_endDate;
    }

    public function setActEndDate(\DateTimeInterface $act_endDate): self
    {
        $this->act_endDate = $act_endDate;

        return $this;
    }

    public function getActObjectives(): ?string
    {
        return $this->act_objectives;
    }

    public function setActObjectives(string $act_objectives): self
    {
        $this->act_objectives = $act_objectives;

        return $this;
    }

    public function getActStatus(): ?int
    {
        return $this->act_status;
    }

    public function setActStatus(int $act_status): self
    {
        $this->act_status = $act_status;

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
     * @return ActivityUser[]|ArrayCollection
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param ActivityUser[]|ArrayCollection $participants
     */
    public function setParticipants($participants): void
    {
        $this->participants = $participants;
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

    /**
     * @return int
     */
    public function getCreatedBy(): int
    {
        return $this->createdBy;
    }

    /**
     * @param int $createdBy
     */
    public function setCreatedBy(int $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return DateTime
     */
    public function getInserted(): DateTime
    {
        return $this->inserted;
    }

    /**
     * @param DateTime $inserted
     */
    public function setInserted(DateTime $inserted): void
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
     */
    public function setDeleted(DateTime $deleted): void
    {
        $this->deleted = $deleted;
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
    public function getArchived(): DateTime
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

    public function getActMasterUsr(): ?User
    {
        return $this->act_master_usr;
    }

    public function setActMasterUsr(?User $act_master_usr): self
    {
        $this->act_master_usr = $act_master_usr;

        return $this;
    }

}
