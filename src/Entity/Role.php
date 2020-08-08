<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RoleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=RoleRepository::class)
 */
class Role
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $rol_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $rol_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $rol_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRolName(): ?string
    {
        return $this->rol_name;
    }

    public function setRolName(string $rol_name): self
    {
        $this->rol_name = $rol_name;

        return $this;
    }

    public function getRolCreatedBy(): ?int
    {
        return $this->rol_createdBy;
    }

    public function setRolCreatedBy(int $rol_createdBy): self
    {
        $this->rol_createdBy = $rol_createdBy;

        return $this;
    }

    public function getRolInserted(): ?\DateTimeInterface
    {
        return $this->rol_inserted;
    }

    public function setRolInserted(\DateTimeInterface $rol_inserted): self
    {
        $this->rol_inserted = $rol_inserted;

        return $this;
    }
}
