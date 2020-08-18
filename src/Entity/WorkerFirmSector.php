<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerFirmSectorRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WorkerFirmSectorRepository::class)
 */
class WorkerFirmSector
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="wfs_id", type="integer", nullable=false)
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $wfs_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $wfs_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $wfs_inserted;

    /**
     * @ManyToOne(targetEntity="Icon", inversedBy="workerFirmSectors")
     * @JoinColumn(name="icon_ico_id", referencedColumnName="ico_id",nullable=false)
     */
    protected $icon;

    /**
     * @OneToOne(targetEntity="WorkerFirm", mappedBy="mainSector")
     */
    public $firm;

    /**
     * WorkerFirmSector constructor.
     * @param int $id
     * @param $wfs_name
     * @param $wfs_createdBy
     * @param $wfs_inserted
     * @param $icon
     * @param $firm
     */
    public function __construct(
        int $id = 0,
        $wfs_name = null,
        $wfs_createdBy = null,
        $wfs_inserted = null,
        $icon = null,
        $firm = null)
    {
        parent::__construct($id, $wfs_createdBy, new DateTime());
        $this->wfs_name = $wfs_name;
        $this->wfs_inserted = $wfs_inserted;
        $this->icon = $icon;
        $this->firm = $firm;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->wfs_name;
    }

    public function setName(string $wfs_name): self
    {
        $this->wfs_name = $wfs_name;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->wfs_inserted;
    }

    public function setInserted(\DateTimeInterface $wfs_inserted): self
    {
        $this->wfs_inserted = $wfs_inserted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon): void
    {
        $this->icon = $icon;
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
