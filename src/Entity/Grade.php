<?php

namespace App\Entity;

use App\Repository\GradeRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity(repositoryClass=GradeRepository::class)
 */
class Grade extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="grd_id", length=10, type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $grd_type;

    /**
     * @ORM\Column(type="integer")
     */
    public $grd_graded_usr_id;

    /**
     * @ORM\Column(type="integer")
     */
    public $grd_graded_tea_id;

    /**
     * @ORM\Column(type="float")
     */
    public $grd_value;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $grd_comment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $grd_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $grd_inserted;

    /**
     * @ManyToOne(targetEntity="Team", inversedBy="grades")
     * @JoinColumn(name="activity_user_team_tea_id", referencedColumnName="tea_id")
     */
    protected $team;
    /**
     * @ManyToOne(targetEntity="ActivityUser", inversedBy="grades")
     * @JoinColumn(name="activity_user_user_usr_id", referencedColumnName="a_u_id",nullable=false)
     * @var ActivityUser
     */
    protected $participant;
    /**
     * @ManyToOne(targetEntity="Activity", inversedBy="grades")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id",nullable=false)
     */
    protected $activity;
    /**
     * @ManyToOne(targetEntity="Criterion", inversedBy="grades")
     * @JoinColumn(name="criterion_crt_id", referencedColumnName="crt_id",nullable=false)
     */
    protected $criterion;
    /**
     * @ManyToOne(targetEntity="Stage", inversedBy="grades")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id",nullable=false)
     */
    protected $stage;

    /**
     * Grade constructor.
     * @param int $id
     * @param $grd_type
     * @param $grd_graded_usr_id
     * @param $grd_graded_tea_id
     * @param $grd_value
     * @param $grd_comment
     * @param $grd_createdBy
     * @param $grd_inserted
     * @param $team
     * @param ActivityUser $participant
     * @param $activity
     * @param $criterion
     * @param $stage
     */
    //TODO gérer les controllers
    public function __construct(
        int $id = 0,
        $grd_type = 1,
        $grd_graded_usr_id = null,
        $grd_graded_tea_id = null,
        $grd_value = null,
        $grd_comment = null,
        $grd_createdBy = null,
        $grd_inserted = null,
        Team $team = null,
        ActivityUser $participant,
        Activity$activity,
        Criterion $criterion,
        Stage $stage)
    {
        parent::__construct($id, $grd_createdBy, new DateTime());
        $this->grd_type = $grd_type;
        $this->grd_graded_usr_id = $grd_graded_usr_id;
        $this->grd_graded_tea_id = $grd_graded_tea_id;
        $this->grd_value = $grd_value;
        $this->grd_comment = $grd_comment;
        $this->grd_inserted = $grd_inserted;
        $this->team = $team;
        $this->participant = $participant;
        $this->activity = $activity;
        $this->criterion = $criterion;
        $this->stage = $stage;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->grd_type;
    }

    public function setType(int $grd_type): self
    {
        $this->grd_type = $grd_type;

        return $this;
    }

    public function getGradedUsrId(): ?int
    {
        return $this->grd_graded_usr_id;
    }

    public function setGradedUsrId(int $grd_graded_usr_id): self
    {
        $this->grd_graded_usr_id = $grd_graded_usr_id;

        return $this;
    }

    public function getGradedTeaId(): ?int
    {
        return $this->grd_graded_tea_id;
    }

    public function setGradedTeaId(int $grd_graded_tea_id): self
    {
        $this->grd_graded_tea_id = $grd_graded_tea_id;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->grd_value;
    }

    public function setValue(float $grd_value): self
    {
        $this->grd_value = $grd_value;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->grd_comment;
    }

    public function setComment(?string $grd_comment): self
    {
        $this->grd_comment = $grd_comment;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->grd_inserted;
    }

    public function setInserted(\DateTimeInterface $grd_inserted): self
    {
        $this->grd_inserted = $grd_inserted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGradedTeam()
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
     * @return ActivityUser
     */
    public function getGradedParticipant(): ActivityUser
    {
        return $this->participant;
    }

    /**
     * @param ActivityUser $participant
     */
    public function setParticipant(ActivityUser $participant): void
    {
        $this->participant = $participant;
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

}
