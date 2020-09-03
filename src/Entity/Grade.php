<?php

namespace App\Entity;

use App\Repository\GradeRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
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
    public $gradedUsrId;

    /**
     * @ORM\Column(name="grd_graded_tea_id", type="integer", nullable=true)
     */
    public $gradedTeaId;

    /**
     * @ORM\Column(name="grd_value", type="float", nullable=true)
     */
    public $value;

    /**
     * @ORM\Column(name="grd_comment", type="string", nullable=true)
     */
    public $comment;

    /**
     * @ORM\Column(name="grd_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="grd_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="Team", inversedBy="grades")
     * @JoinColumn(name="activity_user_team_tea_id", referencedColumnName="tea_id", nullable=true)
     */
    protected $team;
    /**
     * @ManyToOne(targetEntity="Participation", inversedBy="grades")
     * @JoinColumn(name="activity_user_user_usr_id", referencedColumnName="par_id",nullable=false)
     * @var Participation
     */
    protected $participation;
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
     * @param $type
     * @param $gradedUsrId
     * @param $gradedTeaId
     * @param $value
     * @param $comment
     * @param $createdBy
     * @param $inserted
     * @param $team
     * @param Participation $participation
     * @param $activity
     * @param $criterion
     * @param $stage
     */
    //TODO gérer les controllers
    public function __construct(
      ?int $id = 0,
        $type = 1,
        $gradedUsrId = null,
        $gradedTeaId = null,
        $value = null,
        $comment = null,
        $createdBy = null,
        $inserted = null,
        Team $team = null,
        Participation $participation = null,
        Activity$activity = null,
        Criterion $criterion = null,
        Stage $stage = null)
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->type = $type;
        $this->gradedUsrId = $gradedUsrId;
        $this->gradedTeaId = $gradedTeaId;
        $this->value = $value;
        $this->comment = $comment;
        $this->team = $team;
        $this->participation = $participation;
        $this->activity = $activity;
        $this->criterion = $criterion;
        $this->stage = $stage;
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

    public function getGradedUsrId(): ?int
    {
        return $this->gradedUsrId;
    }

    public function setGradedUsrId(int $gradedUsrId): self
    {
        $this->gradedUsrId = $gradedUsrId;

        return $this;
    }

    public function getGradedTeaId(): ?int
    {
        return $this->gradedTeaId;
    }

    public function setGradedTeaId(int $gradedTeaId): self
    {
        $this->gradedTeaId = $gradedTeaId;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

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

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

        return $this;
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
     * @return Participation
     */
    public function getParticipation(){
        return $this->participation;
    }

    /**
     * @param Participation $participation
     */
    public function setParticipation(Participation $participation): self
    {
        $this->participant = $participation;
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

}
