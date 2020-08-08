<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ActivityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 */
class Activity
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
    private $act_complete;

    /**
     * @ORM\Column(type="integer")
     */
    private $act_master_usr_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $act_magnitude;

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
     * @ORM\Column(type="datetime")
     */
    private $act_startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $act_endDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $act_objectives;

    /**
     * @ORM\Column(type="integer")
     */
    private $act_status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActComplete(): ?bool
    {
        return $this->act_complete;
    }

    public function setActComplete(bool $act_complete): self
    {
        $this->act_complete = $act_complete;

        return $this;
    }

    public function getActMasterUsrId(): ?int
    {
        return $this->act_master_usr_id;
    }

    public function setActMasterUsrId(int $act_master_usr_id): self
    {
        $this->act_master_usr_id = $act_master_usr_id;

        return $this;
    }

    public function getActMagnitude(): ?int
    {
        return $this->act_magnitude;
    }

    public function setActMagnitude(int $act_magnitude): self
    {
        $this->act_magnitude = $act_magnitude;

        return $this;
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

    public function getActStartDate(): ?\DateTimeInterface
    {
        return $this->act_startDate;
    }

    public function setActStartDate(\DateTimeInterface $act_startDate): self
    {
        $this->act_startDate = $act_startDate;

        return $this;
    }

    public function getActEndDate(): ?\DateTimeInterface
    {
        return $this->act_endDate;
    }

    public function setActEndDate(\DateTimeInterface $act_endDate): self
    {
        $this->act_endDate = $act_endDate;

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

    public function getActStatus(): ?int
    {
        return $this->act_status;
    }

    public function setActStatus(int $act_status): self
    {
        $this->act_status = $act_status;

        return $this;
    }
}
