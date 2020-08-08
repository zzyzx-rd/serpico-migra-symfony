<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AppreciationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AppreciationRepository::class)
 */
class Appreciation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $apt_value;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apt_comment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $apt_createdBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAptValue(): ?float
    {
        return $this->apt_value;
    }

    public function setAptValue(float $apt_value): self
    {
        $this->apt_value = $apt_value;

        return $this;
    }

    public function getAptComment(): ?string
    {
        return $this->apt_comment;
    }

    public function setAptComment(string $apt_comment): self
    {
        $this->apt_comment = $apt_comment;

        return $this;
    }

    public function getAptCreatedBy(): ?int
    {
        return $this->apt_createdBy;
    }

    public function setAptCreatedBy(?int $apt_createdBy): self
    {
        $this->apt_createdBy = $apt_createdBy;

        return $this;
    }
}
