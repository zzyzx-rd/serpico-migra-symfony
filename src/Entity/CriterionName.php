<?php

namespace App\Entity;

use App\Repository\CriterionNameRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity(repositoryClass=CriterionNameRepository::class)
 */
class CriterionName extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="cna_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="cna_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="cna_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="can_unit", type="string", length=255, nullable=true)
     */
    public $unit;

    /**
     * @ORM\Column(name="can_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="can_inserted", type="datetime", nullable=true)
     */
    public ?DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="Icon", inversedBy="criterionNames")
     * @JoinColumn(name="icon_ico_id", referencedColumnName="ico_id",nullable=true)
     * @var Icon
     */
    protected $icon;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="criterionNames")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=true)
     * @var Organization
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Department")
     * @JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id", nullable=true)
     * @var Department|null
     */
    protected $department;

    /**
     * @ManyToOne(targetEntity="CriterionGroup", inversedBy="criteria")
     * @JoinColumn(name="criterion_group_cgp_id", referencedColumnName="cgp_id", nullable=true)
     * @var CriterionGroup
     */
    protected $criterionGroup;

    /**
     * CriterionName constructor.
     * @param $id
     * @param $cna_type
     * @param $cna_name
     * @param $can_unit
     * @param $can_createdBy
     * @param $can_inserted
     * @param Icon $icon
     * @param Organization $organization
     * @param Department|null $department
     * @param CriterionGroup $criterionGroup
     */
    public function __construct(
        $id = null,
        $cna_type = null,
        $cna_name = '',
        $can_unit = '',
        $can_createdBy = null,
        $can_inserted = null,
        Icon $icon = null,
        Organization $organization = null,
        ?Department $department = null,
        CriterionGroup $criterionGroup = null)
    {
        parent::__construct($id, $can_createdBy, new DateTime());
        $this->type = $cna_type;
        $this->name = $cna_name;
        $this->unit = $can_unit;
        $this->inserted = $can_inserted;
        $this->icon = $icon;
        $this->organization = $organization;
        $this->department = $department;
        $this->criterionGroup = $criterionGroup;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $cna_type): self
    {
        $this->type = $cna_type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $cna_name): self
    {
        $this->name = $cna_name;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

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
    public function __toString()
    {
        return (string) $this->id;
    }
}
