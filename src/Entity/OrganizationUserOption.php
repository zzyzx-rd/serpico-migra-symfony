<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrganizationUserOptionRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
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
    public ?int $id;

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
    public ?int $createdBy;

    /**
     * @ORM\Column(name="opt_inserted", type="datetime", nullable=true)
     */
    public DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="OptionName")
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
     * @Column(name="org_role", nullable=true, type="integer")
     */
    public $role;

    /**
     * OrganizationUserOption constructor.
     * @param int $id
     * @param bool $enabled
     * @param $createdBy
     */
    public function __construct($id = 0, $enabled = true, $createdBy = null)
    {
        parent::__construct($id,$createdBy , new DateTime());
        $this->enabled = $enabled;
        $this->createdBy = $createdBy;
    }


    public function isOptionTrue(): ?bool
    {
        return $this->optionTrue;
    }

    public function setOptionTrue(bool $optionTrue): self
    {
        $this->optionTrue = $optionTrue;
        return $this;
    }

    public function getOptionIValue(): ?float
    {
        return $this->optionIValue;
    }

    public function setOptionIValue(float $optionIValue): self
    {
        $this->optionIValue = $optionIValue;
        return $this;
    }

    public function getOptionSecondaryIValue(): ?float
    {
        return $this->optionSecondaryIValue;
    }

    public function setOptionSecondaryIValue(float $optionSecondaryIValue): self
    {
        $this->optionSecondaryIValue = $optionSecondaryIValue;
        return $this;
    }

    public function getOptionFValue(): ?float
    {
        return $this->optionFValue;
    }

    public function setOptionFValue(float $optionFValue): self
    {
        $this->optionFValue = $optionFValue;
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

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOName(): OptionName
    {
        return $this->oName;
    }

    public function setOName($oName): OrganizationUserOption
    {
        $this->oName = $oName;
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
    public function setOrganization(Organization $organization): self
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
    public function setDepartment($department): OrganizationUserOption
    {
        $this->department = $department;
        return $this;
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
    public function setPosition($position): self
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }


    public function setTitle($title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(?int $role): self
    {
        $this->role = $role;
        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }

}
