<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SurveyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=SurveyRepository::class)
 */
class Survey
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $sur_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $sur_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sur_inserted;

    /**
     * @ORM\Column(type="integer")
     */
    private $sur_state;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurName(): ?string
    {
        return $this->sur_name;
    }

    public function setSurName(string $sur_name): self
    {
        $this->sur_name = $sur_name;

        return $this;
    }

    public function getSurCreatedBy(): ?int
    {
        return $this->sur_createdBy;
    }

    public function setSurCreatedBy(int $sur_createdBy): self
    {
        $this->sur_createdBy = $sur_createdBy;

        return $this;
    }

    public function getSurInserted(): ?\DateTimeInterface
    {
        return $this->sur_inserted;
    }

    public function setSurInserted(\DateTimeInterface $sur_inserted): self
    {
        $this->sur_inserted = $sur_inserted;

        return $this;
    }

    public function getSurState(): ?int
    {
        return $this->sur_state;
    }

    public function setSurState(int $sur_state): self
    {
        $this->sur_state = $sur_state;

        return $this;
    }
}
