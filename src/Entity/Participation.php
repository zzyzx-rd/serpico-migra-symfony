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
     * @Column(name="par_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="par_status", type="integer", nullable=true)
     */
    public $status;

    /**
     * @ORM\Column(name="par_leader", type="boolean", nullable=true)
     */
    public $leader;

    /**
     * @ORM\Column(name="par_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="par_mWeight", type="float", nullable=true)
     */
    public $mWeight;

    /**
     * @ORM\Column(name="par_precomment", type="string", length=10, nullable=true)
     */
    public $precomment;

    /**
     * @ORM\Column(name="par_ivp_bonus", type="float", nullable=true)
     */
    public $ivp_bonus;

    /**
     * @ORM\Column(name="par_ivp_penalty", type="float", nullable=true)
     */
    public $ivp_penalty;

    /**
     * @ORM\Column(name="par_of_bonus", type="float", nullable=true)
     */
    public $of_bonus;

    /**
     * @ORM\Column(name="par_of_penalty", type="float", nullable=true)
     */
    public $of_penalty;

    /**
     * @ORM\Column(name="par_mailed", type="boolean", nullable=true)
     */
    public $mailed;

    /**
     * @ORM\Column(name="par_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="par_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="par_confirmed", type="datetime", nullable=true)
     */
    public $confirmed;

    /**
     * @ORM\Column(name="par_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @OneToMany(targetEntity="Grade", mappedBy="participation",cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection
     */
    public $grades;
    /**
     * @ManyToOne(targetEntity="Team", inversedBy="participations")
     * @JoinColumn(name="team_tea_id", referencedColumnName="tea_id",nullable=true)
     */
    protected $team;
    /**
     * @ManyToOne(targetEntity="Activity", inversedBy="participations")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id",nullable=false)
     */
    protected $activity;
    /**
     * @ManyToOne(targetEntity="Stage", inversedBy="participations")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id",nullable=false)
     */
    protected $stage;
    /**
     * @ManyToOne(targetEntity="Criterion", inversedBy="participations")
     * @JoinColumn(name="criterion_crt_id", referencedColumnName="crt_id",nullable=true)
     */
    protected $criterion;
    /**
     * @ManyToOne(targetEntity="Survey", inversedBy="participations")
     * @JoinColumn(name="survey_sur_id", referencedColumnName="sur_id", nullable=true)
     */
    protected $survey;
    /**
     *
     * @OneToMany(targetEntity="Answer", mappedBy="participation", cascade={"persist", "remove"}, orphanRemoval=true)
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
     * @param int|null $id
     * @param int $parstatus
     * @param bool $parleader
     * @param int $partype
     * @param float $parmWeight
     * @param string $precomment
     * @param float|null $ivp_bonus
     * @param float|null $ivp_penalty
     * @param float|null $of_bonus
     * @param float|null $of_penalty
     * @param bool|null $mailed
     * @param int|null $createdBy
     * @param DateTime|null $inserted
     * @param DateTime|null $confirmed
     * @param DateTime|null $deleted
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
        int $status= 0,
        bool $leader = false,
        int $type = 1,
        float $mWeight = 0.0,
        string $precomment = "",
        float $ivp_bonus = null,
        float $ivp_penalty = null,
        float $of_bonus = null,
        float $of_penalty = null,
        bool $mailed = null,
        int $createdBy = null,
        DateTime $inserted = null,
        DateTime $confirmed = null,
        DateTime $deleted = null,
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
        parent::__construct($id, $createdBy, new DateTime());
        $this->status = $status;
        $this->leader = $leader;
        $this->type = $type;
        $this->mWeight = $mWeight;
        $this->precomment = $precomment;
        $this->ivp_bonus = $ivp_bonus;
        $this->ivp_penalty = $ivp_penalty;
        $this->of_bonus = $of_bonus;
        $this->of_penalty = $of_penalty;
        $this->mailed = $mailed;
        $this->confirmed = $confirmed;
        $this->deleted = $deleted;
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

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function isLeader(): ?bool
    {
        return $this->leader;
    }

    public function setLeader(bool $leader): self
    {
        $this->leader = $leader;

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

    public function getMWeight(): ?float
    {
        return $this->mWeight;
    }

    public function setMWeight(float $mWeight): self
    {
        $this->mWeight = $mWeight;

        return $this;
    }

    public function getPrecomment(): ?string
    {
        return $this->precomment;
    }

    public function setPrecomment(?string $precomment): self
    {
        $this->precomment = $precomment;

        return $this;
    }

    public function getIvpBonus(): ?float
    {
        return $this->ivp_bonus;
    }

    public function setIvpBonus(float $ivp_bonus): self
    {
        $this->ivp_bonus = $ivp_bonus;

        return $this;
    }

    public function getIvpPenalty(): ?float
    {
        return $this->ivp_penalty;
    }

    public function setIvpPenalty(float $ivp_penalty): self
    {
        $this->ivp_penalty = $ivp_penalty;

        return $this;
    }

    public function getOfBonus(): ?float
    {
        return $this->of_bonus;
    }

    public function setOfBonus(float $of_bonus): self
    {
        $this->of_bonus = $of_bonus;

        return $this;
    }

    public function getOfPenalty(): ?float
    {
        return $this->of_penalty;
    }

    public function setOfPenalty(float $of_penalty): self
    {
        $this->of_penalty = $of_penalty;

        return $this;
    }

    public function getisMailed(): ?bool
    {
        return $this->mailed;
    }

    public function setIsMailed(?bool $mailed): self
    {
        $this->mailed = $mailed;

        return $this;
    }

    public function setInserted(?DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

        return $this;
    }

    public function getConfirmed(): ?DateTimeInterface
    {
        return $this->confirmed;
    }

    public function setConfirmed(?DateTimeInterface $confirmed): self
    {
        $this->confirmed = $confirmed;

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
     * @return ArrayCollection
     */
    public function getGrades(): ArrayCollection
    {
        return $this->grades;
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
    public function setTeam($team): self
    {
        $this->team = $team;
        return $this;
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
    public function setActivity($activity): self
    {
        $this->activity = $activity;
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
    public function getCriterion()
    {
        return $this->criterion;
    }

    /**
     * @param mixed $criterion
     */
    public function setCriterion($criterion): self
    {
        $this->criterion = $criterion;
        return $this;
    }

    /**
     * @return ArrayCollection|Answer[]
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
    public function getDirectUser(){
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
        $answer->setParticipation($this);
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
        $grade->setParticipation($this);
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
        $grade->setParticipation($this);
        return $this;
    }

    public function removeReceivedGrade(Grade $grade): Participation
    {
        $this->grades->removeElement($grade);
        return $this;
    }

    public function getSurvey(): ?Survey
    {
        return $this->survey;
    }

    public function setSurvey(?Survey $survey): self
    {
        $this->survey = $survey;
        return $this;
    }
}
