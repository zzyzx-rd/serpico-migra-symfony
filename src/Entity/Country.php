<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use DateTime;
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
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cou_abbr;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cou_fullname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cou_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cou_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cou_inserted;

    /**
     * @OneToMany(targetEntity="State", mappedBy="country",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $states;

    /**
     * @OneToMany(targetEntity="WorkerFirm", mappedBy="country",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $firms;

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
        $this->cou_abbr = $cou_abbr;
        $this->cou_fullname = $cou_fullname;
        $this->cou_name = $cou_name;
        $this->cou_inserted = $cou_inserted;
        $this->states = $states?$states:new ArrayCollection();
        $this->firms = $firms?$firms:new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAbbr(): ?string
    {
        return $this->cou_abbr;
    }

    public function setAbbr(string $cou_abbr): self
    {
        $this->cou_abbr = $cou_abbr;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->cou_fullname;
    }

    public function setFullname(string $cou_fullname): self
    {
        $this->cou_fullname = $cou_fullname;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->cou_name;
    }

    public function setName(string $cou_name): self
    {
        $this->cou_name = $cou_name;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->cou_createdBy;
    }

    public function setCreatedBy(?int $cou_createdBy): self
    {
        $this->cou_createdBy = $cou_createdBy;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->cou_inserted;
    }

    public function setInserted(\DateTimeInterface $cou_inserted): self
    {
        $this->cou_inserted = $cou_inserted;

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
