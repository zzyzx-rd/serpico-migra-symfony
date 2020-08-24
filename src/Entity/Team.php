<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TeamRepository;
use DateTime;
use DateTimeInterface;
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
    public ?int $id;

    /**
     * @ORM\Column(name="tea_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="tea_weight_ini", type="float", nullable=true)
     */
    public $weightIni;

    /**
     * @ORM\Column(name="tea_picture", type="string", length=10, nullable=true)
     */
    public $picture;

    /**
     * @ORM\Column(name="tea_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="tea_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="tea_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     *@ManyToOne(targetEntity="Organization", inversedBy="teams")
     *@JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=true)
     */
    protected $organization;

    /**
     * @OneToMany(targetEntity="TeamUser", mappedBy="team", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $teamUsers;

    /**
     * @OneToMany(targetEntity="Participation", mappedBy="team", cascade={"persist", "remove"}, orphanRemoval=true)
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
        $this->name = $tea_name;
        $this->weightIni = $tea_weight_ini;
        $this->picture = $tea_picture;
        $this->deleted = $tea_deleted;
        $this->organization = $organization;
        $this->teamUsers = $teamUsers?:new ArrayCollection();
        $this->participations = $participations?: new ArrayCollection();
        $this->grades = $grades?:new ArrayCollection();
        $this->targets = $targets?: new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $tea_name): self
    {
        $this->name = $tea_name;

        return $this;
    }

    public function getWeightIni(): ?float
    {
        return $this->weightIni;
    }

    public function setWeightIni(float $tea_weight_ini): self
    {
        $this->weightIni = $tea_weight_ini;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $tea_picture): self
    {
        $this->picture = $tea_picture;

        return $this;
    }

    public function setInserted(DateTimeInterface $tea_inserted): self
    {
        $this->inserted = $tea_inserted;

        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(DateTimeInterface $tea_deleted): self
    {
        $this->deleted = $tea_deleted;

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

    /**
     * @return mixed
     */
    public function getTeamUsers()
    {
    return $this->teamUsers;
    }

    function addTeamUser(TeamUser $teamUser){

        $this->teamUsers->add($teamUser);
        $teamUser->setTeam($this);
        return $this;
    }

    public function removeTeamUser(TeamUser $teamUser): Team
    {

        $this->teamUsers->removeElement($teamUser);
        return $this;
    }
    public function getCurrentTeamUsers(): ArrayCollection
    {
        return $this->teamUsers->filter(static function(TeamUser $tu){
            return !$tu->isDeleted();
        });
    }

    public function getCurrentTeamExtUsers()
    {
        return $this->getTeamExtUsers()->filter(static function(TeamUser $tu){
            return !$tu->isDeleted();
        });
    }

    public function getPastTeamExtUsers()
    {
        return $this->getTeamExtUsers()->filter(static function(TeamUser $tu){
            return $tu->isDeleted();
        });
    }

    public function getCurrentTeamIntUsers()
    {
        return $this->getTeamIntUsers()->filter(static function(TeamUser $tu){
            return !$tu->isDeleted();
        });
    }

    public function getPastTeamIntUsers()
    {
        return $this->getTeamIntUsers()->filter(static function(TeamUser $tu){
            return $tu->isDeleted();
        });
    }

    public function addTeamIntUser(TeamUser $teamUser): Team
    {
        $this->teamUsers->add($teamUser);
        $teamUser->setTeam($this);
        return $this;
    }

    public function removeTeamIntUser(TeamUser $teamUser): Team
    {
        $this->teamUsers->removeElement($teamUser);
        return $this;
    }

    public function addTeamExtUser(TeamUser $teamUser): Team
    {
        $this->teamUsers->add($teamUser);
        $teamUser->setTeam($this);
        return $this;
    }

    public function removeTeamExtUser(TeamUser $teamUser): Team
    {
        $this->teamUsers->removeElement($teamUser);
        return $this;
    }
    public function getActiveTeamUsers(): ArrayCollection
    {
        $activeTeamUsers = new ArrayCollection;
        foreach ($this->teamUsers as $teamUser) {
            if ($teamUser->isDeleted() === false){
                $activeTeamUsers->add($teamUser);
            }
        }
        return $activeTeamUsers;
    }
    public function addGrade(Grade $grade): Team
    {

        $this->grades->add($grade);
        $grade->setParticipant($this);
        return $this;
    }

    public function removeGrade(Grade $grade): Team
    {
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
    public function addTarget(Target $target): Team
    {
        $this->targets->add($target);
        $target->setTeam($this);
        return $this;
    }

    public function removeTarget(Target $target): Team
    {
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
