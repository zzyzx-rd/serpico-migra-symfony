<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\InsitutionProcessRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=InsitutionProcessRepository::class)
 */
class InsitutionProcess
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
    private $inp_name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $inp_approvable;

    /**
     * @ORM\Column(type="boolean")
     */
    private $inp_gradable;

    /**
     * @ORM\Column(type="integer")
     */
    private $inp_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $inp_isnerted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $inp_deleted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInpName(): ?string
    {
        return $this->inp_name;
    }

    public function setInpName(string $inp_name): self
    {
        $this->inp_name = $inp_name;

        return $this;
    }

    public function getInpApprovable(): ?bool
    {
        return $this->inp_approvable;
    }

    public function setInpApprovable(bool $inp_approvable): self
    {
        $this->inp_approvable = $inp_approvable;

        return $this;
    }

    public function getInpGradable(): ?bool
    {
        return $this->inp_gradable;
    }

    public function setInpGradable(bool $inp_gradable): self
    {
        $this->inp_gradable = $inp_gradable;

        return $this;
    }

    public function getInpCreatedBy(): ?int
    {
        return $this->inp_createdBy;
    }

    public function setInpCreatedBy(int $inp_createdBy): self
    {
        $this->inp_createdBy = $inp_createdBy;

        return $this;
    }

    public function getInpIsnerted(): ?\DateTimeInterface
    {
        return $this->inp_isnerted;
    }

    public function setInpIsnerted(\DateTimeInterface $inp_isnerted): self
    {
        $this->inp_isnerted = $inp_isnerted;

        return $this;
    }

    public function getInpDeleted(): ?\DateTimeInterface
    {
        return $this->inp_deleted;
    }

    public function setInpDeleted(?\DateTimeInterface $inp_deleted): self
    {
        $this->inp_deleted = $inp_deleted;

        return $this;
    }
}
