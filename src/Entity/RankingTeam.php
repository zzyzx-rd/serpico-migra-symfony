<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RankingTeamRepository;
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
    private $id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $rkt_dtype;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $rkt_wtype;

    /**
     * @ORM\Column(type="integer")
     */
    private $rkt_abs_result;

    /**
     * @ORM\Column(type="float")
     */
    private $rkt_rel_result;

    /**
     * @ORM\Column(type="integer")
     */
    private $rkt_period;

    /**
     * @ORM\Column(type="integer")
     */
    private $rkt_freq;

    /**
     * @ORM\Column(type="float")
     */
    private $rkt_value;

    /**
     * @ORM\Column(type="integer")
     */
    private $rkt_series_pop;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rkt_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $rkt_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $rkt_updated;

    /**
     *@ManyToOne(targetEntity="Activity")
     *@JoinColumn(name="rkt_activity", referencedColumnName="act_id", nullable=false)
     */
    protected $activity;

    /**
     *@ManyToOne(targetEntity="Stage")
     *@JoinColumn(name="rkt_stage", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;

    /**
     *@ManyToOne(targetEntity="Criterion")
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

    public function __construct($id=0, $dType=null, $wType=null, $organization=null, $absResult=null, $relResult=null, $period=null, $frequency=null, $value= null, $seriesPopulation= null, $createdBy = null, $inserted=null, $updated=null)
    {
        parent::__construct($id, $createdBy, new \DateTime);
        $this->rkt_dtype = $dType;
        $this->rkt_wtype = $wType;
        $this->rkt_abs_result = $absResult;
        $this->rkt_rel_result = $relResult;
        $this->rkt_period = $period;
        $this->rkt_freq = $frequency;
        $this->rkt_series_pop = $seriesPopulation;
        $this->rkt_updated = $updated;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDtype(): ?string
    {
        return $this->rkt_dtype;
    }

    public function setDtype(string $rkt_dtype): self
    {
        $this->rkt_dtype = $rkt_dtype;

        return $this;
    }

    public function getWtype(): ?string
    {
        return $this->rkt_wtype;
    }

    public function setWtype(string $rkt_wtype): self
    {
        $this->rkt_wtype = $rkt_wtype;

        return $this;
    }

    public function getAbsResult(): ?int
    {
        return $this->rkt_abs_result;
    }

    public function setAbsResult(int $rkt_abs_result): self
    {
        $this->rkt_abs_result = $rkt_abs_result;

        return $this;
    }

    public function getRelResult(): ?float
    {
        return $this->rkt_rel_result;
    }

    public function setRelResult(float $rkt_rel_result): self
    {
        $this->rkt_rel_result = $rkt_rel_result;

        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->rkt_period;
    }

    public function setPeriod(int $rkt_period): self
    {
        $this->rkt_period = $rkt_period;

        return $this;
    }

    public function getFreq(): ?int
    {
        return $this->rkt_freq;
    }

    public function setFreq(int $rkt_freq): self
    {
        $this->rkt_freq = $rkt_freq;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->rkt_value;
    }

    public function setValue(float $rkt_value): self
    {
        $this->rkt_value = $rkt_value;

        return $this;
    }

    public function getSeriesPop(): ?int
    {
        return $this->rkt_series_pop;
    }

    public function setSeriesPop(int $rkt_series_pop): self
    {
        $this->rkt_series_pop = $rkt_series_pop;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->rkt_createdBy;
    }

    public function setCreatedBy(?int $rkt_createdBy): self
    {
        $this->rkt_createdBy = $rkt_createdBy;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->rkt_inserted;
    }

    public function setInserted(\DateTimeInterface $rkt_inserted): self
    {
        $this->rkt_inserted = $rkt_inserted;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->rkt_updated;
    }

    public function setUpdated(\DateTimeInterface $rkt_updated): self
    {
        $this->rkt_updated = $rkt_updated;

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
