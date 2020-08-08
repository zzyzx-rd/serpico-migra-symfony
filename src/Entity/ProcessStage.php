<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProcessStageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ProcessStageRepository::class)
 */
class ProcessStage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stg_complete;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_mode;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_visibility;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stg_definite_dates;

    /**
     * @ORM\Column(type="float")
     */
    private $stg_status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_desc;

    /**
     * @ORM\Column(type="float")
     */
    private $stg_progress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_weight;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_dperiod;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_dfrequency;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_dorigin;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_fperiod;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_ffrequency;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_forigin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_startdate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_enddated;

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
    private $stg_dealine_nbDays;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $stg_deadline_mailSent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stg_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $stg_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_isFinalized;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_deleted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_dcompleted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStgComplete(): ?bool
    {
        return $this->stg_complete;
    }

    public function setStgComplete(bool $stg_complete): self
    {
        $this->stg_complete = $stg_complete;

        return $this;
    }

    public function getStgName(): ?string
    {
        return $this->stg_name;
    }

    public function setStgName(string $stg_name): self
    {
        $this->stg_name = $stg_name;

        return $this;
    }

    public function getStgMode(): ?string
    {
        return $this->stg_mode;
    }

    public function setStgMode(string $stg_mode): self
    {
        $this->stg_mode = $stg_mode;

        return $this;
    }

    public function getStgVisibility(): ?int
    {
        return $this->stg_visibility;
    }

    public function setStgVisibility(int $stg_visibility): self
    {
        $this->stg_visibility = $stg_visibility;

        return $this;
    }

    public function getStgDefiniteDates(): ?bool
    {
        return $this->stg_definite_dates;
    }

    public function setStgDefiniteDates(bool $stg_definite_dates): self
    {
        $this->stg_definite_dates = $stg_definite_dates;

        return $this;
    }

    public function getStgStatus(): ?float
    {
        return $this->stg_status;
    }

    public function setStgStatus(float $stg_status): self
    {
        $this->stg_status = $stg_status;

        return $this;
    }

    public function getStgDesc(): ?string
    {
        return $this->stg_desc;
    }

    public function setStgDesc(string $stg_desc): self
    {
        $this->stg_desc = $stg_desc;

        return $this;
    }

    public function getStgProgress(): ?float
    {
        return $this->stg_progress;
    }

    public function setStgProgress(float $stg_progress): self
    {
        $this->stg_progress = $stg_progress;

        return $this;
    }

    public function getStgWeight(): ?string
    {
        return $this->stg_weight;
    }

    public function setStgWeight(string $stg_weight): self
    {
        $this->stg_weight = $stg_weight;

        return $this;
    }

    public function getStgDperiod(): ?int
    {
        return $this->stg_dperiod;
    }

    public function setStgDperiod(int $stg_dperiod): self
    {
        $this->stg_dperiod = $stg_dperiod;

        return $this;
    }

    public function getStgDfrequency(): ?string
    {
        return $this->stg_dfrequency;
    }

    public function setStgDfrequency(string $stg_dfrequency): self
    {
        $this->stg_dfrequency = $stg_dfrequency;

        return $this;
    }

    public function getStgDorigin(): ?int
    {
        return $this->stg_dorigin;
    }

    public function setStgDorigin(int $stg_dorigin): self
    {
        $this->stg_dorigin = $stg_dorigin;

        return $this;
    }

    public function getStgFperiod(): ?int
    {
        return $this->stg_fperiod;
    }

    public function setStgFperiod(int $stg_fperiod): self
    {
        $this->stg_fperiod = $stg_fperiod;

        return $this;
    }

    public function getStgFfrequency(): ?string
    {
        return $this->stg_ffrequency;
    }

    public function setStgFfrequency(string $stg_ffrequency): self
    {
        $this->stg_ffrequency = $stg_ffrequency;

        return $this;
    }

    public function getStgForigin(): ?int
    {
        return $this->stg_forigin;
    }

    public function setStgForigin(int $stg_forigin): self
    {
        $this->stg_forigin = $stg_forigin;

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

    public function getStgEnddated(): ?\DateTimeInterface
    {
        return $this->stg_enddated;
    }

    public function setStgEnddated(\DateTimeInterface $stg_enddated): self
    {
        $this->stg_enddated = $stg_enddated;

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

    public function getStgDealineNbDays(): ?int
    {
        return $this->stg_dealine_nbDays;
    }

    public function setStgDealineNbDays(int $stg_dealine_nbDays): self
    {
        $this->stg_dealine_nbDays = $stg_dealine_nbDays;

        return $this;
    }

    public function getStgDeadlineMailSent(): ?bool
    {
        return $this->stg_deadline_mailSent;
    }

    public function setStgDeadlineMailSent(?bool $stg_deadline_mailSent): self
    {
        $this->stg_deadline_mailSent = $stg_deadline_mailSent;

        return $this;
    }

    public function getStgCreatedBy(): ?int
    {
        return $this->stg_createdBy;
    }

    public function setStgCreatedBy(?int $stg_createdBy): self
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

    public function getStgIsFinalized(): ?\DateTimeInterface
    {
        return $this->stg_isFinalized;
    }

    public function setStgIsFinalized(\DateTimeInterface $stg_isFinalized): self
    {
        $this->stg_isFinalized = $stg_isFinalized;

        return $this;
    }

    public function getStgDeleted(): ?\DateTimeInterface
    {
        return $this->stg_deleted;
    }

    public function setStgDeleted(\DateTimeInterface $stg_deleted): self
    {
        $this->stg_deleted = $stg_deleted;

        return $this;
    }

    public function getStgDcompleted(): ?\DateTimeInterface
    {
        return $this->stg_dcompleted;
    }

    public function setStgDcompleted(\DateTimeInterface $stg_dcompleted): self
    {
        $this->stg_dcompleted = $stg_dcompleted;

        return $this;
    }
}
