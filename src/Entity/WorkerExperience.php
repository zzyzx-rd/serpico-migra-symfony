<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerExperienceRepository;
use DateTime;
use DateTimeInterface;
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
    public ?int $id;

    /**
     * @ORM\Column(name="wex_active", type="boolean", nullable=true)
     */
    public $active;

    /**
     * @ORM\Column(name="wex_position", type="string", length=255, nullable=true)
     */
    public $position;

    /**
     * @ORM\Column(name="wex_location", type="string", length=255, nullable=true)
     */
    public $location;

    /**
     * @ORM\Column(name="wex_startdate", type="datetime", nullable=true)
     */
    public $startdate;

    /**
     * @ORM\Column(name="wex_enddate", type="datetime", nullable=true)
     */
    public $enddate;

    /**
     * @ORM\Column(name="wex_createdBy", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="wex_inserted", type="datetime", nullable=true)
     */
    public ?DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="WorkerIndividual", inversedBy="experiences")
     * @JoinColumn(name="worker_individual_wid", referencedColumnName="win_id",nullable=false)
     */
    protected $individual;

    /**
     * @ManyToOne(targetEntity="WorkerFirm", inversedBy="experiences")
     * @JoinColumn(name="worker_firm_wfi", referencedColumnName="wfi_id",nullable=true)
     */
    protected $firm;

    /**
     * WorkerExperience constructor.
     * @param ?int$id
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
      ?int $id = 0,
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
        $this->active = $wex_active;
        $this->position = $wex_position;
        $this->location = $wex_location;
        $this->startdate = $wex_startdate;
        $this->enddate = $wex_enddate;
        $this->inserted = $wex_inserted;
        $this->individual = $individual;
        $this->firm = $firm;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $wex_active): self
    {
        $this->active = $wex_active;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $wex_position): self
    {
        $this->position = $wex_position;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $wex_location): self
    {
        $this->location = $wex_location;

        return $this;
    }

    public function getStartdate(): ?DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(DateTimeInterface $wex_startdate): self
    {
        $this->startdate = $wex_startdate;

        return $this;
    }

    public function getEnddate(): ?DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(DateTimeInterface $wex_enddate): self
    {
        $this->enddate = $wex_enddate;

        return $this;
    }

    public function getInserted(): ?DateTimeInterface
    {
        return $this->inserted;
    }

    public function setInserted(DateTimeInterface $wex_inserted): self
    {
        $this->inserted = $wex_inserted;

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
