<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\IProcessCriterionRepository;
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
class IProcessCriterion
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCrtType(): ?int
    {
        return $this->crt_type;
    }

    public function setCrtType(int $crt_type): self
    {
        $this->crt_type = $crt_type;

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

    public function getCrtLowerBound(): ?float
    {
        return $this->crt_lowerBound;
    }

    public function setCrtLowerBound(?float $crt_lowerBound): self
    {
        $this->crt_lowerBound = $crt_lowerBound;

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

}
