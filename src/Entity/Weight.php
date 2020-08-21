<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WeightRepository;
use DateTime;
use DateTimeInterface;
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
    public ?int $id;

    /**
     * @ORM\Column(name="wgt_interval", type="integer", nullable=true)
     */
    public $interval;

    /**
     * @ORM\Column(name="wgt_titleframe", type="string", length=255, nullable=true)
     */
    public $titleframe;

    /**
     * @ORM\Column(name="wgt_value", type="float", nullable=true)
     */
    public $value;

    /**
     * @ORM\Column(name="wgt_modified", type="datetime", nullable=true)
     */
    public $modified;

    /**
     * @ORM\Column(name="wgt_createdBy", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="wgt_inserted", type="datetime", nullable=true)
     */
    public ?DateTime $inserted;

    /**
     * @ORM\Column(name="wgt_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="weights")
     * @JoinColumn(name="org_id", referencedColumnName="org_id",nullable=false)
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Position", inversedBy="weights")
     * @JoinColumn(name="pos_id", referencedColumnName="pos_id",nullable=false)
     */
    protected $position;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="weight_wgt", cascade={"persist", "remove"})
     * @JoinColumn(name="user_usr_id",referencedColumnName="usr_id", nullable=true)
     */
    public $user;

    /**
     * Weight constructor.
     * @param ?int$id
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
      ?int $id = 0,
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
        $this->interval = $wgt_interval;
        $this->titleframe = $wgt_titleframe;
        $this->value = $wgt_value;
        $this->modified = $wgt_modified;
        $this->inserted = $wgt_inserted;
        $this->deleted = $wgt_deleted;
        $this->organization = $organization;
        $this->position = $position;
        $this->user = $user;
    }

    public function getInterval(): ?int
    {
        return $this->interval;
    }

    public function setInterval(int $wgt_interval): self
    {
        $this->interval = $wgt_interval;

        return $this;
    }

    public function getTitleframe(): ?string
    {
        return $this->titleframe;
    }

    public function setTitleframe(string $wgt_titleframe): self
    {
        $this->titleframe = $wgt_titleframe;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $wgt_value): self
    {
        $this->value = $wgt_value;

        return $this;
    }

    public function getModified(): ?DateTimeInterface
    {
        return $this->modified;
    }

    public function setModified(?DateTimeInterface $wgt_modified): self
    {
        $this->modified = $wgt_modified;

        return $this;
    }

    public function getInserted(): ?DateTimeInterface
    {
        return $this->inserted;
    }

    public function setInserted(DateTimeInterface $wgt_inserted): self
    {
        $this->inserted = $wgt_inserted;

        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeInterface $wgt_deleted): self
    {
        $this->deleted = $wgt_deleted;

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
