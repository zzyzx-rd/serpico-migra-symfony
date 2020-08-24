<?php

namespace App\Entity;

use App\Repository\ProcessCriterionRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ORM\Entity(repositoryClass=ProcessCriterionRepository::class)
 */
class ProcessCriterion extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="crt_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="crt_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="crt_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="crt_weight", type="float", nullable=true)
     */
    public $weight;

    /**
     * @ORM\Column(name="crt_forceComment_compare", type="boolean", nullable=true)
     */
    public $forceCommentCompare;

    /**
     * @ORM\Column(name="crt_forceComment_value", type="float", nullable=true)
     */
    public $forceCommentValue;

    /**
     * @ORM\Column(name="crt_forceComment_sign", type="string", length=255, nullable=true)
     */
    public $forceCommentSign;

    /**
     * @ORM\Column(name="crt_lowerbound", type="float", nullable=true)
     */
    public $lowerbound;

    /**
     * @ORM\Column(name="crt_upperbound", type="float", nullable=true)
     */
    public $upperbound;

    /**
     * @ORM\Column(name="crt_step", type="float", nullable=true)
     */
    public $step;

    /**
     * @ORM\Column(name="crt_comment", type="string", length=255, nullable=true)
     */
    public $comment;

    /**
     * @ORM\Column(name="crt_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="crt_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="ProcessStage", inversedBy="criteria")
     * @JoinColumn(name="process_stage_stg_id", referencedColumnName="stg_id",nullable=true)
     */
    protected $stage;

    /**
     * @ORM\ManyToOne(targetEntity=Process::class, inversedBy="criteria")
     * @JoinColumn(name="process_pro_id", referencedColumnName="pro_id",nullable=true)
     */
    private $process;

    /**
     * @OneToOne(targetEntity="CriterionName")
     * @JoinColumn(name="criterion_name_cna_id", referencedColumnName="cna_id", nullable=true)
     */
    protected $cName;


    /**
     * ProcessCriterion constructor.
     * @param $id
     * @param $crt_type
     * @param $crt_name
     * @param $crt_weight
     * @param $crt_forceComment_compare
     * @param $crt_forceComment_value
     * @param $crt_forceComment_sign
     * @param $crt_lowerbound
     * @param $crt_upperbound
     * @param $crt_step
     * @param $crt_comment
     * @param $crt_createdBy
     * @param $crt_inserted
     * @param $stage
     * @param $process
     * @param $cName
     */
    public function __construct(
        $id = 0,
        $crt_type = 1,
        $crt_name = null,
        $crt_weight = 1,
        $crt_lowerbound = 0,
        $crt_upperbound = 5,
        $crt_step = 0.5,
        $crt_forceComment_compare = null,
        $crt_forceComment_value = null,
        $crt_forceComment_sign = null,
        $crt_comment = null,
        $crt_createdBy = null,
        $crt_inserted = null,
        $stage = null,
        $process = null,
        $cName = null)
    {
        parent::__construct($id, $crt_createdBy, new DateTime());
        $this->type = $crt_type;
        $this->name = $crt_name;
        $this->weight = $crt_weight;
        $this->forceCommentCompare = $crt_forceComment_compare;
        $this->forceCommentValue = $crt_forceComment_value;
        $this->forceCommentSign = $crt_forceComment_sign;
        $this->lowerbound = $crt_lowerbound;
        $this->upperbound = $crt_upperbound;
        $this->step = $crt_step;
        $this->comment = $crt_comment;
        $this->stage = $stage;
        $this->process = $process;
        $this->cName = $cName;
    }


    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $crt_type): self
    {
        $this->type = $crt_type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $crt_name): self
    {
        $this->name = $crt_name;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $crt_weight): self
    {
        $this->weight = $crt_weight;

        return $this;
    }

    public function getForceCommentCompare(): ?bool
    {
        return $this->forceCommentCompare;
    }

    public function setForceCommentCompare(bool $crt_forceComment_compare): self
    {
        $this->forceCommentCompare = $crt_forceComment_compare;

        return $this;
    }

    public function getForceCommentValue(): ?float
    {
        return $this->forceCommentValue;
    }

    public function setForceCommentValue(?float $crt_forceComment_value): self
    {
        $this->forceCommentValue = $crt_forceComment_value;

        return $this;
    }

    public function getForceCommentSign(): ?string
    {
        return $this->forceCommentSign;
    }

    public function setForceCommentSign(?string $crt_forceComment_sign): self
    {
        $this->forceCommentSign = $crt_forceComment_sign;

        return $this;
    }

    public function getLowerbound(): ?float
    {
        return $this->lowerbound;
    }

    public function setLowerbound(?float $crt_lowerbound): self
    {
        $this->lowerbound = $crt_lowerbound;

        return $this;
    }

    public function getUpperbound(): ?float
    {
        return $this->upperbound;
    }

    public function setUpperbound(?float $crt_upperbound): self
    {
        $this->upperbound = $crt_upperbound;

        return $this;
    }

    public function getStep(): ?float
    {
        return $this->step;
    }

    public function setStep(?float $crt_step): self
    {
        $this->step = $crt_step;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $crt_comment): self
    {
        $this->comment = $crt_comment;

        return $this;
    }

    public function setInserted(?DateTimeInterface $crt_inserted): self
    {
        $this->inserted = $crt_inserted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * @param mixed $stage
     */
    public function setStage($stage)
    {
        $this->stage = $stage;

    }

    /**
     * @return mixed
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param mixed $process
     */
    public function setProcess($process)
    {
        $this->process = $process;
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
    public function setCName($cName)
    {
        $this->cName = $cName;
        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }


}
