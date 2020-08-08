<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TemplateStageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TemplateStageRepository::class)
 */
class TemplateStage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $stg_weight;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_period;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_frequency;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_startdate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_enddate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_gstartdate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_genddate;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_deadline_nbDays;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stg_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $stg_inserted;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stg_mode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStgWeight(): ?float
    {
        return $this->stg_weight;
    }

    public function setStgWeight(float $stg_weight): self
    {
        $this->stg_weight = $stg_weight;

        return $this;
    }

    public function getStgPeriod(): ?int
    {
        return $this->stg_period;
    }

    public function setStgPeriod(int $stg_period): self
    {
        $this->stg_period = $stg_period;

        return $this;
    }

    public function getStgFrequency(): ?string
    {
        return $this->stg_frequency;
    }

    public function setStgFrequency(string $stg_frequency): self
    {
        $this->stg_frequency = $stg_frequency;

        return $this;
    }

    public function getStgStartdate(): ?\DateTimeInterface
    {
        return $this->stg_startdate;
    }

    public function setStgStartdate(\DateTimeInterface $stg_startdate): self
    {
        $this->stg_startdate = $stg_startdate;

        return $this;
    }

    public function getStgEnddate(): ?\DateTimeInterface
    {
        return $this->stg_enddate;
    }

    public function setStgEnddate(\DateTimeInterface $stg_enddate): self
    {
        $this->stg_enddate = $stg_enddate;

        return $this;
    }

    public function getStgGstartdate(): ?\DateTimeInterface
    {
        return $this->stg_gstartdate;
    }

    public function setStgGstartdate(\DateTimeInterface $stg_gstartdate): self
    {
        $this->stg_gstartdate = $stg_gstartdate;

        return $this;
    }

    public function getStgGenddate(): ?\DateTimeInterface
    {
        return $this->stg_genddate;
    }

    public function setStgGenddate(\DateTimeInterface $stg_genddate): self
    {
        $this->stg_genddate = $stg_genddate;

        return $this;
    }

    public function getStgDeadlineNbDays(): ?int
    {
        return $this->stg_deadline_nbDays;
    }

    public function setStgDeadlineNbDays(int $stg_deadline_nbDays): self
    {
        $this->stg_deadline_nbDays = $stg_deadline_nbDays;

        return $this;
    }

    public function getStgCreatedBy(): ?string
    {
        return $this->stg_createdBy;
    }

    public function setStgCreatedBy(?string $stg_createdBy): self
    {
        $this->stg_createdBy = $stg_createdBy;

        return $this;
    }

    public function getStgInserted(): ?\DateTimeInterface
    {
        return $this->stg_inserted;
    }

    public function setStgInserted(?\DateTimeInterface $stg_inserted): self
    {
        $this->stg_inserted = $stg_inserted;

        return $this;
    }

    public function getStgMode(): ?int
    {
        return $this->stg_mode;
    }

    public function setStgMode(?int $stg_mode): self
    {
        $this->stg_mode = $stg_mode;

        return $this;
    }
}
