<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerExperienceRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WorkerExperienceRepository::class)
 */
class WorkerExperience
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="wex_id", type="integer", nullable=false)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $wex_active;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wex_position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wex_location;

    /**
     * @ORM\Column(type="datetime")
     */
    private $wex_startdate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $wex_enddate;

    /**
     * @ORM\Column(type="integer")
     */
    private $wex_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $wex_inserted;

    /**
     * @ManyToOne(targetEntity="WorkerIndividual")
     * @JoinColumn(name="worker_individual_wid", referencedColumnName="win_id",nullable=false)
     */
    protected $individual;

    /**
     * @ManyToOne(targetEntity="WorkerFirm")
     * @JoinColumn(name="worker_firm_wfi", referencedColumnName="wfi_id",nullable=false)
     */
    protected $firm;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWexActive(): ?bool
    {
        return $this->wex_active;
    }

    public function setWexActive(bool $wex_active): self
    {
        $this->wex_active = $wex_active;

        return $this;
    }

    public function getWexPosition(): ?string
    {
        return $this->wex_position;
    }

    public function setWexPosition(string $wex_position): self
    {
        $this->wex_position = $wex_position;

        return $this;
    }

    public function getWexLocation(): ?string
    {
        return $this->wex_location;
    }

    public function setWexLocation(string $wex_location): self
    {
        $this->wex_location = $wex_location;

        return $this;
    }

    public function getWexStartdate(): ?\DateTimeInterface
    {
        return $this->wex_startdate;
    }

    public function setWexStartdate(\DateTimeInterface $wex_startdate): self
    {
        $this->wex_startdate = $wex_startdate;

        return $this;
    }

    public function getWexEnddate(): ?\DateTimeInterface
    {
        return $this->wex_enddate;
    }

    public function setWexEnddate(\DateTimeInterface $wex_enddate): self
    {
        $this->wex_enddate = $wex_enddate;

        return $this;
    }

    public function getWexCreatedBy(): ?int
    {
        return $this->wex_createdBy;
    }

    public function setWexCreatedBy(int $wex_createdBy): self
    {
        $this->wex_createdBy = $wex_createdBy;

        return $this;
    }

    public function getWexInserted(): ?\DateTimeInterface
    {
        return $this->wex_inserted;
    }

    public function setWexInserted(\DateTimeInterface $wex_inserted): self
    {
        $this->wex_inserted = $wex_inserted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIndividual()
    {
        return $this->individual;
    }

    /**
     * @param mixed $individual
     */
    public function setIndividual($individual): void
    {
        $this->individual = $individual;
    }

    /**
     * @return mixed
     */
    public function getFirm()
    {
        return $this->firm;
    }

    /**
     * @param mixed $firm
     */
    public function setFirm($firm): void
    {
        $this->firm = $firm;
    }

}
