<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=StageRepository::class)
 */
class Stage
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
     * @ORM\Column(type="integer")
     */
    private $stg_mode;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_visibility;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_access_link;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_desc;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_progress;

    /**
     * @ORM\Column(type="float")
     */
    private $stg_weight;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stg_definite_dates;

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
    private $stg_dealine_nb_days;

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
     * @ORM\Column(type="boolean")
     */
    private $stg_reopened;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_last_reopened;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stg_unstarted_notif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stg_uncompleted_notif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stg_unfinished_notif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stg_isFinalized;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_deleted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_gcompleted;

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

    public function getStgMode(): ?int
    {
        return $this->stg_mode;
    }

    public function setStgMode(int $stg_mode): self
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

    public function getStgAccessLink(): ?string
    {
        return $this->stg_access_link;
    }

    public function setStgAccessLink(string $stg_access_link): self
    {
        $this->stg_access_link = $stg_access_link;

        return $this;
    }

    public function getStgStatus(): ?int
    {
        return $this->stg_status;
    }

    public function setStgStatus(int $stg_status): self
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

    public function getStgProgress(): ?int
    {
        return $this->stg_progress;
    }

    public function setStgProgress(int $stg_progress): self
    {
        $this->stg_progress = $stg_progress;

        return $this;
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

    public function getStgDefiniteDates(): ?bool
    {
        return $this->stg_definite_dates;
    }

    public function setStgDefiniteDates(bool $stg_definite_dates): self
    {
        $this->stg_definite_dates = $stg_definite_dates;

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

    public function getStgDealineNbDays(): ?int
    {
        return $this->stg_dealine_nb_days;
    }

    public function setStgDealineNbDays(int $stg_dealine_nb_days): self
    {
        $this->stg_dealine_nb_days = $stg_dealine_nb_days;

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

    public function getStgReopened(): ?bool
    {
        return $this->stg_reopened;
    }

    public function setStgReopened(bool $stg_reopened): self
    {
        $this->stg_reopened = $stg_reopened;

        return $this;
    }

    public function getStgLastReopened(): ?\DateTimeInterface
    {
        return $this->stg_last_reopened;
    }

    public function setStgLastReopened(\DateTimeInterface $stg_last_reopened): self
    {
        $this->stg_last_reopened = $stg_last_reopened;

        return $this;
    }

    public function getStgUnstartedNotif(): ?bool
    {
        return $this->stg_unstarted_notif;
    }

    public function setStgUnstartedNotif(bool $stg_unstarted_notif): self
    {
        $this->stg_unstarted_notif = $stg_unstarted_notif;

        return $this;
    }

    public function getStgUncompletedNotif(): ?bool
    {
        return $this->stg_uncompleted_notif;
    }

    public function setStgUncompletedNotif(bool $stg_uncompleted_notif): self
    {
        $this->stg_uncompleted_notif = $stg_uncompleted_notif;

        return $this;
    }

    public function getStgUnfinishedNotif(): ?bool
    {
        return $this->stg_unfinished_notif;
    }

    public function setStgUnfinishedNotif(bool $stg_unfinished_notif): self
    {
        $this->stg_unfinished_notif = $stg_unfinished_notif;

        return $this;
    }

    public function getStgIsFinalized(): ?bool
    {
        return $this->stg_isFinalized;
    }

    public function setStgIsFinalized(bool $stg_isFinalized): self
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

    public function getStgGcompleted(): ?\DateTimeInterface
    {
        return $this->stg_gcompleted;
    }

    public function setStgGcompleted(\DateTimeInterface $stg_gcompleted): self
    {
        $this->stg_gcompleted = $stg_gcompleted;

        return $this;
    }
}
