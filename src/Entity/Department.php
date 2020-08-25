<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DepartmentRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;
use phpDocumentor\Reflection\Types\This;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\DepartmentRepository", repositoryClass=DepartmentRepository::class)
 */
class Department extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="dpt_id", type="integer", length=10, nullable=false)
     */
    protected ?int $id;


    /**
     * @ORM\Column(name="dpt_name", type="string", length=45)
     */
    public $name;

    /**
     * @ORM\Column(name="dpt_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="dpt_inserted", type="datetime")
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="dpt_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="leadingDepartments")
     * @JoinColumn(name="masterUser_usr_id", referencedColumnName="usr_id", nullable=false)
     */
    protected $masterUser;

    /**
     * @OneToMany(targetEntity="Position", mappedBy="department", cascade={"persist", "remove"})
     */
//     * @OrderBy({"name" = "ASC"})
    public $positions;

    /**
     * @OneToMany(targetEntity="OrganizationUserOption", mappedBy="department", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $options;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="departments")
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
    public $targets;

    /**
     * @OneToMany(targetEntity="User", mappedBy="department",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $users;

    /**
     * Department constructor.
     * @param $id
     * @param $name
     * @param $createdBy
     * @param $inserted
     * @param $deleted
     * @param $masterUser
     * @param $positions
     * @param $templateActivities
     * @param $options
     * @param $organization
     * @param ArrayCollection $criterionGroups
     * @param $targets
     */
    //TODO Set correctement dans les controlleurs
    public function __construct(
        $id = 0,
        $name = '',
        $createdBy = null,
        $inserted = null,
        $deleted = null,
        $masterUser = null,
        $positions = null,
        $templateActivities = null,
        ArrayCollection $options = null,
        $organization = null,
        ArrayCollection $criterionGroups = null,
        ArrayCollection $targets = null)
    {
        parent::__construct($id, $createdBy, new DateTime);
        $this->name = $name;
        $this->deleted = $deleted;
        $this->masterUser = $masterUser;
        $this->positions = $positions?:new ArrayCollection();
        $this->options = $options?:new ArrayCollection();
        $this->organization = $organization;
        $this->criterionGroups = $criterionGroups;
        $this->targets = $targets?:new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setInserted(DateTime $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeInterface $deleted): self
    {
        $this->deleted = $deleted;

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
    public function setMasterUser($masterUser): Department
    {
        $this->masterUser = $masterUser;
        return $this;
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
    public function setPositions($positions)
    {
        $this->positions = $positions;
        return $this;
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
    public function setTemplateActivities($templateActivities)
    {
        $this->templateActivities = $templateActivities;
        return $this;
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
    public function setOptions($options)
    {
        $this->options = $options;
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
    public function setOrganization($organization): Department
    {
        $this->organization = $organization;
        return $this;
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
    public function setCriterionGroups(ArrayCollection $criterionGroups)
    {
        $this->criterionGroups = $criterionGroups;
        return $this;
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
    public function setTargets($targets)
    {
        $this->targets = $targets;
        return $this;
    }

    public function addPosition(Position $position): Department
    {

        $this->positions->add($position);
        $position->setDepartment($this);
        return $this;
    }

    public function removePosition(Position $position): Department
    {
        $this->positions->removeElement($position);
        return $this;
    }

    //TODO l'histoire du getUSers et viewable users

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
    public function addOption(OrganizationUserOption $option): Department
    {
        $this->options->add($option);
        $option->setDepartment($this);
        return $this;
    }

    public function removeOption(OrganizationUserOption $option): Department
    {
        $this->options->removeElement($option);
        return $this;
    }

    /**
     * @return ArrayCollection|User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function addUser(User $user): Department
    {
        $this->users->add($user);
        $user->setDepartment($this);
        return $this;
    }
    public function removeUser(User $user): Department
    {
        $this->users->removeElement($user);
        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
