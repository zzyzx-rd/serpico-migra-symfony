<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ZIPCodeRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ZIPCodeRepository::class)
 */
class ZIPCode extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="zip_id", type="integer", length=10, nullable=false)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="zip_code", type="integer", nullable=true)
     */
    public $code;

    /**
     * @ManyToOne(targetEntity="City", inversedBy="ZIPCodes")
     * @JoinColumn(name="city_cit_id", referencedColumnName="cit_id", nullable=false)
     */
    public City $city;

    /**
     * @OneToMany(targetEntity="WorkerFirm", mappedBy="ZIPCode",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $firms;

    /**
     * @ORM\Column(name="zip_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * ZIPCode constructor.
     * @param int|null $id
     * @param int $code
     */
    public function __construct(
        ?int $id = 0,
        $code = 0
    )
    {
        parent::__construct($id, null, new DateTime());
        $this->code = $code;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param City $city
     */
    public function setCity(City $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirms()
    {
        return $this->firms;
    }

    public function addFirm(WorkerFirm $firm): ZIPCode
    {
        $this->firms->add($firm);
        $firm->setZIPCode($this);
        return $this;
    }

    public function removeFirm(WorkerFirm $firm): ZIPCode
    {
        $this->firms->removeElement($firm);
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

}