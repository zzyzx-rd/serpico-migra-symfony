<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProcessStageRepository;
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
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ProcessStageRepository::class)
 */
class ProcessStage extends DbObject
{
    public const STATUS_UNSTARTED    = 0;
    public const STATUS_ONGOING      = 1;
    public const STATUS_COMPLETED  = 2;
    public const STATUS_PUBLISHED    = 3;

    public const VISIBILITY_PUBLIC  = 1;
    public const VISIBILITY_UNLISTED = 2;
    public const VISIBILITY_public = 3;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="stg_id", type="integer",nullable=false, length=10)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="stg_complete", type="boolean", nullable=true)
     */
    public $complete;

    /**
     * @ORM\Column(name="stg_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="stg_mode", type="string", length=255, nullable=true)
     */
    public $mode;

    /**
     * @ORM\Column(name="stg_visibility", type="integer", nullable=true)
     */
    public $visibility;

    /**
     * @ORM\Column(name="stg_definite_dates", type="boolean", nullable=true)
     */
    public $definiteDates;

    /**
     * @ORM\Column(name="stg_status", type="float", nullable=true)
     */
    public $status;

    /**
     * @ORM\Column(name="stg_desc", type="string", length=255, nullable=true)
     */
    public $desc;

    /**
     * @ORM\Column(name="stg_progress", type="float", nullable=true)
     */
    public $progress;

    /**
     * @ORM\Column(name="stg_weight", type="string", length=255, nullable=true)
     */
    public $weight;

    /**
     * @ORM\Column(name="stg_dperiod", type="integer", nullable=true)
     */
    public $dperiod;

    /**
     * @ORM\Column(name="stg_dfrequency", type="string", length=255, nullable=true)
     */
    public $dfrequency;

    /**
     * @ORM\Column(name="stg_dorigin", type="integer", nullable=true)
     */
    public $dorigin;

    /**
     * @ORM\Column(name="stg_fperiod", type="integer", nullable=true)
     */
    public $fperiod;

    /**
     * @ORM\Column(name="stg_ffrequency", type="string", length=255, nullable=true)
     */
    public $ffrequency;

    /**
     * @ORM\Column(name="stg_forigin", type="integer", nullable=true)
     */
    public $forigin;

    /**
     * @ORM\Column(name="stg_startdate", type="datetime", nullable=true)
     */
    public $startdate;

    /**
     * @ORM\Column(name="stg_enddated", type="datetime", nullable=true)
     */
    public $enddated;



    /**
     * @ORM\Column(name="stg_dealine_nbDays", type="integer", nullable=true)
     */
    public $dealineNbDays;

    /**
     * @ORM\Column(name="stg_deadline_mailSent", type="boolean", nullable=true)
     */
    public $stgDeadlineMailSent;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="processStageInitiatives")
     * @JoinColumn(name="stg_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\Column(name="stg_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="stg_isFinalized", type="datetime", nullable=true)
     */
    public $isFinalized;

    /**
     * @Column(name="stg_finalized", type="datetime")
     * @var DateTime
     */
    protected $finalized;

    /**
     * @ORM\Column(name="stg_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @ORM\Column(name="stg_dcompleted", type="datetime", nullable=true)
     */
    public $dcompleted;

    /**
     * @ManyToOne(targetEntity="Process", inversedBy="stages")
     * @JoinColumn(name="process_pro_id", referencedColumnName="pro_id",nullable=false)
     */
    protected $process;
    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=true)
     * @var Organization
     */
    protected $organization;

    /**
     * @OneToMany(targetEntity="ProcessCriterion", mappedBy="stage", cascade={"persist", "remove"}, orphanRemoval=true)
     */
