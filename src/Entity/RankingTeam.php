<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RankingTeamRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=RankingTeamRepository::class)
 */
class RankingTeam extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="rkt_id", type="integer", nullable=false, length=10)
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(name="rkt_dtype", type="string", length=1)
     */
    public $dType;

    /**
     * @ORM\Column(name="rkt_wtype", type="string", length=1)
     */
    public $wType;

    /**
     * @ORM\Column(name="rkt_abs_result", type="integer", nullable=true)
     */
    public $absResult;

    /**
     * @ORM\Column(name="rkt_rel_result", type="float", nullable=true)
     */
    public $relResult;

    /**
     * @ORM\Column(name="rkt_period", type="integer", nullable=true)
     */
    public $period;

    /**
     * @ORM\Column(name="rkt_freq", type="integer", nullable=true)
     */
    public $frequency;

    /**
     * @ORM\Column(name="rkt_value", type="float", nullable=true)
     */
    public $value;

    /**
     * @ORM\Column(name="rkt_series_pop", type="integer", nullable=true)
     */
    public $seriesPopulation;

    /**
     * @ORM\Column(name="rkt_created_by", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="rkt_inserted", type="datetime", nullable=true)
     */
    public $inserted;

    /**
     * @ORM\Column(name="rkt_updated", type="datetime", nullable=true)
     */
    public $updated;

    /**
     *@ManyToOne(targetEntity="Activity", inversedBy="rankingTeams")
     *@JoinColumn(name="rkt_activity", referencedColumnName="act_id", nullable=false)
     */
    protected $activity;

    /**
     *@ManyToOne(targetEntity="Stage", inversedBy="rankingTeams")
     *@JoinColumn(name="rkt_stage", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;

    /**
     *@ManyToOne(targetEntity="Criterion", inversedBy="rankingTeams")
     *@JoinColumn(name="rkt_criterion", referencedColumnName="crt_id", nullable=true)
     */
    protected $criterion;

    /**
     *@ManyToOne(targetEntity="Team")
     *@JoinColumn(name="team_tea_id", referencedColumnName="tea_id", nullable=false)
     */
    protected $team;

    /**
     *@ManyToOne(targetEntity="Organization")
     *@JoinColumn(name="rkt_organization", referencedColumnName="org_id", nullable=false)
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
        $createdBy = null,
        $updated = null)
    {
        parent::__construct($id, $createdBy, new DateTime);
        $this->dType = $dType;
        $this->wType = $wType;
        $this->absResult = $absResult;
        $this->relResult = $relResult;
        $this->period = $period;
        $this->frequency = $frequency;
        $this->seriesPopulation = $seriesPopulation;
        $this->updated = $updated;
    }

    public function getDtype(): ?string
    {
        return $this->dType;
    }

    public function setDtype(string $rkt_dtype): self
    {
        $this->dType = $rkt_dtype;

        return $this;
    }

    public function getWtype(): ?string
    {
        return $this->wType;
    }

    public function setWtype(string $rkt_wtype): self
    {
        $this->wType = $rkt_wtype;

        return $this;
    }

    public function getAbsResult(): ?int
    {
        return $this->absResult;
    }

    public function setAbsResult(int $rkt_abs_result): self
    {
        $this->absResult = $rkt_abs_result;

        return $this;
    }

    public function getRelResult(): ?float
    {
        return $this->relResult;
    }

    public function setRelResult(float $rkt_rel_result): self
    {
        $this->relResult = $rkt_rel_result;

        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(int $rkt_period): self
    {
        $this->period = $rkt_period;

        return $this;
    }

    public function getFreq(): ?int
    {
        return $this->frequency;
    }

    public function setFreq(int $rkt_freq): self
    {
        $this->frequency = $rkt_freq;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $rkt_value): self
    {
        $this->value = $rkt_value;

        return $this;
    }

    public function getSeriesPop(): ?int
    {
        return $this->seriesPopulation;
    }

    public function setSeriesPop(int $rkt_series_pop): self
    {
        $this->seriesPopulation = $rkt_series_pop;

        return $this;
    }

    public function setInserted(DateTimeInterface $rkt_inserted): self
    {
        $this->inserted = $rkt_inserted;

        return $this;
    }

    public function getUpdated(): ?DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(DateTimeInterface $rkt_updated): self
    {
        $this->updated = $rkt_updated;

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
