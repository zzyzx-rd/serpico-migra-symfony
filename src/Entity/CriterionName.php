<?php

namespace App\Entity;

use App\Repository\CriterionNameRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity(repositoryClass=CriterionNameRepository::class)
 */
class CriterionName
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="cna_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cna_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cna_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $can_unit;

    /**
     * @ORM\Column(type="integer")
     */
    private $can_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $can_inserted;

    /**
     * @ManyToOne(targetEntity="Icon")
     * @JoinColumn(name="icon_ico_id", referencedColumnName="ico_id",nullable=false)
     * @var Icon
     */
    protected $icon;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     * @var Organization
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Department")
     * @JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id")
     * @var Department|null
     */
    protected $department;

    /**
     * @ManyToOne(targetEntity="CriterionGroup", inversedBy="criteria")
     * @JoinColumn(name="criterion_group_cgp_id", referencedColumnName="cgp_id")
     * @var CriterionGroup
     */
    protected $criterionGroup;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCnaType(): ?int
    {
        return $this->cna_type;
    }

    public function setCnaType(int $cna_type): self
    {
        $this->cna_type = $cna_type;

        return $this;
    }

    public function getCnaName(): ?string
    {
        return $this->cna_name;
    }

    public function setCnaName(string $cna_name): self
    {
        $this->cna_name = $cna_name;

        return $this;
    }

    public function getCanUnit(): ?string
    {
        return $this->can_unit;
    }

    public function setCanUnit(string $can_unit): self
    {
        $this->can_unit = $can_unit;

        return $this;
    }

    public function getCanCreatedBy(): ?int
    {
        return $this->can_createdBy;
    }

    public function setCanCreatedBy(int $can_createdBy): self
    {
        $this->can_createdBy = $can_createdBy;

        return $this;
    }

    public function getCanInserted(): ?\DateTimeInterface
    {
        return $this->can_inserted;
    }

    public function setCanInserted(\DateTimeInterface $can_inserted): self
    {
        $this->can_inserted = $can_inserted;

        return $this;
    }

    /**
     * @return Icon
     */
    public function getIcon(): Icon
    {
        return $this->icon;
    }

    /**
     * @param Icon $icon
     */
    public function setIcon(Icon $icon): void
    {
        $this->icon = $icon;
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
     * @return Department|null
     */
    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    /**
     * @param Department|null $department
     */
    public function setDepartment(?Department $department): void
    {
        $this->department = $department;
    }

    /**
     * @return CriterionGroup
     */
    public function getCriterionGroup(): CriterionGroup
    {
        return $this->criterionGroup;
    }

    /**
     * @param CriterionGroup $criterionGroup
     */
    public function setCriterionGroup(CriterionGroup $criterionGroup): void
    {
        $this->criterionGroup = $criterionGroup;
    }

}
