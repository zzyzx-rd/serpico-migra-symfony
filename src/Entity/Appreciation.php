<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AppreciationRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AppreciationRepository::class)
 */
class Appreciation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="apt_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $apt_value;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apt_comment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $apt_createdBy;
    /**
     * @ManyToOne(targetEntity="Criterion")
     * @JoinColumn(name="apt_criterion", referencedColumnName="crt_id",nullable=false)
     */
    protected $criterion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAptValue(): ?float
    {
        return $this->apt_value;
    }

    public function setAptValue(float $apt_value): self
    {
        $this->apt_value = $apt_value;

        return $this;
    }

    public function getAptComment(): ?string
    {
        return $this->apt_comment;
    }

    public function setAptComment(string $apt_comment): self
    {
        $this->apt_comment = $apt_comment;

        return $this;
    }

    public function getAptCreatedBy(): ?int
    {
        return $this->apt_createdBy;
    }

    public function setAptCreatedBy(?int $apt_createdBy): self
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
