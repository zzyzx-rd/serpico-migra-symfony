<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CriterionGroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CriterionGroupRepository::class)
 */
class CriterionGroup
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
    private $cgp_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cgp_inserted;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cgp_name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCgpCreatedBy(): ?int
    {
        return $this->cgp_createdBy;
    }

    public function setCgpCreatedBy(int $cgp_createdBy): self
    {
        $this->cgp_createdBy = $cgp_createdBy;

        return $this;
    }

    public function getCgpInserted(): ?\DateTimeInterface
    {
        return $this->cgp_inserted;
    }

    public function setCgpInserted(\DateTimeInterface $cgp_inserted): self
    {
        $this->cgp_inserted = $cgp_inserted;

        return $this;
    }

    public function getCgpName(): ?string
    {
        return $this->cgp_name;
    }

    public function setCgpName(string $cgp_name): self
    {
        $this->cgp_name = $cgp_name;

        return $this;
    }
}
