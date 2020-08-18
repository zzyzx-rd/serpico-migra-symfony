<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerFirmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WorkerFirmRepository::class)
 */
class WorkerFirm extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="wfi_id", type="integer", nullable=false)
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $wfi_hq_location;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $wfi_hq_city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $wfi_hq_state;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $wfi_hq_country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $wfi_logo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $wfi_website;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $wfi_creation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $wfi_firm_type;

    /**
     * @ORM\Column(type="integer")
     */
    public $wfi_size;

    /**
     * @ORM\Column(type="integer")
     */
    public $wfi_nb_lk_followers;

    /**
     * @ORM\Column(type="integer")
     */
    public $wfi_nb_lk_employees;

    /**
     * @ORM\Column(type="boolean")
     */
    public $wfi_active;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $wfi_url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $wfi_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $wfi_common_name;

    /**
     * @ORM\Column(type="integer")
     */
    public $wfi_mail_prefix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $wfi_suffix;

    /**
     * @ORM\Column(type="integer")
     */
    public $wfi_nb_active_exp;

    /**
     * @ORM\Column(type="integer")
     */
    public $wfi_created;

    /**
     *@Column(name="wfi_creation_date", type="datetime", nullable=false)
     * @var \DateTime
     */
    public $creationDate;

    /**
     * @ORM\Column(type="integer")
     */
    public $wfi_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $wfi_inserted;

    /**
     * @OneToOne(targetEntity="WorkerFirmSector", inversedBy="firm")
     * @JoinColumn(name="worker_firm_sector_wfs_id", referencedColumnName="wfs_id",nullable=false)
     */
    public $mainSector;

    /**
     * @ManyToOne(targetEntity="City", inversedBy="firms")
     * @JoinColumn(name="city_cit_id", referencedColumnName="cit_id",nullable=false)
     */
    public $city;

    /**
     * @ManyToOne(targetEntity="State", inversedBy="firms")
     * @JoinColumn(name="state_sta_id", referencedColumnName="sta_id",nullable=false)
     */
    public $state;

    /**
     * @ManyToOne(targetEntity="Country", inversedBy="firms")
     * @JoinColumn(name="country_cou_id", referencedColumnName="cou_id",nullable=false)
     */
    public $country;

    /**
     * @OneToMany(targetEntity="WorkerExperience", mappedBy="firm", cascade={"persist", "remove"}, orphanRemoval=true)
     */
