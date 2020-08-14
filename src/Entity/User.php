<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
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
 * @ORM\Entity(repositoryClass=App\Repository\UserRepository::class)
 */
// * @UniqueEntity(
// *     fields={"email"},
// *     message="This email already exists"
// * )
class User extends DbObject implements  UserInterface, \Serializable
{

    const ROLE_ADMIN = 1;
    const ROLE_ROOT = 4;
    const ROLE_AM = 2;
    const ROLE_COLLAB = 3;
    /**
     * @Id()
     * @GeneratedValue()
     * @Column(name="usr_id", type="integer", nullable=false)
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(name="usr_int", type="boolean")
     */
    public $internal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $usr_firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $usr_lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $usr_username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $usr_nickname;

    /**
     * @ORM\Column(type="datetime")
     */
    public $usr_birthdate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $usr_email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $usr_password;

    /**
     * @ORM\Column(name="usr_position_name", type="string", length=255, nullable=true)
     */
    public $usr_positionName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $usr_picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $usr_token;

    /**
     * @ORM\Column(type="float")
     */
    public $usr_weight_ini;

    /**
     * @ORM\Column(type="float")
     */
    public $usr_usr_weight_1y;

    /**
     * @ORM\Column(type="float")
     */
    public $usr_weight_2y;

    /**
     * @ORM\Column(type="float")
     */
    public $usr_weight_3y;

    /**
     * @ORM\Column(type="float")
     */
    public $usr_weight_4y;

    /**
     * @ORM\Column(type="float")
     */
    public $usr_weight_5y;

    /**
     * @ORM\Column(type="integer")
     */
    public $usr_act_archive_nbDays;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $usr_rm_token;

    /**
     * @ORM\Column(type="datetime")
     */
    public $usr_validated;

    /**
     * @ORM\Column(type="boolean")
     */
    public $usr_enabledCreatingUser;

    /**
     * @ORM\Column(name="usr_created_by", type="integer")
     */
    public $createdBy;

    /**
     * @ORM\Column(name="usr_inserted", type="datetime")
     */
    public $inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $usr_last_connected;

    /**
     * @ORM\Column(name="usr_deleted", type="datetime")
     */
    public $deleted;

    /**
     * @ManyToOne(targetEntity="Role")
     * @JoinColumn(name="rol_id", referencedColumnName="role_rol_id")
     * @Column(name="role_rol_id", type="integer")
     * @var int
     */
    protected $role;

    /**
     * @OneToMany(targetEntity="ExternalUser", mappedBy="user",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $externalUsers;

    /** @ManyToOne(targetEntity="User", inversedBy="subordinates")
     * @JoinColumn(name="usr_superior", referencedColumnName="usr_id", nullable=true)
     * @Column(name="usr_superior", type="integer")
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
     * @ORM\OneToMany(targetEntity=ActivityUser::class, mappedBy="user_usr")
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
     * @ORM\OneToMany(targetEntity=Stage::class, mappedBy="stg_master_user")
     */
    public $stagesWhereMaster;

    /**
     * @ORM\OneToMany(targetEntity=TeamUser::class, mappedBy="user_usr")
     */
    public $teamUsers;

    /**
     * @ORM\OneToOne(targetEntity=Weight::class, inversedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="weight_wgt_id",referencedColumnName="wgt_id", nullable=false)
     */
    public $weight_wgt;

