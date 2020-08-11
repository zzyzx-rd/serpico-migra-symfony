<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\IProcessCriterionRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;

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
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $crt_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $crt_name;

    /**
     * @ORM\Column(type="float")
     */
    private $crt_weight;

    /**
     * @ORM\Column(type="boolean")
     */
    private $crt_forceComment_compare;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $crt_forceComment_value;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $crt_forceComment_sign;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $crt_lowerBound;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $crt_upperbound;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $crt_step;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $crt_comment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $crt_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $crt_inserted;

    /**
     * @ManyToOne(targetEntity="IProcessStage")
     * @JoinColumn(name="iprocess_stage_stg_id", referencedColumnName="stg_id",nullable=true)
     */
    protected $stage;

    /**
     * @ManyToOne(targetEntity="InstitutionProcess")
     * @JoinColumn(name="iprocess_inp_id", referencedColumnName="inp_id",nullable=true)
     */
    protected $institutionProcess;

    /**
     * @OneToMany(targetEntity="IProcessActivityUser", mappedBy="criterion", cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"leader" = "DESC"})
     * @var Collection
     */
    private $participants;

    /**
     * @OneToOne(targetEntity="CriterionName")
     * @JoinColumn(name="criterion_name_cna_id", referencedColumnName="cna_id")
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
     * @param Collection $participants
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
        $this->crt_type = $crt_type;
        $this->crt_name = $crt_name;
        $this->crt_weight = $crt_weight;
        $this->crt_forceComment_compare = $crt_forceComment_compare;
        $this->crt_forceComment_value = $crt_forceComment_value;
        $this->crt_forceComment_sign = $crt_forceComment_sign;
        $this->crt_lowerBound = $crt_lowerBound;
        $this->crt_upperbound = $crt_upperbound;
        $this->crt_step = $crt_step;
        $this->crt_comment = $crt_comment;
        $this->crt_inserted = $crt_inserted;
        $this->stage = $stage;
        $this->institutionProcess = $institutionProcess;
        $this->participants = $participants;
        $this->cName = $cName;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->crt_type;
    }

    public function setType(int $crt_type): self
    {
        $this->crt_type = $crt_type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->crt_name;
    }

    public function setName(?string $crt_name): self
    {
        $this->crt_name = $crt_name;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->crt_weight;
    }

    public function setWeight(float $crt_weight): self
    {
        $this->crt_weight = $crt_weight;

        return $this;
    }

    public function getForceCommentCompare(): ?bool
    {
        return $this->crt_forceComment_compare;
    }

    public function setForceCommentCompare(bool $crt_forceComment_compare): self
    {
        $this->crt_forceComment_compare = $crt_forceComment_compare;

        return $this;
    }

    public function getForceCommentValue(): ?float
    {
        return $this->crt_forceComment_value;
    }

    public function setForceCommentValue(?float $crt_forceComment_value): self
    {
        $this->crt_forceComment_value = $crt_forceComment_value;

        return $this;
    }

    public function getForceCommentSign(): ?string
    {
        return $this->crt_forceComment_sign;
    }

    public function setForceCommentSign(?string $crt_forceComment_sign): self
    {
        $this->crt_forceComment_sign = $crt_forceComment_sign;

        return $this;
    }

    public function getLowerBound(): ?float
    {
        return $this->crt_lowerBound;
    }

    public function setLowerBound(?float $crt_lowerBound): self
    {
        $this->crt_lowerBound = $crt_lowerBound;

        return $this;
    }

    public function getUpperbound(): ?float
    {
        return $this->crt_upperbound;
    }

    public function setUpperbound(?float $crt_upperbound): self
    {
        $this->crt_upperbound = $crt_upperbound;

        return $this;
    }

    public function getStep(): ?float
    {
        return $this->crt_step;
    }

    public function setStep(?float $crt_step): self
    {
        $this->crt_step = $crt_step;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->crt_comment;
    }

    public function setComment(string $crt_comment): self
    {
        $this->crt_comment = $crt_comment;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->crt_createdBy;
    }

    public function setCreatedBy(?int $crt_createdBy): self
    {
        $this->crt_createdBy = $crt_createdBy;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->crt_inserted;
    }

    public function setInserted(?\DateTimeInterface $crt_inserted): self
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

    /**
     * @return Collection
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    /**
     * @param Collection $participants
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

    function addParticipant(IProcessActivityUser $participant){
        $this->participants->add($participant);
        $participant->setCriterion($this);
        return $this;
    }


    function removeParticipant(IProcessActivityUser $participant){
        // Remove this participant
        $this->participants->removeElement($participant);
        return $this;
    }

}
