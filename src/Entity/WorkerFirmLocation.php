<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerFirmLocationRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WorkerFirmLocationRepository::class)
 */
class WorkerFirmLocation extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="wfl_id", type="integer", nullable=false)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="wfl_hq_city", type="string", length=255, nullable=true)
     */
    public $HQCity;

    /**
     * @ORM\Column(name="wfl_hq_state", type="string", length=255, nullable=true)
     */
    public $HQState;

    /**
     * @ORM\Column(name="wfl_hq_country", type="string", length=255, nullable=true)
     */
    public $HQCountry;

    /**
     * @ORM\Column(name="wfl_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="wfl_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * WorkerFirmLocation constructor.
     * @param ?int$id
     * @param $wfl_hq_city
     * @param $wfl_hq_state
     * @param $wfl_hq_country
     * @param $wfl_createdBy
     * @param $wfl_inserted
     */
    public function __construct(
      ?int $id = 0,
        $wfl_createdBy = null,
        $wfl_inserted = null,
        $wfl_hq_city = null,
        $wfl_hq_state = null,
        $wfl_hq_country = null)
    {
        parent::__construct($id, $wfl_createdBy, new DateTime());
        $this->HQCity = $wfl_hq_city;
        $this->HQState = $wfl_hq_state;
        $this->HQCountry = $wfl_hq_country;
    }

    public function getHQCity(): ?string
    {
        return $this->HQCity;
    }

    public function setHQCity(string $HQCity): self
    {
        $this->HQCity = $HQCity;

        return $this;
    }

    public function getHQState(): ?string
    {
        return $this->HQState;
    }

    public function setHQState(string $HQState): self
    {
        $this->HQState = $HQState;

        return $this;
    }

    public function getHQCountry(): ?string
    {
        return $this->HQCountry;
    }

    public function setHQCountry(string $HQCountry): self
    {
        $this->HQCountry = $HQCountry;

        return $this;
    }

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }
}