    /**
     * @ORM\ManyToOne(targetEntity=Position::class)
     * @JoinColumn(name="position_pos_id", referencedColumnName="pos_id")
     */
    public $position_pos;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class)
     * @JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id")
     */
    public $departement_dpt;

    /**
     * @ORM\ManyToOne(targetEntity=Title::class)
     * @JoinColumn(name="title_tit_id", referencedColumnName="tit_id")
     */
    public $title_tit;

    /**
     * @ORM\ManyToOne(targetEntity=Organization::class)
     * @ORM\JoinColumn(nullable=false, name="organization_org_id", referencedColumnName="org_id")
     */
    public $organization_org;

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
     * @param int $id
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
        int $id = null,
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
        $this->usr_firstname = $usr_firstname;
        $this->usr_lastname = $usr_lastname;
        $this->usr_username = $usr_username;
        $this->usr_nickname = $usr_nickname;
        $this->usr_birthdate = $usr_birthdate;
        $this->usr_email = $usr_email;
        $this->usr_password = $usr_password;
        $this->usr_positionName = $usr_positionName;
        $this->usr_picture = $usr_picture;
        $this->usr_token = $usr_token;
        $this->usr_weight_ini = $usr_weight_ini;
        $this->usr_usr_weight_1y = $usr_usr_weight_1y;
        $this->usr_weight_2y = $usr_weight_2y;
        $this->usr_weight_3y = $usr_weight_3y;
        $this->usr_weight_4y = $usr_weight_4y;
        $this->usr_weight_5y = $usr_weight_5y;
        $this->usr_act_archive_nbDays = $usr_act_archive_nbDays;
        $this->usr_rm_token = $usr_rm_token;
        $this->usr_validated = $usr_validated;
        $this->usr_enabledCreatingUser = $usr_enabledCreatingUser;
        $this->inserted = $usr_inserted;
        $this->usr_last_connected = $usr_last_connected;
        $this->deleted = $usr_deleted;
        $this->role = $role;
        $this->externalUsers = $externalUsers?$externalUsers:new ArrayCollection();
        $this->superior = $superior;
        $this->mails = $mails?$mails:new ArrayCollection();
        $this->targets = $targets?$targets:new ArrayCollection();
        $this->options = $options?$options:new ArrayCollection();
        $this->workerIndividual = $workerIndividual;
        $this->activity_user_usr = $activity_user_usr;
        $this->Reccuring = $Reccuring;
        $this->results = $results;
        $this->stagesWhereMaster = $stagesWhereMaster;
        $this->teamUsers = $teamUsers;
        $this->weight_wgt = $weight_wgt;
        $this->position_pos = $position_pos;
        $this->departement_dpt = $departement_dpt;
        $this->title_tit = $title_tit;
        $this->organization_org = $organization_org;
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
        return $this->usr_firstname;
    }

    public function setFirstname(string $usr_firstname): self
    {
        $this->usr_firstname = $usr_firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->usr_lastname;
    }

    public function setLastname(string $usr_lastname): self
    {
        $this->usr_lastname = $usr_lastname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->usr_username;
    }

    public function setUsername(string $usr_username): self
    {
        $this->usr_username = $usr_username;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->usr_nickname;
    }

    public function setNickname(string $usr_nickname): self
    {
        $this->usr_nickname = $usr_nickname;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->usr_birthdate;
    }

    public function setBirthdate(\DateTimeInterface $usr_birthdate): self
    {
        $this->usr_birthdate = $usr_birthdate;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->usr_email;
    }

    public function setEmail(?string $usr_email): self
    {
        $this->usr_email = $usr_email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->usr_password;
    }

    public function setPassword(?string $usr_password): self
    {
        $this->usr_password = $usr_password;

        return $this;
    }

    public function getPositionName(): ?string
    {
        return $this->usr_positionName;
    }

    public function setPositionName(?string $usr_positionName): self
    {
        $this->usr_positionName = $usr_positionName;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->usr_picture;
    }

    public function setPicture(string $usr_picture): self
    {
        $this->usr_picture = $usr_picture;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->usr_token;
    }

    public function setToken(string $usr_token): self
    {
        $this->usr_token = $usr_token;

        return $this;
    }

    public function getWeightIni(): ?float
    {
        return $this->usr_weight_ini;
    }

    public function setWeightIni(float $usr_weight_ini): self
    {
        $this->usr_weight_ini = $usr_weight_ini;

        return $this;
    }

    public function getUsrWeight1y(): ?float
    {
        return $this->usr_usr_weight_1y;
    }

    public function setUsrWeight1y(float $usr_usr_weight_1y): self
    {
        $this->usr_usr_weight_1y = $usr_usr_weight_1y;

        return $this;
    }

    public function getWeight2y(): ?float
    {
        return $this->usr_weight_2y;
    }

    public function setWeight2y(float $usr_weight_2y): self
    {
        $this->usr_weight_2y = $usr_weight_2y;

        return $this;
    }

    public function getWeight3y(): ?float
    {
        return $this->usr_weight_3y;
    }

    public function setWeight3y(float $usr_weight_3y): self
    {
        $this->usr_weight_3y = $usr_weight_3y;

        return $this;
    }

    public function getWeight4y(): ?float
    {
        return $this->usr_weight_4y;
    }

    public function setWeight4y(float $usr_weight_4y): self
    {
        $this->usr_weight_4y = $usr_weight_4y;

        return $this;
    }

    public function getWeight5y(): ?float
    {
        return $this->usr_weight_5y;
    }

    public function setWeight5y(float $usr_weight_5y): self
    {
        $this->usr_weight_5y = $usr_weight_5y;

        return $this;
    }

    public function getActArchiveNbDays(): ?int
    {
        return $this->usr_act_archive_nbDays;
    }

    public function setActArchiveNbDays(int $usr_act_archive_nbDays): self
    {
        $this->usr_act_archive_nbDays = $usr_act_archive_nbDays;

        return $this;
    }

    public function getRememberMeToken(): ?string
    {
        return $this->usr_rm_token;
    }

    public function setRememberMeToken(string $usr_rm_token): self
    {
        $this->usr_rm_token = $usr_rm_token;

        return $this;
    }

    public function getValidated(): ?\DateTimeInterface
    {
        return $this->usr_validated;
    }

    public function setValidated(\DateTimeInterface $usr_validated): self
    {
        $this->usr_validated = $usr_validated;

        return $this;
    }

    public function getEnabledCreatingUser(): ?bool
    {
        return $this->usr_enabledCreatingUser;
    }

    public function setEnabledCreatingUser(bool $usr_enabledCreatingUser): self
    {
        $this->usr_enabledCreatingUser = $usr_enabledCreatingUser;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->inserted;
    }

    public function setInserted(\DateTimeInterface $usr_inserted): self
    {
        $this->inserted = $usr_inserted;

        return $this;
    }

    public function getLastConnected(): ?\DateTimeInterface
    {
        return $this->usr_last_connected;
    }

    public function setLastConnected(?\DateTimeInterface $usr_last_connected): self
    {
        $this->usr_last_connected = $usr_last_connected;

        return $this;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(\DateTimeInterface $usr_deleted): self
    {
        $this->deleted = $usr_deleted;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
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
        return $this->activity_user_usr;
    }

    public function addExternalUser(ActivityUser $externalUser): self
    {
        if (!$this->activity_user_usr->contains($externalUser)) {
            $this->activity_user_usr[] = $externalUser;
            $externalUser->setUserUsr($this);
        }

        return $this;
    }

    public function removeExternalUser(ActivityUser $externalUser): self
    {
        if ($this->activity_user_usr->contains($externalUser)) {
            $this->activity_user_usr->removeElement($externalUser);
            // set the owning side to null (unless already changed)
            if ($externalUser->getUserUsr() === $this) {
                $externalUser->setUserUsr(null);
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
            $stagesWhereMaster->setStgMasterUser($this);
        }

        return $this;
    }

    public function removeStagesWhereMaster(Stage $stagesWhereMaster): self
    {
        if ($this->stagesWhereMaster->contains($stagesWhereMaster)) {
            $this->stagesWhereMaster->removeElement($stagesWhereMaster);
            // set the owning side to null (unless already changed)
            if ($stagesWhereMaster->getStgMasterUser() === $this) {
                $stagesWhereMaster->setStgMasterUser(null);
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
            $teamUser->setUserUsr($this);
        }

        return $this;
    }

    public function removeTeamUser(TeamUser $teamUser): self
    {
        if ($this->teamUsers->contains($teamUser)) {
            $this->teamUsers->removeElement($teamUser);
            // set the owning side to null (unless already changed)
            if ($teamUser->getUserUsr() === $this) {
                $teamUser->setUserUsr(null);
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

    public function getOrganization(): ?Organization
    {
        return $this->organization_org;
    }

    public function setOrganization(?Organization $organization_org): self
    {
        $this->organization_org = $organization_org;

        return $this;
    }
    public function getFullName(): string
    {
        if ($this->deleted) {
            return (string) $this->id;
        }

        return "$this->usr_firstname . $this->usr_lastname";
    }

    public function getInvertedFullName()
    {
        if ($this->deleted) {
            return (string) $this->id;
        }

        return "$this->usr_lastname $this->usr_firstname";
    }
    public function getAbbr()
    {
        $prefix = $this->firstname ? $this->firstname[0] : '';
        $suffix = $this->lastname ? $this->lastname[0] : '';
        return strtoupper($prefix . $suffix);
    }
    public function removeOption(OrganizationUserOption $option)
    {
        $this->options->removeElement($option);
        return $this;
    }
    public function __toString()
    {
        return  strval($this->id);
    }
    public function addMail(Mail $mail)
    {
        $this->mails->add($mail);
        $mail->setUser($this);
        return $this;
    }

    public function removeMail(Mail $mail)
    {
        $this->mails->removeElement($mail);
        return $this;
    }
    public function addTarget(Target $target)
    {
        $this->targets->add($target);
        $target->setUser($this);
        return $this;
    }

    public function removeTarget(Target $target)
    {
        $this->targets->removeElement($target);
        return $this;
    }
    /**
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


    public function getRoles()
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
            $this->usr_username,
            $this->usr_email,
            $this->usr_password,
        ] = unserialize($serialized, ['allow_classes' => false]);
    }

    public function toArray()
    {
        if ($this->position_pos != null) {
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
            'firstname' => $this->usr_firstname,
            'lastname' => $this->usr_lastname,
            'picture' => $this->usr_picture,
            'weight' => $this->weight_wgt?$this->weight_wgt->getValue():null,
            'inserted' => $this->inserted,
            'internal' => $this->internal,
            'position' => $posName,
            'role' => $this->role,
            'email' => ($this->usr_email == null) ? 0 : 1,
//            'avgGrade' => ($this->role == 3) ? $this->getAverage($app, false, 'P', 'W', 'A') : $this->getAverage($app, true, 'P', 'W', 'A'),
//            'avgStdDevRatio' => ($this->role == 3) ? $this->getAverage($app, false, 'D', 'W', 'A') : $this->getAverage($app, true, 'D', 'W', 'A'),
//            'nbCompletedActivities' => $nbCompletedActivities,
//            'nbScheduledActivities' => $nbScheduledActivities,
            'lastConnected' => $this->getLastConnected(),
        ];
    }

    public function is_root(): bool
    {
        return $this->role == 4;
    }

    public function is_admin(): bool
    {
        return $this->role == 1;
    }
    public function is_am(): bool
    {
        return $this->role == 2;
    }

    public function is_collab(): bool
    {
        return $this->role == 3;
    }
}