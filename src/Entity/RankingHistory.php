<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RankingHistoryRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=RankingHistoryRepository::class)
 */
class RankingHistory extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="rkh_id", type="integer", nullable=false, length=10)
     *@var int
     */
    public $id;

    /**
     * @ORM\Column(name="rkh_wtype", type="string", length=1)
     */
    public $wtype;

    /**
     * @ORM\Column(name="rkh_abs_result", type="integer", nullable=true)
     */
    public $absResult;

    /**
     * @ORM\Column(name="rkh_rel_result", type="float", nullable=true)
     */
    public $relResult;

    /**
     * @ORM\Column(name="rkh_period", type="integer", nullable=true)
     */
    public $period;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $rkh_freq;

    /**
     * @ORM\Column(name="rkh_value", type="float", nullable=true)
     */
    public $value;

    /**
     * @ORM\Column(name="rkh_series_pop", type="integer", nullable=true)
     */
    public $seriesPopulation;

    /**
     * @ORM\Column(name="rkh_createdBy", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="rkh_inserted", type="datetime", nullable=true)
     */
    public $inserted;

    /**
     * @ManyToOne(targetEntity="Activity", inversedBy="historicalRankings")
     * @JoinColumn(name="rkh_activity", referencedColumnName="act_id", nullable=false)
     */
    protected $activity;

    /**
     * @ManyToOne(targetEntity="Stage", inversedBy="historicalRankings")
     * @JoinColumn(name="rkh_stage", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;

    /**
     * @ManyToOne(targetEntity="Criterion", inversedBy="historicalRankings")
     * @JoinColumn(name="rkh_criterion", referencedColumnName="crt_id", nullable=true)
     */
    protected $criterion;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @JoinColumn(name="rkh_user_usr_id", referencedColumnName="usr_id")
     */
    public $user_usr;

    /**
     * RankingHistory constructor.
     * @param int $id
     * @param $rkh_wtype
     * @param $rkh_abs_result
     * @param $rkh_rel_result
     * @param $rkh_period
     * @param $rkh_freq
     * @param $rkh_value
     * @param $rkh_series_pop
     * @param $rkh_createdBy
     * @param $rkh_inserted
     * @param $activity
     * @param $stage
     * @param $criterion
     * @param $rhk_user_usr
     */
    public function __construct(
        int $id = 0,
        $rkh_wtype = null,
        $rkh_abs_result = null,
        $rkh_rel_result = null,
        $rkh_period = null,
        $rkh_freq = null,
        $rkh_value = null,
        $rkh_series_pop = null,
        $rkh_createdBy = null,
        $rkh_inserted = null,
        $activity = null,
        $stage = null,
        $criterion = null,
        $rhk_user_usr = null)
    {
        parent::__construct($id, $rkh_createdBy, new DateTime());
        $this->wtype = $rkh_wtype;
        $this->absResult = $rkh_abs_result;
        $this->relResult = $rkh_rel_result;
        $this->period = $rkh_period;
        $this->rkh_freq = $rkh_freq;
        $this->value = $rkh_value;
        $this->seriesPopulation = $rkh_series_pop;
        $this->inserted = $rkh_inserted;
        $this->activity = $activity;
        $this->stage = $stage;
        $this->criterion = $criterion;
        $this->user_usr = $rhk_user_usr;
    }


    public function getWtype(): ?string
    {
        return $this->wtype;
    }

    public function setWtype(string $rkh_wtype): self
    {
        $this->wtype = $rkh_wtype;

        return $this;
    }

    public function getAbsResult(): ?int
    {
        return $this->absResult;
    }

    public function setAbsResult(int $rkh_abs_result): self
    {
        $this->absResult = $rkh_abs_result;

        return $this;
    }

    public function getRelResult(): ?float
    {
        return $this->relResult;
    }

    public function setRelResult(float $rkh_rel_result): self
    {
        $this->relResult = $rkh_rel_result;

        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(int $rkh_period): self
    {
        $this->period = $rkh_period;

        return $this;
    }

    public function getFreq(): ?int
    {
        return $this->rkh_freq;
    }

    public function setFreq(int $rkh_freq): self
    {
        $this->rkh_freq = $rkh_freq;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $rkh_value): self
    {
        $this->value = $rkh_value;

        return $this;
    }

    public function getSeriesPop(): ?int
    {
        return $this->seriesPopulation;
    }

    public function setSeriesPop(int $rkh_series_pop): self
    {
        $this->seriesPopulation = $rkh_series_pop;

        return $this;
    }

    public function setInserted(DateTimeInterface $rkh_inserted): self
    {
        $this->inserted = $rkh_inserted;

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

    public function getUserUsr(): ?User
    {
        return $this->user_usr;
    }

    public function setUserUsr(?User $user_usr): self
    {
        $this->user_usr = $user_usr;

        return $this;
    }

}
