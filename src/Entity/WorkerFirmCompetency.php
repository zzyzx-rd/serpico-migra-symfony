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
    public ?int $id;

    /**
     * @ORM\Column(name="wfc_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="workerFirmCompetencyInitiatives")
     * @JoinColumn(name="wfc_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\Column(name="wfc_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="WorkerFirm")
     * @JoinColumn(name="worker_firm_wfi_id", referencedColumnName="wfi_id", nullable=true)
     */
    protected $firm;

    /**
     * WorkerFirmCompetency constructor.
     * @param ?int$id
     * @param $wfc_name
     * @param $firm
     */
    public function __construct(
      ?int $id = 0,
        $wfc_name = null,
        $firm = null)
    {
        parent::__construct($id, null, new DateTime());
        $this->name = $wfc_name;
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
