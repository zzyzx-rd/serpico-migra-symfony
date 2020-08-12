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
class WorkerExperience extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="wex_id", type="integer", nullable=false)
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(type="boolean")
     */
    public $wex_active;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $wex_position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $wex_location;

    /**
     * @ORM\Column(type="datetime")
     */
    public $wex_startdate;

    /**
     * @ORM\Column(type="datetime")
     */
    public $wex_enddate;

    /**
     * @ORM\Column(type="integer")
     */
    public $wex_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $wex_inserted;

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

    /**
     * WorkerExperience constructor.
     * @param int $id
     * @param $wex_active
     * @param $wex_position
     * @param $wex_location
     * @param $wex_startdate
     * @param $wex_enddate
     * @param $wex_createdBy
     * @param $wex_inserted
     * @param $individual
     * @param $firm
     */
    public function __construct(
        int $id = 0,
        $wex_active = null,
        $wex_position = null,
        $wex_location = null,
        $wex_startdate = null,
        $wex_enddate = null,
        $wex_createdBy = null,
        $wex_inserted = null,
        $individual = null,
        $firm = null)
    {
        parent::__construct($id, $wex_createdBy, new DateTime());
        $this->wex_active = $wex_active;
        $this->wex_position = $wex_position;
        $this->wex_location = $wex_location;
        $this->wex_startdate = $wex_startdate;
        $this->wex_enddate = $wex_enddate;
        $this->wex_inserted = $wex_inserted;
        $this->individual = $individual;
        $this->firm = $firm;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActive(): ?bool
    {
        return $this->wex_active;
    }

    public function setActive(bool $wex_active): self
    {
        $this->wex_active = $wex_active;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->wex_position;
    }

    public function setPosition(string $wex_position): self
    {
        $this->wex_position = $wex_position;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->wex_location;
    }

    public function setLocation(string $wex_location): self
    {
        $this->wex_location = $wex_location;

        return $this;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->wex_startdate;
    }

    public function setStartdate(\DateTimeInterface $wex_startdate): self
    {
        $this->wex_startdate = $wex_startdate;

        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->wex_enddate;
    }

    public function setEnddate(\DateTimeInterface $wex_enddate): self
    {
        $this->wex_enddate = $wex_enddate;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->wex_inserted;
    }

    public function setInserted(\DateTimeInterface $wex_inserted): self
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
