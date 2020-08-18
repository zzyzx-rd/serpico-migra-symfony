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
class SurveyFieldParameter extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="sfp_id", type="integer", length=10, nullable=false)
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $sfp_value;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $sfp_lowerbound;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $sfp_upperbound;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $sfp_step;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $sfp_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $sfp_inserted;

    /**
     * @ManyToOne(targetEntity="SurveyField", inversedBy="parameters")
     * @JoinColumn(name="survey_field_sfi_id", referencedColumnName="sfi_id", nullable=false)
     */
    protected $field;

    /**
     * SurveyFieldParameter constructor.
     * @param int $id
     * @param $sfp_value
     * @param $sfp_lowerbound
     * @param $sfp_upperbound
     * @param $sfp_step
     * @param $sfp_createdBy
     * @param $sfp_inserted
     * @param $field
     */
    public function __construct(
        int $id = 0,
        $sfp_value = null,
        $sfp_lowerbound = true,
        $sfp_upperbound = null,
        $sfp_step = null,
        $sfp_createdBy = null,
        $sfp_inserted = null,
        $field = null)
    {
        parent::__construct($id, $sfp_createdBy, new DateTime());
        $this->sfp_value = $sfp_value;
        $this->sfp_lowerbound = $sfp_lowerbound;
        $this->sfp_upperbound = $sfp_upperbound;
        $this->sfp_step = $sfp_step;
        $this->sfp_inserted = $sfp_inserted;
        $this->field = $field;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->sfp_value;
    }

    public function setValue(string $sfp_value): self
    {
        $this->sfp_value = $sfp_value;

        return $this;
    }

    public function getLowerbound(): ?float
    {
        return $this->sfp_lowerbound;
    }

    public function setLowerbound(?float $sfp_lowerbound): self
    {
        $this->sfp_lowerbound = $sfp_lowerbound;

        return $this;
    }

    public function getUpperbound(): ?float
    {
        return $this->sfp_upperbound;
    }

    public function setUpperbound(?float $sfp_upperbound): self
    {
        $this->sfp_upperbound = $sfp_upperbound;

        return $this;
    }

    public function getStep(): ?float
    {
        return $this->sfp_step;
    }

    public function setStep(?float $sfp_step): self
    {
        $this->sfp_step = $sfp_step;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->sfp_inserted;
    }

    public function setInserted(\DateTimeInterface $sfp_inserted): self
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

    public function jsonSerialize()
    {
        return [
            'value'=>$this->value,
        ];
    }
}
