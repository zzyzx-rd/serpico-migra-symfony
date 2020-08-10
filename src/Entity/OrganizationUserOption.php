<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrganizationUserOptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=OrganizationUserOptionRepository::class)
 */
class OrganizationUserOption
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="opt_id", type="integer", nullable=false)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $opt_bool_value;

    /**
     * @ORM\Column(type="float")
     */
    private $opt_int_value;

    /**
     * @ORM\Column(type="float")
     */
    private $opt_int_value_2;

    /**
     * @ORM\Column(type="float")
     */
    private $opt_float_value;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pot_string_value;

    /**
     * @ORM\Column(type="boolean")
     */
    private $opt_enabled;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $opt_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $opt_inserted;

    /**
     * @OneToOne(targetEntity="OptionName")
     * @JoinColumn(name="option_name_ona_id", referencedColumnName="ona_id")
     */
    protected $oName;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Department")
     * @JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id")
     */
    protected $department;

    /**
     * @ManyToOne(targetEntity="Position")
     * @JoinColumn(name="position_pos_id", referencedColumnName="pos_id")
     */
    protected $position;

    /**
     * @ManyToOne(targetEntity="Title")
     * @JoinColumn(name="title_tit_id", referencedColumnName="tit_id")
     */
    protected $title;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id")
     */
    protected $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOptBoolValue(): ?bool
    {
        return $this->opt_bool_value;
    }

    public function setOptBoolValue(bool $opt_bool_value): self
    {
        $this->opt_bool_value = $opt_bool_value;

        return $this;
    }

    public function getOptIntValue(): ?float
    {
        return $this->opt_int_value;
    }

    public function setOptIntValue(float $opt_int_value): self
    {
        $this->opt_int_value = $opt_int_value;

        return $this;
    }

    public function getOptIntValue2(): ?float
    {
        return $this->opt_int_value_2;
    }

    public function setOptIntValue2(float $opt_int_value_2): self
    {
        $this->opt_int_value_2 = $opt_int_value_2;

        return $this;
    }

    public function getOptFloatValue(): ?float
    {
        return $this->opt_float_value;
    }

    public function setOptFloatValue(float $opt_float_value): self
    {
        $this->opt_float_value = $opt_float_value;

        return $this;
    }

    public function getPotStringValue(): ?string
    {
        return $this->pot_string_value;
    }

    public function setPotStringValue(string $pot_string_value): self
    {
        $this->pot_string_value = $pot_string_value;

        return $this;
    }

    public function getOptEnabled(): ?bool
    {
        return $this->opt_enabled;
    }

    public function setOptEnabled(bool $opt_enabled): self
    {
        $this->opt_enabled = $opt_enabled;

        return $this;
    }

    public function getOptCreatedBy(): ?int
    {
        return $this->opt_createdBy;
    }

    public function setOptCreatedBy(?int $opt_createdBy): self
    {
        $this->opt_createdBy = $opt_createdBy;

        return $this;
    }

    public function getOptInserted(): ?\DateTimeInterface
    {
        return $this->opt_inserted;
    }

    public function setOptInserted(\DateTimeInterface $opt_inserted): self
    {
        $this->opt_inserted = $opt_inserted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOName()
    {
        return $this->oName;
    }

    /**
     * @param mixed $oName
     */
    public function setOName($oName): void
    {
        $this->oName = $oName;
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
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $department
     */
    public function setDepartment($department): void
    {
        $this->department = $department;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position): void
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

}
