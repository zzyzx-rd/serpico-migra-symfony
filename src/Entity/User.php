<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=UserRepository::class)
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

}