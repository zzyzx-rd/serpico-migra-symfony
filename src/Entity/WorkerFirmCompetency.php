<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerFirmCompetencyRepository;
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $wfc_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $wfc_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $wfc_inserted;

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
        $this->wfc_name = $wfc_name;
        $this->wfc_inserted = $wfc_inserted;
        $this->firm = $firm;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWfcName(): ?string
    {
        return $this->wfc_name;
    }

    public function setWfcName(string $wfc_name): self
    {
        $this->wfc_name = $wfc_name;

        return $this;
    }

    public function getWfcCreatedBy(): ?int
    {
        return $this->wfc_createdBy;
    }

    public function setWfcCreatedBy(int $wfc_createdBy): self
    {
        $this->wfc_createdBy = $wfc_createdBy;

        return $this;
    }

    public function getWfcInserted(): ?\DateTimeInterface
    {
        return $this->wfc_inserted;
    }

    public function setWfcInserted(\DateTimeInterface $wfc_inserted): self
    {
        $this->wfc_inserted = $wfc_inserted;

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
