<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WeightRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=WeightRepository::class)
 */
class Weight
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="wgt_id", type="integer", length=10, nullable=false)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $wgt_interval;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wgt_titleframe;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $wgt_value;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $wgt_modified;

    /**
     * @ORM\Column(type="integer")
     */
    private $wgt_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $wgt_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $wgt_deleted;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="org_id", referencedColumnName="org_id",nullable=false)
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Position")
     * @JoinColumn(name="pos_id", referencedColumnName="pos_id",nullable=false)
     */
    protected $position;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWgtInterval(): ?int
    {
        return $this->wgt_interval;
    }

    public function setWgtInterval(int $wgt_interval): self
    {
        $this->wgt_interval = $wgt_interval;

        return $this;
    }

    public function getWgtTitleframe(): ?string
    {
        return $this->wgt_titleframe;
    }

    public function setWgtTitleframe(string $wgt_titleframe): self
    {
        $this->wgt_titleframe = $wgt_titleframe;

        return $this;
    }

    public function getWgtValue(): ?float
    {
        return $this->wgt_value;
    }

    public function setWgtValue(?float $wgt_value): self
    {
        $this->wgt_value = $wgt_value;

        return $this;
    }

    public function getWgtModified(): ?\DateTimeInterface
    {
        return $this->wgt_modified;
    }

    public function setWgtModified(?\DateTimeInterface $wgt_modified): self
    {
        $this->wgt_modified = $wgt_modified;

        return $this;
    }

    public function getWgtCreatedBy(): ?int
    {
        return $this->wgt_createdBy;
    }

    public function setWgtCreatedBy(int $wgt_createdBy): self
    {
        $this->wgt_createdBy = $wgt_createdBy;

        return $this;
    }

    public function getWgtInserted(): ?\DateTimeInterface
    {
        return $this->wgt_inserted;
    }

    public function setWgtInserted(\DateTimeInterface $wgt_inserted): self
    {
        $this->wgt_inserted = $wgt_inserted;

        return $this;
    }

    public function getWgtDeleted(): ?\DateTimeInterface
    {
        return $this->wgt_deleted;
    }

    public function setWgtDeleted(?\DateTimeInterface $wgt_deleted): self
    {
        $this->wgt_deleted = $wgt_deleted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param mixed $organization
     */
    public function setOrganization($organization): void
    {
        $this->organization = $organization;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position): void
    {
        $this->position = $position;
    }

}
