<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProcessRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ProcessRepository::class)
 */
class Process extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="pro_id", type="integer", length=10, nullable=false)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="pro_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="pro_approvable", type="boolean", nullable=true)
     */
    public $approvable;

    /**
     * @ORM\Column(name="pro_gradable", type="boolean", nullable=true)
     */
    public $gradable;

    /**
     * @ORM\Column(name="pro_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="pro_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="pro_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="process")
     */
    public $activities;

    /**
     *@ManyToOne(targetEntity="Organization", inversedBy="processes")
     *@JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Process", inversedBy="children")
     * @JoinColumn(name="parent_id", referencedColumnName="pro_id", nullable=true)
     */
    public $parent;

    /**
     * @OneToMany(targetEntity="Process", mappedBy="parent", cascade={"persist"}, orphanRemoval=false)
     */
    public $children;

    /**
     * @ManyToOne(targetEntity="Icon")
     * @JoinColumn(name="icon_ico_id", referencedColumnName="ico_id", nullable=true)
     */
    protected $icon;

    /**
     * @OneToMany(targetEntity="InstitutionProcess", mappedBy="process", cascade={"persist"})
     */
    public $institutionProcesses;

    /**
     * @OneToMany(targetEntity="ProcessStage", mappedBy="process", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $stages;

    /**
     * @ORM\OneToMany(targetEntity=ProcessCriterion::class, mappedBy="process")
     */
    private $criteria;

    /**
     * Process constructor.
     * @param ?int$id
     * @param $pro_name
     * @param $pro_approvable
     * @param $pro_gradable
     * @param $pro_createdBy
     * @param $pro_inserted
     * @param $pro_deleted
     * @param $children
     * @param $stages
     */
    public function __construct(
      ?int $id = 0,
        $pro_name = '',
        $pro_createdBy = null,
        $pro_gradable = true,
        $pro_inserted = null,
        $pro_deleted = null,
        $pro_approvable = false,
        $children = null,
        $stages = null)
    {
        parent::__construct($id, $pro_createdBy, new DateTime());
        $this->name = $pro_name;
        $this->approvable = $pro_approvable;
        $this->gradable = $pro_gradable;
        $this->deleted = $pro_deleted;
        $this->children = $children?:new ArrayCollection();
        $this->stages = $stages?:new ArrayCollection();
        $this->criteria = new ArrayCollection();
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $pro_name): self
    {
        $this->name = $pro_name;

        return $this;
    }

    public function isApprovable(): ?bool
    {
        return $this->approvable;
    }

    public function setApprovable(bool $pro_approvable): self
    {
        $this->approvable = $pro_approvable;
        return $this;
    }

    public function getGradable(): ?bool
    {
        return $this->gradable;
    }

    public function setGradable(bool $pro_gradable): self
    {
        $this->gradable = $pro_gradable;

        return $this;
    }

    public function setInserted(DateTimeInterface $pro_inserted): self
    {
        $this->inserted = $pro_inserted;

        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeInterface $pro_deleted): self
    {
        $this->deleted = $pro_deleted;

        return $this;
    }

    /**
     * @return ArrayCollection|Activity[]
     */
    public function getActivities()
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setcess($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
            // set the owning side to null (unless already changed)
            if ($activity->getcess() === $this) {
                $activity->setcess(null);
            }
        }

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
    public function setOrganization($organization): self
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon(?Icon $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstitutionProcesses()
    {
        return $this->institutionProcesses;
    }

    /**
     * @return mixed
     */
    public function getStages()
    {
        return $this->stages;
    }

    public function addInstitutionProcess(InstitutionProcess $institutionProcess): Process
    {
        $this->institutionProcesses->add($institutionProcess);
        $institutionProcess->setProcess($this);
        return $this;
    }

    public function removeInstitutionProcess(InstitutionProcess $institutionProcess): Process
    {
        $this->institutionProcesses->removeElement($institutionProcess);
        return $this;
    }
    public function addChildren(Process $child): Process
    {
        $this->children->add($child);
        $child->setParent($this);
        return $this;
    }

    public function removeChildren(Process $child): Process
    {
        $this->children->removeElement($child);
        return $this;
    }

    /**
     * @return Collection|Process[]
    */
    function getValidatedChildren() {
        return $this->children->filter(function(Process $p){
            return !$p->isApprovable();
        });
    }

    public function addValidatedChildren(Process $child): Process
    {
        return $this->addChildren($child);
    }

    public function removeValidatedChildren(Process $child): Process
    {
        return $this->removeChildren($child);
    }
    public function addStage(ProcessStage $stage): Process
    {

        $this->stages->add($stage);
        $stage->setProcess($this);
        return $this;
    }

    public function removeStage(ProcessStage $stage): Process
    {
        $this->stages->removeElement($stage);
        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }

    public function userCanEdit(User $u): bool
    {
        return $u->getRole() === 4;
    }

    /**
     * @return ArrayCollection|ProcessCriterion[]
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    public function addCriterion(ProcessCriterion $criterion): self
    {
        if (!$this->criteria->contains($criterion)) {
            $this->criteria[] = $criterion;
            $criterion->setProcess($this);
        }

        return $this;
    }

    public function removeCriterion(ProcessCriterion $criterion): self
    {
        if ($this->criteria->contains($criterion)) {
            $this->criteria->removeElement($criterion);
            // set the owning side to null (unless already changed)
            if ($criterion->getProcess() === $this) {
                $criterion->setProcess(null);
            }
        }

        return $this;
    }


}
