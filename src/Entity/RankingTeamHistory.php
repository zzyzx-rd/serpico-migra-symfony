<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RankingTeamHistoryRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=RankingTeamHistoryRepository::class)
 */
class RankingTeamHistory extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="rth_id", type="integer", nullable=false, length=10)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="rth_dtype", type="string", length=1)
     */
    public $dType;

    /**
     * @ORM\Column(name="rth_wtype", type="string", length=1)
     */
    public $wType;

    /**
     * @ORM\Column(name="rth_abs_result", type="integer", nullable=true)
     */
    public $absResult;

    /**
     * @ORM\Column(name="rth_rel_result", type="float", nullable=true)
     */
    public $relResult;

    /**
     * @ORM\Column(name="rth_period", type="integer", nullable=true)
     */
    public $period;

    /**
     * @ORM\Column(name="rth_freq", type="integer", nullable=true)
     */
    public $frequency;

    /**
     * @ORM\Column(name="rth_value", type="float", nullable=true)
     */
    public $value;

    /**
     * @ORM\Column(name="rth_series_pop", type="integer", nullable=true)
     */
    public $seriesPopulation;

    /**
     * @ORM\Column(name="rth_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="rth_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     *@ManyToOne(targetEntity="Activity", inversedBy="historicalRankingTeams")
     *@JoinColumn(name="rth_activity", referencedColumnName="act_id", nullable=true)
     */
    protected $activity;

    /**
     *@ManyToOne(targetEntity="Stage", inversedBy="historicalRankingTeams")
     *@JoinColumn(name="rth_stage", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;

    /**
     *@ManyToOne(targetEntity="Criterion", inversedBy="historicalRankingTeams")
     *@JoinColumn(name="rth_criterion", referencedColumnName="crt_id", nullable=true)
     */
    protected $criterion;

    /**
     *@ManyToOne(targetEntity="Team")
     *@JoinColumn(name="team_tea_id", referencedColumnName="tea_id", nullable=true)
     */
    protected $team;
    /**
     *@ManyToOne(targetEntity="Organization")
     *@JoinColumn(name="rth_organization", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    public function __construct(
        $id = 0,
        $dType = null,
        $wType = null,
        $absResult = null,
        $relResult = null,
        $period = null,
        $frequency = null,
        $seriesPopulation = null,
        $createdBy = null)
    {
        parent::__construct($id, $createdBy, new DateTime);
        $this->dType = $dType;
        $this->wType = $wType;
        $this->absResult = $absResult;
        $this->relResult = $relResult;
        $this->period = $period;
        $this->frequency = $frequency;
        $this->seriesPopulation = $seriesPopulation;
    }

    public function getDtype(): ?string
    {
        return $this->dType;
    }

    public function setDtype(string $dtype): self
    {
        $this->dType = $dtype;

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
    public function setOrganization($organization): void
    {
        $this->organization = $organization;
    }

}
