<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ContactRepository;
use DateTime;
use DateTimeInterface;
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
     * @ORM\Column(name="con_id", type="integer", nullable=true)
     */
    public $id;

    /**
     * @ORM\Column(name="con_locale", type="string", length=255, nullable=true)
     */
    public $locale;

    /**
     * @ORM\Column(name="con_type", type="string", length=255, nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="con_sent", type="boolean", nullable=true)
     */
    public $sent;

    /**
     * @ORM\Column(name="con_fullname", type="string", length=255, nullable=true)
     */
    public $fullname;

    /**
     * @ORM\Column(name="con_compagny", type="string", length=255, nullable=true)
     */
    public $compagny;

    /**
     * @ORM\Column(name="con_adress", type="string", length=255, nullable=true)
     */
    public $adress;

    /**
     * @ORM\Column(name="con_zipCode", type="integer", nullable=true)
     */
    public $zipCode;

    /**
     * @ORM\Column(name="con_city", type="string", length=255, nullable=true)
     */
    public $city;

    /**
     * @ORM\Column(name="con_country", type="string", length=255, nullable=true)
     */
    public $country;

    /**
     * @ORM\Column(name="con_position", type="string", length=255, nullable=true)
     */
    public $position;

    /**
     * @ORM\Column(name="con_email", type="string", length=255, nullable=true)
     */
    public $email;

    /**
     * @ORM\Column(name="con_message", type="string", length=255, nullable=true)
     */
    public $message;

    /**
     * @ORM\Column(name="con_newsletter", type="boolean", nullable=true)
     */
    public $newsletter;

    /**
     * @ORM\Column(name="con_doc", type="boolean", nullable=true)
     */
    public $doc;

    /**
     * @ORM\Column(name="con_mdate", type="datetime", nullable=true)
     */
    public $mdate;

    /**
     * @ORM\Column(name="con_mtime", type="datetime", nullable=true)
     */
    public $mtime;

    /**
     * @ORM\Column(name="con_inserted", type="datetime", nullable=true)
     */
    public $inserted;

    /**
     * @ORM\Column(name="con_confirmed", type="datetime", nullable=true)
     */
    public $confirmed;

    /**
     * @ORM\Column(name="con_created_by", type="integer", nullable=true)
     */
    public $createdBy;

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
        $this->locale = $con_locale;
        $this->type = $con_type;
        $this->sent = $con_sent;
        $this->fullname = $con_fullname;
        $this->compagny = $con_compagny;
        $this->adress = $con_adress;
        $this->zipCode = $con_zipCode;
        $this->city = $con_city;
        $this->country = $con_country;
        $this->position = $con_position;
        $this->email = $con_email;
        $this->message = $con_message;
        $this->newsletter = $con_newsletter;
        $this->doc = $con_doc;
        $this->mdate = $con_mdate;
        $this->mtime = $con_mtime;
        $this->inserted = $con_inserted;
        $this->confirmed = $con_confirmed;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $con_locale): self
    {
        $this->locale = $con_locale;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $con_type): self
    {
        $this->type = $con_type;

        return $this;
    }

    public function getSent(): ?bool
    {
        return $this->sent;
    }

    public function setSent(bool $con_sent): self
    {
        $this->sent = $con_sent;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $con_fullname): self
    {
        $this->fullname = $con_fullname;

        return $this;
    }

    public function getCompagny(): ?string
    {
        return $this->compagny;
    }

    public function setCompagny(string $con_compagny): self
    {
        $this->compagny = $con_compagny;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $con_adress): self
    {
        $this->adress = $con_adress;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $con_zipCode): self
    {
        $this->zipCode = $con_zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $con_city): self
    {
        $this->city = $con_city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $con_country): self
    {
        $this->country = $con_country;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $con_position): self
    {
        $this->position = $con_position;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $con_email): self
    {
        $this->email = $con_email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $con_message): self
    {
        $this->message = $con_message;

        return $this;
    }

    public function isNewsletter(): ?bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(bool $con_newsletter): self
    {
        $this->newsletter = $con_newsletter;

        return $this;
    }

    public function isDoc(): ?bool
    {
        return $this->doc;
    }

    public function setDoc(bool $con_doc): self
    {
        $this->doc = $con_doc;

        return $this;
    }

    public function getMdate(): ?DateTimeInterface
    {
        return $this->mdate;
    }

    public function setMdate(DateTimeInterface $con_mdate): self
    {
        $this->mdate = $con_mdate;

        return $this;
    }

    public function getMtime(): ?DateTimeInterface
    {
        return $this->mtime;
    }

    public function setMtime(DateTimeInterface $con_mtime): self
    {
        $this->mtime = $con_mtime;

        return $this;
    }

    public function setInserted(DateTimeInterface $con_inserted): self
    {
        $this->inserted = $con_inserted;

        return $this;
    }

    public function getConfirmed(): ?DateTimeInterface
    {
        return $this->confirmed;
    }

    public function setConfirmed(DateTimeInterface $con_confirmed): self
    {
        $this->confirmed = $con_confirmed;

        return $this;
    }

}
