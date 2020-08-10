<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\IProcessActivityUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=IProcessActivityUserRepository::class)
 */
class IProcessActivityUser
{
    const PARTICIPATION_ACTIVE       = 1;
    const PARTICIPATION_THIRD_PARTY  = 0;
    const PARTICIPATION_PASSIVE      = -1;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="a_u_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $a_u_status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $a_u_leader;

    /**
     * @ORM\Column(type="integer")
     */
    private $a_u_type;

    /**
     * @ORM\Column(type="float")
     */
    private $a_u_mWeight;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $a_u_precomment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $a_u_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $a_u_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $a_u_deleted;

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
    private $user_usr;

    /**
     * @ORM\ManyToOne(targetEntity=ExternalUser::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $external_user_ext_usr;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAUStatus(): ?int
    {
        return $this->a_u_status;
    }

    public function setAUStatus(int $a_u_status): self
    {
        $this->a_u_status = $a_u_status;

        return $this;
    }

    public function getAULeader(): ?bool
    {
        return $this->a_u_leader;
    }

    public function setAULeader(bool $a_u_leader): self
    {
        $this->a_u_leader = $a_u_leader;

        return $this;
    }

    public function getAUType(): ?int
    {
        return $this->a_u_type;
    }

    public function setAUType(int $a_u_type): self
    {
        $this->a_u_type = $a_u_type;

        return $this;
    }

    public function getAUMWeight(): ?float
    {
        return $this->a_u_mWeight;
    }

    public function setAUMWeight(float $a_u_mWeight): self
    {
        $this->a_u_mWeight = $a_u_mWeight;

        return $this;
    }

    public function getAUPrecomment(): ?string
    {
        return $this->a_u_precomment;
    }

    public function setAUPrecomment(string $a_u_precomment): self
    {
        $this->a_u_precomment = $a_u_precomment;

        return $this;
    }

    public function getAUCreatedBy(): ?int
    {
        return $this->a_u_createdBy;
    }

    public function setAUCreatedBy(?int $a_u_createdBy): self
    {
        $this->a_u_createdBy = $a_u_createdBy;

        return $this;
    }

    public function getAUInserted(): ?\DateTimeInterface
    {
        return $this->a_u_inserted;
    }

    public function setAUInserted(?\DateTimeInterface $a_u_inserted): self
    {
        $this->a_u_inserted = $a_u_inserted;

        return $this;
    }

    public function getAUDeleted(): ?string
    {
        return $this->a_u_deleted;
    }

    public function setAUDeleted(string $a_u_deleted): self
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

}
