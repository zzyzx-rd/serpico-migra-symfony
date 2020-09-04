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
     * @ORM\Column(name="tea_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
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
     * @OneToMany(targetEntity="Member", mappedBy="team", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $members;

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

    public ?User $currentUser = null;

    /**
     * Team constructor.
     * @param $id
     * @param $name
     * @param $weight_ini
     * @param $picture
     * @param $createdBy
     * @param $deleted
     * @param $organization
     * @param $members
     * @param $participations
     * @param $grades
     * @param $targets
     */
    public function __construct(
        $id = 0,
        $name = null,
        $picture = null,
        $weight_ini = null,
        $organization = null,
        $createdBy = null,
        $deleted = null,
        $members = null,
        $participations = null,
        $grades = null,
        $targets = null)
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->name = $name;
        $this->weightIni = $weight_ini;
        $this->picture = $picture;
        $this->deleted = $deleted;
        $this->organization = $organization;
        $this->members = $members?:new ArrayCollection();
        $this->participations = $participations?: new ArrayCollection();
        $this->grades = $grades?:new ArrayCollection();
        $this->targets = $targets?: new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getWeightIni(): ?float
    {
        return $this->weightIni;
    }

    public function setWeightIni(float $weight_ini): self
    {
        $this->weightIni = $weight_ini;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(DateTimeInterface $deleted): self
    {
        $this->deleted = $deleted;

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
     * @return ArrayCollection|Participation[]
     */
    public function getParticipations()
    {
        return $this->participations;
    }

    /**
     * @return ArrayCollection|Grade[]
     */
    public function getGrades()
    {
        return $this->grades;
    }

    /**
     * @return ArrayCollection|Target[]
     */
    public function getTargets()
    {
        return $this->targets;
    }

    /**
     * @return ArrayCollection|Member[]
     */
    public function getMembers()
    {
    return $this->members;
    }

    function addMember(Member $member){

        $this->members->add($member);
        $member->setTeam($this);
        return $this;
    }

    public function removeMember(Member $member): Team
    {

        $this->members->removeElement($member);
        return $this;
    }

    public function getCurrentMembers(): ArrayCollection
    {
        return $this->members->filter(static function(Member $m){
            return !$m->isDeleted();
        });
    }

    public function getIntMembers()
    {
        $teamIntUsers = new ArrayCollection(array_values(array_filter($this->members->toArray(),function(Member $m){
            return $m->getUser()->getOrganization() == $this->currentUser->getOrganization();
        })));
        return $teamIntUsers;
    }

    public function getExtMembers()
    {
        $teamIntUsers = new ArrayCollection(array_values(array_filter($this->members->toArray(),function(Member $m){
            return $m->getUser()->getOrganization() != $this->currentUser->getOrganization();
        })));
        return $teamIntUsers;
    }

    public function getCurrentExtMembers()
    {
        return $this->getExtMembers()->filter(static function(Member $m){
            return !$m->isDeleted();
        });
    }

    public function getPastExtMembers()
    {
        return $this->getExtMembers()->filter(static function(Member $m){
            return $m->isDeleted();
        });
    }

    public function getCurrentIntMembers()
    {
        return $this->getIntMembers()->filter(static function(Member $m){
            return !$m->isDeleted();
        });
    }

    public function getPastIntMembers()
    {
        return $this->getIntMembers()->filter(static function(Member $m){
            return $m->isDeleted();
        });
    }

    public function addIntMember(Member $member): Team
    {
        $this->members->add($member);
        $member->setTeam($this);
        return $this;
    }

    public function removeIntMember(Member $member): Team
    {
        $this->members->removeElement($member);
        return $this;
    }

    public function addExtMember(Member $member): Team
    {
        $this->members->add($member);
        $member->setTeam($this);
        return $this;
    }

    public function removeExtMember(Member $member): Team
    {
        $this->members->removeElement($member);
        return $this;
    }

    public function getActiveMembers(): ArrayCollection
    {
        $activeMembers = new ArrayCollection;
        foreach ($this->members as $member) {
            if ($member->isDeleted() === false){
                $activeMembers->add($member);
            }
        }
        return $activeMembers;
    }
    public function addGrade(Grade $grade): Team
    {
        $this->grades->add($grade);
        $grade->setTeam($this);
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

    public function isModifiable()
    {
        $connectedUser = $this->currentUser;
        $connectedUserRole = $connectedUser->getRole();
        $connectedUserId = $connectedUser->getId();

        if($connectedUserRole == USER::ROLE_ROOT){
            return true;
        } else {
            $teamLeader = $this->getMembers()->filter(function(Member $m){return $m->isLeader();})->first();
            $teamOrganization = $this->getOrganization();
            $grantedRights = 
                $teamLeader && $teamLeader->getUser() == $connectedUser || 
                !$teamLeader && $this->getCreatedBy() == $connectedUser->getId() ||
                $teamOrganization == $connectedUser->getOrganization() && $connectedUser->getRole() == USER::ROLE_ADMIN;
                // Or if there is an option giving you such right (currently unexisting)
        }

        return $grantedRights;
    }

    public function isOverviewable()
    {
        return $this->isModifiable();
    }

    public function __toString()
    {
        return (string)$this->id;
    }

    //TODO getIntMember et le get Average et les trucs qui suivent
}
