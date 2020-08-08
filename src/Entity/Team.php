<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TeamRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
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
    private $tea_name;

    /**
     * @ORM\Column(type="float")
     */
    private $tea_weight_ini;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $tea_picture;

    /**
     * @ORM\Column(type="integer")
     */
    private $tea_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tea_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tea_deleted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeaName(): ?string
    {
        return $this->tea_name;
    }

    public function setTeaName(string $tea_name): self
    {
        $this->tea_name = $tea_name;

        return $this;
    }

    public function getTeaWeightIni(): ?float
    {
        return $this->tea_weight_ini;
    }

    public function setTeaWeightIni(float $tea_weight_ini): self
    {
        $this->tea_weight_ini = $tea_weight_ini;

        return $this;
    }

    public function getTeaPicture(): ?string
    {
        return $this->tea_picture;
    }

    public function setTeaPicture(?string $tea_picture): self
    {
        $this->tea_picture = $tea_picture;

        return $this;
    }

    public function getTeaCreatedBy(): ?int
    {
        return $this->tea_createdBy;
    }

    public function setTeaCreatedBy(int $tea_createdBy): self
    {
        $this->tea_createdBy = $tea_createdBy;

        return $this;
    }

    public function getTeaInserted(): ?\DateTimeInterface
    {
        return $this->tea_inserted;
    }

    public function setTeaInserted(\DateTimeInterface $tea_inserted): self
    {
        $this->tea_inserted = $tea_inserted;

        return $this;
    }

    public function getTeaDeleted(): ?\DateTimeInterface
    {
        return $this->tea_deleted;
    }

    public function setTeaDeleted(\DateTimeInterface $tea_deleted): self
    {
        $this->tea_deleted = $tea_deleted;

        return $this;
    }
}
