<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CriterionRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
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
    public $forceCommentCompare;

    /**
     * @ORM\Column(name="cri_forceCommentValue", type="float", nullable=true)
     */
    public $forceCommentValue;

    /**
     * @ORM\Column(name="cri_forceComment_sign", type="string", length=255, nullable=true)
     */
    public $forceCommentSign;

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
    public $gradeType;

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
     * @OneToMany(targetEntity="Participation", mappedBy="criterion", cascade={"persist", "remove"}, orphanRemoval=true)
     * @OrderBy({"leader" = "DESC"})
     */
    public $participations;

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
     * @param $complete
     * @param $type
     * @param $name
     * @param $weight
     * @param $forceCommentCompare
     * @param $forceCommentValue
     * @param $forceCommentSign
     * @param $lowerbound
     * @param $upperbound
     * @param $step
     * @param $gradeType
     * @param $comment
     * @param $createdBy
     * @param $inserted
     * @param $deleted
     * @param $stage
     * @param $organization
     * @param $cName
     * @param $target
     * @param $participations
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
        $complete = false,
        $type = 1,
        $name = '',
        $weight = 1,
        $lowerbound = 0,
        $upperbound = 5,
        $step = 0.5,
        $forceCommentCompare = null,
        $forceCommentValue = null,
        $forceCommentSign = null,
        $gradeType = 1,
        $comment = '',
        $createdBy = null,
        $inserted = null,
        $deleted = null,
        //TODO Gérer la création dans les controlleurs
        Stage $stage = null,
        Organization $organization = null,
        CriterionName $cName = null,
        $target = null,
        $participations = null,
        $grades = null,
        $results = null,
        $resultTeams = null,
        $rankings = null,
        $rankingTeams = null,
        $historicalRankings = null,
        $historicalRankingTeams = null,
        $template = null)
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->complete = $complete;
        $this->type = $type;
        $this->name = $name;
        $this->weight = $weight;
        $this->forceCommentCompare = $forceCommentCompare;
        $this->forceCommentValue = $forceCommentValue;
        $this->forceCommentSign = $forceCommentSign;
        $this->lowerbound = $lowerbound;
        $this->upperbound = $upperbound;
        $this->step = $step;
        $this->gradeType = $gradeType;
        $this->comment = $comment;
        $this->createdBy = $createdBy;
        $this->deleted = $deleted;
        $this->stage = $stage;
        $this->organization = $organization;
        $this->participants = $participations ?: new ArrayCollection;
        $this->cName = $cName;
        $this->target = $target;
        $this->grades = $grades ?: new ArrayCollection;
        $this->results = $results ?: new ArrayCollection;
        $this->resultTeams = $resultTeams ?: new ArrayCollection;
        $this->rankings = $rankings ?: new ArrayCollection;
        $this->rankingTeams = $rankingTeams ?: new ArrayCollection;
        $this->historicalRankings = $historicalRankings ?: new ArrayCollection;
        $this->historicalRankingTeams = $historicalRankingTeams ?: new ArrayCollection;
        $this->template = $template;
    }


    public function getComplete(): ?bool
    {
        return $this->complete;
    }

    public function setComplete(bool $complete): self
    {
        $this->complete = $complete;
        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function getForceCommentCompare(): ?bool
    {
        return $this->forceCommentCompare;
    }

    public function setForceCommentCompare(bool $forceCommentCompare): self
    {
        $this->forceCommentCompare = $forceCommentCompare;

        return $this;
    }

    public function getForceCommentValue(): ?float
    {
        return $this->forceCommentValue;
    }

    public function setForceCommentValue(?float $forceCommentValue): self
    {
        $this->forceCommentValue = $forceCommentValue;

        return $this;
    }

    public function getForceCommentSign(): ?string
    {
        return $this->forceCommentSign;
    }

    public function setForceCommentSign(?string $forceCommentSign): self
    {
        $this->forceCommentSign = $forceCommentSign;
        return $this;
    }

    public function getLowerbound(): ?float
    {
        return $this->lowerbound;
    }

    public function setLowerbound(?float $lowerbound): self
    {
        $this->lowerbound = $lowerbound;
        return $this;
    }

    public function getUpperbound(): ?float
    {
        return $this->upperbound;
    }

    public function setUpperbound(?float $upperbound): self
    {
        $this->upperbound = $upperbound;
        return $this;
    }

    public function getStep(): ?float
    {
        return $this->step;
    }

    public function setStep(?float $step): self
    {
        $this->step = $step;
        return $this;
    }

    public function getGradeType(): ?int
    {
        return $this->gradeType;
    }

    public function setGradeType(int $gradeType): self
    {
        $this->gradeType = $gradeType;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function setInserted(?DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeInterface $deleted): self
    {
        $this->deleted = $deleted;
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
    public function setStage($stage): self
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
    public function setOrganization($organization): self
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getParticipations()
    {
        return $this->participations;
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
     * @return ArrayCollection|Grade[]
     */
    public function getGrades()
    {
        return $this->grades;
    }

    /**
     * @return ArrayCollection|Result[]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @return ArrayCollection|ResultTeam[]
     */
    public function getResultTeams()
    {
        return $this->resultTeams;
    }

    /**
     * @return ArrayCollection|Ranking[]
     */
    public function getRankings()
    {
        return $this->rankings;
    }

    /**
     * @return mixed
     */
    public function getRankingTeams()
    {
        return $this->rankingTeams;
    }

    /**
     * @return mixed
     */
    public function getHistoricalRankings()
    {
        return $this->historicalRankings;
    }

    /**
     * @return mixed
     */
    public function getHistoricalRankingTeams()
    {
        return $this->historicalRankingTeams;
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

    public function addParticipation(Participation $participation): Criterion
    {
        $this->participations->add($participation);
        $participation->setCriterion($this);
        return $this;
    }


    public function removeParticipation(Participation $participation): Criterion
    {
        // Remove this participation
        $this->participations->removeElement($participation);
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

    public function addRanking(Ranking $ranking): Criterion
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
