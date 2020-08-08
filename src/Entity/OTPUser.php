<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OTPUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=OTPUserRepository::class)
 */
class OTPUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOtpType(): ?string
    {
        return $this->otp_type;
    }

    public function setOtpType(string $otp_type): self
    {
        $this->otp_type = $otp_type;

        return $this;
    }

    public function getOtpFullname(): ?string
    {
        return $this->otp_fullname;
    }

    public function setOtpFullname(string $otp_fullname): self
    {
        $this->otp_fullname = $otp_fullname;

        return $this;
    }

    public function getOtpTipe(): ?string
    {
        return $this->otp_tipe;
    }

    public function setOtpTipe(string $otp_tipe): self
    {
        $this->otp_tipe = $otp_tipe;

        return $this;
    }

    public function getOtpEmail(): ?string
    {
        return $this->otp_email;
    }

    public function setOtpEmail(string $otp_email): self
    {
        $this->otp_email = $otp_email;

        return $this;
    }

    public function getOtpCreatedBy(): ?int
    {
        return $this->otp_createdBy;
    }

    public function setOtpCreatedBy(?int $otp_createdBy): self
    {
        $this->otp_createdBy = $otp_createdBy;

        return $this;
    }

    public function getOtpInserted(): ?\DateTimeInterface
    {
        return $this->otp_inserted;
    }

    public function setOtpInserted(\DateTimeInterface $otp_inserted): self
    {
        $this->otp_inserted = $otp_inserted;

        return $this;
    }
}
