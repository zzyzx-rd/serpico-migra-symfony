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
     * @ORM\Column(name="res_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="res_war", type="float", nullable=true)
     */
    public $weightedAbsoluteResult;

    /**
     * @ORM\Column(name="res_ear", type="float", nullable=true)
     */
    public $equalAbsoluteResult;

    /**
     * @ORM\Column(name="res_wrr", type="float", nullable=true)
     */
    public $weightedRelativeResult;

    /**
     * @ORM\Column(name="res_err", type="float", nullable=true)
     */
    public $equalRelativeResult;

    /**
     * @ORM\Column(name="res_wsd", type="float", nullable=true)
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
     * @ORM\Column(name="res_created_by", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="res_inserted", type="datetime")
     */
    public $inserted;

    /**
     * @ManyToOne(targetEntity="Activity", inversedBy="results")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id")
     */
    protected $activity;

    /**
     * @ManyToOne(targetEntity="Stage", inversedBy="results")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;

    /**
     * @ManyToOne(targetEntity="Criterion", inversedBy="results")
     * @JoinColumn(name="criterion_crt_id", referencedColumnName="crt_id", nullable=true)
     */
    protected $criterion;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="results")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id")
     */
    public $user_usr;

    /**
     * @ORM\ManyToOne(targetEntity=ExternalUser::class)
     * @JoinColumn(name="external_user_ext_usr_id", referencedColumnName="ext_id")
     */
    private $external_user_ext_usr;
    

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
        $this->type = $res_type;
        $this->weightedAbsoluteResult = $res_war;
        $this->equalAbsoluteResult = $res_ear;
        $this->weightedRelativeResult = $res_wrr;
        $this->equalRelativeResult = $res_err;
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
        $this->inserted = $res_inserted;
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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getWeightedAbsoluteResult(): ?float
    {
        return $this->weightedAbsoluteResult;
    }

    public function setWeightedAbsoluteResult(?float $weightedAbsoluteResult): self
    {
        $this->weightedAbsoluteResult = $weightedAbsoluteResult;

        return $this;
    }

    public function getEqualAbsoluteResult(): ?float
    {
        return $this->equalAbsoluteResult;
    }

    public function setEqualAbsoluteResult(?float $equalAbsoluteResult): self
    {
        $this->equalAbsoluteResult = $equalAbsoluteResult;

        return $this;
    }

    public function getWeightedRelativeResult(): ?float
    {
        return $this->weightedRelativeResult;
    }

    public function setWeightedRelativeResult(?float $weightedRelativeResult): self
    {
        $this->weightedRelativeResult = $weightedRelativeResult;

        return $this;
    }

    public function getEqualRelativeResult(): ?float
    {
        return $this->equalRelativeResult;
    }

    public function setEqualRelativeResult(?float $equalRelativeResult): self
    {
        $this->equalRelativeResult = $equalRelativeResult;

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


    public function getInserted(): ?\DateTimeInterface
    {
        return $this->inserted;
    }

    public function setInserted(\DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

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
