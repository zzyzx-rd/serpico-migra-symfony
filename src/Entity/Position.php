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
    public $id;

    /**
     * @ORM\Column(name="pos_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="pos_weight_ini", type="float", nullable=true)
     */
    public $weight_ini;

    /**
     * @ORM\Column(name="pos_weight_1y", type="float", nullable=true)
     */
    public $weight_1y;

    /**
     * @ORM\Column(name="pos_weight_2y", type="float", nullable=true)
     */
    public $weight_2y;

    /**
     * @ORM\Column(name="pos_weight_3y", type="float", nullable=true)
     */
    public $weight_3y;

    /**
     * @ORM\Column(name="pos_weight_4y", type="float", nullable=true)
     */
    public $weight_4y;

    /**
     * @ORM\Column(name="pos_weight_5y", type="float", nullable=true)
     */
    public $weight_5y;

    /**
     * @ORM\Column(name="pos_created_by", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="pos_inserted", type="datetime", nullable=true)
     */
    public $inserted;

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
     * @OneToMany(targetEntity="Weight", mappedBy="position", cascade={"persist", "remove"})
     */
    public $weights;

    /**
     * @OneToMany(targetEntity="OrganizationUserOption", mappedBy="position", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $options;

    /**
     * @OneToMany(targetEntity="Target", mappedBy="position",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $targets;
    private $users;

    /**
     * Position constructor.
     * @param int $id
     * @param $pos_name
     * @param $pos_weight_ini
     * @param $pos_weight_1y
     * @param $pos_weight_2y
     * @param $pos_weight_3y
     * @param $pos_weight_4y
     * @param $pos_weight_5y
     * @param $pos_createdBy
     * @param $pos_inserted
     * @param $pos_deleted
     * @param $organization
     * @param $department
     * @param $weights
     * @param $options
     * @param $targets
     */
    public function __construct(
        int $id = 0,
        $pos_name = '',
        $pos_weight_ini = 0.0,
        $pos_weight_1y = 0.0,
        $pos_weight_2y = 0.0,
        $pos_weight_3y = 0.0,
        $pos_weight_4y = 0.0,
        $pos_weight_5y = 0.0,
        $organization = 0,
        $department = null,
        $pos_createdBy = null,
        $pos_inserted = null,
        $pos_deleted = null,
        $weights = null,
        $options = null,
        $targets = null)
    {
        parent::__construct($id, $pos_createdBy, new DateTime());
        $this->name = $pos_name;
        $this->weight_ini = $pos_weight_ini;
        $this->weight_1y = $pos_weight_1y;
        $this->weight_2y = $pos_weight_2y;
        $this->weight_3y = $pos_weight_3y;
        $this->weight_4y = $pos_weight_4y;
        $this->weight_5y = $pos_weight_5y;
        $this->inserted = $pos_inserted;
        $this->deleted = $pos_deleted;
        $this->organization = $organization;
        $this->department = $department;
        $this->weights = $weights?:new ArrayCollection();
        $this->options = $options?:new ArrayCollection();
        $this->targets = $targets?:new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getWeightIni(): ?float
    {
        return $this->weight_ini;
    }

    public function setWeightIni(float $weight_ini): self
    {
        $this->weight_ini = $weight_ini;

        return $this;
    }

    public function getWeight1Y(): ?float
    {
        return $this->weight_1y;
    }

    public function setWeight1Y(float $weight_1y): self
    {
        $this->weight_1y = $weight_1y;

        return $this;
    }

    public function getWeight2Y(): ?float
    {
        return $this->weight_2y;
    }

    public function setWeight2Y(float $weight_2y): self
    {
        $this->weight_2y = $weight_2y;

        return $this;
    }

    public function getWeight3Y(): ?float
    {
        return $this->weight_3y;
    }

    public function setWeight3Y(float $weight_3y): self
    {
        $this->weight_3y = $weight_3y;

        return $this;
    }

    public function getWeight4Y(): ?float
    {
        return $this->weight_4y;
    }

    public function setWeight4Y(float $weight_4y): self
    {
        $this->weight_4y = $weight_4y;

        return $this;
    }

    public function getWeight5Y(): ?float
    {
        return $this->weight_5y;
    }

    public function setWeight5Y(float $weight_5y): self
    {
        $this->weight_5y = $weight_5y;

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
    public function getWeights()
    {
        return $this->weights;
    }

    /**
     * @param mixed $weights
     */
    public function setWeights($weights): void
    {
        $this->weights = $weights;
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
    public function setOptions($options): void
    {
        $this->options = $options;
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
    public function setTargets($targets): void
    {
        $this->targets = $targets;
    }

    public function addUser(User $user): Position
    {

        $this->users->add($user);
        // $user->setPosition($this);
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
