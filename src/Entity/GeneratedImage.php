<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GeneratedImageRepository;
use DateTime;
use DateTimeInterface;
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
     */
    protected ?int $id;

    /**
     * @ORM\Column(name="gim_all", type="integer", nullable=true)
     */
    public $all;

    /**
     * @ORM\Column(name="gim_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="gim_tid", type="integer", nullable=true)
     */
    public $tid;

    /**
     * @ORM\Column(name="gim_uid", type="integer", nullable=true)
     */
    public $uid;

    /**
     * @ORM\Column(name="gim_aid", type="integer", nullable=true)
     */
    public $aid;

    /**
     * @ORM\Column(name="gim_ov", type="boolean", nullable=true)
     */
    public $overview;

    /**
     * @ORM\Column(name="gim_sid", type="integer", nullable=true)
     */
    public $sid;

    /**
     * @Column(name="gim_cid", type="integer", nullable=true, length=10)
     * @var int
     */
    protected $crtId;
    
    /**
     * @ORM\Column(name="gim_role", type="integer", nullable=true)
     */
    public $role;

    /**
     * @ORM\Column(name="gim_createdBy", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="gim_val", type="string", length=255, nullable=true)
     */
    public $val;

    /**
     * @ORM\Column(name="gim_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * @OneToOne(targetEntity="CriterionName")
     * @JoinColumn(name="criterion_name_cna_id", referencedColumnName="cna_id", nullable=true)
     */
    protected $cName;

    /**
     * GeneratedImage constructor.
     * @param ?int$id
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
      ?int $id = 0,
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
        $cName = null)
    {
        parent::__construct($id, $gim_createdBy, new DateTime());
        $this->all = $gim_all;
        $this->type = $gim_type;
        $this->tid = $gim_tid;
        $this->uid = $gim_uid;
        $this->aid = $gim_aid;
        $this->overview = $gim_ov;
        $this->sid = $gim_sid;
        $this->crtId = $crtId;
        $this->role = $gim_role;
        $this->val = $gim_val;
        $this->cName = $cName;
    }


    public function getAll(): ?int
    {
        return $this->all;
    }

    public function setAll(int $gim_all): self
    {
        $this->all = $gim_all;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $gim_type): self
    {
        $this->type = $gim_type;

        return $this;
    }

    public function getTid(): ?int
    {
        return $this->tid;
    }

    public function setTid(?int $gim_tid): self
    {
        $this->tid = $gim_tid;

        return $this;
    }

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(?int $gim_uid): self
    {
        $this->uid = $gim_uid;

        return $this;
    }

    public function getAid(): ?int
    {
        return $this->aid;
    }

    public function setAid(?int $gim_aid): self
    {
        $this->aid = $gim_aid;

        return $this;
    }

    public function isOverview(): ?bool
    {
        return $this->overview;
    }

    public function setOverview(bool $gim_ov): self
    {
        $this->overview = $gim_ov;

        return $this;
    }

    public function getSid(): ?int
    {
        return $this->sid;
    }

    public function setSid(?int $gim_sid): self
    {
        $this->sid = $gim_sid;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(?int $gim_role): self
    {
        $this->role = $gim_role;

        return $this;
    }

    public function getVal(): ?string
    {
        return $this->val;
    }

    public function setVal(string $gim_val): self
    {
        $this->val = $gim_val;

        return $this;
    }

    public function setInserted(DateTimeInterface $gim_inserted): self
    {
        $this->inserted = $gim_inserted;

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
