<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TemplateStageRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TemplateStageRepository::class)
 */
class TemplateStage extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="stg_id", type="integer",nullable=false, length=10)
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(name="stg_weight", type="float", nullable=true)
     */
    public $weight;

    /**
     * @ORM\Column(name="stg_period", type="integer", nullable=true)
     */
    public $period;

    /**
     * @Column(name="stg_name", type="string", nullable=true)
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(name="stg_frequency", type="string", length=255, nullable=true)
     */
    public $frequency;

    /**
     * @ORM\Column(name="stg_startdate", type="datetime", nullable=true)
     */
    public $startdate;

    /**
     * @ORM\Column(name="stg_enddate", type="datetime", nullable=true)
     */
    public $enddate;

    /**
     * @ORM\Column(name="stg_gstartdate", type="datetime", nullable=true)
     */
    public $gstartdate;

    /**
     * @ORM\Column(name="stg_genddate", type="datetime", nullable=true)
     */
    public $genddate;

    /**
     * @ORM\Column(name="stg_deadline_nbDays", type="integer", nullable=true)
     */
    public $deadlineNbDays;

    /**
     * @ORM\Column(name="stg_created_by", type="string", length=255, nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="stg_inserted", type="datetime", nullable=true)
     */
    public $inserted;

    /**
     * @ORM\Column(name="stg_mode", type="integer", nullable=true)
     */
    public $mode;

    /**
     * @ManyToOne(targetEntity="TemplateActivity", inversedBy="stages")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id",nullable=false)
     */
    protected $activity;

    /**
     * @OneToMany(targetEntity="TemplateCriterion", mappedBy="stage", cascade={"persist", "remove"}, orphanRemoval=true)
     */
//     * @OrderBy({"weight" = "DESC"})
    public $criteria;

    /**
     * @OneToMany(targetEntity="TemplateActivityUser", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"team" = "ASC"})
     */
    public $participants;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @JoinColumn(name="stg_master_usr_id", referencedColumnName="usr_id", nullable=true)
     */
    public $master_usr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $stg_desc;

    /**
     * TemplateStage constructor.
     * @param int $id
     * @param $stg_master_usr
     * @param null $name
     * @param $stg_weight
     * @param $stg_period
     * @param $stg_frequency
     * @param $stg_startdate
     * @param $stg_enddate
     * @param $stg_gstartdate
     * @param $stg_genddate
     * @param $stg_deadline_nbDays
     * @param $stg_createdBy
     * @param $stg_inserted
     * @param $stg_mode
     * @param $activity
     * @param $criteria
     * @param $participants
     * @param $stg_desc
     */
    public function __construct(
        int $id = 0,
        $stg_master_usr = null,
        $name= null,
        $stg_mode = null,
        $stg_desc = null,
        $stg_weight = 0.0,
        $stg_period = 15,
        $stg_frequency = '0',
        $stg_startdate = null,
        $stg_enddate = null,
        $stg_gstartdate = null,
        $stg_genddate = null,
        $stg_deadline_nbDays = 3,
        $stg_createdBy = null,
        $stg_inserted = null,
        $activity = null,
        $criteria = null,
        $participants = null)
    {
        parent::__construct($id, $stg_createdBy, new DateTime());
        $this->name = $name;
        $this->weight = $stg_weight;
        $this->period = $stg_period;
        $this->frequency = $stg_frequency;
        $this->startdate = $stg_startdate;
        $this->enddate = $stg_enddate;
        $this->gstartdate = $stg_gstartdate;
        $this->genddate = $stg_genddate;
        $this->deadlineNbDays = $stg_deadline_nbDays;
        $this->inserted = $stg_inserted;
        $this->mode = $stg_mode;
        $this->activity = $activity;
        $this->criteria = $criteria?:new ArrayCollection();
        $this->participants = $participants?:new ArrayCollection();
        $this->master_usr = $stg_master_usr;
        $this->stg_desc = $stg_desc;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(int $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function setFrequency(string $frequency): self
    {
        $this->frequency = $frequency;

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

    public function getGstartdate(): ?DateTimeInterface
    {
        return $this->gstartdate;
    }

    public function setGstartdate(DateTimeInterface $gstartdate): self
    {
        $this->gstartdate = $gstartdate;

        return $this;
    }

    public function getGenddate(): ?DateTimeInterface
    {
        return $this->genddate;
    }

    public function setGenddate(DateTimeInterface $genddate): self
    {
        $this->genddate = $genddate;

        return $this;
    }

    public function getDeadlineNbDays(): ?int
    {
        return $this->deadlineNbDays;
    }

    public function setDeadlineNbDays(int $deadlineNbDays): self
    {
        $this->deadlineNbDays = $deadlineNbDays;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setInserted(?DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

        return $this;
    }

    public function getMode(): ?int
    {
        return $this->mode;
    }

    public function setMode(?int $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param mixed $activity
     */
    public function setActivity($activity): void
    {
        $this->activity = $activity;
    }

    /**
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param mixed $criteria
     */
    public function setCriteria($criteria): void
    {
        $this->criteria = $criteria;
    }

    /**
     * @return mixed
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param mixed $participants
     */
    public function setParticipants($participants): void
    {
        $this->participants = $participants;
    }

    public function getMasterUsr(): ?User
    {
        return $this->master_usr;
    }

    public function setMasterUsr(?User $master_usr): self
    {
        $this->master_usr = $master_usr;

        return $this;
    }

    public function getStgDesc(): ?string
    {
        return $this->stg_desc;
    }

    public function setStgDesc(string $stg_desc): self
    {
        $this->stg_desc = $stg_desc;

        return $this;
    }
    public function addCriterion(TemplateCriterion $criterion): TemplateStage
    {
        //The below line is to prevent adding a criterion already submitted (because of activeStages/stages).
        // However as stage criteria are built in advance for recurring activities, we also need to take into account this exception

        //if(!$criterion->getStage() || $criterion->getStage()->getActivity()->getRecurring()){
        $this->criteria->add($criterion);
        $criterion->setStage($this);
        return $this;
        //}
    }

    public function removeCriterion(TemplateCriterion $criterion): TemplateStage
    {
        $this->criteria->removeElement($criterion);
        $criterion->setStage(null);
        return $this;
    }

    public function addParticipant(TemplateActivityUser $participant): TemplateStage
    {

        $this->participants->add($participant);
        $participant->setStage($this);
        return $this;
    }

    public function removeParticipant(TemplateActivityUser $participant): TemplateStage
    {
        $this->participants->removeElement($participant);
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

    public function getGradingProgress()
    {
        $k = 0;
        $l = 0;
        if(count($this->participants) > 0){
            foreach($this->participants as $participant){
                if($participant->getStatus() >= 2){
                    $k++;
                } else {
                    $l++;
                }
            }
            return $k / ($k + $l);
        }

        return 0;
    }
    /**
     * @return Collection|TemplateActivityUser[]
     */
    public function getUniqueParticipations()
    {
        return $this->criteria->first()->getParticipants();
    }

    public function addUniqueParticipation(TemplateActivityUser $participant): TemplateStage
    {
        foreach($this->criteria as $criterion){
            $criterion->addParticipant($participant);
            $participant->setCriterion($criterion)->setStage($this);
        }
        return $this;
    }

    public function removeUniqueParticipation(TemplateActivityUser $participant): TemplateStage
    {
        foreach($this->criteria as $criterion){
            $criterion->getParticipants()->removeElement($participant);
        }
        return $this;
    }
}
