<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\MasterController;
use App\Repository\OrganizationRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Component\Validator\Constraints as Assert;
use Stripe;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=OrganizationRepository::class)
 */
class Organization extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="org_id", type="integer", nullable=false)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="org_legalname", type="string", length=255, nullable=true)
     */
    public $legalname;

    /**
     * @ORM\Column(name="org_commname", type="string", length=255, nullable=true)
     */
    public $commname;

    /**
     * @ORM\Column(name="org_type", type="string", length=255, nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="org_isClient", type="boolean", nullable=true)
     */
    public $isClient;

    /**
     * @ORM\Column(name="org_oth_language", type="string", length=255, nullable=true)
     */
    public $oth_language;

    /**
     * @ORM\Column(name="org_weight_type", type="string", length=255, nullable=true)
     */
    public $weight_type;

    /**
     * @ORM\Column(name="org_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;


    /**
     * @ORM\Column(name="org_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="org_last_checked_plan", type="datetime", nullable=true)
     */
    public $lastCheckedPlan;

    /**
     * @ORM\Column(name="org_expired", type="datetime", nullable=true)
     */
    public $expired;

    /**
     * @ORM\Column(name="org_testing_reminder_sent", type="boolean", nullable=true)
     */
    public $reminderMailSent;

    /**
     * @ORM\Column(name="org_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @ORM\Column(name="org_routine_pstatus", type="datetime", nullable=true)
     */
    public $orgRoutinePStatus;

    /**
     * @ORM\Column(name="org_routine_greminders", type="datetime", nullable=true)
     */
    public $routineGreminders;

    /**
     * @OneToMany(targetEntity="Stage", mappedBy="organization", cascade={"persist", "remove"}, orphanRemoval=true)
     */
//     * @OrderBy({"startdate" = "ASC"})
    public $stages;
    /**
     * @OneToMany(targetEntity="Department", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
     */
//     * @OrderBy({"name" = "ASC"})
    public $departments;
    /**
     * @OneToMany(targetEntity="Position", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
     */
//     * @OrderBy({"name" = "ASC"})
    public $positions;
    /**
     * @OneToMany(targetEntity="Title", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
     */
//     * @OrderBy({"name" = "ASC"})
    public $titles;
    /**
     * @OneToMany(targetEntity="Weight", mappedBy="organization", cascade={"persist", "remove"}, orphanRemoval=true)
     */
//     * @OrderBy({"value" = "ASC"})
    public $weights;
    /**
     * @OneToMany(targetEntity="Decision", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
     */
//     * @OrderBy({"inserted" = "ASC"})
    public $decisions;
    /**
     * @OneToMany(targetEntity="Activity", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
     */
//     * @OrderBy({"status" = "ASC", "name" = "ASC"})
    public $activities;
    /**
     * @OneToMany(targetEntity="Criterion", mappedBy="organization", cascade={"persist", "remove"}, orphanRemoval=true)
     */
//     * @OrderBy({", type" = "ASC", "inserted" = "ASC"})
    public $criteria;

    /**
     * @OneToMany(targetEntity="Client", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
     * @var ArrayCollection|Client[]
     */
    public $clients;

    /**
     * @OneToMany(targetEntity="CriterionName", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
     */
