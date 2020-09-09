<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\IProcessStageRepository;
use DateTime;
use DateTimeInterface;
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
 * @ORM\Entity(repositoryClass=IProcessStageRepository::class)
 */
class IProcessStage extends DbObject
{
    public const STAGE_UNSTARTED    = 0;
    public const STAGE_ONGOING      = 1;
    public const STAGE_COMPLETED  = 2;
    public const STAGE_PUBLISHED    = 3;

    public const VISIBILITY_public = 0;
    public const VISIBILITY_UNLISTED = 1;
    public const VISIBILITY_PUBLIC  = 2;
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
     * @ORM\Column(name="stg_mod", type="integer", nullable=true)
     */
    public $mod;

    /**
     * @ORM\Column(name="stg_visibility", type="integer", nullable=true)
     */
    public $visibility;

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
     * @ORM\Column(name="stg_weight", type="float", nullable=true)
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
     * @ORM\Column(name="stg_ffrequency", type="string", length=255, nullable=true)
     */
    public $ffrequency;

    /**
     * @ORM\Column(name="stg_forigin", type="integer", nullable=true)
     */
    public $forigin;

    /**
     * @ORM\Column(name="stg_definite_dates", type="boolean", nullable=true)
     */
    public $definite_dates;

    /**
     * @ORM\Column(name="stg_startdate", type="datetime", nullable=true)
     */
    public $startdate;

    /**
     * @ORM\Column(name="stg_enddate", type="datetime", nullable=true)
     */
    public $enddate;

   

    /**
     * @ORM\Column(name="stg_deadline_nbDays", type="integer", nullable=true)
     */
    public $deadline_nbDays;

    /**
     * @ORM\Column(name="stg_deadline_mailSent", type="boolean", nullable=true)
     */
    public $deadline_mailSent;

    /**
     * @ORM\Column(name="stg_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="stg_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="stg_isFinalized", type="boolean", nullable=true)
     */
    public $isFinalized;

    /**
     * @ORM\Column(name="stg_finalized", type="datetime", nullable=true)
     */
    public $finalized;

    /**
     * @ORM\Column(name="stg_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @ORM\Column(name="stg_gcompleted", type="datetime", nullable=true)
     */
    public $gcompleted;

    /**
     * @ManyToOne(targetEntity="InstitutionProcess", inversedBy="stages")
     * @JoinColumn(name="iprocess_inp_id", referencedColumnName="inp_id",nullable=true)
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
     * @ORM\JoinColumn(name="stg_master_user_id", referencedColumnName="usr_id", nullable=true)
     */
    public $stg_master_usr;

