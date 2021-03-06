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
use Model\ActivityUser;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=StageRepository::class)
 */
class Stage extends DbObject
{

    // IMPORTANT : STAGE PROGRESS IS A STATIC VARIABLE MODIFIABLE BY USER, WHEREAS STATUS ONLY DEPENDS OF STAGE SELF STATE (DATES AND OUTPUT) 

    public const STATUS_INCOMPLETE = -1;
    public const STATUS_UNSTARTED = 0;
    public const STATUS_ONGOING = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_PUBLISHED = 3;

    public const PROGRESS_STOPPED = -5;
    public const PROGRESS_POSTPONED = -4;
    public const PROGRESS_SUSPENDED = -3;
    public const PROGRESS_REOPENED = -2;
    public const PROGRESS_UNSTARTED = -1;
    public const PROGRESS_UPCOMING = 0;
    public const PROGRESS_ONGOING = 1;
    public const PROGRESS_COMPLETED = 2;
    public const PROGRESS_FINALIZED = 3;

    public const VISIBILITY_PRIVATE = -1;
    public const VISIBILITY_UNLISTED = 0;
    public const VISIBILITY_PUBLIC = 1;

    public const FOLLOWABLE_FORBIDDEN = -1;
    public const FOLLOWABLE_REQUEST = 0;
    public const FOLLOWABLE_DIRECT = 1;

    public const JOINABLE_FORBIDDEN = -1;
    public const JOINABLE_REQUEST = 0;
    public const JOINABLE_DIRECT = 1;

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
    public ?string $name;

    /**
     * @ORM\Column(name="stg_mode", type="integer", nullable=true)
     */
    public int $mode;

    /**
     * @ORM\Column(name="stg_visibility", type="integer", nullable=true)
     */
    public int $visibility;

    /**
     * @ORM\Column(name="stg_access_link", type="string", length=255, nullable=true)
     */
    public ?string $accessLink;

    /**
     * @ORM\Column(name="stg_joinable_status", type="integer", nullable=true)
     */
    public ?int $joinableStatus;

    /**
     * @ORM\Column(name="stg_followable_status", type="integer", nullable=true)
     */
    public ?int $followableStatus;

    /**
     * @ORM\Column(name="stg_invit_closed", type="datetime", nullable=true)
     */
    public ?DateTime $invitClosed;

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
    public ?DateTime $enddate;


    /**
     * @ORM\Column(name="stg_dealine_nb_days", type="integer", nullable=true)
     */
    public int $deadlineNbDays;

