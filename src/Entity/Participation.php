<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ParticipationRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ParticipationRepository::class)
 */
class Participation extends DbObject
{
    public const PARTICIPATION_ACTIVE      = 1;
    public const PARTICIPATION_THIRD_PARTY = 0;
    public const PARTICIPATION_PASSIVE     = -1;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @Column(name="a_u_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="a_u_status", type="integer", nullable=true)
     */
    public $status;

    /**
     * @ORM\Column(name="a_u_leader", type="boolean", nullable=true)
     */
    public $leader;

    /**
     * @ORM\Column(name="a_u_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="a_u_mWeight", type="float", nullable=true)
     */
    public $mWeight;

    /**
     * @ORM\Column(name="a_u_precomment", type="string", length=10, nullable=true)
     */
    public $precomment;

    /**
     * @ORM\Column(name="a_u_ivp_bonus", type="float", nullable=true)
     */
    public $ivp_bonus;

    /**
     * @ORM\Column(name="a_u_ivp_penalty", type="float", nullable=true)
     */
    public $ivp_penalty;

    /**
     * @ORM\Column(name="a_u_of_bonus", type="float", nullable=true)
     */
    public $of_bonus;

    /**
     * @ORM\Column(name="a_u_of_penalty", type="float", nullable=true)
     */
    public $of_penalty;

    /**
     * @ORM\Column(name="a_u_mailed", type="boolean", nullable=true)
     */
    public $mailed;

    /**
     * @ORM\Column(name="a_u_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="a_u_inserted", type="datetime", nullable=true)
     */
    public ?DateTime $inserted;

    /**
     * @ORM\Column(name="a_u_confirmed", type="datetime", nullable=true)
     */
    public $confirmed;

    /**
     * @ORM\Column(name="a_u_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @OneToMany(targetEntity="Grade", mappedBy="participant",cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection
     */
    public $grades;
    /**
     * @ManyToOne(targetEntity="Team", inversedBy="participations")
     * @JoinColumn(name="team_tea_id", referencedColumnName="tea_id",nullable=true)
     */
    protected $team;
    /**
     * @ManyToOne(targetEntity="Activity", inversedBy="participants")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id",nullable=false)
     */
    protected $activity;
    /**
     * @ManyToOne(targetEntity="Stage", inversedBy="participants")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id",nullable=false)
     */
    protected $stage;
    /**
     * @ManyToOne(targetEntity="Criterion", inversedBy="participants")
     * @JoinColumn(name="criterion_crt_id", referencedColumnName="crt_id",nullable=true)
     */
    protected $criterion;
    /**
     * @ManyToOne(targetEntity="Survey", inversedBy="participants")
     * @JoinColumn(name="survey_sur_id", referencedColumnName="sur_id",nullable=true)
     */
    protected $survey;
    /**
     *
     * @OneToMany(targetEntity="Answer", mappedBy="participant",cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Answer[] $answers
     */
    protected $answers;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="participations")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id", nullable=false)
     */
    public $user;

    /**
     * @ORM\ManyToOne(targetEntity=ExternalUser::class, inversedBy="participations")
     * @JoinColumn(name="external_user_ext_usr_id", referencedColumnName="ext_id", nullable=true)
     */
    public $externalUser;


