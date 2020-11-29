<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventCommentRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

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
     * @ORM\Column(name="evc_content", type="string", nullable=true)
     */
    public $content;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="eventComments")
     * @JoinColumn(name="evc_author", referencedColumnName="usr_id", nullable=true)
     * @var User
     */
    protected $author;

    /**
     * @ManyToOne(targetEntity="Event", inversedBy="comments")
     * @JoinColumn(name="event_eve_id", referencedColumnName="eve_id", nullable=true)
     * @var Event
     */
    protected $event;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="eventComments")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=true)
     * @var Organization
     */
    protected $organization;

    /**
     * @ORM\Column(name="evc_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ManyToOne(targetEntity="EventComment", inversedBy="replies")
     * @JoinColumn(name="parent_id", referencedColumnName="evc_id", nullable=true)
     */
    public $parent;

    /**
     * @OneToMany(targetEntity="EventComment", mappedBy="parent", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $replies;

    /**
     * @OneToMany(targetEntity="ElementUpdate", mappedBy="eventComment", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $updates;

    /**
     * @ORM\Column(name="evc_modified", type="datetime", nullable=true)
     */
    public $modified;

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
        $content = null,
        $createdBy = null,
        Event $event = null,
        Organization $organization = null,
        DateTime $modified = null
        )
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->type = $type;
        $this->content = $content;
        $this->description = $description;
        $this->modified = $modified;
        $this->event = $event;
        $this->organization = $organization;
        $this->replies = new ArrayCollection;
        $this->updates = new ArrayCollection;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;
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

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(Organization $organization): self
    {
        $this->organization = $organization;
        return $this;
    }

    public function setInserted(?DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }

    public function getModified(): ?DateTimeInterface
    {
        return $this->modified;
    }

    public function setModified(?DateTimeInterface $modified): self
    {
        $this->modified = $modified;
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

    public function addReply(EventComment $reply): self
    {
        $this->replies->add($reply);
        $reply->setParent($this);
        return $this;
    }

    public function removeReply(EventComment $reply): self
    {
        $this->replies->removeElement($reply);
        return $this;
    }

    /**
     * @return ArrayCollection|EventComment[]
     */
    public function getReplies()
    {
        return $this->replies;
    }

    public function setParent(?EventComment $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    /**
    * @return ArrayCollection|ElementUpdate[]
    */
    public function getUpdates()
    {
        return $this->updates;
    }

    public function addUpdate(ElementUpdate $update): self
    {
        $this->updates->add($update);
        $update->setEventComment($this);
        return $this;
    }

    public function removeUpdate(ElementUpdate $update): self
    {
        $this->updates->removeElement($update);
        return $this;
    }
}
