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
    public $id;

    /**
     * @ORM\Column(name="tit_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="tit_weight_ini", type="float", nullable=true)
     */
    public $weightIni;

    /**
     * @ORM\Column(name="tit_created_by", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="tit_inserted", type="datetime", nullable=true)
     */
    public $inserted;

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
     * @param int $id
     * @param $tit_name
     * @param $tit_weight_ini
     * @param $tit_createdBy
     * @param $tit_inserted
     * @param $tit_deleted
     * @param $organization
     * @param $weight
     * @param $options
     * @param $targets
     */
    public function __construct(
        int $id = 0,
        $tit_name = '',
        $tit_weight_ini = 0.0,
        $tit_createdBy = null,
        $tit_inserted = null,
        $tit_deleted = null,
        $organization = null,
        $weight = null,
        $options = null,
        $targets = null)
    {
        parent::__construct($id, $tit_createdBy, new DateTime());
        $this->name = $tit_name;
        $this->weightIni = $tit_weight_ini;
        $this->inserted = $tit_inserted;
        $this->deleted = $tit_deleted;
        $this->organization = $organization;
        $this->weight = $weight;
        $this->options = $options?:new ArrayCollection();
        $this->targets = $targets?:new ArrayCollection();
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $tit_name): self
    {
        $this->name = $tit_name;

        return $this;
    }

    public function getWeightIni(): ?float
    {
        return $this->weightIni;
    }

    public function setWeightIni(float $tit_weight_ini): self
    {
        $this->weightIni = $tit_weight_ini;

        return $this;
    }

    public function setInserted(DateTimeInterface $tit_inserted): self
    {
        $this->inserted = $tit_inserted;

        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeInterface $tit_deleted): self
    {
        $this->deleted = $tit_deleted;

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
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight): void
    {
        $this->weight = $weight;
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
