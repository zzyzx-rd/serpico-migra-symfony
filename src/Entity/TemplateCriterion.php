<?php

namespace App\Entity;

use App\Repository\TemplateCriterionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ORM\Entity(repositoryClass=TemplateCriterionRepository::class)
 */
class TemplateCriterion extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="crt_id", type="integer", nullable=false)
     */
    public $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $icrt_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $crt_name;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $crt_weight;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $crt_forceComment_compare;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $crt_forceComment_value;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $crt_forceComment_sign;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $crt_lowerbound;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $crt_upperbound;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $crt_step;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $crt_comment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $crt_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $crt_inserted;

    /**
     * @ManyToOne(targetEntity="TemplateStage", inversedBy="criteria")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;

    /**
     * @OneToMany(targetEntity="TemplateActivityUser", mappedBy="criterion",cascade={"persist", "remove"},orphanRemoval=true)
     * @JoinColumn(name="")
     */
//     * @OrderBy({"leader" = "ASC"})
    public $participants;

    /**
     * @OneToOne(targetEntity="CriterionName")
     * @JoinColumn(name="criterion_name_cna_id", referencedColumnName="cna_id")
     */
    protected $cName;

    /**
     * TemplateCriterion constructor.
     * @param $id
     * @param $icrt_type
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
     * @param $participants
     * @param $cName
     */
    public function __construct(
        $id = 0,
        $icrt_type = 1,
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
        $participants = null,
        $cName = null)
    {
        parent::__construct($id,$crt_createdBy , new DateTime());
        $this->icrt_type = $icrt_type;
        $this->crt_name = $crt_name;
        $this->crt_weight = $crt_weight;
        $this->crt_forceComment_compare = $crt_forceComment_compare;
        $this->crt_forceComment_value = $crt_forceComment_value;
        $this->crt_forceComment_sign = $crt_forceComment_sign;
        $this->crt_lowerbound = $crt_lowerbound;
        $this->crt_upperbound = $crt_upperbound;
        $this->crt_step = $crt_step;
        $this->crt_comment = $crt_comment;
        $this->crt_inserted = $crt_inserted;
        $this->stage = $stage;
        $this->participants = $participants?$participants:new ArrayCollection();
        $this->cName = $cName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIcrtType(): ?int
    {
        return $this->icrt_type;
    }

    public function setIcrtType(int $icrt_type): self
    {
        $this->icrt_type = $icrt_type;

        return $this;
    }

    public function getCrtName(): ?string
    {
        return $this->crt_name;
    }

    public function setCrtName(?string $crt_name): self
    {
        $this->crt_name = $crt_name;

        return $this;
    }

    public function getCrtWeight(): ?float
    {
        return $this->crt_weight;
    }

    public function setCrtWeight(float $crt_weight): self
    {
        $this->crt_weight = $crt_weight;

        return $this;
    }

    public function getCrtForceCommentCompare(): ?bool
    {
        return $this->crt_forceComment_compare;
    }

    public function setCrtForceCommentCompare(bool $crt_forceComment_compare): self
    {
        $this->crt_forceComment_compare = $crt_forceComment_compare;

        return $this;
    }

    public function getCrtForceCommentValue(): ?float
    {
        return $this->crt_forceComment_value;
    }

    public function setCrtForceCommentValue(?float $crt_forceComment_value): self
    {
        $this->crt_forceComment_value = $crt_forceComment_value;

        return $this;
    }

    public function getCrtForceCommentSign(): ?string
    {
        return $this->crt_forceComment_sign;
    }

    public function setCrtForceCommentSign(?string $crt_forceComment_sign): self
    {
        $this->crt_forceComment_sign = $crt_forceComment_sign;

        return $this;
    }

    public function getCrtLowerbound(): ?float
    {
        return $this->crt_lowerbound;
    }

    public function setCrtLowerbound(?float $crt_lowerbound): self
    {
        $this->crt_lowerbound = $crt_lowerbound;

        return $this;
    }

    public function getCrtUpperbound(): ?float
    {
        return $this->crt_upperbound;
    }

    public function setCrtUpperbound(?float $crt_upperbound): self
    {
        $this->crt_upperbound = $crt_upperbound;

        return $this;
    }

    public function getCrtStep(): ?float
    {
        return $this->crt_step;
    }

    public function setCrtStep(?float $crt_step): self
    {
        $this->crt_step = $crt_step;

        return $this;
    }

    public function getCrtComment(): ?string
    {
        return $this->crt_comment;
    }

    public function setCrtComment(string $crt_comment): self
    {
        $this->crt_comment = $crt_comment;

        return $this;
    }

    public function getCrtCreatedBy(): ?int
    {
        return $this->crt_createdBy;
    }

    public function setCrtCreatedBy(?int $crt_createdBy): self
    {
        $this->crt_createdBy = $crt_createdBy;

        return $this;
    }

    public function getCrtInserted(): ?\DateTimeInterface
    {
        return $this->crt_inserted;
    }

    public function setCrtInserted(?\DateTimeInterface $crt_inserted): self
    {
        $this->crt_inserted = $crt_inserted;

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
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param mixed $participants
     */
    public function setParticipants($participants): void
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
    function addParticipant(TemplateActivityUser $participant){
        $this->participants->add($participant);
        $participant->setCriterion($this);
        return $this;
    }


    function removeParticipant(TemplateActivityUser $participant){
        // Remove this participant
        $this->participants->removeElement($participant);
        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }



}
