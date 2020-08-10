<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TemplateRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TemplateRepository::class)
 */
class Template
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="tmp_id", type="integer", length=10)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tmp_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $tmp_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tmp_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tmp_deleted;

    /**
     *@OneToMany(targetEntity="Activity", mappedBy="activity")
     */
    private $activities;

    /**
     *@OneToOne(targetEntity="Stage")
     *@JoinColumn(name="stage_stg_id", referencedColumnName="stg_id", nullable=false)
     */
    protected $stage;

    /**
     *@OneToOne(targetEntity="Criterion")
     *@JoinColumn(name="criterion_crt_id", referencedColumnName="crt_id", nullable=false)
     */
    protected $criterion;

    /**
     *@ManyToOne(targetEntity="Department")
     *@JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id", nullable=false)
     */
    protected $department;

    /**
     *@ManyToOne(targetEntity="Organization")
     *@JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTmpName(): ?string
    {
        return $this->tmp_name;
    }

    public function setTmpName(string $tmp_name): self
    {
        $this->tmp_name = $tmp_name;

        return $this;
    }

    public function getTmpCreatedBy(): ?int
    {
        return $this->tmp_createdBy;
    }

    public function setTmpCreatedBy(int $tmp_createdBy): self
    {
        $this->tmp_createdBy = $tmp_createdBy;

        return $this;
    }

    public function getTmpInserted(): ?\DateTimeInterface
    {
        return $this->tmp_inserted;
    }

    public function setTmpInserted(\DateTimeInterface $tmp_inserted): self
    {
        $this->tmp_inserted = $tmp_inserted;

        return $this;
    }

    public function getTmpDeleted(): ?\DateTimeInterface
    {
        return $this->tmp_deleted;
    }

    public function setTmpDeleted(\DateTimeInterface $tmp_deleted): self
    {
        $this->tmp_deleted = $tmp_deleted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * @param mixed $activities
     */
    public function setActivities($activities): void
    {
        $this->activities = $activities;
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

}
