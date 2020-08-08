<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PositionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=PositionRepository::class)
 */
class Position
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
    private $pos_name;

    /**
     * @ORM\Column(type="float")
     */
    private $pos_weight_ini;

    /**
     * @ORM\Column(type="float")
     */
    private $pos_weight_1y;

    /**
     * @ORM\Column(type="float")
     */
    private $pos_weight_2y;

    /**
     * @ORM\Column(type="float")
     */
    private $pos_weight_3y;

    /**
     * @ORM\Column(type="float")
     */
    private $pos_weight_4y;

    /**
     * @ORM\Column(type="float")
     */
    private $pos_weight_5y;

    /**
     * @ORM\Column(type="integer")
     */
    private $pos_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $pos_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $pos_deleted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosName(): ?string
    {
        return $this->pos_name;
    }

    public function setPosName(string $pos_name): self
    {
        $this->pos_name = $pos_name;

        return $this;
    }

    public function getPosWeightIni(): ?float
    {
        return $this->pos_weight_ini;
    }

    public function setPosWeightIni(float $pos_weight_ini): self
    {
        $this->pos_weight_ini = $pos_weight_ini;

        return $this;
    }

    public function getPosWeight1y(): ?float
    {
        return $this->pos_weight_1y;
    }

    public function setPosWeight1y(float $pos_weight_1y): self
    {
        $this->pos_weight_1y = $pos_weight_1y;

        return $this;
    }

    public function getPosWeight2y(): ?float
    {
        return $this->pos_weight_2y;
    }

    public function setPosWeight2y(float $pos_weight_2y): self
    {
        $this->pos_weight_2y = $pos_weight_2y;

        return $this;
    }

    public function getPosWeight3y(): ?float
    {
        return $this->pos_weight_3y;
    }

    public function setPosWeight3y(float $pos_weight_3y): self
    {
        $this->pos_weight_3y = $pos_weight_3y;

        return $this;
    }

    public function getPosWeight4y(): ?float
    {
        return $this->pos_weight_4y;
    }

    public function setPosWeight4y(float $pos_weight_4y): self
    {
        $this->pos_weight_4y = $pos_weight_4y;

        return $this;
    }

    public function getPosWeight5y(): ?float
    {
        return $this->pos_weight_5y;
    }

    public function setPosWeight5y(float $pos_weight_5y): self
    {
        $this->pos_weight_5y = $pos_weight_5y;

        return $this;
    }

    public function getPosCreatedBy(): ?int
    {
        return $this->pos_createdBy;
    }

    public function setPosCreatedBy(int $pos_createdBy): self
    {
        $this->pos_createdBy = $pos_createdBy;

        return $this;
    }

    public function getPosInserted(): ?\DateTimeInterface
    {
        return $this->pos_inserted;
    }

    public function setPosInserted(\DateTimeInterface $pos_inserted): self
    {
        $this->pos_inserted = $pos_inserted;

        return $this;
    }

    public function getPosDeleted(): ?\DateTimeInterface
    {
        return $this->pos_deleted;
    }

    public function setPosDeleted(?\DateTimeInterface $pos_deleted): self
    {
        $this->pos_deleted = $pos_deleted;

        return $this;
    }
}
