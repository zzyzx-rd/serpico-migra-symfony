<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RankingRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=RankingRepository::class)
 */
class Ranking extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="rnk_id", type="integer", nullable=false, length=10)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="rnk_dtype", type="string", length=1)
     */
    public $dType;

    /**
     * @ORM\Column(name="rnk_wtype", type="string", length=1)
     */
    public $wType;

    /**
     * @ORM\Column(name="rnk_abs_result", type="integer", nullable=true)
     */
    public $absResult;

    /**
     * @ORM\Column(name="rnk_rel_result", type="float", nullable=true)
     */
    public $relResult;

    /**
     * @ORM\Column(name="rnk_period", type="integer", nullable=true)
     */
    public $period;

    /**
     * @ORM\Column(name="rnk_freq", type="integer", nullable=true)
     */
    public $frequency;

    /**
     * @ORM\Column(name="rnk_value", type="float", nullable=true)
     */
    public $value;

    /**
     * @ORM\Column(name="rnk_series_pop", type="integer", nullable=true)
     */
    public $seriesPopulation;

    /**
     * @ORM\Column(name="rnk_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="rnk_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="rnk_updated", type="datetime", nullable=true)
     */
    public $updated;

    /**
     *@ManyToOne(targetEntity="Activity", inversedBy="rankings")
     *@JoinColumn(name="rnk_activity", referencedColumnName="act_id", nullable=false)
     */
    protected $activity;

    /**
     *@ManyToOne(targetEntity="Stage", inversedBy="rankings")
     *@JoinColumn(name="rnk_stage", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;

    /**
     *@ManyToOne(targetEntity="Criterion", inversedBy="rankings")
     *@JoinColumn(name="rnk_criterion", referencedColumnName="crt_id", nullable=true)
     */
    protected $criterion;

    /**
     *@ManyToOne(targetEntity="Organization")
     *@JoinColumn(name="rnk_organization", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @JoinColumn(name="rnk_user_usr_id", referencedColumnName="usr_id")
     */
    public $user;

    /**
     * Ranking constructor.
     * @param ?int$id
     * @param $rnk_dtype
     * @param $rnk_wtype
     * @param $rnk_abs_result
     * @param $rnk_rel_result
     * @param $rnk_period
     * @param $rnk_freq
     * @param $rnk_value
     * @param $rnk_series_pop
     * @param $rnk_createdBy
     * @param $rnk_inserted
     * @param $rnk_updated
     * @param $activity
     * @param $stage
     * @param $criterion
     * @param $organization
     * @param $rnk_user
     */
    public function __construct(
      ?int $id = 0,
        $rnk_dtype = null,
        $rnk_wtype = null,
        $rnk_user = null,
        $organization = null,
        $rnk_abs_result = null,
        $rnk_rel_result = null,
        $rnk_period = null,
        $rnk_freq = null,
        $rnk_value = null,
        $rnk_series_pop = null,
        $rnk_createdBy = null,
        $rnk_inserted = null,
        $rnk_updated = null,
        $activity = null,
        $stage = null,
        $criterion = null)
    {
        parent::__construct($id, $rnk_createdBy, new DateTime());
        $this->dType = $rnk_dtype;
        $this->wType = $rnk_wtype;
        $this->absResult = $rnk_abs_result;
        $this->relResult = $rnk_rel_result;
        $this->period = $rnk_period;
        $this->frequency = $rnk_freq;
        $this->value = $rnk_value;
        $this->seriesPopulation = $rnk_series_pop;
        $this->updated = $rnk_updated;
        $this->activity = $activity;
        $this->stage = $stage;
        $this->criterion = $criterion;
        $this->organization = $organization;
        $this->user = $rnk_user;
    }


    public function getDType(): ?string
    {
        return $this->dType;
    }

    public function setDType(string $dType): self
    {
        $this->dType = $dType;

        return $this;
    }

    public function getWType(): ?string
    {
        return $this->wType;
    }

    public function setWType(string $wType): self
    {
        $this->wType = $wType;

        return $this;
    }

    public function getAbsResult(): ?int
    {
        return $this->absResult;
    }

    public function setAbsResult(int $absResult): self
    {
        $this->absResult = $absResult;

        return $this;
    }

    public function getRelResult(): ?float
    {
        return $this->relResult;
    }

    public function setRelResult(float $relResult): self
    {
        $this->relResult = $relResult;

        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(int $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getFrequency(): ?int
    {
        return $this->frequency;
    }

    public function setFrequency(int $frequency): self
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getSeriesPopulation(): ?int
    {
        return $this->seriesPopulation;
    }

    public function setSeriesPopulation(int $seriesPopulation): self
    {
        $this->seriesPopulation = $seriesPopulation;

        return $this;
    }


    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

        return $this;
    }

    public function getUpdated(): ?DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(DateTimeInterface $updated): self
    {
        $this->updated = $updated;

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
    public function setActivity($activity)
    {
        $this->activity = $activity; return $this;
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
    public function setStage($stage)
    {
        $this->stage = $stage; return $this;
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
    public function setCriterion($criterion)
    {
        $this->criterion = $criterion; return $this;
    }

    /**
     * @return mixed
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param mixed $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization; return $this;
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

}
