<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TemplateRecurringRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TemplateRecurringRepository::class)
 */
class TemplateRecurring extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="rct_id", type="integer", length=10, nullable=true)
     * @var int
     */
    public $id;

    /**
     * @Column(name="rct_name", type="string")
     * @var string
     */
    protected $name;
    /**
     * @Column(name="rct_status", type="integer")
     * @var int
     */
    protected $status;
    /**
     * @Column(name="rct_timeframe", type="string")
     * @var string
     */
    protected $timeFrame;
    /**
     * @Column(name="rct_freq", type="integer")
     * @var int
     */
    protected $frequency;
    /**
     * @Column(name="rct_gsd_interval", type="integer")
     * @var int
     */
    protected $gStartDateInterval;
    /**
     * @Column(name="rct_gsd_timeframe", type="string")
     * @var string
     */
    protected $gStartDateTimeFrame;
    /**
     * @Column(name="rct_ged_interval", type="integer")
     * @var int
     */
    protected $gEndDateInterval;
    /**
     * @Column(name="rct_ged_timeframe", type="string")
     * @var string
     */
    protected $gEndDateTimeFrame;
    /**
     * @Column(name="rct_type", type="integer")
     * @var int
     */
    protected $type;
    /**
     * @Column(name="rct_lowerbound", type="float")
     * @var float
     */
    protected $lowerbound;
    /**
     * @Column(name="rct_upperbound", type="float")
     * @var float
     */
    protected $upperbound;
    /**
     * @Column(name="rct_step", type="float")
     * @var float
     */
    protected $step;
    /**
     * @Column(name="rct_open_end", type="boolean")
     * @var bool
     */
    protected $openEnd;
    /**
     * @Column(name="rct_startdate", type="datetime")
     * @var \DateTime
     */
    protected $startdate;
    /**
     * @Column(name="rct_enddate", type="datetime")
     * @var \DateTime
     */
    protected $enddate;
    /**
     * @Column(name="rct_same_part", type="boolean")
     * @var bool
     */
    protected $replicatePart;
    //AAAA
    /**
     * @Column(name="rct_master_usr_id", type="integer")
     * @var int
     */
    protected $masterUserId;
    /**
     * @Column(name="rct_createdBy", type="integer")
     * @var int
     */
    protected $createdBy;
    /**
     * @Column(name="rct_inserted", type="datetime")
     * @var \DateTime
     */
    protected $inserted;
    /**
     * @Column(name="rct_deleted", type="datetime")
     * @var \DateTime
     */
    protected $deleted;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=false)
     */
    protected $organization;

    /**
     * @OneToMany(targetEntity="TemplateActivity", mappedBy="recurring", cascade={"persist", "remove"},orphanRemoval=true)
     */
    //     * @OrderBy({"startdate" = "ASC"})
    public $activities;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @JoinColumn(name="rct_master_usr_id", referencedColumnName="usr_id")
     */
    public $master_usr;

    /**
     * TemplateRecurring constructor.
     * @param int $id
     * @param string $name
     * @param null $status
     * @param string $timeFrame
     * @param int $gStartDateInterval
     * @param string $gStartDateTimeFrame
     * @param int $gEndDateInterval
     * @param string $gEndDateTimeFrame
     * @param int $frequency
     * @param int $type
     * @param null $lowerbound
     * @param null $upperbound
     * @param null $step
     * @param null $openEnd
     * @param null $startdate
     * @param null $enddate
     * @param null $deleted
     * @param null $replicatePart
     * @param $rct_master_usr
     * @param $rct_createdBy
     * @param $organization
     * @param $activities
     */
    public function __construct(
        int $id = 0,
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
        $deleted = null,
        $replicatePart = null,
        $rct_master_usr = null,
        $rct_createdBy = null,
        $organization = null,
        $activities = null)
    {
        parent::__construct($id, $rct_createdBy, new DateTime());
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
        $this->deleted = $deleted;
        $this->organization = $organization;
        $this->activities = $activities?:new ArrayCollection();
        $this->master_usr = $rct_master_usr;
    }




    /**
     * @return mixed
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return TemplateRecurring
     */
    public function setName(string $name): TemplateRecurring
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return TemplateRecurring
     */
    public function setStatus(int $status): TemplateRecurring
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeFrame(): string
    {
        return $this->timeFrame;
    }

    /**
     * @param string $timeFrame
     * @return TemplateRecurring
     */
    public function setTimeFrame(string $timeFrame): TemplateRecurring
    {
        $this->timeFrame = $timeFrame;
        return $this;
    }

    /**
     * @return int
     */
    public function getFrequency(): int
    {
        return $this->frequency;
    }

    /**
     * @param int $frequency
     * @return TemplateRecurring
     */
    public function setFrequency(int $frequency): TemplateRecurring
    {
        $this->frequency = $frequency;
        return $this;
    }

    /**
     * @return int
     */
    public function getGStartDateInterval(): int
    {
        return $this->gStartDateInterval;
    }

    /**
     * @param int $gStartDateInterval
     * @return TemplateRecurring
     */
    public function setGStartDateInterval(int $gStartDateInterval): TemplateRecurring
    {
        $this->gStartDateInterval = $gStartDateInterval;
        return $this;
    }

    /**
     * @return string
     */
    public function getGStartDateTimeFrame(): string
    {
        return $this->gStartDateTimeFrame;
    }

    /**
     * @param string $gStartDateTimeFrame
     * @return TemplateRecurring
     */
    public function setGStartDateTimeFrame(string $gStartDateTimeFrame): TemplateRecurring
    {
        $this->gStartDateTimeFrame = $gStartDateTimeFrame;
        return $this;
    }

    /**
     * @return int
     */
    public function getGEndDateInterval(): int
    {
        return $this->gEndDateInterval;
    }

    /**
     * @param int $gEndDateInterval
     * @return TemplateRecurring
     */
    public function setGEndDateInterval(int $gEndDateInterval): TemplateRecurring
    {
        $this->gEndDateInterval = $gEndDateInterval;
        return $this;
    }

    /**
     * @return string
     */
    public function getGEndDateTimeFrame(): string
    {
        return $this->gEndDateTimeFrame;
    }

    /**
     * @param string $gEndDateTimeFrame
     * @return TemplateRecurring
     */
    public function setGEndDateTimeFrame(string $gEndDateTimeFrame): TemplateRecurring
    {
        $this->gEndDateTimeFrame = $gEndDateTimeFrame;
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return TemplateRecurring
     */
    public function setType(int $type): TemplateRecurring
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return float
     */
    public function getLowerbound(): float
    {
        return $this->lowerbound;
    }

    /**
     * @param float $lowerbound
     * @return TemplateRecurring
     */
    public function setLowerbound(float $lowerbound): TemplateRecurring
    {
        $this->lowerbound = $lowerbound;
        return $this;
    }

    /**
     * @return float
     */
    public function getUpperbound(): float
    {
        return $this->upperbound;
    }

    /**
     * @param float $upperbound
     * @return TemplateRecurring
     */
    public function setUpperbound(float $upperbound): TemplateRecurring
    {
        $this->upperbound = $upperbound;
        return $this;
    }

    /**
     * @return float
     */
    public function getStep(): float
    {
        return $this->step;
    }

    /**
     * @param float $step
     * @return TemplateRecurring
     */
    public function setStep(float $step): TemplateRecurring
    {
        $this->step = $step;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOpenEnd(): bool
    {
        return $this->openEnd;
    }

    /**
     * @param bool $openEnd
     * @return TemplateRecurring
     */
    public function setOpenEnd(bool $openEnd): TemplateRecurring
    {
        $this->openEnd = $openEnd;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getStartdate(): DateTime
    {
        return $this->startdate;
    }

    /**
     * @param DateTime $startdate
     * @return TemplateRecurring
     */
    public function setStartdate(DateTime $startdate): TemplateRecurring
    {
        $this->startdate = $startdate;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEnddate(): DateTime
    {
        return $this->enddate;
    }

    /**
     * @param DateTime $enddate
     * @return TemplateRecurring
     */
    public function setEnddate(DateTime $enddate): TemplateRecurring
    {
        $this->enddate = $enddate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isReplicatePart(): bool
    {
        return $this->replicatePart;
    }

    /**
     * @param bool $replicatePart
     * @return TemplateRecurring
     */
    public function setReplicatePart(bool $replicatePart): TemplateRecurring
    {
        $this->replicatePart = $replicatePart;
        return $this;
    }

    /**
     * @return int
     */
    public function getMasterUserId(): int
    {
        return $this->masterUserId;
    }

    /**
     * @param int $masterUserId
     * @return TemplateRecurring
     */
    public function setMasterUserId(int $masterUserId): TemplateRecurring
    {
        $this->masterUserId = $masterUserId;
        return $this;
    }

    /**
     * @param DateTime $inserted
     * @return TemplateRecurring
     */
    public function setInserted(DateTime $inserted): TemplateRecurring
    {
        $this->inserted = $inserted;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDeleted(): DateTime
    {
        return $this->deleted;
    }

    /**
     * @param DateTime $deleted
     * @return TemplateRecurring
     */
    public function setDeleted(DateTime $deleted): TemplateRecurring
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @param mixed $organization
     */
    public function setOrganization($organization): void
    {
        $this->organization = $organization;
    }

    /**
     * @return mixed
     */
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * @param mixed $activities
     */
    public function setActivities($activities): void
    {
        $this->activities = $activities;
    }

    public function getMasterUsr(): ?User
    {
        return $this->master_usr;
    }

    public function setMasterUsr(?User $rct_master_usr): self
    {
        $this->master_usr = $rct_master_usr;

        return $this;
    }
    public function addActivity(TemplateActivity $activity): TemplateRecurring
    {

        $this->activities->add($activity);
        $activity->setRecurring($this);
        return $this;
    }

    public function removeActivity(TemplateActivity $activity): TemplateRecurring
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
