<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GeneratedImageRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=GeneratedImageRepository::class)
 */
class GeneratedImage extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="gim_id", type="integer", nullable=false)
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $gim_all;

    /**
     * @ORM\Column(type="integer")
     */
    public $gim_type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $gim_tid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $gim_uid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $gim_aid;

    /**
     * @ORM\Column(type="boolean")
     */
    public $gim_ov;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $gim_sid;

    /**
     *@Column(name="gim_cid", type="integer", nullable=true, length=10)
     * @var int
     */
    protected $crtId;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $gim_role;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $gim_createdBy;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gim_val;

    /**
     * @ORM\Column(type="datetime")
     */
    public $gim_inserted;

    /**
     * @OneToOne(targetEntity="CriterionName")
     * @JoinColumn(name="criterion_name_cna_id", referencedColumnName="cna_id")
     */
    protected $cName;

    /**
     * GeneratedImage constructor.
     * @param int $id
     * @param int $gim_type
     * @param $gim_tid
     * @param $gim_uid
     * @param $gim_aid
     * @param $gim_ov
     * @param $gim_sid
     * @param null $crtId
     * @param $gim_role
     * @param $gim_createdBy
     * @param $gim_val
     * @param $gim_inserted
     * @param $gim_all
     * @param $cName
     */
    public function __construct(
        int $id = 0,
        $gim_type = 0,
        $gim_tid = null,
        $gim_uid = null,
        $gim_aid = null,
        $gim_ov = null,
        $gim_sid = null,
        $crtId = null,
        $gim_role = null,
        $gim_createdBy = null,
        $gim_val = null,
        $gim_inserted = null,
        $gim_all = null,
        $cName)
    {
        parent::__construct($id, $gim_createdBy, new DateTime());
        $this->gim_all = $gim_all;
        $this->gim_type = $gim_type;
        $this->gim_tid = $gim_tid;
        $this->gim_uid = $gim_uid;
        $this->gim_aid = $gim_aid;
        $this->gim_ov = $gim_ov;
        $this->gim_sid = $gim_sid;
        $this->crtId = $crtId;
        $this->gim_role = $gim_role;
        $this->gim_val = $gim_val;
        $this->gim_inserted = $gim_inserted;
        $this->cName = $cName;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAll(): ?int
    {
        return $this->gim_all;
    }

    public function setAll(int $gim_all): self
    {
        $this->gim_all = $gim_all;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->gim_type;
    }

    public function setType(int $gim_type): self
    {
        $this->gim_type = $gim_type;

        return $this;
    }

    public function getTid(): ?int
    {
        return $this->gim_tid;
    }

    public function setTid(?int $gim_tid): self
    {
        $this->gim_tid = $gim_tid;

        return $this;
    }

    public function getUid(): ?int
    {
        return $this->gim_uid;
    }

    public function setUid(?int $gim_uid): self
    {
        $this->gim_uid = $gim_uid;

        return $this;
    }

    public function getAid(): ?int
    {
        return $this->gim_aid;
    }

    public function setAid(?int $gim_aid): self
    {
        $this->gim_aid = $gim_aid;

        return $this;
    }

    public function isOverview(): ?bool
    {
        return $this->gim_ov;
    }

    public function setOverview(bool $gim_ov): self
    {
        $this->gim_ov = $gim_ov;

        return $this;
    }

    public function getSid(): ?int
    {
        return $this->gim_sid;
    }

    public function setSid(?int $gim_sid): self
    {
        $this->gim_sid = $gim_sid;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->gim_role;
    }

    public function setRole(?int $gim_role): self
    {
        $this->gim_role = $gim_role;

        return $this;
    }

    public function getVal(): ?string
    {
        return $this->gim_val;
    }

    public function setVal(string $gim_val): self
    {
        $this->gim_val = $gim_val;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->gim_inserted;
    }

    public function setInserted(\DateTimeInterface $gim_inserted): self
    {
        $this->gim_inserted = $gim_inserted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCName()
    {
        return $this->cName;
    }

    /**
     * @param mixed $cName
     */
    public function setCName($cName): void
    {
        $this->cName = $cName;
    }

}
