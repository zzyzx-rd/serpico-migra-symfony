<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\IconRepository;
use DateTime;
use DateTimeInterface;
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
    protected ?int $id;

    /**
     * @ORM\Column(name="ico_type", type="string", length=255, nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="ico_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="ico_unicode", type="string", length=255, nullable=true)
     */
    public $unicode;

    /**
     * @ORM\Column(name="ico_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="ico_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * @OneToMany(targetEntity="CriterionName", mappedBy="icon", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var Collec
     * tion<CriterionName>
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
        $this->type = $ico_type;
        $this->name = $ico_name;
        $this->unicode = $ico_unicode;
        $this->criterionNames = $criterionNames;
        $this->workerFirmSectors = $workerFirmSectors;
    }


    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getUnicode(): ?string
    {
        return $this->unicode;
    }

    public function setUnicode(string $unicode): self
    {
        $this->unicode = $unicode;

        return $this;
    }

    public function getInserted(): ?DateTimeInterface
    {
        return $this->inserted;
    }

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCriterionNames()
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
    public function getWorkerFirmSectors()
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

    public function addCriterionName(CriterionName $criterionName): Icon
    {
        $criterionName->setIcon($this);
        $this->criterionNames->add($criterionName);
        return $this;
    }

    public function removeCriterionName(CriterionName $criterionName): Icon
    {
        $this->criterionNames->removeElement($criterionName);
        return $this;
    }

    public function addWorkerFirmSector(WorkerFirmSector $workerFirmSector): Icon
    {
        $workerFirmSector->setIcon($this);
        $this->workerFirmSectors->add($workerFirmSector);
        return $this;
    }

    public function removeWorkerFirmSector(WorkerFirmSector $workerFirmSector): Icon
    {
        $this->workerFirmSectors->removeElement($workerFirmSector);
        return $this;
    }

    public function getChar() {
        $i = $this->type;
        if ($i === 'm') {
            return $this->name;
        }

        return IntlChar::chr(hexdec($this->unicode));
    }

    public function __toString()
    {
        return $this->getChar();
    }
}
