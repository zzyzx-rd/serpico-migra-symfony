<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CriterionGroupRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CriterionGroupRepository::class)
 */
class CriterionGroup extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="cgp_id", type="integer", nullable=false)
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $cgp_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $cgp_inserted;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $cgp_name;

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

    /**
     * CriterionGroup constructor.
     * @param $id
     * @param $cgp_createdBy
     * @param $cgp_inserted
     * @param $cgp_name
     * @param CriterionName[] $criteria
     * @param Organization $organization
     * @param Department $department
     */
    public function __construct(
        string $cgp_name = null,
        Organization $organization = null,
        Department $department = null,
        $cgp_createdBy = null,
        $id = null,
        array $criteria = [])
    {
        $this->id = $id;
        $this->cgp_inserted = new DateTime();
        $this->cgp_name = $cgp_name;
        $this->criteria = $criteria;
        $this->organization = $organization;
        $this->department = $department;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->cgp_inserted;
    }

    public function setInserted(\DateTimeInterface $cgp_inserted): self
    {
        $this->cgp_inserted = $cgp_inserted;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->cgp_name;
    }

    public function setName(string $cgp_name): self
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

    function addCriterion(CriterionName $criterion) {
        if (!$criterion->getCriterionGroup()) {
            $this->criteria[] = $criterion;
            $criterion->setCriterionGroup($this);
        }
        return $this;
    }

    function removeCriterion(CriterionName $criterion) {
        $this->criteria->removeElement($criterion);
        return $this;
    }
    public function hasNoCriterion() {
        return $this->criteria->isEmpty();
    }

    public function toArray() {
        $criteria = $this->criteria->map(function(CriterionName $e) {
            return [
                'id' => $e->getId(),
                'type' => $e->getType(),
                'name' => $e->getName()
            ];
        })->toArray();

        return [
            'id' => $this->id,
            'createdBy' => $this->createdBy,
            'inserted' => $this->inserted,
            'name' => $this->cgp_name,
            'criteria' => $criteria,
            'organization' => $this->organization->toArray(),
            'department' => $this->department->toArray()
        ];
    }

    function __toString()
    {
        return $this->cgp_name;
    }
    
}
