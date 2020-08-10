<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CriterionGroupRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CriterionGroupRepository::class)
 */
class CriterionGroup
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="cgp_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cgp_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cgp_inserted;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cgp_name;

    /**
     * @OneToMany(targetEntity="CriterionName", mappedBy="criterionGroup", cascade={"persist", "remove"})
     * @var CriterionName[]
     */
    protected $criteria;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="criterionGroups")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id")
     * @var Organization
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Department", inversedBy="criterionGroups")
     * @JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id")
     * @var Department
     */
    protected $department;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCgpCreatedBy(): ?int
    {
        return $this->cgp_createdBy;
    }

    public function setCgpCreatedBy(int $cgp_createdBy): self
    {
        $this->cgp_createdBy = $cgp_createdBy;

        return $this;
    }

    public function getCgpInserted(): ?\DateTimeInterface
    {
        return $this->cgp_inserted;
    }

    public function setCgpInserted(\DateTimeInterface $cgp_inserted): self
    {
        $this->cgp_inserted = $cgp_inserted;

        return $this;
    }

    public function getCgpName(): ?string
    {
        return $this->cgp_name;
    }

    public function setCgpName(string $cgp_name): self
    {
        $this->cgp_name = $cgp_name;

        return $this;
    }

    /**
     * @return CriterionName[]
     */
    public function getCriteria(): array
    {
        return $this->criteria;
    }

    /**
     * @param CriterionName[] $criteria
     */
    public function setCriteria(array $criteria): void
    {
        $this->criteria = $criteria;
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
     * @return Department
     */
    public function getDepartment(): Department
    {
        return $this->department;
    }

    /**
     * @param Department $department
     */
    public function setDepartment(Department $department): void
    {
        $this->department = $department;
    }

}
