<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TeamRepository;
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
class Team
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="tea_id", type="integer", nullable=false, length=10)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tea_name;

    /**
     * @ORM\Column(type="float")
     */
    private $tea_weight_ini;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $tea_picture;

    /**
     * @ORM\Column(type="integer")
     */
    private $tea_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tea_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tea_deleted;

    /**
     *@ManyToOne(targetEntity="Organization")
     *@JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    /**
     * @OneToMany(targetEntity="TeamUser", mappedBy="team", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $teamUsers;

    /**
     * @OneToMany(targetEntity="ActivityUser", mappedBy="team", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $participations;

    /**
     * @OneToMany(targetEntity="Grade", mappedBy="team", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $grades;

    /**
     * @OneToMany(targetEntity="Target", mappedBy="team",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $targets;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeaName(): ?string
    {
        return $this->tea_name;
    }

    public function setTeaName(string $tea_name): self
    {
        $this->tea_name = $tea_name;

        return $this;
    }

    public function getTeaWeightIni(): ?float
    {
        return $this->tea_weight_ini;
    }

    public function setTeaWeightIni(float $tea_weight_ini): self
    {
        $this->tea_weight_ini = $tea_weight_ini;

        return $this;
    }

    public function getTeaPicture(): ?string
    {
        return $this->tea_picture;
    }

    public function setTeaPicture(?string $tea_picture): self
    {
        $this->tea_picture = $tea_picture;

        return $this;
    }

    public function getTeaCreatedBy(): ?int
    {
        return $this->tea_createdBy;
    }

    public function setTeaCreatedBy(int $tea_createdBy): self
    {
        $this->tea_createdBy = $tea_createdBy;

        return $this;
    }

    public function getTeaInserted(): ?\DateTimeInterface
    {
        return $this->tea_inserted;
    }

    public function setTeaInserted(\DateTimeInterface $tea_inserted): self
    {
        $this->tea_inserted = $tea_inserted;

        return $this;
    }

    public function getTeaDeleted(): ?\DateTimeInterface
    {
        return $this->tea_deleted;
    }

    public function setTeaDeleted(\DateTimeInterface $tea_deleted): self
    {
        $this->tea_deleted = $tea_deleted;

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

    /**
     * @return mixed
     */
    public function getTeamUsers()
    {
        return $this->teamUsers;
    }

    /**
     * @param mixed $teamUsers
     */
    public function setTeamUsers($teamUsers): void
    {
        $this->teamUsers = $teamUsers;
    }

    /**
     * @return mixed
     */
    public function getParticipations()
    {
        return $this->participations;
    }

    /**
     * @param mixed $participations
     */
    public function setParticipations($participations): void
    {
        $this->participations = $participations;
    }

    /**
     * @return mixed
     */
    public function getGrades()
    {
        return $this->grades;
    }

    /**
     * @param mixed $grades
     */
    public function setGrades($grades): void
    {
        $this->grades = $grades;
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

}
