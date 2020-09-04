<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ResultRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
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
    public ?int $id;

    /**
     * @ORM\Column(name="res_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @Column(name="res_war", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $weightedAbsoluteResult;
    /**
     * @Column(name="res_ear", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $equalAbsoluteResult;
    /**
     * @Column(name="res_wrr", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $weightedRelativeResult;
    /**
     * @Column(name="res_err", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $equalRelativeResult;
    /**
     * @Column(name="res_wsd", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $weightedStdDev;
    /**
     * @Column(name="res_esd", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $equalStdDev;
    /**
     * @Column(name="res_wdr", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $weightedDevRatio;
    /**
     * @Column(name="res_edr", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $equalDevRatio;
    /**
     * @Column(name="res_wsd_max", length= 10, type="float")
     * @var float
     */
    protected $weightedStdDevMax;
    /**
     * @Column(name="res_esd_max", length= 10, type="float")
     * @var float
     */
    protected $equalStdDevMax;
    /**
     * @Column(name="res_win", length= 10, type="float")
     * @var float
     */
    protected $weightedInertia;
    /**
     * @Column(name="res_ein", length= 10, type="float")
     * @var float
     */
    protected $equalInertia;
    /**
     * @Column(name="res_win_max", length= 10, type="float")
     * @var float
     */
    protected $weightedInertiaMax;
    /**
     * @Column(name="res_ein_max", length= 10, type="float")
     * @var float
     */
    protected $equalInertiaMax;
    /**
     * @Column(name="res_wdr_gen", length= 10, type="float")
     * @var float
     */
    protected $weightedDistanceRatio;
    /**
     * @Column(name="res_edr_gen", length= 10, type="float")
     * @var float
     */
    protected $equalDistanceRatio;
    /**
     * @ORM\Column(name="res_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="res_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

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
     * @JoinColumn(name="user_id", referencedColumnName="usr_id")
     */
    public $user;

    /**
     * @ORM\ManyToOne(targetEntity=ExternalUser::class)
     * @JoinColumn(name="externalUser_id", referencedColumnName="ext_id")
     */
    private $externalUser;


    /**
     * Result constructor.
     * @param ?int$id
     * @param $res_type
     * @param $res_war
     * @param $res_ear
     * @param $res_wrr
     * @param $res_err
     * @param null $weightedAbsoluteResult
     * @param null $equalAbsoluteResult
     * @param null $weightedRelativeResult
     * @param null $equalRelativeResult
     * @param null $weightedStdDev
     * @param null $equalStdDev
     * @param null $weightedDevRatio
     * @param null $equalDevRatio
     * @param null $weightedStdDevMax
     * @param null $equalStdDevMax
     * @param null $weightedInertia
     * @param null $equalInertia
     * @param null $weightedInertiaMax
     * @param null $equalInertiaMax
     * @param null $weightedDistanceRatio
     * @param null $equalDistanceRatio
     * @param $res_createdBy
     * @param Activity $activity
     * @param Stage $stage
     * @param Criterion $criterion
     * @param User $user
     * @param null $externalUser
     */
    public function __construct(
        $id = 0,
        $res_type = null,
        $res_war = null,
        $res_ear = null,
        $res_wrr = null,
        $res_err = null,
        $weightedAbsoluteResult = null,
        $equalAbsoluteResult = null,
        $weightedRelativeResult = null,
        $equalRelativeResult = null,
        $weightedStdDev = null,
        $equalStdDev = null,
        $weightedDevRatio = null,
        $equalDevRatio = null,
        $weightedStdDevMax = null,
        $equalStdDevMax = null,
        $weightedInertia = null,
        $equalInertia = null,
        $weightedInertiaMax = null,
        $equalInertiaMax = null,
        $weightedDistanceRatio = null,
        $equalDistanceRatio = null,
        $res_createdBy= null,
        Activity $activity= null,
        Stage $stage= null,
        Criterion $criterion= null,
        User $user= null,
        ExternalUser $externalUser= null)
    {
        parent::__construct($id, $res_createdBy, new DateTime());
        $this->type = $res_type;
        $this->weightedAbsoluteResult = $res_war;
        $this->equalAbsoluteResult = $res_ear;
        $this->weightedRelativeResult = $res_wrr;
        $this->equalRelativeResult = $res_err;
        $this->weightedAbsoluteResult = $weightedAbsoluteResult;
        $this->equalAbsoluteResult = $equalAbsoluteResult;
        $this->weightedRelativeResult = $weightedRelativeResult;
        $this->equalRelativeResult = $equalRelativeResult;
        $this->weightedStdDev = $weightedStdDev;
        $this->equalStdDev = $equalStdDev;
        $this->weightedDevRatio = $weightedDevRatio;
        $this->equalDevRatio = $equalDevRatio;
        $this->weightedStdDevMax = $weightedStdDevMax;
        $this->equalStdDevMax = $equalStdDevMax;
        $this->weightedInertia = $weightedInertia;
        $this->equalInertia = $equalInertia;
        $this->weightedInertiaMax = $weightedInertiaMax;
        $this->equalInertiaMax = $equalInertiaMax;
        $this->weightedDistanceRatio = $weightedDistanceRatio;
        $this->equalDistanceRatio = $equalDistanceRatio;
        $this->activity = $activity;
        $this->stage = $stage;
        $this->criterion = $criterion;
        $this->user = $user;
        $this->externalUser = $externalUser;
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

    /**
     * @return float
     */
    public function getWeightedStdDev(): float
    {
        return $this->weightedStdDev;
    }

    /**
     * @param float $weightedStdDev
     * @return Result
     */
    public function setWeightedStdDev(float $weightedStdDev): Result
    {
        $this->weightedStdDev = $weightedStdDev;
        return $this;
    }

    /**
     * @return float
     */
    public function getEqualStdDev(): float
    {
        return $this->equalStdDev;
    }

    /**
     * @param float $equalStdDev
     * @return Result
     */
    public function setEqualStdDev(float $equalStdDev): Result
    {
        $this->equalStdDev = $equalStdDev;
        return $this;
    }

    /**
     * @return float
     */
    public function getWeightedDevRatio(): float
    {
        return $this->weightedDevRatio;
    }

    /**
     * @param float $weightedDevRatio
     * @return Result
     */
    public function setWeightedDevRatio(float $weightedDevRatio): Result
    {
        $this->weightedDevRatio = $weightedDevRatio;
        return $this;
    }

    /**
     * @return float
     */
    public function getEqualDevRatio(): float
    {
        return $this->equalDevRatio;
    }

    /**
     * @param float $equalDevRatio
     * @return Result
     */
    public function setEqualDevRatio(float $equalDevRatio): Result
    {
        $this->equalDevRatio = $equalDevRatio;
        return $this;
    }

    /**
     * @return float
     */
    public function getWeightedStdDevMax(): float
    {
        return $this->weightedStdDevMax;
    }

    /**
     * @param float $weightedStdDevMax
     * @return Result
     */
    public function setWeightedStdDevMax(float $weightedStdDevMax): Result
    {
        $this->weightedStdDevMax = $weightedStdDevMax;
        return $this;
    }

    /**
     * @return float
     */
    public function getEqualStdDevMax(): float
    {
        return $this->equalStdDevMax;
    }

    /**
     * @param float $equalStdDevMax
     * @return Result
     */
    public function setEqualStdDevMax(float $equalStdDevMax): Result
    {
        $this->equalStdDevMax = $equalStdDevMax;
        return $this;
    }

    /**
     * @return float
     */
    public function getWeightedInertia(): float
    {
        return $this->weightedInertia;
    }

    /**
     * @param float $weightedInertia
     * @return Result
     */
    public function setWeightedInertia(float $weightedInertia): Result
    {
        $this->weightedInertia = $weightedInertia;
        return $this;
    }

    /**
     * @return float
     */
    public function getEqualInertia(): float
    {
        return $this->equalInertia;
    }

    /**
     * @param float $equalInertia
     * @return Result
     */
    public function setEqualInertia(float $equalInertia): Result
    {
        $this->equalInertia = $equalInertia;
        return $this;
    }

    /**
     * @return float
     */
    public function getWeightedInertiaMax(): float
    {
        return $this->weightedInertiaMax;
    }

    /**
     * @param float $weightedInertiaMax
     * @return Result
     */
    public function setWeightedInertiaMax(float $weightedInertiaMax): Result
    {
        $this->weightedInertiaMax = $weightedInertiaMax;
        return $this;
    }

    /**
     * @return float
     */
    public function getEqualInertiaMax(): float
    {
        return $this->equalInertiaMax;
    }

    /**
     * @param float $equalInertiaMax
     * @return Result
     */
    public function setEqualInertiaMax(float $equalInertiaMax): Result
    {
        $this->equalInertiaMax = $equalInertiaMax;
        return $this;
    }

    /**
     * @return float
     */
    public function getWeightedDistanceRatio(): float
    {
        return $this->weightedDistanceRatio;
    }

    /**
     * @param float $weightedDistanceRatio
     * @return Result
     */
    public function setWeightedDistanceRatio(float $weightedDistanceRatio): Result
    {
        $this->weightedDistanceRatio = $weightedDistanceRatio;
        return $this;
    }

    /**
     * @return float
     */
    public function getEqualDistanceRatio(): float
    {
        return $this->equalDistanceRatio;
    }

    /**
     * @param float $equalDistanceRatio
     * @return Result
     */
    public function setEqualDistanceRatio(float $equalDistanceRatio): Result
    {
        $this->equalDistanceRatio = $equalDistanceRatio;
        return $this;
    }


    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

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
        $this->externalUser = $externalUser;
        return $this;
    }
}
