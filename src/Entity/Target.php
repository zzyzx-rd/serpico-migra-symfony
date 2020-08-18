<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TargetRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TargetRepository::class)
 */
class Target
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="tgt_id", type="integer", nullable=false)
     */
    public $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $tgt_sign;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $tgt_value;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $tgt_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $tgt_inserted;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="targets")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=false)
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Department", inversedBy="targets")
     * @JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id",nullable=false)
     */
    protected $department;

    /**
     * @ManyToOne(targetEntity="Position", inversedBy="targets")
     * @JoinColumn(name="position_pos_id", referencedColumnName="pos_id",nullable=false)
     */
    protected $position;

    /**
     * @ManyToOne(targetEntity="Title", inversedBy="targets")
     * @JoinColumn(name="title_tit_id", referencedColumnName="tit_id",nullable=false)
     */
    protected $title;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="targets")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id", nullable=false)
     */
    protected $user;

    /**
     * @ManyToOne(targetEntity="Team", inversedBy="targets")
     * @JoinColumn(name="team_tea_id", referencedColumnName="tea_id", nullable=true)
     */
    protected $team;

    /**
     * @OneToOne(targetEntity="CriterionName")
     * @JoinColumn(name="criterion_name_cna_id", referencedColumnName="cna_id")
     */
    protected $cName;

    /**
     * @OneToOne(targetEntity="Criterion", inversedBy="target")
     * @JoinColumn(name="criterion_crt_id", referencedColumnName="crt_id",nullable=false)
     */
    protected $criterion;

    /**
     * Target constructor.
     * @param $id
     * @param $tgt_sign
     * @param $tgt_value
     * @param $tgt_createdBy
     * @param $tgt_inserted
     * @param $organization
     * @param $department
     * @param $position
     * @param $title
     * @param $user
     * @param $team
     * @param $cName
     * @param $criterion
     */
    public function __construct(
        $id = 0,
        $tgt_sign = null,
        $tgt_value = null,
        $tgt_createdBy = null,
        $tgt_inserted = null,
        $organization = null,
        $department = null,
        $position = null,
        $title = null,
        $user = null,
        $team = null,
        $cName = null,
        $criterion = null)
    {
        parent::__construct($id, $tgt_createdBy, new DateTime());
        $this->tgt_sign = $tgt_sign;
        $this->tgt_value = $tgt_value;
        $this->tgt_inserted = $tgt_inserted;
        $this->organization = $organization;
        $this->department = $department;
        $this->position = $position;
        $this->title = $title;
        $this->user = $user;
        $this->team = $team;
        $this->cName = $cName;
        $this->criterion = $criterion;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTgtSign(): ?int
    {
        return $this->tgt_sign;
    }

    public function setTgtSign(int $tgt_sign): self
    {
        $this->tgt_sign = $tgt_sign;

        return $this;
    }

    public function getTgtValue(): ?float
    {
        return $this->tgt_value;
    }

    public function setTgtValue(float $tgt_value): self
    {
        $this->tgt_value = $tgt_value;

        return $this;
    }

    public function getTgtCreatedBy(): ?int
    {
        return $this->tgt_createdBy;
    }

    public function setTgtCreatedBy(?int $tgt_createdBy): self
    {
        $this->tgt_createdBy = $tgt_createdBy;

        return $this;
    }

    public function getTgtInserted(): ?\DateTimeInterface
    {
        return $this->tgt_inserted;
    }

    public function setTgtInserted(\DateTimeInterface $tgt_inserted): self
    {
        $this->tgt_inserted = $tgt_inserted;

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
    public function setOrganization($organization): void
    {
        $this->organization = $organization;
    }

    /**
     * @return mixed
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $department
     */
    public function setDepartment($department): void
    {
        $this->department = $department;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position): void
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
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

    public function __toString()
    {
        return (string) $this->id;
    }
 }