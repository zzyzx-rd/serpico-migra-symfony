<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
// * @UniqueEntity(
// *     fields={"email"},
// *     message="This email already exists"
// * )
class User extends DbObject implements  UserInterface, \Serializable
{

    public const ROLE_ADMIN = 1;
    public const ROLE_ROOT = 4;
    public const ROLE_AM = 2;
    public const ROLE_COLLAB = 3;
    /**
     * @Id()
     * @GeneratedValue()
     * @Column(name="usr_id", type="integer", nullable=false)
     * @var int
     */
    protected ?int $id;


    /**
     * @ORM\Column(name="usr_int", type="boolean", nullable=true)
     */
    public $internal;

    /**
     * @ORM\Column(name="usr_firstname", type="string", length=255, nullable=true)
     */
    public $firstname;

    /**
     * @ORM\Column(name="usr_lastname", type="string", length=255, nullable=true)
     */
    public $lastname;

    /**
     * @ORM\Column(name="usr_username", type="string", length=255, nullable=true)
     */
    public $username;

    /**
     * @ORM\Column(name="usr_nickname", type="string", length=255, nullable=true)
     */
    public $nickname;

    /**
     * @ORM\Column(name="usr_birthdate", type="datetime", nullable=true)
     */
    public $birthdate;

    /**
     * @ORM\Column(name="usr_email", type="string", length=255, nullable=true)
     */
    public $email;

    /**
     * @ORM\Column(name="usr_password", type="string", length=255, nullable=true)
     */
    public $password;

    /**
     * @ORM\Column(name="usr_position_name", type="string", length=255, nullable=true)
     */
    public $positionName;

    /**
     * @ORM\Column(name="usr_picture", type="string", length=255, nullable=true)
     */
    public $picture;

    /**
     * @ORM\Column(name="usr_token", type="string", length=255, nullable=true)
     */
    public $token;

    /**
     * @ORM\Column(name="usr_weight_ini", type="float", nullable=true)
     */
    public $weight_ini;

    /**
     * @ORM\Column(name="usr_act_archive_nb_days", type="integer", nullable=true)
     */
    public $activitiesArchivingNbDays;

    /**
     * @ORM\Column(name="usr_rm_token", type="string", length=255, nullable=true)
     */
    public $rememberMeToken;

    /**
     * @ORM\Column(name="usr_validated", type="datetime", nullable=true)
     */
    public $validated;

    /**
     * @ORM\Column(name="usr_enabledCreatingUser", type="boolean", nullable=true)
     */
    public $enabledCreatingUser;

    /**
     * @ORM\Column(name="usr_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="usr_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="usr_last_connected", type="datetime", nullable=true)
     */
    public $lastConnected;

    /**
     * @ORM\Column(name="usr_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @Column(name="role_rol_id", type="integer", nullable=true)
     * @var int
     */
    protected $role;

    /**
     * @OneToMany(targetEntity="ExternalUser", mappedBy="user",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $externalUsers;

    /**
     * @OneToMany(targetEntity="EventComment", mappedBy="user",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $eventComments;

    /** 
     * @ManyToOne(targetEntity="User", inversedBy="subordinates")
     * @JoinColumn(name="usr_superior", referencedColumnName="usr_id")
     * @var User|null
     */
    protected $superior;

    /**
     * @OneToMany(targetEntity="Mail", mappedBy="user",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $mails;

    /**
     * @OneToMany(targetEntity="Target", mappedBy="user",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $targets;

    /**
     * @OneToMany(targetEntity="OrganizationUserOption", mappedBy="user", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $options;

    /**
     * @OneToOne(targetEntity="WorkerIndividual")
     * @JoinColumn(name="worker_individual_win_id", referencedColumnName="win_id")
     */
    public $workerIndividual;

    /**
     * @ORM\OneToMany(targetEntity=Participation::class, mappedBy="user")
     */
    public $participations;

