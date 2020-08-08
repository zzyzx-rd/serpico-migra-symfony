<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RankingTeamHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=RankingTeamHistoryRepository::class)
 */
class RankingTeamHistory
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
}