    /**
     * Participation constructor.
     * @param int $a_u_status
     * @param bool $a_u_leader
     * @param int $a_u_type
     * @param float $a_u_mWeight
     * @param string $a_u_precomment
     * @param float $a_u_ivp_bonus
     * @param float $a_u_ivp_penalty
     * @param float $a_u_of_bonus
     * @param float $a_u_of_penalty
     * @param bool $a_u_mailed
     * @param int $a_u_createdBy
     * @param DateTime $a_u_inserted
     * @param DateTime $a_u_confirmed
     * @param DateTime $a_u_deleted
     * @param ArrayCollection $grades
     * @param Team $team
     * @param Activity $activity
     * @param Stage $stage
     * @param Criterion $criterion
     * @param Survey|null $survey
     * @param Answer $answers
     * @param User $user
     * @param ExternalUser $externalUser
     */
    public function __construct(
      ?int $id = 0,
        int $a_u_status= 0,
        bool $a_u_leader = false,
        int $a_u_type = 1,
        float $a_u_mWeight = 0.0,
        string $a_u_precomment = "",
        float $a_u_ivp_bonus = null,
        float $a_u_ivp_penalty = null,
        float $a_u_of_bonus = null,
        float $a_u_of_penalty = null,
        bool $a_u_mailed = null,
        int $a_u_createdBy = null,
        DateTime $a_u_inserted = null,
        DateTime $a_u_confirmed = null,
        DateTime $a_u_deleted = null,
        ArrayCollection $grades = null,
        Team $team = null,
        Activity $activity = null,
        Stage $stage = null,
        Criterion $criterion = null,
        Survey $survey = null,
        Answer $answers = null,
        User $user = null,
        ExternalUser $externalUser = null)
    {
        parent::__construct($id, $a_u_createdBy, new DateTime());
        $this->status = $a_u_status;
        $this->leader = $a_u_leader;
        $this->type = $a_u_type;
        $this->mWeight = $a_u_mWeight;
        $this->precomment = $a_u_precomment;
        $this->ivp_bonus = $a_u_ivp_bonus;
        $this->ivp_penalty = $a_u_ivp_penalty;
        $this->of_bonus = $a_u_of_bonus;
        $this->of_penalty = $a_u_of_penalty;
        $this->mailed = $a_u_mailed;
        $this->inserted = $a_u_inserted;
        $this->confirmed = $a_u_confirmed;
        $this->deleted = $a_u_deleted;
        $this->grades = $grades;
        $this->team = $team;
        $this->activity = $activity;
        $this->stage = $stage;
        $this->criterion = $criterion;
        $this->survey = $survey;
        $this->answers = $answers;
        $this->user = $user;
        $this->externalUser = $externalUser;
        $this->id = $id;
    }


    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $a_u_status): self
    {
        $this->status = $a_u_status;

        return $this;
    }

    public function isLeader(): ?bool
    {
        return $this->leader;
    }

    public function setLeader(bool $a_u_leader): self
    {
        $this->leader = $a_u_leader;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $a_u_type): self
    {
        $this->type = $a_u_type;

        return $this;
    }

    public function getMWeight(): ?float
    {
        return $this->mWeight;
    }

    public function setMWeight(float $a_u_mWeight): self
    {
        $this->mWeight = $a_u_mWeight;

        return $this;
    }

    public function getPrecomment(): ?string
    {
        return $this->precomment;
    }

    public function setPrecomment(?string $a_u_precomment): self
    {
        $this->precomment = $a_u_precomment;

        return $this;
    }

    public function getIvpBonus(): ?float
    {
        return $this->ivp_bonus;
    }

    public function setIvpBonus(float $a_u_ivp_bonus): self
    {
        $this->ivp_bonus = $a_u_ivp_bonus;

        return $this;
    }

    public function getIvpPenalty(): ?float
    {
        return $this->ivp_penalty;
    }

    public function setIvpPenalty(float $a_u_ivp_penalty): self
    {
        $this->ivp_penalty = $a_u_ivp_penalty;

        return $this;
    }

    public function getOfBonus(): ?float
    {
        return $this->of_bonus;
    }

    public function setOfBonus(float $a_u_of_bonus): self
    {
        $this->of_bonus = $a_u_of_bonus;

        return $this;
    }

    public function getOfPenalty(): ?float
    {
        return $this->of_penalty;
    }

    public function setOfPenalty(float $a_u_of_penalty): self
    {
        $this->of_penalty = $a_u_of_penalty;

        return $this;
    }

    public function getisMailed(): ?bool
    {
        return $this->mailed;
    }

    public function setIsMailed(?bool $a_u_mailed): self
    {
        $this->mailed = $a_u_mailed;

        return $this;
    }

    public function setInserted(?DateTimeInterface $a_u_inserted): self
    {
        $this->inserted = $a_u_inserted;

        return $this;
    }

    public function getConfirmed(): ?DateTimeInterface
    {
        return $this->confirmed;
    }

    public function setConfirmed(?DateTimeInterface $a_u_confirmed): self
    {
        $this->confirmed = $a_u_confirmed;

        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeInterface $a_u_deleted): self
    {
        $this->deleted = $a_u_deleted;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getGrades(): ArrayCollection
    {
        return $this->grades;
    }

    /**
     * @param ArrayCollection $grades
     */
    public function setGrades(ArrayCollection $grades): void
    {
        $this->grades = $grades;
    }

    /**
     * @return mixed
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param mixed $team
     */
    public function setTeam($team): void
    {
        $this->team = $team;
    }

    /**
     * @return mixed
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param mixed $activity
     */
    public function setActivity($activity): void
    {
        $this->activity = $activity;
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
    public function getCriterion()
    {
        return $this->criterion;
    }

    /**
     * @param mixed $criterion
     */
    public function setCriterion($criterion): void
    {
        $this->criterion = $criterion;
    }

    /**
     * @return Answer[]|ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param Answer[]|ArrayCollection $answers
     */
    public function setAnswers($answers): void
    {
        $this->answers = $answers;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getExternalUser(): ?ExternalUser
    {
        return $this->externalUser;
    }

    public function setExternalUser(?ExternalUser $externalUser): self
    {
        $this->externalUser = $externalUser;
        return $this;
    }

    /** Add of not default methods
     * @param Answer $answer
     * @return Participation
     */
    public function addAnswer(Answer $answer): Participation
    {
        $this->answers->add($answer);
        $answer->setParticipant($this);
        return $this;
    }

    public function removeAnswer(Answer $answer): Participation
    {
        $this->answers->removeElement($answer);
        return $this;
    }
    public function addGrade(Grade $grade): Participation
    {

        $this->grades->add($grade);
        $grade->setParticipant($this);
        return $this;
    }

    public function removeGrade(Grade $grade): Participation
    {
        $this->grades->removeElement($grade);
        return $this;
    }

    /** @return array
     * @var Grade[]
     */
    public function getReceivedGrades(): array
    {

        $receivedGrades = new ArrayCollection;
        $myParticipations = $this->stage->getSelfParticipations();
        $myTeam = ($myParticipations->count() == 0) ? null : $myParticipations->first()->getTeam();
        if(($myTeam != $this->team) && ($this->team != null)){

            $unsortedReceivedGrades = $this->stage->getGrades()->matching(Criteria::create()
                ->where(Criteria::expr()->in("participant", $myParticipations->getValues()))
                ->andWhere(Criteria::expr()->eq("gradedTeaId", $this->team->getId()))
                ->andWhere(Criteria::expr()->eq("gradedUsrId", null))
            );

        } else {

            $unsortedReceivedGrades = $this->stage->getGrades()->matching(Criteria::create()
                ->where(Criteria::expr()->in("participant", $myParticipations->getValues()))
                ->andWhere(Criteria::expr()->eq("gradedTeaId", $this->team ? $this->team->getId() : null))
                ->andWhere(Criteria::expr()->eq("gradedUsrId", $this->user->getId()))
            );

        }

        foreach($unsortedReceivedGrades as $unsortedReceivedGrade){
            $receivedGrades->add($unsortedReceivedGrade);
        }

        return $receivedGrades->getValues();
    }
    public function addReceivedGrade(Grade $grade): Participation
    {
        $this->grades->add($grade);
        $grade->setParticipant($this);
        return $this;
    }

    public function removeReceivedGrade(Grade $grade): Participation
    {
        $this->grades->removeElement($grade);
        return $this;
    }
}
