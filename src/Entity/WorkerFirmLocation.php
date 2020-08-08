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
     * @ORM\Column(type="integer")
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
}
