<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerFirmRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Exception;

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
     * @ORM\Column(name="wfi_hq_location", type="string", length=255, nullable=true)
     */
    public $HQLocation;

    /**
     * @ORM\Column(name="wfi_hq_city", type="string", length=255, nullable=true)
     */
    public $HQCity;

    /**
     * @ORM\Column(name="wfi_hq_state", type="string", length=255, nullable=true)
     */
    public $WQState;

    /**
     * @ORM\Column(name="wfi_hq_country", type="string", length=255, nullable=true)
     */
    public $HQCountry;

    /**
     * @ORM\Column(name="wfi_logo", type="string", length=255, nullable=true)
     */
    public $logo;

    /**
     * @ORM\Column(name="wfi_website", type="string", length=255, nullable=true)
     */
    public $website;

    /**
     * @ORM\Column(name="wfi_creation", type="string", length=255, nullable=true)
     */
    public $creation;

    /**
     * @ORM\Column(name="wfi_firm_type", type="string", length=255, nullable=true)
     */
    public $firm_type;

    /**
     * @ORM\Column(name="wfi_size", type="integer", nullable=true)
     */
    public $size;

    /**
     * @ORM\Column(name="wfi_nb_lk_followers", type="integer", nullable=true)
     */
    public $bLkFollowers;

    /**
     * @ORM\Column(name="wfi_nb_lk_employees", type="integer", nullable=true)
     */
    public $nbLkEmployees;

    /**
     * @ORM\Column(name="wfi_active", type="boolean", nullable=true)
     */
    public $active;

    /**
     * @ORM\Column(name="wfi_url", type="string", length=255, nullable=true)
     */
    public $url;

    /**
     * @ORM\Column(name="wfi_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="wfi_common_name", type="string", length=255, nullable=true)
     */
    public $commonName;

    /**
     * @ORM\Column(name="wfi_mail_prefix", type="integer", nullable=true)
     */
    public $mailPrefix;

    /**
     * @ORM\Column(name="wfi_mail_prefix", type="string", length=255, nullable=true)
     */
    public $suffix;

    /**
     * @ORM\Column(name="wfi_nb_active_exp", type="integer", nullable=true)
     */
    public $nbActiveExp;

    /**
     * @ORM\Column(name="wfi_created", type="integer", nullable=true)
     */
    public $created;

    /**
     *@Column(name="wfi_creation_date", type="datetime", nullable=false)
     * @var DateTime
     */
    public $creationDate;

    /**
     * @ORM\Column(name="wfi_created_by", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="wfi_inserted", type="datetime", nullable=true)
     */
    public $inserted;

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
        $this->HQLocation = $wfi_hq_location;
        $this->HQCity = $wfi_hq_city;
        $this->WQState = $wfi_hq_state;
        $this->HQCountry = $wfi_hq_country;
        $this->logo = $wfi_logo;
        $this->website = $wfi_website;
        $this->creation = $wfi_creation;
        $this->firm_type = $wfi_firm_type;
        $this->size = $wfi_size;
        $this->bLkFollowers = $wfi_nb_lk_followers;
        $this->nbLkEmployees = $wfi_nb_lk_employees;
        $this->active = $wfi_active;
        $this->url = $wfi_url;
        $this->name = $wfi_name;
        $this->commonName = $wfi_common_name;
        $this->mailPrefix = $wfi_mail_prefix;
        $this->suffix = $wfi_suffix;
        $this->nbActiveExp = $wfi_nb_active_exp;
        $this->created = $wfi_created;
        $this->inserted = $wfi_inserted;
        $this->mainSector = $mainSector;
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
        $this->experiences = $experiences?:new ArrayCollection();
        $this->mails = $mails?:new ArrayCollection();
    }

    public function getHQLocation(): ?string
    {
        return $this->HQLocation;
    }

    public function setHQLocation(string $wfi_hq_location): self
    {
        $this->HQLocation = $wfi_hq_location;

        return $this;
    }

    public function getHQCity(): ?string
    {
        return $this->HQCity;
    }

    public function setHQCity(string $wfi_hq_city): self
    {
        $this->HQCity = $wfi_hq_city;

        return $this;
    }

    public function getHQState(): ?string
    {
        return $this->WQState;
    }

    public function setHQState(string $wfi_hq_state): self
    {
        $this->WQState = $wfi_hq_state;

        return $this;
    }

    public function getHQCountry(): ?string
    {
        return $this->HQCountry;
    }

    public function setHQCountry(string $wfi_hq_country): self
    {
        $this->HQCountry = $wfi_hq_country;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $wfi_logo): self
    {
        $this->logo = $wfi_logo;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $wfi_website): self
    {
        $this->website = $wfi_website;

        return $this;
    }

    public function getCreation(): ?string
    {
        return $this->creation;
    }

    public function setCreation(string $wfi_creation): self
    {
        $this->creation = $wfi_creation;

        return $this;
    }

    public function getFirmType(): ?string
    {
        return $this->firm_type;
    }

    public function setFirmType(string $wfi_firm_type): self
    {
        $this->firm_type = $wfi_firm_type;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $wfi_size): self
    {
        $this->size = $wfi_size;

        return $this;
    }

    public function getNbLkFollowers(): ?int
    {
        return $this->bLkFollowers;
    }

    public function setNbLkFollowers(int $wfi_nb_lk_followers): self
    {
        $this->bLkFollowers = $wfi_nb_lk_followers;

        return $this;
    }

    public function getNbLkEmployees(): ?int
    {
        return $this->nbLkEmployees;
    }

    public function setNbLkEmployees(int $wfi_nb_lk_employees): self
    {
        $this->nbLkEmployees = $wfi_nb_lk_employees;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $wfi_active): self
    {
        $this->active = $wfi_active;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $wfi_url): self
    {
        $this->url = $wfi_url;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $wfi_name): self
    {
        $this->name = $wfi_name;

        return $this;
    }

    public function getCommonName(): ?string
    {
        return $this->commonName;
    }

    public function setCommonName(string $wfi_common_name): self
    {
        $this->commonName = $wfi_common_name;

        return $this;
    }

    public function getMailPrefix(): ?int
    {
        return $this->mailPrefix;
    }

    public function setMailPrefix(int $wfi_mail_prefix): self
    {
        $this->mailPrefix = $wfi_mail_prefix;

        return $this;
    }

    public function getSuffix(): ?string
    {
        return $this->suffix;
    }

    public function setSuffix(string $wfi_suffix): self
    {
        $this->suffix = $wfi_suffix;

        return $this;
    }

    public function getNbActiveExp(): ?int
    {
        return $this->nbActiveExp;
    }

    public function setNbActiveExp(int $wfi_nb_active_exp): self
    {
        $this->nbActiveExp = $wfi_nb_active_exp;

        return $this;
    }

    public function getCreated(): ?int
    {
        return $this->created;
    }

    public function setCreated(int $wfi_created): self
    {
        $this->created = $wfi_created;

        return $this;
    }

    public function setInserted(DateTimeInterface $wfi_inserted): self
    {
        $this->inserted = $wfi_inserted;

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
    public function getActiveExperiences(): array
    {
        $activeExperiences = [];
        $firmExperiences = $this->experiences;
        foreach($firmExperiences as $firmExperience){
            if($firmExperience->isActive()){
                $activeExperiences[] = $firmExperience;
            }
        }
        return $activeExperiences;
    }

    public function addExperience(WorkerExperience $experience): WorkerFirm
    {
        $this->experiences->add($experience);
        $experience->setFirm($this);
        return $this;
    }

    public function removeExperience(WorkerExperience $experience): WorkerFirm
    {
        $this->experiences->removeElement($experience);
        return $this;
    }
    public function removeMail(Mail $mail): WorkerFirm
    {
        $this->mails->removeElement($mail);
        return $this;
    }

    /**
     * @return Collection|WorkerIndividual[]
     * @throws Exception
     */
    public function getWorkingIndividuals(){
        $workingIndividuals = [];
        foreach($this->experiences as $experience){
            if($experience->isActive()){
                $workingIndividuals[] = $experience->getIndividual();
            }
        }

        $workingIndividuals = new ArrayCollection($workingIndividuals);
        $iterator =  $workingIndividuals->getIterator();

        $iterator->uasort(static function ($first, $second) {
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
