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
     * @ORM\Column(name="rol_id", type="integer", nullable=false)
     * @var int
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

    /**
     * Role constructor.
     * @param int $id
     * @param $rol_name
     * @param $rol_createdBy
     * @param $rol_inserted
     */
    public function __construct(int $id = 0, $rol_name = '', $rol_createdBy = null, $rol_inserted = null)
    {
        $this->rol_name = $rol_name;
        $this->rol_inserted = $rol_inserted;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->rol_name;
    }

    public function setName(string $rol_name): self
    {
        $this->rol_name = $rol_name;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->rol_createdBy;
    }

    public function setCreatedBy(int $rol_createdBy): self
    {
        $this->rol_createdBy = $rol_createdBy;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->rol_inserted;
    }

    public function setInserted(\DateTimeInterface $rol_inserted): self
    {
        $this->rol_inserted = $rol_inserted;

        return $this;
    }
}
