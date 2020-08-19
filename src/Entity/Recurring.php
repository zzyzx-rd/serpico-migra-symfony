<?php

namespace App\Entity;

use App\Repository\RecurringRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ORM\Entity(repositoryClass=RecurringRepository::class)
 */
class Recurring extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="rct_id", type="integer", length=10, nullable=true)
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(name="rct_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="rct_status", type="integer", nullable=true)
     */
    public $status;

    /**
     * @ORM\Column(name="rct_timeframe", type="string", length=255, nullable=true)
     */
    public $timeFrame;

    /**
     * @ORM\Column(name="rct_freq", type="integer", nullable=true)
     */
    public $frequency;

    /**
     * @ORM\Column(name="rct_gsd_interval", type="integer", nullable=true)
     */
    public $gStartDateInterval;

    /**
     * @ORM\Column(name="rct_gsd_timeframe", type="string", length=255, nullable=true)
     */
    public $gStartDateTimeFrame;

    /**
     * @ORM\Column(name="rct_ged_interval", type="integer", nullable=true)
     */
    public $gEndDateInterval;

    /**
     * @ORM\Column(name="rct_ged_timeframe", type="string", length=255, nullable=true)
     */
    public $gEndDateTimeFrame;

    /**
     * @ORM\Column(name="rct_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="rct_lowerbound", type="float", nullable=true)
     */
    public $lowerbound;

    /**
     * @ORM\Column(name="rct_upperbound", type="float", nullable=true)
     */
    public $upperbound;

    /**
     * @ORM\Column(name="rct_step", type="float", nullable=true)
     */
    public $step;

    /**
     * @ORM\Column(name="rct_opend_end", type="boolean", nullable=true)
     */
    public $openEnd;

    /**
     * @ORM\Column(name="rct_startdate", type="datetime", nullable=true)
     */
    public $startdate;

    /**
     * @ORM\Column(name="rct_enddate", type="datetime", nullable=true)
     */
    public $enddate;

    /**
     * @ORM\Column(name="rct_same_part", type="boolean", nullable=true)
     */
    public $replicatePart;

    /**
     * @ORM\Column(name="rct_created_by", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="rct_inserted", type="datetime", nullable=true)
     */
    public $inserted;

    /**
     * @ORM\Column(name="rct_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=false)
     */
    protected $organization;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="Reccuring")
     * @JoinColumn(name="rec_master_user_id", referencedColumnName="usr_id")
     */
    public $rec_master_user;
    /**
     * @var ArrayCollection
     */
    private $activities;

    public function __construct(
        $id = 0,
        $name = '',
        $status = null,
        $timeFrame = '',
        $gStartDateInterval = 0,
        $gStartDateTimeFrame = '',
        $gEndDateInterval = 0,
        $gEndDateTimeFrame = '',
        $frequency = 0,
        $type = 1,
        $lowerbound = null,
        $upperbound = null,
        $step = null,
        $openEnd = null,
        $startdate = null,
        $enddate = null,
        $replicatePart = null,
        $masterUser = null,
        $deleted = null)
    {
        parent::__construct($id,null ,new DateTime);
        $this->name = $name;
        $this->status = $status;
        $this->timeFrame = $timeFrame;
        $this->frequency = $frequency;
        $this->gStartDateInterval = $gStartDateInterval;
        $this->gStartDateTimeFrame = $gStartDateTimeFrame;
        $this->gEndDateInterval = $gEndDateInterval;
        $this->gEndDateTimeFrame = $gEndDateTimeFrame;
        $this->type = $type;
        $this->lowerbound = $lowerbound;
        $this->upperbound = $upperbound;
        $this->step = $step;
        $this->openEnd = $openEnd;
        $this->startdate = $startdate;
        $this->enddate = $enddate;
        $this->replicatePart = $replicatePart;
        $this->rec_master_user = $masterUser;
        $this->deleted = $deleted;
        $this->activities = new ArrayCollection;
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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTimeFrame(): ?string
    {
        return $this->timeFrame;
    }

    public function setTimeFrame(string $timeFrame): self
    {
        $this->timeFrame = $timeFrame;

        return $this;
    }

    public function getFrequency(): ?int
    {
        return $this->frequency;
    }

    public function setFrequency(int $frequency): self
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getGStartDateInterval(): ?int
    {
        return $this->gStartDateInterval;
    }

    public function setGStartDateInterval(int $gStartDateInterval): self
    {
        $this->gStartDateInterval = $gStartDateInterval;

        return $this;
    }

    public function getGStartDateTimeFrame(): ?string
    {
        return $this->gStartDateTimeFrame;
    }

    public function setGStartDateTimeFrame(string $gStartDateTimeFrame): self
    {
        $this->gStartDateTimeFrame = $gStartDateTimeFrame;

        return $this;
    }

    public function getGEndDateInterval(): ?int
    {
        return $this->gEndDateInterval;
    }

    public function setGEndDateInterval(int $gEndDateInterval): self
    {
        $this->gEndDateInterval = $gEndDateInterval;

        return $this;
    }

    public function getGEndDateTimeFrame(): ?string
    {
        return $this->gEndDateTimeFrame;
    }

    public function setGEndDateTimeFrame(string $gEndDateTimeFrame): self
    {
        $this->gEndDateTimeFrame = $gEndDateTimeFrame;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLowerbound(): ?float
    {
        return $this->lowerbound;
    }

    public function setLowerbound(float $lowerbound): self
    {
        $this->lowerbound = $lowerbound;

        return $this;
    }

    public function getUpperbound(): ?float
    {
        return $this->upperbound;
    }

    public function setUpperbound(float $upperbound): self
    {
        $this->upperbound = $upperbound;

        return $this;
    }

    public function getStep(): ?float
    {
        return $this->step;
    }

    public function setStep(float $step): self
    {
        $this->step = $step;

        return $this;
    }

    public function getOpenEnd(): ?bool
    {
        return $this->openEnd;
    }

    public function setOpenEnd(bool $openEnd): self
    {
        $this->openEnd = $openEnd;

        return $this;
    }

    public function getStartdate(): ?DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getEnddate(): ?DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getReplicatePart(): ?bool
    {
        return $this->replicatePart;
    }

    public function setReplicatePart(bool $replicatePart): self
    {
        $this->replicatePart = $replicatePart;

        return $this;
    }

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeInterface $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getRecMasterUser(): ?User
    {
        return $this->rec_master_user;
    }

    public function setRecMasterUser(?User $rec_master_user): self
    {
        $this->rec_master_user = $rec_master_user;

        return $this;
    }
    public function addActivity(Activity $activity): Recurring
    {

        $this->activities->add($activity);
        return $this;
    }

    public function removeActivity(Activity $activity): Recurring
    {
        $this->activities->removeElement($activity);
        return $this;
    }

    public function getOngoingFutCurrActivities(): ArrayCollection
    {

        $activities = new ArrayCollection;
        //$activities = [];
        foreach($this->activities as $recurringActivity){
            if ($recurringActivity->getStatus() === 2){
                continue;
            }
            $activities->add($recurringActivity);
            //$activities[] = $recurringActivity;
        }
        return $activities;
    }

    public function __toString()
    {
        return (string) $this->id;
    }
}
