<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrganizationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=OrganizationRepository::class)
 */
class Organization
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $org_legalname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $org_commname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $org_type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $org_isClient;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $org_oth_language;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $org_weight_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $org_logo;

    /**
     * @ORM\Column(type="integer")
     */
    private $org_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $org_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $org_validated;

    /**
     * @ORM\Column(type="datetime")
     */
    private $org_expired;

    /**
     * @ORM\Column(type="boolean")
     */
    private $org_testing_reminder_sent;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $org_deleted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $org_routine_pstatus;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $org_routine_greminders;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrgLegalname(): ?string
    {
        return $this->org_legalname;
    }

    public function setOrgLegalname(string $org_legalname): self
    {
        $this->org_legalname = $org_legalname;

        return $this;
    }

    public function getOrgCommname(): ?string
    {
        return $this->org_commname;
    }

    public function setOrgCommname(string $org_commname): self
    {
        $this->org_commname = $org_commname;

        return $this;
    }

    public function getOrgType(): ?string
    {
        return $this->org_type;
    }

    public function setOrgType(string $org_type): self
    {
        $this->org_type = $org_type;

        return $this;
    }

    public function getOrgIsClient(): ?bool
    {
        return $this->org_isClient;
    }

    public function setOrgIsClient(bool $org_isClient): self
    {
        $this->org_isClient = $org_isClient;

        return $this;
    }

    public function getOrgOthLanguage(): ?string
    {
        return $this->org_oth_language;
    }

    public function setOrgOthLanguage(string $org_oth_language): self
    {
        $this->org_oth_language = $org_oth_language;

        return $this;
    }

    public function getOrgWeightType(): ?string
    {
        return $this->org_weight_type;
    }

    public function setOrgWeightType(string $org_weight_type): self
    {
        $this->org_weight_type = $org_weight_type;

        return $this;
    }

    public function getOrgLogo(): ?string
    {
        return $this->org_logo;
    }

    public function setOrgLogo(?string $org_logo): self
    {
        $this->org_logo = $org_logo;

        return $this;
    }

    public function getOrgCreatedBy(): ?int
    {
        return $this->org_createdBy;
    }

    public function setOrgCreatedBy(int $org_createdBy): self
    {
        $this->org_createdBy = $org_createdBy;

        return $this;
    }

    public function getOrgInserted(): ?\DateTimeInterface
    {
        return $this->org_inserted;
    }

    public function setOrgInserted(\DateTimeInterface $org_inserted): self
    {
        $this->org_inserted = $org_inserted;

        return $this;
    }

    public function getOrgValidated(): ?\DateTimeInterface
    {
        return $this->org_validated;
    }

    public function setOrgValidated(\DateTimeInterface $org_validated): self
    {
        $this->org_validated = $org_validated;

        return $this;
    }

    public function getOrgExpired(): ?\DateTimeInterface
    {
        return $this->org_expired;
    }

    public function setOrgExpired(\DateTimeInterface $org_expired): self
    {
        $this->org_expired = $org_expired;

        return $this;
    }

    public function getOrgTestingReminderSent(): ?bool
    {
        return $this->org_testing_reminder_sent;
    }

    public function setOrgTestingReminderSent(bool $org_testing_reminder_sent): self
    {
        $this->org_testing_reminder_sent = $org_testing_reminder_sent;

        return $this;
    }

    public function getOrgDeleted(): ?\DateTimeInterface
    {
        return $this->org_deleted;
    }

    public function setOrgDeleted(?\DateTimeInterface $org_deleted): self
    {
        $this->org_deleted = $org_deleted;

        return $this;
    }

    public function getOrgRoutinePstatus(): ?\DateTimeInterface
    {
        return $this->org_routine_pstatus;
    }

    public function setOrgRoutinePstatus(?\DateTimeInterface $org_routine_pstatus): self
    {
        $this->org_routine_pstatus = $org_routine_pstatus;

        return $this;
    }

    public function getOrgRoutineGreminders(): ?\DateTimeInterface
    {
        return $this->org_routine_greminders;
    }

    public function setOrgRoutineGreminders(?\DateTimeInterface $org_routine_greminders): self
    {
        $this->org_routine_greminders = $org_routine_greminders;

        return $this;
    }
}
