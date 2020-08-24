<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\IProcessCriterionRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=IProcessCriterionRepository::class)
 */
class IProcessCriterion extends DbObject
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
    public $forceComment_compare;

    /**
     * @ORM\Column(name="crt_forceComment_value", type="float", nullable=true)
     */
    public $forceComment_value;

    /**
     * @ORM\Column(name="crt_forceComment_sign", type="string", length=255, nullable=true)
     */
    public $forceComment_sign;

    /**
     * @ORM\Column(name="crt_lowerBound", type="float", nullable=true)
     */
    public $lowerBound;

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
    public ?DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="IProcessStage", inversedBy="criteria")
     * @JoinColumn(name="iprocess_stage_stg_id", referencedColumnName="stg_id",nullable=true)
     */
    protected $stage;

    /**
     * @ManyToOne(targetEntity="InstitutionProcess")
     * @JoinColumn(name="iprocess_inp_id", referencedColumnName="inp_id",nullable=true)
     */
    protected $institutionProcess;

    /**
     * @OneToMany(targetEntity="IProcessParticipation", mappedBy="criterion", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var Collection
     */
//     * @OrderBy({"leader" = "DESC"})
    public $participants;

    /**
     * @OneToOne(targetEntity="CriterionName")
     * @JoinColumn(name="criterion_name_cna_id", referencedColumnName="cna_id", nullable=true)
     */
    protected $cName;

    /**
     * IProcessCriterion constructor.
     * @param $id
     * @param $crt_type
     * @param $crt_name
     * @param $crt_weight
     * @param $crt_forceComment_compare
     * @param $crt_forceComment_value
     * @param $crt_forceComment_sign
     * @param $crt_lowerBound
     * @param $crt_upperbound
     * @param $crt_step
     * @param $crt_comment
     * @param $crt_createdBy
     * @param $crt_inserted
     * @param $stage
     * @param $institutionProcess
     * @param $participants
     * @param $cName
     */
    public function __construct(
        $id = 0,
        $crt_type = 1,
        $crt_name = null,
        $crt_weight = 1,
        $crt_lowerBound = 0,
        $crt_upperbound = 5,
        $crt_step = 0.5,
        $crt_forceComment_compare = null,
        $crt_forceComment_value = null,
        $crt_forceComment_sign = null,
        $crt_comment = null,
        $crt_createdBy = null,
        $crt_inserted= null,
        Stage $stage = null,
        InstitutionProcess $institutionProcess = null,
        Collection $participants = null,
        $cName = null)
    {
        parent::__construct($id, $crt_createdBy, new DateTime());
        $this->type = $crt_type;
        $this->name = $crt_name;
        $this->weight = $crt_weight;
        $this->forceComment_compare = $crt_forceComment_compare;
        $this->forceComment_value = $crt_forceComment_value;
        $this->forceComment_sign = $crt_forceComment_sign;
        $this->lowerBound = $crt_lowerBound;
        $this->upperbound = $crt_upperbound;
        $this->step = $crt_step;
        $this->comment = $crt_comment;
        $this->inserted = $crt_inserted;
        $this->stage = $stage;
        $this->institutionProcess = $institutionProcess;
        $this->participants = $participants;
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
        return $this->forceComment_compare;
    }

    public function setForceCommentCompare(bool $crt_forceComment_compare): self
    {
        $this->forceComment_compare = $crt_forceComment_compare;

        return $this;
    }

    public function getForceCommentValue(): ?float
    {
        return $this->forceComment_value;
    }

    public function setForceCommentValue(?float $crt_forceComment_value): self
    {
        $this->forceComment_value = $crt_forceComment_value;

        return $this;
    }

    public function getForceCommentSign(): ?string
    {
        return $this->forceComment_sign;
    }

    public function setForceCommentSign(?string $crt_forceComment_sign): self
    {
        $this->forceComment_sign = $crt_forceComment_sign;

        return $this;
    }

    public function getLowerBound(): ?float
    {
        return $this->lowerBound;
    }

    public function setLowerBound(?float $crt_lowerBound): self
    {
        $this->lowerBound = $crt_lowerBound;

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

    public function setComment(string $crt_comment): self
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
    public function setStage($stage): void
    {
        $this->stage = $stage;
    }

    /**
     * @return mixed
     */
    public function getInstitutionProcess()
    {
        return $this->institutionProcess;
    }

    /**
     * @param mixed $institutionProcess
     */
    public function setInstitutionProcess($institutionProcess): void
    {
        $this->institutionProcess = $institutionProcess;
    }

    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    /**
     * @param $participants
     */
    public function setParticipants(Collection $participants): void
    {
        $this->participants = $participants;
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

    public function __toString()
    {
        return (string) $this->id;
    }

    public function addParticipant(IProcessParticipation $participant): IProcessCriterion
    {
        $this->participants->add($participant);
        $participant->setCriterion($this);
        return $this;
    }


    public function removeParticipant(IProcessParticipation $participant): IProcessCriterion
    {
        // Remove this participant
        $this->participants->removeElement($participant);
        return $this;
    }

}