    /**
     * IProcessStage constructor.
     * @param ?int$id
     * @param $stg_complete
     * @param $stg_name
     * @param $stg_mod
     * @param $stg_visibility
     * @param $stg_status
     * @param $stg_desc
     * @param $stg_progress
     * @param $stg_weight
     * @param $stg_dperiod
     * @param $stg_dfrequency
     * @param $stg_dorigin
     * @param $stg_ffrequency
     * @param $stg_forigin
     * @param $stg_definite_dates
     * @param $stg_startdate
     * @param $stg_enddate
     * @param $stg_deadline_nbDays
     * @param $stg_deadline_mailSent
     * @param $stg_createdBy
     * @param $stg_isFinalized
     * @param $stg_finalized
     * @param $stg_deleted
     * @param $stg_gcompleted
     * @param InstitutionProcess $institutionProcess
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
        User $stg_master_usr = null,
        $stg_name = '',
        $stg_mod = null,
        $stg_visibility = 3,
        $stg_status = 0,
        $stg_desc = '',
        $stg_progress = 0.0,
        $stg_weight = 0.0,
        $stg_dperiod = 15,
        $stg_dfrequency = '0',
        $stg_definite_dates = false,
        $stg_startdate = null,
        $stg_enddate = null,
        $stg_deadline_nbDays = 3,
        $stg_deadline_mailSent = null,
        $stg_createdBy = null,
        $stg_isFinalized = False,
        $stg_finalized = null,
        $stg_deleted = null,
        $stg_gcompleted = null,
        $stg_dorigin = null,
        $stg_ffrequency = null,
        $stg_forigin = null,
        InstitutionProcess $institutionProcess = null,
        Organization $organization = null,
        $criteria = null,
        $participants = null,
        $decisions = null,
        $grades = null,
        $results = null,
        $resultTeams = null,
        $rankings = null,
        $rankingTeams = null,
        $historicalRankings = null,
        $historicalRankingTeams = null
        )
    {
        parent::__construct($id, $stg_createdBy, new DateTime());
        $this->complete = $stg_complete;
        $this->name = $stg_name;
        $this->mod = $stg_mod;
        $this->visibility = $stg_visibility;
        $this->status = $stg_status;
        $this->desc = $stg_desc;
        $this->progress = $stg_progress;
        $this->weight = $stg_weight;
        $this->dperiod = $stg_dperiod;
        $this->dfrequency = $stg_dfrequency;
        $this->dorigin = $stg_dorigin;
        $this->ffrequency = $stg_ffrequency;
        $this->forigin = $stg_forigin;
        $this->definite_dates = $stg_definite_dates;
        $this->startdate = $stg_startdate;
        $this->enddate = $stg_enddate;
        $this->deadline_nbDays = $stg_deadline_nbDays;
        $this->deadline_mailSent = $stg_deadline_mailSent;
        $this->createdBy = $stg_createdBy;
        $this->isFinalized = $stg_isFinalized;
        $this->finalized = $stg_finalized;
        $this->deleted = $stg_deleted;
        $this->gcompleted = $stg_gcompleted;
        $this->institutionProcess = $institutionProcess;
        $this->organization = $organization;
        $this->criteria = $criteria;
        $this->participants = $participants;
        $this->decisions = $decisions;
        $this->grades = $grades;
        $this->results = $results;
        $this->resultTeams = $resultTeams;
        $this->rankings = $rankings;
        $this->rankingTeams = $rankingTeams;
        $this->historicalRankings = $historicalRankings;
        $this->historicalRankingTeams = $historicalRankingTeams;
        $this->stg_master_usr = $stg_master_usr;
    }


    public function isComplete(): ?bool
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

    public function getMod(): ?int
    {
        return $this->mod;
    }

    public function setMod(int $stg_mod): self
    {
        $this->mod = $stg_mod;

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

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $stg_weight): self
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

    public function isDefiniteDates(): ?bool
    {
        return $this->definite_dates;
    }

    public function setDefiniteDates(bool $stg_definite_dates): self
    {
        $this->definite_dates = $stg_definite_dates;

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

    public function getEnddate(): ?DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(DateTimeInterface $stg_enddate): self
    {
        $this->enddate = $stg_enddate;

        return $this;
    }

    

    public function isDeadlineNbDays(): ?int
    {
        return $this->deadline_nbDays;
    }

    public function setDeadlineNbDays(int $stg_deadline_nbDays): self
    {
        $this->deadline_nbDays = $stg_deadline_nbDays;

        return $this;
    }

    public function getDeadlineMailSent(): ?bool
    {
        return $this->deadline_mailSent;
    }

    public function setDeadlineMailSent(?bool $stg_deadline_mailSent): self
    {
        $this->deadline_mailSent = $stg_deadline_mailSent;

        return $this;
    }

    public function setInserted(?DateTimeInterface $stg_inserted): self
    {
        $this->inserted = $stg_inserted;

        return $this;
    }

    public function getIsFinalized(): ?bool
    {
        return $this->isFinalized;
    }

    public function setIsFinalized(bool $stg_isFinalized): self
    {
        $this->isFinalized = $stg_isFinalized;

        return $this;
    }

    public function getFinalized(): ?DateTimeInterface
    {
        return $this->finalized;
    }

    public function setFinalized(DateTimeInterface $stg_finalized): self
    {
        $this->finalized = $stg_finalized;

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

    public function getGcompleted(): ?DateTimeInterface
    {
        return $this->gcompleted;
    }

    public function setGcompleted(DateTimeInterface $stg_gcompleted): self
    {
        $this->gcompleted = $stg_gcompleted;

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
        if(count($this->criteria) == 0){return $this->participants;}
        /** @var IProcessCriterion */
        $criterion = $this->criteria->first();
        return $criterion->getParticipants();
    }


    /**
     * @return ArrayCollection|IProcessParticipation[]
     */
    public function getUniqueIntParticipations()
    {
        $orgId = $this->institutionProcess->getOrganization()->getId();
        $intParticipants = new ArrayCollection;

        foreach($this->getUniqueParticipations() as $participant){
            if($participant->getUser()->getOrgId() == $orgId){
                $intParticipants->add($participant);
            }
        };
        return count($intParticipants) > 0 ? $intParticipants : null;
    }

    /**
     * @return ArrayCollection|IProcessParticipation[]
     */
    public function getUniqueExtParticipations()
    {
        $orgId = $this->institutionProcess->getOrganization()->getId();
        $extParticipants = new ArrayCollection;
        foreach($this->getUniqueParticipations() as $participant){
            if($participant->getUser()->getOrgId() != $orgId){
                $extParticipants->add($participant);
            }
        };
        return count($extParticipants) > 0 ? $extParticipants : null;
    }

    /**
     * @return ArrayCollection|IProcessParticipation[]
     */
    public function getUniqueTeamParticipations()
    {
        $orgId = $this->institutionProcess->getOrganization()->getId();
        $teamParticipants = new ArrayCollection;
        $currentTeam = null;
        foreach($this->getUniqueParticipations() as $participant){
            $pTeam = $participant->getTeam();
            if($pTeam != null && $pTeam != $currentTeam){
                $currentTeam = $pTeam;
                $teamParticipants->add($pTeam);
            }
        };
        return count($teamParticipants) > 0 ? $teamParticipants : null;
    }


    // Display participant names
    public function getUniqueParticipantNames($fullNameFormat = false): ?array
    {
        $uniqueParticipants = $this->criteria->first()->getParticipants();
        $DNames = [];
        foreach($uniqueParticipants as $uniqueParticipant){
            $DNames[] = $uniqueParticipant->getDirectUser()->getFirstname();
        }

        $doublonEndingElmts = array_diff_key($DNames, array_unique($DNames));

        if(count($doublonEndingElmts) == 0){
            return $DNames;
        } else {
            // We reverse the array while keeping the keys unchanged
            $reversedDNames = array_reverse($DNames,true);
            $doublonStartingElmts = array_diff_key($reversedDNames, array_unique($reversedDNames));
            // We get all the doublon elements in array, with their position (key)
            $doublonElmts = array_reverse($doublonStartingElmts,true) + $doublonEndingElmts;

            foreach($doublonElmts as $key => $doublonElmt){
                $DNames[$key] .= ' '.strtoupper($uniqueParticipants->get($key)->getDirectUser()->getLastname()[0]);
            }
            return $DNames;
        }
    }
    public function addGrade(Grade $grade): IProcessStage
    {
        $this->grades->add($grade);
        return $this;
    }

    public function removeGrade(Grade $grade): IProcessStage
    {
        $this->grades->removeElement($grade);
        return $this;
    }
    public function addCriterion(IProcessCriterion $criterion): IProcessStage
    {
        //The below line is to prevent adding a criterion already submitted (because of activeStages/stages).
        // However as stage criteria are built in advance for recurring activities, we also need to take into account this exception

        //if(!$criterion->getStage() || $criterion->getStage()->getInstitutionProcess()->getRecurring()){
        $this->criteria->add($criterion);
        $criterion->setStage($this);
        return $this;
        //}
    }

    public function removeCriterion(IProcessCriterion $criterion): IProcessStage
    {
        $this->criteria->removeElement($criterion);
        $criterion->setStage(null);
        return $this;
    }

    public function addParticipant(IProcessParticipation $participant): IProcessStage
    {

        $this->participants->add($participant);
        $participant->setStage($this);
        return $this;
    }

    public function removeParticipant(IProcessParticipation $participant): IProcessStage
    {
        $this->participants->removeElement($participant);
        return $this;
    }

    public function addUniqueParticipation(IProcessParticipation $participant): IProcessStage
    {
        foreach($this->criteria as $criterion){
            $criterion->addParticipant($participant);
            $participant->setCriterion($criterion)->setStage($this)->setInstitutionProcess($this->getInstitutionProcess());
        }
        return $this;
    }

    public function removeUniqueParticipation(IProcessParticipation $participant): IProcessStage
    {
        $participantUsrId = $participant->getUsrId();
        foreach ($this->participants as $theParticipant) {
            if ($participantUsrId == $theParticipant->getUsrId()) {
                $this->removeParticipant($theParticipant);
            }
        }
        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }
    // Defines which users can grade
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

    /**
     * @return int
     */
    public function getNbEvaluatingCriteria(): int
    {
        return count($this->getCriteria()->matching(Criteria::create()->where(Criteria::expr()->eq(", type", 1))));
    }

    /**
     * @return ArrayCollection|IProcessParticipation[]
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
            } else if (!in_array($team, $teams)) {
                $uniqueParticipants->add($eligibleParticipant);
                $teams[] = $team;
            }
        }
        return $uniqueParticipants;
    }
    public function addIndependantUniqueIntParticipation(IProcessParticipation $participant): IProcessStage
    {
        $this->addUniqueParticipation($participant);
        return $this;
    }

    public function removeIndependantUniqueIntParticipation(IProcessParticipation $participant): IProcessStage
    {
        $this->removeUniqueParticipation($participant);
        return $this;
    }

    public function addIndependantUniqueExtParticipation(IProcessParticipation $participant): IProcessStage
    {
        $this->addUniqueParticipation($participant);
        return $this;
    }

    public function removeIndependantUniqueExtParticipation(IProcessParticipation $participant): IProcessStage
    {
        $this->removeUniqueParticipation($participant);
        return $this;
    }

    public function addIndependantUniqueTeamParticipation(Participation $participant): IProcessStage
    {
        $this->addUniqueTeamParticipation($participant);
        return $this;
    }

    public function removeIndependantUniqueTeamParticipation(Participation $participant): IProcessStage
    {
        $this->removeUniqueTeamParticipation($participant);
        return $this;
    }
    //TODO isModifiable et getGradableUSers et independant unique participations
}
