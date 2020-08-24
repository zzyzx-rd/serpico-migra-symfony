<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CriterionGroupRepository;
use DateTime;
use DateTimeInterface;
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
    public ?int $id;

    /**
     * @ORM\Column(name="cgp_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="cgp_inserted", type="datetime", nullable=true)
     */
    public ?DateTime $inserted;

    /**
     * @ORM\Column(name="cgp_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @OneToMany(targetEntity="CriterionName", mappedBy="criterionGroup", cascade={"persist", "remove"})
     * @var CriterionName[]
     */
    protected $criteria;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="criterionGroups")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=true)
     * @var Organization
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Department", inversedBy="criterionGroups")
     * @JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id", nullable=true)
     * @var Department
     */
    protected $department;

    /**
     * CriterionGroup constructor.
     * @param string $cgp_name
     * @param Organization $organization
     * @param Department $department
     * @param $cgp_createdBy
     * @param $id
     * @param CriterionName[] $criteria
     */
    public function __construct(
        string $cgp_name = null,
        Organization $organization = null,
        Department $department = null,
        $cgp_createdBy = null,
        $id = null,
        array $criteria = [])
    {
        parent::__construct($id, $cgp_createdBy, new DateTime());
        $this->name = $cgp_name;
        $this->criteria = $criteria;
        $this->organization = $organization;
        $this->department = $department;
    }


    public function setInserted(DateTimeInterface $cgp_inserted): self
    {
        $this->inserted = $cgp_inserted;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $cgp_name): self
    {
        $this->name = $cgp_name;

        return $this;
    }

    public function getCriteria()
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
    public function getDepartment(): ?Department
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

    public function addCriterion(CriterionName $criterion): ?CriterionGroup
    {
        if (!$criterion->getCriterionGroup()) {
            $this->criteria[] = $criterion;
            $criterion->setCriterionGroup($this);
        }
        return $this;
    }

    public function removeCriterion(CriterionName $criterion): ?CriterionGroup
    {
        $this->criteria->removeElement($criterion);
        return $this;
    }
    public function hasNoCriterion() {
        return $this->criteria->isEmpty();
    }

    public function toArray()
    {
        $criteria = $this->criteria->map(static function(CriterionName $e) {
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
            'name' => $this->name,
            'criteria' => $criteria,
            'organization' => $this->organization->toArray(),
            'department' => $this->department->toArray()
        ];
    }

//    public function __toString()
//    {
//        return $this->name;
//    }
    
}
