<?php

namespace App\Entity;

use App\Repository\RecurringRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ORM\Entity(repositoryClass=RecurringRepository::class)
 */
class Recurring
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="rct_id", type="integer", length=10, nullable=true)
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $rct_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $rct_status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $rct_timeframe;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $rct_freq;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $rct_gsd_interval;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $rct_gsd_timeframe;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $rct_ged_interval;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $rct_ged_timeframe;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $rct_type;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $rct_lowerbound;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $rct_upperbound;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $rct_step;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $rct_opend_end;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $rct_startdate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $rct_enddate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $rct_same_part;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $rct_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $rct_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $rct_deleted;

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
        parent::__construct($id,null ,new \DateTime);
        $this->rct_name = $name;
        $this->rct_status = $status;
        $this->rct_timeframe = $timeFrame;
        $this->rct_freq = $frequency;
        $this->rct_gsd_interval = $gStartDateInterval;
        $this->rct_gsd_timeframe = $gStartDateTimeFrame;
        $this->rct_ged_interval = $gEndDateInterval;
        $this->rct_ged_timeframe = $gEndDateTimeFrame;
        $this->rct_type = $type;
        $this->rct_lowerbound = $lowerbound;
        $this->rct_upperbound = $upperbound;
        $this->rct_step = $step;
        $this->rct_opend_end = $openEnd;
        $this->rct_startdate = $startdate;
        $this->rct_enddate = $enddate;
        $this->rct_same_part = $replicatePart;
        $this->rec_master_user = $masterUser;
        $this->rct_deleted = $deleted;
        $this->activities = new ArrayCollection;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRctName(): ?string
    {
        return $this->rct_name;
    }

    public function setRctName(string $rct_name): self
    {
        $this->rct_name = $rct_name;

        return $this;
    }

    public function getRctStatus(): ?int
    {
        return $this->rct_status;
    }

    public function setRctStatus(int $rct_status): self
    {
        $this->rct_status = $rct_status;

        return $this;
    }

    public function getRctTimeframe(): ?string
    {
        return $this->rct_timeframe;
    }

    public function setRctTimeframe(string $rct_timeframe): self
    {
        $this->rct_timeframe = $rct_timeframe;

        return $this;
    }

    public function getRctFreq(): ?int
    {
        return $this->rct_freq;
    }

    public function setRctFreq(int $rct_freq): self
    {
        $this->rct_freq = $rct_freq;

        return $this;
    }

    public function getRctGsdInterval(): ?int
    {
        return $this->rct_gsd_interval;
    }

    public function setRctGsdInterval(int $rct_gsd_interval): self
    {
        $this->rct_gsd_interval = $rct_gsd_interval;

        return $this;
    }

    public function getRctGsdTimeframe(): ?string
    {
        return $this->rct_gsd_timeframe;
    }

    public function setRctGsdTimeframe(string $rct_gsd_timeframe): self
    {
        $this->rct_gsd_timeframe = $rct_gsd_timeframe;

        return $this;
    }

    public function getRctGedInterval(): ?int
    {
        return $this->rct_ged_interval;
    }

    public function setRctGedInterval(int $rct_ged_interval): self
    {
        $this->rct_ged_interval = $rct_ged_interval;

        return $this;
    }

    public function getRctGedTimeframe(): ?string
    {
        return $this->rct_ged_timeframe;
    }

    public function setRctGedTimeframe(string $rct_ged_timeframe): self
    {
        $this->rct_ged_timeframe = $rct_ged_timeframe;

        return $this;
    }

    public function getRctType(): ?int
    {
        return $this->rct_type;
    }

    public function setRctType(int $rct_type): self
    {
        $this->rct_type = $rct_type;

        return $this;
    }

    public function getRctLowerbound(): ?float
    {
        return $this->rct_lowerbound;
    }

    public function setRctLowerbound(float $rct_lowerbound): self
    {
        $this->rct_lowerbound = $rct_lowerbound;

        return $this;
    }

    public function getRctUpperbound(): ?float
    {
        return $this->rct_upperbound;
    }

    public function setRctUpperbound(float $rct_upperbound): self
    {
        $this->rct_upperbound = $rct_upperbound;

        return $this;
    }

    public function getRctStep(): ?float
    {
        return $this->rct_step;
    }

    public function setRctStep(float $rct_step): self
    {
        $this->rct_step = $rct_step;

        return $this;
    }

    public function getRctOpendEnd(): ?bool
    {
        return $this->rct_opend_end;
    }

    public function setRctOpendEnd(bool $rct_opend_end): self
    {
        $this->rct_opend_end = $rct_opend_end;

        return $this;
    }

    public function getRctStartdate(): ?\DateTimeInterface
    {
        return $this->rct_startdate;
    }

    public function setRctStartdate(\DateTimeInterface $rct_startdate): self
    {
        $this->rct_startdate = $rct_startdate;

        return $this;
    }

    public function getRctEnddate(): ?\DateTimeInterface
    {
        return $this->rct_enddate;
    }

    public function setRctEnddate(\DateTimeInterface $rct_enddate): self
    {
        $this->rct_enddate = $rct_enddate;

        return $this;
    }

    public function getRctSamePart(): ?bool
    {
        return $this->rct_same_part;
    }

    public function setRctSamePart(bool $rct_same_part): self
    {
        $this->rct_same_part = $rct_same_part;

        return $this;
    }

    public function getRctCreatedBy(): ?int
    {
        return $this->rct_createdBy;
    }

    public function setRctCreatedBy(?int $rct_createdBy): self
    {
        $this->rct_createdBy = $rct_createdBy;

        return $this;
    }

    public function getRctInserted(): ?\DateTimeInterface
    {
        return $this->rct_inserted;
    }

    public function setRctInserted(\DateTimeInterface $rct_inserted): self
    {
        $this->rct_inserted = $rct_inserted;

        return $this;
    }

    public function getRctDeleted(): ?\DateTimeInterface
    {
        return $this->rct_deleted;
    }

    public function setRctDeleted(?\DateTimeInterface $rct_deleted): self
    {
        $this->rct_deleted = $rct_deleted;

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
    function addActivity(Activity $activity){

        $this->activities->add($activity);
        $activity->setRecurring($this);
        return $this;
    }

    function removeActivity(Activity $activity)
    {
        $this->activities->removeElement($activity);
        return $this;
    }

    public function getOngoingFutCurrActivities(){

        $activities = new ArrayCollection;
        //$activities = [];
        foreach($this->activities as $recurringActivity){
            if ($recurringActivity->getStatus() == 2){
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
