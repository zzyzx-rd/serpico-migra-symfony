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
use Doctrine\ORM\Mapping\OrderBy;

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
     * @ORM\Column(name="cli_name", type="string", length=255, nullable=true)
     */
    public $name;

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
     * @ORM\Column(name="cli_inserted", type="datetime", nullable=true, options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="clients")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=false)
     */
    protected $organization;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="client_org_id", referencedColumnName="org_id",nullable=false)
     */
    protected $clientOrganization;

    /**
     * @ManyToOne(targetEntity="WorkerFirm", inversedBy="clients")
     * @JoinColumn(name="worker_firm_wfi_id", referencedColumnName="wfi_id", nullable=true)
     */
    protected ?WorkerFirm $workerFirm;

    /**
     * @OneToMany(targetEntity="ExternalUser", mappedBy="client", cascade={"persist","remove"}, orphanRemoval=true)
     * @OrderBy({"synthetic" = "DESC", "lastname" = "ASC"})
     * 
     * @var ArrayCollection|ExternalUser[]
     */
    public $externalUsers;

    /**
     * Client constructor.
     * @param $id
     * @param $name
     * @param $type
     * @param $logo
     * @param $email
     * @param $createdBy
     * @param $organization
     * @param $clientOrganization
     * @param $workerFirm
     * @param $externalUsers
     */
    public function __construct(
      ?int $id = null,
        $createdBy = null,
        $type = 'F',
        $name = null,
        $logo = null,
        $email = null,
        $workerFirm = null,
        $externalUsers = null)
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->name = $name;
        $this->type = $type;
        $this->logo = $logo;
        $this->email = $email;
        $this->workerFirm = $workerFirm;
        $this->externalUsers = $externalUsers?:new ArrayCollection();
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
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

    public function setInserted(?DateTimeInterface $inserted): self
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
    public function setOrganization(Organization $organization): self
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return Organization
     */
    public function getClientOrganization(): ?Organization
    {
        return $this->clientOrganization;
    }

    /**
     * @param Organization $clientOrganization
     */
    public function setClientOrganization(Organization $clientOrganization): self
    {
        $this->clientOrganization = $clientOrganization;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWorkerFirm(): ?WorkerFirm
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
     * @return ArrayCollection|ExternalUser[]
     */
    public function getExternalUsers()
    {
        return $this->externalUsers;
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

    /**
     * @return ArrayCollection|ExternalUser[]
     */
    public function getAliveExternalUsers(): ArrayCollection
    {
        return $this->getExternalUsers()->filter(fn(ExternalUser $eu) => !$eu->getDeleted() && !($eu->isSynthetic() && $eu->getUser()->getFirstname() == 'ZZ'));
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

    public function isVirtual()
    {
        if (!$this->externalUsers){
            return true;
        } else {
            $usersHavingEmailAddresses = $this->externalUsers->filter(function(ExternalUser $eu){
                return $eu->getEmail() != null;
            });
            if($usersHavingEmailAddresses->count() == 0){
                return true;
            } else {
                return !$usersHavingEmailAddresses->exists(function(int $i, ExternalUser $eu){
                    return $eu->isOwner();
                });
            }
        }
    }
}
