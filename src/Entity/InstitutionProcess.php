<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\InstitutionProcessRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=InstitutionProcessRepository::class)
 */
class InstitutionProcess extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="inp_id", type="integer", length=10, nullable=true)
     * @var int
     */
    protected ?int $id;


    /**
     * @ORM\Column(name="inp_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="inp_approvable", type="boolean", nullable=true)
     */
    public $approvable;

    /**
     * @ORM\Column(name="inp_gradable", type="boolean", nullable=true)
     */
    public $gradable;

    /**
     * @ORM\Column(name="inp_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="inp_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="inp_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="institutionProcesses")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;
    /**
     * @ManyToOne(targetEntity="Process", inversedBy="institutionProcesses")
     * @JoinColumn(name="process_pro_id", referencedColumnName="pro_id", nullable=false)
     */
    protected ?Process $process;
    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="masterUser_usr_id", referencedColumnName="usr_id", nullable=false)
     */
    protected $masterUser;
    /**
     * @ManyToOne(targetEntity="InstitutionProcess", inversedBy="children")
     * @JoinColumn(name="parent_id", referencedColumnName="inp_id", nullable=true)
     */
    public $parent;
    /**
     * @OneToMany(targetEntity="InstitutionProcess", mappedBy="parent", cascade={"persist"}, orphanRemoval=false)
     */
    public $children;
    /**
     * @OneToMany(targetEntity="IProcessStage", mappedBy="institutionProcess", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $stages;
    /**
     * @OneToMany(targetEntity="Activity", mappedBy="institutionProcess", cascade={"persist","remove"})
     * @OrderBy({"status" = "ASC", "name" = "ASC"})
     */
    public $activities;

    /**
     * InstitutionProcess constructor.
     * @param ?int$id
     * @param $inp_name
     * @param $inp_approvable
     * @param $inp_gradable
     * @param $inp_createdBy
     * @param $inp_inserted
     * @param $inp_deleted
     * @param $organization
     * @param $process
     * @param $masterUser
     * @param $parent
     * @param $children
     * @param $stages
     * @param $activities
     */
    public function __construct(
      ?int $id = 0,
        $inp_name = '',
        $inp_createdBy = null,
        $inp_approvable = false,
        $inp_gradable = true,
        $inp_inserted = null,
        $inp_deleted = null,
        Organization $organization = null,
        Process $process = null,
        User $masterUser = null,
        $parent = null,
        $children = null,
        $stages = null,
        $activities = null)
    {
        parent::__construct($id, $inp_createdBy, new DateTime());
        $this->name = $inp_name;
        $this->approvable = $inp_approvable;
        $this->gradable = $inp_gradable;
        $this->deleted = $inp_deleted;
        $this->organization = $organization;
        $this->process = $process;
        $this->masterUser = $masterUser;
        $this->parent = $parent;
        $this->children = new ArrayCollection();
        $this->stages = new ArrayCollection();
        $this->activities = new ArrayCollection();
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $inp_name): self
    {
        $this->name = $inp_name;

        return $this;
    }

    public function isApprovable(): ?bool
    {
        return $this->approvable;
    }

    public function setApprovable(bool $inp_approvable): self
    {
        $this->approvable = $inp_approvable;

        return $this;
    }

    public function isGradable(): ?bool
    {
        return $this->gradable;
    }

    public function setGradable(bool $inp_gradable): self
    {
        $this->gradable = $inp_gradable;

        return $this;
    }

    public function getInserted(): ?DateTimeInterface
    {
        return $this->inserted;
    }

    public function setInserted(DateTimeInterface $inp_inserted): self
    {
        $this->inserted = $inp_inserted;

        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeInterface $inp_deleted): self
    {
        $this->deleted = $inp_deleted;

        return $this;
    }

    /**
     * @return ArrayCollection|Activity[]
     */
    public function getActivities()
    {
        return $this->activities;
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
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param mixed $process
     */
    public function setProcess($process): void
    {
        $this->process = $process;
    }

    /**
     * @return mixed
     */
    public function getMasterUser()
    {
        return $this->masterUser;
    }

    /**
     * @param mixed $masterUser
     */
    public function setMasterUser($masterUser): void
    {
        $this->masterUser = $masterUser;
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
    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children): void
    {
        $this->children = $children;
    }

    /**
     * @return mixed
     */
    public function getStages()
    {
        return $this->stages;
    }

    /**
     * @param mixed $stages
     */
    public function setStages($stages): void
    {
        $this->stages = $stages;
    }

    public function addChildren(InstitutionProcess $child): InstitutionProcess
    {
        $this->children->add($child);
        $child->setParent($this);
        return $this;
    }

    public function removeChildren(InstitutionProcess $child): InstitutionProcess
    {
        $this->children->removeElement($child);
        return $this;
    }

    /**
     * @return ArrayCollection|InstitutionProcess[]
     */
    public function getValidatedChildren() {
        return $this->children->filter(static function(InstitutionProcess $p){
            return !$p->isApprovable();
        });
    }

    public function addValidatedChildren(InstitutionProcess $child): InstitutionProcess
    {
        return $this->addChildren($child);
    }

    public function removeValidatedChildren(InstitutionProcess $child): InstitutionProcess
    {
        return $this->removeChildren($child);
    }
    public function addActivity(Activity $activity): InstitutionProcess
    {

        $this->activities->add($activity);
        $activity->setInstitutionProcess($this);
        return $this;
    }

    public function removeActivity(Activity $activity): InstitutionProcess
    {
        $this->activities->removeElement($activity);
        return $this;
    }

    public function addStage(IProcessStage $stage): InstitutionProcess
    {

        $this->stages->add($stage);
        $stage->setInstitutionProcess($this);
        return $this;
    }

    public function removeStage(IProcessStage $stage): InstitutionProcess
    {
        $this->stages->removeElement($stage);
        return $this;
    }
    /**
     * @return ArrayCollection|IProcessStage[]
     */
    public function getActiveStages()
    {
        return $this->getStages();
    }

    public function addActiveStage(IProcessStage $stage): InstitutionProcess
    {
        return $this->addStage($stage);
    }

    public function removeActiveStage(IProcessStage $stage): InstitutionProcess
    {
        return $this->removeStage($stage);
    }

    //TODO getActiveModifiableStages
    public function addActiveModifiableStage(IProcessStage $stage): InstitutionProcess
    {
        return $this->addStage($stage);
    }

    public function removeActiveModifiableStage(IProcessStage $stage): InstitutionProcess
    {
        return $this->removeStage($stage);
    }
    public function __toString()
    {
        return (string) $this->id;
    }

    public function userCanEdit(User $u): bool
    {
        return $u->getRole() == 1 || $u->getRole() == 4 || $this->masterUser == $u;
    }
}
