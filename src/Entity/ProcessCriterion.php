<?php

namespace App\Entity;

use App\Repository\ProcessCriterionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProcessCriterionRepository::class)
 */
class ProcessCriterion
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
    private $crt_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $crt_name;

    /**
     * @ORM\Column(type="float")
     */
    private $crt_weight;

    /**
     * @ORM\Column(type="boolean")
     */
    private $crt_forceComment_compare;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $crt_forceComment_value;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $crt_forceComment_sign;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $crt_lowerbound;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $crt_upperbound;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $crt_step;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $crt_comment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $crt_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $crt_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCrtType(): ?int
    {
        return $this->crt_type;
    }

    public function setCrtType(int $crt_type): self
    {
        $this->crt_type = $crt_type;

        return $this;
    }

    public function getCrtName(): ?string
    {
        return $this->crt_name;
    }

    public function setCrtName(?string $crt_name): self
    {
        $this->crt_name = $crt_name;

        return $this;
    }

    public function getCrtWeight(): ?float
    {
        return $this->crt_weight;
    }

    public function setCrtWeight(float $crt_weight): self
    {
        $this->crt_weight = $crt_weight;

        return $this;
    }

    public function getCrtForceCommentCompare(): ?bool
    {
        return $this->crt_forceComment_compare;
    }

    public function setCrtForceCommentCompare(bool $crt_forceComment_compare): self
    {
        $this->crt_forceComment_compare = $crt_forceComment_compare;

        return $this;
    }

    public function getCrtForceCommentValue(): ?float
    {
        return $this->crt_forceComment_value;
    }

    public function setCrtForceCommentValue(?float $crt_forceComment_value): self
    {
        $this->crt_forceComment_value = $crt_forceComment_value;

        return $this;
    }

    public function getCrtForceCommentSign(): ?string
    {
        return $this->crt_forceComment_sign;
    }

    public function setCrtForceCommentSign(?string $crt_forceComment_sign): self
    {
        $this->crt_forceComment_sign = $crt_forceComment_sign;

        return $this;
    }

    public function getCrtLowerbound(): ?float
    {
        return $this->crt_lowerbound;
    }

    public function setCrtLowerbound(?float $crt_lowerbound): self
    {
        $this->crt_lowerbound = $crt_lowerbound;

        return $this;
    }

    public function getCrtUpperbound(): ?float
    {
        return $this->crt_upperbound;
    }

    public function setCrtUpperbound(?float $crt_upperbound): self
    {
        $this->crt_upperbound = $crt_upperbound;

        return $this;
    }

    public function getCrtStep(): ?float
    {
        return $this->crt_step;
    }

    public function setCrtStep(?float $crt_step): self
    {
        $this->crt_step = $crt_step;

        return $this;
    }

    public function getCrtComment(): ?string
    {
        return $this->crt_comment;
    }

    public function setCrtComment(?string $crt_comment): self
    {
        $this->crt_comment = $crt_comment;

        return $this;
    }

    public function getCrtCreatedBy(): ?int
    {
        return $this->crt_createdBy;
    }

    public function setCrtCreatedBy(?int $crt_createdBy): self
    {
        $this->crt_createdBy = $crt_createdBy;

        return $this;
    }

    public function getCrtInserted(): ?\DateTimeInterface
    {
        return $this->crt_inserted;
    }

    public function setCrtInserted(?\DateTimeInterface $crt_inserted): self
    {
        $this->crt_inserted = $crt_inserted;

        return $this;
    }
}
