<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\IconRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use IntlChar;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=IconRepository::class)
 */
class Icon extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="ico_id", type="integer", nullable=false)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $ico_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $ico_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $ico_unicode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $ico_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $ico_inserted;

    /**
     * @OneToMany(targetEntity="CriterionName", mappedBy="icon", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var Collection<CriterionName>
     */
    public $criterionNames;

    /**
     * @OneToMany(targetEntity="WorkerFirmSector", mappedBy="icon", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var Collection<WorkerFirmSector>
     */
    public $workerFirmSectors;

    /**
     * Icon constructor.
     * @param $id
     * @param $ico_type
     * @param $ico_name
     * @param $ico_unicode
     * @param $ico_createdBy
     * @param $ico_inserted
     * @param Collection $criterionNames
     * @param Collection $workerFirmSectors
     */
    public function __construct(
        $id = null,
        $ico_type = null,
        $ico_name = null,
        $ico_unicode = null,
        $ico_createdBy = null,
        $ico_inserted = null,
        Collection $criterionNames = null,
        Collection $workerFirmSectors = null)
    {
        parent::__construct($id, $ico_createdBy, new DateTime());
        $this->ico_type = $ico_type;
        $this->ico_name = $ico_name;
        $this->ico_unicode = $ico_unicode;
        $this->ico_inserted = $ico_inserted;
        $this->criterionNames = $criterionNames;
        $this->workerFirmSectors = $workerFirmSectors;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function etType(): ?string
    {
        return $this->ico_type;
    }

    public function setIcoType(string $ico_type): self
    {
        $this->ico_type = $ico_type;

        return $this;
    }

    public function etName(): ?string
    {
        return $this->ico_name;
    }

    public function setIcoName(string $ico_name): self
    {
        $this->ico_name = $ico_name;

        return $this;
    }

    public function etUnicode(): ?string
    {
        return $this->ico_unicode;
    }

    public function setIcoUnicode(string $ico_unicode): self
    {
        $this->ico_unicode = $ico_unicode;

        return $this;
    }

    public function etInserted(): ?\DateTimeInterface
    {
        return $this->ico_inserted;
    }

    public function setIcoInserted(\DateTimeInterface $ico_inserted): self
    {
        $this->ico_inserted = $ico_inserted;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCriterionNames(): Collection
    {
        return $this->criterionNames;
    }

    /**
     * @param Collection $criterionNames
     */
    public function setCriterionNames(Collection $criterionNames): void
    {
        $this->criterionNames = $criterionNames;
    }

    /**
     * @return Collection
     */
    public function getWorkerFirmSectors(): Collection
    {
        return $this->workerFirmSectors;
    }

    /**
     * @param Collection $workerFirmSectors
     */
    public function setWorkerFirmSectors(Collection $workerFirmSectors): void
    {
        $this->workerFirmSectors = $workerFirmSectors;
    }

    function addCriterionName(CriterionName $criterionName)
    {
        $criterionName->setIcon($this);
        $this->criterionNames->add($criterionName);
        return $this;
    }

    function removeCriterionName(CriterionName $criterionName)
    {
        $this->criterionNames->removeElement($criterionName);
        return $this;
    }

    function addWorkerFirmSector(WorkerFirmSector $workerFirmSector)
    {
        $workerFirmSector->setIcon($this);
        $this->workerFirmSectors->add($workerFirmSector);
        return $this;
    }

    function removeWorkerFirmSector(WorkerFirmSector $workerFirmSector)
    {
        $this->workerFirmSectors->removeElement($workerFirmSector);
        return $this;
    }

    function getChar() {
        switch ($this->ico_type) {
            case 'm': return $this->ico_name;
            default: return IntlChar::chr(hexdec($this->ico_unicode));
        }
    }

    public function __toString()
    {
        return $this->getChar();
    }
}
