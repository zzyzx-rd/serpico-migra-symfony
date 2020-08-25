<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CriterionRepository;
use DateTime;
use DateTimeInterface;
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
    public ?int $id;

    /**
     * @ORM\Column(name="cri_complete", type="boolean", nullable=true)
     */
    public $complete;

    /**
     * @ORM\Column(name="cri_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="cri_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="cri_weight", type="float", nullable=true)
     */
    public $weight;

    /**
     * @ORM\Column(name="cri_forceComment_compare", type="boolean", nullable=true)
     */
    public $forceComment_compare;

    /**
     * @ORM\Column(name="cri_forceCommentValue", type="float", nullable=true)
     */
    public $forceCommentValue;

    /**
     * @ORM\Column(name="cri_forceComment_sign", type="string", length=255, nullable=true)
     */
    public $forceComment_sign;

    /**
     * @ORM\Column(name="cri_lowerbound", type="float", nullable=true)
     */
    public $lowerbound;

    /**
     * @ORM\Column(name="cri_upperbound", type="float", nullable=true)
     */
    public $upperbound;

    /**
     * @ORM\Column(name="cri_step", type="float", nullable=true)
     */
    public $step;

    /**
     * @ORM\Column(name="cri_grade_type", type="integer", nullable=true)
     */
    public $grade_type;

    /**
     * @ORM\Column(name="cri_comment", type="string", length=255, nullable=true)
     */
    public $comment;

    /**
     * @ORM\Column(name="cri_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="cri_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="cri_deleted", type="datetime", nullable=true)
     */
    public $deleted;

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
     * @OneToMany(targetEntity="Participation", mappedBy="criterion",cascade={"persist", "remove"}, orphanRemoval=true)
     * @var Collection
     */
