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
use phpDocumentor\Reflection\Types\Null_;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CriterionRepository::class)
 */
class Criterion extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="crt_id", type="integer", nullable=false)
     */
    public $id;

    /**
     * @ORM\Column(type="boolean")
     */
    public $cri_complete;

    /**
     * @ORM\Column(type="integer")
     */
    public $cri_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $cri_name;

    /**
     * @ORM\Column(type="float")
     */
    public $cri_weight;

    /**
     * @ORM\Column(type="boolean")
     */
    public $cri_forceComment_compare;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $cri_forceCommentValue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $cri_forceComment_sign;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $cri_lowerbound;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $cri_upperbound;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $cri_step;

    /**
     * @ORM\Column(type="integer")
     */
    public $cri_grade_type;

    /**
     * @ORM\Column(type="float")
     */
    public $cri_avg_ae_res;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $cri_avg_rw_res;

    /**
     * @ORM\Column(type="float")
     */
    public $cri_avg_re_res;

    /**
     * @ORM\Column(type="float")
     */
    public $cri_max_w_dev;

    /**
     * @ORM\Column(type="float")
     */
    public $cri_max_e_dev;

    /**
     * @ORM\Column(type="float")
     */
    public $cri_avg_w_dev;

    /**
     * @ORM\Column(type="float")
     */
    public $cri_avg_e_dev;

    /**
     * @ORM\Column(type="float")
     */
    public $cri_w_inertia;

    /**
     * @ORM\Column(type="float")
     */
    public $cri_e_inertia;

    /**
     * @ORM\Column(type="float")
     */
    public $cri_max_w_inertia;

    /**
     * @ORM\Column(type="float")
     */
    public $cri_max_e_inertia;

    /**
     * @ORM\Column(type="float")
     */
    public $cri_w_distratio;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $cri_comment;

    /**
     * @ORM\Column(name="cri_created_by", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="cri_inserted", type="datetime", nullable=true)
     */
    public $inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $cri_deleted;

    /**
     * @ManyToOne(targetEntity="Stage", inversedBy="criteria")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id",nullable=true)
     */
    protected $stage;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="criteria")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=true)
     */
    protected $organization;

    /**
     * @OneToMany(targetEntity="ActivityUser", mappedBy="criterion",cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"leader" = "DESC"})
     * @var Collection
     */
    public $participants;

    /**
     * @ManyToOne(targetEntity="CriterionName")
     * @JoinColumn(name="criterion_name_cna_id", referencedColumnName="cna_id")
     */
    protected $cName;
    /**
     * @OneToOne(targetEntity="Target", mappedBy="criterion",cascade={"persist"}, orphanRemoval=true)
     */
    public $target;
    /**
     * @OneToMany(targetEntity="Grade", mappedBy="criterion", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $grades;
    /**
     * @OneToMany(targetEntity="Result", mappedBy="criterion",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $results;
    /**
     * @OneToMany(targetEntity="ResultTeam", mappedBy="criterion",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $resultTeams;
    /**
     * @OneToMany(targetEntity="Ranking", mappedBy="criterion",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $rankings;
    /**
     * @OneToMany(targetEntity="RankingTeam", mappedBy="criterion",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $rankingTeams;
    /**
     * @OneToMany(targetEntity="RankingHistory", mappedBy="criterion",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $historicalRankings;
    /**
     * @OneToMany(targetEntity="RankingTeamHistory", mappedBy="criterion",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $historicalRankingTeams;
    /**
     * @OneToOne(targetEntity="Template", mappedBy="criterion",cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $template;

    /**
     * Criterion constructor.
     * @param $id
     * @param $cri_complete
     * @param $cri_type
     * @param $cri_name
     * @param $cri_weight
     * @param $cri_forceComment_compare
     * @param $cri_forceCommentValue
     * @param $cri_forceComment_sign
     * @param $cri_lowerbound
     * @param $cri_upperbound
     * @param $cri_step
     * @param $cri_grade_type
     * @param $cri_avg_ae_res
     * @param $cri_avg_rw_res
     * @param $cri_avg_re_res
     * @param $cri_max_w_dev
     * @param $cri_max_e_dev
     * @param $cri_avg_w_dev
     * @param $cri_avg_e_dev
     * @param $cri_w_inertia
     * @param $cri_e_inertia
     * @param $cri_max_w_inertia
     * @param $cri_max_e_inertia
     * @param $cri_w_distratio
     * @param $cri_comment
     * @param $cri_createdBy
     * @param $cri_inserted
     * @param $cri_deleted
     * @param $stage
     * @param $organization
     * @param Collection $participants
     * @param $cName
     * @param $target
     * @param $grades
     * @param $results
     * @param $resultTeams
     * @param $rankings
     * @param $rankingTeams
     * @param $historicalRankings
     * @param $historicalRankingTeams
     * @param $template
     */
    public function __construct(
        $id = 0,
        $cri_complete = false,
        $cri_type = 1,
        $cri_name = '',
        $cri_weight = 1,
        $cri_lowerbound = 0,
        $cri_upperbound = 5,
        $cri_step = 0.5,
        $cri_forceComment_compare = null,
        $cri_forceCommentValue = null,
        $cri_forceComment_sign = null,
        $cri_grade_type = 1,
        $cri_avg_ae_res = null,
        $cri_avg_rw_res = null,
        $cri_avg_re_res = null,
        $cri_max_w_dev = null,
        $cri_max_e_dev = null,
        $cri_avg_w_dev = null,
        $cri_avg_e_dev = null,
        $cri_w_inertia = null,
        $cri_e_inertia = null,
        $cri_max_w_inertia = null,
        $cri_max_e_inertia = null,
        $cri_w_distratio = null,
        $cri_comment = '',
        $cri_createdBy = null,
        $cri_inserted = null,
        $cri_deleted = null,
        //TODO Gérer la création dans les controlleurs
        Stage $stage = null,
        Organization $organization,
        Collection $participants,
        $cName,
        $target,
        $grades,
        Result $results = null,
        ResultTeam $resultTeams = null,
        Ranking $rankings = null,
        RankingTeam $rankingTeams = null,
        RankingHistory $historicalRankings = null,
        RankingTeamHistory $historicalRankingTeams = null,
        $template = null)
    {
        $this->id = $id;
        $this->cri_complete = $cri_complete;
        $this->cri_type = $cri_type;
        $this->cri_name = $cri_name;
        $this->cri_weight = $cri_weight;
        $this->cri_forceComment_compare = $cri_forceComment_compare;
        $this->cri_forceCommentValue = $cri_forceCommentValue;
        $this->cri_forceComment_sign = $cri_forceComment_sign;
        $this->cri_lowerbound = $cri_lowerbound;
        $this->cri_upperbound = $cri_upperbound;
        $this->cri_step = $cri_step;
        $this->cri_grade_type = $cri_grade_type;
        $this->cri_avg_ae_res = $cri_avg_ae_res;
        $this->cri_avg_rw_res = $cri_avg_rw_res;
        $this->cri_avg_re_res = $cri_avg_re_res;
        $this->cri_max_w_dev = $cri_max_w_dev;
        $this->cri_max_e_dev = $cri_max_e_dev;
        $this->cri_avg_w_dev = $cri_avg_w_dev;
        $this->cri_avg_e_dev = $cri_avg_e_dev;
        $this->cri_w_inertia = $cri_w_inertia;
        $this->cri_e_inertia = $cri_e_inertia;
        $this->cri_max_w_inertia = $cri_max_w_inertia;
        $this->cri_max_e_inertia = $cri_max_e_inertia;
        $this->cri_w_distratio = $cri_w_distratio;
        $this->cri_comment = $cri_comment;
        $this->createdBy = $cri_createdBy;
        $this->inserted = $cri_inserted;
        $this->cri_deleted = $cri_deleted;
        $this->stage = $stage;
        $this->organization = $organization;
        $this->participants = $participants;
        $this->cName = $cName;
        $this->target = $target;
        $this->grades = $grades;
        $this->results = $results;
        $this->resultTeams = $resultTeams;
        $this->rankings = $rankings;
        $this->rankingTeams = $rankingTeams;
        $this->historicalRankings = $historicalRankings;
        $this->historicalRankingTeams = $historicalRankingTeams;
        $this->template = $template;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComplete(): ?bool
    {
        return $this->cri_complete;
    }

    public function setComplete(bool $cri_complete): self
    {
        $this->cri_complete = $cri_complete;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->cri_type;
    }

    public function setType(int $cri_type): self
    {
        $this->cri_type = $cri_type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->cri_name;
    }

    public function setName(string $cri_name): self
    {
        $this->cri_name = $cri_name;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->cri_weight;
    }

    public function setWeight(float $cri_weight): self
    {
        $this->cri_weight = $cri_weight;

        return $this;
    }

    public function getForceCommentCompare(): ?bool
    {
        return $this->cri_forceComment_compare;
    }

    public function setForceCommentCompare(bool $cri_forceComment_compare): self
    {
        $this->cri_forceComment_compare = $cri_forceComment_compare;

        return $this;
    }

    public function getForceCommentValue(): ?float
    {
        return $this->cri_forceCommentValue;
    }

    public function setForceCommentValue(?float $cri_forceCommentValue): self
    {
        $this->cri_forceCommentValue = $cri_forceCommentValue;

        return $this;
    }

    public function getForceCommentSign(): ?string
    {
        return $this->cri_forceComment_sign;
    }

    public function setForceCommentSign(?string $cri_forceComment_sign): self
    {
        $this->cri_forceComment_sign = $cri_forceComment_sign;

        return $this;
    }

    public function getLowerbound(): ?float
    {
        return $this->cri_lowerbound;
    }

    public function setLowerbound(?float $cri_lowerbound): self
    {
        $this->cri_lowerbound = $cri_lowerbound;

        return $this;
    }

    public function getUpperbound(): ?float
    {
        return $this->cri_upperbound;
    }

    public function setUpperbound(?float $cri_upperbound): self
    {
        $this->cri_upperbound = $cri_upperbound;

        return $this;
    }

    public function getStep(): ?float
    {
        return $this->cri_step;
    }

    public function setStep(?float $cri_step): self
    {
        $this->cri_step = $cri_step;

        return $this;
    }

    public function getGradeType(): ?int
    {
        return $this->cri_grade_type;
    }

    public function setGradeType(int $cri_grade_type): self
    {
        $this->cri_grade_type = $cri_grade_type;

        return $this;
    }

    public function getAvgAeRes(): ?float
    {
        return $this->cri_avg_ae_res;
    }

    public function setAvgAeRes(float $cri_avg_ae_res): self
    {
        $this->cri_avg_ae_res = $cri_avg_ae_res;

        return $this;
    }

    public function getAvgRwRes(): ?string
    {
        return $this->cri_avg_rw_res;
    }

    public function setAvgRwRes(string $cri_avg_rw_res): self
    {
        $this->cri_avg_rw_res = $cri_avg_rw_res;

        return $this;
    }

    public function getAvgReRes(): ?float
    {
        return $this->cri_avg_re_res;
    }

    public function setAvgReRes(float $cri_avg_re_res): self
    {
        $this->cri_avg_re_res = $cri_avg_re_res;

        return $this;
    }

    public function getMaxWDev(): ?float
    {
        return $this->cri_max_w_dev;
    }

    public function setMaxWDev(float $cri_max_w_dev): self
    {
        $this->cri_max_w_dev = $cri_max_w_dev;

        return $this;
    }

    public function getMaxEDev(): ?float
    {
        return $this->cri_max_e_dev;
    }

    public function setMaxEDev(float $cri_max_e_dev): self
    {
        $this->cri_max_e_dev = $cri_max_e_dev;

        return $this;
    }

    public function getAvgWDev(): ?float
    {
        return $this->cri_avg_w_dev;
    }

    public function setAvgWDev(float $cri_avg_w_dev): self
    {
        $this->cri_avg_w_dev = $cri_avg_w_dev;

        return $this;
    }

    public function getAvgEDev(): ?float
    {
        return $this->cri_avg_e_dev;
    }

    public function setAvgEDev(float $cri_avg_e_dev): self
    {
        $this->cri_avg_e_dev = $cri_avg_e_dev;

        return $this;
    }

    public function getWInertia(): ?float
    {
        return $this->cri_w_inertia;
    }

    public function setWInertia(float $cri_w_inertia): self
    {
        $this->cri_w_inertia = $cri_w_inertia;

        return $this;
    }

    public function getEInertia(): ?float
    {
        return $this->cri_e_inertia;
    }

    public function setEInertia(float $cri_e_inertia): self
    {
        $this->cri_e_inertia = $cri_e_inertia;

        return $this;
    }

    public function getMaxWInertia(): ?float
    {
        return $this->cri_max_w_inertia;
    }

    public function setMaxWInertia(float $cri_max_w_inertia): self
    {
        $this->cri_max_w_inertia = $cri_max_w_inertia;

        return $this;
    }

    public function getMaxEInertia(): ?float
    {
        return $this->cri_max_e_inertia;
    }

    public function setMaxEInertia(float $cri_max_e_inertia): self
    {
        $this->cri_max_e_inertia = $cri_max_e_inertia;

        return $this;
    }

    public function getWDistratio(): ?float
    {
        return $this->cri_w_distratio;
    }

    public function setWDistratio(float $cri_w_distratio): self
    {
        $this->cri_w_distratio = $cri_w_distratio;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->cri_comment;
    }

    public function setComment(string $cri_comment): self
    {
        $this->cri_comment = $cri_comment;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->inserted;
    }

    public function setInserted(?\DateTimeInterface $cri_inserted): self
    {
        $this->inserted = $cri_inserted;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->cri_deleted;
    }

    public function setDeleted(?\DateTimeInterface $cri_deleted): self
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

    function addGrade(Grade $grade){

        $this->grades->add($grade);
        $grade->setCriterion($this);
        return $this;
    }

    function removeGrade(Grade $grade){
        $this->grades->removeElement($grade);
        return $this;
    }

    function addParticipant(ActivityUser $participant){
        $this->participants->add($participant);
        $participant->setCriterion($this);
        return $this;
    }


    function removeParticipant(ActivityUser $participant){
        // Remove this participant
        $this->participants->removeElement($participant);
        return $this;
    }

    function addResult(Result $result){
        $this->results->add($result);
        $result->setCriterion($this);
        return $this;
    }

    function removeResult(Result $result){
        $this->results->removeElement($result);
        return $this;
    }

    function addRankings(Ranking $ranking){
        $this->rankings->add($ranking);
        $ranking->setActivity($this);
        return $this;
    }

    function removeRanking(Ranking $ranking){
        $this->rankings->removeElement($ranking);
        return $this;
    }

    function addHistoricalRankings(RankingHistory $historicalRanking){
        $this->historicalRankings->add($historicalRanking);
        $historicalRanking->setActivity($this);
        return $this;
    }

    function removeHistoricalRanking(RankingHistory $ranking){
        $this->rankings->removeElement($ranking);
        return $this;
    }

    function addResultTeam(ResultTeam $resultTeam){
        $this->resultTeams->add($resultTeam);
        $resultTeam->setCriterion($this);
        return $this;
    }

    function removeResultTeam(ResultTeam $resultTeam){
        $this->resultTeams->removeElement($resultTeam);
        return $this;
    }

    function addRankingTeam(RankingTeam $rankingTeam){
        $this->rankingTeams->add($rankingTeam);
        $rankingTeam->setActivity($this);
        return $this;
    }

    function removeRankingTeam(RankingTeam $rankingTeam){
        $this->rankingTeams->removeElement($rankingTeam);
        return $this;
    }

    function addHistoricalRankingTeam(RankingTeamHistory $historicalRankingTeam){
        $this->historicalRankingTeams->add($historicalRankingTeam);
        $historicalRankingTeam->setActivity($this);
        return $this;
    }

    function removeHistoricalRankingTeam(RankingTeamHistory $historicalRankingTeam){
        $this->historicalRankingTeams->removeElement($historicalRankingTeam);
        return $this;
    }

    public function getGlobalParticipants(){
        $teams = [];
        $globalParticipants = [];
        foreach($this->participants as $participant){
            $team = $participant->getTeam();
            if($team == null){
                $globalParticipants[] = $participant;
            } else {
                if(!in_array($team,$teams)){
                    $globalParticipants[] = $participant;
                    $teams[] = $team;
                }
            }
        }
        return $globalParticipants;
    }

    public function getTargetValue()
    {
        if($this->target != null){
            return $this->target->getValue();
        } else {
            return 0.7;
        }
    }

    public function __toString()
    {
        return (string) $this->id;
    }


    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->cName,
            'lowerbound' => $this->cri_lowerbound,
            'upperbound' => $this->cri_upperbound,
            'step' => $this->cri_step,
            'weight'=> $this->cri_weight
        ];
    }
}
