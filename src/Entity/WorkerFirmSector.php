<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WorkerFirmSectorRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WorkerFirmSectorRepository::class)
 */
class WorkerFirmSector extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="wfs_id", type="integer", nullable=false)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="wfs_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="wfs_createdBy", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="wfs_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="Icon", inversedBy="workerFirmSectors")
     * @JoinColumn(name="icon_ico_id", referencedColumnName="ico_id",nullable=true)
     */
    protected $icon;

    /**
     * @OneToMany(targetEntity="WorkerFirm", mappedBy="mainSector")
     */
    public $firms;

    /**
     * WorkerFirmSector constructor.
     * @param ?int$id
     * @param $wfs_name
     * @param $wfs_createdBy
     * @param $icon
     * @param $firm
     */
    public function __construct(
      ?int $id = 0,
        $wfs_name = null,
        $wfs_createdBy = null,
        $icon = null)
    {
        parent::__construct($id, $wfs_createdBy, new DateTime());
        $this->name = $wfs_name;
        $this->icon = $icon;
        $this->firms = new ArrayCollection;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $wfs_name): self
    {
        $this->name = $wfs_name;

        return $this;
    }

    public function setInserted(DateTimeInterface $wfs_inserted): self
    {
        $this->inserted = $wfs_inserted;

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
     * @return ArrayCollection|WorkerFirm[]
    */
    public function getFirms()
    {
        return $this->firms;
    }

    public function addFirm(WorkerFirm $firm): self
    {
        $this->firms->add($firm);
        $firm->setMainSector($this);
        return $this;
    }

    public function removeFirm(WorkerFirm $firm): self
    {
        $this->firms->removeElement($firm);
        return $this;
    }

}
