<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

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
    public $id;

    /**
     * @ORM\Column(name="cou_abbr", type="string", length=255, nullable=true)
     */
    public $abbr;

    /**
     * @ORM\Column(name="cou_fullname", type="string", length=255, nullable=true)
     */
    public $fullname;

    /**
     * @ORM\Column(name="cou_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="cou_created_by", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="cou_inserted", type="datetime", nullable=true)
     */
    public $inserted;

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
     * @param $cou_abbr
     * @param $cou_fullname
     * @param $cou_name
     * @param $cou_createdBy
     * @param $cou_inserted
     * @param $states
     * @param $firms
     */
    public function __construct(
        $id,
        $cou_abbr = null,
        $cou_fullname = null,
        $cou_name = null,
        $cou_createdBy= null,
        $cou_inserted = null,
        $states = null,
        $firms = null)
    {
        parent::__construct($id, $cou_createdBy, new DateTime());
        $this->abbr = $cou_abbr;
        $this->fullname = $cou_fullname;
        $this->name = $cou_name;
        $this->inserted = $cou_inserted;
        $this->states = $states?$states:new ArrayCollection();
        $this->firms = $firms?$firms:new ArrayCollection();
    }


    public function getAbbr(): ?string
    {
        return $this->abbr;
    }

    public function setAbbr(string $cou_abbr): self
    {
        $this->abbr = $cou_abbr;

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

    public function setInserted(DateTimeInterface $cou_inserted): self
    {
        $this->inserted = $cou_inserted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * @param mixed $states
     */
    public function setStates($states): void
    {
        $this->states = $states;
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
    public function addFirm(WorkerFirm $firm): Country
    {
        $this->firms->add($firm);
        $firm->setState($this);
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
