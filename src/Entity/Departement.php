<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DepartementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=DepartementRepository::class)
 */
class Departement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $dpt_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $dpt_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dpt_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dpt_deleted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDptName(): ?string
    {
        return $this->dpt_name;
    }

    public function setDptName(string $dpt_name): self
    {
        $this->dpt_name = $dpt_name;

        return $this;
    }

    public function getDptCreatedBy(): ?int
    {
        return $this->dpt_createdBy;
    }

    public function setDptCreatedBy(int $dpt_createdBy): self
    {
        $this->dpt_createdBy = $dpt_createdBy;

        return $this;
    }

    public function getDptInserted(): ?\DateTimeInterface
    {
        return $this->dpt_inserted;
    }

    public function setDptInserted(\DateTimeInterface $dpt_inserted): self
    {
        $this->dpt_inserted = $dpt_inserted;

        return $this;
    }

    public function getDptDeleted(): ?\DateTimeInterface
    {
        return $this->dpt_deleted;
    }

    public function setDptDeleted(?\DateTimeInterface $dpt_deleted): self
    {
        $this->dpt_deleted = $dpt_deleted;

        return $this;
    }
}
