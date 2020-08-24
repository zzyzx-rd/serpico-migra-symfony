<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\IProcessParticipationRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=IProcessParticipationRepository::class)
 */
class IProcessParticipation extends DbObject
{
    public const PARTICIPATION_ACTIVE       = 1;
    public const PARTICIPATION_THIRD_PARTY  = 0;
    public const PARTICIPATION_PASSIVE      = -1;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="par_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="par_status", type="integer", nullable=true)
     */
    public $status;

    /**
     * @ORM\Column(name="par_leader", type="boolean", nullable=true)
     */
    public $leader;

    /**
     * @ORM\Column(name="par_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="par_mWeight", type="float", nullable=true)
     */
    public $mWeight;

    /**
     * @ORM\Column(name="par_precomment", type="string", length=255, nullable=true)
     */
    public $precomment;

    /**
     * @ORM\Column(name="par_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="par_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="par_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @ManyToOne(targetEntity="Team")
     * @JoinColumn(name="team_tea_id", referencedColumnName="tea_id",nullable=true)
     */
    protected $team;

    /**
     * @ManyToOne(targetEntity="InstitutionProcess")
     * @JoinColumn(name="iprocess_inp_id", referencedColumnName="inp_id",nullable=true)
     */
    protected $institutionProcess;

    /**
     * @ManyToOne(targetEntity="IProcessStage", inversedBy="participants")
     * @JoinColumn(name="iprocess_stage_stg_id", referencedColumnName="stg_id",nullable=true)
     */
    protected $stage;

    /**
     * @ManyToOne(targetEntity="IProcessCriterion", inversedBy="participants")
     * @JoinColumn(name="iprocess_criterion_crt_id", referencedColumnName="crt_id",nullable=true)
     */
    protected $criterion;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id",nullable=true)
     */
    public ?User $user;

    /**
     * @ORM\ManyToOne(targetEntity=ExternalUser::class)
     * @ORM\JoinColumn(name="external_useR_ext_usr_id", referencedColumnName="ext_id", nullable=true)
     */
    public ?ExternalUser $external_user;

    /**
     * IProcessParticipation constructor.
     * @param $id
     * @param $par_status
     * @param $par_leader
     * @param $par_type
     * @param $par_mWeight
     * @param $par_precomment
     * @param $par_createdBy
     * @param $par_inserted
     * @param $par_deleted
     * @param $team
     * @param $institutionProcess
     * @param $stage
     * @param $criterion
     * @param $user_usr
     * @param $external_user_ext_usr
     */
    public function __construct(
      ?int $id = 0,
        User $user_usr = null,
        ExternalUser $external_user_ext_usr = null,
        bool $par_leader = false,
        int $par_type = 1,
        int $par_status = 0,
        float $par_mWeight = 0.0,
        $par_precomment = '',
        $par_createdBy = null,
        DateTime $par_inserted = null,
        DateTime $par_deleted = null,
        Team $team = null,
        InstitutionProcess $institutionProcess = null,
        Stage $stage = null,
        Criterion $criterion = null
     )
    {
        parent::__construct($id, $par_createdBy, new DateTime());
        $this->status = $par_status;
        $this->leader = $par_leader;
        $this->type = $par_type;
        $this->mWeight = $par_mWeight;
        $this->precomment = $par_precomment;
        $this->deleted = $par_deleted;
        $this->team = $team;
        $this->institutionProcess = $institutionProcess;
        $this->stage = $stage;
        $this->criterion = $criterion;
        $this->user = $user_usr;
        $this->external_user = $external_user_ext_usr;
    }


    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $par_status): self
    {
        $this->status = $par_status;

        return $this;
    }

    public function isLeader(): ?bool
    {
        return $this->leader;
    }

    public function setLeader(bool $par_leader): self
    {
        $this->leader = $par_leader;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $par_type): self
    {
        $this->type = $par_type;

        return $this;
    }

    public function getMWeight(): ?float
    {
        return $this->mWeight;
    }

    public function setMWeight(float $par_mWeight): self
    {
        $this->mWeight = $par_mWeight;

        return $this;
    }

    public function getPrecomment(): ?string
    {
        return $this->precomment;
    }

    public function setPrecomment(string $par_precomment): self
    {
        $this->precomment = $par_precomment;

        return $this;
    }

    public function setInserted(?DateTimeInterface $par_inserted): self
    {
        $this->inserted = $par_inserted;

        return $this;
    }

    public function getDeleted(): ?string
    {
        return $this->deleted;
    }

    public function setDeleted(string $par_deleted): self
    {
        $this->deleted = $par_deleted;

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
        return $this->external_user;
    }

    public function setExternalUser(?ExternalUser $external_user): self
    {
        $this->external_user = $external_user;

        return $this;
    }

    //TODO the direct user

}
