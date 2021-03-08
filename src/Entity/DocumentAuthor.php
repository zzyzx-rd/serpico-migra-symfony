<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MemberRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=DocumentAuthorRepository::class)
 */
class DocumentAuthor extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="dau_id", type="integer", length=10, nullable=true)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="dau_leader", type="boolean", nullable=true)
     */
    public $leader;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="documentAuthorInitiatives")
     * @JoinColumn(name="dau_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\Column(name="dau_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     *@ManyToOne(targetEntity="EventDocument", inversedBy="documentAuthors")
     *@JoinColumn(name="event_document_evd_id", referencedColumnName="evd_id", nullable=false)
     */
    protected $document;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="documentContributions")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id")
     */
    public $author;

    /**
     * DocumentAuthor constructor.
     * @param ?int$id
     * @param $leader
     * @param $document
     * @param $author
     * @param $externalUser
     */

    public function __construct(
      ?int $id = 0,
        $leader = false)
    {
        parent::__construct($id, null, new DateTime());
        $this->leader = $leader;
    }

    public function isLeader(): ?bool
    {
        return $this->leader;
    }

    public function setLeader(bool $leader): self
    {
        $this->leader = $leader;
        return $this;
    }

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDocument(): ?EventDocument
    {
        return $this->document;
    }

    /**
     * @param mixed $document
     */
    public function setDocument(EventDocument $document): self
    {
        $this->document = $document;
        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;
        return $this;
    }

}
