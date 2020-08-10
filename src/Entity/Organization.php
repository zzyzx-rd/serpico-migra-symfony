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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=OrganizationRepository::class)
 */
class Organization
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
    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrgLegalname(): ?string
    {
        return $this->org_legalname;
    }

    public function setOrgLegalname(string $org_legalname): self
    {
        $this->org_legalname = $org_legalname;

        return $this;
    }

    public function getOrgCommname(): ?string
    {
        return $this->org_commname;
    }

    public function setOrgCommname(string $org_commname): self
    {
        $this->org_commname = $org_commname;

        return $this;
    }

    public function getOrgType(): ?string
    {
        return $this->org_type;
    }

    public function setOrgType(string $org_type): self
    {
        $this->org_type = $org_type;

        return $this;
    }

    public function getOrgIsClient(): ?bool
    {
        return $this->org_isClient;
    }

    public function setOrgIsClient(bool $org_isClient): self
    {
        $this->org_isClient = $org_isClient;

        return $this;
    }

    public function getOrgOthLanguage(): ?string
    {
        return $this->org_oth_language;
    }

    public function setOrgOthLanguage(string $org_oth_language): self
    {
        $this->org_oth_language = $org_oth_language;

        return $this;
    }

    public function getOrgWeightType(): ?string
    {
        return $this->org_weight_type;
    }

    public function setOrgWeightType(string $org_weight_type): self
    {
        $this->org_weight_type = $org_weight_type;

        return $this;
    }

    public function getOrgCreatedBy(): ?int
    {
        return $this->org_createdBy;
    }

    public function setOrgCreatedBy(int $org_createdBy): self
    {
        $this->org_createdBy = $org_createdBy;

        return $this;
    }

    public function getOrgInserted(): ?\DateTimeInterface
    {
        return $this->org_inserted;
    }

    public function setOrgInserted(\DateTimeInterface $org_inserted): self
    {
        $this->org_inserted = $org_inserted;

        return $this;
    }

    public function getOrgValidated(): ?\DateTimeInterface
    {
        return $this->org_validated;
    }

    public function setOrgValidated(\DateTimeInterface $org_validated): self
    {
        $this->org_validated = $org_validated;

        return $this;
    }

    public function getOrgExpired(): ?\DateTimeInterface
    {
        return $this->org_expired;
    }

    public function setOrgExpired(\DateTimeInterface $org_expired): self
    {
        $this->org_expired = $org_expired;

        return $this;
    }

    public function getOrgTestingReminderSent(): ?bool
    {
        return $this->org_testing_reminder_sent;
    }

    public function setOrgTestingReminderSent(bool $org_testing_reminder_sent): self
    {
        $this->org_testing_reminder_sent = $org_testing_reminder_sent;

        return $this;
    }

    public function getOrgDeleted(): ?\DateTimeInterface
    {
        return $this->org_deleted;
    }

    public function setOrgDeleted(?\DateTimeInterface $org_deleted): self
    {
        $this->org_deleted = $org_deleted;

        return $this;
    }

    public function getOrgRoutinePstatus(): ?\DateTimeInterface
    {
        return $this->org_routine_pstatus;
    }

    public function setOrgRoutinePstatus(?\DateTimeInterface $org_routine_pstatus): self
    {
        $this->org_routine_pstatus = $org_routine_pstatus;

        return $this;
    }

    public function getOrgRoutineGreminders(): ?\DateTimeInterface
    {
        return $this->org_routine_greminders;
    }

    public function setOrgRoutineGreminders(?\DateTimeInterface $org_routine_greminders): self
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
            $activity->setOrganization($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
            // set the owning side to null (unless already changed)
            if ($activity->getOrganization() === $this) {
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

}
