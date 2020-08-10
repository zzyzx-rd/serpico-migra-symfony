<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TemplateActivityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TemplateActivityRepository::class)
 */
class TemplateActivity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="act_id", type="integer", nullable=false)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $act_simplified;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $act_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $act_visibility;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $act_objectives;

    /**
     * @ORM\Column(type="integer")
     */
    private $act_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $act_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $act_saved;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=false)
     */
    protected $organization;

    /**
     *@ManyToOne(targetEntity="Department")
     *@JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id", nullable=false)
     */
    protected $department;

    /**
     * @ManyToOne(targetEntity="TemplateRecurring")
     * @JoinColumn(name="recurring_rct_id", referencedColumnName="rct_id",nullable=true)
     */
    protected $recurring;

    /**
     * @OneToMany(targetEntity="TemplateStage", mappedBy="activity", cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"startdate" = "ASC"})
     * @var Collection|TemplateStage[]
     */
    private $stages;

    /**
     * @OneToMany(targetEntity="TemplateActivityUser", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"team" = "ASC"})
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $act_master_usr;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActSimplified(): ?bool
    {
        return $this->act_simplified;
    }

    public function setActSimplified(bool $act_simplified): self
    {
        $this->act_simplified = $act_simplified;

        return $this;
    }

    public function getActName(): ?string
    {
        return $this->act_name;
    }

    public function setActName(string $act_name): self
    {
        $this->act_name = $act_name;

        return $this;
    }

    public function getActVisibility(): ?string
    {
        return $this->act_visibility;
    }

    public function setActVisibility(string $act_visibility): self
    {
        $this->act_visibility = $act_visibility;

        return $this;
    }

    public function getActObjectives(): ?string
    {
        return $this->act_objectives;
    }

    public function setActObjectives(string $act_objectives): self
    {
        $this->act_objectives = $act_objectives;

        return $this;
    }

    public function getActCreatedBy(): ?int
    {
        return $this->act_createdBy;
    }

    public function setActCreatedBy(int $act_createdBy): self
    {
        $this->act_createdBy = $act_createdBy;

        return $this;
    }

    public function getActInserted(): ?\DateTimeInterface
    {
        return $this->act_inserted;
    }

    public function setActInserted(\DateTimeInterface $act_inserted): self
    {
        $this->act_inserted = $act_inserted;

        return $this;
    }

    public function getActSaved(): ?\DateTimeInterface
    {
        return $this->act_saved;
    }

    public function setActSaved(\DateTimeInterface $act_saved): self
    {
        $this->act_saved = $act_saved;

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
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $department
     */
    public function setDepartment($department): void
    {
        $this->department = $department;
    }

    /**
     * @return mixed
     */
    public function getRecurring()
    {
        return $this->recurring;
    }

    /**
     * @param mixed $recurring
     */
    public function setRecurring($recurring): void
    {
        $this->recurring = $recurring;
    }

    /**
     * @return TemplateStage[]|Collection
     */
    public function getStages()
    {
        return $this->stages;
    }

    /**
     * @param TemplateStage[]|Collection $stages
     */
    public function setStages($stages): void
    {
        $this->stages = $stages;
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

    public function getActMasterUsr(): ?User
    {
        return $this->act_master_usr;
    }

    public function setActMasterUsr(?User $act_master_usr): self
    {
        $this->act_master_usr = $act_master_usr;

        return $this;
    }

}