//     * @OrderBy({"leader" = "DESC"})
    public $participants;

    /**
     * @ManyToOne(targetEntity="CriterionName")
     * @JoinColumn(name="criterion_name_cna_id", referencedColumnName="cna_id", nullable=true)
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
        $cri_comment = '',
        $cri_createdBy = null,
        $cri_inserted = null,
        $cri_deleted = null,
        //TODO Gérer la création dans les controlleurs
        Stage $stage = null,
        Organization $organization = null,
        Collection $participants = null,
        $cName = null,
        $target = null,
        $grades = null,
        Result $results = null,
        ResultTeam $resultTeams = null,
        Ranking $rankings = null,
        RankingTeam $rankingTeams = null,
        RankingHistory $historicalRankings = null,
        RankingTeamHistory $historicalRankingTeams = null,
        $template = null)
    {
        parent::__construct($id, $cri_createdBy, new DateTime());
        $this->complete = $cri_complete;
        $this->type = $cri_type;
        $this->name = $cri_name;
        $this->weight = $cri_weight;
        $this->forceComment_compare = $cri_forceComment_compare;
        $this->forceCommentValue = $cri_forceCommentValue;
        $this->forceComment_sign = $cri_forceComment_sign;
        $this->lowerbound = $cri_lowerbound;
        $this->upperbound = $cri_upperbound;
        $this->step = $cri_step;
        $this->grade_type = $cri_grade_type;
        $this->comment = $cri_comment;
        $this->createdBy = $cri_createdBy;
        $this->deleted = $cri_deleted;
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


    public function getComplete(): ?bool
    {
        return $this->complete;
    }

    public function setComplete(bool $cri_complete): self
    {
        $this->complete = $cri_complete;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $cri_type): self
    {
        $this->type = $cri_type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $cri_name): self
    {
        $this->name = $cri_name;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $cri_weight): self
    {
        $this->weight = $cri_weight;

        return $this;
    }

    public function getForceCommentCompare(): ?bool
    {
        return $this->forceComment_compare;
    }

    public function setForceCommentCompare(bool $cri_forceComment_compare): self
    {
        $this->forceComment_compare = $cri_forceComment_compare;

        return $this;
    }

    public function getForceCommentValue(): ?float
    {
        return $this->forceCommentValue;
    }

    public function setForceCommentValue(?float $cri_forceCommentValue): self
    {
        $this->forceCommentValue = $cri_forceCommentValue;

        return $this;
    }

    public function getForceCommentSign(): ?string
    {
        return $this->forceComment_sign;
    }

    public function setForceCommentSign(?string $cri_forceComment_sign): self
    {
        $this->forceComment_sign = $cri_forceComment_sign;

        return $this;
    }

    public function getLowerbound(): ?float
    {
        return $this->lowerbound;
    }

    public function setLowerbound(?float $cri_lowerbound): self
    {
        $this->lowerbound = $cri_lowerbound;

        return $this;
    }

    public function getUpperbound(): ?float
    {
        return $this->upperbound;
    }

    public function setUpperbound(?float $cri_upperbound): self
    {
        $this->upperbound = $cri_upperbound;

        return $this;
    }

    public function getStep(): ?float
    {
        return $this->step;
    }

    public function setStep(?float $cri_step): self
    {
        $this->step = $cri_step;

        return $this;
    }

    public function getGradeType(): ?int
    {
        return $this->grade_type;
    }

    public function setGradeType(int $cri_grade_type): self
    {
        $this->grade_type = $cri_grade_type;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $cri_comment): self
    {
        $this->comment = $cri_comment;

        return $this;
    }

    public function setInserted(?DateTimeInterface $cri_inserted): self
    {
        $this->inserted = $cri_inserted;

        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeInterface $cri_deleted): self
    {
        $this->deleted = $cri_deleted;

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
        return $this;
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
    public function setOrganization($organization)
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param Collection $participants
     */
    public function setParticipants(Collection $participants)
    {
        $this->participants = $participants;
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
    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
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
    public function setGrades($grades)
    {
        $this->grades = $grades;
        return $this;
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
    public function setResults($results)
    {
        $this->results = $results;
        return $this;
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
    public function setResultTeams($resultTeams)
    {
        $this->resultTeams = $resultTeams;
        return $this;
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
    public function setRankings($rankings)
    {
        $this->rankings = $rankings;
        return $this;
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
    public function setRankingTeams($rankingTeams)
    {
        $this->rankingTeams = $rankingTeams;
        return $this;
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
    public function setHistoricalRankings($historicalRankings)
    {
        $this->historicalRankings = $historicalRankings;
        return $this;
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
    public function setHistoricalRankingTeams($historicalRankingTeams)
    {
        $this->historicalRankingTeams = $historicalRankingTeams;
        return $this;
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
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    public function addGrade(Grade $grade): Criterion
    {

        $this->grades->add($grade);
        $grade->setCriterion($this);
        return $this;
    }

    public function removeGrade(Grade $grade): Criterion
    {
        $this->grades->removeElement($grade);
        return $this;
    }

    public function addParticipant(Participation $participant): Criterion
    {
        $this->participants->add($participant);
        $participant->setCriterion($this);
        return $this;
    }


    public function removeParticipant(Participation $participant): Criterion
    {
        // Remove this participant
        $this->participants->removeElement($participant);
        return $this;
    }

    public function addResult(Result $result): Criterion
    {
        $this->results->add($result);
        $result->setCriterion($this);
        return $this;
    }

    public function removeResult(Result $result): Criterion
    {
        $this->results->removeElement($result);
        return $this;
    }

    public function addRankings(Ranking $ranking): Criterion
    {
        $this->rankings->add($ranking);
        $ranking->setActivity($this);
        return $this;
    }

    public function removeRanking(Ranking $ranking): Criterion
    {
        $this->rankings->removeElement($ranking);
        return $this;
    }

    public function addHistoricalRankings(RankingHistory $historicalRanking): Criterion
    {
        $this->historicalRankings->add($historicalRanking);
        $historicalRanking->setActivity($this);
        return $this;
    }

    public function removeHistoricalRanking(RankingHistory $ranking): Criterion
    {
        $this->rankings->removeElement($ranking);
        return $this;
    }

    public function addResultTeam(ResultTeam $resultTeam): Criterion
    {
        $this->resultTeams->add($resultTeam);
        $resultTeam->setCriterion($this);
        return $this;
    }

    public function removeResultTeam(ResultTeam $resultTeam): Criterion
    {
        $this->resultTeams->removeElement($resultTeam);
        return $this;
    }

    public function addRankingTeam(RankingTeam $rankingTeam): Criterion
    {
        $this->rankingTeams->add($rankingTeam);
        $rankingTeam->setActivity($this);
        return $this;
    }

    public function removeRankingTeam(RankingTeam $rankingTeam): Criterion
    {
        $this->rankingTeams->removeElement($rankingTeam);
        return $this;
    }

    public function addHistoricalRankingTeam(RankingTeamHistory $historicalRankingTeam): Criterion
    {
        $this->historicalRankingTeams->add($historicalRankingTeam);
        $historicalRankingTeam->setActivity($this);
        return $this;
    }

    public function removeHistoricalRankingTeam(RankingTeamHistory $historicalRankingTeam): Criterion
    {
        $this->historicalRankingTeams->removeElement($historicalRankingTeam);
        return $this;
    }

    public function getGlobalParticipants(): array
    {
        $teams = [];
        $globalParticipants = [];
        foreach($this->participants as $participant){
            $team = $participant->getTeam();
            if($team == null){
                $globalParticipants[] = $participant;
            } else if(!in_array($team,$teams)){
                $globalParticipants[] = $participant;
                $teams[] = $team;
            }
        }
        return $globalParticipants;
    }

    public function getTargetValue(): ?float
    {
        if($this->target !== null){
            return $this->target->getValue();
        }

        return 0.7;
    }

    public function __toString()
    {
        return (string) $this->id;
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->cName,
            'lowerbound' => $this->lowerbound,
            'upperbound' => $this->upperbound,
            'step' => $this->step,
            'weight'=> $this->weight
        ];
    }
}
