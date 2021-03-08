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
 * @ORM\Entity()
 */
class EventGroupName extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="egn_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="egn_name", type="string", length=255, nullable=true)
     */
    public $name;
    
    /**
     * @OneToMany(targetEntity="EventName", mappedBy="eventGroupName", cascade={"persist", "remove"})
     * @var ArrayCollection
     */
    protected $eventNames;
    
    /**
     * @OneToMany(targetEntity="EventGroup", mappedBy="eventGroupName", cascade={"persist", "remove"})
     * @var ArrayCollection
     */
    protected $eventGroups;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="eventGroupNameInitiatives")
     * @JoinColumn(name="egn_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\Column(name="egn_inserted", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;
    
    /**
     * EventGroup constructor.
     * @param string $name
     * @param Organization $organization
     * @param Department $department
     * @param $id
     * @param ArrayCollection $eventNames
     */
    public function __construct(
        string $name = null,
        Organization $organization = null,
        Department $department = null,
        $enabled = true,
        $id = null,
        ArrayCollection $eventNames = null,
        ArrayCollection $eventGroups = null)
    {
        parent::__construct($id, null, new DateTime());
        $this->name = $name;
        $this->organization = $organization;
        $this->department = $department;
        $this->enabled = $enabled;
        $this->eventNames = $eventNames ?: new ArrayCollection;
        $this->eventGroups = $eventGroups ?: new ArrayCollection;
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
     * @return ArrayCollection|EventName[]
     */
    public function getEventNames()
    {
        return $this->eventNames;
    }

    public function addEventName(EventName $eventName): self
    {
        $this->eventNames->add($eventName);
        $eventName->setEventGroupName($this);
        return $this;
    }

    public function removeEventName(EventName $eventName): ?EventGroupName
    {
        $this->eventNames->removeElement($eventName);
        return $this;
    }

    public function hasNoEventName() {
        return $this->eventNames->isEmpty();
    }

    /**
     * @return ArrayCollection|EventGroup[]
     */
    public function getEventGroups()
    {
        return $this->eventGroups;
    }

    public function addEventGroup(EventGroup $eventGroup): self
    {
        $this->eventGroups->add($eventGroup);
        $eventGroup->setEventGroupName($this);
        return $this;
    }

    public function removeEventGroup(EventGroup $eventGroup): ?EventGroupName
    {
        $this->eventGroups->removeElement($eventGroup);
        return $this;
    }

    /*
    public function toArray()
    {
        $events = $this->eventNames->map(static function(EventName $e) {
            return [
                'id' => $e->getId(),
                'type' => $e->getType(),
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
    */

    public function __toString()
    {
        return (string) $this->id;
    }
    
}
