<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ContactRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="con_id", type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $con_locale;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $con_type;

    /**
     * @ORM\Column(type="boolean")
     */
    public $con_sent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $con_fullname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $con_compagny;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $con_adress;

    /**
     * @ORM\Column(type="integer")
     */
    public $con_zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $con_city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $con_country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $con_position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $con_email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $con_message;

    /**
     * @ORM\Column(type="boolean")
     */
    public $con_newsletter;

    /**
     * @ORM\Column(type="boolean")
     */
    public $con_doc;

    /**
     * @ORM\Column(type="datetime")
     */
    public $con_mdate;

    /**
     * @ORM\Column(type="datetime")
     */
    public $con_mtime;

    /**
     * @ORM\Column(type="datetime")
     */
    public $con_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    public $con_confirmed;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $con_createdBy;

    /**
     * Contact constructor.
     * @param $id
     * @param $con_locale
     * @param $con_type
     * @param $con_sent
     * @param $con_fullname
     * @param $con_compagny
     * @param $con_adress
     * @param $con_zipCode
     * @param $con_city
     * @param $con_country
     * @param $con_position
     * @param $con_email
     * @param $con_message
     * @param $con_newsletter
     * @param $con_doc
     * @param $con_mdate
     * @param $con_mtime
     * @param $con_inserted
     * @param $con_confirmed
     * @param $con_createdBy
     */
    public function __construct(
        $id = 0,
        $con_locale = '',
        $con_type = '',
        $con_sent = false,
        $con_fullname = null,
        $con_compagny = "",
        $con_adress = null,
        $con_zipCode = null,
        $con_city = null,
        $con_country = null,
        $con_position = '',
        $con_email = '',
        $con_message = null,
        $con_newsletter = null,
        $con_doc = false,
        $con_mdate = null,
        $con_mtime = null,
        $con_inserted = null,
        $con_confirmed = null,
        $con_createdBy = null)
    {
        parent::__construct($id, $con_createdBy, new DateTime());
        $this->con_locale = $con_locale;
        $this->con_type = $con_type;
        $this->con_sent = $con_sent;
        $this->con_fullname = $con_fullname;
        $this->con_compagny = $con_compagny;
        $this->con_adress = $con_adress;
        $this->con_zipCode = $con_zipCode;
        $this->con_city = $con_city;
        $this->con_country = $con_country;
        $this->con_position = $con_position;
        $this->con_email = $con_email;
        $this->con_message = $con_message;
        $this->con_newsletter = $con_newsletter;
        $this->con_doc = $con_doc;
        $this->con_mdate = $con_mdate;
        $this->con_mtime = $con_mtime;
        $this->con_inserted = $con_inserted;
        $this->con_confirmed = $con_confirmed;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocale(): ?string
    {
        return $this->con_locale;
    }

    public function setLocale(string $con_locale): self
    {
        $this->con_locale = $con_locale;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->con_type;
    }

    public function setType(string $con_type): self
    {
        $this->con_type = $con_type;

        return $this;
    }

    public function getSent(): ?bool
    {
        return $this->con_sent;
    }

    public function setSent(bool $con_sent): self
    {
        $this->con_sent = $con_sent;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->con_fullname;
    }

    public function setFullname(string $con_fullname): self
    {
        $this->con_fullname = $con_fullname;

        return $this;
    }

    public function getCompagny(): ?string
    {
        return $this->con_compagny;
    }

    public function setCompagny(string $con_compagny): self
    {
        $this->con_compagny = $con_compagny;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->con_adress;
    }

    public function setAdress(string $con_adress): self
    {
        $this->con_adress = $con_adress;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->con_zipCode;
    }

    public function setZipCode(int $con_zipCode): self
    {
        $this->con_zipCode = $con_zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->con_city;
    }

    public function setCity(string $con_city): self
    {
        $this->con_city = $con_city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->con_country;
    }

    public function setCountry(string $con_country): self
    {
        $this->con_country = $con_country;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->con_position;
    }

    public function setPosition(string $con_position): self
    {
        $this->con_position = $con_position;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->con_email;
    }

    public function setEmail(string $con_email): self
    {
        $this->con_email = $con_email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->con_message;
    }

    public function setMessage(string $con_message): self
    {
        $this->con_message = $con_message;

        return $this;
    }

    public function isNewsletter(): ?bool
    {
        return $this->con_newsletter;
    }

    public function setNewsletter(bool $con_newsletter): self
    {
        $this->con_newsletter = $con_newsletter;

        return $this;
    }

    public function isDoc(): ?bool
    {
        return $this->con_doc;
    }

    public function setDoc(bool $con_doc): self
    {
        $this->con_doc = $con_doc;

        return $this;
    }

    public function getMdate(): ?\DateTimeInterface
    {
        return $this->con_mdate;
    }

    public function setMdate(\DateTimeInterface $con_mdate): self
    {
        $this->con_mdate = $con_mdate;

        return $this;
    }

    public function getMtime(): ?\DateTimeInterface
    {
        return $this->con_mtime;
    }

    public function setMtime(\DateTimeInterface $con_mtime): self
    {
        $this->con_mtime = $con_mtime;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->con_inserted;
    }

    public function setInserted(\DateTimeInterface $con_inserted): self
    {
        $this->con_inserted = $con_inserted;

        return $this;
    }

    public function getConfirmed(): ?\DateTimeInterface
    {
        return $this->con_confirmed;
    }

    public function setConfirmed(\DateTimeInterface $con_confirmed): self
    {
        $this->con_confirmed = $con_confirmed;

        return $this;
    }

}
