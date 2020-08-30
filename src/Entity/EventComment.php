<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OptionNameRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=EventCommentRepository::class)
 */
class EventComment extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="evc_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="evc_type", type="string", nullable=true)
     */
    public $value;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="eventComments")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id", nullable=true)
     * @var User
     */
    protected $user;

    /**
     * @ManyToOne(targetEntity="Event", inversedBy="comments")
     * @JoinColumn(name="event_eve_id", referencedColumnName="eve_id", nullable=true)
     * @var Event
     */
    protected $event;

    /**
     * @ORM\Column(name="evc_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="evc_inserted", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * OptionName constructor.
     * @param $id
     * @param $type
     * @param $name
     * @param $description
     * @param $createdBy
     */
    public function __construct(
        $id = 0,
        $type = null,
        $description = null,
        $name = null,
        $user = null,
        $createdBy = null
        )
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->type = $type;
        $this->name = $name;
        $this->user = $user;
        $this->description = $description;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): self
    {
        $this->event = $event;
        return $this;
    }

    public function setInserted(?DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }
}
