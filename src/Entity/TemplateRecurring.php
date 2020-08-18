<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TemplateRecurringRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
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
    public $rct_open_end;

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
     * @param $rct_name
     * @param $rct_status
     * @param $rct_timeframe
     * @param $rct_freq
     * @param $rct_gsd_interval
     * @param $rct_gsd_timeframe
     * @param $rct_ged_interval
     * @param $rct_ged_timeframe
     * @param $rct_type
     * @param $rct_lowerbound
     * @param $rct_upperbound
     * @param $rct_step
     * @param $rct_open_end
     * @param $rct_startdate
     * @param $rct_enddate
     * @param $rct_same_part
     * @param $rct_createdBy
     * @param $rct_inserted
     * @param $rct_deleted
     * @param $organization
     * @param $activities
     * @param $rct_master_usr
     */
    public function __construct(
        int $id = 0,
        $rct_name = '',
        $rct_status = null,
        $rct_timeframe = '',
        $rct_gsd_interval = 0,
        $rct_gsd_timeframe = '',
        $rct_ged_interval = 0,
        $rct_ged_timeframe = '',
        $rct_freq = 0,
        $rct_type = 1,
        $rct_lowerbound = null,
        $rct_upperbound = null,
        $rct_step = null,
        $rct_open_end = null,
        $rct_startdate = null,
        $rct_enddate = null,
        $rct_same_part = null,
        $rct_master_usr = null,
        $rct_createdBy = null,
        $rct_inserted = null,
        $rct_deleted = null,
        $organization = null,
        $activities = null)
    {
        parent::__construct($id, $rct_createdBy, new DateTime());
        $this->rct_name = $rct_name;
        $this->rct_status = $rct_status;
        $this->rct_timeframe = $rct_timeframe;
        $this->rct_freq = $rct_freq;
        $this->rct_gsd_interval = $rct_gsd_interval;
        $this->rct_gsd_timeframe = $rct_gsd_timeframe;
        $this->rct_ged_interval = $rct_ged_interval;
        $this->rct_ged_timeframe = $rct_ged_timeframe;
        $this->rct_type = $rct_type;
        $this->rct_lowerbound = $rct_lowerbound;
        $this->rct_upperbound = $rct_upperbound;
        $this->rct_step = $rct_step;
        $this->rct_open_end = $rct_open_end;
        $this->rct_startdate = $rct_startdate;
        $this->rct_enddate = $rct_enddate;
        $this->rct_same_part = $rct_same_part;
        $this->rct_inserted = $rct_inserted;
        $this->rct_deleted = $rct_deleted;
        $this->organization = $organization;
        $this->activities = $activities?$activities:new ArrayCollection();
        $this->master_usr = $rct_master_usr;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->rct_name;
    }

    public function setName(string $rct_name): self
    {
        $this->rct_name = $rct_name;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->rct_status;
    }

    public function setStatus(int $rct_status): self
    {
        $this->rct_status = $rct_status;

        return $this;
    }

    public function getTimeframe(): ?string
    {
        return $this->rct_timeframe;
    }

    public function setTimeframe(string $rct_timeframe): self
    {
        $this->rct_timeframe = $rct_timeframe;

        return $this;
    }

    public function getFreq(): ?int
    {
        return $this->rct_freq;
    }

    public function setFreq(int $rct_freq): self
    {
        $this->rct_freq = $rct_freq;

        return $this;
    }

    public function getGsdInterval(): ?int
    {
        return $this->rct_gsd_interval;
    }

    public function setGsdInterval(int $rct_gsd_interval): self
    {
        $this->rct_gsd_interval = $rct_gsd_interval;

        return $this;
    }

    public function getGsdTimeframe(): ?string
    {
        return $this->rct_gsd_timeframe;
    }

    public function setGsdTimeframe(string $rct_gsd_timeframe): self
    {
        $this->rct_gsd_timeframe = $rct_gsd_timeframe;

        return $this;
    }

    public function getGedInterval(): ?int
    {
        return $this->rct_ged_interval;
    }

    public function setGedInterval(int $rct_ged_interval): self
    {
        $this->rct_ged_interval = $rct_ged_interval;

        return $this;
    }

    public function getGedTimeframe(): ?string
    {
        return $this->rct_ged_timeframe;
    }

    public function setGedTimeframe(string $rct_ged_timeframe): self
    {
        $this->rct_ged_timeframe = $rct_ged_timeframe;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->rct_type;
    }

    public function setType(int $rct_type): self
    {
        $this->rct_type = $rct_type;

        return $this;
    }

    public function getLowerbound(): ?float
    {
        return $this->rct_lowerbound;
    }

    public function setLowerbound(float $rct_lowerbound): self
    {
        $this->rct_lowerbound = $rct_lowerbound;

        return $this;
    }

    public function getUpperbound(): ?float
    {
        return $this->rct_upperbound;
    }

    public function setUpperbound(float $rct_upperbound): self
    {
        $this->rct_upperbound = $rct_upperbound;

        return $this;
    }

    public function getStep(): ?float
    {
        return $this->rct_step;
    }

    public function setStep(float $rct_step): self
    {
        $this->rct_step = $rct_step;

        return $this;
    }

    public function getOpenEnd(): ?bool
    {
        return $this->rct_open_end;
    }

    public function setOpenEnd(bool $rct_open_end): self
    {
        $this->rct_open_end = $rct_open_end;

        return $this;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->rct_startdate;
    }

    public function setStartdate(\DateTimeInterface $rct_startdate): self
    {
        $this->rct_startdate = $rct_startdate;

        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->rct_enddate;
    }

    public function setEnddate(\DateTimeInterface $rct_enddate): self
    {
        $this->rct_enddate = $rct_enddate;

        return $this;
    }

    public function getSamePart(): ?bool
    {
        return $this->rct_same_part;
    }

    public function setSamePart(bool $rct_same_part): self
    {
        $this->rct_same_part = $rct_same_part;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->rct_inserted;
    }

    public function setInserted(\DateTimeInterface $rct_inserted): self
    {
        $this->rct_inserted = $rct_inserted;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->rct_deleted;
    }

    public function setDeleted(\DateTimeInterface $rct_deleted): self
    {
        $this->rct_deleted = $rct_deleted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrganization()
    {
        return $this->organization;
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
    function addActivity(TemplateActivity $activity){

        $this->activities->add($activity);
        $activity->setRecurring($this);
        return $this;
    }

    function removeActivity(TemplateActivity $activity){
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
