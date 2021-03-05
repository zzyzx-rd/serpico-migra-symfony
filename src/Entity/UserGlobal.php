<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserGlobalRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Asset\Context\NullContext;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=UserGlobalRepository::class)
 */
class UserGlobal extends DbObject
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="usg_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="usg_phone_number", type="string", length=20, nullable=true)
     */
    public $phoneNumber;
    
    /**
     * @ORM\Column(name="usg_username", type="string", length=255, nullable=true)
     */
    public $username;

    /**
     * @ORM\Column(name="usg_email", type="string", length=255, nullable=true)
     */
    public $email;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="userGlobalInitiatives")
     * @JoinColumn(name="usg_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="userGlobal", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"lastConnected" = "DESC"})
     * @var ArrayCollection|User[]
     */
    private $userAccounts;

    /**
     * UserGlobal constructor.
     * @param $id
     * @param $username
     * @param $email
     * @param $phoneNumber
     */
    public function __construct(
        $id = null,
        $username = null,
        $email = null,
        $phoneNumber = null)
    {
        parent::__construct($id, null, new DateTime);
        $this->username = $username;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->userAccounts = new ArrayCollection;
    }
    
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
    * @return ArrayCollection|User[]
    */
    public function getUserAccounts()
    {
        return $this->userAccounts;
    }

    public function addUserAccount(User $userAccount): self
    {
        $this->userAccounts->add($userAccount);
        $userAccount->setUserGlobal($this);
        return $this;
    }

    public function removeUserAccount(User $userAccount): self
    {
        $this->userAccounts->removeElement($userAccount);
        return $this;
    }

}
