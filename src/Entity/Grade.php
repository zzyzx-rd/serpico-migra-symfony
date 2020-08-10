<?php

namespace App\Entity;

use App\Repository\GradeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity(repositoryClass=GradeRepository::class)
 */
class Grade
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="grd_id", length=10, type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $grd_type;

    /**
     * @ORM\Column(type="integer")
     */
    private $grd_graded_usr_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $grd_graded_tea_id;

    /**
     * @ORM\Column(type="float")
     */
    private $grd_value;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $grd_comment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $grd_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $grd_inserted;

    /**
     * @ManyToOne(targetEntity="Team")
     * @JoinColumn(name="activity_user_team_tea_id", referencedColumnName="tea_id")
     */
    protected $team;
    /**
     * @ManyToOne(targetEntity="ActivityUser")
     * @JoinColumn(name="activity_user_user_usr_id", referencedColumnName="a_u_id",nullable=false)
     * @var ActivityUser
     */
    protected $participant;
    /**
     * @ManyToOne(targetEntity="Activity")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id",nullable=false)
     */
    protected $activity;
    /**
     * @ManyToOne(targetEntity="Criterion")
     * @JoinColumn(name="criterion_crt_id", referencedColumnName="crt_id",nullable=false)
     */
    protected $criterion;
    /**
     * @ManyToOne(targetEntity="Stage")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id",nullable=false)
     */
    protected $stage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGrdType(): ?int
    {
        return $this->grd_type;
    }

    public function setGrdType(int $grd_type): self
    {
        $this->grd_type = $grd_type;

        return $this;
    }

    public function getGrdGradedUsrId(): ?int
    {
        return $this->grd_graded_usr_id;
    }

    public function setGrdGradedUsrId(int $grd_graded_usr_id): self
    {
        $this->grd_graded_usr_id = $grd_graded_usr_id;

        return $this;
    }

    public function getGrdGradedTeaId(): ?int
    {
        return $this->grd_graded_tea_id;
    }

    public function setGrdGradedTeaId(int $grd_graded_tea_id): self
    {
        $this->grd_graded_tea_id = $grd_graded_tea_id;

        return $this;
    }

    public function getGrdValue(): ?float
    {
        return $this->grd_value;
    }

    public function setGrdValue(float $grd_value): self
    {
        $this->grd_value = $grd_value;

        return $this;
    }

    public function getGrdComment(): ?string
    {
        return $this->grd_comment;
    }

    public function setGrdComment(?string $grd_comment): self
    {
        $this->grd_comment = $grd_comment;

        return $this;
    }

    public function getGrdCreatedBy(): ?int
    {
        return $this->grd_createdBy;
    }

    public function setGrdCreatedBy(?int $grd_createdBy): self
    {
        $this->grd_createdBy = $grd_createdBy;

        return $this;
    }

    public function getGrdInserted(): ?\DateTimeInterface
    {
        return $this->grd_inserted;
    }

    public function setGrdInserted(\DateTimeInterface $grd_inserted): self
    {
        $this->grd_inserted = $grd_inserted;

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
     * @return ActivityUser
     */
    public function getParticipant(): ActivityUser
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
