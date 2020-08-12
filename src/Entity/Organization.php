<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;
use Model\OrganizationUserOption;
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
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $org_legalname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $org_commname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $org_type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $org_isClient;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $org_oth_language;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $org_weight_type;

    /**
     * @ORM\Column(type="integer")
     */
    private $org_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $org_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $org_validated;

    /**
     * @ORM\Column(type="datetime")
     */
    private $org_expired;

    /**
     * @ORM\Column(type="boolean")
     */
    private $org_testing_reminder_sent;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $org_deleted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $org_routine_pstatus;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $org_routine_greminders;

    /**
     * @OneToMany(targetEntity="Stage", mappedBy="organization", cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"startdate" = "ASC"})
     */
    private $stages;
    /**
     * @OneToMany(targetEntity="Department", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
     * @OrderBy({"name" = "ASC"})
     */
    private $departments;
    /**
     * @OneToMany(targetEntity="Position", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
     * @OrderBy({"name" = "ASC"})
     */
    private $positions;
    /**
     * @OneToMany(targetEntity="Title", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
     * @OrderBy({"name" = "ASC"})
     */
    private $titles;
    /**
     * @OneToMany(targetEntity="Weight", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
     * @OrderBy({"value" = "ASC"})
     */
    private $weights;
    /**
     * @OneToMany(targetEntity="Decision", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
     * @OrderBy({"inserted" = "ASC"})
     */
    private $decisions;
    /**
     * @OneToMany(targetEntity="Activity", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
     * @OrderBy({"status" = "ASC", "name" = "ASC"})
     */
    private $activities;
    /**
     * @OneToMany(targetEntity="Criterion", mappedBy="organization", cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"type" = "ASC", "inserted" = "ASC"})
     */
    private $criteria;
    /**
     * @OneToMany(targetEntity="TemplateActivity", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
     * @OrderBy({"name" = "ASC"})
     */
    private $templateActivities;
    /**
     * @OneToMany(targetEntity="Client", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
     * @var ArrayCollection|Client[]
     */
    private $clients;

    /**
     * @OneToMany(targetEntity="CriterionName", mappedBy="organization", cascade={"persist", "remove"},orphanRemoval=true)
     * @OrderBy({"type" = "DESC", "name" = "ASC"})
     */
    private $criterionNames;

    /**
     * @Column(name="org_users_CSV", type="string")
     * @Assert\File(mimeTypes={ "text/csv" })
     */
    private $usersCSV;
    /**
     * @Column(name="org_logo", type="string", nullable=true)
     * @var string
     */
    protected $logo;
    /**
     * @OneToMany(targetEntity="Team", mappedBy="organization",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $teams;
    /**
     * @OneToMany(targetEntity="Mail", mappedBy="organization", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $mails;
    /**
     * @OneToMany(targetEntity="Target", mappedBy="organization",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $targets;
    /**
     * @OneToMany(targetEntity="OrganizationUserOption", mappedBy="organization", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $options;
    /**
     * @OneToMany(targetEntity="Process", mappedBy="organization", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $processes;
    /**
     * @OneToMany(targetEntity="InstitutionProcess", mappedBy="organization", cascade={"persist","remove"}, orphanRemoval=true)
     * @var ArrayCollection|InstitutionProcess[]
     */
    private $institutionProcesses;

    /**
     * @OneToMany(targetEntity="CriterionGroup", mappedBy="organization", cascade={"persist","remove"}, orphanRemoval=true)
     * @var CriterionGroup[]
     */
    protected $criterionGroups;
    /**
     * @OneToOne(targetEntity="WorkerFirm")
     * @JoinColumn(name="worker_firm_wfi_id", referencedColumnName="wfi_id")
     */
    private $workerFirm;

    /**
     * Organization constructor.
     * @param int $id
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
     * @param $templateActivities
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
        int $id = 0,
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
        $stages = null,
        $departments = null,
        $positions = null,
        $titles = null,
        $weights = null,
        $decisions = null,
        $activities = null,
        $criteria = null,
        $templateActivities = null,
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
        $this->org_legalname = $org_legalname;
        $this->org_commname = $org_commname;
        $this->org_type = $org_type;
        $this->org_isClient = $org_isClient;
        $this->org_oth_language = $org_oth_language;
        $this->org_weight_type = $org_weight_type;
        $this->org_inserted = $org_inserted;
        $this->org_validated = $org_validated;
        $this->org_expired = $org_expired;
        $this->org_testing_reminder_sent = $org_testing_reminder_sent;
        $this->org_deleted = $org_deleted;
        $this->org_routine_pstatus = $org_routine_pstatus;
        $this->org_routine_greminders = $org_routine_greminders;
        $this->stages = $stages;
        $this->departments = $departments?$departments: new ArrayCollection();
        $this->positions = $positions?$positions : new ArrayCollection();
        $this->titles = $titles?$titles : new ArrayCollection();
        $this->weights = $weights? $weights : new ArrayCollection();
        $this->decisions = $decisions? $decisions: new ArrayCollection();
        $this->activities = $activities?$activities : new ArrayCollection();
        $this->criteria = $criteria;
        $this->templateActivities = $templateActivities?$templateActivities:new ArrayCollection();
        $this->clients = $clients?$clients: new ArrayCollection();
        $this->criterionNames = $criterionNames?$criterionNames: new ArrayCollection();
        $this->usersCSV = $usersCSV;
        $this->logo = $logo;
        $this->teams = $teams;
        $this->mails = $mails;
        $this->targets = $targets?$targets: new ArrayCollection();
        $this->options = $options;
        $this->processes = $processes?$processes: new ArrayCollection();
        $this->institutionProcesses = $institutionProcesses?$institutionProcesses: new ArrayCollection();
        $this->criterionGroups = $criterionGroups;
        $this->workerFirm = $workerFirm;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLegalname(): ?string
    {
        return $this->org_legalname;
    }

    public function setLegalname(string $org_legalname): self
    {
        $this->org_legalname = $org_legalname;

        return $this;
    }

    public function getCommname(): ?string
    {
        return $this->org_commname;
    }

    public function setCommname(string $org_commname): self
    {
        $this->org_commname = $org_commname;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->org_type;
    }

    public function setType(string $org_type): self
    {
        $this->org_type = $org_type;

        return $this;
    }

    public function getIsClient(): ?bool
    {
        return $this->org_isClient;
    }

    public function setIsClient(bool $org_isClient): self
    {
        $this->org_isClient = $org_isClient;

        return $this;
    }

    public function getOthLanguage(): ?string
    {
        return $this->org_oth_language;
    }

    public function setOthLanguage(string $org_oth_language): self
    {
        $this->org_oth_language = $org_oth_language;

        return $this;
    }

    public function getWeightType(): ?string
    {
        return $this->org_weight_type;
    }

    public function setWeightType(string $org_weight_type): self
    {
        $this->org_weight_type = $org_weight_type;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->org_createdBy;
    }

    public function setCreatedBy(int $org_createdBy): self
    {
        $this->org_createdBy = $org_createdBy;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->org_inserted;
    }

    public function setInserted(\DateTimeInterface $org_inserted): self
    {
        $this->org_inserted = $org_inserted;

        return $this;
    }

    public function getValidated(): ?\DateTimeInterface
    {
        return $this->org_validated;
    }

    public function setValidated(\DateTimeInterface $org_validated): self
    {
        $this->org_validated = $org_validated;

        return $this;
    }

    public function getExpired(): ?\DateTimeInterface
    {
        return $this->org_expired;
    }

    public function setExpired(\DateTimeInterface $org_expired): self
    {
        $this->org_expired = $org_expired;

        return $this;
    }

    public function isReminderMailSent(): ?bool
    {
        return $this->org_testing_reminder_sent;
    }

    public function setTestingReminderSent(bool $org_testing_reminder_sent): self
    {
        $this->org_testing_reminder_sent = $org_testing_reminder_sent;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->org_deleted;
    }

    public function setDeleted(?\DateTimeInterface $org_deleted): self
    {
        $this->org_deleted = $org_deleted;

        return $this;
    }

    public function getRoutinePstatus(): ?\DateTimeInterface
    {
        return $this->org_routine_pstatus;
    }

    public function setRoutinePstatus(?\DateTimeInterface $org_routine_pstatus): self
    {
        $this->org_routine_pstatus = $org_routine_pstatus;

        return $this;
    }

    public function getRoutineGreminders(): ?\DateTimeInterface
    {
        return $this->org_routine_greminders;
    }

    public function setRoutineGreminders(?\DateTimeInterface $org_routine_greminders): self
    {
        $this->org_routine_greminders = $org_routine_greminders;

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
            if ($activity->getanization() === $this) {
                $activity->setanization(null);
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
     * @param mixed $departments
     */
    public function setDepartments($departments): void
    {
        $this->departments = $departments;
    }

    /**
     * @return mixed
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * @param mixed $positions
     */
    public function setPositions($positions): void
    {
        $this->positions = $positions;
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
     * @return mixed
     */
    public function getWeights()
    {
        return $this->weights;
    }

    /**
     * @param mixed $weights
     */
    public function setWeights($weights): void
    {
        $this->weights = $weights;
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
    public function getTemplateActivities()
    {
        return $this->templateActivities;
    }

    /**
     * @param mixed $templateActivities
     */
    public function setTemplateActivities($templateActivities): void
    {
        $this->templateActivities = $templateActivities;
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
    public function setClients($clients): void
    {
        $this->clients = $clients;
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
    public function getLogo(): string
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
     * @param mixed $options
     */
    public function setOptions($options): void
    {
        $this->options = $options;
    }

    /**
     * @return mixed
     */
    public function getProcesses()
    {
        return $this->processes;
    }

    /**
     * @param mixed $processes
     */
    public function setProcesses($processes): void
    {
        $this->processes = $processes;
    }

    /**
     * @return InstitutionProcess[]|ArrayCollection
     */
    public function getInstitutionProcesses()
    {
        return $this->institutionProcesses;
    }

    /**
     * @param InstitutionProcess[]|ArrayCollection $institutionProcesses
     */
    public function setInstitutionProcesses($institutionProcesses): void
    {
        $this->institutionProcesses = $institutionProcesses;
    }

    /**
     * @return CriterionGroup[]
     */
    public function getCriterionGroups(): array
    {
        return $this->criterionGroups;
    }

    /**
     * @param CriterionGroup[] $criterionGroups
     */
    public function setCriterionGroups(array $criterionGroups): void
    {
        $this->criterionGroups = $criterionGroups;
    }

    /**
     * @return mixed
     */
    public function getWorkerFirm()
    {
        return $this->workerFirm;
    }

    /**
     * @param mixed $workerFirm
     */
    public function setWorkerFirm($workerFirm): void
    {
        $this->workerFirm = $workerFirm;
    }
    function addDepartment(Department $department){

        $this->departments->add($department);
        $department->setOrganization($this);
        return $this;
    }

    function removeDepartment(Department $department){
        $this->departments->removeElement($department);
        return $this;
    }
    function addTitle(Title $title){

        $this->titles->add($title);
        $title->setOrganization($this);
        return $this;
    }

    function removeTitle(Title $title){
        $this->titles->removeElement($title);
        return $this;
    }

    public function getActiveTeams()
    {
        $theTeams = new ArrayCollection;
        $teams = $this->teams;
        foreach($teams as $team){
            if($team->getDeleted() != null){
                $theTeams->add($team);
            }
        }
        return $theTeams;
    }

    function addPosition(Position $position){

        $this->positions->add($position);
        $position->setOrganization($this);
        return $this;
    }

    function addOption(OrganizationUserOption $option){

        $this->options->add($option);
        $option->setOrganization($this);
        return $this;
    }

    function removeOption(OrganizationUserOption $option){
        $this->options->removeElement($option);
        return $this;
    }

    /**
     * @return ArrayCollection|OrganizationUserOption[]
     */
    public function getAliveOptions()
    {
        return $this->options->filter(function(OrganizationUserOption $o) {return $o->isEnabled();});
    }

    function addAliveOption(OrganizationUserOption $option){

        $this->aliveOptions->add($option);
        $option->setOrganization($this);
        return $this;
    }

    function removeAliveOption(OrganizationUserOption $option){
        $this->aliveOptions->removeElement($option);
        return $this;
    }
    function addCriterion(Criterion $criterion){
        $this->criteria->add($criterion);
        $criterion->setOrganization($this);
        return $this;
    }

    function removeCriterion(Criterion $criterion){
        $this->criteria->removeElement($criterion);
        return $this;
    }
    function addWeight(Weight $weight){

        $this->weights->add($weight);
        $weight->setOrganization($this);
        return $this;
    }

    function removeWeight(Weight $weight){
        $this->weights->removeElement($weight);
        return $this;
    }

    function addDecision(Decision $decision){

        $this->decisions->add($decision);
        $decision->setOrganization($this);
        return $this;
    }

    function removeDecision(Decision $decision){
        $this->decisions->removeElement($decision);
        return $this;
    }

    function addRecurring(Recurring $recurring){

        $this->recurrings->add($recurring);
        $recurring->setOrganization($this);
        return $this;
    }

    function removeRecurring(Recurring $recurring){
        $this->recurrings->removeElement($recurring);
        return $this;
    }

    function addTeam(Team $team){

        $this->teams->add($team);
        $team->setOrganization($this);
        return $this;
    }

    function removeTeam(Team $team){
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
                if($externalUser->getDeleted() == null){
                    $extUsers->add($externalUser);
                }
            }
        }
        return $extUsers;
    }

    function addCriterionName(CriterionName $criterionName){

        $this->criterionNames->add($criterionName);
        $criterionName->setOrganization($this);
        return $this;
    }

    function removeCriterionName(CriterionName $criterionName){
        $this->criterionNames->removeElement($criterionName);
        return $this;
    }
    function addTemplateActivity(TemplateActivity $templateActivity){
        $this->templateActivities->add($templateActivity);
        $templateActivity->setOrganization($this);
        return $this;
    }

    function removeTemplateActivity(TemplateActivity $templateActivity){
        $this->templateActivities->removeElement($templateActivity);
        return $this;
    }
    function addStage(Stage $stage){

        $this->stages->add($stage);
        //- $stage->setActivity($this);
        return $this;
    }

    function removeStage(Stage $stage){
        $this->stages->removeElement($stage);
        //$stage->setActivity(null);
        return $this;
    }
    function addMail(Mail $mail){
        $this->mails->add($mail);
        //- $mail->setUser($this);
        return $this;
    }

    function removeMail(Mail $mail){
        $this->mails->removeElement($mail);
        return $this;
    }
    function addTarget(Target $target){
        $this->targets->add($target);
        $target->setOrganization($this);
        return $this;
    }

    function removeTarget(Target $target){
        $this->targets->removeElement($target);
        return $this;
    }
    function addCriterionGroup(CriterionGroup $criterionGroup){
        $this->criterionGroups->add($criterionGroup);
        $criterionGroup->setOrganization($this);
        return $this;
    }

    function removeCriterionGroup(CriterionGroup $criterionGroup){
        $this->criterionGroups->removeElement($criterionGroup);
        return $this;
    }
    function addInstitutionProcess(InstitutionProcess $institutionProcess){
        $this->institutionProcesses->add($institutionProcess);
        $institutionProcess->setOrganization($this);
        return $this;
    }

    function removeInstitutionProcess(InstitutionProcess $institutionProcess){
        $this->institutionProcesses->removeElement($institutionProcess);
        return $this;
    }
    function addProcess(Process $process){
        $this->processes->add($process);
        $process->setOrganization($this);
        return $this;
    }

    function removeProcess(Process $process){
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
    function getParentValidatedProcesses() {
        return $this->processes->filter(function(Process $p){
            return $p->getOrganization()->getId() == 1 && $p->getParent() == null && !$p->isApprovable();
        });
    }

    function addParentValidatedProcess(Process $process){
        $this->addProcess($process);
        return $this;
    }

    function removeParentValidatedProcess(Process $process){
        $this->removeProcess($process);
        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }

    public function hasActiveAdmin(){
        return $this->getActiveUsers()
            ->exists(function(int $i, User $u){
                return $u->getRole() == USER::ROLE_ADMIN && $u->getLastConnected() != null;
            });
    }
    //TODO userSortedDepartement et le removePosition

}
