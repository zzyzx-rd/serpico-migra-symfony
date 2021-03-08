<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 */
class Country extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="cou_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="abbr", type="string", length=255, nullable=true)
     */
    public $abbr;
    
    /**
     * @ORM\Column(name="cou_zip_abbr", type="string", length=255, nullable=true)
     */
    public $ZIPAbbr;

    /**
     * @ORM\Column(name="cou_fullname", type="string", length=255, nullable=true)
     */
    public $fullname;

    /**
     * @ORM\Column(name="cou_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="countryInitiatives")
     * @JoinColumn(name="cou_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\Column(name="cou_inserted", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @OneToMany(targetEntity="State", mappedBy="country",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $states;

    /**
     * @OneToMany(targetEntity="WorkerFirm", mappedBy="country",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $firms;

    /**
     * Country constructor.
     * @param $id
     * @param $abbr
     * @param $ZIPAbbr
     * @param $fullname
     * @param $name
     * @param $states
     * @param $firms
     */
    public function __construct(
        $id,
        $abbr = null,
        $ZIPAbbr = null,
        $fullname = null,
        $cou_name = null,
        $states = null,
        $firms = null)
    {
        parent::__construct($id, null, new DateTime());
        $this->abbr = $abbr;
        $this->ZIPAbbr = $ZIPAbbr;
        $this->fullname = $fullname;
        $this->name = $cou_name;
        $this->states = $states?$states:new ArrayCollection();
        $this->firms = $firms?$firms:new ArrayCollection();
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
    
    public function getZIPAbbr(): ?string
    {
        return $this->ZIPAbbr;
    }

    public function setZIPAbbr(string $ZIPAbbr): self
    {
        $this->ZIPAbbr = $ZIPAbbr;
        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $cou_fullname): self
    {
        $this->fullname = $cou_fullname;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $cou_name): self
    {
        $this->name = $cou_name;

        return $this;
    }

    public function setInserted(?DateTimeInterface $cou_inserted): self
    {
        $this->inserted = $cou_inserted;

        return $this;
    }

    /**
     * @return ArrayCollection|State[]
     */
    public function getStates()
    {
        return $this->states;
    }

    public function addState(State $state): Country
    {
        $this->states->add($state);
        $state->setCountry($this);
        return $this;
    }

    public function removeState(State $state): Country
    {
        $this->states->removeElement($state);
        return $this;
    }

    /**
     * @return ArrayCollection|WorkerFirm[]
     */
    public function getFirms()
    {
        return $this->firms;
    }

    public function addFirm(WorkerFirm $firm): Country
    {
        $this->firms->add($firm);
        $firm->setCountry($this);
        return $this;
    }

    public function removeFirm(WorkerFirm $firm): Country
    {
        $this->firms->removeElement($firm);
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

}
