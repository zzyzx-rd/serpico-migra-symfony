<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RankingHistoryRepository;
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
     * @ORM\Column(type="string", length=1)
     */
    public $rkh_wtype;

    /**
     * @ORM\Column(type="integer")
     */
    public $rkh_abs_result;

    /**
     * @ORM\Column(type="float")
     */
    public $rkh_rel_result;

    /**
     * @ORM\Column(type="integer")
     */
    public $rkh_period;

    /**
     * @ORM\Column(type="integer")
     */
    public $rkh_freq;

    /**
     * @ORM\Column(type="float")
     */
    public $rkh_value;

    /**
     * @ORM\Column(type="integer")
     */
    public $rkh_series_pop;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $rkh_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $rkh_inserted;

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
        $this->rkh_wtype = $rkh_wtype;
        $this->rkh_abs_result = $rkh_abs_result;
        $this->rkh_rel_result = $rkh_rel_result;
        $this->rkh_period = $rkh_period;
        $this->rkh_freq = $rkh_freq;
        $this->rkh_value = $rkh_value;
        $this->rkh_series_pop = $rkh_series_pop;
        $this->rkh_inserted = $rkh_inserted;
        $this->activity = $activity;
        $this->stage = $stage;
        $this->criterion = $criterion;
        $this->user_usr = $rhk_user_usr;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWtype(): ?string
    {
        return $this->rkh_wtype;
    }

    public function setWtype(string $rkh_wtype): self
    {
        $this->rkh_wtype = $rkh_wtype;

        return $this;
    }

    public function getAbsResult(): ?int
    {
        return $this->rkh_abs_result;
    }

    public function setAbsResult(int $rkh_abs_result): self
    {
        $this->rkh_abs_result = $rkh_abs_result;

        return $this;
    }

    public function getRelResult(): ?float
    {
        return $this->rkh_rel_result;
    }

    public function setRelResult(float $rkh_rel_result): self
    {
        $this->rkh_rel_result = $rkh_rel_result;

        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->rkh_period;
    }

    public function setPeriod(int $rkh_period): self
    {
        $this->rkh_period = $rkh_period;

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
        return $this->rkh_value;
    }

    public function setValue(float $rkh_value): self
    {
        $this->rkh_value = $rkh_value;

        return $this;
    }

    public function getSeriesPop(): ?int
    {
        return $this->rkh_series_pop;
    }

    public function setSeriesPop(int $rkh_series_pop): self
    {
        $this->rkh_series_pop = $rkh_series_pop;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->rkh_inserted;
    }

    public function setInserted(\DateTimeInterface $rkh_inserted): self
    {
        $this->rkh_inserted = $rkh_inserted;

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
