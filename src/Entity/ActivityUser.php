<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ActivityUserRepository;
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
 * @ORM\Entity(repositoryClass=ActivityUserRepository::class)
 */
class ActivityUser
{
    const PARTICIPATION_ACTIVE      = 1;
    const PARTICIPATION_THIRD_PARTY = 0;
    const PARTICIPATION_PASSIVE     = -1;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @Column(name="a_u_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $a_u_status;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $a_u_aw_result;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $a_u_ae_result;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $a_u_re_result;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $a_u_e_dev;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $a_u_w_devratio;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $a_u_e_devratio;

    /**
     * @ORM\Column(type="boolean")
     */
    private $a_u_leader;

    /**
     * @ORM\Column(type="integer")
     */
    private $a_u_type;

    /**
     * @ORM\Column(type="float")
     */
    private $a_u_mWeight;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $a_u_precomment;

    /**
     * @ORM\Column(type="float")
     */
    private $a_u_ivp_bonus;

    /**
     * @ORM\Column(type="float")
     */
    private $a_u_ivp_penalty;

    /**
     * @ORM\Column(type="float")
     */
    private $a_u_of_bonus;

    /**
     * @ORM\Column(type="float")
     */
    private $a_u_of_penalty;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $a_u_mailed;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $a_u_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $a_u_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $a_u_confirmed;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $a_u_deleted;
    /**
     * @OneToMany(targetEntity="Grade", mappedBy="participant",cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection
     */
    private $grades;
    /**
     * @ManyToOne(targetEntity="Team")
     * @JoinColumn(name="team_tea_id", referencedColumnName="tea_id",nullable=false)
     */
    protected $team;
    /**
     * @ManyToOne(targetEntity="Activity")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id",nullable=false)
     */
    protected $activity;
    /**
     * @ManyToOne(targetEntity="Stage")
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="external_user")
     */
    private $user_usr;

    /**
     * @ORM\ManyToOne(targetEntity=ExternalUser::class, inversedBy="activity_user_act_usr")
     */
    private $external_user_ext_usr;

    /**
     * ActivityUser constructor.
     * @param int $id
     * @param int $a_u_status
     * @param float $a_u_aw_result
     * @param float $a_u_ae_result
     * @param float $a_u_re_result
     * @param float $a_u_e_dev
     * @param float $a_u_w_devratio
     * @param float $a_u_e_devratio
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
     * @param Answer[]|ArrayCollection $answers
     * @param $user_usr
     * @param $external_user_ext_usr
     */
    public function __construct(
        int $id = 0,
        int $a_u_status= 0,
        float $a_u_aw_result = null,
        float $a_u_ae_result = null,
        float $a_u_re_result = null,
        float $a_u_e_dev = null,
        float $a_u_w_devratio = null,
        float $a_u_e_devratio = null,
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
        ArrayCollection $grades,
        Team $team = null,
        Activity $activity,
        Stage $stage,
        Criterion $criterion,
        Survey $survey = null,
        Answer $answers = null,
        User $user_usr,
        ExternalUser $external_user_ext_usr = null)
    {
        $this->id = $id;
        $this->a_u_status = $a_u_status;
        $this->a_u_aw_result = $a_u_aw_result;
        $this->a_u_ae_result = $a_u_ae_result;
        $this->a_u_re_result = $a_u_re_result;
        $this->a_u_e_dev = $a_u_e_dev;
        $this->a_u_w_devratio = $a_u_w_devratio;
        $this->a_u_e_devratio = $a_u_e_devratio;
        $this->a_u_leader = $a_u_leader;
        $this->a_u_type = $a_u_type;
        $this->a_u_mWeight = $a_u_mWeight;
        $this->a_u_precomment = $a_u_precomment;
        $this->a_u_ivp_bonus = $a_u_ivp_bonus;
        $this->a_u_ivp_penalty = $a_u_ivp_penalty;
        $this->a_u_of_bonus = $a_u_of_bonus;
        $this->a_u_of_penalty = $a_u_of_penalty;
        $this->a_u_mailed = $a_u_mailed;
        $this->a_u_createdBy = $a_u_createdBy;
        $this->a_u_inserted = $a_u_inserted;
        $this->a_u_confirmed = $a_u_confirmed;
        $this->a_u_deleted = $a_u_deleted;
        $this->grades = $grades;
        $this->team = $team;
        $this->activity = $activity;
        $this->stage = $stage;
        $this->criterion = $criterion;
        $this->survey = $survey;
        $this->answers = $answers;
        $this->user_usr = $user_usr;
        $this->external_user_ext_usr = $external_user_ext_usr;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->a_u_status;
    }

    public function setStatus(int $a_u_status): self
    {
        $this->a_u_status = $a_u_status;

        return $this;
    }

    public function getAbsoluteWeightedResult(): ?float
    {
        return $this->a_u_aw_result;
    }

    public function setAbsoluteWeightedResult(?float $a_u_aw_result): self
    {
        $this->a_u_aw_result = $a_u_aw_result;

        return $this;
    }

    public function getAbsoluteEqualResult(): ?float
    {
        return $this->a_u_ae_result;
    }

    public function setAbsoluteEqualResult(?float $a_u_ae_result): self
    {
        $this->a_u_ae_result = $a_u_ae_result;

        return $this;
    }

    public function getReResult(): ?float
    {
        return $this->a_u_re_result;
    }

    public function setReResult(?float $a_u_re_result): self
    {
        $this->a_u_re_result = $a_u_re_result;

        return $this;
    }

    public function getEDev(): ?float
    {
        return $this->a_u_e_dev;
    }

    public function setEDev(?float $a_u_e_dev): self
    {
        $this->a_u_e_dev = $a_u_e_dev;

        return $this;
    }

    public function getWeightedDevRatio(): ?float
    {
        return $this->a_u_w_devratio;
    }

    public function setWeightedDevRatio(?float $a_u_w_devratio): self
    {
        $this->a_u_w_devratio = $a_u_w_devratio;

        return $this;
    }

    public function getEqualDevRatio(): ?float
    {
        return $this->a_u_e_devratio;
    }

    public function setEDevratio(?float $a_u_e_devratio): self
    {
        $this->a_u_e_devratio = $a_u_e_devratio;

        return $this;
    }

    public function getLeader(): ?bool
    {
        return $this->a_u_leader;
    }

    public function setLeader(bool $a_u_leader): self
    {
        $this->a_u_leader = $a_u_leader;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->a_u_type;
    }

    public function setType(int $a_u_type): self
    {
        $this->a_u_type = $a_u_type;

        return $this;
    }

    public function getMWeight(): ?float
    {
        return $this->a_u_mWeight;
    }

    public function setMWeight(float $a_u_mWeight): self
    {
        $this->a_u_mWeight = $a_u_mWeight;

        return $this;
    }

    public function getPrecomment(): ?string
    {
        return $this->a_u_precomment;
    }

    public function setPrecomment(?string $a_u_precomment): self
    {
        $this->a_u_precomment = $a_u_precomment;

        return $this;
    }

    public function getIvpBonus(): ?float
    {
        return $this->a_u_ivp_bonus;
    }

    public function setIvpBonus(float $a_u_ivp_bonus): self
    {
        $this->a_u_ivp_bonus = $a_u_ivp_bonus;

        return $this;
    }

    public function getIvpPenalty(): ?float
    {
        return $this->a_u_ivp_penalty;
    }

    public function setIvpPenalty(float $a_u_ivp_penalty): self
    {
        $this->a_u_ivp_penalty = $a_u_ivp_penalty;

        return $this;
    }

    public function getOfBonus(): ?float
    {
        return $this->a_u_of_bonus;
    }

    public function setOfBonus(float $a_u_of_bonus): self
    {
        $this->a_u_of_bonus = $a_u_of_bonus;

        return $this;
    }

    public function getOfPenalty(): ?float
    {
        return $this->a_u_of_penalty;
    }

    public function setOfPenalty(float $a_u_of_penalty): self
    {
        $this->a_u_of_penalty = $a_u_of_penalty;

        return $this;
    }

    public function getisMailed(): ?bool
    {
        return $this->a_u_mailed;
    }

    public function setIsMailed(?bool $a_u_mailed): self
    {
        $this->a_u_mailed = $a_u_mailed;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->a_u_createdBy;
    }

    public function setCreatedBy(?int $a_u_createdBy): self
    {
        $this->a_u_createdBy = $a_u_createdBy;

        return $this;
    }

    public function getInserted(): ?DateTimeInterface
    {
        return $this->a_u_inserted;
    }

    public function setInserted(?DateTimeInterface $a_u_inserted): self
    {
        $this->a_u_inserted = $a_u_inserted;

        return $this;
    }

    public function getConfirmed(): ?DateTimeInterface
    {
        return $this->a_u_confirmed;
    }

    public function setConfirmed(?DateTimeInterface $a_u_confirmed): self
    {
        $this->a_u_confirmed = $a_u_confirmed;

        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->a_u_deleted;
    }

    public function setDeleted(?DateTimeInterface $a_u_deleted): self
    {
        $this->a_u_deleted = $a_u_deleted;

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

    public function getUserUsr(): ?User
    {
        return $this->user_usr;
    }

    public function setUserUsr(?User $user_usr): self
    {
        $this->user_usr = $user_usr;

        return $this;
    }

    public function getExternalUserExtUsr(): ?ExternalUser
    {
        return $this->external_user_ext_usr;
    }

    public function setExternalUserExtUsr(?ExternalUser $external_user_ext_usr): self
    {
        $this->external_user_ext_usr = $external_user_ext_usr;

        return $this;
    }

    /** Add of not default methods
     * @param Answer $answer
     * @return ActivityUser
     */
    public function addAnswer(Answer $answer)
    {
        $this->answers->add($answer);
        $answer->setParticipant($this);
        return $this;
    }

    public function removeAnswer(Answer $answer)
    {
        $this->answers->removeElement($answer);
        return $this;
    }
    public function addGrade(Grade $grade)
    {

        $this->grades->add($grade);
        $grade->setParticipant($this);
        return $this;
    }

    public function removeGrade(Grade $grade)
    {
        $this->grades->removeElement($grade);
        return $this;
    }

    /** @return array
     * @var Grade[]
     */
    public function getReceivedGrades(){

        $receivedGrades = new ArrayCollection;
        $myParticipations = $this->stage->getSelfParticipations();
        $myTeam = $myParticipations->count() == 0 ? null : $myParticipations->first()->getTeam();
        if($myTeam != $this->team && $this->team != null){

            $unsortedReceivedGrades = $this->stage->getGrades()->matching(Criteria::create()
                ->where(Criteria::expr()->in("participant", $myParticipations->getValues()))
                ->andWhere(Criteria::expr()->eq("gradedTeaId", $this->team->getId()))
                ->andWhere(Criteria::expr()->eq("gradedUsrId", null))
            );

        } else {

            $unsortedReceivedGrades = $this->stage->getGrades()->matching(Criteria::create()
                ->where(Criteria::expr()->in("participant", $myParticipations->getValues()))
                ->andWhere(Criteria::expr()->eq("gradedTeaId", $this->team ? $this->team->getId() : null))
                ->andWhere(Criteria::expr()->eq("gradedUsrId", $this->usrId))
            );

        }

        foreach($unsortedReceivedGrades as $unsortedReceivedGrade){
            $receivedGrades->add($unsortedReceivedGrade);
        }

        return $receivedGrades->getValues();
    }
    function addReceivedGrade(Grade $grade){
        $this->grades->add($grade);
        $grade->setParticipant($this);
        return $this;
    }

    function removeReceivedGrade(Grade $grade){
        $this->grades->removeElement($grade);
        return $this;
    }
}
