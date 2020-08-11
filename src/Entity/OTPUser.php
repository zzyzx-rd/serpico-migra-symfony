<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OTPUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=OTPUserRepository::class)
 */
class OTPUser extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="otp_id", type="integer", nullable=false, length=10)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $otp_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $otp_fullname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $otp_tipe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $otp_email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $otp_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $otp_inserted;

    /**
     *@ManyToOne(targetEntity="Organization")
     *@JoinColumn(name="otp_organization", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    /**
     * OTPUser constructor.
     * @param int $id
     * @param $otp_type
     * @param $otp_fullname
     * @param $otp_tipe
     * @param $otp_email
     * @param $otp_createdBy
     * @param $otp_inserted
     * @param $organization
     */
    public function __construct(
        int $id = 0,
        $otp_type = null,
        $otp_fullname = null,
        $otp_tipe = null,
        Organization $organization = null,
        $otp_email = null,
        $otp_createdBy = null,
        $otp_inserted = null)
    {
        $this->otp_type = $otp_type;
        $this->otp_fullname = $otp_fullname;
        $this->otp_tipe = $otp_tipe;
        $this->otp_email = $otp_email;
        $this->otp_inserted = $otp_inserted;
        $this->organization = $organization;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->otp_type;
    }

    public function setType(string $otp_type): self
    {
        $this->otp_type = $otp_type;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->otp_fullname;
    }

    public function setFullname(string $otp_fullname): self
    {
        $this->otp_fullname = $otp_fullname;

        return $this;
    }

    public function getTipe(): ?string
    {
        return $this->otp_tipe;
    }

    public function setTipe(string $otp_tipe): self
    {
        $this->otp_tipe = $otp_tipe;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->otp_email;
    }

    public function setEmail(string $otp_email): self
    {
        $this->otp_email = $otp_email;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->otp_createdBy;
    }

    public function setCreatedBy(?int $otp_createdBy): self
    {
        $this->otp_createdBy = $otp_createdBy;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->otp_inserted;
    }

    public function setInserted(\DateTimeInterface $otp_inserted): self
    {
        $this->otp_inserted = $otp_inserted;

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

}
