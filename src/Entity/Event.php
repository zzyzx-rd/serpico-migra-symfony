<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CriterionRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity()
 */
class Event extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="eve_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="eve_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="eve_priority", type="integer", nullable=true)
     */
    public $priority;

    /**
     * @ORM\Column(name="eve_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="eve_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ManyToOne(targetEntity="Activity", inversedBy="events")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id", nullable=true)
     */
    protected $activity;

    /**
     * @ManyToOne(targetEntity="Stage", inversedBy="events")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="events")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=true)
     */
    protected $organization;

    /**
     * @OneToMany(targetEntity="EventDocument", mappedBy="event", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $documents;

    /**
     * @OneToMany(targetEntity="EventComment", mappedBy="event", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $comments;

    /**
     * @ManyToOne(targetEntity="EventType")
     * @JoinColumn(name="event_type_evt_id", referencedColumnName="evt_id", nullable=true)
     */
    protected $eventType;

    /**
     * @ORM\Column(name="eve_onset_date", type="datetime", nullable=true)
     */
    public $onsetDate;

    /**
     * @ORM\Column(name="eve_expres_date", type="datetime", nullable=true)
     */
    public $expResDate;

    /**
     * @ORM\Column(name="eve_res_date", type="datetime", nullable=true)
     */
    public $resDate;

    /**
     * @ORM\Column(name="eve_inserted", type="datetime", nullable=true, options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="eve_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * Criterion constructor.
     * @param $id
     * @param $type
     * @param $priority
     * @param $name
     * @param $comment
     * @param $createdBy
     * @param $deleted
     * @param $stage
     * @param $activity
     * @param $organization
     * @param $eventType
     * @param $documents
     * @param $comments
     */
    public function __construct(
        $id = 0,
        $priority = null,
        $type = 1,
        $name = null,
        $createdBy = null,
        $onsetDate = null,
        $resDate = null,
        $expResDate = null,
        Stage $stage = null,
        Activity $activity = null,
        Organization $organization = null,
        EventType $eventType = null,
        ArrayCollection $documents = null,
        ArrayCollection $comments = null,
        $deleted = null)
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->priority = $priority;
        $this->type = $type;
        $this->name = $name;
        $this->stage = $stage;
        $this->activity = $activity;
        $this->organization = $organization;
        $this->eventType = $eventType;
        $this->resDate = $resDate;
        $this->expResDate = $expResDate;
        $this->onsetDate = $onsetDate;
        $this->documents = $documents ?: new ArrayCollection;
        $this->comments = $comments ?: new ArrayCollection;
        $this->deleted = $deleted;
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

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPrioriry(int $priority): self
    {
        $this->priority = $priority;
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

    public function setInserted(?DateTimeInterface $inserted): self
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

    /**
     * @return Stage
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * @param mixed $stage
     */
    public function setStage(Stage $stage): self
    {
        $this->stage = $stage;
        return $this;
    }

    /**
     * @return Activity
     */
    public function getActivity(): Activity
    {
        return $this->activity;
    }

    /**
     * @param mixed $activity
     */
    public function setActivity(Activity $activity): self
    {
        $this->activity = $activity;
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
    public function setOrganization(Organization $organization): self
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * @param mixed $eventType
     */
    public function setEventType(EventType $eventType): self
    {
        $this->eventType = $eventType;
        return $this;
    }

    /**
     * @return ArrayCollection|EventComment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    public function addComment(EventComment $comment): Event
    {
        $this->comments->add($comment);
        $comment->setEvent($this);
        return $this;
    }

    public function removeComment(EventComment $comment): Event
    {
        $this->comments->removeElement($comment);
        return $this;
    }

    public function getResDate(): ?DateTimeInterface
    {
        return $this->resDate;
    }

    public function setResDate($resDate): self
    {
        $this->resDate = $resDate;
        return $this;
    }

    public function getExpResDate(): ?DateTimeInterface
    {
        return $this->expResDate;
    }

    public function setExpResDate($expResDate): self
    {
        $this->expResDate = $expResDate;
        return $this;
    }

    public function getOnsetDate(): ?DateTimeInterface
    {
        return $this->onsetDate;
    }

    public function setOnsetDate($onsetDate): self
    {
        $this->onsetDate = $onsetDate;
        return $this;
    }

    /**
     * @return ArrayCollection|EventDocument[]
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    public function addDocument(EventDocument $document): Event
    {
        $this->documents->add($document);
        $document->setEvent($this);
        return $this;
    }

    public function removeDocument(EventDocument $document): Event
    {
        $this->documents->removeElement($document);
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

    public function getOnsetdateU()
    {
        return $this->onsetDate->format('U');
    }

    public function getPeriod()
    {
        $expResDate = $this->expResDate;
        if(!$expResDate){return null;}
        $onsetDate = $this->onsetDate;
        $diff = $expResDate->diff($onsetDate)->format("%s");
        return $diff;
    }

    /**
     * 
     * Function which return number of events, self excluded, which are lying within current event period +- threshold % of considered interval period 
     * @return Collection|Event[]
     */
    public function getEmbeddedEvents($period = 'y', $thresholdIntPct = 3){

        // Period interval in seconds
        switch($period){
            case 'y' :
                $intCurrYear = intval($_COOKIE['ci']);
                $intNextYear = $intCurrYear + 1;
                $sd = new DateTime("first day of january $intCurrYear");
                $ed = new DateTime("first day of january $intNextYear");
                break;
            case 't' :
                $cookieElmts = explode('/',$_COOKIE['ci']);
                $intYear = intval(end($cookieElmts));
                $intQuarter = intval(prev($cookieElmts));
                $quarterMonths = ['january', 'april', 'july', 'october'];
                $quarterStartingMonth = $quarterMonths[$intQuarter - 1];
                $quarterEndingMonth = $quarterMonths[$intQuarter % 4];
                $quarterEndingYear = $intQuarter == 4 ? $intYear + 1 : $intYear;
                $sd = new DateTime("first day of $quarterStartingMonth $intYear");
                $ed = new DateTime("first day of $quarterEndingMonth $quarterEndingYear");
                break;
            case 'w' :
                $cookieElmts = explode('/',$_COOKIE['ci']);
                $intYear = intval(end($cookieElmts));
                $intCurrWeekOffset = intval(prev($cookieElmts)) - 1;
                $intNextWeekOffset = $intCurrWeekOffset + 1;
                $sd = new DateTime("+$intCurrWeekOffset weeks january $intYear");
                $ed = new DateTime("+$intNextWeekOffset weeks january $intYear");
                break;
        }

        $period = $ed->getTimestamp() - $sd->getTimestamp();
        $sortedByPeriodEvents = $this->stage->getSortedEventsPerPeriod();
        $embeddedStages = new ArrayCollection;
        foreach($sortedByPeriodEvents as $key => $sortedByPeriodEvent){
            if($key <= $sortedByPeriodEvents->indexOf($this)){
                continue;
            } else {
                if($this->getOnsetdateU() - round($thresholdIntPct * 0.01 * $period)  < $sortedByPeriodEvent->getOnsetdateU() && $sortedByPeriodEvent->getOnsetdateU() + $sortedByPeriodEvent->getPeriod() < $this->getOnsetdateU() + $this->getPeriod() + round($thresholdIntPct * 0.01 * $period)){
                    $embeddedStages->add($sortedByPeriodEvent);
                } else {
                    break;
                }
            }
        }
        return $embeddedStages;
    }

    function nicetime($date)
    {
        if(empty($date)) {
            return "No date provided";
        }
    
        $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths         = array("60","60","24","7","4.35","12","10");
        $now             = time();
        $unix_date       = strtotime($date);
    
        // check validity of date
        if(empty($unix_date)) {   
            return "Bad date";
        }

        // is it future date or past date
        if($now > $unix_date) {   
            $difference     = $now - $unix_date;
            $tense         = "ago";
        
        } else {
            $difference     = $unix_date - $now;
            $tense         = "from now";
        }
    
        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }
    
        $difference = round($difference);
    
        if($difference != 1) {
            $periods[$j].= "s";
        }
    
        return "$difference $periods[$j] {$tense}";
    }

}
