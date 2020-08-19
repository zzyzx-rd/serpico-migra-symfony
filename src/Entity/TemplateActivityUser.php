<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TemplateActivityUserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TemplateActivityUserRepository::class)
 */
class TemplateActivityUser extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="a_u_id", type="integer", nullable=false)
     */
    public $id;

    /**
     * @ORM\Column(name="a_u_leader", type="boolean", nullable=true)
     */
    public $leader;

    /**
     * @ORM\Column(name="a_u_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="a_u_mWeight", type="float", nullable=true)
     */
    public $mWeight;

    /**
     * @ORM\Column(name="a_u_precomment", type="string", length=10, nullable=true)
     */
    public $precomment;

    /**
     * @ORM\Column(name="a_u_createdBy", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="a_u_inserted", type="datetime", nullable=true)
     */
    public $inserted;

    /**
     * @ManyToOne(targetEntity="Team")
     * @JoinColumn(name="team_tea_id", referencedColumnName="tea_id",nullable=false)
     */
    protected $team;

    /**
     * @ManyToOne(targetEntity="TemplateActivity", inversedBy="participants")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id",nullable=false)
     */
    protected $activity;

    /**
     * @ManyToOne(targetEntity="TemplateStage", inversedBy="participants")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id",nullable=false)
     */
    protected $stage;

    /**
     * @ManyToOne(targetEntity="TemplateCriterion", inversedBy="participants")
     * @JoinColumn(name="criterion_crt_id", referencedColumnName="crt_id",nullable=true)
     */
    protected $criterion;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="user_usr_id", referencedColumnName="usr_id", nullable=false)
     */
    public $user;

    /**
     * @ORM\ManyToOne(targetEntity=ExternalUser::class)
     * @ORM\JoinColumn(name="external_user_ext_usr_id", referencedColumnName="ext_id", nullable=true)
     */
    public $ext_user;

    /**
     * TemplateActivityUser constructor.
     * @param $id
     * @param $a_u_leader
     * @param $a_u_type
     * @param $a_u_mWeight
     * @param $a_u_precomment
     * @param $a_u_createdBy
     * @param $a_u_inserted
     * @param $team
     * @param $activity
     * @param $stage
     * @param $criterion
     * @param $user_usr
     * @param $external_user_ext_usr
     */
    public function __construct(
        $id = 0,
        $user_usr = null,
        $external_user_ext_usr = null,
        $a_u_leader = false,
        $a_u_type = 1,
        $a_u_mWeight = 0.0,
        $a_u_precomment = null,
        $a_u_createdBy = null,
        $a_u_inserted = null,
        Team $team = null,
        Activity $activity = null,
        Stage $stage = null,
        Criterion $criterion = null)
    {
        parent::__construct($id, $a_u_createdBy, new DateTime());
        $this->leader = $a_u_leader;
        $this->type = $a_u_type;
        $this->mWeight = $a_u_mWeight;
        $this->precomment = $a_u_precomment;
        $this->inserted = $a_u_inserted;
        $this->team = $team;
        $this->activity = $activity;
        $this->stage = $stage;
        $this->criterion = $criterion;
        $this->user = $user_usr;
        $this->ext_user = $external_user_ext_usr;
    }

    public function getLeader(): ?bool
    {
        return $this->leader;
    }

    public function setLeader(bool $a_u_leader): self
    {
        $this->leader = $a_u_leader;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $a_u_type): self
    {
        $this->type = $a_u_type;

        return $this;
    }

    public function getMWeight(): ?float
    {
        return $this->mWeight;
    }

    public function setMWeight(float $a_u_mWeight): self
    {
        $this->mWeight = $a_u_mWeight;

        return $this;
    }

    public function getPrecomment(): ?string
    {
        return $this->precomment;
    }

    public function setPrecomment(string $a_u_precomment): self
    {
        $this->precomment = $a_u_precomment;

        return $this;
    }

    public function getInserted(): ?DateTimeInterface
    {
        return $this->inserted;
    }

    public function setInserted(?DateTimeInterface $a_u_inserted): self
    {
        $this->inserted = $a_u_inserted;

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
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param mixed $activity
     */
    public function setActivity($activity): void
    {
        $this->activity = $activity;
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

    public function getExtUser(): ?ExternalUser
    {
        return $this->ext_user;
    }

    public function setExtUser(?ExternalUser $ext_user): self
    {
        $this->ext_user = $ext_user;

        return $this;
    }

}
