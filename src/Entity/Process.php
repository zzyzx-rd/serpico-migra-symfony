<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProcessRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ProcessRepository::class)
 */
class Process
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
    private $pro_name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pro_approvable;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pro_gradable;

    /**
     * @ORM\Column(type="integer")
     */
    private $pro_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $pro_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $pro_deleted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProName(): ?string
    {
        return $this->pro_name;
    }

    public function setProName(string $pro_name): self
    {
        $this->pro_name = $pro_name;

        return $this;
    }

    public function getProApprovable(): ?bool
    {
        return $this->pro_approvable;
    }

    public function setProApprovable(bool $pro_approvable): self
    {
        $this->pro_approvable = $pro_approvable;

        return $this;
    }

    public function getProGradable(): ?bool
    {
        return $this->pro_gradable;
    }

    public function setProGradable(bool $pro_gradable): self
    {
        $this->pro_gradable = $pro_gradable;

        return $this;
    }

    public function getProCreatedBy(): ?int
    {
        return $this->pro_createdBy;
    }

    public function setProCreatedBy(int $pro_createdBy): self
    {
        $this->pro_createdBy = $pro_createdBy;

        return $this;
    }

    public function getProInserted(): ?\DateTimeInterface
    {
        return $this->pro_inserted;
    }

    public function setProInserted(\DateTimeInterface $pro_inserted): self
    {
        $this->pro_inserted = $pro_inserted;

        return $this;
    }

    public function getProDeleted(): ?\DateTimeInterface
    {
        return $this->pro_deleted;
    }

    public function setProDeleted(?\DateTimeInterface $pro_deleted): self
    {
        $this->pro_deleted = $pro_deleted;

        return $this;
    }
}
