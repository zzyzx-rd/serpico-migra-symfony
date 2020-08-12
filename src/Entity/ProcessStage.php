<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProcessStageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
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
    const STAGE_UNSTARTED    = 0;
    const STAGE_ONGOING      = 1;
    const STAGE_COMPLETED  = 2;
    const STAGE_PUBLISHED    = 3;

    const VISIBILITY_PUBLIC  = 1;
    const VISIBILITY_UNLISTED = 2;
    const VISIBILITY_public = 3;

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
     * @ORM\Column(type="string", length=255)
     */
    public $stg_mode;

    /**
     * @ORM\Column(type="integer")
     */
    public $stg_visibility;

    /**
     * @ORM\Column(type="boolean")
     */
    public $stg_definite_dates;

    /**
     * @ORM\Column(type="float")
     */
    public $stg_status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $stg_desc;

    /**
     * @ORM\Column(type="float")
     */
    public $stg_progress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $stg_weight;

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
    public $stg_enddated;

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
    public $stg_dealine_nbDays;

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
     * @ORM\Column(type="datetime")
     */
    public $stg_isFinalized;

    /**
     * @ORM\Column(type="datetime")
     */
    public $stg_deleted;

    /**
     * @ORM\Column(type="datetime")
     */
    public $stg_dcompleted;

    /**
     * @ManyToOne(targetEntity="Process")
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
     * @OrderBy({"weight" = "DESC"})
     * @var Collection
     */
    public $criteria;

    /**
     * @OneToMany(targetEntity="IProcessActivityUser", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
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
     * @ORM\JoinColumn(nullable=false)
     */
    public $stg_master_usr;

    /**
     * ProcessStage constructor.
     * @param int $id
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
     * @param $stg_gstartdate
     * @param $stg_genddate
     * @param $stg_dealine_nbDays
     * @param $stg_deadline_mailSent
     * @param $stg_createdBy
     * @param $stg_inserted
     * @param $stg_isFinalized
     * @param $stg_deleted
     * @param $stg_dcompleted
     * @param $process
     * @param Organization $organization
     * @param Collection $criteria
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
        int $id = 0,
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
        $stg_gstartdate = null,
        $stg_genddate = null,
        $stg_dealine_nbDays = 3,
        $stg_deadline_mailSent = null,
        $stg_createdBy = null,
        $stg_inserted = null,
        $stg_isFinalized = false,
        $stg_deleted = false,
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
        parent::__construct($id, $stg_createdBy, new DateTime());
        $this->stg_complete = $stg_complete;
        $this->stg_name = $stg_name;
        $this->stg_mode = $stg_mode;
        $this->stg_visibility = $stg_visibility;
        $this->stg_definite_dates = $stg_definite_dates;
        $this->stg_status = $stg_status;
        $this->stg_desc = $stg_desc;
        $this->stg_progress = $stg_progress;
        $this->stg_weight = $stg_weight;
        $this->stg_dperiod = $stg_dperiod;
        $this->stg_dfrequency = $stg_dfrequency;
        $this->stg_dorigin = $stg_dorigin;
        $this->stg_fperiod = $stg_fperiod;
        $this->stg_ffrequency = $stg_ffrequency;
        $this->stg_forigin = $stg_forigin;
        $this->stg_startdate = $stg_startdate;
        $this->stg_enddated = $stg_enddated;
        $this->stg_gstartdate = $stg_gstartdate;
        $this->stg_genddate = $stg_genddate;
        $this->stg_dealine_nbDays = $stg_dealine_nbDays;
        $this->stg_deadline_mailSent = $stg_deadline_mailSent;
        $this->stg_inserted = $stg_inserted;
        $this->stg_isFinalized = $stg_isFinalized;
        $this->stg_deleted = $stg_deleted;
        $this->stg_dcompleted = $stg_dcompleted;
        $this->process = $process;
        $this->organization = $organization;
        $this->criteria = $criteria?$criteria:new ArrayCollection();
        $this->participants = $participants?$participants:new ArrayCollection();
        $this->decisions = $decisions?$decisions:new ArrayCollection();
        $this->grades = $grades?$grades:new ArrayCollection();
        $this->results = $results?$results:new ArrayCollection();
        $this->resultTeams = $resultTeams?$resultTeams:new ArrayCollection();
        $this->rankings = $rankings?$rankings:new ArrayCollection();
        $this->rankingTeams = $rankingTeams?$rankingTeams:new ArrayCollection();
        $this->historicalRankings = $historicalRankings?$historicalRankings:new ArrayCollection();
        $this->historicalRankingTeams = $historicalRankingTeams?$historicalRankingTeams:new ArrayCollection();
        $this->stg_master_usr = $stg_master_usr;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComplete(): ?bool
    {
        return $this->stg_complete;
    }

    public function setComplete(bool $stg_complete): self
    {
        $this->stg_complete = $stg_complete;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->stg_name;
    }

    public function setName(string $stg_name): self
    {
        $this->stg_name = $stg_name;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->stg_mode;
    }

    public function setMode(string $stg_mode): self
    {
        $this->stg_mode = $stg_mode;

        return $this;
    }

    public function getVisibility(): ?int
    {
        return $this->stg_visibility;
    }

    public function setVisibility(int $stg_visibility): self
    {
        $this->stg_visibility = $stg_visibility;

        return $this;
    }

    public function getDefiniteDates(): ?bool
    {
        return $this->stg_definite_dates;
    }

    public function setDefiniteDates(bool $stg_definite_dates): self
    {
        $this->stg_definite_dates = $stg_definite_dates;

        return $this;
    }

    public function getStatus(): ?float
    {
        return $this->stg_status;
    }

    public function setStatus(float $stg_status): self
    {
        $this->stg_status = $stg_status;

        return $this;
    }

    public function getDesc(): ?string
    {
        return $this->stg_desc;
    }

    public function setDesc(string $stg_desc): self
    {
        $this->stg_desc = $stg_desc;

        return $this;
    }

    public function getProgress(): ?float
    {
        return $this->stg_progress;
    }

    public function setProgress(float $stg_progress): self
    {
        $this->stg_progress = $stg_progress;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->stg_weight;
    }

    public function setWeight(string $stg_weight): self
    {
        $this->stg_weight = $stg_weight;

        return $this;
    }

    public function getDperiod(): ?int
    {
        return $this->stg_dperiod;
    }

    public function setDperiod(int $stg_dperiod): self
    {
        $this->stg_dperiod = $stg_dperiod;

        return $this;
    }

    public function getDfrequency(): ?string
    {
        return $this->stg_dfrequency;
    }

    public function setDfrequency(string $stg_dfrequency): self
    {
        $this->stg_dfrequency = $stg_dfrequency;

        return $this;
    }

    public function getDorigin(): ?int
    {
        return $this->stg_dorigin;
    }

    public function setDorigin(int $stg_dorigin): self
    {
        $this->stg_dorigin = $stg_dorigin;

        return $this;
    }

    public function getFperiod(): ?int
    {
        return $this->stg_fperiod;
    }

    public function setFperiod(int $stg_fperiod): self
    {
        $this->stg_fperiod = $stg_fperiod;

        return $this;
    }

    public function getFfrequency(): ?string
    {
        return $this->stg_ffrequency;
    }

    public function setFfrequency(string $stg_ffrequency): self
    {
        $this->stg_ffrequency = $stg_ffrequency;

        return $this;
    }

    public function getForigin(): ?int
    {
        return $this->stg_forigin;
    }

    public function setForigin(int $stg_forigin): self
    {
        $this->stg_forigin = $stg_forigin;

        return $this;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->stg_startdate;
    }

    public function setStartdate(\DateTimeInterface $stg_startdate): self
    {
        $this->stg_startdate = $stg_startdate;

        return $this;
    }

    public function getEnddated(): ?\DateTimeInterface
    {
        return $this->stg_enddated;
    }

    public function setEnddated(\DateTimeInterface $stg_enddated): self
    {
        $this->stg_enddated = $stg_enddated;

        return $this;
    }

    public function getGstartdate(): ?\DateTimeInterface
    {
        return $this->stg_gstartdate;
    }

    public function setGstartdate(\DateTimeInterface $stg_gstartdate): self
    {
        $this->stg_gstartdate = $stg_gstartdate;

        return $this;
    }

    public function getGenddate(): ?\DateTimeInterface
    {
        return $this->stg_genddate;
    }

    public function setGenddate(\DateTimeInterface $stg_genddate): self
    {
        $this->stg_genddate = $stg_genddate;

        return $this;
    }

    public function getDealineNbDays(): ?int
    {
        return $this->stg_dealine_nbDays;
    }

    public function setDealineNbDays(int $stg_dealine_nbDays): self
    {
        $this->stg_dealine_nbDays = $stg_dealine_nbDays;

        return $this;
    }

    public function getDeadlineMailSent(): ?bool
    {
        return $this->stg_deadline_mailSent;
    }

    public function setDeadlineMailSent(?bool $stg_deadline_mailSent): self
    {
        $this->stg_deadline_mailSent = $stg_deadline_mailSent;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->stg_inserted;
    }

    public function setInserted(?\DateTimeInterface $stg_inserted): self
    {
        $this->stg_inserted = $stg_inserted;

        return $this;
    }

    public function getIsFinalized(): ?\DateTimeInterface
    {
        return $this->stg_isFinalized;
    }

    public function setIsFinalized(\DateTimeInterface $stg_isFinalized): self
    {
        $this->stg_isFinalized = $stg_isFinalized;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->stg_deleted;
    }

    public function setDeleted(\DateTimeInterface $stg_deleted): self
    {
        $this->stg_deleted = $stg_deleted;

        return $this;
    }

    public function getDcompleted(): ?\DateTimeInterface
    {
        return $this->stg_dcompleted;
    }

    public function setDcompleted(\DateTimeInterface $stg_dcompleted): self
    {
        $this->stg_dcompleted = $stg_dcompleted;

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
        return $this->stg_master_usr;
    }

    public function setMasterUsr(?User $stg_master_usr): self
    {
        $this->stg_master_usr = $stg_master_usr;

        return $this;
    }
    public function getUniqueParticipations()
    {
        /** @var Criterion */
        $criterion = $this->criteria->first();
        return $criterion->getParticipants();
    }
    function addGrade(Grade $grade){
        $this->grades->add($grade);
        return $this;
    }

    function removeGrade(Grade $grade){
        $this->grades->removeElement($grade);
        return $this;
    }
    function addCriterion(ProcessCriterion $criterion){
        //The below line is to prevent adding a criterion already submitted (because of activeStages/stages).
        // However as stage criteria are built in advance for recurring activities, we also need to take into account this exception

        //if(!$criterion->getStage() || $criterion->getStage()->getProcess()->getRecurring()){
        $this->criteria->add($criterion);
        $criterion->setStage($this);
        return $this;
        //}
    }

    function removeCriterion(ProcessCriterion $criterion){
        $this->criteria->removeElement($criterion);
        $criterion->setStage(null);
        return $this;
    }

    function addParticipant(IProcessActivityUser $participant){

        $this->participants->add($participant);
        $participant->setStage($this);
        return $this;
    }

    function removeParticipant(IProcessActivityUser $participant){
        $this->participants->removeElement($participant);
        return $this;
    }

    function addUniqueParticipation(IProcessActivityUser $participant){
        foreach($this->criteria as $criterion){
            $criterion->addParticipant($participant);
            $participant->setCriterion($criterion)->setStage($this)->setProcess($this->getProcess());
        }
        return $this;
    }

    function removeUniqueParticipation(IProcessActivityUser $participant){
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
     * @return Collection|User[]
     */
    function getGraderUsers(){
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
    function getNbEvaluatingCriteria(){
        return count($this->getCriteria()->matching(Criteria::create()->where(Criteria::expr()->eq("type", 1))));
    }

    public function hasCompletedOutput(){
        return false;
    }
}
