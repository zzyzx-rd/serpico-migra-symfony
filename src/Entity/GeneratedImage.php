<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GeneratedImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=GeneratedImageRepository::class)
 */
class GeneratedImage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="gim_id", type="integer", nullable=false)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $gim_all;

    /**
     * @ORM\Column(type="integer")
     */
    private $gim_type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gim_tid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gim_uid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gim_aid;

    /**
     * @ORM\Column(type="boolean")
     */
    private $gim_ov;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gim_sid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gim_role;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gim_createdBy;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gim_val;

    /**
     * @ORM\Column(type="datetime")
     */
    private $gim_inserted;

    /**
     * @OneToOne(targetEntity="CriterionName")
     * @JoinColumn(name="criterion_name_cna_id", referencedColumnName="cna_id")
     */
    protected $cName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGimAll(): ?int
    {
        return $this->gim_all;
    }

    public function setGimAll(int $gim_all): self
    {
        $this->gim_all = $gim_all;

        return $this;
    }

    public function getGimType(): ?int
    {
        return $this->gim_type;
    }

    public function setGimType(int $gim_type): self
    {
        $this->gim_type = $gim_type;

        return $this;
    }

    public function getGimTid(): ?int
    {
        return $this->gim_tid;
    }

    public function setGimTid(?int $gim_tid): self
    {
        $this->gim_tid = $gim_tid;

        return $this;
    }

    public function getGimUid(): ?int
    {
        return $this->gim_uid;
    }

    public function setGimUid(?int $gim_uid): self
    {
        $this->gim_uid = $gim_uid;

        return $this;
    }

    public function getGimAid(): ?int
    {
        return $this->gim_aid;
    }

    public function setGimAid(?int $gim_aid): self
    {
        $this->gim_aid = $gim_aid;

        return $this;
    }

    public function getGimOv(): ?bool
    {
        return $this->gim_ov;
    }

    public function setGimOv(bool $gim_ov): self
    {
        $this->gim_ov = $gim_ov;

        return $this;
    }

    public function getGimSid(): ?int
    {
        return $this->gim_sid;
    }

    public function setGimSid(?int $gim_sid): self
    {
        $this->gim_sid = $gim_sid;

        return $this;
    }

    public function getGimRole(): ?int
    {
        return $this->gim_role;
    }

    public function setGimRole(?int $gim_role): self
    {
        $this->gim_role = $gim_role;

        return $this;
    }

    public function getGimCreatedBy(): ?int
    {
        return $this->gim_createdBy;
    }

    public function setGimCreatedBy(?int $gim_createdBy): self
    {
        $this->gim_createdBy = $gim_createdBy;

        return $this;
    }

    public function getGimVal(): ?string
    {
        return $this->gim_val;
    }

    public function setGimVal(string $gim_val): self
    {
        $this->gim_val = $gim_val;

        return $this;
    }

    public function getGimInserted(): ?\DateTimeInterface
    {
        return $this->gim_inserted;
    }

    public function setGimInserted(\DateTimeInterface $gim_inserted): self
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
