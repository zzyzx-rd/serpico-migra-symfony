<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\IProcessStageRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=IProcessStageRepository::class)
 */
class IProcessStage
{
    const STAGE_UNSTARTED    = 0;
    const STAGE_ONGOING      = 1;
    const STAGE_COMPLETED  = 2;
    const STAGE_PUBLISHED    = 3;

    const VISIBILITY_PRIVATE = 0;
    const VISIBILITY_UNLISTED = 1;
    const VISIBILITY_PUBLIC  = 2;
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
    private $stg_mod;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_visibility;

    /**
     * @ORM\Column(type="float")
     */
    private $stg_status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_desc;

    /**
     * @ORM\Column(type="float")
     */
    private $stg_progress;

    /**
     * @ORM\Column(type="float")
     */
    private $stg_weight;

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
     * @ORM\Column(type="string", length=255)
     */
    private $stg_ffrequency;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_forigin;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stg_definite_dates;

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
    private $stg_deadline_nbDays;

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
    private $stg_isFinalized;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_finalized;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_deleted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_gcompleted;

    /**
     * @ManyToOne(targetEntity="InstitutionProcess")
     * @JoinColumn(name="iprocess_inp_id", referencedColumnName="inp_id",nullable=false)
     * @var InstitutionProcess
     */
    protected $institutionProcess;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=true)
     * @var Organization
     */
    protected $organization;

    /**
     * @OneToMany(targetEntity="IProcessCriterion", mappedBy="stage", cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"weight" = "DESC"})
     */
    private $criteria;

    /**
     * @OneToMany(targetEntity="IProcessActivityUser", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
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

    public function getStgMod(): ?int
    {
        return $this->stg_mod;
    }

    public function setStgMod(int $stg_mod): self
    {
        $this->stg_mod = $stg_mod;

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

    public function getStgStatus(): ?float
    {
        return $this->stg_status;
    }

    public function setStgStatus(float $stg_status): self
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

    public function getStgProgress(): ?float
    {
        return $this->stg_progress;
    }

    public function setStgProgress(float $stg_progress): self
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

    public function getStgDefiniteDates(): ?bool
    {
        return $this->stg_definite_dates;
    }

    public function setStgDefiniteDates(bool $stg_definite_dates): self
    {
        $this->stg_definite_dates = $stg_definite_dates;

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

    public function getStgDeadlineNbDays(): ?int
    {
        return $this->stg_deadline_nbDays;
    }

    public function setStgDeadlineNbDays(int $stg_deadline_nbDays): self
    {
        $this->stg_deadline_nbDays = $stg_deadline_nbDays;

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

    public function getStgIsFinalized(): ?bool
    {
        return $this->stg_isFinalized;
    }

    public function setStgIsFinalized(bool $stg_isFinalized): self
    {
        $this->stg_isFinalized = $stg_isFinalized;

        return $this;
    }

    public function getStgFinalized(): ?\DateTimeInterface
    {
        return $this->stg_finalized;
    }

    public function setStgFinalized(\DateTimeInterface $stg_finalized): self
    {
        $this->stg_finalized = $stg_finalized;

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
     * @return InstitutionProcess
     */
    public function getInstitutionProcess(): InstitutionProcess
    {
        return $this->institutionProcess;
    }

    /**
     * @param InstitutionProcess $institutionProcess
     */
    public function setInstitutionProcess(InstitutionProcess $institutionProcess): void
    {
        $this->institutionProcess = $institutionProcess;
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
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param mixed $criteria
     */
    public function setCriteria($criteria): void
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

}
