<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RankingTeamHistoryRepository;
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
    private $id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $rth_dtype;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $rth_wtype;

    /**
     * @ORM\Column(type="integer")
     */
    private $rth_abs_result;

    /**
     * @ORM\Column(type="float")
     */
    private $rth_rel_result;

    /**
     * @ORM\Column(type="integer")
     */
    private $rth_period;

    /**
     * @ORM\Column(type="integer")
     */
    private $rth_freq;

    /**
     * @ORM\Column(type="float")
     */
    private $rth_value;

    /**
     * @ORM\Column(type="integer")
     */
    private $rth_series_pop;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rth_creatdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $rth_inserted;

    /**
     *@ManyToOne(targetEntity="Activity")
     *@JoinColumn(name="rth_activity", referencedColumnName="act_id", nullable=false)
     */
    protected $activity;

    /**
     *@ManyToOne(targetEntity="Stage")
     *@JoinColumn(name="rth_stage", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;

    /**
     *@ManyToOne(targetEntity="Criterion")
     *@JoinColumn(name="rth_criterion", referencedColumnName="crt_id", nullable=true)
     */
    protected $criterion;

    /**
     *@ManyToOne(targetEntity="Team")
     *@JoinColumn(name="team_tea_id", referencedColumnName="tea_id", nullable=false)
     */
    protected $team;
    /**
     *@ManyToOne(targetEntity="Organization")
     *@JoinColumn(name="rth_organization", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    public function __construct($id=0, $dType=null, $wType=null, $organization=null, $absResult=null, $relResult=null, $period=null, $frequency=null, $value= null, $seriesPopulation=null,$createdBy=null, $inserted=null)
    {
        parent::__construct($id,$createdBy, new \DateTime);
        $this->dType = $dType;
        $this->rth_wtype = $wType;
        $this->rth_abs_result = $absResult;
        $this->rth_rel_result = $relResult;
        $this->rth_period = $period;
        $this->rth_freq = $frequency;
        $this->rth_series_pop = $seriesPopulation;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRthDtype(): ?string
    {
        return $this->rth_dtype;
    }

    public function setRthDtype(string $rth_dtype): self
    {
        $this->rth_dtype = $rth_dtype;

        return $this;
    }

    public function getRthWtype(): ?string
    {
        return $this->rth_wtype;
    }

    public function setRthWtype(string $rth_wtype): self
    {
        $this->rth_wtype = $rth_wtype;

        return $this;
    }

    public function getRthAbsResult(): ?int
    {
        return $this->rth_abs_result;
    }

    public function setRthAbsResult(int $rth_abs_result): self
    {
        $this->rth_abs_result = $rth_abs_result;

        return $this;
    }

    public function getRthRelResult(): ?float
    {
        return $this->rth_rel_result;
    }

    public function setRthRelResult(float $rth_rel_result): self
    {
        $this->rth_rel_result = $rth_rel_result;

        return $this;
    }

    public function getRthPeriod(): ?int
    {
        return $this->rth_period;
    }

    public function setRthPeriod(int $rth_period): self
    {
        $this->rth_period = $rth_period;

        return $this;
    }

    public function getRthFreq(): ?int
    {
        return $this->rth_freq;
    }

    public function setRthFreq(int $rth_freq): self
    {
        $this->rth_freq = $rth_freq;

        return $this;
    }

    public function getRthValue(): ?float
    {
        return $this->rth_value;
    }

    public function setRthValue(float $rth_value): self
    {
        $this->rth_value = $rth_value;

        return $this;
    }

    public function getRthSeriesPop(): ?int
    {
        return $this->rth_series_pop;
    }

    public function setRthSeriesPop(int $rth_series_pop): self
    {
        $this->rth_series_pop = $rth_series_pop;

        return $this;
    }

    public function getRthCreatdBy(): ?int
    {
        return $this->rth_creatdBy;
    }

    public function setRthCreatdBy(?int $rth_creatdBy): self
    {
        $this->rth_creatdBy = $rth_creatdBy;

        return $this;
    }

    public function getRthInserted(): ?\DateTimeInterface
    {
        return $this->rth_inserted;
    }

    public function setRthInserted(\DateTimeInterface $rth_inserted): self
    {
        $this->rth_inserted = $rth_inserted;

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
