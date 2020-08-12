<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\IProcessActivityUserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=IProcessActivityUserRepository::class)
 */
class IProcessActivityUser extends DbObject
{
    const PARTICIPATION_ACTIVE       = 1;
    const PARTICIPATION_THIRD_PARTY  = 0;
    const PARTICIPATION_PASSIVE      = -1;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="a_u_id", type="integer", nullable=false)
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $a_u_status;

    /**
     * @ORM\Column(type="boolean")
     */
    public $a_u_leader;

    /**
     * @ORM\Column(type="integer")
     */
    public $a_u_type;

    /**
     * @ORM\Column(type="float")
     */
    public $a_u_mWeight;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $a_u_precomment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $a_u_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $a_u_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    public $a_u_deleted;

    /**
     * @ManyToOne(targetEntity="Team")
     * @JoinColumn(name="team_tea_id", referencedColumnName="tea_id",nullable=false)
     */
    protected $team;

    /**
     * @ManyToOne(targetEntity="InstitutionProcess")
     * @JoinColumn(name="iprocess_inp_id", referencedColumnName="inp_id",nullable=false)
     */
    protected $institutionProcess;

    /**
     * @ManyToOne(targetEntity="IProcessStage")
     * @JoinColumn(name="iprocess_stage_stg_id", referencedColumnName="stg_id",nullable=false)
     */
    protected $stage;

    /**
     * @ManyToOne(targetEntity="IProcessCriterion", inversedBy="participants")
     * @JoinColumn(name="iprocess_criterion_crt_id", referencedColumnName="crt_id",nullable=true)
     */
    protected $criterion;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    public $user_usr;

    /**
     * @ORM\ManyToOne(targetEntity=ExternalUser::class)
     * @ORM\JoinColumn(nullable=false)
     */
    public $external_user_ext_usr;

    /**
     * IProcessActivityUser constructor.
     * @param $id
     * @param $a_u_status
     * @param $a_u_leader
     * @param $a_u_type
     * @param $a_u_mWeight
     * @param $a_u_precomment
     * @param $a_u_createdBy
     * @param $a_u_inserted
     * @param $a_u_deleted
     * @param $team
     * @param $institutionProcess
     * @param $stage
     * @param $criterion
     * @param $user_usr
     * @param $external_user_ext_usr
     */
    public function __construct(
        int $id = 0,
        User $user_usr = null,
        ExternalUser $external_user_ext_usr = null,
        bool $a_u_leader = false,
        int $a_u_type = 1,
        int $a_u_status = 0,
        float $a_u_mWeight = 0.0,
        $a_u_precomment = '',
        $a_u_createdBy = null,
        DateTime $a_u_inserted = null,
        DateTime $a_u_deleted = null,
        Team $team = null,
        InstitutionProcess $institutionProcess = null,
        Stage $stage = null,
        Criterion $criterion
     )
    {
        $this->a_u_status = $a_u_status;
        $this->a_u_leader = $a_u_leader;
        $this->a_u_type = $a_u_type;
        $this->a_u_mWeight = $a_u_mWeight;
        $this->a_u_precomment = $a_u_precomment;
        $this->a_u_inserted = $a_u_inserted;
        $this->a_u_deleted = $a_u_deleted;
        $this->team = $team;
        $this->institutionProcess = $institutionProcess;
        $this->stage = $stage;
        $this->criterion = $criterion;
        $this->user_usr = $user_usr;
        $this->external_user_ext_usr = $external_user_ext_usr;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->a_u_status;
    }

    public function setStatus(int $a_u_status): self
    {
        $this->a_u_status = $a_u_status;

        return $this;
    }

    public function isLeader(): ?bool
    {
        return $this->a_u_leader;
    }

    public function setLeader(bool $a_u_leader): self
    {
        $this->a_u_leader = $a_u_leader;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->a_u_type;
    }

    public function setType(int $a_u_type): self
    {
        $this->a_u_type = $a_u_type;

        return $this;
    }

    public function getMWeight(): ?float
    {
        return $this->a_u_mWeight;
    }

    public function setMWeight(float $a_u_mWeight): self
    {
        $this->a_u_mWeight = $a_u_mWeight;

        return $this;
    }

    public function getPrecomment(): ?string
    {
        return $this->a_u_precomment;
    }

    public function setPrecomment(string $a_u_precomment): self
    {
        $this->a_u_precomment = $a_u_precomment;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->a_u_inserted;
    }

    public function setInserted(?\DateTimeInterface $a_u_inserted): self
    {
        $this->a_u_inserted = $a_u_inserted;

        return $this;
    }

    public function getDeleted(): ?string
    {
        return $this->a_u_deleted;
    }

    public function setDeleted(string $a_u_deleted): self
    {
        $this->a_u_deleted = $a_u_deleted;

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

    /**
     * @return mixed
     */
    public function getInstitutionProcess()
    {
        return $this->institutionProcess;
    }

    /**
     * @param mixed $institutionProcess
     */
    public function setInstitutionProcess($institutionProcess): void
    {
        $this->institutionProcess = $institutionProcess;
    }

    /**
     * @return mixed
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * @param mixed $stage
     */
    public function setStage($stage): void
    {
        $this->stage = $stage;
    }

    /**
     * @return mixed
     */
    public function getCriterion()
    {
        return $this->criterion;
    }

    /**
     * @param mixed $criterion
     */
    public function setCriterion($criterion): void
    {
        $this->criterion = $criterion;
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

    public function getExternalUserExtUsr(): ?ExternalUser
    {
        return $this->external_user_ext_usr;
    }

    public function setExternalUserExtUsr(?ExternalUser $external_user_ext_usr): self
    {
        $this->external_user_ext_usr = $external_user_ext_usr;

        return $this;
    }

    //TODO the direct user

}
