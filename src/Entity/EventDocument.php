<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventDocumentRepository;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=EventDocumentRepository::class)
 */
class EventDocument extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="evd_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="evd_title", type="string", length=255, nullable=true)
     */
    public $title;

    /**
     * @ORM\Column(name="evd_type", type="string", length=255, nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="evd_mime", type="string", length=255, nullable=true)
     */
    public $mime;

    /**
     * @ORM\Column(name="evd_path", type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @ORM\Column(name="evd_size", type="integer", length=10, nullable=true)
     */
    public ?int $size;

    /**
     * @OneToMany(targetEntity="DocumentAuthor", mappedBy="document", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $documentAuthors;

    /**
     * @OneToMany(targetEntity="ElementUpdate", mappedBy="eventDocument", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $updates;

    /**
     * @ManyToOne(targetEntity="Event", inversedBy="documents")
     * @JoinColumn(name="event_eve_id", referencedColumnName="eve_id", nullable=true)
     * @var Event
     */
    protected $event;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="eventDocuments")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=true)
     * @var Organization
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="eventDocumentInitiatives")
     * @JoinColumn(name="evd_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\Column(name="evd_modified", type="datetime", nullable=true)
     */
    public $modified;

    /**
     * @ORM\Column(name="evd_inserted", type="datetime", nullable=true, options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    public ?DateTimeZone $tz;

    /**
     * OptionName constructor.
     * @param $id
     * @param $title
     * @param $path
     * @param $size
     * @param $event
     */
    public function __construct(
        $id = 0,
        $title = null,
        $path = null,
        $size = null,
        $type = null,
        $mime = null,
        Event $event = null,
        Organization $organization = null,
        User $author = null,
        DateTime $modified = null)
    {
        parent::__construct($id, null, new DateTime());
        $this->title = $title;
        $this->path = $path;
        $this->size = $size;
        $this->type = $type;
        $this->mime = $mime;
        $this->event = $event;
        $this->organization = $organization;
        $this->author = $author;
        $this->modified = $modified;
        $this->documentAuthors = new ArrayCollection;
        $this->updates = new ArrayCollection;
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getMime(): ?string
    {
        return $this->mime;
    }

    public function setMime(string $mime): self
    {
        $this->mime = $mime;
        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;
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


    /**
     * @return ArrayCollection|DocumentAuthor[]
     */
    public function getDocumentAuthors()
    {
        return $this->documentAuthors;
    }

    public function addDocumentAuthor(DocumentAuthor $documentAuthor): self
    {
        $this->documentAuthors->add($documentAuthor);
        return $this;
    }

    public function removeDocumentAuthor(DocumentAuthor $documentAuthor): self
    {
        $this->documentAuthors->removeElement($documentAuthor);
        return $this;
    }

    public function setInserted(?DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }

    public function getModified($niceFormat = null): ?DateTimeInterface
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

    /**
    * @return ArrayCollection|ElementUpdate[]
    */
    public function getUpdates()
    {
        return $this->updates;
    }

    public function addUpdate(ElementUpdate $update): EventDocument
    {
        $this->updates->add($update);
        $update->setEventDocument($this);
        return $this;
    }

    public function removeUpdate(ElementUpdate $update): EventDocument
    {
        $this->updates->removeElement($update);
        return $this;
    }
}
