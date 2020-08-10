<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TemplateActivityUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TemplateActivityUserRepository::class)
 */
class TemplateActivityUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="a_u_id", type="integer", nullable=false)
     */
    private $id;

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
     * @ORM\Column(type="string", length=10)
     */
    private $a_u_precomment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $a_u_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $a_u_inserted;

    /**
     * @ManyToOne(targetEntity="Team")
     * @JoinColumn(name="team_tea_id", referencedColumnName="tea_id",nullable=false)
     */
    protected $team;

    /**
     * @ManyToOne(targetEntity="TemplateActivity")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id",nullable=false)
     */
    protected $activity;

    /**
     * @ManyToOne(targetEntity="TemplateStage")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id",nullable=false)
     */
    protected $stage;

    /**
     * @ManyToOne(targetEntity="TemplateCriterion")
     * @JoinColumn(name="criterion_crt_id", referencedColumnName="crt_id",nullable=true)
     */
    protected $criterion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAULeader(): ?bool
    {
        return $this->a_u_leader;
    }

    public function setAULeader(bool $a_u_leader): self
    {
        $this->a_u_leader = $a_u_leader;

        return $this;
    }

    public function getAUType(): ?int
    {
        return $this->a_u_type;
    }

    public function setAUType(int $a_u_type): self
    {
        $this->a_u_type = $a_u_type;

        return $this;
    }

    public function getAUMWeight(): ?float
    {
        return $this->a_u_mWeight;
    }

    public function setAUMWeight(float $a_u_mWeight): self
    {
        $this->a_u_mWeight = $a_u_mWeight;

        return $this;
    }

    public function getAUPrecomment(): ?string
    {
        return $this->a_u_precomment;
    }

    public function setAUPrecomment(string $a_u_precomment): self
    {
        $this->a_u_precomment = $a_u_precomment;

        return $this;
    }

    public function getAUCreatedBy(): ?int
    {
        return $this->a_u_createdBy;
    }

    public function setAUCreatedBy(?int $a_u_createdBy): self
    {
        $this->a_u_createdBy = $a_u_createdBy;

        return $this;
    }

    public function getAUInserted(): ?\DateTimeInterface
    {
        return $this->a_u_inserted;
    }

    public function setAUInserted(?\DateTimeInterface $a_u_inserted): self
    {
        $this->a_u_inserted = $a_u_inserted;

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

}
