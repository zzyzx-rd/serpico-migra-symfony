<?php


namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\OrganizationPaymentMethodRepository;
use Stripe;
/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=OrganizationPaymentMethodRepository::class)
 */
class OrganizationPaymentMethod extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="opm_id", type="integer", nullable=false, length=10)
     * @var int
     */
    public ?int $id;

    /**
     * @ORM\Column(name="opm_pmid",type="string", length=200)
     */
    public $pmId;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="organizationPaymentMethodInitiatives")
     * @JoinColumn(name="opm_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\Column(name="opm_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public \DateTime $inserted;
    /**
     * @ORM\ManyToOne(targetEntity=Organization::class, inversedBy="paymentMethods")
     * @ORM\JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=true, onDelete="CASCADE")
     */
    public $organization;
    /**
     * Ranking constructor.
     * @param ?int$id
     * @param $pmId
     * @param $organization
     */
    public function __construct(
        ?int $id = 0,
        $pmId= null
       )
    {
        parent::__construct($id, null, new DateTime());

    }

    /**
     * @return mixed
     */
    public function getPmId()
    {
        return $this->pmId;
    }

    /**
     * @param mixed $pmId
     */
    public function setPmId($pmId): void
    {
        $this->pmId = $pmId;
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
    public function getLast4(): String
    {
        $payment_method = Stripe\PaymentMethod::retrieve(
            $this->pmId
        );
        return $payment_method->card->last4;
    }
    public function getName(): String
    {
        $payment_method = Stripe\PaymentMethod::retrieve(
            $this->pmId
        );
        return $payment_method->billing_details->name;
    }
    public function getBrand(): String
    {
        $payment_method = Stripe\PaymentMethod::retrieve(
            $this->pmId
        );
        return   $payment_method->card->brand;
    }
    public function getDateend(): String
    {
        $payment_method = Stripe\PaymentMethod::retrieve(
            $this->pmId
        );
        $month = $payment_method->card->exp_month;
        if($month < 10){
            $montheend = "0". (string)$month;
        }
        else {
            $montheend = $month;
        }
        $dateend = (string) $payment_method->card->exp_year;
        $dateend = $montheend . "/" . $dateend[2] . $dateend[3];
        return $dateend;
    }

}
