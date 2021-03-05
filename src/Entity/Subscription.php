<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity(repositoryClass="SubscriptionRepository", repositoryClass=SubscriptionRepository::class)
 */
class Subscription extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="sub_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="sub_sid", type="string", length=255, nullable=true)
     */
    public $stripeId;

    /**
     * @ORM\Column(name="sub_period", type="string", length=255, nullable=true)
     */
    public $period;

    /**
     * @ORM\Column(name="sub_status", type="integer", nullable=false)
     */
    public int $status;

    /**
     * @ORM\Column(name="sub_discounted", type="boolean", nullable=true)
     */
    public $discounted;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="subscriptions")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=true)
     */
    public Organization $organization;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="subscriptionInitiatives")
     * @JoinColumn(name="sub_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="subscription", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $subscriptors;

    /**
     * @ORM\Column(name="sub_inserted", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;
    
    /**
     * @ORM\Column(name="sub_started", type="datetime", nullable=true)
     */
    public $started;

    /**
     * @ORM\Column(name="sub_ended", type="datetime", nullable=true)
     */
    public $ended;

    const STATUS_CANCELLED = -2;
    const STATUS_EXPIRED = -1;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const PERIOD_MONTH = 'M';
    const PERIOD_YEAR = 'Y';

    /**
     * Subscription constructor.
     * @param $id
     * @param $stripeId
     * @param $period
     * @param $status
     * @param $discounted
     * @param $started
     * @param $ended
     */
    public function __construct(
      ?int $id = 0,
        $stripeId = null,
        $period = 'M',
        $status = 1,
        $discounted = false,
        $started = null,
        $ended = null
    )
    {
        parent::__construct($id, null, new DateTime());
        $this->stripeId = $stripeId;
        $this->period = $period;
        $this->status = $status;
        $this->discounted = $discounted;
        $this->started = $started;
        $this->ended = $ended;
        $this->users = new ArrayCollection();
    }


    public function getStripeId(): ?string
    {
        return $this->stripeId;
    }

    public function setStripeId(string $stripeId): self
    {
        $this->stripeId = $stripeId;
        return $this;
    }
    
    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): self
    {
        $this->period = $period;
        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(Organization $organization): self
    {
        $this->organization = $organization;
        return $this;
    }

    public function isDiscounted(): ?bool
    {
        return $this->discounted;
    }

    public function setDiscounted(bool $discounted): self
    {
        $this->discounted = $discounted;
        return $this;
    }

    public function setStarted(?DateTimeInterface $started): self
    {
        $this->started = $started;
        return $this;
    }

    public function getStarted(): ?DateTimeInterface
    {
        return $this->started;
    }

    public function setEnded(?DateTimeInterface $ended): self
    {
        $this->ended = $ended;
        return $this;
    }

    public function getEnded(): ?DateTimeInterface
    {
        return $this->ended;
    }

    /**
     * @return ArrayCollection|User[]
     */
    public function getSubscriptors(){
        return $this->subscriptors;
    }

    public function addSubscriptor(User $subscriptor): self
    {
        $this->subscriptors->add($subscriptor);
        $subscriptor->setSubscription($this);
        return $this;
    }

    public function removeSubscriptor(User $subscriptor): self
    {
        $this->subscriptors->removeElement($subscriptor);
        return $this;
    }


    public function __toString()
    {
        return (string) $this->id;
    }

}
