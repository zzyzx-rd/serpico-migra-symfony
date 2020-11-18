<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ElementUpdateRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ElementUpdateRepository::class)
 */
class ElementUpdate extends DbObject
{

    public const CREATION = 0;
    public const DELETION = -1;
    public const CHANGE = 1;

    public const EVENT_OCCURENCE_DATE = 'oDate';
    public const EVENT_EXPECTED_RESOLUTION_DATE = 'expResDate';
    public const EVENT_DOC_NAME = 'name';
    public const EVENT_DOC_CONTENT = 'content';



    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="upd_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="upd_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="upd_prop", type="string", length=255, nullable=true)
     */
    public $property;  

    /**
     * @ManyToOne(targetEntity="Department", inversedBy="updates")
     * @JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id", nullable=true)
     * @var Department
     */
    public $department;

    /**
     * @ManyToOne(targetEntity="Position", inversedBy="updates")
     * @JoinColumn(name="position_pos_id", referencedColumnName="pos_id", nullable=true)
     * @var Position
     */
    public $position;

    /**
     * @ManyToOne(targetEntity="InstitutionProcess", inversedBy="updates")
     * @JoinColumn(name="institution_process_inp_id", referencedColumnName="inp_id", nullable=true)
     * @var InstitutionProcess
     */
    public $institutionProcess;

    /**
     * @ManyToOne(targetEntity="Activity", inversedBy="updates")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id", nullable=true)
     * @var Activity
     */
    public $activity;

    /**
     * @ManyToOne(targetEntity="Stage", inversedBy="updates")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id", nullable=true)
     * @var Stage
     */
    public $stage;

    /**
     * @ManyToOne(targetEntity="Event", inversedBy="updates")
     * @JoinColumn(name="event_eve_id", referencedColumnName="eve_id", nullable=true)
     * @var Event
     */
    public $event;

    /**
     * @ManyToOne(targetEntity="EventDocument", inversedBy="updates")
     * @JoinColumn(name="event_document_evd_id", referencedColumnName="evd_id", nullable=true)
     * @var EventDocument
     */
    public $eventDocument;

    /**
     * @ManyToOne(targetEntity="EventComment", inversedBy="updates")
     * @JoinColumn(name="event_comment_evc_id", referencedColumnName="evc_id", nullable=true)
     * @var EventComment
     */
    public $eventComment;

    /**
     * @ManyToOne(targetEntity="Output", inversedBy="updates")
     * @JoinColumn(name="output_otp_id", referencedColumnName="otp_id", nullable=true)
     * @var Output
     */
    public $output;

    /**
     * @ManyToOne(targetEntity="Criterion", inversedBy="updates")
     * @JoinColumn(name="criterion_crt_id", referencedColumnName="crt_id", nullable=true)
     * @var Criterion
     */
    public $criterion;

    /**
     * @ManyToOne(targetEntity="Participation", inversedBy="updates")
     * @JoinColumn(name="participation_par_id", referencedColumnName="par_id", nullable=true)
     * @var Participation
     */
    public $participation;

    /**
     * @ManyToOne(targetEntity="Result", inversedBy="updates")
     * @JoinColumn(name="result_res_id", referencedColumnName="res_id", nullable=true)
     * @var Result
     */
    public $result;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="updates")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id", nullable=true)
     * @var User
     */
    public $user;

    /**
     * @ORM\Column(name="upd_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="upd_viewed", type="datetime", nullable=true)
     */
    public ?DateTime $viewed;
    
    /**
     * @ORM\Column(name="upd_mailed", type="datetime", nullable=true)
     */
    public ?DateTime $mailed;

    /**
     * @ORM\Column(name="upd_inserted", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * EventType constructor.
     * @param $id
     * @param $type
     */
    public function __construct(
        $id = null,
        $type = null,
        DateTime $mailed = null,
        DateTime $viewed = null)
    {
        parent::__construct($id, null, new DateTime);
        $this->type = $type;
        $this->mailed = $mailed;
        $this->viewed = $viewed;
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

    public function getProperty(): ?string
    {
        return $this->property;
    }

    public function setProperty(?string $property): self
    {
        $this->property = $property;
        return $this;
    }
    /**
     * @return Department|null
     */
    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    /**
     * @param Department $department
     */
    public function setDepartment(?Department $department): self
    {
        $this->department = $department;    
        return $this;
    }
    /**
     * @return Position|null
     */
    public function getPosition(): ?Position
    {
        return $this->position;
    }

    /**
     * @param Position $position
     */
    public function setPosition(?Position $position): self
    {
        $this->position = $position;    
        return $this;
    }
    /**
     * @return InstitutionProcess|null
     */
    public function getInstitutionProcess(): ?InstitutionProcess
    {
        return $this->institutionProcess;
    }

    /**
     * @param InstitutionProcess $institutionProcess
     */
    public function setInstitutionProcess(?InstitutionProcess $institutionProcess): self
    {
        $this->institutionProcess = $institutionProcess;    
        return $this;
    }
    /**
     * @return Activity|null
     */
    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    /**
     * @param Activity $activity
     */
    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;    
        return $this;
    }
    /**
     * @return Stage|null
     */
    public function getStage(): ?Stage
    {
        return $this->stage;
    }

    /**
     * @param Stage $stage
     */
    public function setStage(?Stage $stage): self
    {
        $this->stage = $stage;    
        return $this;
    }
    /**
     * @return Event|null
     */
    public function getEvent(): ?Event
    {
        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent(?Event $event): self
    {
        $this->event = $event;    
        return $this;
    }
    /**
     * @return EventDocument|null
     */
    public function getEventDocument(): ?EventDocument
    {
        return $this->eventDocument;
    }

    /**
     * @param EventDocument $eventDocument
     */
    public function setEventDocument(?EventDocument $eventDocument): self
    {
        $this->eventDocument = $eventDocument;    
        return $this;
    }
    /**
     * @return EventComment|null
     */
    public function getEventComment(): ?EventComment
    {
        return $this->eventComment;
    }

    /**
     * @param EventComment $eventComment
     */
    public function setEventComment(?EventComment $eventComment): self
    {
        $this->eventComment = $eventComment;    
        return $this;
    }
    /**
     * @return Output|null
     */
    public function getOutput(): ?Output
    {
        return $this->output;
    }

    /**
     * @param Output $output
     */
    public function setOutput(?Output $output): self
    {
        $this->output = $output;    
        return $this;
    }
    /**
     * @return Criterion|null
     */
    public function getCriterion(): ?Criterion
    {
        return $this->criterion;
    }

    /**
     * @param Criterion $criterion
     */
    public function setCriterion(?Criterion $criterion): self
    {
        $this->criterion = $criterion;    
        return $this;
    }
    /**
     * @return Participation|null
     */
    public function getParticipation(): ?Participation
    {
        return $this->participation;
    }

    /**
     * @param Participation $participation
     */
    public function setParticipation(?Participation $participation): self
    {
        $this->participation = $participation;    
        return $this;
    }
    /**
     * @return Result|null
     */
    public function getResult(): ?Result
    {
        return $this->result;
    }

    /**
     * @param Result $result
     */
    public function setResult(?Result $result): self
    {
        $this->result = $result;    
        return $this;
    }
    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;    
        return $this;
    }

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }

    public function getViewed(): ?DateTimeInterface
    {
        return $this->viewed;
    }

    public function setViewed(?DateTimeInterface $viewed): self
    {
        $this->viewed = $viewed;
        return $this;
    }

    public function getMailed(): ?DateTimeInterface
    {
        return $this->mailed;
    }

    public function setMailed(?DateTimeInterface $mailed): self
    {
        $this->mailed = $mailed;
        return $this;
    }

    
}
