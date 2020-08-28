<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MemberRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=MemberRepository::class)
 */
class Member extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="mem_id", type="integer", length=10, nullable=true)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="mem_leader", type="boolean", nullable=true)
     */
    public $leader;

    /**
     * @ORM\Column(name="mem_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="mem_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="mem_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @ORM\Column(name="mem_is_deleted", type="boolean", nullable=true)
     */
    public $isDeleted;

    /**
     *@ManyToOne(targetEntity="Team", inversedBy="members")
     *@JoinColumn(name="team_tea_id", referencedColumnName="tea_id", nullable=false)
     */
    protected $team;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="members")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id")
     */
    public $user;

    /**
     * @ORM\ManyToOne(targetEntity=ExternalUser::class, inversedBy="members")
     * @ORM\JoinColumn(name="external_user_ext_usr_id", referencedColumnName="ext_id", nullable=false)
     */
    public $externalUser;

    /**
     * TeamUser constructor.
     * @param ?int$id
     * @param $leader
     * @param $createdBy
     * @param $inserted
     * @param $deleted
     * @param $isDeleted
     * @param $team
     * @param $user
     * @param $externalUser
     */
    public function __construct(
      ?int $id = 0,
        $user = null,
        $externalUser = null,
        $createdBy = null,
        $leader = false,
        $inserted = null,
        $deleted = null,
        $isDeleted = false,
        $team = null)
    {
        parent::__construct($id,$createdBy , new DateTime());
        $this->leader = $leader;
        $this->deleted = $deleted;
        $this->isDeleted = $isDeleted;
        $this->team = $team;
        $this->user = $user;
        $this->externalUser = $externalUser;
    }

    public function isLeader(): ?bool
    {
        return $this->leader;
    }

    public function setLeader(bool $leader): self
    {
        $this->leader = $leader;
        return $this;
    }

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }

    public function isDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeInterface $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $is_deleted): self
    {
        $this->isDeleted = $is_deleted;
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
    public function setTeam($team): self
    {
        $this->team = $team;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getExternalUser(): ?ExternalUser
    {
        return $this->externalUser;
    }

    public function setExternalUser(?ExternalUser $externalUser): self
    {
        $this->external_User = $externalUser;
        return $this;
    }
    public function toggleIsDeleted(): self
    {
        $this->isDeleted = !$this->isDeleted;
        return $this;
    }
}
