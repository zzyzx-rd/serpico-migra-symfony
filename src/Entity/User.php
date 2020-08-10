<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"email"},
 *     message="This email already exists"
 * )
 */
class User
{
    /**
     * @Id()
     * @GeneratedValue()
     * @Column(name="usr_id", type="integer", nullable=false)
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $usr_int;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $usr_firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $usr_lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $usr_username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $usr_nickname;

    /**
     * @ORM\Column(type="datetime")
     */
    private $usr_birthdate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $usr_email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $usr_password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $usr_positionName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $usr_picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $usr_token;

    /**
     * @ORM\Column(type="float")
     */
    private $usr_weight_ini;

    /**
     * @ORM\Column(type="float")
     */
    private $usr_usr_weight_1y;

    /**
     * @ORM\Column(type="float")
     */
    private $usr_weight_2y;

    /**
     * @ORM\Column(type="float")
     */
    private $usr_weight_3y;

    /**
     * @ORM\Column(type="float")
     */
    private $usr_weight_4y;

    /**
     * @ORM\Column(type="float")
     */
    private $usr_weight_5y;

    /**
     * @ORM\Column(type="integer")
     */
    private $usr_act_archive_nbDays;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $usr_rm_token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $usr_validated;

    /**
     * @ORM\Column(type="boolean")
     */
    private $usr_enabledCreatingUser;

    /**
     * @ORM\Column(type="integer")
     */
    private $usr_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $usr_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $usr_last_connected;

    /**
     * @ORM\Column(type="datetime")
     */
    private $usr_deleted;

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
    private $externalUsers;

    /** @ManyToOne(targetEntity="User", inversedBy="subordinates")
     * @JoinColumn(name="usr_superior", referencedColumnName="usr_id", nullable=true)
     * @Column(name="usr_superior", type="integer")
     * @var int
     */
    protected $superior;

    /**
     * @OneToMany(targetEntity="Mail", mappedBy="user",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $mails;

    /**
     * @OneToMany(targetEntity="Target", mappedBy="user",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $targets;

    /**
     * @OneToMany(targetEntity="OrganizationUserOption", mappedBy="user", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $options;

    /**
     * @OneToOne(targetEntity="WorkerIndividual")
     * @JoinColumn(name="worker_individual_win_id", referencedColumnName="win_id")
     */
    private $workerIndividual;

    /**
     * @ORM\OneToMany(targetEntity=ActivityUser::class, mappedBy="user_usr")
     */
    private $activity_user_usr;

