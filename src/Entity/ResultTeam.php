<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ResultTeamRepository;
use Doctrine\ORM\Mapping as ORM;
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
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rst_type;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rst_war;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rst_ear;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rst_wrr;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rst_err;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rst_wsd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rst_esd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rst_wdr;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rst_edr;

    /**
     * @ORM\Column(type="float")
     */
    private $rst_wsd_max;

    /**
     * @ORM\Column(type="float")
     */
    private $rst_esd_max;

    /**
     * @ORM\Column(type="float")
     */
    private $rst_win;

    /**
     * @ORM\Column(type="float")
     */
    private $rst_ein;

    /**
     * @ORM\Column(type="float")
     */
    private $rst_win_max;

    /**
     * @ORM\Column(type="float")
     */
    private $rst_ein_max;

    /**
     * @ORM\Column(type="float")
     */
    private $rst_wdr_gen;

    /**
     * @ORM\Column(type="float")
     */
    private $rst_edr_gen;

    /**
     * @ORM\Column(type="integer")
     */
    private $rst_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $rst_inserted;

    /**
     * @ManyToOne(targetEntity="Activity")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id")
     */
    protected $activity;

    /**
     * @ManyToOne(targetEntity="Stage")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;

    /**
     * @ManyToOne(targetEntity="Criterion")
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
     * @param $id
     * @param $rst_type
     * @param $rst_war
     * @param $rst_ear
     * @param $rst_wrr
     * @param $rst_err
     * @param $rst_wsd
     * @param $rst_esd
     * @param $rst_wdr
     * @param $rst_edr
     * @param $rst_wsd_max
     * @param $rst_esd_max
     * @param $rst_win
     * @param $rst_ein
     * @param $rst_win_max
     * @param $rst_ein_max
     * @param $rst_wdr_gen
     * @param $rst_edr_gen
     * @param $rst_createdBy
     * @param $rst_inserted
     * @param $activity
     * @param $stage
     * @param $criterion
     * @param $team
     */
    public function __construct(
        $id = 0,
        $rst_type = null,
        $rst_war = null,
        $rst_ear = null,
        $rst_wrr = null,
        $rst_err = null,
        $rst_wsd = null,
        $rst_esd = null,
        $rst_wdr = null,
        $rst_edr = null,
        $rst_wsd_max = null,
        $rst_esd_max = null,
        $rst_win = null,
        $rst_ein = null,
        $rst_win_max = null,
        $rst_ein_max = null,
        $rst_wdr_gen = null,
        $rst_edr_gen = null,
        $rst_createdBy = null,
        $rst_inserted = null,
        $activity = null,
        $stage = null,
        $criterion = null,
        $team = null)
    {
        parent::__construct($id, $rst_createdBy, new DateTime());
        $this->rst_type = $rst_type;
        $this->rst_war = $rst_war;
        $this->rst_ear = $rst_ear;
        $this->rst_wrr = $rst_wrr;
        $this->rst_err = $rst_err;
        $this->rst_wsd = $rst_wsd;
        $this->rst_esd = $rst_esd;
        $this->rst_wdr = $rst_wdr;
        $this->rst_edr = $rst_edr;
        $this->rst_wsd_max = $rst_wsd_max;
        $this->rst_esd_max = $rst_esd_max;
        $this->rst_win = $rst_win;
        $this->rst_ein = $rst_ein;
        $this->rst_win_max = $rst_win_max;
        $this->rst_ein_max = $rst_ein_max;
        $this->rst_wdr_gen = $rst_wdr_gen;
        $this->rst_edr_gen = $rst_edr_gen;
        $this->rst_inserted = $rst_inserted;
        $this->activity = $activity;
        $this->stage = $stage;
        $this->criterion = $criterion;
        $this->team = $team;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRstType(): ?int
    {
        return $this->rst_type;
    }

    public function setRstType(?int $rst_type): self
    {
        $this->rst_type = $rst_type;

        return $this;
    }

    public function getRstWar(): ?float
    {
        return $this->rst_war;
    }

    public function setRstWar(?float $rst_war): self
    {
        $this->rst_war = $rst_war;

        return $this;
    }

    public function getRstEar(): ?float
    {
        return $this->rst_ear;
    }

    public function setRstEar(?float $rst_ear): self
    {
        $this->rst_ear = $rst_ear;

        return $this;
    }

    public function getRstWrr(): ?float
    {
        return $this->rst_wrr;
    }

    public function setRstWrr(?float $rst_wrr): self
    {
        $this->rst_wrr = $rst_wrr;

        return $this;
    }

    public function getRstErr(): ?float
    {
        return $this->rst_err;
    }

    public function setRstErr(?float $rst_err): self
    {
        $this->rst_err = $rst_err;

        return $this;
    }

    public function getRstWsd(): ?float
    {
        return $this->rst_wsd;
    }

    public function setRstWsd(?float $rst_wsd): self
    {
        $this->rst_wsd = $rst_wsd;

        return $this;
    }

    public function getRstEsd(): ?float
    {
        return $this->rst_esd;
    }

    public function setRstEsd(?float $rst_esd): self
    {
        $this->rst_esd = $rst_esd;

        return $this;
    }

    public function getRstWdr(): ?float
    {
        return $this->rst_wdr;
    }

    public function setRstWdr(?float $rst_wdr): self
    {
        $this->rst_wdr = $rst_wdr;

        return $this;
    }

    public function getRstEdr(): ?float
    {
        return $this->rst_edr;
    }

    public function setRstEdr(?float $rst_edr): self
    {
        $this->rst_edr = $rst_edr;

        return $this;
    }

    public function getRstWsdMax(): ?float
    {
        return $this->rst_wsd_max;
    }

    public function setRstWsdMax(float $rst_wsd_max): self
    {
        $this->rst_wsd_max = $rst_wsd_max;

        return $this;
    }

    public function getRstEsdMax(): ?float
    {
        return $this->rst_esd_max;
    }

    public function setRstEsdMax(float $rst_esd_max): self
    {
        $this->rst_esd_max = $rst_esd_max;

        return $this;
    }

    public function getRstWin(): ?float
    {
        return $this->rst_win;
    }

    public function setRstWin(float $rst_win): self
    {
        $this->rst_win = $rst_win;

        return $this;
    }

    public function getRstEin(): ?float
    {
        return $this->rst_ein;
    }

    public function setRstEin(float $rst_ein): self
    {
        $this->rst_ein = $rst_ein;

        return $this;
    }

    public function getRstWinMax(): ?float
    {
        return $this->rst_win_max;
    }

    public function setRstWinMax(float $rst_win_max): self
    {
        $this->rst_win_max = $rst_win_max;

        return $this;
    }

    public function getRstEinMax(): ?float
    {
        return $this->rst_ein_max;
    }

    public function setRstEinMax(float $rst_ein_max): self
    {
        $this->rst_ein_max = $rst_ein_max;

        return $this;
    }

    public function getRstWdrGen(): ?float
    {
        return $this->rst_wdr_gen;
    }

    public function setRstWdrGen(float $rst_wdr_gen): self
    {
        $this->rst_wdr_gen = $rst_wdr_gen;

        return $this;
    }

    public function getRstEdrGen(): ?float
    {
        return $this->rst_edr_gen;
    }

    public function setRstEdrGen(float $rst_edr_gen): self
    {
        $this->rst_edr_gen = $rst_edr_gen;

        return $this;
    }

    public function getRstCreatedBy(): ?int
    {
        return $this->rst_createdBy;
    }

    public function setRstCreatedBy(int $rst_createdBy): self
    {
        $this->rst_createdBy = $rst_createdBy;

        return $this;
    }

    public function getRstInserted(): ?\DateTimeInterface
    {
        return $this->rst_inserted;
    }

    public function setRstInserted(\DateTimeInterface $rst_inserted): self
    {
        $this->rst_inserted = $rst_inserted;

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
