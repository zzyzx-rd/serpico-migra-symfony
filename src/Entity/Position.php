<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PositionRepository;
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
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pos_name;

    /**
     * @ORM\Column(type="float")
     */
    private $pos_weight_ini;

    /**
     * @ORM\Column(type="float")
     */
    private $pos_weight_1y;

    /**
     * @ORM\Column(type="float")
     */
    private $pos_weight_2y;

    /**
     * @ORM\Column(type="float")
     */
    private $pos_weight_3y;

    /**
     * @ORM\Column(type="float")
     */
    private $pos_weight_4y;

    /**
     * @ORM\Column(type="float")
     */
    private $pos_weight_5y;

    /**
     * @ORM\Column(type="integer")
     */
    private $pos_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $pos_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $pos_deleted;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Department")
     * @JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id", nullable=true)
     */
    protected $department;

    /**
     * @OneToMany(targetEntity="Weight", mappedBy="position", cascade={"persist", "remove"})
     */
    private $weights;

    /**
     * @OneToMany(targetEntity="OrganizationUserOption", mappedBy="position", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $options;

    /**
     * @OneToMany(targetEntity="Target", mappedBy="position",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $targets;

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
        $this->pos_name = $pos_name;
        $this->pos_weight_ini = $pos_weight_ini;
        $this->pos_weight_1y = $pos_weight_1y;
        $this->pos_weight_2y = $pos_weight_2y;
        $this->pos_weight_3y = $pos_weight_3y;
        $this->pos_weight_4y = $pos_weight_4y;
        $this->pos_weight_5y = $pos_weight_5y;
        $this->pos_inserted = $pos_inserted;
        $this->pos_deleted = $pos_deleted;
        $this->organization = $organization;
        $this->department = $department;
        $this->weights = $weights?$weights:new ArrayCollection();
        $this->options = $options?$options:new ArrayCollection();
        $this->targets = $targets?$targets:new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosName(): ?string
    {
        return $this->pos_name;
    }

    public function setPosName(string $pos_name): self
    {
        $this->pos_name = $pos_name;

        return $this;
    }

    public function getPosWeightIni(): ?float
    {
        return $this->pos_weight_ini;
    }

    public function setPosWeightIni(float $pos_weight_ini): self
    {
        $this->pos_weight_ini = $pos_weight_ini;

        return $this;
    }

    public function getPosWeight1y(): ?float
    {
        return $this->pos_weight_1y;
    }

    public function setPosWeight1y(float $pos_weight_1y): self
    {
        $this->pos_weight_1y = $pos_weight_1y;

        return $this;
    }

    public function getPosWeight2y(): ?float
    {
        return $this->pos_weight_2y;
    }

    public function setPosWeight2y(float $pos_weight_2y): self
    {
        $this->pos_weight_2y = $pos_weight_2y;

        return $this;
    }

    public function getPosWeight3y(): ?float
    {
        return $this->pos_weight_3y;
    }

    public function setPosWeight3y(float $pos_weight_3y): self
    {
        $this->pos_weight_3y = $pos_weight_3y;

        return $this;
    }

    public function getPosWeight4y(): ?float
    {
        return $this->pos_weight_4y;
    }

    public function setPosWeight4y(float $pos_weight_4y): self
    {
        $this->pos_weight_4y = $pos_weight_4y;

        return $this;
    }

    public function getPosWeight5y(): ?float
    {
        return $this->pos_weight_5y;
    }

    public function setPosWeight5y(float $pos_weight_5y): self
    {
        $this->pos_weight_5y = $pos_weight_5y;

        return $this;
    }

    public function getPosCreatedBy(): ?int
    {
        return $this->pos_createdBy;
    }

    public function setPosCreatedBy(int $pos_createdBy): self
    {
        $this->pos_createdBy = $pos_createdBy;

        return $this;
    }

    public function getPosInserted(): ?\DateTimeInterface
    {
        return $this->pos_inserted;
    }

    public function setPosInserted(\DateTimeInterface $pos_inserted): self
    {
        $this->pos_inserted = $pos_inserted;

        return $this;
    }

    public function getPosDeleted(): ?\DateTimeInterface
    {
        return $this->pos_deleted;
    }

    public function setPosDeleted(?\DateTimeInterface $pos_deleted): self
    {
        $this->pos_deleted = $pos_deleted;

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

    function addUser(User $user){

        $this->users->add($user);
        // $user->setPosition($this);
        return $this;
    }
    function removeUser(User $user){
        $this->users->removeElement($user);
        return $this;
    }

    function addWeight(Weight $weight){

        $this->weights->add($weight);
        $weight->setPosition($this);
        return $this;
    }

    function removeWeight(Weight $weight){
        $this->weights->removeElement($weight);
        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }
    function addTarget(Target $target)
    {
        $this->targets->add($target);
        $target->setPosition($this);
        return $this;
    }

    function removeTarget(Target $target)
    {
        $this->targets->removeElement($target);
        return $this;
    }
    function addOption(OrganizationUserOption $option)
    {
        $this->options->add($option);
        $option->setPosition($this);
        return $this;
    }

    function removeOption(OrganizationUserOption $option)
    {
        $this->options->removeElement($option);
        return $this;
    }


}
