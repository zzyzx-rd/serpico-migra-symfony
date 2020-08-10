<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ExternalUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ExternalUserRepository::class)
 */
class ExternalUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="ext_id", type="integer", nullable=false)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ext_fisrtname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ext_lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ext_email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ext_positionName;

    /**
     * @ORM\Column(type="float")
     */
    private $ext_weight_value;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ext_owner;

    /**
     * @ORM\Column(type="integer")
     */
    private $ext_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $ext_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $ext_last_connected;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $ext_deleted;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id", nullable=true)
     */
    protected $user;

    /**
     * @ManyToOne(targetEntity="Client")
     * @JoinColumn(name="client_cli_id", referencedColumnName="cli_id", nullable=false)
     */
    protected $client;

    /**
     * @ORM\OneToMany(targetEntity=ActivityUser::class, mappedBy="external_user_ext_usr")
     */
    private $activity_user_act_usr;

    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="external_user_ext_id")
     */
    private $results;

    /**
     * @ORM\OneToMany(targetEntity=TeamUser::class, mappedBy="external_user_ext_id")
     */
    private $teamUsers;

    public function __construct()
    {
        $this->activity_user_act_usr = new ArrayCollection();
        $this->results = new ArrayCollection();
        $this->teamUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExtFisrtname(): ?string
    {
        return $this->ext_fisrtname;
    }

    public function setExtFisrtname(string $ext_fisrtname): self
    {
        $this->ext_fisrtname = $ext_fisrtname;

        return $this;
    }

    public function getExtLastname(): ?string
    {
        return $this->ext_lastname;
    }

    public function setExtLastname(string $ext_lastname): self
    {
        $this->ext_lastname = $ext_lastname;

        return $this;
    }

    public function getExtEmail(): ?string
    {
        return $this->ext_email;
    }

    public function setExtEmail(?string $ext_email): self
    {
        $this->ext_email = $ext_email;

        return $this;
    }

    public function getExtPositionName(): ?string
    {
        return $this->ext_positionName;
    }

    public function setExtPositionName(string $ext_positionName): self
    {
        $this->ext_positionName = $ext_positionName;

        return $this;
    }

    public function getExtWeightValue(): ?float
    {
        return $this->ext_weight_value;
    }

    public function setExtWeightValue(float $ext_weight_value): self
    {
        $this->ext_weight_value = $ext_weight_value;

        return $this;
    }

    public function getExtOwner(): ?bool
    {
        return $this->ext_owner;
    }

    public function setExtOwner(bool $ext_owner): self
    {
        $this->ext_owner = $ext_owner;

        return $this;
    }

    public function getExtCreatedBy(): ?int
    {
        return $this->ext_createdBy;
    }

    public function setExtCreatedBy(int $ext_createdBy): self
    {
        $this->ext_createdBy = $ext_createdBy;

        return $this;
    }

    public function getExtInserted(): ?\DateTimeInterface
    {
        return $this->ext_inserted;
    }

    public function setExtInserted(\DateTimeInterface $ext_inserted): self
    {
        $this->ext_inserted = $ext_inserted;

        return $this;
    }

    public function getExtLastConnected(): ?\DateTimeInterface
    {
        return $this->ext_last_connected;
    }

    public function setExtLastConnected(?\DateTimeInterface $ext_last_connected): self
    {
        $this->ext_last_connected = $ext_last_connected;

        return $this;
    }

    public function getExtDeleted(): ?\DateTimeInterface
    {
        return $this->ext_deleted;
    }

    public function setExtDeleted(?\DateTimeInterface $ext_deleted): self
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
            $activityUserActUsr->setExternalUserExtUsr($this);
        }

        return $this;
    }

    public function removeActivityUserActUsr(ActivityUser $activityUserActUsr): self
    {
        if ($this->activity_user_act_usr->contains($activityUserActUsr)) {
            $this->activity_user_act_usr->removeElement($activityUserActUsr);
            // set the owning side to null (unless already changed)
            if ($activityUserActUsr->getExternalUserExtUsr() === $this) {
                $activityUserActUsr->setExternalUserExtUsr(null);
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
            $result->setExternalUserExtId($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getExternalUserExtId() === $this) {
                $result->setExternalUserExtId(null);
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
            $teamUser->setExternalUserExtId($this);
        }

        return $this;
    }

    public function removeTeamUser(TeamUser $teamUser): self
    {
        if ($this->teamUsers->contains($teamUser)) {
            $this->teamUsers->removeElement($teamUser);
            // set the owning side to null (unless already changed)
            if ($teamUser->getExternalUserExtId() === $this) {
                $teamUser->setExternalUserExtId(null);
            }
        }

        return $this;
    }

}
