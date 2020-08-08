<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\IconRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=IconRepository::class)
 */
class Icon
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
    private $ico_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ico_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ico_unicode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ico_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $ico_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIcoType(): ?string
    {
        return $this->ico_type;
    }

    public function setIcoType(string $ico_type): self
    {
        $this->ico_type = $ico_type;

        return $this;
    }

    public function getIcoName(): ?string
    {
        return $this->ico_name;
    }

    public function setIcoName(string $ico_name): self
    {
        $this->ico_name = $ico_name;

        return $this;
    }

    public function getIcoUnicode(): ?string
    {
        return $this->ico_unicode;
    }

    public function setIcoUnicode(string $ico_unicode): self
    {
        $this->ico_unicode = $ico_unicode;

        return $this;
    }

    public function getIcoCreatedBy(): ?int
    {
        return $this->ico_createdBy;
    }

    public function setIcoCreatedBy(?int $ico_createdBy): self
    {
        $this->ico_createdBy = $ico_createdBy;

        return $this;
    }

    public function getIcoInserted(): ?\DateTimeInterface
    {
        return $this->ico_inserted;
    }

    public function setIcoInserted(\DateTimeInterface $ico_inserted): self
    {
        $this->ico_inserted = $ico_inserted;

        return $this;
    }
}
