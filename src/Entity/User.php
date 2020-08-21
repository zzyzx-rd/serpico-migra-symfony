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
     * @ORM\Column(name="usr_usr_weight_1y", type="float", nullable=true)
     */
    public $weight_1y;

    /**
     * @ORM\Column(name="usr_weight_2y", type="float", nullable=true)
     */
    public $weight_2y;

    /**
     * @ORM\Column(name="usr_weight_3y", type="float", nullable=true)
     */
    public $weight_3y;

    /**
     * @ORM\Column(name="usr_weight_4y", type="float", nullable=true)
     */
    public $weight_4y;

    /**
     * @ORM\Column(name="usr_weight_5y", type="float", nullable=true)
     */
    public $weight_5y;

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
     * @ORM\Column(name="usr_inserted", type="datetime", nullable=true)
     */
    public ?DateTime $inserted;

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

    /** @ManyToOne(targetEntity="User", inversedBy="subordinates")
     * @JoinColumn(name="usr_superior", referencedColumnName="usr_id", nullable=true)
     * @Column(name="usr_superior", type="integer", nullable=true)
     * @var int
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
     * @ORM\OneToMany(targetEntity=ActivityUser::class, mappedBy="user")
     */
    public $activity_user_act_usr;

    /**
     * @ORM\OneToMany(targetEntity=Recurring::class, mappedBy="rec_master_user")
     */
    public $Reccuring;

    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="user_usr")
     */
    public $results;

    /**
     * @ORM\OneToMany(targetEntity=Stage::class, mappedBy="masterUser")
     */
    public $stagesWhereMaster;

    /**
     * @ORM\OneToMany(targetEntity=TeamUser::class, mappedBy="user")
     */
    public $teamUsers;

    /**
     * @ORM\OneToOne(targetEntity=Weight::class, inversedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="weight_wgt_id",referencedColumnName="wgt_id", nullable=true)
     */
    public $weight_wgt;

    /**
     * @ORM\ManyToOne(targetEntity=Position::class)
     * @JoinColumn(name="position_pos_id", referencedColumnName="pos_id", nullable=true)
     */
    public $position_pos;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class)
     * @JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id", nullable=true)
     */
    public $departement_dpt;

    /**
     * @ORM\ManyToOne(targetEntity=Title::class)
     * @JoinColumn(name="title_tit_id", referencedColumnName="tit_id", nullable=true)
     */
    public $title_tit;

    /**
     * @ORM\ManyToOne(targetEntity=Organization::class)
     * @ORM\JoinColumn(nullable=false, name="organization_org_id", referencedColumnName="org_id", nullable=true)
     */
    public $organization;

    /**
     * @OneToMany(targetEntity="Department", mappedBy="masterUser",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $leadingDepartments;
    
    /**
     * @var UploadedFile
     */
    protected $pictureFile;

    /**
     * User constructor.
     * @param ?int$id
     * @param bool $usr_int
     * @param string $usr_firstname
     * @param string $usr_lastname
     * @param string $usr_username
     * @param string $usr_nickname
     * @param $usr_birthdate
     * @param string $usr_email
     * @param string $usr_password
     * @param $usr_picture
     * @param null $pictureFile
     * @param $usr_positionName
     * @param $usr_token
     * @param $usr_weight_ini
     * @param $usr_usr_weight_1y
     * @param $usr_weight_2y
     * @param $usr_weight_3y
     * @param $usr_weight_4y
     * @param $usr_weight_5y
     * @param $usr_act_archive_nbDays
     * @param $usr_rm_token
     * @param $usr_validated
     * @param $usr_enabledCreatingUser
     * @param $usr_createdBy
     * @param $usr_inserted
     * @param $usr_last_connected
     * @param $usr_deleted
     * @param int $role
     * @param $externalUsers
     * @param int $superior
     * @param $mails
     * @param $targets
     * @param $options
     * @param $workerIndividual
     * @param $activity_user_usr
     * @param $Reccuring
     * @param $results
     * @param $stagesWhereMaster
     * @param $teamUsers
     * @param $weight_wgt
     * @param $position_pos
     * @param $departement_dpt
     * @param $title_tit
     * @param $organization_org
     */
    public function __construct(
      ?int $id = null,
        $usr_int = true,
        $usr_firstname = '',
        $usr_lastname = '',
        $usr_username = '',
        $usr_nickname = '',
        $usr_birthdate = null,
        $usr_email = '',
        $usr_password = '',
        $usr_picture = null,
        $pictureFile = null,
        $usr_token ='',
        $usr_weight_ini = null,
        $usr_usr_weight_1y = 0.0,
        $usr_weight_2y = 0.0,
        $usr_weight_3y = 0.0,
        $usr_weight_4y = 0.0,
        $usr_weight_5y = 0.0,
        int $role = null,
        $departement_dpt = null,
        $position_pos = null,
        $usr_positionName = null,
        $organization_org = null,
        $usr_act_archive_nbDays = 7,
        $usr_rm_token = null,
        int $superior = null,
        $usr_createdBy = null,
        $usr_inserted = null,
        $usr_validated = null,
        $usr_last_connected = null,
        $usr_deleted = null,
        $usr_enabledCreatingUser = null,
        $externalUsers = null,
        $mails = null,
        $targets = null,
        $options = null,
        $workerIndividual = null,
        $activity_user_usr = null,
        $Reccuring = null,
        $results = null,
        $stagesWhereMaster = null,
        $teamUsers = null,
        $weight_wgt = null,
        $title_tit = null)
    {
        parent::__construct($id, $usr_createdBy, new DateTime());
        $this->pictureFile = $pictureFile;
        $this->internal = $usr_int;
        $this->firstname = $usr_firstname;
        $this->lastname = $usr_lastname;
        $this->username = $usr_username;
        $this->nickname = $usr_nickname;
        $this->birthdate = $usr_birthdate;
        $this->email = $usr_email;
        $this->password = $usr_password;
        $this->positionName = $usr_positionName;
        $this->picture = $usr_picture;
        $this->token = $usr_token;
        $this->weight_ini = $usr_weight_ini;
        $this->weight_1y = $usr_usr_weight_1y;
        $this->weight_2y = $usr_weight_2y;
        $this->weight_3y = $usr_weight_3y;
        $this->weight_4y = $usr_weight_4y;
        $this->weight_5y = $usr_weight_5y;
        $this->activitiesArchivingNbDays = $usr_act_archive_nbDays;
        $this->rememberMeToken = $usr_rm_token;
        $this->validated = $usr_validated;
        $this->enabledCreatingUser = $usr_enabledCreatingUser;
        $this->inserted = $usr_inserted;
        $this->lastConnected = $usr_last_connected;
        $this->deleted = $usr_deleted;
        $this->role = $role;
        $this->externalUsers = $externalUsers?:new ArrayCollection();
        $this->superior = $superior;
        $this->mails = $mails?:new ArrayCollection();
        $this->targets = $targets?:new ArrayCollection();
        $this->options = $options?:new ArrayCollection();
        $this->workerIndividual = $workerIndividual;
        $this->activity_user_act_usr = $activity_user_usr;
        $this->Reccuring = $Reccuring;
        $this->results = $results;
        $this->stagesWhereMaster = $stagesWhereMaster;
        $this->teamUsers = $teamUsers;
        $this->weight_wgt = $weight_wgt;
        $this->position_pos = $position_pos;
        $this->departement_dpt = $departement_dpt;
        $this->title_tit = $title_tit;
        $this->organization = $organization_org;
        $this->leadingDepartments = new ArrayCollection();
    }


    public function isInternal(): ?bool
    {
        return $this->internal;
    }

    public function setInt(bool $usr_int): self
    {
        $this->internal = $usr_int;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $usr_firstname): self
    {
        $this->firstname = $usr_firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $usr_lastname): self
    {
        $this->lastname = $usr_lastname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $usr_username): self
    {
        $this->username = $usr_username;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $usr_nickname): self
    {
        $this->nickname = $usr_nickname;

        return $this;
    }

    public function getBirthdate(): ?DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(DateTimeInterface $usr_birthdate): self
    {
        $this->birthdate = $usr_birthdate;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $usr_email): self
    {
        $this->email = $usr_email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $usr_password): self
    {
        $this->password = $usr_password;

        return $this;
    }

    public function getPositionName(): ?string
    {
        return $this->positionName;
    }

    public function setPositionName(?string $usr_positionName): self
    {
        $this->positionName = $usr_positionName;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $usr_picture): self
    {
        $this->picture = $usr_picture;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $usr_token): self
    {
        $this->token = $usr_token;

        return $this;
    }

    public function getWeightIni(): ?float
    {
        return $this->weight_ini;
    }

    public function setWeightIni(float $usr_weight_ini): self
    {
        $this->weight_ini = $usr_weight_ini;

        return $this;
    }

    public function getUsrWeight1y(): ?float
    {
        return $this->weight_1y;
    }

    public function setUsrWeight1y(float $usr_usr_weight_1y): self
    {
        $this->weight_1y = $usr_usr_weight_1y;

        return $this;
    }

    public function getWeight2y(): ?float
    {
        return $this->weight_2y;
    }

    public function setWeight2y(float $usr_weight_2y): self
    {
        $this->weight_2y = $usr_weight_2y;

        return $this;
    }

    public function getWeight3y(): ?float
    {
        return $this->weight_3y;
    }

    public function setWeight3y(float $usr_weight_3y): self
    {
        $this->weight_3y = $usr_weight_3y;

        return $this;
    }

    public function getWeight4y(): ?float
    {
        return $this->weight_4y;
    }

    public function setWeight4y(float $usr_weight_4y): self
    {
        $this->weight_4y = $usr_weight_4y;

        return $this;
    }

    public function getWeight5y(): ?float
    {
        return $this->weight_5y;
    }

    public function setWeight5y(float $usr_weight_5y): self
    {
        $this->weight_5y = $usr_weight_5y;

        return $this;
    }

    public function getActivitiesArchivingNbDays(): ?int
    {
        return $this->activitiesArchivingNbDays;
    }

    public function setActivitiesArchivingNbDays(int $usr_act_archive_nbDays): self
    {
        $this->activitiesArchivingNbDays = $usr_act_archive_nbDays;

        return $this;
    }

    public function getRememberMeToken(): ?string
    {
        return $this->rememberMeToken;
    }

    public function setRememberMeToken(string $usr_rm_token): self
    {
        $this->rememberMeToken = $usr_rm_token;

        return $this;
    }

    public function getValidated(): ?DateTimeInterface
    {
        return $this->validated;
    }

    public function setValidated(DateTimeInterface $usr_validated): self
    {
        $this->validated = $usr_validated;

        return $this;
    }

    public function getEnabledCreatingUser(): ?bool
    {
        return $this->enabledCreatingUser;
    }

    public function setEnabledCreatingUser(bool $usr_enabledCreatingUser): self
    {
        $this->enabledCreatingUser = $usr_enabledCreatingUser;

        return $this;
    }

    public function setInserted(DateTimeInterface $usr_inserted): self
    {
        $this->inserted = $usr_inserted;

        return $this;
    }

    public function getLastConnected(): ?DateTimeInterface
    {
        return $this->lastConnected;
    }

    public function setLastConnected(?DateTimeInterface $usr_last_connected): self
    {
        $this->lastConnected = $usr_last_connected;

        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(DateTimeInterface $usr_deleted): self
    {
        $this->deleted = $usr_deleted;

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
     * @return mixed
     */
    public function getExternalUsers()
    {
        return $this->externalUsers;
    }

    /**
     * @param mixed $externalUsers
     * @return User
     */
    public function setExternalUsers($externalUsers): User
    {
        $this->externalUsers = $externalUsers;
        return $this;

    }

    /**
     * @return int
     */
    public function getSuperior(): int
    {
        return $this->superior;
    }

    /**
     * @param int $superior
     * @return User
     */
    public function setSuperior(int $superior): User
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
     * @param mixed $targets
     */
    public function setTargets($targets): User
    {
        $this->targets = $targets;
        return $this;
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
     * @return User
     */
    public function setOptions($options): User
    {
        $this->options = $options;
        return $this;
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
     * @return Collection|ActivityUser[]
     */
    public function getExternalUser(): Collection
    {
        return $this->activity_user_act_usr;
    }

    public function addExternalUser(ActivityUser $externalUser): self
    {
        if (!$this->activity_user_act_usr->contains($externalUser)) {
            $this->activity_user_act_usr[] = $externalUser;
            $externalUser->setUser($this);
        }

        return $this;
    }

    public function removeExternalUser(ActivityUser $externalUser): self
    {
        if ($this->activity_user_act_usr->contains($externalUser)) {
            $this->activity_user_act_usr->removeElement($externalUser);
            // set the owning side to null (unless already changed)
            if ($externalUser->getUser() === $this) {
                $externalUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Recurring[]
     */
    public function getReccuring(): Collection
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
     * @return Collection|Result[]
     */
    public function getResults(): Collection
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
     * @return Collection|Stage[]
     */
    public function getStagesWhereMaster(): Collection
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
     * @return Collection|TeamUser[]
     */
    public function getTeamUsers(): Collection
    {
        return $this->teamUsers;
    }

    public function addTeamUser(TeamUser $teamUser): self
    {
        if (!$this->teamUsers->contains($teamUser)) {
            $this->teamUsers[] = $teamUser;
            $teamUser->setUser($this);
        }

        return $this;
    }

    public function removeTeamUser(TeamUser $teamUser): self
    {
        if ($this->teamUsers->contains($teamUser)) {
            $this->teamUsers->removeElement($teamUser);
            // set the owning side to null (unless already changed)
            if ($teamUser->getUser() === $this) {
                $teamUser->setUser(null);
            }
        }

        return $this;
    }

    public function getWeightWgt(): ?Weight
    {
        return $this->weight_wgt;
    }

    public function setWeightWgt(Weight $weight_wgt): self
    {
        $this->weight_wgt = $weight_wgt;

        return $this;
    }

    public function getPosition(): ?Position
    {
        return $this->position_pos;
    }

    public function setPosition(?Position $position_pos): self
    {
        $this->position_pos = $position_pos;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->departement_dpt;
    }

    public function setDepartment(?Department $departement_dpt): self
    {
        $this->departement_dpt = $departement_dpt;

        return $this;
    }

    public function getTitleTit(): ?Title
    {
        return $this->title_tit;
    }

    public function setTitleTit(?Title $title_tit): self
    {
        $this->title_tit = $title_tit;

        return $this;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    public function setOrganization(Organization $organization_org): self
    {
        $this->organization = $organization_org;

        return $this;
    }
    public function getFullName(): string
    {
        if ($this->deleted) {
            return (string) $this->id;
        }

        return "$this->firstname . $this->lastname";
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
     * @return Collection|Grade[]
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
     * @return Collection|ActivityUser[]
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
        if ($this->position_pos !== null) {
            $posName = $this->position_pos->getName();
        } else {
            $posName = "";
        }

//        $sql =
//            'SELECT a_u_id
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
//            'SELECT a_u_id
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
            'weight' => $this->weight_wgt?$this->weight_wgt->getValue():null,
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
}