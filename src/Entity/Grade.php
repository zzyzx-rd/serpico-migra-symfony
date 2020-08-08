<?php

namespace App\Entity;

use App\Repository\GradeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GradeRepository::class)
 */
class Grade
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
    private $grd_type;

    /**
     * @ORM\Column(type="integer")
     */
    private $grd_graded_usr_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $grd_graded_tea_id;

    /**
     * @ORM\Column(type="float")
     */
    private $grd_value;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $grd_comment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $grd_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $grd_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGrdType(): ?int
    {
        return $this->grd_type;
    }

    public function setGrdType(int $grd_type): self
    {
        $this->grd_type = $grd_type;

        return $this;
    }

    public function getGrdGradedUsrId(): ?int
    {
        return $this->grd_graded_usr_id;
    }

    public function setGrdGradedUsrId(int $grd_graded_usr_id): self
    {
        $this->grd_graded_usr_id = $grd_graded_usr_id;

        return $this;
    }

    public function getGrdGradedTeaId(): ?int
    {
        return $this->grd_graded_tea_id;
    }

    public function setGrdGradedTeaId(int $grd_graded_tea_id): self
    {
        $this->grd_graded_tea_id = $grd_graded_tea_id;

        return $this;
    }

    public function getGrdValue(): ?float
    {
        return $this->grd_value;
    }

    public function setGrdValue(float $grd_value): self
    {
        $this->grd_value = $grd_value;

        return $this;
    }

    public function getGrdComment(): ?string
    {
        return $this->grd_comment;
    }

    public function setGrdComment(?string $grd_comment): self
    {
        $this->grd_comment = $grd_comment;

        return $this;
    }

    public function getGrdCreatedBy(): ?int
    {
        return $this->grd_createdBy;
    }

    public function setGrdCreatedBy(?int $grd_createdBy): self
    {
        $this->grd_createdBy = $grd_createdBy;

        return $this;
    }

    public function getGrdInserted(): ?\DateTimeInterface
    {
        return $this->grd_inserted;
    }

    public function setGrdInserted(\DateTimeInterface $grd_inserted): self
    {
        $this->grd_inserted = $grd_inserted;

        return $this;
    }
}
