<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ExternalUserRepository;
use DateTime;
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
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $ext_fisrtname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $ext_lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $ext_email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $ext_positionName;

    /**
     * @ORM\Column(type="float")
     */
    public $ext_weight_value;

    /**
     * @ORM\Column(type="boolean")
     */
    public $ext_owner;

    /**
     * @ORM\Column(type="integer")
     */
    public $ext_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $ext_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $ext_last_connected;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $ext_deleted;

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
     * @param int $id
     * @param $ext_fisrtname
     * @param $ext_lastname
     * @param $ext_email
     * @param $ext_positionName
     * @param $ext_weight_value
     * @param $ext_owner
     * @param $ext_createdBy
     * @param $ext_inserted
     * @param $ext_last_connected
     * @param $ext_deleted
     * @param $user
     * @param $client
     * @param $activity_user_act_usr
     * @param $results
     * @param $teamUsers
     */
    public function __construct(
        int $id = 0,
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
        Result $results = null,
        TeamUser $teamUsers = null)
    {
        parent::__construct($id, $ext_createdBy, new DateTime());
        $this->ext_fisrtname = $ext_fisrtname;
        $this->ext_lastname = $ext_lastname;
        $this->ext_email = $ext_email;
        $this->ext_positionName = $ext_positionName;
        $this->ext_weight_value = $ext_weight_value;
        $this->ext_owner = $ext_owner;
        $this->ext_inserted = $ext_inserted;
        $this->ext_last_connected = $ext_last_connected;
        $this->ext_deleted = $ext_deleted;
        $this->user = $user;
        $this->client = $client;
        $this->activity_user_act_usr = $activity_user_act_usr;
        $this->results = $results;
        $this->teamUsers = $teamUsers;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFisrtname(): ?string
    {
        return $this->ext_fisrtname;
    }

    public function setFisrtname(string $ext_fisrtname): self
    {
        $this->ext_fisrtname = $ext_fisrtname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->ext_lastname;
    }

    public function setLastname(string $ext_lastname): self
    {
        $this->ext_lastname = $ext_lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->ext_email;
    }

    public function setEmail(?string $ext_email): self
    {
        $this->ext_email = $ext_email;

        return $this;
    }

    public function getPositionName(): ?string
    {
        return $this->ext_positionName;
    }

    public function setPositionName(string $ext_positionName): self
    {
        $this->ext_positionName = $ext_positionName;

        return $this;
    }

    public function getWeightValue(): ?float
    {
        return $this->ext_weight_value;
    }

    public function setWeightValue(float $ext_weight_value): self
    {
        $this->ext_weight_value = $ext_weight_value;

        return $this;
    }

    public function isOwner(): ?bool
    {
        return $this->ext_owner;
    }

    public function setOwner(bool $ext_owner): self
    {
        $this->ext_owner = $ext_owner;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->ext_inserted;
    }

    public function setInserted(\DateTimeInterface $ext_inserted): self
    {
        $this->ext_inserted = $ext_inserted;

        return $this;
    }

    public function getLastConnected(): ?\DateTimeInterface
    {
        return $this->ext_last_connected;
    }

    public function setLastConnected(?\DateTimeInterface $ext_last_connected): self
    {
        $this->ext_last_connected = $ext_last_connected;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->ext_deleted;
    }

    public function setDeleted(?\DateTimeInterface $ext_deleted): self
    {
        $this->ext_deleted = $ext_deleted;

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
     * @return Collection|ActivityUser[]
     */
    public function getActivityUserActUsr(): Collection
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

    /**
     * @return Collection|Result[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->seternalUserExtId($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->geternalUserExtId() === $this) {
                $result->seternalUserExtId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TeamUser[]
     */
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

    public function getFullName()
    {
        $prefix = $this->firstname ?? '';
        $suffix = $this->lastname ?? '';
        return $prefix . ' ' . $suffix;
    }

    public function getInvertedFullName()
    {
        $prefix = $this->lastname ?? '';
        $suffix = $this->firstname ?? '';
        return $prefix . ' ' . $suffix;
    }
}
