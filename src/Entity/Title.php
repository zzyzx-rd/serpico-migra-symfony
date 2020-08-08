<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TitleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TitleRepository::class)
 */
class Title
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
    private $tit_name;

    /**
     * @ORM\Column(type="float")
     */
    private $tit_weight_ini;

    /**
     * @ORM\Column(type="integer")
     */
    private $tit_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tit_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tit_deleted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitName(): ?string
    {
        return $this->tit_name;
    }

    public function setTitName(string $tit_name): self
    {
        $this->tit_name = $tit_name;

        return $this;
    }

    public function getTitWeightIni(): ?float
    {
        return $this->tit_weight_ini;
    }

    public function setTitWeightIni(float $tit_weight_ini): self
    {
        $this->tit_weight_ini = $tit_weight_ini;

        return $this;
    }

    public function getTitCreatedBy(): ?int
    {
        return $this->tit_createdBy;
    }

    public function setTitCreatedBy(int $tit_createdBy): self
    {
        $this->tit_createdBy = $tit_createdBy;

        return $this;
    }

    public function getTitInserted(): ?\DateTimeInterface
    {
        return $this->tit_inserted;
    }

    public function setTitInserted(\DateTimeInterface $tit_inserted): self
    {
        $this->tit_inserted = $tit_inserted;

        return $this;
    }

    public function getTitDeleted(): ?\DateTimeInterface
    {
        return $this->tit_deleted;
    }

    public function setTitDeleted(?\DateTimeInterface $tit_deleted): self
    {
        $this->tit_deleted = $tit_deleted;

        return $this;
    }
}
