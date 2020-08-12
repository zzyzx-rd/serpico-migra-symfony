<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TemplateRepository::class)
 */
class Template extends DbObject
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

    /**
     * @ORM\ManyToOne(targetEntity=Activity::class)
     */
    private $original_activity_act;

    /**
     * Template constructor.
     * @param int $id
     * @param $tmp_name
     * @param $tmp_createdBy
     * @param $tmp_inserted
     * @param $tmp_deleted
     * @param $activities
     * @param $stage
     * @param $criterion
     * @param $department
     * @param $organization
     * @param $original_activity_act
     */
    public function __construct(
        int $id = 0,
        $tmp_name = null,
        $original_activity_act = null,
        $tmp_createdBy = null,
        $tmp_inserted = null,
        $tmp_deleted = null,
        $activities = null,
        $stage = null,
        $criterion = null,
        $department = null,
        $organization = null)
    {
        $this->tmp_name = $tmp_name;
        $this->tmp_inserted = $tmp_inserted;
        $this->tmp_deleted = $tmp_deleted;
        $this->activities = $activities?$activities:new ArrayCollection();
        $this->stage = $stage;
        $this->criterion = $criterion;
        $this->department = $department;
        $this->organization = $organization;
        $this->original_activity_act = $original_activity_act;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->tmp_name;
    }

    public function setName(string $tmp_name): self
    {
        $this->tmp_name = $tmp_name;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->tmp_createdBy;
    }

    public function setCreatedBy(int $tmp_createdBy): self
    {
        $this->tmp_createdBy = $tmp_createdBy;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->tmp_inserted;
    }

    public function setInserted(\DateTimeInterface $tmp_inserted): self
    {
        $this->tmp_inserted = $tmp_inserted;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->tmp_deleted;
    }

    public function setDeleted(\DateTimeInterface $tmp_deleted): self
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

    public function getOriginalActivityAct(): ?Activity
    {
        return $this->original_activity_act;
    }

    public function setOriginalActivityAct(?Activity $original_activity_act): self
    {
        $this->original_activity_act = $original_activity_act;

        return $this;
    }
    function addActivity(Activity $activity){
        $this->activities->add($activity);
        $activity->setTemplate($this);
        return $this;
    }

    function removeActivity(Activity $activity){
        $this->activities->removeElement($activity);
        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }
}