//     * @OrderBy({", type" = "DESC", "name" = "ASC"})
    public $criterionNames;

    /**
     * @Column(name="org_users_CSV", type="string", nullable=true)
     * @Assert\File(mimeTypes={ "text/csv" })
     */
    public $usersCSV;
    /**
     * @ORM\ManyToOne(targetEntity=WorkerFirm::class, inversedBy="organizations")
     * @JoinColumn(name="worker_firm_wfi_id", referencedColumnName="wfi_id", nullable=true)
     */
    private $workerFirm;

    /**
     * @ORM\OneToMany(targetEntity=UserMaster::class, mappedBy="organization", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|UserMaster[]
     */
    private $userMasters;

    /**
     * @OneToOne(targetEntity="User", inversedBy="organization")
     * @JoinColumn(name="payment_usr_id", referencedColumnName="usr_id",nullable=true)
     */
    private $paymentUser;
    /**
     * @Column(name="org_logo", type="string", nullable=true)
     * @var string
     */
    protected $logo;

    /**
     * @Column(name="org_plan", type="integer", length=1, nullable=false, options={"default": "3"})
     * @var string
     */
    protected $plan;
    /**
     * @Column(name="org_cus_id", type="string", nullable=true)
     * @var string
     */
    protected $customerId;
    /**
     * @OneToMany(targetEntity="Team", mappedBy="organization",cascade={"persist", "remove"})
     */
    public $teams;
    /**
     * @OneToMany(targetEntity="Mail", mappedBy="organization", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $mails;
    /**
     * @OneToMany(targetEntity="Target", mappedBy="organization",cascade={"persist", "remove"})
     */
    public $targets;
    /**
     * @OneToMany(targetEntity="OrganizationUserOption", mappedBy="organization", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $options;
    /**
     * @OneToMany(targetEntity="Process", mappedBy="organization", cascade={"persist","remove"})
     */
    public $processes;
    /**
     * @OneToMany(targetEntity="User", mappedBy="organization", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $users;
    /**
     * @OneToMany(targetEntity="OrganizationPaymentMethod", mappedBy="organization", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $paymentMethods;
    /**
     * @OneToMany(targetEntity="InstitutionProcess", mappedBy="organization", cascade={"persist","remove"}, orphanRemoval=true)
     * @var ArrayCollection|InstitutionProcess[]
     */
    public $institutionProcesses;

    /**
     * @OneToMany(targetEntity="CriterionGroup", mappedBy="organization", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $criterionGroups;

    /**
     * @OneToMany(targetEntity="EventGroup", mappedBy="organization", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $eventGroups;

    /**
     * @OneToMany(targetEntity="EventType", mappedBy="organization", cascade={"persist","remove"})
     */
    protected $eventTypes;

    /**
     * @OneToMany(targetEntity="Event", mappedBy="organization", cascade={"persist","remove"})
     */
    protected $events;
    /**
     * @OneToMany(targetEntity="EventDocument", mappedBy="organization", cascade={"persist","remove"})
     */
    protected $eventDocuments;
    /**
     * @OneToMany(targetEntity="EventComment", mappedBy="organization", cascade={"persist","remove"})
     */
    protected $eventComments;
    /**
     * @OneToMany(targetEntity="DynamicTranslation", mappedBy="organization", cascade={"persist","remove"})
     */
    protected $dTranslations;

    const PLAN_FREE = 3;
    const PLAN_PREMIUM = 2;
    const PLAN_ENTERPRISE = 1;

    /**
     * Organization constructor.
     * @param ?int$id
     * @param $legalname
     * @param $commname
     * @param $type
     * @param $plan
     * @param $isClient
     * @param $oth_language
     * @param $weight_type
     * @param $createdBy
     * @param $lastCheckedPlan
     * @param $expired
     * @param $testing_reminder_sent
     * @param $deleted
     * @param $routine_pstatus
     * @param $routine_greminders
     * @param $stages
     * @param $departments
     * @param $positions
     * @param $titles
     * @param $weights
     * @param $decisions
     * @param $activities
     * @param $criteria
     * @param ArrayCollection|Client[] $clients
     * @param $criterionNames
     * @param $usersCSV
     * @param string $logo
     * @param $teams
     * @param $mails
     * @param $targets
     * @param $options
     * @param $processes
     * @param $customerId
     * @param ArrayCollection|InstitutionProcess[] $institutionProcesses
     * @param CriterionGroup[] $criterionGroups
     * @param $workerFirm
     * @param $paymentMethods
     * @param $paymentUser
     */
    public function __construct(
      ?int $id = 0,
        $isClient = null,
        $plan = 3,
        $legalname = '',
        $commname = '',
        $type = '',
        $oth_language = 'FR',
        ?string $logo = null,
        $weight_type = "",
        $usersCSV = '',
        $createdBy = null,
        $lastCheckedPlan = null,
        $expired = null,
        $testing_reminder_sent = null,
        $deleted = null,
        $routine_pstatus = null,
        $routine_greminders = null,
        $users = null,
        $stages = null,
        $departments = null,
        $positions = null,
        $titles = null,
        $weights = null,
        $decisions = null,
        $activities = null,
        $criteria = null,
        $clients = null,
        $criterionNames = null,
        $teams = null,
        $mails = null,
        $targets = null,
        $options = null,
        $processes = null,
        $customerId = null,
        $paymentUser = null ,
        $institutionProcesses = null,
        array $criterionGroups = null
        )
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->legalname = $legalname;
        $this->commname = $commname;
        $this->type = $type;
        $this->plan = $plan;
        $this->isClient = $isClient;
        $this->oth_language = $oth_language;
        $this->weight_type = $weight_type;
        $this->lastCheckedPlan = $lastCheckedPlan;
        $this->expired = $expired;
        $this->reminderMailSent = $testing_reminder_sent;
        $this->deleted = $deleted;
        $this->orgRoutinePStatus = $routine_pstatus;
        $this->routineGreminders = $routine_greminders;
        $this->stages = $stages;
        $this->departments = $departments?: new ArrayCollection();
        $this->positions = $positions?: new ArrayCollection();
        $this->titles = $titles?: new ArrayCollection();
        $this->weights = $weights?: new ArrayCollection();
        $this->decisions = $decisions?: new ArrayCollection();
        $this->activities = $activities?: new ArrayCollection();
        $this->criteria = $criteria;
        $this->clients = $clients?: new ArrayCollection();
        $this->criterionNames = $criterionNames?: new ArrayCollection();
        $this->usersCSV = $usersCSV;
        $this->logo = $logo;
        $this->teams = $teams;
        $this->mails = $mails;
        $this->targets = $targets?: new ArrayCollection();
        $this->options = new ArrayCollection;
        $this->processes = $processes?: new ArrayCollection();
        $this->institutionProcesses = $institutionProcesses?: new ArrayCollection();
        $this->criterionGroups = $criterionGroups?: new ArrayCollection();
        $this->users = $users?: new ArrayCollection();
        $this->customerId = $customerId;
        $this->eventGroups = new ArrayCollection();
        $this->eventTypes = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->paymentMethods = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->userMasters = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getCustomerId(): ?string
    {


        return $this->customerId;
    }

    /**
     * @param string $customerId
     */
    public function setCustomerId(string $customerId): void
    {
        $this->customerId = $customerId;
    }




    public function getOrgId(): ?int
    {
        return $this->id;
    }

    public function getPlan(): ?int
    {
        return $this->plan;
    }

    public function setPlan(int $plan): self
    {
        $this->plan = $plan;
        return $this;
    }

    public function getLegalname(): ?string
    {
        return $this->legalname;
    }

    public function setLegalname(string $legalname): self
    {
        $this->legalname = $legalname;
        return $this;
    }

    public function getCommname(): ?string
    {
        return $this->commname;
    }

    public function setCommname(string $commname): self
    {
        $this->commname = $commname;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isClient(): ?bool
    {
        return $this->isClient;
    }

    public function setIsClient(bool $isClient): self
    {
        $this->isClient = $isClient;

        return $this;
    }

    public function getOthLanguage(): ?string
    {
        return $this->oth_language;
    }

    public function setOthLanguage(string $oth_language): self
    {
        $this->oth_language = $oth_language;

        return $this;
    }

    public function getWeightType(): ?string
    {
        return $this->weight_type;
    }

    public function setWeightType(string $weight_type): self
    {
        $this->weight_type = $weight_type;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getInserted(): DateTime
    {
        return $this->inserted;
    }

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }

    public function getLastCheckedPlan(): ?DateTimeInterface
    {
        return $this->lastCheckedPlan;
    }

    public function setLastCheckedPlan(DateTimeInterface $lastCheckedPlan): self
    {
        $this->lastCheckedPlan = $lastCheckedPlan;
        return $this;
    }

    public function getExpired(): ?DateTimeInterface
    {
        return $this->expired;
    }

    /**
     * @return mixed
     */
    public function getPaymentUser()
    {
        return $this->paymentUser;
    }

    /**
     * @param mixed $paymentUser
     */
    public function setPaymentUser($paymentUser): void
    {
        $this->paymentUser = $paymentUser;
    }

    public function setExpired(DateTimeInterface $expired): self
    {
        $this->expired = $expired;

        return $this;
    }

    public function isReminderMailSent(): ?bool
    {
        return $this->reminderMailSent;
    }

    public function setReminderMailSent(bool $testing_reminder_sent): self
    {
        $this->reminderMailSent = $testing_reminder_sent;

        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeInterface $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getRoutinePstatus(): ?DateTimeInterface
    {
        return $this->orgRoutinePStatus;
    }

    public function setRoutinePstatus(?DateTimeInterface $routine_pstatus): self
    {
        $this->orgRoutinePStatus = $routine_pstatus;

        return $this;
    }

    public function getRoutineGreminders(): ?DateTimeInterface
    {
        return $this->routineGreminders;
    }

    public function setRoutineGreminders(?DateTimeInterface $routine_greminders): self
    {
        $this->routineGreminders = $routine_greminders;

        return $this;
    }

    /**
     * @return ArrayCollection|Activity[]
     */
    public function getActivities()
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setOrganization($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
            // set the owning side to null (unless already changed)
            if ($activity->getOrganization() == $this) {
                $activity->setOrganization(null);
            }
        }

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
    public function getDepartments()
    {
        return $this->departments;
    }

    /**
     * @return mixed
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * @return mixed
     */
    public function getTitles()
    {
        return $this->titles;
    }

    /**
     * @param mixed $titles
     */
    public function setTitles($titles): void
    {
        $this->titles = $titles;
    }

    /**
     * @return ArrayCollection|Weight[]
     */
    public function getWeights()
    {
        return $this->weights;
    }

    /**
     * @return Decision[]|ArrayCollection
     */
    public function getDecisions()
    {
        return $this->decisions;
    }

    /**
     * @return ArrayCollection|Criterion[]
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @return ArrayCollection|Client[]
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @return mixed
     */
    public function getCriterionNames()
    {
        return $this->criterionNames;
    }

    /**
     * @param mixed $criterionNames
     */
    public function setCriterionNames($criterionNames): void
    {
        $this->criterionNames = $criterionNames;
    }

    /**
     * @return mixed
     */
    public function getUsersCSV()
    {
        return $this->usersCSV;
    }

    /**
     * @param mixed $usersCSV
     */
    public function setUsersCSV($usersCSV): void
    {
        $this->usersCSV = $usersCSV;
    }

    /**
     * @return string
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo(string $logo): void
    {
        $this->logo = $logo;
    }

    /**
     * @return mixed
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param mixed $teams
     */
    public function setTeams($teams): void
    {
        $this->teams = $teams;
    }

    /**
     * @return mixed
     */
    public function getMails()
    {
        return $this->mails;
    }

    /**
     * @param mixed $mails
     */
    public function setMails($mails): void
    {
        $this->mails = $mails;
    }

    /**
     * @return mixed
     */
    public function getTargets()
    {
        return $this->targets;
    }

    /**
     * @param mixed $targets
     */
    public function setTargets($targets): void
    {
        $this->targets = $targets;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return mixed
     */
    public function getProcesses()
    {
        return $this->processes;
    }

    /**
     * @return InstitutionProcess[]|ArrayCollection
     */
    public function getInstitutionProcesses()
    {
        return $this->institutionProcesses;
    }

    public function getCriterionGroups()
    {
        return $this->criterionGroups;
    }

    /**
     * @return WorkerFirm
     */
    public function getWorkerFirm()
    {
        return $this->workerFirm;
    }

    /**
     * @param WorkerFirm|null $workerFirm
     * @return Organization
     */
    public function setWorkerFirm(?WorkerFirm $workerFirm): Organization
    {
        $this->workerFirm = $workerFirm;
        return $this;
    }
    public function addDepartment(Department $department): Organization
    {

        $this->departments->add($department);
        $department->setOrganization($this);
        return $this;
    }

    public function removeDepartment(Department $department): Organization
    {
        $this->departments->removeElement($department);
        return $this;
    }
    public function addTitle(Title $title): Organization
    {

        $this->titles->add($title);
        $title->setOrganization($this);
        return $this;
    }

    public function removeTitle(Title $title): Organization
    {
        $this->titles->removeElement($title);
        return $this;
    }

    public function getActiveTeams(): ArrayCollection
    {
        $theTeams = new ArrayCollection;
        $teams = $this->teams;
        foreach($teams as $team){
            if($team->getDeleted() !== null){
                $theTeams->add($team);
            }
        }
        return $theTeams;
    }

    public function addPosition(Position $position): Organization
    {

        $this->positions->add($position);
        $position->setOrganization($this);
        return $this;
    }

    public function addOption(OrganizationUserOption $option): Organization
    {

        $this->options->add($option);
        $option->setOrganization($this);
        return $this;
    }

    public function removeOption(OrganizationUserOption $option): Organization
    {
        $this->options->removeElement($option);
        return $this;
    }

    /**
     * @return ArrayCollection|OrganizationUserOption[]
     */
    public function getAliveOptions()
    {
        return $this->options->filter(static function(OrganizationUserOption $o) {return $o->isEnabled();});
    }

    public function addAliveOption(OrganizationUserOption $option): Organization
    {

        $this->aliveOptions->add($option);
        $option->setOrganization($this);
        return $this;
    }

    public function removeAliveOption(OrganizationUserOption $option): Organization
    {
        $this->aliveOptions->removeElement($option);
        return $this;
    }

    public function addCriterion(Criterion $criterion): Organization
    {
        $this->criteria->add($criterion);
        $criterion->setOrganization($this);
        return $this;
    }

    public function removeCriterion(Criterion $criterion): Organization
    {
        $this->criteria->removeElement($criterion);
        return $this;
    }

    public function addWeight(Weight $weight): Organization
    {
        $this->weights->add($weight);
        $weight->setOrganization($this);
        return $this;
    }

    public function removeWeight(Weight $weight): Organization
    {
        $this->weights->removeElement($weight);
        return $this;
    }

    public function addDecision(Decision $decision): Organization
    {
        $this->decisions->add($decision);
        $decision->setOrganization($this);
        return $this;
    }

    public function removeDecision(Decision $decision): Organization
    {
        $this->decisions->removeElement($decision);
        return $this;
    }

    public function addTeam(Team $team): Organization
    {

        $this->teams->add($team);
        $team->setOrganization($this);
        return $this;
    }

    public function removeTeam(Team $team): Organization
    {
        $this->teams->removeElement($team);
        //$team->setOrganization(null);
        return $this;
    }
    /**
     * @return ArrayCollection|ExternalUser[]
     */
    public function getExternalUsers()
    {
        $extUsers = new ArrayCollection;
        foreach($this->clients as $client){
            foreach($client->getExternalUsers() as $externalUser){
                if($externalUser->getDeleted() === null){
                    $extUsers->add($externalUser);
                }
            }
        }
        return $extUsers;
    }

    public function addCriterionName(CriterionName $criterionName): Organization
    {

        $this->criterionNames->add($criterionName);
        $criterionName->setOrganization($this);
        return $this;
    }

    public function removeCriterionName(CriterionName $criterionName): Organization
    {
        $this->criterionNames->removeElement($criterionName);
        return $this;
    }

    public function addStage(Stage $stage): Organization
    {

        $this->stages->add($stage);
        //- $stage->setActivity($this);
        return $this;
    }

    public function removeStage(Stage $stage): Organization
    {
        $this->stages->removeElement($stage);
        //$stage->setActivity(null);
        return $this;
    }
    public function addMail(Mail $mail): Organization
    {
        $this->mails->add($mail);
        //- $mail->setUser($this);
        return $this;
    }

    public function removeMail(Mail $mail): Organization
    {
        $this->mails->removeElement($mail);
        return $this;
    }
    public function addTarget(Target $target): Organization
    {
        $this->targets->add($target);
        $target->setOrganization($this);
        return $this;
    }

    public function removeTarget(Target $target): Organization
    {
        $this->targets->removeElement($target);
        return $this;
    }
    public function addCriterionGroup(CriterionGroup $criterionGroup): Organization
    {
        $this->criterionGroups->add($criterionGroup);
        $criterionGroup->setOrganization($this);
        return $this;
    }

    public function removeCriterionGroup(CriterionGroup $criterionGroup): Organization
    {
        $this->criterionGroups->removeElement($criterionGroup);
        return $this;
    }
    public function addInstitutionProcess(InstitutionProcess $institutionProcess): Organization
    {
        $this->institutionProcesses->add($institutionProcess);
        $institutionProcess->setOrganization($this);
        return $this;
    }

    public function removeInstitutionProcess(InstitutionProcess $institutionProcess): Organization
    {
        $this->institutionProcesses->removeElement($institutionProcess);
        return $this;
    }
    public function addProcess(Process $process): Organization
    {
        $this->processes->add($process);
        $process->setOrganization($this);
        return $this;
    }

    public function removeProcess(Process $process): Organization
    {
        $this->processes->removeElement($process);
        $children = $process->getChildren();
        foreach($children as $child){
            $child->setParent(null);
        }
        return $this;
    }
    /**
     * @return ArrayCollection|Process[]
     */
    public function getParentValidatedProcesses() {
        return $this->processes->filter(static function(Process $p){
            return $p->getOrganization()->getId() === 1 && $p->getParent() === null && !$p->isApprovable();
        });
    }

    public function addParentValidatedProcess(Process $process): Organization
    {
        $this->addProcess($process);
        return $this;
    }

    public function removeParentValidatedProcess(Process $process): Organization
    {
        $this->removeProcess($process);
        return $this;
    }

    /**
     * @return Collection|InstitutionProcess[]
     */
    function getParentValidatedInstitutionProcesses() {

        return $this->institutionProcesses->filter(function(InstitutionProcess $p){
            return $p->getParent() == null && !$p->isApprovable();
        });
    }

    function addParentValidatedInstitutionProcess(InstitutionProcess $institutionProcess){
        $this->addInstitutionProcess($institutionProcess);
        return $this;
    }

    function removeParentValidatedInstitutionProcess(InstitutionProcess $institutionProcess){
        $this->removeInstitutionProcess($institutionProcess);
        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }

    /**
     * @return ArrayCollection|User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function addUser(User $user): Organization
    {
        $this->users->add($user);
        $user->setOrganization($this);
        return $this;
    }

    public function removeUser(User $user): Organization
    {
        $this->users->removeElement($user);
        return $this;
    }
    public function addPaymentMethods(OrganizationPaymentMethod $opm): Organization
    {
        $this->paymentMethods->add($opm);
        $opm->setOrganization($this);
        return $this;
    }

    public function removePaymentMethods(OrganizationPaymentMethod $opm): Organization
    {
        $this->users->removeElement($opm);
        return $this;
    }

    /**
     * @return ArrayCollection|OrganizationPaymentMethod []
     */
    public function getPaymentMethods()
    {
        return $this->paymentMethods;
    }

    /**
     * @param ArrayCollection $paymentMethods
     */
    public function setPaymentMethods(ArrayCollection $paymentMethods): void
    {
        $this->paymentMethods = $paymentMethods;
    }

    public function addClient(Client $client): Organization
    {
        $this->clients->add($client);
        $client->setOrganization($this);
        return $this;
    }

    public function removeClient(Client $client): Organization
    {
        $this->clients->removeElement($client);
        return $this;
    }

    public function getActiveUsers(){
        return $this->users->filter(function(User $u){
            return !$u->getDeleted() && $u->getLastname() == 'ZZ';
        });
    }

    public function hasActiveAdmin(){
        return $this->getActiveUsers()
        ->exists(function(int $i, User $u){
            return $u->getRole() == USER::ROLE_ADMIN && $u->getLastConnected() != null;
        });
    }

    public function getCompletedActivities(){
        return $this->getActivities()->filter(function(Activity $a){
            return $a->getStatus() >= ACTIVITY::STATUS_FINALIZED;
        });
    }

    public function getActiveActivities(){
        return $this->getActivities()->filter(function(Activity $a){
            return $a->getStatus() >= ACTIVITY::STATUS_REQUESTED && $a->getStatus() < ACTIVITY::STATUS_FINALIZED;
        });
    }

    public function getLastConnectedUser(){
        $users = $this->getUsers()->filter(function(User $u){
            return $u->getLastConnected() != null;
        });
        if($users->isEmpty()){return null;}
        $users = $users->getValues();
        uasort($users, function(User $a, User $b){
            return $a->getLastConnected() <=> $b->getLastConnected();
        });
        return $users[0];
    }

    public function getDefaultWeight(){
        return $this->weights->first();
    }

    public function getUsersWithoutJobInfo($entity = 'department'){
        return $this->getUsers()->filter(
            fn(User $u) =>  $entity == 'department' ? !$u->getDepartment() : ($entity == 'position' ? !$u->getPosition() : !$u->getTitle())
        );
    }

    function getPendingProcesses() {
        return $this->processes->filter(fn(Process $p) => $p->isApprovable());
    }

    function getPendingInstitutionProcesses() {
        return $this->institutionProcesses->filter(fn(InstitutionProcess $p) => $p->isApprovable());
    }

    /**
    * @return ArrayCollection|EventGroup[]
    */
    public function getEventGroups()
    {
        return $this->eventGroups;
    }

    public function addEventGroup(EventGroup $eventGroup): self
    {
        $this->eventGroups->add($eventGroup);
        $eventGroup->setOrganization($this);
        return $this;
    }

    public function removeEventGroup(EventGroup $eventGroup): self
    {
        $this->eventGroups->removeElement($eventGroup);
        return $this;
    }

    /**
    * @return ArrayCollection|EventType[]
    */
    public function getEventTypes()
    {
        return $this->eventTypes;
    }

    public function addEventType(EventType $eventType): self
    {
        $this->eventTypes->add($eventType);
        $eventType->setOrganization($this);
        return $this;
    }

    public function removeEventType(EventType $eventType): self
    {
        $this->eventTypes->removeElement($eventType);
        return $this;
    }
    
    /**
    * @return ArrayCollection|DynamicTranslation[]
    */
    public function getDTranslations()
    {
        return $this->dTranslations;
    }

    public function addDTranslation(DynamicTranslation $dTranslation): self
    {
        $this->dTranslations->add($dTranslation);
        $dTranslation->setOrganization($this);
        return $this;
    }

    public function removeDTranslation(DynamicTranslation $dTranslation): self
    {
        $this->dTranslations->removeElement($dTranslation);
        return $this;
    }

    /**
    * @return ArrayCollection|EventDocument[]
    */
    public function getEventDocuments()
    {
        return $this->eventDocuments;
    }

    public function addEventDocument(EventDocument $eventDocument): self
    {
        $this->eventDocuments->add($eventDocument);
        $eventDocument->setOrganization($this);
        return $this;
    }

    public function removeEventDocument(EventDocument $eventDocument): self
    {
        $this->eventDocuments->removeElement($eventDocument);
        return $this;
    }
    
    /**
    * @return ArrayCollection|EventComment[]
    */
    public function getEventComments()
    {
        return $this->eventComments;
    }

    public function addEventComment(EventComment $eventComment): self
    {
        $this->eventComments->add($eventComment);
        $eventComment->setOrganization($this);
        return $this;
    }

    public function removeEventComment(EventComment $eventComment): self
    {
        $this->eventComments->removeElement($eventComment);
        return $this;
    }

    /**
    * @return ArrayCollection|Event[]
    */
    public function getEvents()
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        $this->events->add($event);
        $event->setOrganization($this);
        return $this;
    }

    public function removeEvent(Event $event): self
    {
        $this->events->removeElement($event);
        return $this;
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
        $userMaster->setOrganization($this);
        return $this;
    }

    public function removeUserMaster(UserMaster $userMaster): self
    {
        $this->userMasters->removeElement($userMaster);
        return $this;
    }


    public function getExternalActivities(){
        $allExternalParticipations = [];
        foreach($this->users as $user){
            $externalUsers = $user->getExternalUsers();
            foreach($externalUsers as $externalUser){
                $extUserParticipations = $externalUser->getParticipations();
                foreach($extUserParticipations as $extUserParticipation){
                    $allExternalParticipations[] = $extUserParticipation;
                }
            }
        }

        $allExternalParticipations = new ArrayCollection($allExternalParticipations);
        $existingActivities = new ArrayCollection;
        return $allExternalParticipations->filter(function(Participation $p) use ($existingActivities){
            $activity = $p->getStage()->getActivity();
            if(!$existingActivities->contains($activity)){
                $existingActivities->add($activity);
                return true;
            } else {
                return false;
            }
        })->map(fn(Participation $p) => $p->getStage()->getActivity());

    }


}
