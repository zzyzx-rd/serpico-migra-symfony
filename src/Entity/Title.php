<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TitleRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TitleRepository::class)
 */
class Title extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="tit_id", type="integer", length=10, nullable=false)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="tit_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="tit_weight_ini", type="float", nullable=true)
     */
    public $weightIni;

   /**
     * @ManyToOne(targetEntity="User", inversedBy="titleInitiatives")
     * @JoinColumn(name="tit_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\Column(name="tit_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="tit_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="titles")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=true)
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Weight")
     * @JoinColumn(name="weight_wgt_id", referencedColumnName="wgt_id", nullable=true)
     */
    public $weight;

    /**
     * @OneToMany(targetEntity="OrganizationUserOption", mappedBy="title", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $options;

    /**
     * @OneToMany(targetEntity="Target", mappedBy="title",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $targets;
    private $users;

    /**
     * Title constructor.
     * @param ?int$id
     * @param $name
     * @param $weightIni
     * @param $deleted
     * @param $organization
     * @param $weight
     * @param $options
     * @param $targets
     */
    public function __construct(
      ?int $id = 0,
        $name = '',
        $weightIni = 0.0,
        $deleted = null,
        $organization = null,
        $weight = null,
        $options = null,
        $targets = null)
    {
        parent::__construct($id, null, new DateTime());
        $this->name = $name;
        $this->weightIni = $weightIni;
        $this->deleted = $deleted;
        $this->organization = $organization;
        $this->weight = $weight;
        $this->options = $options?:new ArrayCollection();
        $this->targets = $targets?:new ArrayCollection();
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
        return $this->weightIni;
    }

    public function setWeightIni(float $weightIni): self
    {
        $this->weightIni = $weightIni;
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
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
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
     * @return mixed
     */
    public function getTargets()
    {
        return $this->targets;
    }

    public function addUser(User $user): Title
    {

        $this->users->add($user);
        // $user->setPosition($this);
        return $this;
    }

    public function removeUser(User $user): Title
    {
        $this->users->removeElement($user);
        return $this;
    }

    public function addTarget(Target $target): Title
    {
        $this->targets->add($target);
        $target->setPosition($this);
        return $this;
    }

    public function removeTarget(Target $target): Title
    {
        $this->targets->removeElement($target);
        return $this;
    }

    public function addOption(OrganizationUserOption $option): Title
    {
        $this->options->add($option);
        $option->setTitle($this);
        return $this;
    }

    public function removeOption(OrganizationUserOption $option): Title
    {
        $this->options->removeElement($option);
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }
}
