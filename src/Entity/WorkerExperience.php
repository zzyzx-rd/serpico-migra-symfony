<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerExperienceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WorkerExperienceRepository::class)
 */
class WorkerExperience
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
    private $wex_active;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wex_position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wex_location;

    /**
     * @ORM\Column(type="datetime")
     */
    private $wex_startdate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $wex_enddate;

    /**
     * @ORM\Column(type="integer")
     */
    private $wex_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $wex_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWexActive(): ?bool
    {
        return $this->wex_active;
    }

    public function setWexActive(bool $wex_active): self
    {
        $this->wex_active = $wex_active;

        return $this;
    }

    public function getWexPosition(): ?string
    {
        return $this->wex_position;
    }

    public function setWexPosition(string $wex_position): self
    {
        $this->wex_position = $wex_position;

        return $this;
    }

    public function getWexLocation(): ?string
    {
        return $this->wex_location;
    }

    public function setWexLocation(string $wex_location): self
    {
        $this->wex_location = $wex_location;

        return $this;
    }

    public function getWexStartdate(): ?\DateTimeInterface
    {
        return $this->wex_startdate;
    }

    public function setWexStartdate(\DateTimeInterface $wex_startdate): self
    {
        $this->wex_startdate = $wex_startdate;

        return $this;
    }

    public function getWexEnddate(): ?\DateTimeInterface
    {
        return $this->wex_enddate;
    }

    public function setWexEnddate(\DateTimeInterface $wex_enddate): self
    {
        $this->wex_enddate = $wex_enddate;

        return $this;
    }

    public function getWexCreatedBy(): ?int
    {
        return $this->wex_createdBy;
    }

    public function setWexCreatedBy(int $wex_createdBy): self
    {
        $this->wex_createdBy = $wex_createdBy;

        return $this;
    }

    public function getWexInserted(): ?\DateTimeInterface
    {
        return $this->wex_inserted;
    }

    public function setWexInserted(\DateTimeInterface $wex_inserted): self
    {
        $this->wex_inserted = $wex_inserted;

        return $this;
    }
}
