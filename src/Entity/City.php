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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $cit_name;

    /**
     * @ORM\Column(name="cit_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="cit_inserted", type="datetime", nullable=true)
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
     * @param $cit_abbr
     * @param $cit_fullname
     * @param $cit_name
     * @param $cit_createdBy
     * @param $cit_inserted
     * @param $state
     * @param $country
     * @param $firms
     */
    public function __construct(
      ?int $id = 0,
        $cit_abbr = null,
        $cit_fullname = null,
        $cit_name = null ,
        $cit_createdBy = null,
        $cit_inserted = null,
        $state = null,
        $country = null,
        $firms = null)
    {
        parent::__construct($id, $cit_createdBy, new DateTime());
        $this->abbr = $cit_abbr;
        $this->fullname = $cit_fullname;
        $this->cit_name = $cit_name;
        $this->state = $state;
        $this->country = $country;
        $this->firms = $firms?:new ArrayCollection();
    }


    public function getAbbr(): ?string
    {
        return $this->abbr;
    }

    public function setAbbr(string $cit_abbr): self
    {
        $this->abbr = $cit_abbr;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $cit_fullname): self
    {
        $this->fullname = $cit_fullname;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->cit_name;
    }

    public function setName(string $cit_name): self
    {
        $this->cit_name = $cit_name;

        return $this;
    }

    public function setInserted(DateTimeInterface $cit_inserted): self
    {
        $this->inserted = $cit_inserted;

        return $this;
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
    public function setState($state)
    {
        $this->state = $state;
        return $this;
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
    public function setCountry($country)
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

    /**
     * @param mixed $firms
     */
    public function setFirms($firms)
    {
        $this->firms = $firms;
        return $this;
    }
    public function addFirm(WorkerFirm $firm): City
    {
        $this->firms->add($firm);
        $firm->setState($this);
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
