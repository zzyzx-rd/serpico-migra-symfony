<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerFirmRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WorkerFirmRepository::class)
 */
class WorkerFirm
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="wfi_id", type="integer", nullable=false)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wfi_hq_location;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wfi_hq_city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wfi_hq_state;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wfi_hq_country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wfi_logo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wfi_website;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wfi_creation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wfi_firm_type;

    /**
     * @ORM\Column(type="integer")
     */
    private $wfi_size;

    /**
     * @ORM\Column(type="integer")
     */
    private $wfi_nb_lk_followers;

    /**
     * @ORM\Column(type="integer")
     */
    private $wfi_nb_lk_employees;

    /**
     * @ORM\Column(type="boolean")
     */
    private $wfi_active;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wfi_url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wfi_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wfi_common_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $wfi_mail_prefix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wfi_suffix;

    /**
     * @ORM\Column(type="integer")
     */
    private $wfi_nb_active_exp;

    /**
     * @ORM\Column(type="integer")
     */
    private $wfi_created;

    /**
     * @ORM\Column(type="integer")
     */
    private $wfi_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $wfi_inserted;

    /**
     * @OneToOne(targetEntity="WorkerFirmSector")
     * @JoinColumn(name="worker_firm_sector_wfs_id", referencedColumnName="wfs_id",nullable=false)
     */
    private $mainSector;

    /**
     * @ManyToOne(targetEntity="City")
     * @JoinColumn(name="city_cit_id", referencedColumnName="cit_id",nullable=false)
     */
    private $city;

    /**
     * @ManyToOne(targetEntity="State")
     * @JoinColumn(name="state_sta_id", referencedColumnName="sta_id",nullable=false)
     */
    private $state;

    /**
     * @ManyToOne(targetEntity="Country")
     * @JoinColumn(name="country_cou_id", referencedColumnName="cou_id",nullable=false)
     */
    private $country;

    /**
     * @OneToMany(targetEntity="WorkerExperience", mappedBy="firm", cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"active" = "DESC"})
     */
    private $experiences;

    /**
     * @OneToMany(targetEntity="Mail", mappedBy="workerFirm", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $mails;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWfiHqLocation(): ?string
    {
        return $this->wfi_hq_location;
    }

    public function setWfiHqLocation(string $wfi_hq_location): self
    {
        $this->wfi_hq_location = $wfi_hq_location;

        return $this;
    }

    public function getWfiHqCity(): ?string
    {
        return $this->wfi_hq_city;
    }

    public function setWfiHqCity(string $wfi_hq_city): self
    {
        $this->wfi_hq_city = $wfi_hq_city;

        return $this;
    }

    public function getWfiHqState(): ?string
    {
        return $this->wfi_hq_state;
    }

    public function setWfiHqState(string $wfi_hq_state): self
    {
        $this->wfi_hq_state = $wfi_hq_state;

        return $this;
    }

    public function getWfiHqCountry(): ?string
    {
        return $this->wfi_hq_country;
    }

    public function setWfiHqCountry(string $wfi_hq_country): self
    {
        $this->wfi_hq_country = $wfi_hq_country;

        return $this;
    }

    public function getWfiLogo(): ?string
    {
        return $this->wfi_logo;
    }

    public function setWfiLogo(string $wfi_logo): self
    {
        $this->wfi_logo = $wfi_logo;

        return $this;
    }

    public function getWfiWebsite(): ?string
    {
        return $this->wfi_website;
    }

    public function setWfiWebsite(string $wfi_website): self
    {
        $this->wfi_website = $wfi_website;

        return $this;
    }

    public function getWfiCreation(): ?string
    {
        return $this->wfi_creation;
    }

    public function setWfiCreation(string $wfi_creation): self
    {
        $this->wfi_creation = $wfi_creation;

        return $this;
    }

    public function getWfiFirmType(): ?string
    {
        return $this->wfi_firm_type;
    }

    public function setWfiFirmType(string $wfi_firm_type): self
    {
        $this->wfi_firm_type = $wfi_firm_type;

        return $this;
    }

    public function getWfiSize(): ?int
    {
        return $this->wfi_size;
    }

    public function setWfiSize(int $wfi_size): self
    {
        $this->wfi_size = $wfi_size;

        return $this;
    }

    public function getWfiNbLkFollowers(): ?int
    {
        return $this->wfi_nb_lk_followers;
    }

    public function setWfiNbLkFollowers(int $wfi_nb_lk_followers): self
    {
        $this->wfi_nb_lk_followers = $wfi_nb_lk_followers;

        return $this;
    }

    public function getWfiNbLkEmployees(): ?int
    {
        return $this->wfi_nb_lk_employees;
    }

    public function setWfiNbLkEmployees(int $wfi_nb_lk_employees): self
    {
        $this->wfi_nb_lk_employees = $wfi_nb_lk_employees;

        return $this;
    }

    public function getWfiActive(): ?bool
    {
        return $this->wfi_active;
    }

    public function setWfiActive(bool $wfi_active): self
    {
        $this->wfi_active = $wfi_active;

        return $this;
    }

    public function getWfiUrl(): ?string
    {
        return $this->wfi_url;
    }

    public function setWfiUrl(string $wfi_url): self
    {
        $this->wfi_url = $wfi_url;

        return $this;
    }

    public function getWfiName(): ?string
    {
        return $this->wfi_name;
    }

    public function setWfiName(string $wfi_name): self
    {
        $this->wfi_name = $wfi_name;

        return $this;
    }

    public function getWfiCommonName(): ?string
    {
        return $this->wfi_common_name;
    }

    public function setWfiCommonName(string $wfi_common_name): self
    {
        $this->wfi_common_name = $wfi_common_name;

        return $this;
    }

    public function getWfiMailPrefix(): ?int
    {
        return $this->wfi_mail_prefix;
    }

    public function setWfiMailPrefix(int $wfi_mail_prefix): self
    {
        $this->wfi_mail_prefix = $wfi_mail_prefix;

        return $this;
    }

    public function getWfiSuffix(): ?string
    {
        return $this->wfi_suffix;
    }

    public function setWfiSuffix(string $wfi_suffix): self
    {
        $this->wfi_suffix = $wfi_suffix;

        return $this;
    }

    public function getWfiNbActiveExp(): ?int
    {
        return $this->wfi_nb_active_exp;
    }

    public function setWfiNbActiveExp(int $wfi_nb_active_exp): self
    {
        $this->wfi_nb_active_exp = $wfi_nb_active_exp;

        return $this;
    }

    public function getWfiCreated(): ?int
    {
        return $this->wfi_created;
    }

    public function setWfiCreated(int $wfi_created): self
    {
        $this->wfi_created = $wfi_created;

        return $this;
    }

    public function getWfiCreatedBy(): ?int
    {
        return $this->wfi_createdBy;
    }

    public function setWfiCreatedBy(int $wfi_createdBy): self
    {
        $this->wfi_createdBy = $wfi_createdBy;

        return $this;
    }

    public function getWfiInserted(): ?\DateTimeInterface
    {
        return $this->wfi_inserted;
    }

    public function setWfiInserted(\DateTimeInterface $wfi_inserted): self
    {
        $this->wfi_inserted = $wfi_inserted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMainSector()
    {
        return $this->mainSector;
    }

    /**
     * @param mixed $mainSector
     */
    public function setMainSector($mainSector): void
    {
        $this->mainSector = $mainSector;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state): void
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getExperiences()
    {
        return $this->experiences;
    }

    /**
     * @param mixed $experiences
     */
    public function setExperiences($experiences): void
    {
        $this->experiences = $experiences;
    }

    /**
     * @return mixed
     */
    public function getMails()
    {
        return $this->mails;
    }

    /**
     * @param mixed $mails
     */
    public function setMails($mails): void
    {
        $this->mails = $mails;
    }

}
