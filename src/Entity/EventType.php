<?php

namespace App\Entity;

use App\Repository\EventTypeRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity(repositoryClass=EventTypeRepository::class)
 */
class EventType extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="evt_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="evt_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="evt_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="evt_inserted", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="Icon", inversedBy="eventTypes")
     * @JoinColumn(name="icon_ico_id", referencedColumnName="ico_id",nullable=true)
     * @var Icon
     */
    protected $icon;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="eventTypes")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=true)
     * @var Organization
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="EventGroup", inversedBy="eventTypes")
     * @JoinColumn(name="event_group_evg_id", referencedColumnName="evg_id", nullable=true)
     * @var EventGroup
     */
    protected $eventGroup;

    /**
     * @ManyToOne(targetEntity="EventName", inversedBy="eventTypes")
     * @JoinColumn(name="event_name_evn_id", referencedColumnName="evn_id", nullable=true)
     * @var EventName
     */
    protected $eName;

    /**
     * EventType constructor.
     * @param $id
     * @param $type
     * @param $unit
     * @param $createdBy
     * @param Icon $icon
     * @param Organization $organization
     * @param EventGroup $eventGroup
     * @param EventName $eName
     */
    public function __construct(
        $id = null,
        $type = null,
        $createdBy = null,
        Icon $icon = null,
        Organization $organization = null,
        EventGroup $eventGroup = null,
        EventName $eName = null)
    {
        parent::__construct($id, $createdBy, new DateTime);
        $this->type = $type;
        $this->eName = $eName;
        $this->icon = $icon;
        $this->organization = $organization;
        $this->eventGroup = $eventGroup;
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

    public function getEName(): ?EventName
    {
        return $this->eName;
    }

    public function setEName(EventName $eName): self
    {
        $this->eName = $eName;
        return $this;
    }

    /**
     * @return Icon
     */
    public function getIcon(): Icon
    {
        return $this->icon;
    }

    /**
     * @param Icon $icon
     */
    public function setIcon(Icon $icon): self
    {
        $this->icon = $icon;    
        return $this;
    }

    /**
     * @return Organization
     */
    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    /**
     * @param Organization $organization
     */
    public function setOrganization(Organization $organization)
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return EventGroup
     */
    public function getEventGroup(): EventGroup
    {
        return $this->eventGroup;
    }

    public function setEventGroup(EventGroup $eventGroup)
    {
        $this->eventGroup = $eventGroup;
        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }
}
