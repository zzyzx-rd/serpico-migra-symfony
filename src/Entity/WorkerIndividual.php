<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerIndividualRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WorkerIndividualRepository::class)
 */
class WorkerIndividual extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="win_id", type="integer", nullable=false)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="win_lk_country", type="string", length=10, nullable=true)
     */
    public $country;

    /**
     * @ORM\Column(name="win_lk_url", type="string", length=255, nullable=true)
     */
    public $url;

    /**
     * @ORM\Column(name="win_lk_fullName", type="string", length=255, nullable=true)
     */
    public $fullName;

    /**
     * @ORM\Column(name="win_lk_male", type="boolean", nullable=true)
     */
    public $male;

    /**
     * @ORM\Column(name="win_created", type="integer", nullable=true)
     */
    public $created;

    /**
     * @ORM\Column(name="win_firstname", type="string", length=255, nullable=true)
     */
    public $firstname;

    /**
     * @ORM\Column(name="win_lastname", type="string", length=255, nullable=true)
     */
    public $lastname;

    /**
     * @ORM\Column(name="win_email", type="string", length=255, nullable=true)
     */
    public $email;

    /**
     * @ORM\Column(name="win_gdpr", type="datetime", nullable=true)
     */
    public $gdpr;

    /**
     * @ORM\Column(name="win_lk_nbConnections", type="integer", nullable=true)
     */
    public $nbConnections;

    /**
     * @ORM\Column(name="win_lk_contacted", type="boolean", nullable=true)
     */
    public $contacted;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="workerIndividualInitiatives")
     * @JoinColumn(name="win_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\Column(name="win_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @OneToMany(targetEntity="WorkerExperience", mappedBy="individual", cascade={"persist", "remove"}, orphanRemoval=true)
     */
//     * @OrderBy({"startDate" = "DESC"})
    public $experiences;

    /**
     * @OneToMany(targetEntity="Mail", mappedBy="workerIndividual", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $mails;

    /**
     * WorkerIndividual constructor.
     * @param ?int$id
     * @param $win_lk_country
     * @param $win_lk_url
     * @param $win_lk_fullName
     * @param $win_lk_male
     * @param $win_created
     * @param $win_firstname
     * @param $win_lastname
     * @param $win_email
     * @param $win_gdpr
     * @param $win_lk_nbConnections
     * @param $win_lk_contacted
     * @param $experiences
     * @param $mails
     */
    public function __construct(
      ?int $id = 0,
        $win_lk_country = null,
        $win_lk_url = null,
        $win_lk_fullName = null,
        $win_lk_male = null,
        $win_firstname = null,
        $win_lastname = null,
        $win_email = null,
        $win_gdpr = null,
        $win_lk_contacted = null,
        $win_created = null,
        $win_lk_nbConnections = null,
        $experiences = null,
        $mails = null)
    {
        parent::__construct($id, null, new DateTime());
        $this->country = $win_lk_country;
        $this->url = $win_lk_url;
        $this->fullName = $win_lk_fullName;
        $this->male = $win_lk_male;
        $this->created = $win_created;
        $this->firstname = $win_firstname;
        $this->lastname = $win_lastname;
        $this->email = $win_email;
        $this->gdpr = $win_gdpr;
        $this->nbConnections = $win_lk_nbConnections;
        $this->contacted = $win_lk_contacted;
        $this->experiences = $experiences?$experiences: new ArrayCollection();
        $this->mails = $mails?$mails: new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $win_lk_country): self
    {
        $this->country = $win_lk_country;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $win_lk_url): self
    {
        $this->url = $win_lk_url;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $win_lk_fullName): self
    {
        $this->fullName = $win_lk_fullName;

        return $this;
    }

    public function getMale(): ?bool
    {
        return $this->male;
    }

    public function setMale(bool $win_lk_male): self
    {
        $this->male = $win_lk_male;

        return $this;
    }

    public function getCreated(): ?int
    {
        return $this->created;
    }

    public function setCreated(int $win_created): self
    {
        $this->created = $win_created;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $win_firstname): self
    {
        $this->firstname = $win_firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $win_lastname): self
    {
        $this->lastname = $win_lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $win_email): self
    {
        $this->email = $win_email;

        return $this;
    }

    public function getGdpr(): ?DateTimeInterface
    {
        return $this->gdpr;
    }

    public function setGdpr(DateTimeInterface $win_gdpr): self
    {
        $this->gdpr = $win_gdpr;

        return $this;
    }

    public function getNbConnections(): ?int
    {
        return $this->nbConnections;
    }

    public function setNbConnections(int $win_lk_nbConnections): self
    {
        $this->nbConnections = $win_lk_nbConnections;

        return $this;
    }

    public function getContacted(): ?bool
    {
        return $this->contacted;
    }

    public function setContacted(bool $win_lk_contacted): self
    {
        $this->contacted = $win_lk_contacted;

        return $this;
    }

    public function setInserted(DateTimeInterface $win_inserted): self
    {
        $this->inserted = $win_inserted;

        return $this;
    }
    public function addExperience(WorkerExperience $experience): WorkerIndividual
    {
        $this->experiences->add($experience);
        $experience->setIndividual($this);
        return $this;
    }

    public function removeExperience(WorkerExperience $experience): WorkerIndividual
    {
        $this->experiences->removeElement($experience);
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }
}
