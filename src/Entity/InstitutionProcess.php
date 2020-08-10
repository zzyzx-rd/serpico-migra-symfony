<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\InstitutionProcessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=InstitutionProcessRepository::class)
 */
class InstitutionProcess
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="inp_id", type="integer", length=10)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $inp_name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $inp_approvable;

    /**
     * @ORM\Column(type="boolean")
     */
    private $inp_gradable;

    /**
     * @ORM\Column(type="integer")
     */
    private $inp_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $inp_isnerted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $inp_deleted;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;
    /**
     * @ManyToOne(targetEntity="Process")
     * @JoinColumn(name="process_pro_id", referencedColumnName="pro_id", nullable=false)
     */
    protected $process;
    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="masterUser_usr_id", referencedColumnName="usr_id", nullable=false)
     */
    protected $masterUser;
    /**
     * @OneToOne(targetEntity="InstitutionProcess")
     * @JoinColumn(name="parent_id", referencedColumnName="inp_id", nullable=true)
     */
    private $parent;
    /**
     * @OneToMany(targetEntity="InstitutionProcess", mappedBy="parent", cascade={"persist"}, orphanRemoval=false)
     */
    private $children;
    /**
     * @OneToMany(targetEntity="IProcessStage", mappedBy="institutionProcess", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $stages;
    /**
     * @OneToMany(targetEntity="Activity", mappedBy="institutionProcess", cascade={"persist","remove"})
     * @OrderBy({"status" = "ASC", "name" = "ASC"})
     */
    private $activities;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInpName(): ?string
    {
        return $this->inp_name;
    }

    public function setInpName(string $inp_name): self
    {
        $this->inp_name = $inp_name;

        return $this;
    }

    public function getInpApprovable(): ?bool
    {
        return $this->inp_approvable;
    }

    public function setInpApprovable(bool $inp_approvable): self
    {
        $this->inp_approvable = $inp_approvable;

        return $this;
    }

    public function getInpGradable(): ?bool
    {
        return $this->inp_gradable;
    }

    public function setInpGradable(bool $inp_gradable): self
    {
        $this->inp_gradable = $inp_gradable;

        return $this;
    }

    public function getInpCreatedBy(): ?int
    {
        return $this->inp_createdBy;
    }

    public function setInpCreatedBy(int $inp_createdBy): self
    {
        $this->inp_createdBy = $inp_createdBy;

        return $this;
    }

    public function getInpIsnerted(): ?\DateTimeInterface
    {
        return $this->inp_isnerted;
    }

    public function setInpIsnerted(\DateTimeInterface $inp_isnerted): self
    {
        $this->inp_isnerted = $inp_isnerted;

        return $this;
    }

    public function getInpDeleted(): ?\DateTimeInterface
    {
        return $this->inp_deleted;
    }

    public function setInpDeleted(?\DateTimeInterface $inp_deleted): self
    {
        $this->inp_deleted = $inp_deleted;

        return $this;
    }

    /**
     * @return Collection|Activity[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
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
