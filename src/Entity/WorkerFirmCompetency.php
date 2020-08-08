<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerFirmCompetencyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WorkerFirmCompetencyRepository::class)
 */
class WorkerFirmCompetency
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
    private $wfc_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $wfc_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $wfc_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWfcName(): ?string
    {
        return $this->wfc_name;
    }

    public function setWfcName(string $wfc_name): self
    {
        $this->wfc_name = $wfc_name;

        return $this;
    }

    public function getWfcCreatedBy(): ?int
    {
        return $this->wfc_createdBy;
    }

    public function setWfcCreatedBy(int $wfc_createdBy): self
    {
        $this->wfc_createdBy = $wfc_createdBy;

        return $this;
    }

    public function getWfcInserted(): ?\DateTimeInterface
    {
        return $this->wfc_inserted;
    }

    public function setWfcInserted(\DateTimeInterface $wfc_inserted): self
    {
        $this->wfc_inserted = $wfc_inserted;

        return $this;
    }
}
