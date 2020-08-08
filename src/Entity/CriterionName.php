<?php

namespace App\Entity;

use App\Repository\CriterionNameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CriterionNameRepository::class)
 */
class CriterionName
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cna_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cna_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $can_unit;

    /**
     * @ORM\Column(type="integer")
     */
    private $can_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $can_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCnaType(): ?int
    {
        return $this->cna_type;
    }

    public function setCnaType(int $cna_type): self
    {
        $this->cna_type = $cna_type;

        return $this;
    }

    public function getCnaName(): ?string
    {
        return $this->cna_name;
    }

    public function setCnaName(string $cna_name): self
    {
        $this->cna_name = $cna_name;

        return $this;
    }

    public function getCanUnit(): ?string
    {
        return $this->can_unit;
    }

    public function setCanUnit(string $can_unit): self
    {
        $this->can_unit = $can_unit;

        return $this;
    }

    public function getCanCreatedBy(): ?int
    {
        return $this->can_createdBy;
    }

    public function setCanCreatedBy(int $can_createdBy): self
    {
        $this->can_createdBy = $can_createdBy;

        return $this;
    }

    public function getCanInserted(): ?\DateTimeInterface
    {
        return $this->can_inserted;
    }

    public function setCanInserted(\DateTimeInterface $can_inserted): self
    {
        $this->can_inserted = $can_inserted;

        return $this;
    }
}
