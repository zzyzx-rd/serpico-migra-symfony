<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CriterionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CriterionRepository::class)
 */
class Criterion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="crt_id", type="integer", nullable=false)
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

    /**
     * @ManyToOne(targetEntity="Stage")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id",nullable=true)
     */
    protected $stage;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=true)
     */
    protected $organization;

    /**
     * @OneToMany(targetEntity="ActivityUser", mappedBy="criterion",cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"leader" = "DESC"})
     * @var Collection
     */
    private $participants;

    /**
     * @ManyToOne(targetEntity="CriterionName")
     * @JoinColumn(name="criterion_name_cna_id", referencedColumnName="cna_id")
     */
    protected $cName;
    /**
     * @OneToOne(targetEntity="Target", mappedBy="criterion",cascade={"persist"}, orphanRemoval=true)
     */
    private $target;
    /**
     * @OneToMany(targetEntity="Grade", mappedBy="criterion", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $grades;
    /**
     * @OneToMany(targetEntity="Result", mappedBy="criterion",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $results;
    /**
     * @OneToMany(targetEntity="ResultTeam", mappedBy="criterion",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $resultTeams;
    /**
     * @OneToMany(targetEntity="Ranking", mappedBy="criterion",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $rankings;
    /**
     * @OneToMany(targetEntity="RankingTeam", mappedBy="criterion",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $rankingTeams;
    /**
     * @OneToMany(targetEntity="RankingHistory", mappedBy="criterion",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $historicalRankings;
    /**
     * @OneToMany(targetEntity="RankingTeamHistory", mappedBy="criterion",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $historicalRankingTeams;
    /**
     * @OneToOne(targetEntity="Template", mappedBy="criterion",cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $template;

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
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param mixed $organization
     */
    public function setOrganization($organization): void
    {
        $this->organization = $organization;
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

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param mixed $target
     */
    public function setTarget($target): void
    {
        $this->target = $target;
    }

    /**
     * @return mixed
     */
    public function getGrades()
    {
        return $this->grades;
    }

    /**
     * @param mixed $grades
     */
    public function setGrades($grades): void
    {
        $this->grades = $grades;
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param mixed $results
     */
    public function setResults($results): void
    {
        $this->results = $results;
    }

    /**
     * @return mixed
     */
    public function getResultTeams()
    {
        return $this->resultTeams;
    }

    /**
     * @param mixed $resultTeams
     */
    public function setResultTeams($resultTeams): void
    {
        $this->resultTeams = $resultTeams;
    }

    /**
     * @return mixed
     */
    public function getRankings()
    {
        return $this->rankings;
    }

    /**
     * @param mixed $rankings
     */
    public function setRankings($rankings): void
    {
        $this->rankings = $rankings;
    }

    /**
     * @return mixed
     */
    public function getRankingTeams()
    {
        return $this->rankingTeams;
    }

    /**
     * @param mixed $rankingTeams
     */
    public function setRankingTeams($rankingTeams): void
    {
        $this->rankingTeams = $rankingTeams;
    }

    /**
     * @return mixed
     */
    public function getHistoricalRankings()
    {
        return $this->historicalRankings;
    }

    /**
     * @param mixed $historicalRankings
     */
    public function setHistoricalRankings($historicalRankings): void
    {
        $this->historicalRankings = $historicalRankings;
    }

    /**
     * @return mixed
     */
    public function getHistoricalRankingTeams()
    {
        return $this->historicalRankingTeams;
    }

    /**
     * @param mixed $historicalRankingTeams
     */
    public function setHistoricalRankingTeams($historicalRankingTeams): void
    {
        $this->historicalRankingTeams = $historicalRankingTeams;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template): void
    {
        $this->template = $template;
    }

}
