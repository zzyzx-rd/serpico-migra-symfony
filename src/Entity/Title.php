<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TitleRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TitleRepository::class)
 */
class Title
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitName(): ?string
    {
        return $this->tit_name;
    }

    public function setTitName(string $tit_name): self
    {
        $this->tit_name = $tit_name;

        return $this;
    }

    public function getTitWeightIni(): ?float
    {
        return $this->tit_weight_ini;
    }

    public function setTitWeightIni(float $tit_weight_ini): self
    {
        $this->tit_weight_ini = $tit_weight_ini;

        return $this;
    }

    public function getTitCreatedBy(): ?int
    {
        return $this->tit_createdBy;
    }

    public function setTitCreatedBy(int $tit_createdBy): self
    {
        $this->tit_createdBy = $tit_createdBy;

        return $this;
    }

    public function getTitInserted(): ?\DateTimeInterface
    {
        return $this->tit_inserted;
    }

    public function setTitInserted(\DateTimeInterface $tit_inserted): self
    {
        $this->tit_inserted = $tit_inserted;

        return $this;
    }

    public function getTitDeleted(): ?\DateTimeInterface
    {
        return $this->tit_deleted;
    }

    public function setTitDeleted(?\DateTimeInterface $tit_deleted): self
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



}
