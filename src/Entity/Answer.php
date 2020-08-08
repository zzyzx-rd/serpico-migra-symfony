<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AnswerRepository::class)
 */
class Answer
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
    private $asw_text;

    /**
     * @ORM\Column(type="integer")
     */
    private $asw_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $asw_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAswText(): ?string
    {
        return $this->asw_text;
    }

    public function setAswText(string $asw_text): self
    {
        $this->asw_text = $asw_text;

        return $this;
    }

    public function getAswCreatedBy(): ?int
    {
        return $this->asw_createdBy;
    }

    public function setAswCreatedBy(int $asw_createdBy): self
    {
        $this->asw_createdBy = $asw_createdBy;

        return $this;
    }

    public function getAswInserted(): ?\DateTimeInterface
    {
        return $this->asw_inserted;
    }

    public function setAswInserted(\DateTimeInterface $asw_inserted): self
    {
        $this->asw_inserted = $asw_inserted;

        return $this;
    }
}
