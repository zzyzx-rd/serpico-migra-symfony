<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerFirmLocationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WorkerFirmLocationRepository::class)
 */
class WorkerFirmLocation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="wfl_id", type="integer", nullable=false)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wfl_hq_city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wfl_hq_state;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wfl_hq_country;

    /**
     * @ORM\Column(type="integer")
     */
    private $wfl_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $wfl_inserted;

    /**
     * WorkerFirmLocation constructor.
     * @param int $id
     * @param $wfl_hq_city
     * @param $wfl_hq_state
     * @param $wfl_hq_country
     * @param $wfl_createdBy
     * @param $wfl_inserted
     */
    public function __construct(
        int $id = 0,
        $wfl_createdBy = null,
        $wfl_inserted = null,
        $wfl_hq_city = null,
        $wfl_hq_state = null,
        $wfl_hq_country = null)
    {
        $this->wfl_hq_city = $wfl_hq_city;
        $this->wfl_hq_state = $wfl_hq_state;
        $this->wfl_hq_country = $wfl_hq_country;
        $this->wfl_inserted = $wfl_inserted;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWflHqCity(): ?string
    {
        return $this->wfl_hq_city;
    }

    public function setWflHqCity(string $wfl_hq_city): self
    {
        $this->wfl_hq_city = $wfl_hq_city;

        return $this;
    }

    public function getWflHqState(): ?string
    {
        return $this->wfl_hq_state;
    }

    public function setWflHqState(string $wfl_hq_state): self
    {
        $this->wfl_hq_state = $wfl_hq_state;

        return $this;
    }

    public function getWflHqCountry(): ?string
    {
        return $this->wfl_hq_country;
    }

    public function setWflHqCountry(string $wfl_hq_country): self
    {
        $this->wfl_hq_country = $wfl_hq_country;

        return $this;
    }

    public function getWflCreatedBy(): ?int
    {
        return $this->wfl_createdBy;
    }

    public function setWflCreatedBy(int $wfl_createdBy): self
    {
        $this->wfl_createdBy = $wfl_createdBy;

        return $this;
    }

    public function getWflInserted(): ?\DateTimeInterface
    {
        return $this->wfl_inserted;
    }

    public function setWflInserted(\DateTimeInterface $wfl_inserted): self
    {
        $this->wfl_inserted = $wfl_inserted;

        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }
}
