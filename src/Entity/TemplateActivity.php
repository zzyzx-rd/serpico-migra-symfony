<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TemplateActivityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TemplateActivityRepository::class)
 */
class TemplateActivity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $act_simplified;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $act_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $act_visibility;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $act_objectives;

    /**
     * @ORM\Column(type="integer")
     */
    private $act_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $act_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $act_saved;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActSimplified(): ?bool
    {
        return $this->act_simplified;
    }

    public function setActSimplified(bool $act_simplified): self
    {
        $this->act_simplified = $act_simplified;

        return $this;
    }

    public function getActName(): ?string
    {
        return $this->act_name;
    }

    public function setActName(string $act_name): self
    {
        $this->act_name = $act_name;

        return $this;
    }

    public function getActVisibility(): ?string
    {
        return $this->act_visibility;
    }

    public function setActVisibility(string $act_visibility): self
    {
        $this->act_visibility = $act_visibility;

        return $this;
    }

    public function getActObjectives(): ?string
    {
        return $this->act_objectives;
    }

    public function setActObjectives(string $act_objectives): self
    {
        $this->act_objectives = $act_objectives;

        return $this;
    }

    public function getActCreatedBy(): ?int
    {
        return $this->act_createdBy;
    }

    public function setActCreatedBy(int $act_createdBy): self
    {
        $this->act_createdBy = $act_createdBy;

        return $this;
    }

    public function getActInserted(): ?\DateTimeInterface
    {
        return $this->act_inserted;
    }

    public function setActInserted(\DateTimeInterface $act_inserted): self
    {
        $this->act_inserted = $act_inserted;

        return $this;
    }

    public function getActSaved(): ?\DateTimeInterface
    {
        return $this->act_saved;
    }

    public function setActSaved(\DateTimeInterface $act_saved): self
    {
        $this->act_saved = $act_saved;

        return $this;
    }
}
