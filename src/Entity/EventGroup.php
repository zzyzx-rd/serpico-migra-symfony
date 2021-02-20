<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventGroupRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=EventGroupRepository::class)
 */
class EventGroup extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="evg_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="evg_name", type="string", length=255, nullable=true)
     */
    public $name;
    
    
    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="eventGroups")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=true)
     * @var Organization
     */
    protected $organization;
    
    /**
     * @ManyToOne(targetEntity="Department", inversedBy="eventGroups")
     * @JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id", nullable=true)
     * @var Department
     */
    protected $department;

    /**
     * @OneToMany(targetEntity="EventType", mappedBy="eventGroup", cascade={"persist", "remove"})
     * @var ArrayCollection
     */
    protected $eventTypes;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="eventGroupInitiatives")
     * @JoinColumn(name="evg_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\Column(name="evg_inserted", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="evg_enabled", type="boolean", nullable=false, options={"default": true})
     */
    public bool $enabled;
    /**
     * @ManyToOne(targetEntity="EventGroupName", inversedBy="eventGroups")
     * @JoinColumn(name="event_group_name_egn_id", referencedColumnName="egn_id",nullable=true)
     */
    public $eventGroupName;

    
    /**
     * EventGroup constructor.
     * @param string $name
     * @param Organization $organization
     * @param Department $department
     * @param $id
     * @param ArrayCollection $eventTypes
     */
    public function __construct(
        string $name = null,
        Organization $organization = null,
        Department $department = null,
        $enabled = true,
        $id = null,
        ArrayCollection $eventTypes = null)
    {
        parent::__construct($id, null, new DateTime());
        $this->name = $name;
        $this->organization = $organization;
        $this->department = $department;
        $this->enabled = $enabled;
        $this->eventTypes = $eventTypes ?: new ArrayCollection;
    }

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;
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
    public function setOrganization(Organization $organization): self
    {
        $this->organization = $organization;
        return $this;
    }
    
    /**
     * @return Department
     */
    public function getDepartment(): ?Department
    {
        return $this->department;
    }
    
    /**
     * @param Department $department
     */
    public function setDepartment(Department $department): self
    {
        $this->department = $department;
        return $this;
    }
    /**
     * @return ArrayCollection|EventType[]
     */
    public function getEventTypes()
    {
        return $this->eventTypes;
    }

    public function addEventType(EventType $eventType): self
    {
        $this->eventTypes->add($eventType);
        $eventType->setEventGroup($this);
        return $this;
    }

    public function removeEventType(EventType $eventType): ?EventGroup
    {
        $this->eventTypes->removeElement($eventType);
        return $this;
    }

    public function hasNoEventType() {
        return $this->eventTypes->isEmpty();
    }

    public function toArray()
    {
        $events = $this->eventTypes->map(static function(EventName $e) {
            return [
                'id' => $e->getId(),
                'name' => $e->getName()
            ];
        })->toArray();

        return [
            'id' => $this->id,
            'initiator' => $this->initiator,
            'inserted' => $this->inserted,
            'name' => $this->name,
            'events' => $events,
            'organization' => $this->organization,
            'department' => $this->department
        ];
    }

    public function __toString()
    {
        return (string) $this->id;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return EventGroupName
     */
    public function getEventGroupName(): EventGroupName
    {
        return $this->eventGroupName;
    }

    public function setEventGroupName(EventGroupName $eventGroupName)
    {
        $this->eventGroupName = $eventGroupName;
        return $this;
    }

}
