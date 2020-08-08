<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RankingTeamRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=RankingTeamRepository::class)
 */
class RankingTeam
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRktDtype(): ?string
    {
        return $this->rkt_dtype;
    }

    public function setRktDtype(string $rkt_dtype): self
    {
        $this->rkt_dtype = $rkt_dtype;

        return $this;
    }

    public function getRktWtype(): ?string
    {
        return $this->rkt_wtype;
    }

    public function setRktWtype(string $rkt_wtype): self
    {
        $this->rkt_wtype = $rkt_wtype;

        return $this;
    }

    public function getRktAbsResult(): ?int
    {
        return $this->rkt_abs_result;
    }

    public function setRktAbsResult(int $rkt_abs_result): self
    {
        $this->rkt_abs_result = $rkt_abs_result;

        return $this;
    }

    public function getRktRelResult(): ?float
    {
        return $this->rkt_rel_result;
    }

    public function setRktRelResult(float $rkt_rel_result): self
    {
        $this->rkt_rel_result = $rkt_rel_result;

        return $this;
    }

    public function getRktPeriod(): ?int
    {
        return $this->rkt_period;
    }

    public function setRktPeriod(int $rkt_period): self
    {
        $this->rkt_period = $rkt_period;

        return $this;
    }

    public function getRktFreq(): ?int
    {
        return $this->rkt_freq;
    }

    public function setRktFreq(int $rkt_freq): self
    {
        $this->rkt_freq = $rkt_freq;

        return $this;
    }

    public function getRktValue(): ?float
    {
        return $this->rkt_value;
    }

    public function setRktValue(float $rkt_value): self
    {
        $this->rkt_value = $rkt_value;

        return $this;
    }

    public function getRktSeriesPop(): ?int
    {
        return $this->rkt_series_pop;
    }

    public function setRktSeriesPop(int $rkt_series_pop): self
    {
        $this->rkt_series_pop = $rkt_series_pop;

        return $this;
    }

    public function getRktCreatedBy(): ?int
    {
        return $this->rkt_createdBy;
    }

    public function setRktCreatedBy(?int $rkt_createdBy): self
    {
        $this->rkt_createdBy = $rkt_createdBy;

        return $this;
    }

    public function getRktInserted(): ?\DateTimeInterface
    {
        return $this->rkt_inserted;
    }

    public function setRktInserted(\DateTimeInterface $rkt_inserted): self
    {
        $this->rkt_inserted = $rkt_inserted;

        return $this;
    }

    public function getRktUpdated(): ?\DateTimeInterface
    {
        return $this->rkt_updated;
    }

    public function setRktUpdated(\DateTimeInterface $rkt_updated): self
    {
        $this->rkt_updated = $rkt_updated;

        return $this;
    }
}
