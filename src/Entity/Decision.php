<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DecisionRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=DecisionRepository::class)
 */
class Decision extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="dec_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="dec_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="req_anon", type="integer", nullable=true)
     */
    public $anonymousRequest;

    /**
     * @ORM\Column(name= "dec_anon", type="boolean", nullable=true)
     */
    public $anonymousDecision;

    /**
     * @ORM\Column(name="val_usr_id", type="integer", nullable=true)
     */
    public $validator;

    /**
     * @ORM\Column(name="dec_result", type="integer", nullable=true)
     */
    public $result;

    /**
     * @ORM\Column(name="dec_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="dec_inserted", type="datetime", nullable=true)
     */
    public ?DateTime $inserted;

    /**
     * @ORM\Column(name="dec_decided", type="datetime", nullable=true)
     */
    public $decided;

    /**
     * @ORM\Column(name="dec_validated", type="datetime", nullable=true)
     */
    public $validated;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="decisions")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=true)
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Activity", inversedBy="decisions")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id", nullable=true)
     */
    protected $activity;

    /**
     * @ManyToOne(targetEntity="Stage", inversedBy="decisions")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;
    /**
     * @ManyToMany(targetEntity="User")
     * @JoinColumn(name="req_usr_id", referencedColumnName="usr_id")
     * @Column(name="req_usr_id", type="integer", nullable=true)
     * @var int
     */
    protected $requester;

    /**
     * @ManyToMany(targetEntity="User")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id")
     * @Column(name="dec_usr_id", type="integer", nullable=true)
     * @var int
     */
    protected $decider;

    /**
     * Decision constructor.
     * @param $id
     * @param $dec_type
     * @param $req_anon
     * @param $dec_anon
     * @param $validator
     * @param $dec_result
     * @param $dec_createdBy
     * @param $dec_inserted
     * @param $dec_decided
     * @param $dec_validated
     * @param $organization
     * @param $activity
     * @param $stage
     * @param int $requester
     * @param int $decider
     */
    //TODO le requester correctement
    public function __construct(
        $id = 0,
        $dec_type = 0,
        int $requester = 0,
        int $decider = 0,
        $validator = 0,
        $dec_result = null,
        $dec_createdBy = null,
        $dec_inserted = null,
        $dec_decided = null,
        $dec_validated = null,
        Organization $organization = null,
        Activity $activity = null,
        Stage $stage = null,
        $req_anon = null,
        $dec_anon = null
       )
    {
        parent::__construct($id, $dec_createdBy, new DateTime());
        $this->type = $dec_type;
        $this->anonymousRequest = $req_anon;
        $this->anonymousDecision = $dec_anon;
        $this->validator = $validator;
        $this->result = $dec_result;
        $this->inserted = $dec_inserted;
        $this->decided = $dec_decided;
        $this->validated = $dec_validated;
        $this->organization = $organization;
        $this->activity = $activity;
        $this->stage = $stage;
        $this->requester = $requester;
        $this->decider = $decider;
    }


    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $dec_type): self
    {
        $this->type = $dec_type;

        return $this;
    }

    public function getAnonymousRequest(): ?int
    {
        return $this->anonymousRequest;
    }

    public function setAnonymousRequest(int $anonymousRequest): self
    {
        $this->anonymousRequest = $anonymousRequest;

        return $this;
    }

    public function getAnonymousDecision(): ?bool
    {
        return $this->anonymousDecision;
    }

    public function setAnonymousDecision(?bool $dec_anon): self
    {
        $this->anonymousDecision = $dec_anon;

        return $this;
    }

    public function getValidator(): ?int
    {
        return $this->validator;
    }

    public function setValidator(int $validator): self
    {
        $this->validator = $validator;

        return $this;
    }

    public function getResult(): ?int
    {
        return $this->result;
    }

    public function setResult(int $dec_result): self
    {
        $this->result = $dec_result;

        return $this;
    }

    public function setInserted(DateTimeInterface $dec_inserted): self
    {
        $this->inserted = $dec_inserted;

        return $this;
    }

    public function getDecided(): ?DateTimeInterface
    {
        return $this->decided;
    }

    public function setDecided(DateTimeInterface $dec_decided): self
    {
        $this->decided = $dec_decided;

        return $this;
    }

    public function getValidated(): ?DateTimeInterface
    {
        return $this->validated;
    }

    public function setValidated(DateTimeInterface $dec_validated): self
    {
        $this->validated = $dec_validated;

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
     * @return int
     */
    public function getRequester(): int
    {
        return $this->requester;
    }

    /**
     * @param int $requester
     */
    public function setRequester(int $requester): void
    {
        $this->requester = $requester;
    }

    /**
     * @return int
     */
    public function getider(): int
    {
        return $this->decider;
    }

    /**
     * @param int $decider
     */
    public function setider(int $decider): void
    {
        $this->decider = $decider;
    }

}
