<?php

namespace App\Entity;

use App\Repository\StateRepository;
use DateTime;
use DateTimeInterface;
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
    public ?int $id;

    /**
     * @ORM\Column(name="sta_abbr", type="string", length=255, nullable=true)
     */
    public $abbr;

    /**
     * @ORM\Column(name="sta_fullname", type="string", length=255, nullable=true)
     */
    public $fullname;

    /**
     * @ORM\Column(name="sta_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="sta_createdBy", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="sta_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="Country", inversedBy="states")
     * @JoinColumn(name="country_cou_id", referencedColumnName="cou_id",nullable=true)
     */
    protected $country;

    /**
     * @OneToMany(targetEntity="City", mappedBy="state",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $cities;

    /**
     * @OneToMany(targetEntity="WorkerFirm", mappedBy="state",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $firms;

    /**
     * State constructor.
     * @param ?int$id
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
      ?int $id = 0,
        $sta_abbr = null,
        $sta_fullname = null,
        $sta_name = null,
        $sta_createdBy = null,
        $sta_inserted = null,
        $country = null,
        $cities = null,
        $firms = null)
    {
        parent::__construct($id,$sta_createdBy , new DateTime());
        $this->abbr = $sta_abbr;
        $this->fullname = $sta_fullname;
        $this->name = $sta_name;
        $this->country = $country;
        $this->cities = $cities?:new ArrayCollection();
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

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

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
        $this->country = $country; return $this;
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
    public function setCities($cities)
    {
        $this->cities = $cities; return $this;
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
        $this->firms = $firms; return $this;
    }
    public function addFirm(WorkerFirm $firm): State
    {
        $this->firms->add($firm);
        $firm->setState($this);
        return $this;
    }

    public function removeFirm(WorkerFirm $firm): State
    {
        $this->firms->removeElement($firm);
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

}