    /**
     * @ORM\Column(name="stg_deadline_mailSent", type="boolean", nullable=true)
     */
    public ?bool $deadlineMailSent;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="stageInitiatives")
     * @JoinColumn(name="stg_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\Column(name="stg_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
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
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id", nullable=false)
     */
    protected $activity;

    /**
     * @OneToMany(targetEntity="Output", mappedBy="stage", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $outputs;
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
     * @OneToMany(targetEntity="Participation", mappedBy="stage", cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"inserted" = "DESC", "id" = "DESC", "team" = "ASC"})
     */
    public $participations;

    /**
     * @OneToMany(targetEntity="Event", mappedBy="stage", cascade={"persist", "remove"},  orphanRemoval=true)
     */
    public $events;

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
     * @OneToMany(targetEntity="Mail", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $mails;

    /**
     * @OneToMany(targetEntity="ElementUpdate", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $updates;

    /**
     * @ORM\OneToMany(targetEntity=UserMaster::class, mappedBy="stage", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|UserMaster[]
     */
    private $userMasters;

    /**
     * @var Security
     */
    protected Security $security;
    /**
     * @var UserInterface|null
     */
    public $currentUser;
    /**
     * Stage constructor.
     * @param ?int$id
     * @param bool $complete
     * @param null $activity
     * @param int $visibility
     * @param string $name
     * @param string $accessLink
     * @param int $joinableStatus
     * @param int $followableStatus
     * @param int $status
     * @param string $description
     * @param int $progress
     * @param float $weight
     * @param int $deadlineNbDays
     * @param bool $deadlineMailSent
     * @param DateTime $finalized
     * @param DateTime $lastReopened
     * @param DateTime $deleted
     * @param DateTime $gcompleted
     */
    public function __construct(
        $id = 0,
        $complete = false,
        $activity = null,
        $visibility = 0,
        ?string $name = null,
        $accessLink = null,
        $followableStatus = null,
        $joinableStatus = null,
        $status = 0,
        $description = null,
        $progress = -1,
        $weight = 0.0,
        $deadlineNbDays = 3,
        $deadlineMailSent = null,
        $finalized = null,
        $lastReopened = null,
        $deleted = null,
        $invitClosed = null,
        $gcompleted = null
        )
    {
        parent::__construct($id, null, new DateTime);
        $this->complete = $complete;
        $this->activity = $activity;
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
        $this->joinableStatus = $joinableStatus;
        $this->followableStatus = $followableStatus;
        $this->status = $status;
        $this->description = $description;
        $this->progress = $progress;
        $this->weight = $weight;
        $this->startdate = new DateTime;
        $this->enddate = new DateTime;
        $this->deadlineNbDays = $deadlineNbDays;
        $this->deadlineMailSent = $deadlineMailSent;
        $this->isFinalized = false;
        $this->finalized = $finalized;
        $this->gcompleted = $gcompleted;
        $this->deleted = $deleted;
        $this->invitClosed = $invitClosed;
        $this->reopened = false;
        $this->lastReopened = $lastReopened;
        $this->criteria = new ArrayCollection;
        $this->participations = new ArrayCollection;
        $this->decisions = new ArrayCollection;
        $this->grades = new ArrayCollection;
        $this->results = new ArrayCollection;
        $this->projectResults = new ArrayCollection;
        $this->rankings = new ArrayCollection;
        $this->outputs = new ArrayCollection;
        $this->historicalRankings = new ArrayCollection;
        $this->resultTeams = new ArrayCollection;
        $this->rankingTeams = new ArrayCollection;
        $this->historicalRankingTeams = new ArrayCollection;
        $this->mails = new ArrayCollection;
        $this->userMasters = new ArrayCollection;
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
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

    public function getJoinableStatus(): ?int
    {
        return $this->joinableStatus;
    }

    public function setJoinableStatus(?int $joinableStatus): self
    {
        $this->joinableStatus = $joinableStatus;
        return $this;
    }

    public function getFollowableStatus(): ?int
    {
        return $this->followableStatus;
    }

    public function setFollowableStatus(?int $followableStatus): self
    {
        $this->followableStatus = $followableStatus;
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

    /**
     * @return mixed
     */
    public function getOutputs()
    {
        return $this->outputs;
    }

    public function addOutput(Output $output): Stage
    {
        $this->outputs->add($output);
        $output->setStage($this);
        return $this;
    }
    public function removeOutput(Output $output): Stage
    {
        $this->outputs->removeElement($output);
        return $this;
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

    public function setEnddate(?DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate;
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

    /**
     * @return \DateTime
     */
    public function getLastReopened()
    {
        return $this->lastRe;
    }

    /**
     * @param \DateTime $lastReopened
     */
    public function setLastReopened($lastReopened)
    {
        $this->lastReopened = $lastReopened;
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

    public function getInvitClosed(): ?DateTimeInterface
    {
        return $this->invitClosed;
    }

    public function setInvitClosed(DateTimeInterface $invitClosed): self
    {
        $this->invitClosed = $invitClosed;
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
    public function setSurvey(Survey $survey): void
    {
        $this->survey = $survey;
    }

    /**
     * @return Activity
     */
    public function getActivity(): Activity
    {
        return $this->activity;
    }

    /**
     * @param Activity $activity
     */
    public function setActivity(Activity $activity): self
    {
        $this->activity = $activity;
        return $this;
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
    public function setOrganization(Organization $organization): self
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return ArrayCollection|Criterion[]
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getParticipations()
    {
        return $this->participations;
    }

    /**
     * @return ArrayCollection|Decision[]
     */
    public function getDecisions()
    {
        return $this->decisions;
    }

     /**
     * @return ArrayCollection|Grade[]
     */
    public function getGrades()
    {
        return $this->grades;
    }

    /**
     * @return ArrayCollection|ResultProject[]
     */
    public function getProjectResults()
    {
        return $this->projectResults;
    }

    /**
     * @return ArrayCollection|Result[]
     */
    public function getResults()
    {
        return $this->results;
    }

     /**
     * @return ArrayCollection|ResultTeam[]
     */
    public function getResultTeams()
    {
        return $this->resultTeams;
    }

    /**
     * @return ArrayCollection|Ranking[]
     */
    public function getRankings()
    {
        return $this->rankings;
    }

    /**
     * @return ArrayCollection|RankingTeam[]
     */
    public function getRankingTeams()
    {
        return $this->rankingTeams;
    }

    /**
     * @return ArrayCollection|RankingHistory[]
     */
    public function getHistoricalRankings()
    {
        return $this->historicalRankings;
    }

    /**
     * @return ArrayCollection|RankingTeamHistory[]
     */
    public function getHistoricalRankingTeams()
    {
        return $this->historicalRankingTeams;
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
    public function setTemplate($template): void
    {
        $this->template = $template;
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
        return ($this->status == self::STATUS_ONGOING) && $this->userHasGivenOutput($u);
    }

    public function userHasGivenOutput(User $u): ?bool
    {
        if($this->participations->isEmpty()){
            return false;
        }

        $userParticipations = $this->participations->filter(static function(Participation $p) use ($u){return $p->getUser() === $u;});
        if($userParticipations->count() > 0){
            return $userParticipations->forAll(static function(int $i, Participation $p) {
                return $p->getStatus() >= 3;
            });
        }

        return false;
    }

    public function addParticipation(Participation $participation): Stage
    {

        $this->participations->add($participation);
        $participation->setStage($this)
            ->setActivity($this->activity);
        return $this;
    }

    public function removeParticipation(Participation $participation): Stage
    {
        $this->participations->removeElement($participation);
        return $this;
    }

    public function addParticipant(Participation $participation): Stage
    {
        if (count($this->criteria) !== 0) {
            foreach ($this->criteria as $criterion) {
                $criterion->addParticipation($participation);
                $participation->setCriterion($criterion)->setStage($this)->setActivity($this->getActivity());
            }
        } else {
            $this->addParticipation($participation);
        }
        return $this;
    }

    public function removeParticipant(Participation $participation): Stage
    {
        $participantUser = $participation->getUser();
        $participantTeam = $participation->getTeam();
        $participantExtUser = $participation->getExternalUser();
        foreach ($this->participations as $theParticipant) {
            if ($participantUser == $theParticipant->getUser() || ($participantTeam && $participantTeam == $theParticipant->getTeam()) || ($participantExtUser && $participantExtUser == $theParticipant->getExternalUser())) {
                $this->removeParticipation($theParticipant);
            }
        }
        return $this;
    }

    public function addIndependantParticipant(Participation $participant): Stage
    {
        return $this->addParticipant($participant);
    }

    public function removeIndependantParticipant(Participation $participant): Stage
    {
        return $this->removeParticipant($participant);
    }

    public function addIntParticipant(Participation $participant): Stage
    {
        $this->addParticipant($participant);
        return $this;
    }

    public function removeIntParticipant(Participation $participant): Stage
    {
        $this->removeParticipant($participant);
        return $this;
    }

    public function addExtParticipant(Participation $participant): Stage
    {
        $this->addParticipant($participant);
        return $this;
    }

    public function removeExtParticipant(Participation $participant): Stage
    {
        $this->removeParticipant($participant);
        return $this;
    }

    public function addTeamParticipant(Participation $participant): Stage
    {
        $this->addParticipant($participant);
        return $this;
    }

    public function removeTeamParticipant(Participation $participant): Stage
    {
        $this->removeParticipant($participant);
        return $this;
    }

    public function addIndependantTeamParticipant(Participation $participant): Stage
    {
        $this->addTeamParticipant($participant);
        return $this;
    }

    public function removeIndependantTeamParticipant(Participation $participant): Stage
    {
        $this->removeTeamParticipant($participant);
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
        if (count($this->participations) > 0) {
            foreach ($this->participations as $participant) {
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
        return $this->getGraderParticipants()->map(
            static function (Participation $p) {
                return $p->getUser();
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

    public function getAllSelfGrades()
    {
        if ($this->mode == 1) {
            return null;
        } else {
            return $this->getGrades()->matching(Criteria::create()->where(Criteria::expr()->eq("gradedTeaId", null))->andWhere(Criteria::expr()->eq("gradedUsrId", null)));
        }
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
        if($this->status == $this::STATUS_ONGOING){
            return $this->getGraderParticipants()->exists(function (int $i,Participation $p) use ($u) {
                return $p->getUser() === $u && $p->getType() !== -1 && $p->getStatus() < 3;
            }
            );
        }

        return false;

    }

    public function getOwnerUserId(){
        $uniqueIntParticipations = $this->getIntParticipants();
        if($uniqueIntParticipations){
            foreach($uniqueIntParticipations as $uniqueIntParticipation){
                if($uniqueIntParticipation->isLeader()){
                    return $uniqueIntParticipation->getUser()->getId();
                }
            }
            return null;
        }
        return null;
    }


    public function hasMinimumOutputConfig(): bool
    {
        return $this->getSurvey() !== null || $this->getCriteria()->count() > 0 || $this->getEvents()->count() > 0;
    }

    public function hasFeedbackExpired(): bool
    {
        $yesterdayDate = new DateTime;
        $yesterdayDate->sub(new DateInterval('P1D'));
        return $this->genddate <= $yesterdayDate && $this->status < STAGE::STATUS_COMPLETED;
    }

    public function isComplete(): bool
    {
        $yesterdayDate = new DateTime;
        $yesterdayDate->sub(new DateInterval('P1D'));
        return $this->hasMinimumOutputConfig() && $this->hasMinimumParticipationConfig() && !$this->hasFeedbackExpired();
    }

    public function hasCompletedOutput(): int
    {
        return (int) $this->participations->exists(static function(int $i, Participation $p){
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
                foreach($this->participations as $participant){
                    if($participant->getExternalUser() == $e){
                        $contains = true;
                        break;
                    }
                }
                return $contains;
            });
        });
    }

    public function isModifiable(): bool
    {
        $connectedUser = $this->currentUser;
        $connectedUserRole = $connectedUser->getRole();
        if ($connectedUserRole === 4) {
            return true;
        }

        if ($this->status >= 2) {
            return false;
        }

        if ($connectedUserRole === 1) {
            return true;
        }

        if ($this->getUserMasters()->exists(fn(int $i, UserMaster $m) => $m->getUser() == $connectedUser && $m->getType() == USERMASTER::PARTICIPATION_LEADER) && ($this->getGraderParticipants() === null || !$this->getGraderParticipants()->exists(static function(int $i, Participation $p){return $p->isLeader();}))) {
            return true;
        }

        return $this->getGraderParticipants()->exists(
            static function (int $i, Participation $p) use ($connectedUser) { return $p->getUser() === $connectedUser && $p->isLeader(); }
        );
    }


    public function hasMinimumParticipationConfig(): bool
    {
        return $this->getGraderParticipants()->count() > 0 && $this->getUniqueGradableParticipations()->count() > 0;
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
    public function getUniqueParticipations()
    {

        // Depends on whether current user is part of a team
        $eligibleParticipants = null;
        $uniqueParticipants = new ArrayCollection;
        $teams = [];

        $eligibleParticipants = count($this->criteria) == 0 ? $this->participations : $this->criteria->first()->getParticipations();

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




    public function getSelfParticipations(): ArrayCollection
    {
        return $this->participations->filter(function(Participation $p){
            return $p->getUser() == $this->currentUser;
        });
    }

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getIndependantTeamParticipants()
    {
        $teamParticipants = new ArrayCollection;
        $currentTeam = null;
        foreach ($this->getIndependantParticipants() as $participant) {
            $pTeam = $participant->getTeam();
            if ($pTeam !== null && $pTeam != $currentTeam) {
                $currentTeam = $pTeam;
                $teamParticipants->add($participant);
            }
        };
        return $teamParticipants;
    }

    public function getStartdateU()
    {
        return $this->startdate->format('U');

    }

    public function getPeriod()
    {
        $sD = $this->startdate->format("U");
        if($this->enddate){
            $sE = $this->enddate->format("U");
        } else {
           return null;
        }
        return max(1,$sE - $sD);
    }

    /**
     * 
     * Function which return number of stages, self excluded, which are lying within current stage period +- threshold % of considered interval period 
     * @return Collection|Stage[]
     */
    public function getEmbeddedStages($period = 'y', $thresholdIntPct = 3){

        // Period interval in seconds
        switch($period){
            case 'y' :
                $intCurrYear = intval($_COOKIE['ci']);
                $intNextYear = $intCurrYear + 1;
                $sd = new DateTime("first day of january $intCurrYear");
                $ed = new DateTime("first day of january $intNextYear");
                break;
            case 't' :
                $cookieElmts = explode('-',$_COOKIE['ci']);
                $intYear = intval(end($cookieElmts));
                $intQuarter = intval(prev($cookieElmts));
                $quarterMonths = ['january', 'april', 'july', 'october'];
                $quarterStartingMonth = $quarterMonths[$intQuarter - 1];
                $quarterEndingMonth = $quarterMonths[$intQuarter % 4];
                $quarterEndingYear = $intQuarter == 4 ? $intYear + 1 : $intYear;
                $sd = new DateTime("first day of $quarterStartingMonth $intYear");
                $ed = new DateTime("first day of $quarterEndingMonth $quarterEndingYear");
                break;
            case 'w' :
                $cookieElmts = explode('-',$_COOKIE['ci']);
                $intYear = intval(end($cookieElmts));
                $intCurrWeekOffset = intval(prev($cookieElmts)) - 1;
                $intNextWeekOffset = $intCurrWeekOffset + 1;
                $sd = new DateTime("+$intCurrWeekOffset weeks january $intYear");
                $ed = new DateTime("+$intNextWeekOffset weeks january $intYear");
                break;
            case 'd' :
                $sd = new DateTime(str_replace("-","/",$_COOKIE['ci']));
                $ed = clone $sd->add(new DateInterval('P1D'));
                break;
        }

        $period = $ed->getTimestamp() - $sd->getTimestamp();
        $sortedByPeriodStages = $this->activity->getSortedStagesPerPeriod();
        $embeddedStages = new ArrayCollection;
        foreach($sortedByPeriodStages as $key => $sortedByPeriodStage){
            if($key <= $sortedByPeriodStages->indexOf($this)){
                continue;
            } else {
                if($this->getStartdateU() - round($thresholdIntPct * 0.01 * $period)  < $sortedByPeriodStage->getStartdateU() && $sortedByPeriodStage->getStartdateU() + $sortedByPeriodStage->getPeriod() < $this->getStartdateU() + $this->getPeriod() + round($thresholdIntPct * 0.01 * $period)){
                    $embeddedStages->add($sortedByPeriodStage);
                } else {
                    break;
                }
            }
        }
        return $embeddedStages;
    }

    /**
     * @return Collection|Event[]
     */
    public function getSortedEventsPerPeriod(){
                    
        /** @var Event[] */
        $array = $this->events->getValues();
        usort($array, function($first, $second) {
            $firstExpResDate = $first->getExpResDate() ?: $first->getOnsetDate(); 
            $secondExpResDate = $second->getExpResDate() ?: $second->getOnsetDate(); 
            return ($firstExpResDate->getTimestamp() - $first->getOnsetDate()->getTimestamp() >= $secondExpResDate->getTimestamp() - $second->getOnsetdate()->getTimestamp()) ? 1 : -1;
        });
        $orderedEvents = new ArrayCollection($array);
        return $orderedEvents;
    }

     /**
    * @return ArrayCollection|Event[]
    */
    public function getEvents()
    {
        return $this->events;
    }

    public function addEvent(Event $event): Stage
    {
        $this->events->add($event);
        $event->setStage($this);
        return $this;
    }

    public function removeEvent(Event $event): Stage
    {
        $this->events->removeElement($event);
        return $this;
    }
    /**
     * @return ArrayCollection|Participation[]
     */
    public function getGraderParticipants()
    {
        return $this->getParticipants()->matching(Criteria::create()->where(Criteria::expr()->neq("type", -1)));
    }/**
 * @return ArrayCollection|Participation[]
 */
    public function getIndivParticipants()
    {
        $indivParticipants = new ArrayCollection;
        $myParticipations = $this->getSelfParticipations();
        $myTeam = $myParticipations->count() === 0 ? null : $myParticipations->first()->getTeam();

        foreach ($this->getParticipants() as $participant) {
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
    public function getIntParticipants(){
        return $this->getParticipants()->filter(function(Participation $p){
            return $p->getTeam() === null && $p->getUser()->getOrganization() ==
                $this->currentUser->getOrganization();
        });
    }
    /**
     * @return ArrayCollection|Participation[]
     */
    public function getGradableParticipants()
    {
        return $this->getParticipants()->matching(Criteria::create()->where(Criteria::expr()->neq("type", 0)));
    }

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getExtParticipants()
    {
        return $this->getParticipants()->filter(function(Participation $p){
            return $p->getTeam() === null && $p->getUser()->getOrganization() != $this->currentUser->getOrganization();
        });
    }
    /**
     * @return ArrayCollection|Participation[]
     */
    public function getTeamParticipants()
    {
        $myParticipations = $this->getSelfParticipations();
        $myTeam = $myParticipations->count() === 0 ? null : $myParticipations->first()->getTeam();
        return $this->getParticipants()->filter(static function(Participation $p) use ($myTeam){
            return $p->getTeam() !== null && $p->getTeam() != $myTeam;
        });
    }
    /**
     * @return ArrayCollection|Participation[]
     */
    public function getUserGradableParticipants()
    {
        // We get all non-third party user participations, except those of people who are part of a team we don't belong to
        if ($this->mode == STAGE::GRADED_STAGE) {
            return null;
        } else {

            $userGradableParticipants = new ArrayCollection;

            $unorderedGradableParticipants = $this->getIndivParticipants()->filter(function(Participation $p){
                return $p->getType() != Participation::PARTICIPATION_THIRD_PARTY;
            });

            foreach($unorderedGradableParticipants as $unorderedGradableParticipant){
                $userGradableParticipants->add($unorderedGradableParticipant);
            }

            return $userGradableParticipants;
        }
    }

    public function addTeamGradableParticipation(Participation $participant): Stage
    {
        $this->participations->add($participant);
        return $this;
    }

    public function removeTeamGradableParticipation(Participation $participant): Stage
    {
        $this->participations->removeElement($participant);
        return $this;
    }



    public function addGradableParticipant(Participation $participant): Stage
    {
        if ($this->participations->exists(function (Participation $u) use ($participant) {
            return $u->getUser()->getId() === $participant->getUser()->getId();
        })) {
            return $this;
        }

        foreach ($this->criteria as $criterion) {
            $criterion->addParticipation($participant);
            $participant->setCriterion($criterion)->setStage($this);
        }
        return $this;
    }

    public function removeGradableParticipant(Participation $participant): Stage
    {
        foreach ($this->criteria as $criterion) {
            $criterion->participants->removeElement($participant);
        }
        return $this;
    }

    /**
     * Get distinct participants, independant from current user
     * @return ArrayCollection|Participation[]
     */
    public function getIndependantParticipants()
    {
        $eligibleParticipations = null;
        $independantParticipants = new ArrayCollection;
        $teams = [];

        $eligibleParticipations = count($this->criteria) === 0 ? $this->participations : $this->criteria->first()->getParticipations();

        foreach ($eligibleParticipations as $eligibleParticipation) {
            $team = $eligibleParticipation->getTeam();
            if ($team === null) {
                $independantParticipants->add($eligibleParticipation);
            } else {
                if (!in_array($team, $teams)) {
                    $independantParticipants->add($eligibleParticipation);
                    $teams[] = $team;
                }
            }
        }
        return $independantParticipants;
    }
    /**
     * @return Collection|Stage[]
     */
    public function getSortedOutputsPerPeriod(){

        $array = $this->outputs->getValues();
        usort($array, function($first, $second)  {

                return (date_diff($first->getEnddate(),$first->getStartdate())->format('%r%a') >= date_diff($second->getEnddate(),$second->getStartdate())->format('%r%a')) ? 1 : -1;

        });
        $orderedStages = new ArrayCollection($array);
        return $orderedStages;
    }
    public function getParticipants(): ArrayCollection
    {

        // Depends on whether current user is part of a team
        $eligibleParticipations = null;
        $participants = new ArrayCollection;
        $teams = [];

        $eligibleParticipations = count($this->criteria) === 0 ? $this->participations : $this->criteria->first()->getParticipations();
        $myParticipations = $this->getSelfParticipations();
        $myTeam = $myParticipations->count() === 0 ? null : $myParticipations->first()->getTeam();
        if($eligibleParticipations != null) {
            foreach ($eligibleParticipations as $eligibleParticipation) {
                $currentTeam = $eligibleParticipation->getTeam();
                if ($currentTeam === null || $currentTeam == $myTeam) {
                    $participants->add($eligibleParticipation);
                } else if (!in_array($currentTeam, $teams, true)) {
                    $participants->add($eligibleParticipation);
                    $teams[] = $currentTeam;
                }
            }
        }

        return $participants;
    }
    public function getGradingParticipants()
    {
        return count($this->getParticipants()->matching(
            Criteria::create()->where(Criteria::expr()->neq("type", -1))
        ));
    }

    /**
    * @return ArrayCollection|Mail[]
    */
    public function getMails()
    {
        return $this->mails;
    }

    public function addMail(Mail $mail): Stage
    {
        $this->mails->add($mail);
        $mail->setStage($this);
        return $this;
    }

    public function removeMail(Mail $mail): Stage
    {
        $this->mails->removeElement($mail);
        return $this;
    }

    /**
    * @return ArrayCollection|ElementUpdate[]
    */
    public function getUpdates()
    {
        return $this->updates;
    }

    public function addUpdate(ElementUpdate $update): Stage
    {
        $this->updates->add($update);
        $update->setStage($this);
        return $this;
    }

    public function removeUpdate(ElementUpdate $update): Stage
    {
        $this->updates->removeElement($update);
        return $this;
    }

    public function getNewUpdates(User $user){
        $newUpdates = new ArrayCollection;
        $updates = $this->updates;
        foreach($this->updates->filter(fn(ElementUpdate $eu) => $eu->getUser() == $user && $eu->getViewed() == null)->getValues() as $newUpdate){
            $newUpdates->add($newUpdate);
        }

        return $newUpdates;
    }

    public function getPctElapsed(){
        $now = new DateTime();
        if($now < $this->startdate){
            return null;
        }
        $nowU = $now->format("U");
        $startU = $this->startdate->format("U");
        return round( min(100,100 * ($nowU - $startU) / $this->getPeriod()), 0);
    }
    
    /**
    * @return ArrayCollection|UserMaster[]
    */
    public function getUserMasters()
    {
        return $this->userMasters;
    }

    public function addUserMaster(UserMaster $userMaster): self
    {
        $this->userMasters->add($userMaster);
        $userMaster->setStage($this);
        return $this;
    }

    public function removeUserMaster(UserMaster $userMaster): self
    {
        $this->userMasters->removeElement($userMaster);
        return $this;
    }

    /**
    * @return ArrayCollection
    */
    public function getLeaderMasters()
    {
        return $this->userMasters->filter(fn(UserMaster $m) => $m->getProperty() == 'leader' && $m->getType() >= UserMaster::ADDED);
    }
    /**
    * @return ArrayCollection
    */
    public function getLeaders()
    {
        return $this->getLeaderMasters()->map(fn(UserMaster $m) => $m->getUser());
    }

    /**
    * @return ArrayCollection|UserMaster[]
    */
    public function getFollowerMasters()
    {
        return $this->userMasters->filter(fn(UserMaster $m) => $m->getProperty() == 'followableStatus' && $m->getType() >= UserMaster::ADDED);
    }

    /**
    * @return ArrayCollection|User[]
    */
    public function getFollowers()
    {
        return $this->getFollowerMasters()->map(fn(UserMaster $m) => $m->getUser());
    }

    /**
    * @return ArrayCollection|User[]
    */
    public function getRequesterMasters()
    {
        return $this->userMasters->filter(fn(UserMaster $m) => ($m->getProperty() == 'followableStatus' || $m->getProperty() == 'joinableStatus') && $m->getType() == UserMaster::PENDING);
    }
    /**
    * @return ArrayCollection|User[]
    */
    public function getRequesters()
    {
        return $this->getRequesterMasters()->map(fn(UserMaster $m) => $m->getUser());
    }

}
