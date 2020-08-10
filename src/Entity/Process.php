<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProcessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ProcessRepository::class)
 */
class Process
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="pro_id", type="integer", length=10, nullable=false)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pro_name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pro_approvable;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pro_gradable;

    /**
     * @ORM\Column(type="integer")
     */
    private $pro_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $pro_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $pro_deleted;

    /**
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="process")
     */
    private $activities;

    /**
     *@ManyToOne(targetEntity="Organization")
     *@JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Process", inversedBy="children")
     * @JoinColumn(name="parent_id", referencedColumnName="pro_id", nullable=true)
     */
    private $parent;

    /**
     * @OneToMany(targetEntity="Process", mappedBy="parent", cascade={"persist"}, orphanRemoval=false)
     */
    private $children;

    /**
     * @ManyToOne(targetEntity="Icon")
     * @JoinColumn(name="icon_ico_id", referencedColumnName="ico_id", nullable=false)
     */
    protected $icon;

    /**
     * @OneToMany(targetEntity="InstitutionProcess", mappedBy="process", cascade={"persist"})
     */
    private $institutionProcesses;

    /**
     * @OneToMany(targetEntity="ProcessStage", mappedBy="process", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $stages;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProName(): ?string
    {
        return $this->pro_name;
    }

    public function setProName(string $pro_name): self
    {
        $this->pro_name = $pro_name;

        return $this;
    }

    public function getProApprovable(): ?bool
    {
        return $this->pro_approvable;
    }

    public function setProApprovable(bool $pro_approvable): self
    {
        $this->pro_approvable = $pro_approvable;

        return $this;
    }

    public function getProGradable(): ?bool
    {
        return $this->pro_gradable;
    }

    public function setProGradable(bool $pro_gradable): self
    {
        $this->pro_gradable = $pro_gradable;

        return $this;
    }

    public function getProCreatedBy(): ?int
    {
        return $this->pro_createdBy;
    }

    public function setProCreatedBy(int $pro_createdBy): self
    {
        $this->pro_createdBy = $pro_createdBy;

        return $this;
    }

    public function getProInserted(): ?\DateTimeInterface
    {
        return $this->pro_inserted;
    }

    public function setProInserted(\DateTimeInterface $pro_inserted): self
    {
        $this->pro_inserted = $pro_inserted;

        return $this;
    }

    public function getProDeleted(): ?\DateTimeInterface
    {
        return $this->pro_deleted;
    }

    public function setProDeleted(?\DateTimeInterface $pro_deleted): self
    {
        $this->pro_deleted = $pro_deleted;

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
            $activity->setProcess($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
            // set the owning side to null (unless already changed)
            if ($activity->getProcess() === $this) {
                $activity->setProcess(null);
            }
        }

        return $this;
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
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children): void
    {
        $this->children = $children;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @return mixed
     */
    public function getInstitutionProcesses()
    {
        return $this->institutionProcesses;
    }

    /**
     * @param mixed $institutionProcesses
     */
    public function setInstitutionProcesses($institutionProcesses): void
    {
        $this->institutionProcesses = $institutionProcesses;
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

}
