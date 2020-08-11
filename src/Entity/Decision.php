<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DecisionRepository;
use DateTime;
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
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $dec_type;

    /**
     * @ORM\Column(name="req_anon", type="integer")
     */
    private $anonymousRequest;

    /**
     * @ORM\Column(name= "dec_anon", type="boolean", nullable=true)
     */
    private $anonymousDecision;

    /**
     * @ORM\Column(name="val_usr_id", type="integer")
     */
    private $validator;

    /**
     * @ORM\Column(type="integer")
     */
    private $dec_result;

    /**
     * @ORM\Column(type="integer")
     */
    private $dec_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dec_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dec_decided;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dec_validated;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id")
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Activity")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id")
     */
    protected $activity;

    /**
     * @ManyToOne(targetEntity="Stage")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id")
     */
    protected $stage;
    /**
     * @ManyToMany(targetEntity="User")
     * @JoinColumn(name="req_usr_id", referencedColumnName="usr_id")
     * @Column(name="req_usr_id", type="integer")
     * @var int
     */
    protected $requester;

    /**
     * @ManyToMany(targetEntity="User")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id")
     * @Column(name="dec_usr_id", type="integer")
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
        $req_anon,
        $dec_anon
       )
    {
        parent::__construct($id, $dec_createdBy, new DateTime());
        $this->dec_type = $dec_type;
        $this->anonymousRequest = $req_anon;
        $this->anonymousDecision = $dec_anon;
        $this->validator = $validator;
        $this->dec_result = $dec_result;
        $this->dec_inserted = $dec_inserted;
        $this->dec_decided = $dec_decided;
        $this->dec_validated = $dec_validated;
        $this->organization = $organization;
        $this->activity = $activity;
        $this->stage = $stage;
        $this->requester = $requester;
        $this->decider = $decider;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->dec_type;
    }

    public function setType(int $dec_type): self
    {
        $this->dec_type = $dec_type;

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
        return $this->dec_result;
    }

    public function setResult(int $dec_result): self
    {
        $this->dec_result = $dec_result;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->dec_createdBy;
    }

    public function setCreatedBy(int $dec_createdBy): self
    {
        $this->dec_createdBy = $dec_createdBy;

        return $this;
    }

    public function getInserted(): ?\DateTimeInterface
    {
        return $this->dec_inserted;
    }

    public function setInserted(\DateTimeInterface $dec_inserted): self
    {
        $this->dec_inserted = $dec_inserted;

        return $this;
    }

    public function getDecided(): ?\DateTimeInterface
    {
        return $this->dec_decided;
    }

    public function setDecided(\DateTimeInterface $dec_decided): self
    {
        $this->dec_decided = $dec_decided;

        return $this;
    }

    public function getValidated(): ?\DateTimeInterface
    {
        return $this->dec_validated;
    }

    public function setValidated(\DateTimeInterface $dec_validated): self
    {
        $this->dec_validated = $dec_validated;

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
