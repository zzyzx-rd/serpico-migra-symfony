<?php

namespace App\Entity;

use App\Repository\ResultProjectRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity(repositoryClass=ResultProjectRepository::class)
 */
class ResultProject extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="rsp_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="rsp_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @Column(name="rsp_war", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $weightedAbsoluteResult;
    /**
     * @Column(name="rsp_ear", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $equalAbsoluteResult;
    /**
     * @Column(name="rsp_wrr", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $weightedRelativeResult;
    /**
     * @Column(name="rsp_err", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $equalRelativeResult;
    /**
     * @Column(name="rsp_wsd", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $weightedStdDev;
    /**
     * @Column(name="rsp_esd", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $equalStdDev;
    /**
     * @Column(name="rsp_wdr", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $weightedDevRatio;
    /**
     * @Column(name="rsp_edr", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $equalDevRatio;
    /**
     * @Column(name="rsp_wsd_max", length= 10, type="float")
     * @var float
     */
    protected $weightedStdDevMax;
    /**
     * @Column(name="rsp_esd_max", length= 10, type="float")
     * @var float
     */
    protected $equalStdDevMax;
    /**
     * @Column(name="rsp_win", length= 10, type="float")
     * @var float
     */
    protected $weightedInertia;
    /**
     * @Column(name="rsp_ein", length= 10, type="float")
     * @var float
     */
    protected $equalInertia;
    /**
     * @Column(name="rsp_win_max", length= 10, type="float")
     * @var float
     */
    protected $weightedInertiaMax;
    /**
     * @Column(name="rsp_ein_max", length= 10, type="float")
     * @var float
     */
    protected $equalInertiaMax;
    /**
     * @Column(name="rsp_wdr_gen", length= 10, type="float")
     * @var float
     */
    protected $weightedDistanceRatio;
    /**
     * @Column(name="rsp_edr_gen", length= 10, type="float")
     * @var float
     */
    protected $equalDistanceRatio;
    /**
     * @ORM\Column(name="rsp_createdBy", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="rsp_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="Activity", inversedBy="projectResults")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id")
     */
    protected $activity;
    /**
     * @ManyToOne(targetEntity="Stage", inversedBy="projectResults")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;
    /**
     * @ManyToOne(targetEntity="Criterion")
     * @JoinColumn(name="criterion_crt_id", referencedColumnName="crt_id", nullable=true)
     */
    protected $criterion;

    /**
     * ResultProject constructor.
     * @param $id
     * @param $rsp_type
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
     * @param $rsp_createdBy
     * @param $activity
     * @param $stage
     * @param $criterion
     */
    public function __construct(
        $id = null,
        $rsp_type = null,
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
        $rsp_createdBy = null,
        $activity = null,
        $stage = null,
        $criterion = null)
    {
        parent::__construct($id, $rsp_createdBy, new DateTime());
        $this->type = $rsp_type;
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

    /**
     * @return float
     */
    public function getWeightedAbsoluteResult(): float
    {
        return $this->weightedAbsoluteResult;
    }

    /**
     * @param float $weightedAbsoluteResult
     * @return ResultProject
     */
    public function setWeightedAbsoluteResult(float $weightedAbsoluteResult): ResultProject
    {
        $this->weightedAbsoluteResult = $weightedAbsoluteResult;
        return $this;
    }

    /**
     * @return float
     */
    public function getEqualAbsoluteResult(): float
    {
        return $this->equalAbsoluteResult;
    }

    /**
     * @param float $equalAbsoluteResult
     * @return ResultProject
     */
    public function setEqualAbsoluteResult(float $equalAbsoluteResult): ResultProject
    {
        $this->equalAbsoluteResult = $equalAbsoluteResult;
        return $this;
    }

    /**
     * @return float
     */
    public function getWeightedRelativeResult(): float
    {
        return $this->weightedRelativeResult;
    }

    /**
     * @param float $weightedRelativeResult
     * @return ResultProject
     */
    public function setWeightedRelativeResult(float $weightedRelativeResult): ResultProject
    {
        $this->weightedRelativeResult = $weightedRelativeResult;
        return $this;
    }

    /**
     * @return float
     */
    public function getEqualRelativeResult(): float
    {
        return $this->equalRelativeResult;
    }

    /**
     * @param float $equalRelativeResult
     * @return ResultProject
     */
    public function setEqualRelativeResult(float $equalRelativeResult): ResultProject
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
     * @return ResultProject
     */
    public function setWeightedStdDev(float $weightedStdDev): ResultProject
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
     * @return ResultProject
     */
    public function setEqualStdDev(float $equalStdDev): ResultProject
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
     * @return ResultProject
     */
    public function setWeightedDevRatio(float $weightedDevRatio): ResultProject
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
     * @return ResultProject
     */
    public function setEqualDevRatio(float $equalDevRatio): ResultProject
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
     * @return ResultProject
     */
    public function setWeightedStdDevMax(float $weightedStdDevMax): ResultProject
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
     * @return ResultProject
     */
    public function setEqualStdDevMax(float $equalStdDevMax): ResultProject
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
     * @return ResultProject
     */
    public function setWeightedInertia(float $weightedInertia): ResultProject
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
     * @return ResultProject
     */
    public function setEqualInertia(float $equalInertia): ResultProject
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
     * @return ResultProject
     */
    public function setWeightedInertiaMax(float $weightedInertiaMax): ResultProject
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
     * @return ResultProject
     */
    public function setEqualInertiaMax(float $equalInertiaMax): ResultProject
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
     * @return ResultProject
     */
    public function setWeightedDistanceRatio(float $weightedDistanceRatio): ResultProject
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
     * @return ResultProject
     */
    public function setEqualDistanceRatio(float $equalDistanceRatio): ResultProject
    {
        $this->equalDistanceRatio = $equalDistanceRatio;
        return $this;
    }


    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

        return $this;
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

}
