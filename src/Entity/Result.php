<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ResultRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ResultRepository::class)
 */
class Result extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="res_id", type="integer", nullable=false)
     */
    public $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $res_type;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $res_war;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $res_ear;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $res_wrr;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $res_err;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $res_wsd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $res_esd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $res_wdf;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $res_edr;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $res_wsd_max;

    /**
     * @ORM\Column(type="float")
     */
    public $res_esd_max;

    /**
     * @ORM\Column(type="float")
     */
    public $res_win;

    /**
     * @ORM\Column(type="float")
     */
    public $res_ein;

    /**
     * @ORM\Column(type="float")
     */
    public $res_win_max;

    /**
     * @ORM\Column(type="float")
     */
    public $res_ein_max;

    /**
     * @ORM\Column(type="float")
     */
    public $res_wdr_gen;

    /**
     * @ORM\Column(type="float")
     */
    public $res_res_der_gen;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $res_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $res_inserted;

    /**
     * @ManyToOne(targetEntity="Activity")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id")
     */
    protected $activity;

    /**
     * @ManyToOne(targetEntity="Stage")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;

    /**
     * @ManyToOne(targetEntity="Criterion")
     * @JoinColumn(name="criterion_crt_id", referencedColumnName="crt_id", nullable=true)
     */
    protected $criterion;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="results")
     */
    public $user_usr;

    /**
     * @ORM\ManyToOne(targetEntity=ExternalUser::class, inversedBy="results")
     */
    public $external_user_ext_usr;

    /**
     * Result constructor.
     * @param int $id
     * @param $res_type
     * @param $res_war
     * @param $res_ear
     * @param $res_wrr
     * @param $res_err
     * @param $res_wsd
     * @param $res_esd
     * @param $res_wdf
     * @param $res_edr
     * @param $res_wsd_max
     * @param $res_esd_max
     * @param $res_win
     * @param $res_ein
     * @param $res_win_max
     * @param $res_ein_max
     * @param $res_wdr_gen
     * @param $res_res_der_gen
     * @param $res_createdBy
     * @param $res_inserted
     * @param Activity $activity
     * @param Stage $stage
     * @param Criterion $criterion
     * @param User $user_usr
     * @param null $external_user_ext_usr
     */
    public function __construct(
        $id = 0,
        $res_type = null,
        $res_war = null,
        $res_ear = null,
        $res_wrr = null,
        $res_err = null,
        $res_wsd = null,
        $res_esd = null,
        $res_wdf = null,
        $res_edr = null,
        $res_wsd_max = null,
        $res_esd_max = null,
        $res_win = null,
        $res_ein = null,
        $res_win_max = null,
        $res_ein_max = null,
        $res_wdr_gen= null,
        $res_res_der_gen= null,
        $res_createdBy= null,
        $res_inserted= null,
        Activity $activity= null,
        Stage $stage= null,
        Criterion $criterion= null,
        User $user_usr= null,
        $external_user_ext_usr= null)
    {
        parent::__construct($id, $res_createdBy, new DateTime());
        $this->res_type = $res_type;
        $this->res_war = $res_war;
        $this->res_ear = $res_ear;
        $this->res_wrr = $res_wrr;
        $this->res_err = $res_err;
        $this->res_wsd = $res_wsd;
        $this->res_esd = $res_esd;
        $this->res_wdf = $res_wdf;
        $this->res_edr = $res_edr;
        $this->res_wsd_max = $res_wsd_max;
        $this->res_esd_max = $res_esd_max;
        $this->res_win = $res_win;
        $this->res_ein = $res_ein;
        $this->res_win_max = $res_win_max;
        $this->res_ein_max = $res_ein_max;
        $this->res_wdr_gen = $res_wdr_gen;
        $this->res_res_der_gen = $res_res_der_gen;
        $this->res_inserted = $res_inserted;
        $this->activity = $activity;
        $this->stage = $stage;
        $this->criterion = $criterion;
        $this->user_usr = $user_usr;
        $this->external_user_ext_usr = $external_user_ext_usr;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResType(): ?int
    {
        return $this->res_type;
    }

    public function setResType(?int $res_type): self
    {
        $this->res_type = $res_type;

        return $this;
    }

    public function getResWar(): ?float
    {
        return $this->res_war;
    }

    public function setResWar(?float $res_war): self
    {
        $this->res_war = $res_war;

        return $this;
    }

    public function getResEar(): ?float
    {
        return $this->res_ear;
    }

    public function setResEar(?float $res_ear): self
    {
        $this->res_ear = $res_ear;

        return $this;
    }

    public function getResWrr(): ?float
    {
        return $this->res_wrr;
    }

    public function setResWrr(?float $res_wrr): self
    {
        $this->res_wrr = $res_wrr;

        return $this;
    }

    public function getResErr(): ?float
    {
        return $this->res_err;
    }

    public function setResErr(?float $res_err): self
    {
        $this->res_err = $res_err;

        return $this;
    }

    public function getResWsd(): ?float
    {
        return $this->res_wsd;
    }

    public function setResWsd(?float $res_wsd): self
    {
        $this->res_wsd = $res_wsd;

        return $this;
    }

    public function getResEsd(): ?float
    {
        return $this->res_esd;
    }

    public function setResEsd(?float $res_esd): self
    {
        $this->res_esd = $res_esd;

        return $this;
    }

    public function getResWdf(): ?float
    {
        return $this->res_wdf;
    }

    public function setResWdf(?float $res_wdf): self
    {
        $this->res_wdf = $res_wdf;

        return $this;
    }

    public function getResEdr(): ?float
    {
        return $this->res_edr;
    }

    public function setResEdr(?float $res_edr): self
    {
        $this->res_edr = $res_edr;

        return $this;
    }

    public function getResWsdMax(): ?float
    {
        return $this->res_wsd_max;
    }

    public function setResWsdMax(?float $res_wsd_max): self
    {
        $this->res_wsd_max = $res_wsd_max;

        return $this;
    }

    public function getResEsdMax(): ?float
    {
        return $this->res_esd_max;
    }

    public function setResEsdMax(float $res_esd_max): self
    {
        $this->res_esd_max = $res_esd_max;

        return $this;
    }

    public function getResWin(): ?float
    {
        return $this->res_win;
    }

    public function setResWin(float $res_win): self
    {
        $this->res_win = $res_win;

        return $this;
    }

    public function getResEin(): ?float
    {
        return $this->res_ein;
    }

    public function setResEin(float $res_ein): self
    {
        $this->res_ein = $res_ein;

        return $this;
    }

    public function getResWinMax(): ?float
    {
        return $this->res_win_max;
    }

    public function setResWinMax(float $res_win_max): self
    {
        $this->res_win_max = $res_win_max;

        return $this;
    }

    public function getResEinMax(): ?float
    {
        return $this->res_ein_max;
    }

    public function setResEinMax(float $res_ein_max): self
    {
        $this->res_ein_max = $res_ein_max;

        return $this;
    }

    public function getResWdrGen(): ?float
    {
        return $this->res_wdr_gen;
    }

    public function setResWdrGen(float $res_wdr_gen): self
    {
        $this->res_wdr_gen = $res_wdr_gen;

        return $this;
    }

    public function getResResDerGen(): ?float
    {
        return $this->res_res_der_gen;
    }

    public function setResResDerGen(float $res_res_der_gen): self
    {
        $this->res_res_der_gen = $res_res_der_gen;

        return $this;
    }

    public function getResCreatedBy(): ?int
    {
        return $this->res_createdBy;
    }

    public function setResCreatedBy(?int $res_createdBy): self
    {
        $this->res_createdBy = $res_createdBy;

        return $this;
    }

    public function getResInserted(): ?\DateTimeInterface
    {
        return $this->res_inserted;
    }

    public function setResInserted(\DateTimeInterface $res_inserted): self
    {
        $this->res_inserted = $res_inserted;

        return $this;
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
}
