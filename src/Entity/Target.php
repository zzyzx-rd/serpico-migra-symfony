<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TargetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TargetRepository::class)
 */
class Target
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
    private $tgt_sign;

    /**
     * @ORM\Column(type="float")
     */
    private $tgt_value;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tgt_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tgt_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTgtSign(): ?int
    {
        return $this->tgt_sign;
    }

    public function setTgtSign(int $tgt_sign): self
    {
        $this->tgt_sign = $tgt_sign;

        return $this;
    }

    public function getTgtValue(): ?float
    {
        return $this->tgt_value;
    }

    public function setTgtValue(float $tgt_value): self
    {
        $this->tgt_value = $tgt_value;

        return $this;
    }

    public function getTgtCreatedBy(): ?int
    {
        return $this->tgt_createdBy;
    }

    public function setTgtCreatedBy(?int $tgt_createdBy): self
    {
        $this->tgt_createdBy = $tgt_createdBy;

        return $this;
    }

    public function getTgtInserted(): ?\DateTimeInterface
    {
        return $this->tgt_inserted;
    }

    public function setTgtInserted(\DateTimeInterface $tgt_inserted): self
    {
        $this->tgt_inserted = $tgt_inserted;

        return $this;
    }
}
