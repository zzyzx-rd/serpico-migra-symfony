<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="con_id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $con_locale;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $con_type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $con_sent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $con_fullname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $con_compagny;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $con_adress;

    /**
     * @ORM\Column(type="integer")
     */
    private $con_zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $con_city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $con_country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $con_position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $con_email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $con_message;

    /**
     * @ORM\Column(type="boolean")
     */
    private $con_newsletter;

    /**
     * @ORM\Column(type="boolean")
     */
    private $con_doc;

    /**
     * @ORM\Column(type="datetime")
     */
    private $con_mdate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $con_mtime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $con_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $con_confirmed;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $con_createdBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConLocale(): ?string
    {
        return $this->con_locale;
    }

    public function setConLocale(string $con_locale): self
    {
        $this->con_locale = $con_locale;

        return $this;
    }

    public function getConType(): ?string
    {
        return $this->con_type;
    }

    public function setConType(string $con_type): self
    {
        $this->con_type = $con_type;

        return $this;
    }

    public function getConSent(): ?bool
    {
        return $this->con_sent;
    }

    public function setConSent(bool $con_sent): self
    {
        $this->con_sent = $con_sent;

        return $this;
    }

    public function getConFullname(): ?string
    {
        return $this->con_fullname;
    }

    public function setConFullname(string $con_fullname): self
    {
        $this->con_fullname = $con_fullname;

        return $this;
    }

    public function getConCompagny(): ?string
    {
        return $this->con_compagny;
    }

    public function setConCompagny(string $con_compagny): self
    {
        $this->con_compagny = $con_compagny;

        return $this;
    }

    public function getConAdress(): ?string
    {
        return $this->con_adress;
    }

    public function setConAdress(string $con_adress): self
    {
        $this->con_adress = $con_adress;

        return $this;
    }

    public function getConZipCode(): ?int
    {
        return $this->con_zipCode;
    }

    public function setConZipCode(int $con_zipCode): self
    {
        $this->con_zipCode = $con_zipCode;

        return $this;
    }

    public function getConCity(): ?string
    {
        return $this->con_city;
    }

    public function setConCity(string $con_city): self
    {
        $this->con_city = $con_city;

        return $this;
    }

    public function getConCountry(): ?string
    {
        return $this->con_country;
    }

    public function setConCountry(string $con_country): self
    {
        $this->con_country = $con_country;

        return $this;
    }

    public function getConPosition(): ?string
    {
        return $this->con_position;
    }

    public function setConPosition(string $con_position): self
    {
        $this->con_position = $con_position;

        return $this;
    }

    public function getConEmail(): ?string
    {
        return $this->con_email;
    }

    public function setConEmail(string $con_email): self
    {
        $this->con_email = $con_email;

        return $this;
    }

    public function getConMessage(): ?string
    {
        return $this->con_message;
    }

    public function setConMessage(string $con_message): self
    {
        $this->con_message = $con_message;

        return $this;
    }

    public function getConNewsletter(): ?bool
    {
        return $this->con_newsletter;
    }

    public function setConNewsletter(bool $con_newsletter): self
    {
        $this->con_newsletter = $con_newsletter;

        return $this;
    }

    public function getConDoc(): ?bool
    {
        return $this->con_doc;
    }

    public function setConDoc(bool $con_doc): self
    {
        $this->con_doc = $con_doc;

        return $this;
    }

    public function getConMdate(): ?\DateTimeInterface
    {
        return $this->con_mdate;
    }

    public function setConMdate(\DateTimeInterface $con_mdate): self
    {
        $this->con_mdate = $con_mdate;

        return $this;
    }

    public function getConMtime(): ?\DateTimeInterface
    {
        return $this->con_mtime;
    }

    public function setConMtime(\DateTimeInterface $con_mtime): self
    {
        $this->con_mtime = $con_mtime;

        return $this;
    }

    public function getConInserted(): ?\DateTimeInterface
    {
        return $this->con_inserted;
    }

    public function setConInserted(\DateTimeInterface $con_inserted): self
    {
        $this->con_inserted = $con_inserted;

        return $this;
    }

    public function getConConfirmed(): ?\DateTimeInterface
    {
        return $this->con_confirmed;
    }

    public function setConConfirmed(\DateTimeInterface $con_confirmed): self
    {
        $this->con_confirmed = $con_confirmed;

        return $this;
    }

    public function getConCreatedBy(): ?int
    {
        return $this->con_createdBy;
    }

    public function setConCreatedBy(?int $con_createdBy): self
    {
        $this->con_createdBy = $con_createdBy;

        return $this;
    }
}
