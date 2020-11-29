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
 * @ORM\Entity(repositoryClass=UserMasterRepository::class)
 */
class UserMaster extends DbObject
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="usm_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="usm_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="userMasters")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=true)
     * @var Organization
     */
    public $organization; 

    /**
     * @ManyToOne(targetEntity="Department", inversedBy="userMasters")
     * @JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id", nullable=true)
     * @var Department
     */
    public $department;

    /**
     * @ManyToOne(targetEntity="Position", inversedBy="userMasters")
     * @JoinColumn(name="position_pos_id", referencedColumnName="pos_id", nullable=true)
     * @var Position
     */
    public $position;

    /**
     * @ManyToOne(targetEntity="InstitutionProcess", inversedBy="userMasters")
     * @JoinColumn(name="institution_process_inp_id", referencedColumnName="inp_id", nullable=true)
     * @var InstitutionProcess
     */
    public $institutionProcess;

    /**
     * @ManyToOne(targetEntity="Activity", inversedBy="userMasters")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id", nullable=true)
     * @var Activity
     */
    public $activity;

    /**
     * @ManyToOne(targetEntity="Stage", inversedBy="userMasters")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id", nullable=true)
     * @var Stage
     */
    public $stage;

    /**
     * @ManyToOne(targetEntity="Event", inversedBy="userMasters")
     * @JoinColumn(name="event_eve_id", referencedColumnName="eve_id", nullable=true)
     * @var Event
     */
    public $event;

    /**
     * @ManyToOne(targetEntity="Output", inversedBy="userMasters")
     * @JoinColumn(name="output_otp_id", referencedColumnName="otp_id", nullable=true)
     * @var Output
     */
    public $output;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="masterings")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id", nullable=true)
     * @var User
     */
    public $user;

    /**
     * @ORM\Column(name="usm_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="usm_inserted", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * EventType constructor.
     * @param $id
     * @param $type
     */
    public function __construct(
        $id = null,
        $type = null)
    {
        parent::__construct($id, null, new DateTime);
        $this->type = $type;
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

    
    /**
     * @return Organization|null
     */
    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    /**
     * @param Organization $organization
     */
    public function setOrganization(?Organization $organization): self
    {
        $this->organization = $organization;    
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

}
