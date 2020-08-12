<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AppreciationRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\AppreciationRepository", repositoryClass=AppreciationRepository::class)
 */
class Appreciation extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="apt_id", type="integer", nullable=false)
     */
    public $id;

    /**
     * @ORM\Column(type="float")
     */
    public $apt_value;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $apt_comment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $apt_createdBy;
    /**
     * @ManyToOne(targetEntity="Criterion")
     * @JoinColumn(name="apt_criterion", referencedColumnName="crt_id",nullable=false)
     */
    protected $criterion;

    /**
     * Appreciation constructor.
     * @param $id
     * @param $apt_value
     * @param $apt_comment
     * @param $apt_createdBy
     * @param $criterion
     */
    public function __construct(
        $id = 0,
        $apt_value = 0,
        $apt_comment = "",
        $apt_createdBy = null,
        $criterion = null)
    {
        parent::__construct($id, $apt_createdBy, new DateTime());
        $this->apt_value = $apt_value;
        $this->apt_comment = $apt_comment;
        $this->criterion = $criterion?$criterion:new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->apt_value;
    }

    public function setValue(float $apt_value): self
    {
        $this->apt_value = $apt_value;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->apt_comment;
    }

    public function setComment(string $apt_comment): self
    {
        $this->apt_comment = $apt_comment;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->apt_createdBy;
    }

    public function setCreatedBy($apt_createdBy): self
    {
        $this->apt_createdBy = $apt_createdBy;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCriterion()
    {
        return $this->criterion;
    }

    /**
     * @param mixed $criterion
     */
    public function setCriterion($criterion): void
    {
        $this->criterion = $criterion;
    }

}
