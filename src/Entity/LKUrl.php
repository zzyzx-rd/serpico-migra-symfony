<?php

namespace App\Entity;

use App\Repository\LKUrlRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LKUrlRepository::class)
 */
class LKUrl
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="lk_url_id", type="integer", nullable=false)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $lk_url_country;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $lk_url_letter;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $lk_url_pagNb;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $lk_url_value;

    /**
     * @ORM\Column(type="integer")
     */
    private $lk_url_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lk_url_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLkUrlCountry(): ?string
    {
        return $this->lk_url_country;
    }

    public function setLkUrlCountry(string $lk_url_country): self
    {
        $this->lk_url_country = $lk_url_country;

        return $this;
    }

    public function getLkUrlLetter(): ?string
    {
        return $this->lk_url_letter;
    }

    public function setLkUrlLetter(string $lk_url_letter): self
    {
        $this->lk_url_letter = $lk_url_letter;

        return $this;
    }

    public function getLkUrlPagNb(): ?string
    {
        return $this->lk_url_pagNb;
    }

    public function setLkUrlPagNb(string $lk_url_pagNb): self
    {
        $this->lk_url_pagNb = $lk_url_pagNb;

        return $this;
    }

    public function getLkUrlValue(): ?string
    {
        return $this->lk_url_value;
    }

    public function setLkUrlValue(string $lk_url_value): self
    {
        $this->lk_url_value = $lk_url_value;

        return $this;
    }

    public function getLkUrlCreatedBy(): ?int
    {
        return $this->lk_url_createdBy;
    }

    public function setLkUrlCreatedBy(int $lk_url_createdBy): self
    {
        $this->lk_url_createdBy = $lk_url_createdBy;

        return $this;
    }

    public function getLkUrlInserted(): ?\DateTimeInterface
    {
        return $this->lk_url_inserted;
    }

    public function setLkUrlInserted(\DateTimeInterface $lk_url_inserted): self
    {
        $this->lk_url_inserted = $lk_url_inserted;

        return $this;
    }
}
