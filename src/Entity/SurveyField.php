<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SurveyFieldRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=SurveyFieldRepository::class)
 */
class SurveyField
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="sfi_id", type="integer", length=10, nullable=false)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sfi_type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sfi_isMandatory;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sfi_title;

    /**
     * @ORM\Column(type="integer")
     */
    private $sfi_position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sfi_description;

    /**
     * @ORM\Column(type="integer")
     */
    private $sfi_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sfi_inserted;

    /**
     * @ORM\Column(type="integer")
     */
    private $sfi_upperbound;

    /**
     * @ORM\Column(type="integer")
     */
    private $sfi_lowerbound;

    /**
     * @ManyToOne(targetEntity="Criterion")
     * @JoinColumn(name="criterion_crt_id", referencedColumnName="crt_id", nullable=false)
     */
    protected $criterion;

    /**
     * @ManyToOne(targetEntity="Survey")
     * @JoinColumn(name="survey_sur_id", referencedColumnName="sur_id", nullable=false)
     */
    protected $survey;

    /**
     * @OneToMany(targetEntity="SurveyFieldParameter", mappedBy="field", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $parameters;

    /**
     * @OneToMany(targetEntity="Answer", mappedBy="field", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $answers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSfiType(): ?string
    {
        return $this->sfi_type;
    }

    public function setSfiType(string $sfi_type): self
    {
        $this->sfi_type = $sfi_type;

        return $this;
    }

    public function getSfiIsMandatory(): ?bool
    {
        return $this->sfi_isMandatory;
    }

    public function setSfiIsMandatory(bool $sfi_isMandatory): self
    {
        $this->sfi_isMandatory = $sfi_isMandatory;

        return $this;
    }

    public function getSfiTitle(): ?string
    {
        return $this->sfi_title;
    }

    public function setSfiTitle(string $sfi_title): self
    {
        $this->sfi_title = $sfi_title;

        return $this;
    }

    public function getSfiPosition(): ?int
    {
        return $this->sfi_position;
    }

    public function setSfiPosition(int $sfi_position): self
    {
        $this->sfi_position = $sfi_position;

        return $this;
    }

    public function getSfiDescription(): ?string
    {
        return $this->sfi_description;
    }

    public function setSfiDescription(string $sfi_description): self
    {
        $this->sfi_description = $sfi_description;

        return $this;
    }

    public function getSfiCreatedBy(): ?int
    {
        return $this->sfi_createdBy;
    }

    public function setSfiCreatedBy(int $sfi_createdBy): self
    {
        $this->sfi_createdBy = $sfi_createdBy;

        return $this;
    }

    public function getSfiInserted(): ?\DateTimeInterface
    {
        return $this->sfi_inserted;
    }

    public function setSfiInserted(\DateTimeInterface $sfi_inserted): self
    {
        $this->sfi_inserted = $sfi_inserted;

        return $this;
    }

    public function getSfiUpperbound(): ?int
    {
        return $this->sfi_upperbound;
    }

    public function setSfiUpperbound(int $sfi_upperbound): self
    {
        $this->sfi_upperbound = $sfi_upperbound;

        return $this;
    }

    public function getSfiLowerbound(): ?int
    {
        return $this->sfi_lowerbound;
    }

    public function setSfiLowerbound(int $sfi_lowerbound): self
    {
        $this->sfi_lowerbound = $sfi_lowerbound;

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

    /**
     * @return mixed
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * @param mixed $survey
     */
    public function setSurvey($survey): void
    {
        $this->survey = $survey;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @return mixed
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param mixed $answers
     */
    public function setAnswers($answers): void
    {
        $this->answers = $answers;
    }

}
