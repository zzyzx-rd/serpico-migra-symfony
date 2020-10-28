<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OptionNameRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=EventNameRepository::class)
 */
class EventName extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="evn_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="evn_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="evn_description", type="string", length=255, nullable=true)
     */
    public $description;

    /**
     * @ORM\Column(name="evn_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="evn_inserted", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="EventGroupName", inversedBy="eventNames")
     * @JoinColumn(name="event_group_name_egn_id", referencedColumnName="egn_id",nullable=true)
     */
    public $eventGroupName;

    /**
     * @ManyToOne(targetEntity="Icon", inversedBy="eventNames")
     * @JoinColumn(name="icon_ico_id", referencedColumnName="ico_id",nullable=true)
     * @var Icon
     */
    protected ?Icon $icon;

    /**
     * @OneToMany(targetEntity="EventType", mappedBy="eName", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $eventTypes;

    /**
     * OptionName constructor.
     * @param $id
     * @param $type
     * @param $name
     * @param $description
     * @param $createdBy
     * @param $inserted
     */
    public function __construct(
        $id = 0,
        $description = null,
        $name = null,
        $createdBy = null)
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->name = $name;
        $this->description = $description;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setInserted(?DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

        return $this;
    }

    /**
     * @return ArrayCollection|EventType[]
     */
    public function getEventTypes()
    {
        return $this->eventTypess;
    }

    public function addEventType(EventType $eventType): EventName
    {
        $this->eventTypes->add($eventType);
        $eventType->setEName($this);
        return $this;
    }

    public function removeEventType(EventType $eventType): EventName
    {
        $this->eventTypes->removeElement($eventType);
        return $this;
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

    /**
     * @return Icon|null
     */
    public function getIcon(): ?Icon
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

    public function __toString()
    {
        return (string) $this->id;
    }
}
