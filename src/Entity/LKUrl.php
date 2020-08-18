<?php

namespace App\Entity;

use App\Repository\LKUrlRepository;
use DateTime;
use DateTimeInterface;
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
     * @ORM\Column(name="lk_url_country", type="string", length=10, nullable=true)
     */
    public $url_country;

    /**
     * @ORM\Column(name="lk_url_letter", type="string", length=10, nullable=true)
     */
    public $letter;

    /**
     * @ORM\Column(name="lk_url_pagNb", type="string", length=10, nullable=true)
     */
    public $pagNb;

    /**
     * @ORM\Column(name="lk_url_value", type="string", length=10, nullable=true)
     */
    public $value;

    /**
     * @ORM\Column(name="lk_url_createdBy", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="lk_url_inserted", type="datetime", nullable=true)
     */
    public $inserted;

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
        $this->url_country = $lk_url_country;
        $this->letter = $lk_url_letter;
        $this->pagNb = $lk_url_pagNb;
        $this->value = $lk_url_value;
        $this->inserted = $lk_url_inserted;
    }


    public function getCountry(): ?string
    {
        return $this->url_country;
    }

    public function setCountry(string $lk_url_country): self
    {
        $this->url_country = $lk_url_country;

        return $this;
    }

    public function getLetter(): ?string
    {
        return $this->letter;
    }

    public function setLetter(string $lk_url_letter): self
    {
        $this->letter = $lk_url_letter;

        return $this;
    }

    public function getPagNb(): ?string
    {
        return $this->pagNb;
    }

    public function setPagNb(string $lk_url_pagNb): self
    {
        $this->pagNb = $lk_url_pagNb;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $lk_url_value): self
    {
        $this->value = $lk_url_value;

        return $this;
    }

    public function setInserted(DateTimeInterface $lk_url_inserted): self
    {
        $this->inserted = $lk_url_inserted;

        return $this;
    }
}
