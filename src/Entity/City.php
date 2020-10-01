<?php

namespace App\Entity;

use App\Repository\CityRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository", repositoryClass=CityRepository::class)
 */
class City extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="cit_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="cit_abbr", type="string", length=255, nullable=true)
     */
    public $abbr;

    /**
     * @ORM\Column(name="cit_fullname", type="string", length=255, nullable=true)
     */
    public $fullname;

    /**
     * @ORM\Column(name="cit_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="cit_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="cit_inserted", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="State", inversedBy="cities")
     * @JoinColumn(name="state_sta_id", referencedColumnName="sta_id",nullable=true)
     */
    protected $state;

    /**
     * @ManyToOne(targetEntity="Country")
     * @JoinColumn(name="country_cou_id", referencedColumnName="cou_id",nullable=true)
     */
    protected $country;

    /**
     * @OneToMany(targetEntity="WorkerFirm", mappedBy="city",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $firms;

    /**
     * City constructor.
     * @param $id
     * @param $abbr
     * @param $fullname
     * @param $name
     * @param $createdBy
     * @param $state
     * @param $country
     * @param $firms
     */
    public function __construct(
      ?int $id = 0,
        $abbr = null,
        $fullname = null,
        $name = null ,
        $createdBy = null,
        $state = null,
        $country = null,
        $firms = null)
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->abbr = $abbr;
        $this->fullname = $fullname;
        $this->name = $name;
        $this->state = $state;
        $this->country = $country;
        $this->firms = $firms?:new ArrayCollection();
    }


    public function getAbbr(): ?string
    {
        return $this->abbr;
    }

    public function setAbbr(string $abbr): self
    {
        $this->abbr = $abbr;
        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setInserted(?DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState(State $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry(Country $country): self
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirms()
    {
        return $this->firms;
    }

    public function addFirm(WorkerFirm $firm): City
    {
        $this->firms->add($firm);
        $firm->setCity($this);
        return $this;
    }

    public function removeFirm(WorkerFirm $firm): City
    {
        $this->firms->removeElement($firm);
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

}
