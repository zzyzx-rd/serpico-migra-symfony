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
     * @ORM\Column(name="evc_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ManyToOne(targetEntity="EventComment", inversedBy="children")
     * @JoinColumn(name="parent_id", referencedColumnName="evc_id", nullable=true)
     */
    public $parent;

    /**
     * @OneToMany(targetEntity="EventComment", mappedBy="parent", cascade={"persist", "remove"})
     */
    public $children;

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
        $createdBy = null
        )
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->type = $type;
        $this->content = $content;
        $this->description = $description;
        $this->children = new ArrayCollection;
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

    public function setInserted(?DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

    public function addChildren(EventComment $child): self
    {
        $this->children->add($child);
        $child->setParent($this);
        return $this;
    }

    public function removeChildren(EventComment $child): self
    {
        $this->children->removeElement($child);
        return $this;
    }

    /**
     * @return ArrayCollection|EventComment[]
     */
    public function getChildren()
    {
        return $this->children;
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
}
