<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TemplateStageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $stg_weight;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_period;

    /**
     * @Column(name="stg_name", type="string")
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_frequency;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_startdate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_enddate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_gstartdate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $stg_genddate;

    /**
     * @ORM\Column(type="integer")
     */
    private $stg_deadline_nbDays;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stg_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $stg_inserted;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stg_mode;

    /**
     * @ManyToOne(targetEntity="TemplateActivity")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id",nullable=false)
     */
    protected $activity;

    /**
     * @OneToMany(targetEntity="TemplateCriterion", mappedBy="stage", cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"weight" = "DESC"})
     */
    private $criteria;

    /**
     * @OneToMany(targetEntity="TemplateActivityUser", mappedBy="stage",cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"team" = "ASC"})
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $stg_master_usr;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stg_desc;

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
        $this->name = $name;
        $this->stg_weight = $stg_weight;
        $this->stg_period = $stg_period;
        $this->stg_frequency = $stg_frequency;
        $this->stg_startdate = $stg_startdate;
        $this->stg_enddate = $stg_enddate;
        $this->stg_gstartdate = $stg_gstartdate;
        $this->stg_genddate = $stg_genddate;
        $this->stg_deadline_nbDays = $stg_deadline_nbDays;
        $this->stg_inserted = $stg_inserted;
        $this->stg_mode = $stg_mode;
        $this->activity = $activity;
        $this->criteria = $criteria?$criteria:new ArrayCollection();
        $this->participants = $participants?$participants:new ArrayCollection();
        $this->stg_master_usr = $stg_master_usr;
        $this->stg_desc = $stg_desc;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStgWeight(): ?float
    {
        return $this->stg_weight;
    }

    public function setStgWeight(float $stg_weight): self
    {
        $this->stg_weight = $stg_weight;

        return $this;
    }

    public function getStgPeriod(): ?int
    {
        return $this->stg_period;
    }

    public function setStgPeriod(int $stg_period): self
    {
        $this->stg_period = $stg_period;

        return $this;
    }

    public function getStgFrequency(): ?string
    {
        return $this->stg_frequency;
    }

    public function setStgFrequency(string $stg_frequency): self
    {
        $this->stg_frequency = $stg_frequency;

        return $this;
    }

    public function getStgStartdate(): ?\DateTimeInterface
    {
        return $this->stg_startdate;
    }

    public function setStgStartdate(\DateTimeInterface $stg_startdate): self
    {
        $this->stg_startdate = $stg_startdate;

        return $this;
    }

    public function getStgEnddate(): ?\DateTimeInterface
    {
        return $this->stg_enddate;
    }

    public function setStgEnddate(\DateTimeInterface $stg_enddate): self
    {
        $this->stg_enddate = $stg_enddate;

        return $this;
    }

    public function getStgGstartdate(): ?\DateTimeInterface
    {
        return $this->stg_gstartdate;
    }

    public function setStgGstartdate(\DateTimeInterface $stg_gstartdate): self
    {
        $this->stg_gstartdate = $stg_gstartdate;

        return $this;
    }

    public function getStgGenddate(): ?\DateTimeInterface
    {
        return $this->stg_genddate;
    }

    public function setStgGenddate(\DateTimeInterface $stg_genddate): self
    {
        $this->stg_genddate = $stg_genddate;

        return $this;
    }

    public function getStgDeadlineNbDays(): ?int
    {
        return $this->stg_deadline_nbDays;
    }

    public function setStgDeadlineNbDays(int $stg_deadline_nbDays): self
    {
        $this->stg_deadline_nbDays = $stg_deadline_nbDays;

        return $this;
    }

    public function getStgCreatedBy(): ?string
    {
        return $this->stg_createdBy;
    }

    public function setStgCreatedBy(?string $stg_createdBy): self
    {
        $this->stg_createdBy = $stg_createdBy;

        return $this;
    }

    public function getStgInserted(): ?\DateTimeInterface
    {
        return $this->stg_inserted;
    }

    public function setStgInserted(?\DateTimeInterface $stg_inserted): self
    {
        $this->stg_inserted = $stg_inserted;

        return $this;
    }

    public function getStgMode(): ?int
    {
        return $this->stg_mode;
    }

    public function setStgMode(?int $stg_mode): self
    {
        $this->stg_mode = $stg_mode;

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

    public function getStgMasterUsr(): ?User
    {
        return $this->stg_master_usr;
    }

    public function setStgMasterUsr(?User $stg_master_usr): self
    {
        $this->stg_master_usr = $stg_master_usr;

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
    function addCriterion(TemplateCriterion $criterion){
        //The below line is to prevent adding a criterion already submitted (because of activeStages/stages).
        // However as stage criteria are built in advance for recurring activities, we also need to take into account this exception

        //if(!$criterion->getStage() || $criterion->getStage()->getActivity()->getRecurring()){
        $this->criteria->add($criterion);
        $criterion->setStage($this);
        return $this;
        //}
    }

    function removeCriterion(TemplateCriterion $criterion){
        $this->criteria->removeElement($criterion);
        $criterion->setStage(null);
        return $this;
    }

    function addParticipant(TemplateActivityUser $participant){

        $this->participants->add($participant);
        $participant->setStage($this);
        return $this;
    }

    function removeParticipant(TemplateActivityUser $participant){
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
        } else {
            return 0;
        }
    }
    /**
     * @return Collection|TemplateActivityUser[]
     */
    public function getUniqueParticipations()
    {
        return $this->criteria->first()->getParticipants();
    }

    function addUniqueParticipation(TemplateActivityUser $participant){
        foreach($this->criteria as $criterion){
            $criterion->addParticipant($participant);
            $participant->setCriterion($criterion)->setStage($this);
        }
        return $this;
    }

    function removeUniqueParticipation(TemplateActivityUser $participant){
        foreach($this->criteria as $criterion){
            $criterion->getParticipants()->removeElement($participant);
        }
        return $this;
    }
}
