<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="cit_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cit_abbr;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cit_fullname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cit_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cit_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cit_inserted;

    /**
     * @ManyToOne(targetEntity="State")
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
    private $firms;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCitAbbr(): ?string
    {
        return $this->cit_abbr;
    }

    public function setCitAbbr(string $cit_abbr): self
    {
        $this->cit_abbr = $cit_abbr;

        return $this;
    }

    public function getCitFullname(): ?string
    {
        return $this->cit_fullname;
    }

    public function setCitFullname(string $cit_fullname): self
    {
        $this->cit_fullname = $cit_fullname;

        return $this;
    }

    public function getCitName(): ?string
    {
        return $this->cit_name;
    }

    public function setCitName(string $cit_name): self
    {
        $this->cit_name = $cit_name;

        return $this;
    }

    public function getCitCreatedBy(): ?int
    {
        return $this->cit_createdBy;
    }

    public function setCitCreatedBy(?int $cit_createdBy): self
    {
        $this->cit_createdBy = $cit_createdBy;

        return $this;
    }

    public function getCitInserted(): ?\DateTimeInterface
    {
        return $this->cit_inserted;
    }

    public function setCitInserted(\DateTimeInterface $cit_inserted): self
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

}
