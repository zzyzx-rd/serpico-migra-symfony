<?php

namespace App\Entity;

use App\Repository\MailRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity(repositoryClass=MailRepository::class)
 */
class Mail extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="mail_id", type="integer", length=10, nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="mail_persona", type="string", length=1,  nullable=true)
     */
    public $persona;

    /**
     * @ORM\Column(name="mail_token", type="string", length=255, nullable=true)
     */
    public $token;

    /**
     * @ORM\Column(name="mail_read", type="datetime", nullable=true)
     */
    public $read;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="mailInitiatives")
     * @JoinColumn(name="mail_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\Column(name="mail_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @Column(name="mail_type", length= 255, type="string", nullable=true)
     */
    protected $type;

    /**
     * @ORM\Column(name="mail_language", type="string", length=3)
     */
    public $language;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="mails")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id", nullable=true)
     */
    protected $user;
    /**
     * @ManyToOne(targetEntity="WorkerIndividual", inversedBy="mails")
     * @JoinColumn(name="worker_individual_win_id", referencedColumnName="win_id", nullable=true)
     */
    protected $workerIndividual;
    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="mails")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=true)
     */
    protected $organization;
    /**
     * @ManyToOne(targetEntity="WorkerFirm", inversedBy="mails")
     * @JoinColumn(name="worker_firm_wfi_id", referencedColumnName="wfi_id", nullable=true)
     */
    protected $workerFirm;
    /**
     * @ManyToOne(targetEntity="Activity", inversedBy="mails")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id", nullable=true)
     */
    protected $activity;
    /**
     * @ManyToOne(targetEntity="Stage", inversedBy="mails")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;

    /**
     * Mail constructor.
     * @param ?int$id
     * @param $type
     * @param $persona
     * @param $token
     * @param $read
     * @param $language
     * @param User $user
     * @param WorkerIndividual $workerIndividual
     * @param Organization $organization
     * @param WorkerFirm $workerFirm
     * @param Activity $activity
     * @param Stage $stage
     */
    public function __construct(
        ?int $id = null,
        $type = null,
        $persona = null,
        $token = null,
        $read = null,
        $language = null,
        User $user = null,
        WorkerIndividual $workerIndividual = null,
        Organization $organization = null,
        WorkerFirm $workerFirm = null,
        Activity $activity = null,
        Stage $stage = null)
    {
        parent::__construct($id, null, new DateTime());
        $this->type = $type;
        $this->persona = $persona;
        $this->token = $token;
        $this->read = $read;
        $this->language = $language;
        $this->user = $user;
        $this->workerIndividual = $workerIndividual;
        $this->organization = $organization;
        $this->workerFirm = $workerFirm;
        $this->activity = $activity;
        $this->stage = $stage;
    }


    public function getPersona(): ?string
    {
        return $this->persona;
    }

    public function setPersona(string $persona): self
    {
        $this->persona = $persona;
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getRead(): ?DateTimeInterface
    {
        return $this->read;
    }

    public function setRead(DateTimeInterface $read): self
    {
        $this->read = $read;

        return $this;
    }

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
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
    public function setUser($user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWorkerIndividual()
    {
        return $this->workerIndividual;
    }

    /**
     * @param mixed $workerIndividual
     */
    public function setWorkerIndividual($workerIndividual): void
    {
        $this->workerIndividual = $workerIndividual;
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
    public function setOrganization($organization): self
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWorkerFirm()
    {
        return $this->workerFirm;
    }

    /**
     * @param mixed $workerFirm
     */
    public function setWorkerFirm(?WorkerFirm $workerFirm): self
    {
        $this->workerFirm = $workerFirm;
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
    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;
        return $this;
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
    public function setStage(?Stage $stage): self
    {
        $this->stage = $stage;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

}
