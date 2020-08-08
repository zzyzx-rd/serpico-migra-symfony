<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RankingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=RankingRepository::class)
 */
class Ranking
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
    private $rnk_dtype;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $rnk_wtype;

    /**
     * @ORM\Column(type="integer")
     */
    private $rnk_abs_result;

    /**
     * @ORM\Column(type="float")
     */
    private $rnk_rel_result;

    /**
     * @ORM\Column(type="integer")
     */
    private $rnk_period;

    /**
     * @ORM\Column(type="integer")
     */
    private $rnk_freq;

    /**
     * @ORM\Column(type="float")
     */
    private $rnk_value;

    /**
     * @ORM\Column(type="integer")
     */
    private $rnk_series_pop;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rnk_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $rnk_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $rnk_updated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRnkDtype(): ?string
    {
        return $this->rnk_dtype;
    }

    public function setRnkDtype(string $rnk_dtype): self
    {
        $this->rnk_dtype = $rnk_dtype;

        return $this;
    }

    public function getRnkWtype(): ?string
    {
        return $this->rnk_wtype;
    }

    public function setRnkWtype(string $rnk_wtype): self
    {
        $this->rnk_wtype = $rnk_wtype;

        return $this;
    }

    public function getRnkAbsResult(): ?int
    {
        return $this->rnk_abs_result;
    }

    public function setRnkAbsResult(int $rnk_abs_result): self
    {
        $this->rnk_abs_result = $rnk_abs_result;

        return $this;
    }

    public function getRnkRelResult(): ?float
    {
        return $this->rnk_rel_result;
    }

    public function setRnkRelResult(float $rnk_rel_result): self
    {
        $this->rnk_rel_result = $rnk_rel_result;

        return $this;
    }

    public function getRnkPeriod(): ?int
    {
        return $this->rnk_period;
    }

    public function setRnkPeriod(int $rnk_period): self
    {
        $this->rnk_period = $rnk_period;

        return $this;
    }

    public function getRnkFreq(): ?int
    {
        return $this->rnk_freq;
    }

    public function setRnkFreq(int $rnk_freq): self
    {
        $this->rnk_freq = $rnk_freq;

        return $this;
    }

    public function getRnkValue(): ?float
    {
        return $this->rnk_value;
    }

    public function setRnkValue(float $rnk_value): self
    {
        $this->rnk_value = $rnk_value;

        return $this;
    }

    public function getRnkSeriesPop(): ?int
    {
        return $this->rnk_series_pop;
    }

    public function setRnkSeriesPop(int $rnk_series_pop): self
    {
        $this->rnk_series_pop = $rnk_series_pop;

        return $this;
    }

    public function getRnkCreatedBy(): ?int
    {
        return $this->rnk_createdBy;
    }

    public function setRnkCreatedBy(?int $rnk_createdBy): self
    {
        $this->rnk_createdBy = $rnk_createdBy;

        return $this;
    }

    public function getRnkInserted(): ?\DateTimeInterface
    {
        return $this->rnk_inserted;
    }

    public function setRnkInserted(\DateTimeInterface $rnk_inserted): self
    {
        $this->rnk_inserted = $rnk_inserted;

        return $this;
    }

    public function getRnkUpdated(): ?\DateTimeInterface
    {
        return $this->rnk_updated;
    }

    public function setRnkUpdated(\DateTimeInterface $rnk_updated): self
    {
        $this->rnk_updated = $rnk_updated;

        return $this;
    }
}
