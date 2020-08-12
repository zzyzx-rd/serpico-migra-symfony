<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerIndividualRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WorkerIndividualRepository::class)
 */
class WorkerIndividual
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="win_id", type="integer", nullable=false)
     * @var int
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

    /**
     * @OneToMany(targetEntity="WorkerExperience", mappedBy="individual", cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"startDate" = "DESC"})
     */
    private $experiences;

    /**
     * @OneToMany(targetEntity="Mail", mappedBy="workerIndividual", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $mails;

    /**
     * WorkerIndividual constructor.
     * @param int $id
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
     * @param $win_createdBy
     * @param $win_inserted
     * @param $experiences
     * @param $mails
     */
    public function __construct(
        int $id = 0,
        $win_lk_country = null,
        $win_lk_url = null,
        $win_lk_fullName = null,
        $win_lk_male = null,
        $win_firstname = null,
        $win_lastname = null,
        $win_email = null,
        $win_gdpr = null,
        $win_lk_contacted = null,
        $win_createdBy = null,
        $win_created = null,
        $win_lk_nbConnections = null,
        $win_inserted = null,
        $experiences = null,
        $mails = null)
    {
        $this->win_lk_country = $win_lk_country;
        $this->win_lk_url = $win_lk_url;
        $this->win_lk_fullName = $win_lk_fullName;
        $this->win_lk_male = $win_lk_male;
        $this->win_created = $win_created;
        $this->win_firstname = $win_firstname;
        $this->win_lastname = $win_lastname;
        $this->win_email = $win_email;
        $this->win_gdpr = $win_gdpr;
        $this->win_lk_nbConnections = $win_lk_nbConnections;
        $this->win_lk_contacted = $win_lk_contacted;
        $this->win_inserted = $win_inserted;
        $this->experiences = $experiences = new ArrayCollection();
        $this->mails = $mails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->win_lk_country;
    }

    public function setCountry(string $win_lk_country): self
    {
        $this->win_lk_country = $win_lk_country;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->win_lk_url;
    }

    public function setUrl(string $win_lk_url): self
    {
        $this->win_lk_url = $win_lk_url;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->win_lk_fullName;
    }

    public function setFullName(string $win_lk_fullName): self
    {
        $this->win_lk_fullName = $win_lk_fullName;

        return $this;
    }

    public function getMale(): ?bool
    {
        return $this->win_lk_male;
    }

    public function setMale(bool $win_lk_male): self
    {
        $this->win_lk_male = $win_lk_male;

        return $this;
    }

    public function getCreated(): ?int
    {
        return $this->win_created;
    }

    public function setCreated(int $win_created): self
    {
        $this->win_created = $win_created;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->win_firstname;
    }

    public function setFirstname(string $win_firstname): self
    {
        $this->win_firstname = $win_firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->win_lastname;
    }

    public function setLastname(string $win_lastname): self
    {
        $this->win_lastname = $win_lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->win_email;
    }

    public function setEmail(string $win_email): self
    {
        $this->win_email = $win_email;

        return $this;
    }

    public function getGdpr(): ?\DateTimeInterface
    {
        return $this->win_gdpr;
    }

    public function setGdpr(\DateTimeInterface $win_gdpr): self
    {
        $this->win_gdpr = $win_gdpr;

        return $this;
    }

    public function getNbConnections(): ?int
    {
        return $this->win_lk_nbConnections;
    }

    public function setNbConnections(int $win_lk_nbConnections): self
    {
        $this->win_lk_nbConnections = $win_lk_nbConnections;

        return $this;
    }

    public function getContacted(): ?bool
    {
        return $this->win_lk_contacted;
    }

    public function setContacted(bool $win_lk_contacted): self
    {
        $this->win_lk_contacted = $win_lk_contacted;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->win_createdBy;
    }

    public function setCreatedBy(int $win_createdBy): self
    {
        $this->win_createdBy = $win_createdBy;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->win_inserted;
    }

    public function setInserted(\DateTimeInterface $win_inserted): self
    {
        $this->win_inserted = $win_inserted;

        return $this;
    }
    public function addExperience(WorkerExperience $experience)
    {
        $this->experiences->add($experience);
        $experience->setIndividual($this);
        return $this;
    }

    public function removeExperience(WorkerExperience $experience)
    {
        $this->experiences->removeElement($experience);
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }
}
