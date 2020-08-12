<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 */
class Department extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="dpt_id", type="integer", length=10, nullable=false)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    public $dpt_name;

    /**
     * @ORM\Column(type="integer")
     */
    public $dpt_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $dpt_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $dpt_deleted;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="masterUser_usr_id", referencedColumnName="usr_id", nullable=false)
     */
    protected $masterUser;

    /**
     * @OneToMany(targetEntity="Position", mappedBy="department", cascade={"persist", "remove"})
     * @OrderBy({"name" = "ASC"})
     */
    public $positions;

    /**
     * @OneToMany(targetEntity="TemplateActivity", mappedBy="department", cascade={"persist", "remove"},orphanRemoval=true)
     * @OrderBy({"name" = "ASC"})
     */
    public $templateActivities;

    /**
     * @OneToMany(targetEntity="OrganizationUserOption", mappedBy="department", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $options;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    /**
     * @OneToMany(targetEntity="CriterionGroup", mappedBy="department")
     * @var ArrayCollection
     */
    protected $criterionGroups;

    /**
     * @OneToMany(targetEntity="Target", mappedBy="department",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $targets;

    /**
     * Department constructor.
     * @param $id
     * @param $dpt_name
     * @param $dpt_createdBy
     * @param $dpt_inserted
     * @param $dpt_deleted
     * @param $masterUser
     * @param $positions
     * @param $templateActivities
     * @param $options
     * @param $organization
     * @param ArrayCollection $criterionGroups
     * @param $targets
     */
    //TODO Set correctement dans les controlleurs
    public function __construct(
        $id = 0,
        $dpt_name = '',
        $dpt_createdBy = null,
        $dpt_inserted = null,
        $dpt_deleted = null,
        $masterUser,
        $positions = null,
        $templateActivities = null,
        ArrayCollection $options = null,
        $organization,
        ArrayCollection $criterionGroups,
        ArrayCollection $targets = null)
    {
        $this->id = $id;
        $this->dpt_name = $dpt_name;
        $this->dpt_inserted = $dpt_inserted;
        $this->dpt_deleted = $dpt_deleted;
        $this->masterUser = $masterUser;
        $this->positions = $positions?$positions:new ArrayCollection();
        $this->templateActivities = $templateActivities;
        $this->options = $options?$options:new ArrayCollection();
        $this->organization = $organization;
        $this->criterionGroups = $criterionGroups;
        $this->targets = $targets?$targets:new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->dpt_name;
    }

    public function setName(string $dpt_name): self
    {
        $this->dpt_name = $dpt_name;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->dpt_inserted;
    }

    public function setInserted(\DateTimeInterface $dpt_inserted): self
    {
        $this->dpt_inserted = $dpt_inserted;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->dpt_deleted;
    }

    public function setDeleted(?\DateTimeInterface $dpt_deleted): self
    {
        $this->dpt_deleted = $dpt_deleted;

        return $this;
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
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * @param mixed $positions
     */
    public function setPositions($positions): void
    {
        $this->positions = $positions;
    }

    /**
     * @return mixed
     */
    public function getTemplateActivities()
    {
        return $this->templateActivities;
    }

    /**
     * @param mixed $templateActivities
     */
    public function setTemplateActivities($templateActivities): void
    {
        $this->templateActivities = $templateActivities;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     */
    public function setOptions($options): void
    {
        $this->options = $options;
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
     * @return ArrayCollection
     */
    public function getCriterionGroups(): ArrayCollection
    {
        return $this->criterionGroups;
    }

    /**
     * @param ArrayCollection $criterionGroups
     */
    public function setCriterionGroups(ArrayCollection $criterionGroups): void
    {
        $this->criterionGroups = $criterionGroups;
    }

    /**
     * @return mixed
     */
    public function getTargets()
    {
        return $this->targets;
    }

    /**
     * @param mixed $targets
     */
    public function setTargets($targets): void
    {
        $this->targets = $targets;
    }

    function addPosition(Position $position){

        $this->positions->add($position);
        $position->setDepartment($this);
        return $this;
    }

    function removePosition(Position $position){
        $this->positions->removeElement($position);
        return $this;
    }

    function addTemplateActivity(TemplateActivity $templateActivity){
        $this->templateActivities->add($templateActivity);
        $templateActivity->setDepartment($this);
        return $this;
    }

    function removeTemplateActivity(TemplateActivity $templateActivity){
        $this->templateActivities->removeElement($templateActivity);
        return $this;
    }
    //TODO l'histoire du getUSers et viewable users

    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->dpt_name
        ];
    }
    function addOption(OrganizationUserOption $option)
    {
        $this->options->add($option);
        $option->setDepartment($this);
        return $this;
    }

    function removeOption(OrganizationUserOption $option)
    {
        $this->options->removeElement($option);
        return $this;
    }

    function __toString()
    {
        return $this->dpt_name;
    }
}
