<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WeightRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WeightRepository::class)
 */
class Weight extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="wgt_id", type="integer", length=10, nullable=false)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $wgt_interval;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wgt_titleframe;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $wgt_value;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $wgt_modified;

    /**
     * @ORM\Column(type="integer")
     */
    private $wgt_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $wgt_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $wgt_deleted;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="org_id", referencedColumnName="org_id",nullable=false)
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Position")
     * @JoinColumn(name="pos_id", referencedColumnName="pos_id",nullable=false)
     */
    protected $position;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="weight_wgt", cascade={"persist", "remove"})
     * @JoinColumn(nullable=true)
     */
    private $user;

    /**
     * Weight constructor.
     * @param int $id
     * @param $wgt_interval
     * @param $wgt_titleframe
     * @param $wgt_value
     * @param $wgt_modified
     * @param $wgt_createdBy
     * @param $wgt_inserted
     * @param $wgt_deleted
     * @param $organization
     * @param $position
     * @param $user
     */
    public function __construct(
        int $id = 0,
        $user = null,
        $wgt_interval = 0,
        $wgt_titleframe = '',
        $wgt_value = 100,
        DateTime $wgt_modified = null,
        $wgt_createdBy = null,
        DateTime $wgt_inserted = null,
        DateTime $wgt_deleted = null,
        $organization = null,
        $position = null)
    {
        parent::__construct($id, $wgt_createdBy, new DateTime());
        $this->wgt_interval = $wgt_interval;
        $this->wgt_titleframe = $wgt_titleframe;
        $this->wgt_value = $wgt_value;
        $this->wgt_modified = $wgt_modified;
        $this->wgt_inserted = $wgt_inserted;
        $this->wgt_deleted = $wgt_deleted;
        $this->organization = $organization;
        $this->position = $position;
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInterval(): ?int
    {
        return $this->wgt_interval;
    }

    public function setInterval(int $wgt_interval): self
    {
        $this->wgt_interval = $wgt_interval;

        return $this;
    }

    public function getTitleframe(): ?string
    {
        return $this->wgt_titleframe;
    }

    public function setTitleframe(string $wgt_titleframe): self
    {
        $this->wgt_titleframe = $wgt_titleframe;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->wgt_value;
    }

    public function setValue(?float $wgt_value): self
    {
        $this->wgt_value = $wgt_value;

        return $this;
    }

    public function getModified(): ?\DateTimeInterface
    {
        return $this->wgt_modified;
    }

    public function setModified(?\DateTimeInterface $wgt_modified): self
    {
        $this->wgt_modified = $wgt_modified;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->wgt_createdBy;
    }

    public function setCreatedBy(int $wgt_createdBy): self
    {
        $this->wgt_createdBy = $wgt_createdBy;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->wgt_inserted;
    }

    public function setInserted(\DateTimeInterface $wgt_inserted): self
    {
        $this->wgt_inserted = $wgt_inserted;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->wgt_deleted;
    }

    public function setDeleted(?\DateTimeInterface $wgt_deleted): self
    {
        $this->wgt_deleted = $wgt_deleted;

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
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position): void
    {
        $this->position = $position;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        // set the owning side of the relation if necessary
        if ($user->getWeightWgt() !== $this) {
            $user->setWeightWgt($this);
        }

        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }

}
