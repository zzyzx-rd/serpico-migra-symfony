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
     * @ORM\Column(name="ext_fisrtname", type="string", length=255, nullable=true)
     */
    public $fisrtname;

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
     * @ORM\Column(name="ext_inserted", type="datetime", nullable=true)
     */
    public ?DateTime $inserted;

    /**
     * @ORM\Column(name="ext_last_connected", type="datetime", nullable=true)
     */
    public $last_connected;

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
     * @ORM\OneToMany(targetEntity=ActivityUser::class, mappedBy="external_user_ext_usr")
     */
    public $activity_user_act_usr;

    /**
     * @ORM\OneToMany(targetEntity=TeamUser::class, mappedBy="external_user_ext_id")
     */
    public $teamUsers;

    /**
     * ExternalUser constructor.
     * @param ?int$id
     * @param string $ext_fisrtname
     * @param string $ext_lastname
     * @param $ext_email
     * @param float $ext_weight_value
     * @param $ext_positionName
     * @param $ext_owner
     * @param $ext_createdBy
     * @param $ext_inserted
     * @param $ext_last_connected
     * @param $ext_deleted
     * @param User $user
     * @param Client $client
     * @param ActivityUser $activity_user_act_usr
     * @param TeamUser $teamUsers
     */
    public function __construct(
      ?int $id = 0,
        $ext_fisrtname = '',
        $ext_lastname = '',
        $ext_email = null,
        $ext_weight_value = 0.0,
        $ext_positionName = null,
        $ext_owner = null,
        $ext_createdBy = null,
        $ext_inserted = null,
        $ext_last_connected = null,
        $ext_deleted = null,
        User $user = null,
        Client $client = null,
        ActivityUser $activity_user_act_usr = null,
        TeamUser $teamUsers = null)
    {
        parent::__construct($id, $ext_createdBy, new DateTime());
        $this->fisrtname = $ext_fisrtname;
        $this->lastname = $ext_lastname;
        $this->email = $ext_email;
        $this->positionName = $ext_positionName;
        $this->weight_value = $ext_weight_value;
        $this->owner = $ext_owner;
        $this->inserted = $ext_inserted;
        $this->last_connected = $ext_last_connected;
        $this->deleted = $ext_deleted;
        $this->user = $user;
        $this->client = $client;
        $this->activity_user_act_usr = $activity_user_act_usr;
        $this->teamUsers = $teamUsers;
    }


    public function getFisrtname(): ?string
    {
        return $this->fisrtname;
    }

    public function setFisrtname(string $ext_fisrtname): self
    {
        $this->fisrtname = $ext_fisrtname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $ext_lastname): self
    {
        $this->lastname = $ext_lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $ext_email): self
    {
        $this->email = $ext_email;

        return $this;
    }

    public function getPositionName(): ?string
    {
        return $this->positionName;
    }

    public function setPositionName(string $ext_positionName): self
    {
        $this->positionName = $ext_positionName;

        return $this;
    }

    public function getWeightValue(): ?float
    {
        return $this->weight_value;
    }

    public function setWeightValue(float $ext_weight_value): self
    {
        $this->weight_value = $ext_weight_value;

        return $this;
    }

    public function isOwner(): ?bool
    {
        return $this->owner;
    }

    public function setOwner(bool $ext_owner): self
    {
        $this->owner = $ext_owner;

        return $this;
    }

    public function setInserted(DateTimeInterface $ext_inserted): self
    {
        $this->inserted = $ext_inserted;

        return $this;
    }

    public function getLastConnected(): ?DateTimeInterface
    {
        return $this->last_connected;
    }

    public function setLastConnected(?DateTimeInterface $ext_last_connected): self
    {
        $this->last_connected = $ext_last_connected;

        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeInterface $ext_deleted): self
    {
        $this->deleted = $ext_deleted;

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
    public function setUser($user): void
    {
        $this->user = $user;
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
    public function setClient($client): void
    {
        $this->client = $client;
    }

    /**
     * @return ActivityUser
     */
    public function getActivityUserActUsr(): ActivityUser
    {
        return $this->activity_user_act_usr;
    }

    public function addActivityUserActUsr(ActivityUser $activityUserActUsr): self
    {
        if (!$this->activity_user_act_usr->contains($activityUserActUsr)) {
            $this->activity_user_act_usr[] = $activityUserActUsr;
            $activityUserActUsr->seternalUserExtUsr($this);
        }

        return $this;
    }

    public function removeActivityUserActUsr(ActivityUser $activityUserActUsr): self
    {
        if ($this->activity_user_act_usr->contains($activityUserActUsr)) {
            $this->activity_user_act_usr->removeElement($activityUserActUsr);
            // set the owning side to null (unless already changed)
            if ($activityUserActUsr->geternalUserExtUsr() === $this) {
                $activityUserActUsr->seternalUserExtUsr(null);
            }
        }

        return $this;
    }

    public function getTeamUsers(): Collection
    {
        return $this->teamUsers;
    }

    public function addTeamUser(TeamUser $teamUser): self
    {
        if (!$this->teamUsers->contains($teamUser)) {
            $this->teamUsers[] = $teamUser;
            $teamUser->seternalUserExtId($this);
        }

        return $this;
    }

    public function removeTeamUser(TeamUser $teamUser): self
    {
        if ($this->teamUsers->contains($teamUser)) {
            $this->teamUsers->removeElement($teamUser);
            // set the owning side to null (unless already changed)
            if ($teamUser->geternalUserExtId() === $this) {
                $teamUser->seternalUserExtId(null);
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
