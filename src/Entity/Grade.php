<?php

namespace App\Entity;

use App\Repository\GradeRepository;
use DateTime;
use DateTimeInterface;
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
     * @ORM\Column(name="grd_id", length=10, type="integer", nullable=true)
     * @var int
     */
    protected ?int $id;


    /**
     * @ORM\Column(name="grd_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="grd_graded_usr_id", type="integer", nullable=true)
     */
    public $graded_usr_id;

    /**
     * @ORM\Column(name="grd_graded_tea_id", type="integer", nullable=true)
     */
    public $graded_tea_id;

    /**
     * @ORM\Column(name="grd_value", type="float", nullable=true)
     */
    public $value;

    /**
     * @ORM\Column(name="grd_comment", type="string", length=255, nullable=true)
     */
    public $comment;

    /**
     * @ORM\Column(name="grd_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="grd_inserted", type="datetime", nullable=true)
     */
    public ?DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="Team", inversedBy="grades")
     * @JoinColumn(name="activity_user_team_tea_id", referencedColumnName="tea_id", nullable=true)
     */
    protected $team;
    /**
     * @ManyToOne(targetEntity="Participation", inversedBy="grades")
     * @JoinColumn(name="activity_user_user_usr_id", referencedColumnName="a_u_id",nullable=false)
     * @var Participation
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
     * @param ?int$id
     * @param $grd_type
     * @param $grd_graded_usr_id
     * @param $grd_graded_tea_id
     * @param $grd_value
     * @param $grd_comment
     * @param $grd_createdBy
     * @param $grd_inserted
     * @param $team
     * @param Participation $participant
     * @param $activity
     * @param $criterion
     * @param $stage
     */
    //TODO gérer les controllers
    public function __construct(
      ?int $id = 0,
        $grd_type = 1,
        $grd_graded_usr_id = null,
        $grd_graded_tea_id = null,
        $grd_value = null,
        $grd_comment = null,
        $grd_createdBy = null,
        $grd_inserted = null,
        Team $team = null,
        Participation $participant = null,
        Activity$activity = null,
        Criterion $criterion = null,
        Stage $stage = null)
    {
        parent::__construct($id, $grd_createdBy, new DateTime());
        $this->type = $grd_type;
        $this->graded_usr_id = $grd_graded_usr_id;
        $this->graded_tea_id = $grd_graded_tea_id;
        $this->value = $grd_value;
        $this->comment = $grd_comment;
        $this->inserted = $grd_inserted;
        $this->team = $team;
        $this->participant = $participant;
        $this->activity = $activity;
        $this->criterion = $criterion;
        $this->stage = $stage;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $grd_type): self
    {
        $this->type = $grd_type;

        return $this;
    }

    public function getGradedUsrId(): ?int
    {
        return $this->graded_usr_id;
    }

    public function setGradedUsrId(int $grd_graded_usr_id): self
    {
        $this->graded_usr_id = $grd_graded_usr_id;

        return $this;
    }

    public function getGradedTeaId(): ?int
    {
        return $this->graded_tea_id;
    }

    public function setGradedTeaId(int $grd_graded_tea_id): self
    {
        $this->graded_tea_id = $grd_graded_tea_id;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $grd_value): self
    {
        $this->value = $grd_value;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $grd_comment): self
    {
        $this->comment = $grd_comment;

        return $this;
    }

    public function setInserted(DateTimeInterface $grd_inserted): self
    {
        $this->inserted = $grd_inserted;

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
     * @return Participation
     */
    public function getGradedParticipant(): Participation
    {
        return $this->participant;
    }

    /**
     * @param Participation $participant
     */
    public function setParticipant(Participation $participant): void
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
