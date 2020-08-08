<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerFirmSectorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WorkerFirmSectorRepository::class)
 */
class WorkerFirmSector
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
    private $wfs_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $wfs_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $wfs_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWfsName(): ?string
    {
        return $this->wfs_name;
    }

    public function setWfsName(string $wfs_name): self
    {
        $this->wfs_name = $wfs_name;

        return $this;
    }

    public function getWfsCreatedBy(): ?int
    {
        return $this->wfs_createdBy;
    }

    public function setWfsCreatedBy(int $wfs_createdBy): self
    {
        $this->wfs_createdBy = $wfs_createdBy;

        return $this;
    }

    public function getWfsInserted(): ?\DateTimeInterface
    {
        return $this->wfs_inserted;
    }

    public function setWfsInserted(\DateTimeInterface $wfs_inserted): self
    {
        $this->wfs_inserted = $wfs_inserted;

        return $this;
    }
}
