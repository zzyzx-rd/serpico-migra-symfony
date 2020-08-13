<?php

namespace App\Entity;

use App\Repository\CityRepository;
use DateTime;
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
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $cit_abbr;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $cit_fullname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $cit_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $cit_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $cit_inserted;

    /**
     * @ManyToOne(targetEntity="State", inversedBy="cities")
     * @JoinColumn(name="state_sta_id", referencedColumnName="sta_id",nullable=false)
     */
    protected $state;

    /**
     * @ManyToOne(targetEntity="Country")
     * @JoinColumn(name="country_cou_id", referencedColumnName="cou_id",nullable=false)
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
        int $id = 0,
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
        $this->cit_abbr = $cit_abbr;
        $this->cit_fullname = $cit_fullname;
        $this->cit_name = $cit_name;
        $this->cit_inserted = $cit_inserted;
        $this->state = $state;
        $this->country = $country;
        $this->firms = $firms?$firms:new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAbbr(): ?string
    {
        return $this->cit_abbr;
    }

    public function setAbbr(string $cit_abbr): self
    {
        $this->cit_abbr = $cit_abbr;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->cit_fullname;
    }

    public function setFullname(string $cit_fullname): self
    {
        $this->cit_fullname = $cit_fullname;

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

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->cit_inserted;
    }

    public function setInserted(\DateTimeInterface $cit_inserted): self
    {
        $this->cit_inserted = $cit_inserted;

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
    public function getFirms()
    {
        return $this->firms;
    }

    /**
     * @param mixed $firms
     */
    public function setFirms($firms): void
    {
        $this->firms = $firms;
    }
    function addFirm(WorkerFirm $firm){
        $this->firms->add($firm);
        $firm->setState($this);
        return $this;
    }

    function removeFirm(WorkerFirm $firm){
        $this->firms->removeElement($firm);
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

}
