<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SurveyFieldRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=SurveyFieldRepository::class)
 */
class SurveyField extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="sfi_id", type="integer", length=10, nullable=false)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="sfi_type", type="string", length=255, nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="sfi_isMandatory", type="boolean", nullable=true)
     */
    public $isMandatory;

    /**
     * @ORM\Column(name="sfi_title", type="string", length=255, nullable=true)
     */
    public $title;

    /**
     * @ORM\Column(name="sfi_position", type="integer", nullable=true)
     */
    public $position;

    /**
     * @ORM\Column(name="sfi_description", type="string", length=255, nullable=true)
     */
    public $description;

    /**
     * @ORM\Column(name="sfi_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="sfi_inserted", type="datetime", nullable=true)
     */
    public ?DateTime $inserted;

    /**
     * @ORM\Column(name="sfi_upperbound", type="integer", nullable=true)
     */
    public $upperbound;

    /**
     * @ORM\Column(name="sfi_lowerbound", type="integer", nullable=true)
     */
    public $lowerbound;

    /**
     * @ManyToOne(targetEntity="Criterion")
     * @JoinColumn(name="criterion_crt_id", referencedColumnName="crt_id", nullable=false)
     */
    protected $criterion;

    /**
     * @ManyToOne(targetEntity="Survey", inversedBy="fields")
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

    /**
     * SurveyField constructor.
     * @param ?int$id
     * @param $sfi_type
     * @param $sfi_isMandatory
     * @param $sfi_title
     * @param $sfi_position
     * @param $sfi_description
     * @param $sfi_createdBy
     * @param $sfi_inserted
     * @param $sfi_upperbound
     * @param $sfi_lowerbound
     * @param $criterion
     * @param $survey
     * @param $parameters
     * @param $answers
     */
    public function __construct(
      ?int $id = 0,
        $sfi_type = null,
        $sfi_isMandatory = true,
        $sfi_title = null,
        $sfi_description = null,
        $sfi_position = null,
        $sfi_upperbound = null,
        $sfi_lowerbound = null,
        $sfi_createdBy = null,
        $criterion = null,
        $sfi_inserted = null,
        Survey $survey = null,
        $parameters = null,
        Answer $answers = null)
    {
        parent::__construct($id, $sfi_createdBy, new DateTime());
        $this->type = $sfi_type;
        $this->isMandatory = $sfi_isMandatory;
        $this->title = $sfi_title;
        $this->position = $sfi_position;
        $this->description = $sfi_description;
        $this->inserted = $sfi_inserted;
        $this->upperbound = $sfi_upperbound;
        $this->lowerbound = $sfi_lowerbound;
        $this->criterion = $criterion;
        $this->survey = $survey;
        $this->parameters = $parameters?:new ArrayCollection();
        $this->answers = $answers?: new ArrayCollection();
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $sfi_type): self
    {
        $this->type = $sfi_type;

        return $this;
    }

    public function getIsMandatory(): ?bool
    {
        return $this->isMandatory;
    }

    public function setIsMandatory(bool $sfi_isMandatory): self
    {
        $this->isMandatory = $sfi_isMandatory;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $sfi_title): self
    {
        $this->title = $sfi_title;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $sfi_position): self
    {
        $this->position = $sfi_position;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $sfi_description): self
    {
        $this->description = $sfi_description;

        return $this;
    }

    public function setInserted(DateTimeInterface $sfi_inserted): self
    {
        $this->inserted = $sfi_inserted;

        return $this;
    }

    public function getUpperbound(): ?int
    {
        return $this->upperbound;
    }

    public function setUpperbound(int $sfi_upperbound): self
    {
        $this->upperbound = $sfi_upperbound;

        return $this;
    }

    public function getLowerbound(): ?int
    {
        return $this->lowerbound;
    }

    public function setLowerbound(int $sfi_lowerbound): self
    {
        $this->lowerbound = $sfi_lowerbound;

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

    public function addParameter(SurveyFieldParameter $parameter): SurveyField
    {
        $this->parameters->add($parameter);
        /*$parameter->setSurveyField($this);*/
        return $this;
    }

    public function removeParameter(SurveyFieldParameter $parameter): SurveyField
    {
        $this->parameters->removeElement($parameter);
        return $this;
    }
    public function addAnswers(Answer $answers): SurveyField
    {
        $this->answers->add($answers);
        /*$parameter->setSurveyField($this);*/
        return $this;
    }

    public function removeAnswers(Answer $answers): SurveyField
    {
        $this->answers->removeElement($answers);
        return $this;
    }

}
