<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass=StateRepository::class)
 */
class State extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="sta_id", type="integer", nullable=false)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sta_abbr;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sta_fullname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sta_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sta_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sta_inserted;

    /**
     * @ManyToOne(targetEntity="Country")
     * @JoinColumn(name="country_cou_id", referencedColumnName="cou_id",nullable=false)
     */
    protected $country;

    /**
     * @OneToMany(targetEntity="City", mappedBy="state",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $cities;

    /**
     * @OneToMany(targetEntity="WorkerFirm", mappedBy="state",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $firms;

    /**
     * State constructor.
     * @param int $id
     * @param $sta_abbr
     * @param $sta_fullname
     * @param $sta_name
     * @param $sta_createdBy
     * @param $sta_inserted
     * @param $country
     * @param $cities
     * @param $firms
     */
    public function __construct(
        int $id = 0,
        $sta_abbr = null,
        $sta_fullname = null,
        $sta_name = null,
        $sta_createdBy = null,
        $sta_inserted = null,
        $country = null,
        $cities = null,
        $firms = null)
    {
        $this->id = $id;
        $this->sta_abbr = $sta_abbr;
        $this->sta_fullname = $sta_fullname;
        $this->sta_name = $sta_name;
        $this->sta_createdBy = $sta_createdBy;
        $this->sta_inserted = $sta_inserted;
        $this->country = $country;
        $this->cities = $cities?$cities:new ArrayCollection();
        $this->firms = $firms?$firms:new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStaAbbr(): ?string
    {
        return $this->sta_abbr;
    }

    public function setStaAbbr(string $sta_abbr): self
    {
        $this->sta_abbr = $sta_abbr;

        return $this;
    }

    public function getStaFullname(): ?string
    {
        return $this->sta_fullname;
    }

    public function setStaFullname(string $sta_fullname): self
    {
        $this->sta_fullname = $sta_fullname;

        return $this;
    }

    public function getStaName(): ?string
    {
        return $this->sta_name;
    }

    public function setStaName(string $sta_name): self
    {
        $this->sta_name = $sta_name;

        return $this;
    }

    public function getStaCreatedBy(): ?int
    {
        return $this->sta_createdBy;
    }

    public function setStaCreatedBy(?int $sta_createdBy): self
    {
        $this->sta_createdBy = $sta_createdBy;

        return $this;
    }

    public function getStaInserted(): ?\DateTimeInterface
    {
        return $this->sta_inserted;
    }

    public function setStaInserted(\DateTimeInterface $sta_inserted): self
    {
        $this->sta_inserted = $sta_inserted;

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
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * @param mixed $cities
     */
    public function setCities($cities): void
    {
        $this->cities = $cities;
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
