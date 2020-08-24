<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
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
     * @ORM\Column(name="org_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="org_validated", type="datetime", nullable=true)
     */
    public $validated;

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
     * @OneToMany(targetEntity="Weight", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
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
     * @Column(name="org_logo", type="string", nullable=true)
     * @var string
     */
    protected $logo;
    /**
     * @OneToMany(targetEntity="Team", mappedBy="organization",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $teams;
    /**
     * @OneToMany(targetEntity="Mail", mappedBy="organization", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $mails;
    /**
     * @OneToMany(targetEntity="Target", mappedBy="organization",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $targets;
    /**
     * @OneToMany(targetEntity="OrganizationUserOption", mappedBy="organization", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $options;
    /**
     * @OneToMany(targetEntity="Process", mappedBy="organization", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $processes;
    /**
     * @OneToMany(targetEntity="User", mappedBy="organization", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $users;
    /**
     * @OneToMany(targetEntity="InstitutionProcess", mappedBy="organization", cascade={"persist","remove"}, orphanRemoval=true)
     * @var ArrayCollection|InstitutionProcess[]
     */
    public $institutionProcesses;

    /**
     * @OneToMany(targetEntity="CriterionGroup", mappedBy="organization", cascade={"persist","remove"}, orphanRemoval=true)
     * @var CriterionGroup[]
     */
    protected $criterionGroups;

    /**
     * @ORM\OneToOne(targetEntity=WorkerFirm::class, inversedBy="organization", cascade={"persist", "remove"})
     * @JoinColumn(name="worker_firm_wfi_id", referencedColumnName="wfi_id", nullable=true)
     */
    private $worker_firm_wfi;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @JoinColumn(name="master_user_id", referencedColumnName="usr_id", nullable=true)
     */
    private $masterUser;
    

    /**
     * Organization constructor.
     * @param ?int$id
     * @param $org_legalname
     * @param $org_commname
     * @param $org_type
     * @param $org_isClient
     * @param $org_oth_language
     * @param $org_weight_type
     * @param $org_createdBy
     * @param $org_inserted
     * @param $org_validated
     * @param $org_expired
     * @param $org_testing_reminder_sent
     * @param $org_deleted
     * @param $org_routine_pstatus
     * @param $org_routine_greminders
     * @param $stages
     * @param $departments
     * @param $positions
     * @param $titles
     * @param $weights
     * @param $decisions
     * @param $activities
     * @param $criteria
     * @param Client[]|ArrayCollection $clients
     * @param $criterionNames
     * @param $usersCSV
     * @param string $logo
     * @param $teams
     * @param $mails
     * @param $targets
     * @param $options
     * @param $processes
     * @param InstitutionProcess[]|ArrayCollection $institutionProcesses
     * @param CriterionGroup[] $criterionGroups
     * @param $workerFirm
     */
    public function __construct(
      ?int $id = 0,
        $org_isClient = null,
        $org_legalname = '',
        $org_commname = '',
        $org_type = '',
        $org_oth_language = 'FR',
        string $logo = null,
        $org_weight_type = "",
        $usersCSV = '',
        $org_createdBy = null,
        $org_inserted = null,
        $org_validated = null,
        $org_expired = null,
        $org_testing_reminder_sent = null,
        $org_deleted = null,
        $org_routine_pstatus = null,
        $org_routine_greminders = null,
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
        $institutionProcesses = null,
        array $criterionGroups = null,
        $workerFirm = null)
    {
        parent::__construct($id, $org_createdBy, new DateTime());
        $this->legalname = $org_legalname;
        $this->commname = $org_commname;
        $this->type = $org_type;
        $this->isClient = $org_isClient;
        $this->oth_language = $org_oth_language;
        $this->weight_type = $org_weight_type;
        $this->validated = $org_validated;
        $this->expired = $org_expired;
        $this->reminderMailSent = $org_testing_reminder_sent;
        $this->deleted = $org_deleted;
        $this->orgRoutinePStatus = $org_routine_pstatus;
        $this->routineGreminders = $org_routine_greminders;
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
        $this->options = $options;
        $this->processes = $processes?: new ArrayCollection();
        $this->institutionProcesses = $institutionProcesses?: new ArrayCollection();
        $this->criterionGroups = $criterionGroups;
        $this->workerFirm = $workerFirm;
    }


    public function getOrgId(): ?int
    {
        return $this->id;
    }

    public function getLegalname(): ?string
    {
        return $this->legalname;
    }

    public function setLegalname(string $org_legalname): self
    {
        $this->legalname = $org_legalname;

        return $this;
    }

    public function getCommname(): ?string
    {
        return $this->commname;
    }

    public function setCommname(string $org_commname): self
    {
        $this->commname = $org_commname;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $org_type): self
    {
        $this->type = $org_type;

        return $this;
    }

    public function getIsClient(): ?bool
    {
        return $this->isClient;
    }

    public function setIsClient(bool $org_isClient): self
    {
        $this->isClient = $org_isClient;

        return $this;
    }

    public function getOthLanguage(): ?string
    {
        return $this->oth_language;
    }

    public function setOthLanguage(string $org_oth_language): self
    {
        $this->oth_language = $org_oth_language;

        return $this;
    }

    public function getWeightType(): ?string
    {
        return $this->weight_type;
    }

    public function setWeightType(string $org_weight_type): self
    {
        $this->weight_type = $org_weight_type;

        return $this;
    }

    public function setInserted(DateTimeInterface $org_inserted): self
    {
        $this->inserted = $org_inserted;

        return $this;
    }

    public function getValidated(): ?DateTimeInterface
    {
        return $this->validated;
    }

    public function setValidated(DateTimeInterface $org_validated): self
    {
        $this->validated = $org_validated;

        return $this;
    }

    public function getExpired(): ?DateTimeInterface
    {
        return $this->expired;
    }

    public function setExpired(DateTimeInterface $org_expired): self
    {
        $this->expired = $org_expired;

        return $this;
    }

    public function isReminderMailSent(): ?bool
    {
        return $this->reminderMailSent;
    }

    public function setReminderMailSent(bool $org_testing_reminder_sent): self
    {
        $this->reminderMailSent = $org_testing_reminder_sent;

        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeInterface $org_deleted): self
    {
        $this->deleted = $org_deleted;

        return $this;
    }

    public function getRoutinePstatus(): ?DateTimeInterface
    {
        return $this->orgRoutinePStatus;
    }

    public function setRoutinePstatus(?DateTimeInterface $org_routine_pstatus): self
    {
        $this->orgRoutinePStatus = $org_routine_pstatus;

        return $this;
    }

    public function getRoutineGreminders(): ?DateTimeInterface
    {
        return $this->routineGreminders;
    }

    public function setRoutineGreminders(?DateTimeInterface $org_routine_greminders): self
    {
        $this->routineGreminders = $org_routine_greminders;

        return $this;
    }

    /**
     * @return Collection|Activity[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setanization($this);
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
    public function setStages($stages)
    {
        $this->stages = $stages;
        return $this;
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
    public function setTitles($titles)
    {
        $this->titles = $titles;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeights()
    {
        return $this->weights;
    }

    /**
     * @param mixed $weights
     */
    public function setWeights($weights)
    {
        $this->weights = $weights;
        return $this;
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
    public function setDecisions($decisions)
    {
        $this->decisions = $decisions;
        return $this;
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
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
        return $this;
    }


    /**
     * @return Client[]|ArrayCollection
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param Client[]|ArrayCollection $clients
     */
    public function setClients($clients)
    {
        $this->clients = $clients;
        return $this;
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
    public function setCriterionNames($criterionNames)
    {
        $this->criterionNames = $criterionNames;
        return $this;
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
    public function setUsersCSV($usersCSV)
    {
        $this->usersCSV = $usersCSV;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogo(): string
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo(string $logo)
    {
        $this->logo = $logo;
        return $this;
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
    public function setTeams($teams)
    {
        $this->teams = $teams;
        return $this;
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
    public function setMails($mails)
    {
        $this->mails = $mails;
        return $this;
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
    public function setTargets($targets)
    {
        $this->targets = $targets;
        return $this;
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
     * @return mixed
     */
    public function getWorkerFirm()
    {
        return $this->worker_firm_wfi;
    }

    /**
     * @param mixed $workerFirm
     * @return Organization
     */
    public function setWorkerFirm($workerFirm): Organization
    {
        $this->worker_firm_wfi = $workerFirm;
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
     * @return Collection|Process[]
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
    public function __toString()
    {
        return (string) $this->id;
    }


    //TODO userSortedDepartement et le removePosition

    public function getMasterUser(): ?User
    {
        return $this->masterUser;
    }

    public function setMasterUser(?User $masterUser): self
    {
        $this->masterUser = $masterUser;

        return $this;
    }

    /**
     * @return Collection|User[]
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


}
