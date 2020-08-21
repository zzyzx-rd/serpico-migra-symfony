<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClientRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="cli_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $clicommname;

    /**
     * @ORM\Column(name="cli_type", type="string", length=255, nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="cli_logo", type="string", length=255, nullable=true)
     */
    public $logo;

    /**
     * @ORM\Column(name="cli_email", type="string", length=255, nullable=true)
     */
    public $email;

    /**
     * @ORM\Column(name="cli_createdBy", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="cli_inserted", type="datetime", nullable=true)
     */
    public ?DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="clients")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=false)
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="client_org_id", referencedColumnName="org_id",nullable=false)
     * @var Organization
     */
    protected $clientOrganization;

    /**
     * @OneToOne(targetEntity="WorkerFirm")
     * @JoinColumn(name="worker_firm_wfi_id", referencedColumnName="wfi_id", nullable=true)
     */
    protected $workerFirm;

    /**
     * @OneToMany(targetEntity="ExternalUser", mappedBy="client", cascade={"persist","remove"}, orphanRemoval=true)
     * @var ArrayCollection|ExternalUser[]
     */
    public $externalUsers;

    /**
     * Client constructor.
     * @param $id
     * @param $clicommname
     * @param $cli_type
     * @param $cli_logo
     * @param $cli_email
     * @param $cli_createdBy
     * @param $cli_inserted
     * @param $organization
     * @param Organization $clientOrganization
     * @param $workerFirm
     * @param ExternalUser[]|ArrayCollection $externalUsers
     */
    public function __construct(
      ?int $id = null,
        $cli_createdBy = null,
        $cli_type = 'F',
        $clicommname = null,
        $cli_logo = null,
        $cli_email = null,
        $cli_inserted = null,
        $organization = null,
        Organization $clientOrganization = null,
        $workerFirm = null,
        $externalUsers = null)
    {
        parent::__construct($id, $cli_createdBy, new DateTime());
        $this->clicommname = $clicommname;
        $this->type = $cli_type;
        $this->logo = $cli_logo;
        $this->email = $cli_email;
        $this->inserted = $cli_inserted;
        $this->organization = $organization;
        $this->clientOrganization = $clientOrganization;
        $this->workerFirm = $workerFirm;
        $this->externalUsers = $externalUsers?:new ArrayCollection();
    }


    public function getClicommname(): ?string
    {
        return $this->clicommname;
    }

    public function setClicommname(string $clicommname): self
    {
        $this->clicommname = $clicommname;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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
     * @return Organization
     */
    public function getClientOrganization(): Organization
    {
        return $this->clientOrganization;
    }

    /**
     * @param Organization $clientOrganization
     */
    public function setClientOrganization(Organization $clientOrganization): void
    {
        $this->clientOrganization = $clientOrganization;
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
     * @return ExternalUser[]|ArrayCollection
     */
    public function getExternalUsers()
    {
        return $this->externalUsers;
    }

    /**
     * @param ExternalUser[]|ArrayCollection $externalUsers
     */
    public function setExternalUsers($externalUsers): void
    {
        $this->externalUsers = $externalUsers;
    }

    public function addExternalUser(ExternalUser $externalUser): Client
    {
        $this->externalUsers->add($externalUser);
        $externalUser->setClient($this);
        return $this;
    }

    public function removeExternalUser(ExternalUser $externalUser): Client
    {
        $this->externalUsers->removeElement($externalUser);
        return $this;
    }

    public function getAliveExternalUsers(): ArrayCollection
    {
        $aliveExtUsers = new ArrayCollection;
        foreach ($this->externalUsers as $externalUser) {
            if ($externalUser->getDeleted() == null && $externalUser->getLastname() !== 'ZZ') {
                $aliveExtUsers->add($externalUser);
            }
        };
        return $aliveExtUsers;
    }

    public function addAliveExternalUser(ExternalUser $externalUser): Client
    {
        $this->externalUsers->add($externalUser);
        $externalUser->setClient($this);
        return $this;
    }

    public function removeAliveExternalUser(ExternalUser $externalUser): Client
    {
        $this->externalUsers->removeElement($externalUser);
        return $this;
    }
}
