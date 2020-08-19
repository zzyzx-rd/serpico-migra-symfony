<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerFirmCompetencyRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WorkerFirmCompetencyRepository::class)
 */
class WorkerFirmCompetency extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="wfc_id", type="integer", nullable=false)
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(name="wfc_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="wfc_created_by", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="wfc_inserted", type="datetime", nullable=true)
     */
    public $inserted;

    /**
     * @ManyToOne(targetEntity="WorkerFirm")
     * @JoinColumn(name="worker_firm_wfi_id", referencedColumnName="wfi_id",nullable=false)
     */
    protected $firm;

    /**
     * WorkerFirmCompetency constructor.
     * @param int $id
     * @param $wfc_name
     * @param $wfc_createdBy
     * @param $wfc_inserted
     * @param $firm
     */
    public function __construct(
        int $id = 0,
        $wfc_name = null,
        $wfc_createdBy = null,
        $wfc_inserted = null,
        $firm = null)
    {
        parent::__construct($id, $wfc_createdBy, new DateTime());
        $this->name = $wfc_name;
        $this->inserted = $wfc_inserted;
        $this->firm = $firm;
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
    public function getFirm()
    {
        return $this->firm;
    }

    /**
     * @param mixed $firm
     */
    public function setFirm($firm): void
    {
        $this->firm = $firm;
    }

}
