<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 * @UniqueEntity(fields={"name"},message="This name is already in use on that org.")
 */
class Team extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="tea_id", type="integer", nullable=false, length=10)
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $tea_name;

    /**
     * @ORM\Column(type="float")
     */
    public $tea_weight_ini;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    public $tea_picture;

    /**
     * @ORM\Column(type="integer")
     */
    public $tea_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $tea_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    public $tea_deleted;

    /**
     *@ManyToOne(targetEntity="Organization", inversedBy="teams")
     *@JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    /**
     * @OneToMany(targetEntity="TeamUser", mappedBy="team", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $teamUsers;

    /**
     * @OneToMany(targetEntity="ActivityUser", mappedBy="team", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $participations;

    /**
     * @OneToMany(targetEntity="Grade", mappedBy="team", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $grades;

    /**
     * @OneToMany(targetEntity="Target", mappedBy="team",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $targets;

    /**
     * Team constructor.
     * @param $id
     * @param $tea_name
     * @param $tea_weight_ini
     * @param $tea_picture
     * @param $tea_createdBy
     * @param $tea_inserted
     * @param $tea_deleted
     * @param $organization
     * @param $teamUsers
     * @param $participations
     * @param $grades
     * @param $targets
     */
    public function __construct(
        $id = 0,
        $tea_name = null,
        $tea_picture = null,
        $tea_weight_ini = null,
        $organization = null,
        $tea_createdBy = null,
        $tea_inserted = null,
        $tea_deleted = null,
        $teamUsers = null,
        $participations = null,
        $grades = null,
        $targets = null)
    {
        parent::__construct($id, $tea_createdBy, new DateTime());
        $this->tea_name = $tea_name;
        $this->tea_weight_ini = $tea_weight_ini;
        $this->tea_picture = $tea_picture;
        $this->tea_inserted = $tea_inserted;
        $this->tea_deleted = $tea_deleted;
        $this->organization = $organization;
        $this->teamUsers = $teamUsers?$teamUsers:new ArrayCollection();
        $this->participations = $participations?$participations: new ArrayCollection();
        $this->grades = $grades?$grades:new ArrayCollection();
        $this->targets = $targets?$targets: new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->tea_name;
    }

    public function setName(string $tea_name): self
    {
        $this->tea_name = $tea_name;

        return $this;
    }

    public function getWeightIni(): ?float
    {
        return $this->tea_weight_ini;
    }

    public function setWeightIni(float $tea_weight_ini): self
    {
        $this->tea_weight_ini = $tea_weight_ini;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->tea_picture;
    }

    public function setPicture(?string $tea_picture): self
    {
        $this->tea_picture = $tea_picture;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->tea_inserted;
    }

    public function setInserted(\DateTimeInterface $tea_inserted): self
    {
        $this->tea_inserted = $tea_inserted;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->tea_deleted;
    }

    public function setDeleted(\DateTimeInterface $tea_deleted): self
    {
        $this->tea_deleted = $tea_deleted;

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
    public function getmUsers()
    {
        return $this->teamUsers;
    }

    /**
     * @param mixed $teamUsers
     */
    public function setmUsers($teamUsers): void
    {
        $this->teamUsers = $teamUsers;
    }

    /**
     * @return mixed
     */
    public function getParticipations()
    {
        return $this->participations;
    }

    /**
     * @param mixed $participations
     */
    public function setParticipations($participations): void
    {
        $this->participations = $participations;
    }

    /**
     * @return mixed
     */
    public function getGrades()
    {
        return $this->grades;
    }

    /**
     * @param mixed $grades
     */
    public function setGrades($grades): void
    {
        $this->grades = $grades;
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
    function addTeamUser(TeamUser $teamUser){

        $this->teamUsers->add($teamUser);
        $teamUser->setTeam($this);
        return $this;
    }

    function removeTeamUser(TeamUser $teamUser){

        $this->teamUsers->removeElement($teamUser);
        return $this;
    }
    public function getCurrentTeamUsers()
    {
        return $this->teamUsers->filter(function(TeamUser $tu){
            return !$tu->isDeleted();
        });
    }

    public function getCurrentTeamExtUsers()
    {
        return $this->getTeamExtUsers()->filter(function(TeamUser $tu){
            return !$tu->isDeleted();
        });
    }

    public function getPastTeamExtUsers()
    {
        return $this->getTeamExtUsers()->filter(function(TeamUser $tu){
            return $tu->isDeleted();
        });
    }

    public function getCurrentTeamIntUsers()
    {
        return $this->getTeamIntUsers()->filter(function(TeamUser $tu){
            return !$tu->isDeleted();
        });
    }

    public function getPastTeamIntUsers()
    {
        return $this->getTeamIntUsers()->filter(function(TeamUser $tu){
            return $tu->isDeleted();
        });
    }

    function addTeamIntUser(TeamUser $teamUser)
    {
        $this->teamUsers->add($teamUser);
        $teamUser->setTeam($this);
        return $this;
    }

    function removeTeamIntUser(TeamUser $teamUser)
    {
        $this->teamUsers->removeElement($teamUser);
        return $this;
    }

    function addTeamExtUser(TeamUser $teamUser)
    {
        $this->teamUsers->add($teamUser);
        $teamUser->setTeam($this);
        return $this;
    }

    function removeTeamExtUser(TeamUser $teamUser)
    {
        $this->teamUsers->removeElement($teamUser);
        return $this;
    }
    public function getActiveTeamUsers()
    {
        $activeTeamUsers = new ArrayCollection;
        foreach ($this->teamUsers as $teamUser) {
            if ($teamUser->isDeleted() == false){
                $activeTeamUsers->add($teamUser);
            }
        }
        return $activeTeamUsers;
    }
    function addGrade(Grade $grade){

        $this->grades->add($grade);
        $grade->setParticipant($this);
        return $this;
    }

    function removeGrade(Grade $grade){
        $this->grades->removeElement($grade);
        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'picture'=> $this->picture,
            'name' => $this->name,
        ];
    }
    function addTarget(Target $target){
        $this->targets->add($target);
        $target->setTeam($this);
        return $this;
    }

    function removeTarget(Target $target){
        $this->targets->removeElement($target);
        return $this;
    }
    public function isOverviewable()
    {
        return $this->isModifiable();
    }

    public function __toString()
    {
        return (string)$this->id;
    }

    //TODO getIntTeamUser et le get Average et les trucs qui suivent
}
