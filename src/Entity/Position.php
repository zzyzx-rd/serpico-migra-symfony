<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PositionRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PositionRepository::class)
 */
class Position extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="pos_id", type="integer", length=10, nullable=false)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="pos_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="pos_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="pos_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="pos_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="positions")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Department", inversedBy="positions")
     * @JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id", nullable=true)
     */
    protected $department;

    /**
     * @ORM\ManyToOne(targetEntity=Weight::class, inversedBy="positions")
     * @ORM\JoinColumn(name="weight_wgt_id",referencedColumnName="wgt_id", nullable=true)
     */
    public $weight;

    /**
     * @OneToMany(targetEntity="OrganizationUserOption", mappedBy="position", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $options;

    /**
     * @OneToMany(targetEntity="Target", mappedBy="position",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $targets;

    /**
     * @OneToMany(targetEntity="User", mappedBy="position",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $users;

    /**
     * Position constructor.
     * @param ?int$id
     * @param $name
     * @param $createdBy
     * @param $deleted
     * @param $organization
     * @param $department
     * @param $weight
     * @param $options
     * @param $targets
     */
    public function __construct(
      ?int $id = 0,
        $name = '',
        $organization = null,
        $department = null,
        $createdBy = null,
        $deleted = null)
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->name = $name;
        $this->deleted = $deleted;
        $this->organization = $organization;
        $this->department = $department;
        $this->options = new ArrayCollection();
        $this->targets = new ArrayCollection();
        $this->users = new ArrayCollection();
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getWeight(): ?Weight
    {
        return $this->weight;
    }

    public function setWeight(Weight $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function setInserted(DateTimeInterface $inserted): self
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
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param mixed $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
        return $this;
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
    public function setDepartment($department)
    {
        $this->department = $department;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeights()
    {
        return $this->weights;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return mixed
     */
    public function getTargets()
    {
        return $this->targets;
    }

    /**
     * @return ArrayCollection|User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function addUser(User $user): Position
    {
        $this->users->add($user);
        $user->setPosition($this);
        return $this;
    }
    public function removeUser(User $user): Position
    {
        $this->users->removeElement($user);
        return $this;
    }

    public function addWeight(Weight $weight): Position
    {

        $this->weights->add($weight);
        $weight->setPosition($this);
        return $this;
    }

    public function removeWeight(Weight $weight): Position
    {
        $this->weights->removeElement($weight);
        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }
    public function addTarget(Target $target): Position
    {
        $this->targets->add($target);
        $target->setPosition($this);
        return $this;
    }

    public function removeTarget(Target $target): Position
    {
        $this->targets->removeElement($target);
        return $this;
    }
    public function addOption(OrganizationUserOption $option): Position
    {
        $this->options->add($option);
        $option->setPosition($this);
        return $this;
    }

    public function removeOption(OrganizationUserOption $option): Position
    {
        $this->options->removeElement($option);
        return $this;
    }


}
