<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CriterionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CriterionRepository::class)
 */
class Criterion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cri_complete;

    /**
     * @ORM\Column(type="integer")
     */
    private $cri_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cri_name;

    /**
     * @ORM\Column(type="float")
     */
    private $cri_weight;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cri_forceComment_compare;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cri_forceCommentValue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cri_forceComment_sign;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cri_lowerbound;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cri_upperbound;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cri_step;

    /**
     * @ORM\Column(type="integer")
     */
    private $cri_grade_type;

    /**
     * @ORM\Column(type="float")
     */
    private $cri_avg_ae_res;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cri_avg_rw_res;

    /**
     * @ORM\Column(type="float")
     */
    private $cri_avg_re_res;

    /**
     * @ORM\Column(type="float")
     */
    private $cri_max_w_dev;

    /**
     * @ORM\Column(type="float")
     */
    private $cri_max_e_dev;

    /**
     * @ORM\Column(type="float")
     */
    private $cri_avg_w_dev;

    /**
     * @ORM\Column(type="float")
     */
    private $cri_avg_e_dev;

    /**
     * @ORM\Column(type="float")
     */
    private $cri_w_inertia;

    /**
     * @ORM\Column(type="float")
     */
    private $cri_e_inertia;

    /**
     * @ORM\Column(type="float")
     */
    private $cri_max_w_inertia;

    /**
     * @ORM\Column(type="float")
     */
    private $cri_max_e_inertia;

    /**
     * @ORM\Column(type="float")
     */
    private $cri_w_distratio;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cri_comment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cri_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $cri_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $cri_deleted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCriComplete(): ?bool
    {
        return $this->cri_complete;
    }

    public function setCriComplete(bool $cri_complete): self
    {
        $this->cri_complete = $cri_complete;

        return $this;
    }

    public function getCriType(): ?int
    {
        return $this->cri_type;
    }

    public function setCriType(int $cri_type): self
    {
        $this->cri_type = $cri_type;

        return $this;
    }

    public function getCriName(): ?string
    {
        return $this->cri_name;
    }

    public function setCriName(string $cri_name): self
    {
        $this->cri_name = $cri_name;

        return $this;
    }

    public function getCriWeight(): ?float
    {
        return $this->cri_weight;
    }

    public function setCriWeight(float $cri_weight): self
    {
        $this->cri_weight = $cri_weight;

        return $this;
    }

    public function getCriForceCommentCompare(): ?bool
    {
        return $this->cri_forceComment_compare;
    }

    public function setCriForceCommentCompare(bool $cri_forceComment_compare): self
    {
        $this->cri_forceComment_compare = $cri_forceComment_compare;

        return $this;
    }

    public function getCriForceCommentValue(): ?float
    {
        return $this->cri_forceCommentValue;
    }

    public function setCriForceCommentValue(?float $cri_forceCommentValue): self
    {
        $this->cri_forceCommentValue = $cri_forceCommentValue;

        return $this;
    }

    public function getCriForceCommentSign(): ?string
    {
        return $this->cri_forceComment_sign;
    }

    public function setCriForceCommentSign(?string $cri_forceComment_sign): self
    {
        $this->cri_forceComment_sign = $cri_forceComment_sign;

        return $this;
    }

    public function getCriLowerbound(): ?float
    {
        return $this->cri_lowerbound;
    }

    public function setCriLowerbound(?float $cri_lowerbound): self
    {
        $this->cri_lowerbound = $cri_lowerbound;

        return $this;
    }

    public function getCriUpperbound(): ?float
    {
        return $this->cri_upperbound;
    }

    public function setCriUpperbound(?float $cri_upperbound): self
    {
        $this->cri_upperbound = $cri_upperbound;

        return $this;
    }

    public function getCriStep(): ?float
    {
        return $this->cri_step;
    }

    public function setCriStep(?float $cri_step): self
    {
        $this->cri_step = $cri_step;

        return $this;
    }

    public function getCriGradeType(): ?int
    {
        return $this->cri_grade_type;
    }

    public function setCriGradeType(int $cri_grade_type): self
    {
        $this->cri_grade_type = $cri_grade_type;

        return $this;
    }

    public function getCriAvgAeRes(): ?float
    {
        return $this->cri_avg_ae_res;
    }

    public function setCriAvgAeRes(float $cri_avg_ae_res): self
    {
        $this->cri_avg_ae_res = $cri_avg_ae_res;

        return $this;
    }

    public function getCriAvgRwRes(): ?string
    {
        return $this->cri_avg_rw_res;
    }

    public function setCriAvgRwRes(string $cri_avg_rw_res): self
    {
        $this->cri_avg_rw_res = $cri_avg_rw_res;

        return $this;
    }

    public function getCriAvgReRes(): ?float
    {
        return $this->cri_avg_re_res;
    }

    public function setCriAvgReRes(float $cri_avg_re_res): self
    {
        $this->cri_avg_re_res = $cri_avg_re_res;

        return $this;
    }

    public function getCriMaxWDev(): ?float
    {
        return $this->cri_max_w_dev;
    }

    public function setCriMaxWDev(float $cri_max_w_dev): self
    {
        $this->cri_max_w_dev = $cri_max_w_dev;

        return $this;
    }

    public function getCriMaxEDev(): ?float
    {
        return $this->cri_max_e_dev;
    }

    public function setCriMaxEDev(float $cri_max_e_dev): self
    {
        $this->cri_max_e_dev = $cri_max_e_dev;

        return $this;
    }

    public function getCriAvgWDev(): ?float
    {
        return $this->cri_avg_w_dev;
    }

    public function setCriAvgWDev(float $cri_avg_w_dev): self
    {
        $this->cri_avg_w_dev = $cri_avg_w_dev;

        return $this;
    }

    public function getCriAvgEDev(): ?float
    {
        return $this->cri_avg_e_dev;
    }

    public function setCriAvgEDev(float $cri_avg_e_dev): self
    {
        $this->cri_avg_e_dev = $cri_avg_e_dev;

        return $this;
    }

    public function getCriWInertia(): ?float
    {
        return $this->cri_w_inertia;
    }

    public function setCriWInertia(float $cri_w_inertia): self
    {
        $this->cri_w_inertia = $cri_w_inertia;

        return $this;
    }

    public function getCriEInertia(): ?float
    {
        return $this->cri_e_inertia;
    }

    public function setCriEInertia(float $cri_e_inertia): self
    {
        $this->cri_e_inertia = $cri_e_inertia;

        return $this;
    }

    public function getCriMaxWInertia(): ?float
    {
        return $this->cri_max_w_inertia;
    }

    public function setCriMaxWInertia(float $cri_max_w_inertia): self
    {
        $this->cri_max_w_inertia = $cri_max_w_inertia;

        return $this;
    }

    public function getCriMaxEInertia(): ?float
    {
        return $this->cri_max_e_inertia;
    }

    public function setCriMaxEInertia(float $cri_max_e_inertia): self
    {
        $this->cri_max_e_inertia = $cri_max_e_inertia;

        return $this;
    }

    public function getCriWDistratio(): ?float
    {
        return $this->cri_w_distratio;
    }

    public function setCriWDistratio(float $cri_w_distratio): self
    {
        $this->cri_w_distratio = $cri_w_distratio;

        return $this;
    }

    public function getCriComment(): ?string
    {
        return $this->cri_comment;
    }

    public function setCriComment(string $cri_comment): self
    {
        $this->cri_comment = $cri_comment;

        return $this;
    }

    public function getCriCreatedBy(): ?int
    {
        return $this->cri_createdBy;
    }

    public function setCriCreatedBy(?int $cri_createdBy): self
    {
        $this->cri_createdBy = $cri_createdBy;

        return $this;
    }

    public function getCriInserted(): ?\DateTimeInterface
    {
        return $this->cri_inserted;
    }

    public function setCriInserted(?\DateTimeInterface $cri_inserted): self
    {
        $this->cri_inserted = $cri_inserted;

        return $this;
    }

    public function getCriDeleted(): ?\DateTimeInterface
    {
        return $this->cri_deleted;
    }

    public function setCriDeleted(?\DateTimeInterface $cri_deleted): self
    {
        $this->cri_deleted = $cri_deleted;

        return $this;
    }
}