//     * @OrderBy({"active" = "DESC"})
    public $experiences;

    /**
     * @OneToMany(targetEntity="Mail", mappedBy="workerFirm", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $mails;

    /**
     * WorkerFirm constructor.
     * @param int $id
     * @param $wfi_active
     * @param $wfi_hq_city
     * @param $wfi_hq_state
     * @param $wfi_hq_country
     * @param $wfi_logo
     * @param null $creationDate
     * @param $wfi_hq_location
     * @param $wfi_website
     * @param $wfi_creation
     * @param $wfi_firm_type
     * @param $wfi_size
     * @param $wfi_nb_lk_followers
     * @param $wfi_nb_lk_employees
     * @param $wfi_url
     * @param $wfi_name
     * @param $wfi_common_name
     * @param $wfi_mail_prefix
     * @param $wfi_suffix
     * @param $wfi_nb_active_exp
     * @param $wfi_created
     * @param $wfi_createdBy
     * @param $wfi_inserted
     * @param $mainSector
     * @param $city
     * @param $state
     * @param $country
     * @param $experiences
     * @param $mails
     */
    public function __construct(
        int $id = 0,
        $wfi_active = null,
        $wfi_hq_city = null,
        $wfi_hq_state = null,
        $wfi_hq_country = null,
        $wfi_logo = null,
        $creationDate = null,
        $wfi_firm_type = null,
        $wfi_size = null,
        $wfi_nb_lk_followers = null,
        $wfi_nb_lk_employees = null,
        $wfi_url = null,
        $wfi_name = null,
        $wfi_common_name = null,
        $wfi_mail_prefix = null,
        $wfi_suffix = null,
        $wfi_nb_active_exp = null,
        $wfi_created = null,
        $wfi_createdBy = null,
        $wfi_inserted = null,
        $wfi_hq_location = null,
        $wfi_website = null,
        $wfi_creation = null,
        $mainSector = null,
        $city = null,
        $state = null,
        $country = null,
        $experiences = null,
        $mails = null)
    {
        parent::__construct($id, $wfi_createdBy, new DateTime());
        $this->creationDate = $creationDate;
        $this->wfi_hq_location = $wfi_hq_location;
        $this->wfi_hq_city = $wfi_hq_city;
        $this->wfi_hq_state = $wfi_hq_state;
        $this->wfi_hq_country = $wfi_hq_country;
        $this->wfi_logo = $wfi_logo;
        $this->wfi_website = $wfi_website;
        $this->wfi_creation = $wfi_creation;
        $this->wfi_firm_type = $wfi_firm_type;
        $this->wfi_size = $wfi_size;
        $this->wfi_nb_lk_followers = $wfi_nb_lk_followers;
        $this->wfi_nb_lk_employees = $wfi_nb_lk_employees;
        $this->wfi_active = $wfi_active;
        $this->wfi_url = $wfi_url;
        $this->wfi_name = $wfi_name;
        $this->wfi_common_name = $wfi_common_name;
        $this->wfi_mail_prefix = $wfi_mail_prefix;
        $this->wfi_suffix = $wfi_suffix;
        $this->wfi_nb_active_exp = $wfi_nb_active_exp;
        $this->wfi_created = $wfi_created;
        $this->wfi_inserted = $wfi_inserted;
        $this->mainSector = $mainSector;
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
        $this->experiences = $experiences?$experiences:new ArrayCollection();
        $this->mails = $mails?$mails:new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHQLocation(): ?string
    {
        return $this->wfi_hq_location;
    }

    public function setHQLocation(string $wfi_hq_location): self
    {
        $this->wfi_hq_location = $wfi_hq_location;

        return $this;
    }

    public function getHQCity(): ?string
    {
        return $this->wfi_hq_city;
    }

    public function setHQCity(string $wfi_hq_city): self
    {
        $this->wfi_hq_city = $wfi_hq_city;

        return $this;
    }

    public function getHQState(): ?string
    {
        return $this->wfi_hq_state;
    }

    public function setHQState(string $wfi_hq_state): self
    {
        $this->wfi_hq_state = $wfi_hq_state;

        return $this;
    }

    public function getHQCountry(): ?string
    {
        return $this->wfi_hq_country;
    }

    public function setHQCountry(string $wfi_hq_country): self
    {
        $this->wfi_hq_country = $wfi_hq_country;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->wfi_logo;
    }

    public function setLogo(string $wfi_logo): self
    {
        $this->wfi_logo = $wfi_logo;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->wfi_website;
    }

    public function setWebsite(string $wfi_website): self
    {
        $this->wfi_website = $wfi_website;

        return $this;
    }

    public function getCreation(): ?string
    {
        return $this->wfi_creation;
    }

    public function setCreation(string $wfi_creation): self
    {
        $this->wfi_creation = $wfi_creation;

        return $this;
    }

    public function getFirmType(): ?string
    {
        return $this->wfi_firm_type;
    }

    public function setFirmType(string $wfi_firm_type): self
    {
        $this->wfi_firm_type = $wfi_firm_type;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->wfi_size;
    }

    public function setSize(int $wfi_size): self
    {
        $this->wfi_size = $wfi_size;

        return $this;
    }

    public function getNbLkFollowers(): ?int
    {
        return $this->wfi_nb_lk_followers;
    }

    public function setNbLkFollowers(int $wfi_nb_lk_followers): self
    {
        $this->wfi_nb_lk_followers = $wfi_nb_lk_followers;

        return $this;
    }

    public function getNbLkEmployees(): ?int
    {
        return $this->wfi_nb_lk_employees;
    }

    public function setNbLkEmployees(int $wfi_nb_lk_employees): self
    {
        $this->wfi_nb_lk_employees = $wfi_nb_lk_employees;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->wfi_active;
    }

    public function setActive(bool $wfi_active): self
    {
        $this->wfi_active = $wfi_active;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->wfi_url;
    }

    public function setUrl(string $wfi_url): self
    {
        $this->wfi_url = $wfi_url;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->wfi_name;
    }

    public function setName(string $wfi_name): self
    {
        $this->wfi_name = $wfi_name;

        return $this;
    }

    public function getCommonName(): ?string
    {
        return $this->wfi_common_name;
    }

    public function setCommonName(string $wfi_common_name): self
    {
        $this->wfi_common_name = $wfi_common_name;

        return $this;
    }

    public function getMailPrefix(): ?int
    {
        return $this->wfi_mail_prefix;
    }

    public function setMailPrefix(int $wfi_mail_prefix): self
    {
        $this->wfi_mail_prefix = $wfi_mail_prefix;

        return $this;
    }

    public function getSuffix(): ?string
    {
        return $this->wfi_suffix;
    }

    public function setSuffix(string $wfi_suffix): self
    {
        $this->wfi_suffix = $wfi_suffix;

        return $this;
    }

    public function getNbActiveExp(): ?int
    {
        return $this->wfi_nb_active_exp;
    }

    public function setNbActiveExp(int $wfi_nb_active_exp): self
    {
        $this->wfi_nb_active_exp = $wfi_nb_active_exp;

        return $this;
    }

    public function getCreated(): ?int
    {
        return $this->wfi_created;
    }

    public function setCreated(int $wfi_created): self
    {
        $this->wfi_created = $wfi_created;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->wfi_inserted;
    }

    public function setInserted(\DateTimeInterface $wfi_inserted): self
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
    public function getActiveExperiences()
    {
        $activeExperiences = [];
        $firmExperiences = $this->experiences;
        foreach($firmExperiences as $firmExperience){
            if($firmExperience->isActive() == true){
                $activeExperiences[] = $firmExperience;
            }
        }
        return $activeExperiences;
    }

    function addExperience(WorkerExperience $experience){
        $this->experiences->add($experience);
        $experience->setFirm($this);
        return $this;
    }

    function removeExperience(WorkerExperience $experience){
        $this->experiences->removeElement($experience);
        return $this;
    }
    function removeMail(Mail $mail){
        $this->mails->removeElement($mail);
        return $this;
    }

    /**
     * @return Collection|WorkingIndividual[]
     */
    function getWorkingIndividuals(){
        $workingIndividuals = [];
        foreach($this->experiences as $experience){
            if($experience->isActive() == true){
                $workingIndividuals[] = $experience->getIndividual();
            }
        }

        $workingIndividuals = new ArrayCollection($workingIndividuals);
        $iterator =  $workingIndividuals->getIterator();

        $iterator->uasort(function ($first, $second) {
            return ($first->getExperiences()->last()->getStartDate() > $second->getExperiences()->last()->getStartDate()) ? 1 : -1;
        });

        $workingIndividuals = new ArrayCollection(iterator_to_array($iterator));
        return $workingIndividuals;
    }


    public function __toString()
    {
        return (string) $this->id;
    }


}
