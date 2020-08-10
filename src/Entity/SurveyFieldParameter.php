<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SurveyFieldParameterRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=SurveyFieldParameterRepository::class)
 */
class SurveyFieldParameter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="sfp_id", type="integer", length=10, nullable=false)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sfp_value;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sfp_lowerbound;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sfp_upperbound;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sfp_step;

    /**
     * @ORM\Column(type="integer")
     */
    private $sfp_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sfp_inserted;

    /**
     * @ManyToOne(targetEntity="SurveyField")
     * @JoinColumn(name="survey_field_sfi_id", referencedColumnName="sfi_id", nullable=false)
     */
    protected $field;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSfpValue(): ?string
    {
        return $this->sfp_value;
    }

    public function setSfpValue(string $sfp_value): self
    {
        $this->sfp_value = $sfp_value;

        return $this;
    }

    public function getSfpLowerbound(): ?float
    {
        return $this->sfp_lowerbound;
    }

    public function setSfpLowerbound(?float $sfp_lowerbound): self
    {
        $this->sfp_lowerbound = $sfp_lowerbound;

        return $this;
    }

    public function getSfpUpperbound(): ?float
    {
        return $this->sfp_upperbound;
    }

    public function setSfpUpperbound(?float $sfp_upperbound): self
    {
        $this->sfp_upperbound = $sfp_upperbound;

        return $this;
    }

    public function getSfpStep(): ?float
    {
        return $this->sfp_step;
    }

    public function setSfpStep(?float $sfp_step): self
    {
        $this->sfp_step = $sfp_step;

        return $this;
    }

    public function getSfpCreatedBy(): ?int
    {
        return $this->sfp_createdBy;
    }

    public function setSfpCreatedBy(int $sfp_createdBy): self
    {
        $this->sfp_createdBy = $sfp_createdBy;

        return $this;
    }

    public function getSfpInserted(): ?\DateTimeInterface
    {
        return $this->sfp_inserted;
    }

    public function setSfpInserted(\DateTimeInterface $sfp_inserted): self
    {
        $this->sfp_inserted = $sfp_inserted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     */
    public function setField($field): void
    {
        $this->field = $field;
    }

}
