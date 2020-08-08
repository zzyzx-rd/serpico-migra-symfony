<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RankingHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=RankingHistoryRepository::class)
 */
class RankingHistory
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
    private $rkh_wtype;

    /**
     * @ORM\Column(type="integer")
     */
    private $rkh_abs_result;

    /**
     * @ORM\Column(type="float")
     */
    private $rkh_rel_result;

    /**
     * @ORM\Column(type="integer")
     */
    private $rkh_period;

    /**
     * @ORM\Column(type="integer")
     */
    private $rkh_freq;

    /**
     * @ORM\Column(type="float")
     */
    private $rkh_value;

    /**
     * @ORM\Column(type="integer")
     */
    private $rkh_series_pop;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rkh_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $rkh_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRkhWtype(): ?string
    {
        return $this->rkh_wtype;
    }

    public function setRkhWtype(string $rkh_wtype): self
    {
        $this->rkh_wtype = $rkh_wtype;

        return $this;
    }

    public function getRkhAbsResult(): ?int
    {
        return $this->rkh_abs_result;
    }

    public function setRkhAbsResult(int $rkh_abs_result): self
    {
        $this->rkh_abs_result = $rkh_abs_result;

        return $this;
    }

    public function getRkhRelResult(): ?float
    {
        return $this->rkh_rel_result;
    }

    public function setRkhRelResult(float $rkh_rel_result): self
    {
        $this->rkh_rel_result = $rkh_rel_result;

        return $this;
    }

    public function getRkhPeriod(): ?int
    {
        return $this->rkh_period;
    }

    public function setRkhPeriod(int $rkh_period): self
    {
        $this->rkh_period = $rkh_period;

        return $this;
    }

    public function getRkhFreq(): ?int
    {
        return $this->rkh_freq;
    }

    public function setRkhFreq(int $rkh_freq): self
    {
        $this->rkh_freq = $rkh_freq;

        return $this;
    }

    public function getRkhValue(): ?float
    {
        return $this->rkh_value;
    }

    public function setRkhValue(float $rkh_value): self
    {
        $this->rkh_value = $rkh_value;

        return $this;
    }

    public function getRkhSeriesPop(): ?int
    {
        return $this->rkh_series_pop;
    }

    public function setRkhSeriesPop(int $rkh_series_pop): self
    {
        $this->rkh_series_pop = $rkh_series_pop;

        return $this;
    }

    public function getRkhCreatedBy(): ?int
    {
        return $this->rkh_createdBy;
    }

    public function setRkhCreatedBy(?int $rkh_createdBy): self
    {
        $this->rkh_createdBy = $rkh_createdBy;

        return $this;
    }

    public function getRkhInserted(): ?\DateTimeInterface
    {
        return $this->rkh_inserted;
    }

    public function setRkhInserted(\DateTimeInterface $rkh_inserted): self
    {
        $this->rkh_inserted = $rkh_inserted;

        return $this;
    }
}
