<?php

namespace App\Entity;

use App\Repository\MailRepository;
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
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    public $mail_persona;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $mail_token;

    /**
     * @ORM\Column(type="datetime")
     */
    public $mail_read;

    /**
     * @ORM\Column(type="integer")
     */
    public $mail_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $mail_inserted;

    /**
     * @Column(name="mail_type", length= 255, type="string")
     * @var string
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=3)
     */
    public $mail_language;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_usr_id", referencedColumnName="usr_id", nullable=false)
     */
    protected $user;
    /**
     * @ManyToOne(targetEntity="WorkerIndividual")
     * @JoinColumn(name="worker_individual_win_id", referencedColumnName="win_id", nullable=false)
     */
    protected $workerIndividual;
    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=false)
     */
    protected $organization;
    /**
     * @ManyToOne(targetEntity="WorkerFirm")
     * @JoinColumn(name="worker_firm_wfi_id", referencedColumnName="wfi_id", nullable=false)
     */
    protected $workerFirm;
    /**
     * @ManyToOne(targetEntity="Activity")
     * @JoinColumn(name="activity_act_id", referencedColumnName="act_id", nullable=false)
     */
    protected $activity;
    /**
     * @ManyToOne(targetEntity="Stage")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id", nullable=false)
     */
    protected $stage;

    /**
     * Mail constructor.
     * @param int $id
     * @param $type
     * @param $mail_persona
     * @param $mail_token
     * @param $mail_read
     * @param $mail_createdBy
     * @param $mail_inserted
     * @param $mail_language
     * @param $user
     * @param $workerIndividual
     * @param $organization
     * @param $workerFirm
     * @param $activity
     * @param $stage
     */
    public function __construct(
        int $id,
        $type = null,
        $mail_persona = null,
        $mail_token = null,
        $mail_read = null,
        $mail_createdBy = null,
        $mail_inserted = null,
        $mail_language = null,
        User $user = null,
        WorkerIndividual $workerIndividual = null,
        Organization $organization = null,
        WorkerFirm $workerFirm = null,
        Activity $activity = null,
        Stage $stage = null)
    {
        parent::__construct($id, $lk_url_createdBy, new DateTime());

        $this->type = $type;
        $this->mail_persona = $mail_persona;
        $this->mail_token = $mail_token;
        $this->mail_read = $mail_read;
        $this->mail_inserted = $mail_inserted;
        $this->mail_language = $mail_language;
        $this->user = $user;
        $this->workerIndividual = $workerIndividual;
        $this->organization = $organization;
        $this->workerFirm = $workerFirm;
        $this->activity = $activity;
        $this->stage = $stage;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMailPersona(): ?string
    {
        return $this->mail_persona;
    }

    public function setMailPersona(string $mail_persona): self
    {
        $this->mail_persona = $mail_persona;

        return $this;
    }

    public function getMailToken(): ?string
    {
        return $this->mail_token;
    }

    public function setMailToken(string $mail_token): self
    {
        $this->mail_token = $mail_token;

        return $this;
    }

    public function getMailRead(): ?\DateTimeInterface
    {
        return $this->mail_read;
    }

    public function setMailRead(\DateTimeInterface $mail_read): self
    {
        $this->mail_read = $mail_read;

        return $this;
    }

    public function getMailInserted(): ?\DateTimeInterface
    {
        return $this->mail_inserted;
    }

    public function setMailInserted(\DateTimeInterface $mail_inserted): self
    {
        $this->mail_inserted = $mail_inserted;

        return $this;
    }

    public function getMailLanguage(): ?string
    {
        return $this->mail_language;
    }

    public function setMailLanguage(string $mail_language): self
    {
        $this->mail_language = $mail_language;

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
    public function setUser($user): void
    {
        $this->user = $user;
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
    public function setOrganization($organization): void
    {
        $this->organization = $organization;
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
    public function setWorkerFirm($workerFirm): void
    {
        $this->workerFirm = $workerFirm;
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

}
