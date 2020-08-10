<?php

namespace App\Entity;

use App\Repository\ResultProjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity(repositoryClass=ResultProjectRepository::class)
 */
class ResultProject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="rsp_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rsp_type;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rsp_war;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rsp_ear;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rsp_wrr;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rsp_err;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rsp_wsd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rsp_esd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rsp_wdr;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rsp_edr;

    /**
     * @ORM\Column(type="float")
     */
    private $rsp_wsd_max;

    /**
     * @ORM\Column(type="float")
     */
    private $rsp_esd_max;

    /**
     * @ORM\Column(type="float")
     */
    private $rsp_win;

    /**
     * @ORM\Column(type="float")
     */
    private $rsp_ein;

    /**
     * @ORM\Column(type="float")
     */
    private $rsp_win_max;

    /**
     * @ORM\Column(type="float")
     */
    private $rsp_ein_max;

    /**
     * @ORM\Column(type="float")
     */
    private $rsp_wdr_gen;

    /**
     * @ORM\Column(type="float")
     */
    private $rsp_edr_gen;

    /**
     * @ORM\Column(type="integer")
     */
    private $rsp_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $rsp_inserted;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRspType(): ?int
    {
        return $this->rsp_type;
    }

    public function setRspType(?int $rsp_type): self
    {
        $this->rsp_type = $rsp_type;

        return $this;
    }

    public function getRspWar(): ?float
    {
        return $this->rsp_war;
    }

    public function setRspWar(?float $rsp_war): self
    {
        $this->rsp_war = $rsp_war;

        return $this;
    }

    public function getRspEar(): ?float
    {
        return $this->rsp_ear;
    }

    public function setRspEar(?float $rsp_ear): self
    {
        $this->rsp_ear = $rsp_ear;

        return $this;
    }

    public function getRspWrr(): ?float
    {
        return $this->rsp_wrr;
    }

    public function setRspWrr(?float $rsp_wrr): self
    {
        $this->rsp_wrr = $rsp_wrr;

        return $this;
    }

    public function getRspErr(): ?float
    {
        return $this->rsp_err;
    }

    public function setRspErr(?float $rsp_err): self
    {
        $this->rsp_err = $rsp_err;

        return $this;
    }

    public function getRspWsd(): ?float
    {
        return $this->rsp_wsd;
    }

    public function setRspWsd(?float $rsp_wsd): self
    {
        $this->rsp_wsd = $rsp_wsd;

        return $this;
    }

    public function getRspEsd(): ?float
    {
        return $this->rsp_esd;
    }

    public function setRspEsd(?float $rsp_esd): self
    {
        $this->rsp_esd = $rsp_esd;

        return $this;
    }

    public function getRspWdr(): ?float
    {
        return $this->rsp_wdr;
    }

    public function setRspWdr(?float $rsp_wdr): self
    {
        $this->rsp_wdr = $rsp_wdr;

        return $this;
    }

    public function getRspEdr(): ?float
    {
        return $this->rsp_edr;
    }

    public function setRspEdr(?float $rsp_edr): self
    {
        $this->rsp_edr = $rsp_edr;

        return $this;
    }

    public function getRspWsdMax(): ?float
    {
        return $this->rsp_wsd_max;
    }

    public function setRspWsdMax(float $rsp_wsd_max): self
    {
        $this->rsp_wsd_max = $rsp_wsd_max;

        return $this;
    }

    public function getRspEsdMax(): ?float
    {
        return $this->rsp_esd_max;
    }

    public function setRspEsdMax(float $rsp_esd_max): self
    {
        $this->rsp_esd_max = $rsp_esd_max;

        return $this;
    }

    public function getRspWin(): ?float
    {
        return $this->rsp_win;
    }

    public function setRspWin(float $rsp_win): self
    {
        $this->rsp_win = $rsp_win;

        return $this;
    }

    public function getRspEin(): ?float
    {
        return $this->rsp_ein;
    }

    public function setRspEin(float $rsp_ein): self
    {
        $this->rsp_ein = $rsp_ein;

        return $this;
    }

    public function getRspWinMax(): ?float
    {
        return $this->rsp_win_max;
    }

    public function setRspWinMax(float $rsp_win_max): self
    {
        $this->rsp_win_max = $rsp_win_max;

        return $this;
    }

    public function getRspEinMax(): ?float
    {
        return $this->rsp_ein_max;
    }

    public function setRspEinMax(float $rsp_ein_max): self
    {
        $this->rsp_ein_max = $rsp_ein_max;

        return $this;
    }

    public function getRspWdrGen(): ?float
    {
        return $this->rsp_wdr_gen;
    }

    public function setRspWdrGen(float $rsp_wdr_gen): self
    {
        $this->rsp_wdr_gen = $rsp_wdr_gen;

        return $this;
    }

    public function getRspEdrGen(): ?float
    {
        return $this->rsp_edr_gen;
    }

    public function setRspEdrGen(float $rsp_edr_gen): self
    {
        $this->rsp_edr_gen = $rsp_edr_gen;

        return $this;
    }

    public function getRspCreatedBy(): ?int
    {
        return $this->rsp_createdBy;
    }

    public function setRspCreatedBy(int $rsp_createdBy): self
    {
        $this->rsp_createdBy = $rsp_createdBy;

        return $this;
    }

    public function getRspInserted(): ?\DateTimeInterface
    {
        return $this->rsp_inserted;
    }

    public function setRspInserted(\DateTimeInterface $rsp_inserted): self
    {
        $this->rsp_inserted = $rsp_inserted;

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
