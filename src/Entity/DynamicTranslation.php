<?php

namespace App\Entity;

use App\Repository\DynamicTranslationRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Column;


/**
 * @ORM\Entity(repositoryClass=DynamicTranslationRepository::class)
 */
class DynamicTranslation extends DbObject
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="dtr_id", type="integer")
     */
    public ?int $id;

    /**
     * @ORM\Column(name="dtr_entity", type="string", length=255)
     */
    private ?string $entity;

    /**
     * @ORM\Column(name="dtr_entity_id", type="integer")
     */
    private ?int $entityId;
    
    /**
     * @ORM\Column(name="dtr_entity_prop", type="string", length=255)
     */
    private ?string $entityProp;

    /**
     * @ORM\Column(name="dtr_fr", type="string", length=255, nullable=true)
     */
    private ?string $FR;

    /**
     * @ORM\Column(name="dtr_en", type="string", length=255, nullable=true)
     */
    private ?string $EN;

    /**
     * @ORM\Column(name="dtr_de", type="string", length=255, nullable=true)
     */
    private ?string $DE;

    /**
     * @ORM\Column(name="dtr_es", type="string", length=255, nullable=true)
     */
    private ?string $ES;

    /**
     * @ORM\Column(name="dtr_lu", type="string", length=255, nullable=true)
     */
    private ?string $LU;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="dTranslations")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=true)
     */
    protected ?Organization $organization;

    /**
     * @ORM\Column(name="dtr_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @Column(name="dtr_inserted", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     * @var DateTime
     */
    protected DateTime $inserted;

    public function __construct(
        ?int $id = null,
        string $entity = null,
        int $entityId = null,
        int $entityProp = null,
        int $createdBy = null,
        string $FR = null,
        string $EN = null,
        string $ES = null,
        string $LU = null,
        string $DE = null
        )
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->entity = $entity;
        $this->entityId = $entityId;
        $this->entityProp = $entityProp;
        $this->FR = $FR;
        $this->EN = $EN;
        $this->ES = $ES;
        $this->LU = $LU;
        $this->DE = $DE;
    }

    public $locale;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntity(): ?string
    {
        return $this->entity;
    }

    public function setEntity(string $entity): self
    {
        $this->entity = $entity;
        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    public function setEntityId(int $entityId): self
    {
        $this->entityId = $entityId;
        return $this;
    }

    public function getEntityProp(): string
    {
        return $this->entityProp;
    }

    public function setEntityProp(string $entityProp): self
    {
        $this->entityProp = $entityProp;
        return $this;
    }

    public function getFR(): ?string
    {
        return $this->FR;
    }

    public function setFR(?string $FR): self
    {
        $this->FR = $FR;

        return $this;
    }

    public function getEN(): ?string
    {
        return $this->EN;
    }

    public function setEN(?string $EN): self
    {
        $this->EN = $EN;
        return $this;
    }

    public function getDE(): ?string
    {
        return $this->DE;
    }

    public function setDE(?string $DE): self
    {
        $this->DE = $DE;
        return $this;
    }

    public function getES(): ?string
    {
        return $this->ES;
    }

    public function setES(?string $ES): self
    {
        $this->ES = $ES;
        return $this;
    }

    public function getLU(): ?string
    {
        return $this->LU;
    }

    public function setLU(?string $LU): self
    {
        $this->LU = $LU;
        return $this;
    }

    public function getDynTrans(){
        switch($this->locale){
            case 'FR' :
                return $this->getFR();
            case 'ES' :
                return $this->getES();
            case 'LU' :
                return $this->getLU();
            case 'DE' :
                return $this->getDE();
            case 'EN' :
                return $this->getEN();
            default:
                return $this->getEN();
                
        }
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): self
    {
        $this->organization = $organization;
        return $this;
    }
}
