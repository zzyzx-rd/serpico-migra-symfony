<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 */
class Department
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="dpt_id", type="integer", length=10, nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $dpt_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $dpt_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dpt_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dpt_deleted;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="masterUser_usr_id", referencedColumnName="usr_id", nullable=false)
     */
    protected $masterUser;

    /**
     * @OneToMany(targetEntity="Position", mappedBy="department", cascade={"persist", "remove"})
     * @OrderBy({"name" = "ASC"})
     */
    private $positions;

    /**
     * @OneToMany(targetEntity="TemplateActivity", mappedBy="department", cascade={"persist", "remove"},orphanRemoval=true)
     * @OrderBy({"name" = "ASC"})
     */
    private $templateActivities;

    /**
     * @OneToMany(targetEntity="OrganizationUserOption", mappedBy="department", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $options;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    /**
     * @OneToMany(targetEntity="CriterionGroup", mappedBy="department")
     * @var ArrayCollection
     */
    protected $criterionGroups;

    /**
     * @OneToMany(targetEntity="Target", mappedBy="department",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $targets;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDptName(): ?string
    {
        return $this->dpt_name;
    }

    public function setDptName(string $dpt_name): self
    {
        $this->dpt_name = $dpt_name;

        return $this;
    }

    public function getDptCreatedBy(): ?int
    {
        return $this->dpt_createdBy;
    }

    public function setDptCreatedBy(int $dpt_createdBy): self
    {
        $this->dpt_createdBy = $dpt_createdBy;

        return $this;
    }

    public function getDptInserted(): ?\DateTimeInterface
    {
        return $this->dpt_inserted;
    }

    public function setDptInserted(\DateTimeInterface $dpt_inserted): self
    {
        $this->dpt_inserted = $dpt_inserted;

        return $this;
    }

    public function getDptDeleted(): ?\DateTimeInterface
    {
        return $this->dpt_deleted;
    }

    public function setDptDeleted(?\DateTimeInterface $dpt_deleted): self
    {
        $this->dpt_deleted = $dpt_deleted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMasterUser()
    {
        return $this->masterUser;
    }

    /**
     * @param mixed $masterUser
     */
    public function setMasterUser($masterUser): void
    {
        $this->masterUser = $masterUser;
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
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param mixed $organization
     */
    public function setOrganization($organization): void
    {
        $this->organization = $organization;
    }

    /**
     * @return ArrayCollection
     */
    public function getCriterionGroups(): ArrayCollection
    {
        return $this->criterionGroups;
    }

    /**
     * @param ArrayCollection $criterionGroups
     */
    public function setCriterionGroups(ArrayCollection $criterionGroups): void
    {
        $this->criterionGroups = $criterionGroups;
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

}