    /**
     * @ORM\OneToMany(targetEntity=Recurring::class, mappedBy="rec_master_user")
     */
    private $Reccuring;

    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="user_usr")
     */
    private $results;

    /**
     * @ORM\OneToMany(targetEntity=Stage::class, mappedBy="stg_master_user")
     */
    private $stagesWhereMaster;

    /**
     * @ORM\OneToMany(targetEntity=TeamUser::class, mappedBy="user_usr")
     */
    private $teamUsers;

    /**
     * @ORM\OneToOne(targetEntity=Weight::class, inversedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $weight_wgt;

    /**
     * @ORM\ManyToOne(targetEntity=Position::class)
     */
    private $position_pos;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class)
     */
    private $departement_dpt;

    /**
     * @ORM\ManyToOne(targetEntity=Title::class)
     */
    private $title_tit;

    /**
     * @ORM\ManyToOne(targetEntity=Organization::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $organization_org;

    public function __construct()
    {
        $this->Reccuring = new ArrayCollection();
        $this->results = new ArrayCollection();
        $this->stagesWhereMaster = new ArrayCollection();
        $this->teamUsers = new ArrayCollection();
    }

    public function getUsrInt(): ?bool
    {
        return $this->usr_int;
    }

    public function setUsrInt(bool $usr_int): self
    {
        $this->usr_int = $usr_int;

        return $this;
    }

    public function getUsrFirstname(): ?string
    {
        return $this->usr_firstname;
    }

    public function setUsrFirstname(string $usr_firstname): self
    {
        $this->usr_firstname = $usr_firstname;

        return $this;
    }

    public function getUsrLastname(): ?string
    {
        return $this->usr_lastname;
    }

    public function setUsrLastname(string $usr_lastname): self
    {
        $this->usr_lastname = $usr_lastname;

        return $this;
    }

    public function getUsrUsername(): ?string
    {
        return $this->usr_username;
    }

    public function setUsrUsername(string $usr_username): self
    {
        $this->usr_username = $usr_username;

        return $this;
    }

    public function getUsrNickname(): ?string
    {
        return $this->usr_nickname;
    }

    public function setUsrNickname(string $usr_nickname): self
    {
        $this->usr_nickname = $usr_nickname;

        return $this;
    }

    public function getUsrBirthdate(): ?\DateTimeInterface
    {
        return $this->usr_birthdate;
    }

    public function setUsrBirthdate(\DateTimeInterface $usr_birthdate): self
    {
        $this->usr_birthdate = $usr_birthdate;

        return $this;
    }

    public function getUsrEmail(): ?string
    {
        return $this->usr_email;
    }

    public function setUsrEmail(?string $usr_email): self
    {
        $this->usr_email = $usr_email;

        return $this;
    }

    public function getUsrPassword(): ?string
    {
        return $this->usr_password;
    }

    public function setUsrPassword(?string $usr_password): self
    {
        $this->usr_password = $usr_password;

        return $this;
    }

    public function getUsrPositionName(): ?string
    {
        return $this->usr_positionName;
    }

    public function setUsrPositionName(?string $usr_positionName): self
    {
        $this->usr_positionName = $usr_positionName;

        return $this;
    }

    public function getUsrPicture(): ?string
    {
        return $this->usr_picture;
    }

    public function setUsrPicture(string $usr_picture): self
    {
        $this->usr_picture = $usr_picture;

        return $this;
    }

    public function getUsrToken(): ?string
    {
        return $this->usr_token;
    }

    public function setUsrToken(string $usr_token): self
    {
        $this->usr_token = $usr_token;

        return $this;
    }

    public function getUsrWeightIni(): ?float
    {
        return $this->usr_weight_ini;
    }

    public function setUsrWeightIni(float $usr_weight_ini): self
    {
        $this->usr_weight_ini = $usr_weight_ini;

        return $this;
    }

    public function getUsrUsrWeight1y(): ?float
    {
        return $this->usr_usr_weight_1y;
    }

    public function setUsrUsrWeight1y(float $usr_usr_weight_1y): self
    {
        $this->usr_usr_weight_1y = $usr_usr_weight_1y;

        return $this;
    }

    public function getUsrWeight2y(): ?float
    {
        return $this->usr_weight_2y;
    }

    public function setUsrWeight2y(float $usr_weight_2y): self
    {
        $this->usr_weight_2y = $usr_weight_2y;

        return $this;
    }

    public function getUsrWeight3y(): ?float
    {
        return $this->usr_weight_3y;
    }

    public function setUsrWeight3y(float $usr_weight_3y): self
    {
        $this->usr_weight_3y = $usr_weight_3y;

        return $this;
    }

    public function getUsrWeight4y(): ?float
    {
        return $this->usr_weight_4y;
    }

    public function setUsrWeight4y(float $usr_weight_4y): self
    {
        $this->usr_weight_4y = $usr_weight_4y;

        return $this;
    }

    public function getUsrWeight5y(): ?float
    {
        return $this->usr_weight_5y;
    }

    public function setUsrWeight5y(float $usr_weight_5y): self
    {
        $this->usr_weight_5y = $usr_weight_5y;

        return $this;
    }

    public function getUsrActArchiveNbDays(): ?int
    {
        return $this->usr_act_archive_nbDays;
    }

    public function setUsrActArchiveNbDays(int $usr_act_archive_nbDays): self
    {
        $this->usr_act_archive_nbDays = $usr_act_archive_nbDays;

        return $this;
    }

    public function getUsrRmToken(): ?string
    {
        return $this->usr_rm_token;
    }

    public function setUsrRmToken(string $usr_rm_token): self
    {
        $this->usr_rm_token = $usr_rm_token;

        return $this;
    }

    public function getUsrValidated(): ?\DateTimeInterface
    {
        return $this->usr_validated;
    }

    public function setUsrValidated(\DateTimeInterface $usr_validated): self
    {
        $this->usr_validated = $usr_validated;

        return $this;
    }

    public function getUsrEnabledCreatingUser(): ?bool
    {
        return $this->usr_enabledCreatingUser;
    }

    public function setUsrEnabledCreatingUser(bool $usr_enabledCreatingUser): self
    {
        $this->usr_enabledCreatingUser = $usr_enabledCreatingUser;

        return $this;
    }

    public function getUsrCreatedBy(): ?int
    {
        return $this->usr_createdBy;
    }

    public function setUsrCreatedBy(int $usr_createdBy): self
    {
        $this->usr_createdBy = $usr_createdBy;

        return $this;
    }

    public function getUsrInserted(): ?\DateTimeInterface
    {
        return $this->usr_inserted;
    }

    public function setUsrInserted(\DateTimeInterface $usr_inserted): self
    {
        $this->usr_inserted = $usr_inserted;

        return $this;
    }

    public function getUsrLastConnected(): ?\DateTimeInterface
    {
        return $this->usr_last_connected;
    }

    public function setUsrLastConnected(?\DateTimeInterface $usr_last_connected): self
    {
        $this->usr_last_connected = $usr_last_connected;

        return $this;
    }

    public function getUsrDeleted(): ?\DateTimeInterface
    {
        return $this->usr_deleted;
    }

    public function setUsrDeleted(\DateTimeInterface $usr_deleted): self
    {
        $this->usr_deleted = $usr_deleted;

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
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
    public function setRole(int $role): void
    {
        $this->role = $role;
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
    public function setExternalUsers($externalUsers): void
    {
        $this->externalUsers = $externalUsers;
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
    public function setSuperior(int $superior): void
    {
        $this->superior = $superior;
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
    public function setMails($mails): void
    {
        $this->mails = $mails;
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
    public function getWorkerIndividual()
    {
        return $this->workerIndividual;
    }

    /**
     * @param mixed $workerIndividual
     */
    public function setWorkerIndividual($workerIndividual): void
    {
        $this->workerIndividual = $workerIndividual;
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

    public function getPositionPos(): ?Position
    {
        return $this->position_pos;
    }

    public function setPositionPos(?Position $position_pos): self
    {
        $this->position_pos = $position_pos;

        return $this;
    }

    public function getDepartementDpt(): ?Department
    {
        return $this->departement_dpt;
    }

    public function setDepartementDpt(?Department $departement_dpt): self
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

    public function getOrganizationOrg(): ?Organization
    {
        return $this->organization_org;
    }

    public function setOrganizationOrg(?Organization $organization_org): self
    {
        $this->organization_org = $organization_org;

        return $this;
    }

}