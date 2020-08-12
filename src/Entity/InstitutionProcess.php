<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\InstitutionProcessRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
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
     * @ORM\Column(name="inp_id", type="integer", length=10)
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $inp_name;

    /**
     * @ORM\Column(type="boolean")
     */
    public $inp_approvable;

    /**
     * @ORM\Column(type="boolean")
     */
    public $inp_gradable;

    /**
     * @ORM\Column(type="integer")
     */
    public $inp_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $inp_isnerted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $inp_deleted;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;
    /**
     * @ManyToOne(targetEntity="Process")
     * @JoinColumn(name="process_pro_id", referencedColumnName="pro_id", nullable=false)
     */
    protected $process;
    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="masterUser_usr_id", referencedColumnName="usr_id", nullable=false)
     */
    protected $masterUser;
    /**
     * @OneToOne(targetEntity="InstitutionProcess")
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
     * @param int $id
     * @param $inp_name
     * @param $inp_approvable
     * @param $inp_gradable
     * @param $inp_createdBy
     * @param $inp_isnerted
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
        int $id = 0,
        $inp_name = '',
        $inp_createdBy = null,
        $inp_approvable = false,
        $inp_gradable = true,
        $inp_isnerted = null,
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
        $this->inp_name = $inp_name;
        $this->inp_approvable = $inp_approvable;
        $this->inp_gradable = $inp_gradable;
        $this->inp_isnerted = $inp_isnerted;
        $this->inp_deleted = $inp_deleted;
        $this->organization = $organization;
        $this->process = $process;
        $this->masterUser = $masterUser;
        $this->parent = $parent;
        $this->children = $children;
        $this->stages = $stages;
        $this->activities = $activities;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->inp_name;
    }

    public function setName(string $inp_name): self
    {
        $this->inp_name = $inp_name;

        return $this;
    }

    public function isApprovable(): ?bool
    {
        return $this->inp_approvable;
    }

    public function setApprovable(bool $inp_approvable): self
    {
        $this->inp_approvable = $inp_approvable;

        return $this;
    }

    public function isGradable(): ?bool
    {
        return $this->inp_gradable;
    }

    public function setGradable(bool $inp_gradable): self
    {
        $this->inp_gradable = $inp_gradable;

        return $this;
    }

    public function getIsnerted(): ?\DateTimeInterface
    {
        return $this->inp_isnerted;
    }

    public function setIsnerted(\DateTimeInterface $inp_isnerted): self
    {
        $this->inp_isnerted = $inp_isnerted;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->inp_deleted;
    }

    public function setDeleted(?\DateTimeInterface $inp_deleted): self
    {
        $this->inp_deleted = $inp_deleted;

        return $this;
    }

    /**
     * @return Collection|Activity[]
     */
    public function getActivities(): Collection
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

    function addChildren(InstitutionProcess $child){
        $this->children->add($child);
        $child->setParent($this);
        return $this;
    }

    function removeChildren(InstitutionProcess $child){
        $this->children->removeElement($child);
        return $this;
    }

    /**
     * @return Collection|InstitutionProcess[]
     */
    function getValidatedChildren() {
        return $this->children->filter(function(InstitutionProcess $p){
            return !$p->isApprovable();
        });
    }

    function addValidatedChildren(InstitutionProcess $child){
        return $this->addChildren($child);
    }

    function removeValidatedChildren(InstitutionProcess $child){
        return $this->removeChildren($child);
    }
    function addActivity(Activity $activity){

        $this->activities->add($activity);
        $activity->setInstitutionProcess($this);
        return $this;
    }

    function removeActivity(Activity $activity){
        $this->activities->removeElement($activity);
        return $this;
    }

    function addStage(IProcessStage $stage){

        $this->stages->add($stage);
        $stage->setInstitutionProcess($this);
        return $this;
    }

    function removeStage(IProcessStage $stage){
        $this->stages->removeElement($stage);
        return $this;
    }
    /**
     * @return Collection|IProcessStage[]
     */
    public function getActiveStages()
    {
        return $this->getStages();
    }

    public function addActiveStage(IProcessStage $stage)
    {
        return $this->addStage($stage);
    }

    public function removeActiveStage(IProcessStage $stage)
    {
        return $this->removeStage($stage);
    }

    //TODO getActiveModifiableStages
    public function addActiveModifiableStage(IProcessStage $stage)
    {
        return $this->addStage($stage);
    }

    public function removeActiveModifiableStage(IProcessStage $stage)
    {
        return $this->removeStage($stage);
    }
    public function __toString()
    {
        return (string) $this->id;
    }

    public function userCanEdit(User $u)
    {
        return $u->getRole() == 1 || $u->getRole() == 4 || $this->masterUser == $u;
    }
}
