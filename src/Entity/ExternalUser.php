<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ExternalUserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ExternalUserRepository::class)
 */
class ExternalUser extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="ext_id", type="integer", nullable=false)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="ext_firstname", type="string", length=255, nullable=true)
     */
    public $firstname;

    /**
     * @ORM\Column(name="ext_lastname", type="string", length=255, nullable=true)
     */
    public $lastname;

    /**
     * @ORM\Column(name="ext_email", type="string", length=255, nullable=true)
     */
    public $email;

    /**
     * @ORM\Column(name="ext_positionName", type="string", length=255, nullable=true)
     */
    public $positionName;

    /**
     * @ORM\Column(name="ext_weight_value", type="float", nullable=true)
     */
    public $weight_value;

    /**
     * @ORM\Column(name="ext_owner", type="boolean", nullable=true)
     */
    public $owner;

    /**
     * @ORM\Column(name="ext_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="ext_inserted", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="ext_last_connected", type="datetime", nullable=true)
     */
    public $lastConnected;

    /**
     * @ORM\Column(name="ext_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="externalUsers")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id", nullable=true)
     */
    protected $user;

    /**
     * @ManyToOne(targetEntity="Client", inversedBy="externalUsers")
     * @JoinColumn(name="client_cli_id", referencedColumnName="cli_id", nullable=false)
     */
    protected $client;

    /**
     * @ORM\OneToMany(targetEntity=Participation::class, mappedBy="externalUser")
     */
    public $participations;

    /**
     * @ORM\OneToMany(targetEntity=Member::class, mappedBy="externalUser")
     */
    public $members;

    /**
     * ExternalUser constructor.
     * @param ?int$id
     * @param string $firstname
     * @param string $lastname
     * @param $email
     * @param float $weight_value
     * @param $positionName
     * @param $owner
     * @param $createdBy
     * @param $lastConnected
     * @param $deleted
     * @param User $user
     * @param Client $client
     * @param Participation $participation
     * @param TeamUser $members
     */
    public function __construct(
      ?int $id = 0,
        $firstname = '',
        $lastname = '',
        $email = null,
        $weight_value = 0.0,
        $positionName = null,
        $owner = null,
        $createdBy = null,
        $lastConnected = null,
        $deleted = null,
        User $user = null,
        Client $client = null
    )
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->positionName = $positionName;
        $this->weight_value = $weight_value;
        $this->owner = $owner;
        $this->last_connected = $lastConnected;
        $this->deleted = $deleted;
        $this->user = $user;
        $this->client = $client;
        $this->participations = new ArrayCollection;
        $this->members = new ArrayCollection;
    }


    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPositionName(): ?string
    {
        return $this->positionName;
    }

    public function setPositionName(?string $positionName): self
    {
        $this->positionName = $positionName;
        return $this;
    }

    public function getWeightValue(): ?float
    {
        return $this->weight_value;
    }

    public function setWeightValue(float $weight_value): self
    {
        $this->weight_value = $weight_value;
        return $this;
    }

    public function isOwner(): ?bool
    {
        return $this->owner;
    }

    public function setOwner(bool $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

        return $this;
    }

    public function getLastConnected(): ?DateTimeInterface
    {
        return $this->last_connected;
    }

    public function setLastConnected(?DateTimeInterface $lastConnected): self
    {
        $this->last_connected = $lastConnected;

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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client): self
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getParticipations()
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): self
    {
        $this->participations->add($participation);
        $participation->setExternalUser($this);
        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        $this->participations->removeElement($participation);
        return $this;
    }

    /**
     * @return ArrayCollection|Member[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setExternalUser($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
            // set the owning side to null (unless already changed)
            if ($member->getExternalUser() === $this) {
                $member->setExternalUser(null);
            }
        }

        return $this;
    }

    public function getFullName(): string
    {
        $prefix = $this->firstname ?? '';
        $suffix = $this->lastname ?? '';
        return $prefix . ' ' . $suffix;
    }

    public function getInvertedFullName(): string
    {
        $prefix = $this->lastname ?? '';
        $suffix = $this->firstname ?? '';
        return $prefix . ' ' . $suffix;
    }
}
