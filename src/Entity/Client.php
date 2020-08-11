<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClientRepository;
use DateTime;
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
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clicommname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cli_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cli_logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cli_email;

    /**
     * @ORM\Column(type="integer")
     */
    private $cli_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cli_inserted;

    /**
     * @ManyToOne(targetEntity="Organization")
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
     * @JoinColumn(name="worker_firm_wfi_id", referencedColumnName="wfi_id")
     */
    protected $workerFirm;

    /**
     * @OneToMany(targetEntity="ExternalUser", mappedBy="client", cascade={"persist","remove"}, orphanRemoval=true)
     * @var ArrayCollection|ExternalUser[]
     */
    private $externalUsers;

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
        int $id = null,
        $cli_createdBy = null,
        $cli_type = 'F',
        $clicommname = null,
        $cli_logo = null,
        $cli_email = null,
        $cli_inserted,
        $organization,
        Organization $clientOrganization = null,
        $workerFirm,
        $externalUsers = null)
    {
        parent::__construct($id, $cli_createdBy, new DateTime());
        $this->id = $id;
        $this->clicommname = $clicommname;
        $this->cli_type = $cli_type;
        $this->cli_logo = $cli_logo;
        $this->cli_email = $cli_email;
        $this->cli_createdBy = $cli_createdBy;
        $this->cli_inserted = $cli_inserted;
        $this->organization = $organization;
        $this->clientOrganization = $clientOrganization;
        $this->workerFirm = $workerFirm;
        $this->externalUsers = $externalUsers?$externalUsers:new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getCliType(): ?string
    {
        return $this->cli_type;
    }

    public function setCliType(string $cli_type): self
    {
        $this->cli_type = $cli_type;

        return $this;
    }

    public function getCliLogo(): ?string
    {
        return $this->cli_logo;
    }

    public function setCliLogo(string $cli_logo): self
    {
        $this->cli_logo = $cli_logo;

        return $this;
    }

    public function getCliEmail(): ?string
    {
        return $this->cli_email;
    }

    public function setCliEmail(?string $cli_email): self
    {
        $this->cli_email = $cli_email;

        return $this;
    }

    public function getCliCreatedBy(): ?int
    {
        return $this->cli_createdBy;
    }

    public function setCliCreatedBy(int $cli_createdBy): self
    {
        $this->cli_createdBy = $cli_createdBy;

        return $this;
    }

    public function getCliInserted(): ?\DateTimeInterface
    {
        return $this->cli_inserted;
    }

    public function setCliInserted(\DateTimeInterface $cli_inserted): self
    {
        $this->cli_inserted = $cli_inserted;

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

    public function addExternalUser(ExternalUser $externalUser)
    {
        $this->externalUsers->add($externalUser);
        $externalUser->setClient($this);
        return $this;
    }

    public function removeExternalUser(ExternalUser $externalUser)
    {
        $this->externalUsers->removeElement($externalUser);
        return $this;
    }

    public function getAliveExternalUsers()
    {
        $aliveExtUsers = new ArrayCollection;
        foreach ($this->externalUsers as $externalUser) {
            if ($externalUser->getDeleted() == null && $externalUser->getLastname() != 'ZZ') {
                $aliveExtUsers->add($externalUser);
            }
        };
        return $aliveExtUsers;
    }

    public function addAliveExternalUser(ExternalUser $externalUser)
    {
        $this->externalUsers->add($externalUser);
        $externalUser->setClient($this);
        return $this;
    }

    public function removeAliveExternalUser(ExternalUser $externalUser)
    {
        $this->externalUsers->removeElement($externalUser);
        return $this;
    }
}
