<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TeamUserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TeamUserRepository::class)
 */
class TeamUser extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="tus_id", type="integer", length=10, nullable=true)
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(name="tus_leader", type="boolean", nullable=true)
     */
    public $leader;

    /**
     * @ORM\Column(name="tus_created_by", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="tus_inserted", type="datetime", nullable=true)
     */
    public $inserted;

    /**
     * @ORM\Column(name="tus_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @ORM\Column(name="tus_is_deleted", type="boolean", nullable=true)
     */
    public $isDeleted;

    /**
     *@ManyToOne(targetEntity="Team", inversedBy="teamUsers")
     *@JoinColumn(name="team_tea_id", referencedColumnName="tea_id", nullable=false)
     */
    protected $team;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="teamUsers")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id")
     */
    public $user_usr;

    /**
     * @ORM\ManyToOne(targetEntity=ExternalUser::class, inversedBy="teamUsers")
     * @ORM\JoinColumn(name="external_user_ext_usr_id", referencedColumnName="ext_id", nullable=false)
     */
    public $external_user_ext_id;

    /**
     * TeamUser constructor.
     * @param int $id
     * @param $tus_leader
     * @param $tus_createdBy
     * @param $tus_inserted
     * @param $tus_deleted
     * @param $tus_is_deleted
     * @param $team
     * @param $user_usr
     * @param $external_user_ext_id
     */
    public function __construct(
        int $id = 0,
        $user_usr = null,
        $external_user_ext_id = null,
        $tus_createdBy = null,
        $tus_leader = false,
        $tus_inserted = null,
        $tus_deleted = null,
        $tus_is_deleted = false,
        $team = null)
    {
        parent::__construct($id,$tus_createdBy , new DateTime());
        $this->leader = $tus_leader;
        $this->inserted = $tus_inserted;
        $this->deleted = $tus_deleted;
        $this->isDeleted = $tus_is_deleted;
        $this->team = $team;
        $this->user_usr = $user_usr;
        $this->external_user_ext_id = $external_user_ext_id;
    }

    public function getLeader(): ?bool
    {
        return $this->leader;
    }

    public function setLeader(bool $tus_leader): self
    {
        $this->leader = $tus_leader;

        return $this;
    }

    public function setInserted(DateTimeInterface $tus_inserted): self
    {
        $this->inserted = $tus_inserted;

        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeInterface $tus_deleted): self
    {
        $this->deleted = $tus_deleted;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $tus_is_deleted): self
    {
        $this->isDeleted = $tus_is_deleted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param mixed $team
     */
    public function setTeam($team): void
    {
        $this->team = $team;
    }

    public function getUserUsr(): ?User
    {
        return $this->user_usr;
    }

    public function setUserUsr(?User $user_usr): self
    {
        $this->user_usr = $user_usr;

        return $this;
    }

    public function getExternalUserExtId(): ?ExternalUser
    {
        return $this->external_user_ext_id;
    }

    public function setExternalUserExtId(?ExternalUser $external_user_ext_id): self
    {
        $this->external_user_ext_id = $external_user_ext_id;

        return $this;
    }
    public function toggleIsDeleted(): TeamUser
    {
        $this->isDeleted = !$this->isDeleted;
        return $this;
    }
}
