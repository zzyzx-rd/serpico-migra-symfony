<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OTPUserRepository;
use DateTime;
use DateTimeInterface;
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
    public ?int $id;

    /**
     * @ORM\Column(name="otp_type", type="string", length=1)
     */
    public $type;

    /**
     * @ORM\Column(name="otp_fullname", type="string", length=255, nullable=true)
     */
    public $fullname;

    /**
     * @ORM\Column(name="otp_tipe", type="string", length=255, nullable=true)
     */
    public $tipe;

    /**
     * @ORM\Column(name="otp_email", type="string", length=255, nullable=true)
     */
    public $email;

    /**
     * @ORM\Column(name="otp_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="otp_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     *@ManyToOne(targetEntity="Organization")
     *@JoinColumn(name="otp_organization", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    /**
     * OTPUser constructor.
     * @param ?int$id
     * @param $otp_type
     * @param $otp_fullname
     * @param $otp_tipe
     * @param $otp_email
     * @param $otp_createdBy
     * @param $otp_inserted
     * @param $organization
     */
    public function __construct(
      ?int $id = 0,
        $otp_type = null,
        $otp_fullname = null,
        $otp_tipe = null,
        Organization $organization = null,
        $otp_email = null,
        $otp_createdBy = null,
        $otp_inserted = null)
    {
        parent::__construct($id,$otp_createdBy , new DateTime());
        $this->type = $otp_type;
        $this->fullname = $otp_fullname;
        $this->tipe = $otp_tipe;
        $this->email = $otp_email;
        $this->organization = $organization;
    }


    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $otp_type): self
    {
        $this->type = $otp_type;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $otp_fullname): self
    {
        $this->fullname = $otp_fullname;

        return $this;
    }

    public function getTipe(): ?string
    {
        return $this->tipe;
    }

    public function setTipe(string $otp_tipe): self
    {
        $this->tipe = $otp_tipe;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $otp_email): self
    {
        $this->email = $otp_email;

        return $this;
    }

    public function setInserted(DateTimeInterface $otp_inserted): self
    {
        $this->inserted = $otp_inserted;

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
    public function setOrganization($organization)
    {
        $this->organization = $organization;
        return $this;
    }

}
