<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ResultTeamRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ResultTeamRepository::class)
 */
class ResultTeam extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="rst_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @Column(name="rst_type", type="integer", nullable=true)
     * @var int
     */
    protected $type;
    /**
     * @Column(name="rst_war", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $weightedAbsoluteResult;
    /**
     * @Column(name="rst_ear", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $equalAbsoluteResult;
    /**
     * @Column(name="rst_wrr", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $weightedRelativeResult;
    /**
     * @Column(name="rst_err", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $equalRelativeResult;
    /**
     * @Column(name="rst_wsd", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $weightedStdDev;
    /**
     * @Column(name="rst_esd", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $equalStdDev;
    /**
     * @Column(name="rst_wdr", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $weightedDevRatio;
    /**
     * @Column(name="rst_edr", length= 10, type="float", nullable=true)
     * @var float
     */
    protected $equalDevRatio;
    /**
     * @Column(name="rst_wsd_max", length= 10, type="float")
     * @var float
     */
    protected $weightedStdDevMax;
    /**
     * @Column(name="rst_esd_max", length= 10, type="float")
     * @var float
     */
    protected $equalStdDevMax;
    /**
     * @Column(name="rst_win", length= 10, type="float")
     * @var float
     */
    protected $weightedInertia;
    /**
     * @Column(name="rst_ein", length= 10, type="float")
     * @var float
     */
    protected $equalInertia;
    /**
     * @Column(name="rst_win_max", length= 10, type="float")
     * @var float
     */
    protected $weightedInertiaMax;
    /**
     * @Column(name="rst_ein_max", length= 10, type="float")
     * @var float
     */
    protected $equalInertiaMax;
    /**
     * @Column(name="rst_wdr_gen", length= 10, type="float")
     * @var float
     */
    protected $weightedDistanceRatio;
    /**
     * @Column(name="rst_edr_gen", length= 10, type="float")
     * @var float
     */
    protected $equalDistanceRatio;
    /**
     * @Column(name="rst_createdBy", type="integer")
     * @var int
     */
    protected ?int $createdBy;
    /**
     * @Column(name="rst_inserted", type="datetime")
     * @var DateTime
     */
    protected DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="Activity", inversedBy="resultTeams")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id")
     */
    protected $activity;

    /**
     * @ManyToOne(targetEntity="Stage", inversedBy="resultTeams")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;

    /**
     * @ManyToOne(targetEntity="Criterion", inversedBy="resultTeams")
     * @JoinColumn(name="criterion_crt_id", referencedColumnName="crt_id", nullable=true)
     */
    protected $criterion;

    /**
     * @ManyToOne(targetEntity="Team")
     * @JoinColumn(name="team_tea_id", referencedColumnName="tea_id", nullable=true)
     */
    protected $team;

    /**
     * ResultTeam constructor.
     * @param ?int$id
     * @param $weightedAbsoluteResult
     * @param $equalAbsoluteResult
     * @param $weightedRelativeResult
     * @param $equalRelativeResult
     * @param $weightedStdDev
     * @param $equalStdDev
     * @param $weightedDevRatio
     * @param $equalDevRatio
     * @param $weightedStdDevMax
     * @param $equalStdDevMax
     * @param $weightedInertia
     * @param $equalInertia
     * @param $weightedInertiaMax
     * @param $equalInertiaMax
     * @param $weightedDistanceRatio
     * @param $equalDistanceRatio
     * @param $rst_createdBy
     * @param $activity
     * @param $stage
     * @param $criterion
     * @param $team
     */
    public function __construct(
        $id = 0,
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
        $rst_createdBy = null,
        $activity = null,
        $stage = null,
        $criterion = null,
        $team = null)
    {
        parent::__construct($id, $rst_createdBy, new DateTime());
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
        $this->team = $team;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return ResultTeam
     */
    public function setType(int $type): ResultTeam
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
     * @return ResultTeam
     */
    public function setWeightedAbsoluteResult(float $weightedAbsoluteResult): ResultTeam
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
     * @return ResultTeam
     */
    public function setEqualAbsoluteResult(float $equalAbsoluteResult): ResultTeam
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
     * @return ResultTeam
     */
    public function setWeightedRelativeResult(float $weightedRelativeResult): ResultTeam
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
     * @return ResultTeam
     */
    public function setEqualRelativeResult(float $equalRelativeResult): ResultTeam
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
     * @return ResultTeam
     */
    public function setWeightedStdDev(float $weightedStdDev): ResultTeam
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
     * @return ResultTeam
     */
    public function setEqualStdDev(float $equalStdDev): ResultTeam
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
     * @return ResultTeam
     */
    public function setWeightedDevRatio(float $weightedDevRatio): ResultTeam
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
     * @return ResultTeam
     */
    public function setEqualDevRatio(float $equalDevRatio): ResultTeam
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
     * @return ResultTeam
     */
    public function setWeightedStdDevMax(float $weightedStdDevMax): ResultTeam
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
     * @return ResultTeam
     */
    public function setEqualStdDevMax(float $equalStdDevMax): ResultTeam
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
     * @return ResultTeam
     */
    public function setWeightedInertia(float $weightedInertia): ResultTeam
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
     * @return ResultTeam
     */
    public function setEqualInertia(float $equalInertia): ResultTeam
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
     * @return ResultTeam
     */
    public function setWeightedInertiaMax(float $weightedInertiaMax): ResultTeam
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
     * @return ResultTeam
     */
    public function setEqualInertiaMax(float $equalInertiaMax): ResultTeam
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
     * @return ResultTeam
     */
    public function setWeightedDistanceRatio(float $weightedDistanceRatio): ResultTeam
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
     * @return ResultTeam
     */
    public function setEqualDistanceRatio(float $equalDistanceRatio): ResultTeam
    {
        $this->equalDistanceRatio = $equalDistanceRatio;
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

}