//     * @OrderBy({"weight" = "DESC"})
    public $criteria;

    /**
     * @OneToMany(targetEntity="IProcessParticipation", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
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
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="stg_master_usr_id", referencedColumnName="usr_id", nullable=false)
     */
    public $master_usr;

    /**
     * ProcessStage constructor.
     * @param ?int$id
     * @param $stg_complete
     * @param $stg_name
     * @param $stg_mode
     * @param $stg_visibility
     * @param $stg_definite_dates
     * @param $stg_status
     * @param $stg_desc
     * @param $stg_progress
     * @param $stg_weight
     * @param $stg_dperiod
     * @param $stg_dfrequency
     * @param $stg_dorigin
     * @param $stg_fperiod
     * @param $stg_ffrequency
     * @param $stg_forigin
     * @param $stg_startdate
     * @param $stg_enddated
     * @param $stg_dealine_nbDays
     * @param $stg_deadline_mailSent
     * @param $stg_isFinalized
     * @param $stg_deleted
     * @param $stg_dcompleted
     * @param $process
     * @param Organization $organization
     * @param $criteria
     * @param $participants
     * @param $decisions
     * @param $grades
     * @param $results
     * @param $resultTeams
     * @param $rankings
     * @param $rankingTeams
     * @param $historicalRankings
     * @param $historicalRankingTeams
     * @param $stg_master_usr
     */
    public function __construct(
      ?int $id = 0,
        $stg_complete = false,
        $stg_visibility = 3,
        $stg_definite_dates = false,
        $stg_master_usr = null,
        $stg_name = '',
        $stg_mode = null,
        $stg_status = 0,
        $stg_desc = '',
        $stg_progress = 0.0,
        $stg_weight = 0.0,
        $stg_dperiod = null,
        $stg_dfrequency = null,
        $stg_dorigin = null,
        $stg_fperiod = null,
        $stg_ffrequency = null,
        $stg_forigin = null,
        $stg_startdate = null,
        $stg_enddated = null,
        $stg_dealine_nbDays = 3,
        $stg_deadline_mailSent = null,
        $stg_isFinalized = false,
        $stg_deleted = null,
        $process = null,
        $stg_dcompleted = null,
        Organization $organization = null,
        Collection $criteria = null,
        $participants = null,
        $decisions = null,
        $grades = null,
        $results = null,
        $resultTeams = null,
        $rankings = null,
        $rankingTeams = null,
        $historicalRankings = null,
        $historicalRankingTeams = null)
    {
        parent::__construct($id, null, new DateTime());
        $this->complete = $stg_complete;
        $this->name = $stg_name;
        $this->mode = $stg_mode;
        $this->visibility = $stg_visibility;
        $this->definiteDates = $stg_definite_dates;
        $this->status = $stg_status;
        $this->desc = $stg_desc;
        $this->progress = $stg_progress;
        $this->weight = $stg_weight;
        $this->dperiod = $stg_dperiod;
        $this->dfrequency = $stg_dfrequency;
        $this->dorigin = $stg_dorigin;
        $this->fperiod = $stg_fperiod;
        $this->ffrequency = $stg_ffrequency;
        $this->forigin = $stg_forigin;
        $this->startdate = $stg_startdate;
        $this->enddated = $stg_enddated;
        $this->dealineNbDays = $stg_dealine_nbDays;
        $this->stgDeadlineMailSent = $stg_deadline_mailSent;
        $this->isFinalized = $stg_isFinalized;
        $this->deleted = $stg_deleted;
        $this->dcompleted = $stg_dcompleted;
        $this->process = $process;
        $this->organization = $organization;
        $this->criteria = $criteria?:new ArrayCollection();
        $this->participants = $participants?:new ArrayCollection();
        $this->decisions = $decisions?:new ArrayCollection();
        $this->grades = $grades?:new ArrayCollection();
        $this->results = $results?:new ArrayCollection();
        $this->resultTeams = $resultTeams?:new ArrayCollection();
        $this->rankings = $rankings?:new ArrayCollection();
        $this->rankingTeams = $rankingTeams?:new ArrayCollection();
        $this->historicalRankings = $historicalRankings?:new ArrayCollection();
        $this->historicalRankingTeams = $historicalRankingTeams?:new ArrayCollection();
        $this->master_usr = $stg_master_usr;
    }


    public function getComplete(): ?bool
    {
        return $this->complete;
    }

    public function setComplete(bool $stg_complete): self
    {
        $this->complete = $stg_complete;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $stg_name): self
    {
        $this->name = $stg_name;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $stg_mode): self
    {
        $this->mode = $stg_mode;

        return $this;
    }

    public function getVisibility(): ?int
    {
        return $this->visibility;
    }

    public function setVisibility(int $stg_visibility): self
    {
        $this->visibility = $stg_visibility;

        return $this;
    }

    public function getDefiniteDates(): ?bool
    {
        return $this->definiteDates;
    }

    public function setDefiniteDates(bool $stg_definite_dates): self
    {
        $this->definiteDates = $stg_definite_dates;

        return $this;
    }

    public function getStatus(): ?float
    {
        return $this->status;
    }

    public function setStatus(float $stg_status): self
    {
        $this->status = $stg_status;

        return $this;
    }

    public function getDesc(): ?string
    {
        return $this->desc;
    }

    public function setDesc(string $stg_desc): self
    {
        $this->desc = $stg_desc;

        return $this;
    }

    public function getProgress(): ?float
    {
        return $this->progress;
    }

    public function setProgress(float $stg_progress): self
    {
        $this->progress = $stg_progress;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(string $stg_weight): self
    {
        $this->weight = $stg_weight;

        return $this;
    }

    public function getDperiod(): ?int
    {
        return $this->dperiod;
    }

    public function setDperiod(int $stg_dperiod): self
    {
        $this->dperiod = $stg_dperiod;

        return $this;
    }

    public function getDfrequency(): ?string
    {
        return $this->dfrequency;
    }

    public function setDfrequency(string $stg_dfrequency): self
    {
        $this->dfrequency = $stg_dfrequency;

        return $this;
    }

    public function getDorigin(): ?int
    {
        return $this->dorigin;
    }

    public function setDorigin(int $stg_dorigin): self
    {
        $this->dorigin = $stg_dorigin;

        return $this;
    }

    public function getFperiod(): ?int
    {
        return $this->fperiod;
    }

    public function setFperiod(int $stg_fperiod): self
    {
        $this->fperiod = $stg_fperiod;

        return $this;
    }

    public function getFfrequency(): ?string
    {
        return $this->ffrequency;
    }

    public function setFfrequency(string $stg_ffrequency): self
    {
        $this->ffrequency = $stg_ffrequency;

        return $this;
    }

    public function getForigin(): ?int
    {
        return $this->forigin;
    }

    public function setForigin(int $stg_forigin): self
    {
        $this->forigin = $stg_forigin;

        return $this;
    }

    public function getStartdate(): ?DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(DateTimeInterface $stg_startdate): self
    {
        $this->startdate = $stg_startdate;

        return $this;
    }

    public function getEnddated(): ?DateTimeInterface
    {
        return $this->enddated;
    }

    public function setEnddated(DateTimeInterface $stg_enddated): self
    {
        $this->enddated = $stg_enddated;

        return $this;
    }


    public function getDealineNbDays(): ?int
    {
        return $this->dealineNbDays;
    }

    public function setDealineNbDays(int $stg_dealine_nbDays): self
    {
        $this->dealineNbDays = $stg_dealine_nbDays;

        return $this;
    }

    public function getDeadlineMailSent(): ?bool
    {
        return $this->stgDeadlineMailSent;
    }

    public function setDeadlineMailSent(?bool $stg_deadline_mailSent): self
    {
        $this->stgDeadlineMailSent = $stg_deadline_mailSent;

        return $this;
    }

    public function setInserted(?DateTimeInterface $stg_inserted): self
    {
        $this->inserted = $stg_inserted;

        return $this;
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
     * @return ProcessStage
     */
    public function setIsFinalized(bool $isFinalized): ProcessStage
    {
        $this->isFinalized = $isFinalized;
        return $this;
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
     * @return ProcessStage
     */
    public function setFinalized(DateTime $finalized): ProcessStage
    {
        $this->finalized = $finalized;
        return $this;
    }


    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(DateTimeInterface $stg_deleted): self
    {
        $this->deleted = $stg_deleted;

        return $this;
    }

    public function getDcompleted(): ?DateTimeInterface
    {
        return $this->dcompleted;
    }

    public function setDcompleted(DateTimeInterface $stg_dcompleted): self
    {
        $this->dcompleted = $stg_dcompleted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param mixed $process
     */
    public function setProcess($process): void
    {
        $this->process = $process;
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
    public function getCriteria()
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

    public function getMasterUsr(): ?User
    {
        return $this->master_usr;
    }

    public function setMasterUsr(?User $stg_master_usr): self
    {
        $this->master_usr = $stg_master_usr;

        return $this;
    }
    public function getUniqueParticipations()
    {
        /** @var Criterion */
        $criterion = $this->criteria->first();
        return $criterion->getParticipants();
    }
    public function addGrade(Grade $grade): ProcessStage
    {
        $this->grades->add($grade);
        return $this;
    }

    public function removeGrade(Grade $grade): ProcessStage
    {
        $this->grades->removeElement($grade);
        return $this;
    }
    public function addCriterion(ProcessCriterion $criterion): ProcessStage
    {
        //The below line is to prevent adding a criterion already submitted (because of activeStages/stages).
        // However as stage criteria are built in advance for recurring activities, we also need to take into account this exception

        //if(!$criterion->getStage() || $criterion->getStage()->getProcess()->getRecurring()){
        $this->criteria->add($criterion);
        $criterion->setStage($this);
        return $this;
        //}
    }

    public function removeCriterion(ProcessCriterion $criterion): ProcessStage
    {
        $this->criteria->removeElement($criterion);
        $criterion->setStage(null);
        return $this;
    }

    public function addParticipant(IProcessParticipation $participant): ProcessStage
    {

        $this->participants->add($participant);
        $participant->setStage($this);
        return $this;
    }

    public function removeParticipant(IProcessParticipation $participant): ProcessStage
    {
        $this->participants->removeElement($participant);
        return $this;
    }

    public function addUniqueParticipation(IProcessParticipation $participant): ProcessStage
    {
        foreach($this->criteria as $criterion){
            $criterion->addParticipant($participant);
            $participant->setCriterion($criterion)->setStage($this)->setProcess($this->getProcess());
        }
        return $this;
    }

    public function removeUniqueParticipation(IProcessParticipation $participant): ProcessStage
    {
        foreach($this->criteria as $criterion){
            $criterion->getParticipants()->removeElement($participant);
        }
        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }
    /**
     * @return ArrayCollection|User[]
     */
    public function getGraderUsers(){
        $graderUsers = new ArrayCollection;
        $uniqueGraderParticipations = $this->getUniqueGraderParticipations();
        foreach($uniqueGraderParticipations as $uniqueGraderParticipation){
            $graderUsers->add($uniqueGraderParticipation->getDirectUser());
        }
        return $graderUsers;
    }


    // Defines nb of evaluating criteria
    /**
     * @return int
     */
    public function getNbEvaluatingCriteria(): int
    {
        return count($this->getCriteria()->matching(Criteria::create()->where(Criteria::expr()->eq("type", 1))));
    }

    public function hasCompletedOutput(): bool
    {
        return false;
    }
}
