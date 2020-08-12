<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TitleRepository;
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
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tit_name;

    /**
     * @ORM\Column(type="float")
     */
    private $tit_weight_ini;

    /**
     * @ORM\Column(type="integer")
     */
    private $tit_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tit_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tit_deleted;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Weight")
     * @JoinColumn(name="weight_wgt_id", referencedColumnName="wgt_id", nullable=true)
     */
    private $weight;

    /**
     * @OneToMany(targetEntity="OrganizationUserOption", mappedBy="title", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $options;

    /**
     * @OneToMany(targetEntity="Target", mappedBy="title",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $targets;

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
        $this->tit_name = $tit_name;
        $this->tit_weight_ini = $tit_weight_ini;
        $this->tit_inserted = $tit_inserted;
        $this->tit_deleted = $tit_deleted;
        $this->organization = $organization;
        $this->weight = $weight;
        $this->options = $options?$options:new ArrayCollection();
        $this->targets = $targets?$targets:new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->tit_name;
    }

    public function setName(string $tit_name): self
    {
        $this->tit_name = $tit_name;

        return $this;
    }

    public function getWeightIni(): ?float
    {
        return $this->tit_weight_ini;
    }

    public function setWeightIni(float $tit_weight_ini): self
    {
        $this->tit_weight_ini = $tit_weight_ini;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->tit_createdBy;
    }

    public function setCreatedBy(int $tit_createdBy): self
    {
        $this->tit_createdBy = $tit_createdBy;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->tit_inserted;
    }

    public function setInserted(\DateTimeInterface $tit_inserted): self
    {
        $this->tit_inserted = $tit_inserted;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->tit_deleted;
    }

    public function setDeleted(?\DateTimeInterface $tit_deleted): self
    {
        $this->tit_deleted = $tit_deleted;

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

    function addUser(User $user){

        $this->users->add($user);
        // $user->setPosition($this);
        return $this;
    }

    function removeUser(User $user){
        $this->users->removeElement($user);
        return $this;
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
        $option->setTitle($this);
        return $this;
    }

    function removeOption(OrganizationUserOption $option)
    {
        $this->options->removeElement($option);
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }
}
