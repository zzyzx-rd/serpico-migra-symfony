<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TemplateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TemplateRepository::class)
 */
class Template
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
    private $tmp_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $tmp_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tmp_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tmp_deleted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTmpName(): ?string
    {
        return $this->tmp_name;
    }

    public function setTmpName(string $tmp_name): self
    {
        $this->tmp_name = $tmp_name;

        return $this;
    }

    public function getTmpCreatedBy(): ?int
    {
        return $this->tmp_createdBy;
    }

    public function setTmpCreatedBy(int $tmp_createdBy): self
    {
        $this->tmp_createdBy = $tmp_createdBy;

        return $this;
    }

    public function getTmpInserted(): ?\DateTimeInterface
    {
        return $this->tmp_inserted;
    }

    public function setTmpInserted(\DateTimeInterface $tmp_inserted): self
    {
        $this->tmp_inserted = $tmp_inserted;

        return $this;
    }

    public function getTmpDeleted(): ?\DateTimeInterface
    {
        return $this->tmp_deleted;
    }

    public function setTmpDeleted(\DateTimeInterface $tmp_deleted): self
    {
        $this->tmp_deleted = $tmp_deleted;

        return $this;
    }
}
