<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RankingRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=RankingRepository::class)
 */
class Ranking extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="rnk_id", type="integer", nullable=false, length=10)
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(name="rnk_dtype", type="string", length=1)
     */
    public $dType;

    /**
     * @ORM\Column(name="rnk_wtype", type="string", length=1)
     */
    public $wType;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $rnk_abs_result;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $rnk_rel_result;

    /**
     * @ORM\Column(name="rnk_period", type="integer", nullable=true)
     */
    public $period;

    /**
     * @ORM\Column(name="rnk_freq", type="integer", nullable=true)
     */
    public $frequency;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $rnk_value;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $rnk_series_pop;

    /**
     * @ORM\Column(name="rnk_created_by", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="rnk_inserted", type="datetime", nullable=true)
     */
    public $inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $rnk_updated;

    /**
     *@ManyToOne(targetEntity="Activity", inversedBy="rankings")
     *@JoinColumn(name="rnk_activity", referencedColumnName="act_id", nullable=false)
     */
    protected $activity;

    /**
     *@ManyToOne(targetEntity="Stage", inversedBy="rankings")
     *@JoinColumn(name="rnk_stage", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;

    /**
     *@ManyToOne(targetEntity="Criterion", inversedBy="rankings")
     *@JoinColumn(name="rnk_criterion", referencedColumnName="crt_id", nullable=true)
     */
    protected $criterion;

    /**
     *@ManyToOne(targetEntity="Organization")
     *@JoinColumn(name="rnk_organization", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @JoinColumn(name="rnk_user_usr_id", referencedColumnName="usr_id")
     */
    public $user_usr;

    /**
     * Ranking constructor.
     * @param int $id
     * @param $rnk_dtype
     * @param $rnk_wtype
     * @param $rnk_abs_result
     * @param $rnk_rel_result
     * @param $rnk_period
     * @param $rnk_freq
     * @param $rnk_value
     * @param $rnk_series_pop
     * @param $rnk_createdBy
     * @param $rnk_inserted
     * @param $rnk_updated
     * @param $activity
     * @param $stage
     * @param $criterion
     * @param $organization
     * @param $rnk_user_usr
     */
    public function __construct(
        int $id = 0,
        $rnk_dtype = null,
        $rnk_wtype = null,
        $rnk_user_usr = null,
        $organization = null,
        $rnk_abs_result = null,
        $rnk_rel_result = null,
        $rnk_period = null,
        $rnk_freq = null,
        $rnk_value = null,
        $rnk_series_pop = null,
        $rnk_createdBy = null,
        $rnk_inserted = null,
        $rnk_updated = null,
        $activity = null,
        $stage = null,
        $criterion = null)
    {
        parent::__construct($id, $rnk_createdBy, new DateTime());
        $this->dType = $rnk_dtype;
        $this->wType = $rnk_wtype;
        $this->rnk_abs_result = $rnk_abs_result;
        $this->rnk_rel_result = $rnk_rel_result;
        $this->period = $rnk_period;
        $this->frequency = $rnk_freq;
        $this->rnk_value = $rnk_value;
        $this->rnk_series_pop = $rnk_series_pop;
        $this->inserted = $rnk_inserted;
        $this->rnk_updated = $rnk_updated;
        $this->activity = $activity;
        $this->stage = $stage;
        $this->criterion = $criterion;
        $this->organization = $organization;
        $this->user_usr = $rnk_user_usr;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDType(): ?string
    {
        return $this->dType;
    }

    public function setDType(string $dType): self
    {
        $this->dType = $dType;

        return $this;
    }

    public function getWType(): ?string
    {
        return $this->wType;
    }

    public function setWType(string $wType): self
    {
        $this->wType = $wType;

        return $this;
    }

    public function getRnkAbsResult(): ?int
    {
        return $this->rnk_abs_result;
    }

    public function setRnkAbsResult(int $rnk_abs_result): self
    {
        $this->rnk_abs_result = $rnk_abs_result;

        return $this;
    }

    public function getRnkRelResult(): ?float
    {
        return $this->rnk_rel_result;
    }

    public function setRnkRelResult(float $rnk_rel_result): self
    {
        $this->rnk_rel_result = $rnk_rel_result;

        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(int $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getFrequency(): ?int
    {
        return $this->frequency;
    }

    public function setFrequency(int $frequency): self
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getRnkValue(): ?float
    {
        return $this->rnk_value;
    }

    public function setRnkValue(float $rnk_value): self
    {
        $this->rnk_value = $rnk_value;

        return $this;
    }

    public function getRnkSeriesPop(): ?int
    {
        return $this->rnk_series_pop;
    }

    public function setRnkSeriesPop(int $rnk_series_pop): self
    {
        $this->rnk_series_pop = $rnk_series_pop;

        return $this;
    }


    public function getInserted(): ?\DateTimeInterface
    {
        return $this->inserted;
    }

    public function setInserted(\DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

        return $this;
    }

    public function getRnkUpdated(): ?\DateTimeInterface
    {
        return $this->rnk_updated;
    }

    public function setRnkUpdated(\DateTimeInterface $rnk_updated): self
    {
        $this->rnk_updated = $rnk_updated;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param mixed $activity
     */
    public function setActivity($activity): void
    {
        $this->activity = $activity;
    }

    /**
     * @return mixed
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * @param mixed $stage
     */
    public function setStage($stage): void
    {
        $this->stage = $stage;
    }

    /**
     * @return mixed
     */
    public function getCriterion()
    {
        return $this->criterion;
    }

    /**
     * @param mixed $criterion
     */
    public function setCriterion($criterion): void
    {
        $this->criterion = $criterion;
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

    public function getUserUsr(): ?User
    {
        return $this->user_usr;
    }

    public function setUserUsr(?User $user_usr): self
    {
        $this->user_usr = $user_usr;

        return $this;
    }

}
