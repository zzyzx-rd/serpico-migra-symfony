<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WeightRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ManyToOne(targetEntity="User", inversedBy="weightInitiatives")
     * @JoinColumn(name="wgt_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\Column(name="wgt_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="wgt_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @ORM\ManyToOne(targetEntity="Organization", inversedBy="weights")
     * @JoinColumn(name="org_id", referencedColumnName="org_id", nullable=false, onDelete="CASCADE")
     */
    protected $organization;

    /**
     * @ORM\OneToMany(targetEntity=Position::class, mappedBy="weight", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $positions;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="weight", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $users;

    /**
     * Weight constructor.
     * @param int|null $id
     * @param int $interval
     * @param string $titleframe
     * @param int $value
     * @param DateTime $modified
     * @param DateTime $deleted
     * @param $organization
     */
    public function __construct(
        ?int $id = 0,
        $interval = 0,
        $titleframe = '',
        $value = 100,
        DateTime $modified = null,
        DateTime $deleted = null,
        $organization = null
    )
    {
        parent::__construct($id, null, new DateTime());
        $this->interval = $interval;
        $this->titleframe = $titleframe;
        $this->value = $value;
        $this->modified = $modified;
        $this->deleted = $deleted;
        $this->organization = $organization;
        $this->positions = new ArrayCollection;
        $this->users = new ArrayCollection;
    }

    public function getInterval(): ?int
    {
        return $this->interval;
    }

    public function setInterval(int $interval): self
    {
        $this->interval = $interval;

        return $this;
    }

    public function getTitleframe(): ?string
    {
        return $this->titleframe;
    }

    public function setTitleframe(string $titleframe): self
    {
        $this->titleframe = $titleframe;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getModified(): ?DateTimeInterface
    {
        return $this->modified;
    }

    public function setModified(?DateTimeInterface $modified): self
    {
        $this->modified = $modified;

        return $this;
    }

    public function getInserted(): ?DateTimeInterface
    {
        return $this->inserted;
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
    public function setOrganization(Organization $organization): self
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return ArrayCollection|User[]
     */
    public function getUsers(){
        return $this->users;
    }

    public function addUser(User $user): self
    {
        $this->users->add($user);
        $user->setWeight($this);
        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);
        return $this;
    }

    /**
     * @return ArrayCollection|Position[]
     */
    public function getPositions(){
        return $this->positions;
    }

    public function addPosition(Position $position): self
    {
        $this->positions->add($position);
        $position->setWeight($this);
        return $this;
    }

    public function removePosition(Position $position): self
    {
        $this->positions->removeElement($position);
        return $this;
    }


    public function __toString()
    {
        return (string) $this->id;
    }

}
