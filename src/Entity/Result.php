<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ResultRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ResultRepository::class)
 */
class Result
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $res_type;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $res_war;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $res_ear;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $res_wrr;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $res_err;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $res_wsd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $res_esd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $res_wdf;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $res_edr;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $res_wsd_max;

    /**
     * @ORM\Column(type="float")
     */
    private $res_esd_max;

    /**
     * @ORM\Column(type="float")
     */
    private $res_win;

    /**
     * @ORM\Column(type="float")
     */
    private $res_ein;

    /**
     * @ORM\Column(type="float")
     */
    private $res_win_max;

    /**
     * @ORM\Column(type="float")
     */
    private $res_ein_max;

    /**
     * @ORM\Column(type="float")
     */
    private $res_wdr_gen;

    /**
     * @ORM\Column(type="float")
     */
    private $res_res_der_gen;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $res_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $res_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResType(): ?int
    {
        return $this->res_type;
    }

    public function setResType(?int $res_type): self
    {
        $this->res_type = $res_type;

        return $this;
    }

    public function getResWar(): ?float
    {
        return $this->res_war;
    }

    public function setResWar(?float $res_war): self
    {
        $this->res_war = $res_war;

        return $this;
    }

    public function getResEar(): ?float
    {
        return $this->res_ear;
    }

    public function setResEar(?float $res_ear): self
    {
        $this->res_ear = $res_ear;

        return $this;
    }

    public function getResWrr(): ?float
    {
        return $this->res_wrr;
    }

    public function setResWrr(?float $res_wrr): self
    {
        $this->res_wrr = $res_wrr;

        return $this;
    }

    public function getResErr(): ?float
    {
        return $this->res_err;
    }

    public function setResErr(?float $res_err): self
    {
        $this->res_err = $res_err;

        return $this;
    }

    public function getResWsd(): ?float
    {
        return $this->res_wsd;
    }

    public function setResWsd(?float $res_wsd): self
    {
        $this->res_wsd = $res_wsd;

        return $this;
    }

    public function getResEsd(): ?float
    {
        return $this->res_esd;
    }

    public function setResEsd(?float $res_esd): self
    {
        $this->res_esd = $res_esd;

        return $this;
    }

    public function getResWdf(): ?float
    {
        return $this->res_wdf;
    }

    public function setResWdf(?float $res_wdf): self
    {
        $this->res_wdf = $res_wdf;

        return $this;
    }

    public function getResEdr(): ?float
    {
        return $this->res_edr;
    }

    public function setResEdr(?float $res_edr): self
    {
        $this->res_edr = $res_edr;

        return $this;
    }

    public function getResWsdMax(): ?float
    {
        return $this->res_wsd_max;
    }

    public function setResWsdMax(?float $res_wsd_max): self
    {
        $this->res_wsd_max = $res_wsd_max;

        return $this;
    }

    public function getResEsdMax(): ?float
    {
        return $this->res_esd_max;
    }

    public function setResEsdMax(float $res_esd_max): self
    {
        $this->res_esd_max = $res_esd_max;

        return $this;
    }

    public function getResWin(): ?float
    {
        return $this->res_win;
    }

    public function setResWin(float $res_win): self
    {
        $this->res_win = $res_win;

        return $this;
    }

    public function getResEin(): ?float
    {
        return $this->res_ein;
    }

    public function setResEin(float $res_ein): self
    {
        $this->res_ein = $res_ein;

        return $this;
    }

    public function getResWinMax(): ?float
    {
        return $this->res_win_max;
    }

    public function setResWinMax(float $res_win_max): self
    {
        $this->res_win_max = $res_win_max;

        return $this;
    }

    public function getResEinMax(): ?float
    {
        return $this->res_ein_max;
    }

    public function setResEinMax(float $res_ein_max): self
    {
        $this->res_ein_max = $res_ein_max;

        return $this;
    }

    public function getResWdrGen(): ?float
    {
        return $this->res_wdr_gen;
    }

    public function setResWdrGen(float $res_wdr_gen): self
    {
        $this->res_wdr_gen = $res_wdr_gen;

        return $this;
    }

    public function getResResDerGen(): ?float
    {
        return $this->res_res_der_gen;
    }

    public function setResResDerGen(float $res_res_der_gen): self
    {
        $this->res_res_der_gen = $res_res_der_gen;

        return $this;
    }

    public function getResCreatedBy(): ?int
    {
        return $this->res_createdBy;
    }

    public function setResCreatedBy(?int $res_createdBy): self
    {
        $this->res_createdBy = $res_createdBy;

        return $this;
    }

    public function getResInserted(): ?\DateTimeInterface
    {
        return $this->res_inserted;
    }

    public function setResInserted(\DateTimeInterface $res_inserted): self
    {
        $this->res_inserted = $res_inserted;

        return $this;
    }
}
