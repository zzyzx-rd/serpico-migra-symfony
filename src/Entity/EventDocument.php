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
     * @ORM\Column(name="evd_path", type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @ManyToOne(targetEntity="Event", inversedBy="documents")
     * @JoinColumn(name="event_eve_id", referencedColumnName="eve_id", nullable=true)
     * @var Event
     */
    protected $event;

    /**
     * @ORM\Column(name="evd_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="evd_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * OptionName constructor.
     * @param $id
     * @param $title
     * @param $path
     * @param $event
     * @param $createdBy
     * @param $inserted
     */
    public function __construct(
        $id = 0,
        $title = null,
        $path = null,
        Event $event = null,
        $createdBy = null)
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->title = $title;
        $this->path = $path;
        $this->event = $event;
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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
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
