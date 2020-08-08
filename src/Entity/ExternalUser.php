<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ExternalUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ExternalUserRepository::class)
 */
class ExternalUser
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
    private $ext_fisrtname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ext_lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ext_email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ext_positionName;

    /**
     * @ORM\Column(type="float")
     */
    private $ext_weight_value;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ext_owner;

    /**
     * @ORM\Column(type="integer")
     */
    private $ext_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $ext_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $ext_last_connected;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $ext_deleted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExtFisrtname(): ?string
    {
        return $this->ext_fisrtname;
    }

    public function setExtFisrtname(string $ext_fisrtname): self
    {
        $this->ext_fisrtname = $ext_fisrtname;

        return $this;
    }

    public function getExtLastname(): ?string
    {
        return $this->ext_lastname;
    }

    public function setExtLastname(string $ext_lastname): self
    {
        $this->ext_lastname = $ext_lastname;

        return $this;
    }

    public function getExtEmail(): ?string
    {
        return $this->ext_email;
    }

    public function setExtEmail(?string $ext_email): self
    {
        $this->ext_email = $ext_email;

        return $this;
    }

    public function getExtPositionName(): ?string
    {
        return $this->ext_positionName;
    }

    public function setExtPositionName(string $ext_positionName): self
    {
        $this->ext_positionName = $ext_positionName;

        return $this;
    }

    public function getExtWeightValue(): ?float
    {
        return $this->ext_weight_value;
    }

    public function setExtWeightValue(float $ext_weight_value): self
    {
        $this->ext_weight_value = $ext_weight_value;

        return $this;
    }

    public function getExtOwner(): ?bool
    {
        return $this->ext_owner;
    }

    public function setExtOwner(bool $ext_owner): self
    {
        $this->ext_owner = $ext_owner;

        return $this;
    }

    public function getExtCreatedBy(): ?int
    {
        return $this->ext_createdBy;
    }

    public function setExtCreatedBy(int $ext_createdBy): self
    {
        $this->ext_createdBy = $ext_createdBy;

        return $this;
    }

    public function getExtInserted(): ?\DateTimeInterface
    {
        return $this->ext_inserted;
    }

    public function setExtInserted(\DateTimeInterface $ext_inserted): self
    {
        $this->ext_inserted = $ext_inserted;

        return $this;
    }

    public function getExtLastConnected(): ?\DateTimeInterface
    {
        return $this->ext_last_connected;
    }

    public function setExtLastConnected(?\DateTimeInterface $ext_last_connected): self
    {
        $this->ext_last_connected = $ext_last_connected;

        return $this;
    }

    public function getExtDeleted(): ?\DateTimeInterface
    {
        return $this->ext_deleted;
    }

    public function setExtDeleted(?\DateTimeInterface $ext_deleted): self
    {
        $this->ext_deleted = $ext_deleted;

        return $this;
    }
}
