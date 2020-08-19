<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrganizationUserOptionRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=OrganizationUserOptionRepository::class)
 */
class OrganizationUserOption extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="opt_id", type="integer", nullable=false)
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(name="opt_bool_value", type="boolean", nullable=true)
     */
    public $optionTrue;

    /**
     * @ORM\Column(name="opt_int_value", type="float", nullable=true)
     */
    public $optionIValue;

    /**
     * @ORM\Column(name="opt_int_value_2", type="float", nullable=true)
     */
    public $optionSecondaryIValue;

    /**
     * @ORM\Column(name="opt_float_value", type="float", nullable=true)
     */
    public $optionFValue;

    /**
     * @ORM\Column(name="opt_string_value", type="string", length=255, nullable=true)
     */
    public $optionSValue;

    /**
     * @ORM\Column(name="opt_enabled", type="boolean", nullable=true)
     */
    public $enabled;

    /**
     * @ORM\Column(name="opt_created_by", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="opt_inserted", type="datetime", nullable=true)
     */
    public $inserted;

    /**
     * @OneToOne(targetEntity="OptionName")
     * @JoinColumn(name="option_name_ona_id", referencedColumnName="ona_id")
     */
    protected $oName;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="options")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Department", inversedBy="options")
     * @JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id")
     */
    protected $department;

    /**
     * @ManyToOne(targetEntity="Position", inversedBy="options")
     * @JoinColumn(name="position_pos_id", referencedColumnName="pos_id")
     */
    protected $position;

    /**
     * @ManyToOne(targetEntity="Title", inversedBy="options")
     * @JoinColumn(name="title_tit_id", referencedColumnName="tit_id")
     */
    protected $title;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="options")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class)
     * @ORM\JoinColumn(name="role_rol_id", referencedColumnName="rol_id", nullable=false)
     */
    public $role;

    /**
     * OrganizationUserOption constructor.
     * @param int $id
     * @param bool $opt_enabled
     * @param $opt_createdBy
     */
    public function __construct($id = 0, $opt_enabled = true, $opt_createdBy = null)
    {
        parent::__construct($id,$opt_createdBy , new DateTime());
        $this->enabled = $opt_enabled;
        $this->createdBy = $opt_createdBy;
    }


    public function isOptionTrue(): ?bool
    {
        return $this->optionTrue;
    }

    public function setOptionValue(bool $opt_bool_value): self
    {
        $this->optionTrue = $opt_bool_value;

        return $this;
    }

    public function getOptionIValue(): ?float
    {
        return $this->optionIValue;
    }

    public function setIntValue(float $opt_int_value): self
    {
        $this->optionIValue = $opt_int_value;

        return $this;
    }

    public function getOptionSecondaryIValue(): ?float
    {
        return $this->optionSecondaryIValue;
    }

    public function setIntValue2(float $opt_int_value_2): self
    {
        $this->optionSecondaryIValue = $opt_int_value_2;

        return $this;
    }

    public function getFloatValue(): ?float
    {
        return $this->optionFValue;
    }

    public function setFloatValue(float $opt_float_value): self
    {
        $this->optionFValue = $opt_float_value;

        return $this;
    }

    public function getOptionSValue(): ?string
    {
        return $this->optionSValue;
    }

    public function setOptionSValue(string $optionSValue): self
    {
        $this->optionSValue = $optionSValue;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $opt_enabled): self
    {
        $this->enabled = $opt_enabled;

        return $this;
    }

    public function setInserted(DateTimeInterface $opt_inserted): self
    {
        $this->inserted = $opt_inserted;

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
    public function setOrganization($organization): OrganizationUserOption
    {
        $this->organization = $organization;
        return $this;
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

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }

}
