<?php

namespace App\Entity;

use App\Repository\LKUrlRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LKUrlRepository::class)
 */
class LKUrl extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="lk_url_id", type="integer", nullable=false)
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    public $lk_url_country;

    /**
     * @ORM\Column(type="string", length=10)
     */
    public $lk_url_letter;

    /**
     * @ORM\Column(type="string", length=10)
     */
    public $lk_url_pagNb;

    /**
     * @ORM\Column(type="string", length=10)
     */
    public $lk_url_value;

    /**
     * @ORM\Column(type="integer")
     */
    public $lk_url_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $lk_url_inserted;

    /**
     * LKUrl constructor.
     * @param int $id
     * @param $lk_url_country
     * @param $lk_url_letter
     * @param $lk_url_pagNb
     * @param $lk_url_value
     * @param $lk_url_createdBy
     * @param $lk_url_inserted
     */
    public function __construct(
        int $id = 0,
        $lk_url_letter = null,
        $lk_url_value = null,
        $lk_url_createdBy = null,
        $lk_url_inserted = null,
        $lk_url_pagNb = null,
        $lk_url_country = null)
    {
        parent::__construct($id, $lk_url_createdBy, new DateTime());
        $this->lk_url_country = $lk_url_country;
        $this->lk_url_letter = $lk_url_letter;
        $this->lk_url_pagNb = $lk_url_pagNb;
        $this->lk_url_value = $lk_url_value;
        $this->lk_url_inserted = $lk_url_inserted;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->lk_url_country;
    }

    public function setCountry(string $lk_url_country): self
    {
        $this->lk_url_country = $lk_url_country;

        return $this;
    }

    public function getLetter(): ?string
    {
        return $this->lk_url_letter;
    }

    public function setLetter(string $lk_url_letter): self
    {
        $this->lk_url_letter = $lk_url_letter;

        return $this;
    }

    public function getPagNb(): ?string
    {
        return $this->lk_url_pagNb;
    }

    public function setPagNb(string $lk_url_pagNb): self
    {
        $this->lk_url_pagNb = $lk_url_pagNb;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->lk_url_value;
    }

    public function setValue(string $lk_url_value): self
    {
        $this->lk_url_value = $lk_url_value;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->lk_url_inserted;
    }

    public function setInserted(\DateTimeInterface $lk_url_inserted): self
    {
        $this->lk_url_inserted = $lk_url_inserted;

        return $this;
    }
}
