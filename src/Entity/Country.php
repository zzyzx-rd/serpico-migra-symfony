<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 */
class Country
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
    private $cou_abbr;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cou_fullname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cou_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cou_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cou_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCouAbbr(): ?string
    {
        return $this->cou_abbr;
    }

    public function setCouAbbr(string $cou_abbr): self
    {
        $this->cou_abbr = $cou_abbr;

        return $this;
    }

    public function getCouFullname(): ?string
    {
        return $this->cou_fullname;
    }

    public function setCouFullname(string $cou_fullname): self
    {
        $this->cou_fullname = $cou_fullname;

        return $this;
    }

    public function getCouName(): ?string
    {
        return $this->cou_name;
    }

    public function setCouName(string $cou_name): self
    {
        $this->cou_name = $cou_name;

        return $this;
    }

    public function getCouCreatedBy(): ?int
    {
        return $this->cou_createdBy;
    }

    public function setCouCreatedBy(?int $cou_createdBy): self
    {
        $this->cou_createdBy = $cou_createdBy;

        return $this;
    }

    public function getCouInserted(): ?\DateTimeInterface
    {
        return $this->cou_inserted;
    }

    public function setCouInserted(\DateTimeInterface $cou_inserted): self
    {
        $this->cou_inserted = $cou_inserted;

        return $this;
    }
}
