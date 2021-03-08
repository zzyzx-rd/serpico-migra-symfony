<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SurveyFieldParameterRepository;
use DateTime;
use DateTimeInterface;
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
    public ?int $id;

    /**
     * @ORM\Column(name="sfp_value", type="string", length=255, nullable=true)
     */
    public $value;

    /**
     * @ORM\Column(name="sfp_lowerbound", type="float", nullable=true)
     */
    public $lowerbound;

    /**
     * @ORM\Column(name="sfp_upperbound", type="float", nullable=true)
     */
    public $upperbound;

    /**
     * @ORM\Column(name="sfp_step", type="float", nullable=true)
     */
    public $step;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="surveyFieldParameterInitiatives")
     * @JoinColumn(name="sfp_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\Column(name="sfp_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="SurveyField", inversedBy="parameters")
     * @JoinColumn(name="survey_field_sfi_id", referencedColumnName="sfi_id", nullable=false)
     */
    protected $field;

    /**
     * SurveyFieldParameter constructor.
     * @param ?int$id
     * @param $sfp_value
     * @param $sfp_lowerbound
     * @param $sfp_upperbound
     * @param $sfp_step
     * @param $field
     */
    public function __construct(
      ?int $id = 0,
        $sfp_value = null,
        $sfp_lowerbound = true,
        $sfp_upperbound = null,
        $sfp_step = null,
        $field = null)
    {
        parent::__construct($id, null, new DateTime());
        $this->value = $sfp_value;
        $this->lowerbound = $sfp_lowerbound;
        $this->upperbound = $sfp_upperbound;
        $this->step = $sfp_step;
        $this->field = $field;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $sfp_value): self
    {
        $this->value = $sfp_value;

        return $this;
    }

    public function getLowerbound(): ?float
    {
        return $this->lowerbound;
    }

    public function setLowerbound(?float $sfp_lowerbound): self
    {
        $this->lowerbound = $sfp_lowerbound;

        return $this;
    }

    public function getUpperbound(): ?float
    {
        return $this->upperbound;
    }

    public function setUpperbound(?float $sfp_upperbound): self
    {
        $this->upperbound = $sfp_upperbound;

        return $this;
    }

    public function getStep(): ?float
    {
        return $this->step;
    }

    public function setStep(?float $sfp_step): self
    {
        $this->step = $sfp_step;

        return $this;
    }

    public function setInserted(DateTimeInterface $sfp_inserted): self
    {
        $this->inserted = $sfp_inserted;

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

    public function jsonSerialize(): array
    {
        return [
            'value'=>$this->value,
        ];
    }
}
