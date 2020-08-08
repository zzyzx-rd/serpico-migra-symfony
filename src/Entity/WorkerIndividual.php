<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerIndividualRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WorkerIndividualRepository::class)
 */
class WorkerIndividual
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $win_lk_country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $win_lk_url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $win_lk_fullName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $win_lk_male;

    /**
     * @ORM\Column(type="integer")
     */
    private $win_created;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $win_firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $win_lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $win_email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $win_gdpr;

    /**
     * @ORM\Column(type="integer")
     */
    private $win_lk_nbConnections;

    /**
     * @ORM\Column(type="boolean")
     */
    private $win_lk_contacted;

    /**
     * @ORM\Column(type="integer")
     */
    private $win_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $win_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWinLkCountry(): ?string
    {
        return $this->win_lk_country;
    }

    public function setWinLkCountry(string $win_lk_country): self
    {
        $this->win_lk_country = $win_lk_country;

        return $this;
    }

    public function getWinLkUrl(): ?string
    {
        return $this->win_lk_url;
    }

    public function setWinLkUrl(string $win_lk_url): self
    {
        $this->win_lk_url = $win_lk_url;

        return $this;
    }

    public function getWinLkFullName(): ?string
    {
        return $this->win_lk_fullName;
    }

    public function setWinLkFullName(string $win_lk_fullName): self
    {
        $this->win_lk_fullName = $win_lk_fullName;

        return $this;
    }

    public function getWinLkMale(): ?bool
    {
        return $this->win_lk_male;
    }

    public function setWinLkMale(bool $win_lk_male): self
    {
        $this->win_lk_male = $win_lk_male;

        return $this;
    }

    public function getWinCreated(): ?int
    {
        return $this->win_created;
    }

    public function setWinCreated(int $win_created): self
    {
        $this->win_created = $win_created;

        return $this;
    }

    public function getWinFirstname(): ?string
    {
        return $this->win_firstname;
    }

    public function setWinFirstname(string $win_firstname): self
    {
        $this->win_firstname = $win_firstname;

        return $this;
    }

    public function getWinLastname(): ?string
    {
        return $this->win_lastname;
    }

    public function setWinLastname(string $win_lastname): self
    {
        $this->win_lastname = $win_lastname;

        return $this;
    }

    public function getWinEmail(): ?string
    {
        return $this->win_email;
    }

    public function setWinEmail(string $win_email): self
    {
        $this->win_email = $win_email;

        return $this;
    }

    public function getWinGdpr(): ?\DateTimeInterface
    {
        return $this->win_gdpr;
    }

    public function setWinGdpr(\DateTimeInterface $win_gdpr): self
    {
        $this->win_gdpr = $win_gdpr;

        return $this;
    }

    public function getWinLkNbConnections(): ?int
    {
        return $this->win_lk_nbConnections;
    }

    public function setWinLkNbConnections(int $win_lk_nbConnections): self
    {
        $this->win_lk_nbConnections = $win_lk_nbConnections;

        return $this;
    }

    public function getWinLkContacted(): ?bool
    {
        return $this->win_lk_contacted;
    }

    public function setWinLkContacted(bool $win_lk_contacted): self
    {
        $this->win_lk_contacted = $win_lk_contacted;

        return $this;
    }

    public function getWinCreatedBy(): ?int
    {
        return $this->win_createdBy;
    }

    public function setWinCreatedBy(int $win_createdBy): self
    {
        $this->win_createdBy = $win_createdBy;

        return $this;
    }

    public function getWinInserted(): ?\DateTimeInterface
    {
        return $this->win_inserted;
    }

    public function setWinInserted(\DateTimeInterface $win_inserted): self
    {
        $this->win_inserted = $win_inserted;

        return $this;
    }
}