    /**
     * @ORM\OneToMany(targetEntity=Recurring::class, mappedBy="rec_master_user")
     */
    public $Reccuring;

    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="user")
     */
    public $results;

    /**
     * @ORM\OneToMany(targetEntity=Stage::class, mappedBy="masterUser")
     */
    public $stagesWhereMaster;

    /**
     * @ORM\OneToMany(targetEntity=Member::class, mappedBy="user")
     */
    public $members;

    /**
     * @ORM\ManyToOne(targetEntity=Weight::class, inversedBy="users")
     * @ORM\JoinColumn(name="weight_wgt_id",referencedColumnName="wgt_id", nullable=true)
     */
    public $weight;

    /**
     * @ORM\ManyToOne(targetEntity=Position::class, inversedBy="users")
     * @JoinColumn(name="position_pos_id", referencedColumnName="pos_id", nullable=true)
     */
    public $position;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="users")
     * @JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id", nullable=true)
     */
    public $department;

    /**
     * @ORM\ManyToOne(targetEntity=Title::class)
     * @JoinColumn(name="title_tit_id", referencedColumnName="tit_id", nullable=true)
     */
    public $title;

    /**
     * @ORM\ManyToOne(targetEntity=Organization::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false, name="organization_org_id", referencedColumnName="org_id", nullable=true, onDelete="CASCADE")
     */
    public $organization;

    /**
     * @OneToMany(targetEntity="Department", mappedBy="masterUser",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $leadingDepartments;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="superior")
     */
    public $subordinates;

    
    /**
     * @var UploadedFile
     */
    protected $pictureFile;

    /**
     * User constructor.
     * @param ?int$id
     * @param bool $int
     * @param string $firstname
     * @param string $lastname
     * @param string $username
     * @param string $nickname
     * @param $birthdate
     * @param string $email
     * @param string $password
     * @param $picture
     * @param null $pictureFile
     * @param $positionName
     * @param $token
     * @param $weight_ini
     * @param $usr_weight_1y
     * @param $weight_2y
     * @param $weight_3y
     * @param $weight_4y
     * @param $weight_5y
     * @param $activitiesArchivingNbDays
     * @param $rememberMeToken
     * @param $validated
     * @param $enabledCreatingUser
     * @param $createdBy
     * @param $lastConnected
     * @param $deleted
     * @param int $role
     * @param $externalUsers
     * @param int $superior
     * @param $mails
     * @param $targets
     * @param $options
     * @param $workerIndividual
     * @param $Reccuring
     * @param $results
     * @param $stagesWhereMaster
     * @param $members
     * @param $weight
     * @param $position
     * @param $department
     * @param $title
     * @param $organization
     */
    public function __construct(
      ?int $id = null,
        $int = true,
        $firstname = '',
        $lastname = '',
        $username = '',
        $nickname = '',
        $birthdate = null,
        $email = '',
        $password = '',
        $picture = null,
        $pictureFile = null,
        $token ='',
        $weight_ini = null,
        int $role = null,
        $department = null,
        $position = null,
        $positionName = null,
        $organization = null,
        $activitiesArchivingNbDays = 7,
        $rememberMeToken = null,
        $superior = null,
        $createdBy = null,
        $validated = null,
        $lastConnected = null,
        $deleted = null,
        $enabledCreatingUser = null,
        $externalUsers = null,
        $mails = null,
        $targets = null,
        $options = null,
        $workerIndividual = null,
        $Reccuring = null,
        $results = null,
        $stagesWhereMaster = null,
        $members = null,
        $weight = null,
        $title = null)
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->pictureFile = $pictureFile;
        $this->internal = $int;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->username = $username;
        $this->nickname = $nickname;
        $this->birthdate = $birthdate;
        $this->email = $email;
        $this->password = $password;
        $this->positionName = $positionName;
        $this->picture = $picture;
        $this->token = $token;
        $this->weight_ini = $weight_ini;
        $this->activitiesArchivingNbDays = $activitiesArchivingNbDays;
        $this->rememberMeToken = $rememberMeToken;
        $this->validated = $validated;
        $this->enabledCreatingUser = $enabledCreatingUser;
        $this->lastConnected = $lastConnected;
        $this->deleted = $deleted;
        $this->role = $role;
        $this->externalUsers = new ArrayCollection();
        $this->superior = $superior;
        $this->mails = $mails?:new ArrayCollection();
        $this->targets = $targets?:new ArrayCollection();
        $this->options = $options?:new ArrayCollection();
        $this->workerIndividual = $workerIndividual;
        $this->Reccuring = $Reccuring;
        $this->results = new ArrayCollection();
        $this->stagesWhereMaster = $stagesWhereMaster;
        $this->members = $members;
        $this->weight = $weight;
        $this->position = $position;
        $this->department = $department;
        $this->title = $title;
        $this->organization = $organization;
        $this->leadingDepartments = new ArrayCollection();
        $this->subordinates = new ArrayCollection();
        $this->participations = new ArrayCollection();
    }


    public function isInternal(): ?bool
    {
        return $this->internal;
    }

    public function setInt(bool $int): self
    {
        $this->internal = $int;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getBirthdate(): ?DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPositionName(): ?string
    {
        return $this->positionName;
    }

    public function setPositionName(?string $positionName): self
    {
        $this->positionName = $positionName;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getWeightIni(): ?float
    {
        return $this->weight_ini;
    }

    public function setWeightIni(float $weight_ini): self
    {
        $this->weight_ini = $weight_ini;

        return $this;
    }



    public function getActivitiesArchivingNbDays(): ?int
    {
        return $this->activitiesArchivingNbDays;
    }

    public function setActivitiesArchivingNbDays(int $activitiesArchivingNbDays): self
    {
        $this->activitiesArchivingNbDays = $activitiesArchivingNbDays;

        return $this;
    }

    public function getRememberMeToken(): ?string
    {
        return $this->rememberMeToken;
    }

    public function setRememberMeToken(string $rememberMeToken): self
    {
        $this->rememberMeToken = $rememberMeToken;

        return $this;
    }

    public function getValidated(): ?DateTimeInterface
    {
        return $this->validated;
    }

    public function setValidated(DateTimeInterface $validated): self
    {
        $this->validated = $validated;

        return $this;
    }

    public function getEnabledCreatingUser(): ?bool
    {
        return $this->enabledCreatingUser;
    }

    public function setEnabledCreatingUser(bool $enabledCreatingUser): self
    {
        $this->enabledCreatingUser = $enabledCreatingUser;
        return $this;
    }

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

        return $this;
    }

    public function getLastConnected(): ?DateTimeInterface
    {
        return $this->lastConnected;
    }

    public function setLastConnected(?DateTimeInterface $lastConnected): self
    {
        $this->lastConnected = $lastConnected;
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
     * @param ?int$id
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getRole(): int
    {
        return $this->role;
    }

    /**
     * @param int $role
     * @return User
     */
    public function setRole(int $role): User
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return User
     */
    public function getSuperior()
    {
        return $this->superior;
    }

    /**
     * @param User $superior
     * @return User
     */
    public function setSuperior(?User $superior)
    {
        $this->superior = $superior;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMails()
    {
        return $this->mails;
    }

    /**
     * @param mixed $mails
     * @return User
     */
    public function setMails($mails): User
    {
        $this->mails = $mails;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTargets()
    {
        return $this->targets;
    }


    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return mixed
     */
    public function getWorkerIndividual()
    {
        return $this->workerIndividual;
    }

    /**
     * @param mixed $workerIndividual
     * @return User
     */
    public function setWorkerIndividual($workerIndividual): User
    {
        $this->workerIndividual = $workerIndividual;
        return $this;
    }

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getParticipations(){
        return $this->participations;
    }

    public function addParticipation(Participation $participation): self
    {
        $this->participations->add($participation);
        $participation->setUser($this);
        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        $this->participations->removeElement($participation);
        return $this;
    }

    /**
     * @return ArrayCollection|ExternalUser[]
     */
    public function getExternalUsers()
    {
        return $this->externalUsers;
    }

    public function addExternalUser(ExternalUser $externalUser): self
    {
        $this->externalUsers->add($externalUser);
        $externalUser->setUser($this);
        return $this;
    }

    public function removeExternalUser(ExternalUser $externalUser): self
    {
        $this->externalUsers->removeElement($externalUser);
        return $this;
    }

    /**
     * @return ArrayCollection|EventComment[]
     */
    public function getEventComments()
    {
        return $this->eventComments;
    }

    public function addEventComment(EventComment $eventComment): self
    {
        $this->eventComments->add($eventComment);
        $eventComment->setUser($this);
        return $this;
    }

    public function removeEventComment(EventComment $eventComment): self
    {
        $this->eventComments->removeElement($eventComment);
        return $this;
    }

    /**
     * @return ArrayCollection|Recurring[]
     */
    public function getReccuring()
    {
        return $this->Reccuring;
    }

    public function addReccuring(Recurring $reccuring): self
    {
        if (!$this->Reccuring->contains($reccuring)) {
            $this->Reccuring[] = $reccuring;
            $reccuring->setRecMasterUser($this);
        }

        return $this;
    }

    public function removeReccuring(Recurring $reccuring): self
    {
        if ($this->Reccuring->contains($reccuring)) {
            $this->Reccuring->removeElement($reccuring);
            // set the owning side to null (unless already changed)
            if ($reccuring->getRecMasterUser() === $this) {
                $reccuring->setRecMasterUser(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection|Result[]
     */
    public function getResults()
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setUserUsr($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getUserUsr() === $this) {
                $result->setUserUsr(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection|Stage[]
     */
    public function getStagesWhereMaster()
    {
        return $this->stagesWhereMaster;
    }

    public function addStagesWhereMaster(Stage $stagesWhereMaster): self
    {
        if (!$this->stagesWhereMaster->contains($stagesWhereMaster)) {
            $this->stagesWhereMaster[] = $stagesWhereMaster;
            $stagesWhereMaster->setMasterUser($this);
        }

        return $this;
    }

    public function removeStagesWhereMaster(Stage $stagesWhereMaster): self
    {
        if ($this->stagesWhereMaster->contains($stagesWhereMaster)) {
            $this->stagesWhereMaster->removeElement($stagesWhereMaster);
            // set the owning side to null (unless already changed)
            if ($stagesWhereMaster->getMasterUser() === $this) {
                $stagesWhereMaster->setMasterUser(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection|Member[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setUser($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
            // set the owning side to null (unless already changed)
            if ($member->getUser() === $this) {
                $member->setUser(null);
            }
        }

        return $this;
    }

    public function getWeight(): ?Weight
    {
        return $this->weight;
    }

    public function setWeight(Weight $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getTitle(): ?Title
    {
        return $this->title;
    }

    public function setTitle(?Title $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    public function setOrganization(Organization $organization): self
    {
        $this->organization = $organization;

        return $this;
    }
    public function getFullName(): string
    {
        if ($this->deleted) {
            return (string) $this->id;
        }
        return "$this->firstname $this->lastname";
    }

    public function getInvertedFullName(): string
    {
        if ($this->deleted) {
            return (string) $this->id;
        }

        return "$this->lastname $this->firstname";
    }
    public function getAbbr()
    {
        $prefix = $this->firstname ? $this->firstname[0] : '';
        $suffix = $this->lastname ? $this->lastname[0] : '';
        return strtoupper($prefix . $suffix);
    }
    public function removeOption(OrganizationUserOption $option): User
    {
        $this->options->removeElement($option);
        return $this;
    }
    public function __toString()
    {
        return  strval($this->id);
    }
    public function addMail(Mail $mail): User
    {
        $this->mails->add($mail);
        $mail->setUser($this);
        return $this;
    }

    public function removeMail(Mail $mail): User
    {
        $this->mails->removeElement($mail);
        return $this;
    }
    public function addTarget(Target $target): User
    {
        $this->targets->add($target);
        $target->setUser($this);
        return $this;
    }

    public function removeTarget(Target $target): User
    {
        $this->targets->removeElement($target);
        return $this;
    }

    /**
     * @param Stage $stage
     * @param User $gradingUser
     * @return ArrayCollection|Grade[]
     */
    public function getGrades(Stage $stage, User $gradingUser)
    {
        $grades = new ArrayCollection;
        foreach ($stage->getParticipants() as $participant) {
            // Retrieving grading user participations
            if ($participant->getUsrId() == $gradingUser->getId()) {
                foreach ($participant->getGrades() as $grade) {
                    if ($grade->getGradedUsrId() == $this->id) {
                        $grades->add($grade);
                    }
                }
            }
        }
        return $grades;
    }

    /**
     * @param Stage $stage
     * @return ArrayCollection|Participation[]
     */
    public function getStageParticipations(Stage $stage)
    {
        return $stage->getParticipants()->matching(Criteria::create()->where(Criteria::expr()->eq("usrId", $this->id)));
    }
    //TODO get Role et les autres gros trucs, subordinates


    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize([
            $this->getId(),
            $this->getUsername(),
            $this->getEmail(),
            $this->getPassword(),
        ]);
    }

    public function unserialize($serialized)
    {
        [
            $this->id,
            $this->username,
            $this->email,
            $this->password,
        ] = unserialize($serialized, ['allow_classes' => false]);
    }

    public function toArray(): array
    {
        if ($this->position !== null) {
            $posName = $this->position->getName();
        } else {
            $posName = "";
        }

//        $sql =
//            'SELECT par_id
//         FROM activity_user
//         INNER JOIN activity ON activity_user.activity_act_id = activity.act_id
//         WHERE activity.act_status >= :status
//        AND activity_user.user_usr_id = :usrId GROUP BY activity_user.activity_act_id';
//        $pdoStatement = $app['db']->prepare($sql);
//        $pdoStatement->bindValue(':status', 2);
//        $pdoStatement->bindValue(':usrId', $this->id);
//        $pdoStatement->execute();
//        $nbCompletedActivities = count($pdoStatement->fetchAll());

//        $sql =
//            'SELECT par_id
//         FROM activity_user
//         INNER JOIN activity ON activity_user.activity_act_id = activity.act_id
//         WHERE activity.act_status IN (:status_1, :status_2)
//         AND activity_user.user_usr_id = :usrId
//         GROUP BY activity_user.activity_act_id';
//
//        $pdoStatement = $app['db']->prepare($sql);
//        $pdoStatement->bindValue(':status_1', 0);
//        $pdoStatement->bindValue(':status_2', 1);
//        $pdoStatement->bindValue(':usrId', $this->id);
//        $pdoStatement->execute();
//        $nbScheduledActivities = count($pdoStatement->fetchAll());
//
//        //* GET WEIGHT
//        // Weight is defined for internal user (we find associated weight object), whereas for external user it is a property value.
//        $em = MasterController::getEntityManager();
//
//        if ($this->wgtId !== null) {
//            /**
//             * @var EntityRepository
//             */
//            $weightRepository = $em->getRepository(Weight::class);
//            /**
//             * @var Weight
//             */
//            $weight = $weightRepository->find($this->wgtId);
//            $weightValue = $weight ? $weight->getValue() : 0;
//        } else {
//            /**
//             * @var EntityRepository
//             */
//            $externalUserRepository = $em->getRepository(ExternalUser::class);
//            /**
//             * @var ExternalUser
//             */
//            $externalUser = $externalUserRepository->findOneBy(['user' => $this]);
//            $weightValue = $externalUser ? $externalUser->getWeightValue() : 0;
//        }

        return [
            'obj' => $this,
            'id' => $this->id,
            'orgId' => $this->getOrganization()->getId(),
            'organization' => $this->getOrganization(),
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'picture' => $this->picture,
            'weight' => $this->weight?$this->weight->getValue():null,
            'inserted' => $this->inserted,
            'internal' => $this->internal,
            'position' => $posName,
            'role' => $this->role,
            'email' => ($this->email === null) ? 0 : 1,
//            'avgGrade' => ($this->role == 3) ? $this->getAverage($app, false, 'P', 'W', 'A') : $this->getAverage($app, true, 'P', 'W', 'A'),
//            'avgStdDevRatio' => ($this->role == 3) ? $this->getAverage($app, false, 'D', 'W', 'A') : $this->getAverage($app, true, 'D', 'W', 'A'),
//            'nbCompletedActivities' => $nbCompletedActivities,
//            'nbScheduledActivities' => $nbScheduledActivities,
            'lastConnected' => $this->getLastConnected(),
        ];
    }

    public function is_root(): bool
    {
        return $this->role === 4;
    }

    public function is_admin(): bool
    {
        return $this->role === 1;
    }
    public function is_am(): bool
    {
        return $this->role === 2;
    }

    public function is_collab(): bool
    {
        return $this->role === 3;
    }

     /**
     * @return ArrayCollection|User[]
     */
    public function getSubordinates()
    {
        return $this->subordinates;
    }

    public function addSubordinate(User $user): User
    {
        $this->subordinates->add($user);
        $user->setSuperior($this);
        return $this;
    }

    public function removeSubordinate(User $user): User
    {
        $this->subordinates->removeElement($user);
        return $this;
    }

}
