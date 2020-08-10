<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SurveyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=SurveyRepository::class)
 */
class Survey
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="sur_id", type="integer", length=10, nullable=false)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $sur_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $sur_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sur_inserted;

    /**
     * @ORM\Column(type="integer")
     */
    private $sur_state;

    /**
     * @OneToOne(targetEntity="Stage")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id",nullable=true)
     */
    protected $stage;

    /**
     * @ManyToOne(targetEntity="Organization")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=true)
     */
    protected $organization;

    /**
     * @OneToMany(targetEntity="ActivityUser", mappedBy="survey", cascade={"persist"})
     * @OrderBy({"leader" = "DESC"})
     * @var ArrayCollection<ActivityUser>
     */
    private $participants;

    /**
     * @OneToMany(targetEntity="SurveyField", mappedBy="survey", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"sfi_position" = "ASC"})
     *  @var SurveyField[] $fields
     */
    protected $fields;
    /**
     * @OneToMany(targetEntity="Answer", mappedBy="survey", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"sfi_id" = "ASC"})
     * @var ArrayCollection|Answer[] $answers
     */
    protected $answers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurName(): ?string
    {
        return $this->sur_name;
    }

    public function setSurName(string $sur_name): self
    {
        $this->sur_name = $sur_name;

        return $this;
    }

    public function getSurCreatedBy(): ?int
    {
        return $this->sur_createdBy;
    }

    public function setSurCreatedBy(int $sur_createdBy): self
    {
        $this->sur_createdBy = $sur_createdBy;

        return $this;
    }

    public function getSurInserted(): ?\DateTimeInterface
    {
        return $this->sur_inserted;
    }

    public function setSurInserted(\DateTimeInterface $sur_inserted): self
    {
        $this->sur_inserted = $sur_inserted;

        return $this;
    }

    public function getSurState(): ?int
    {
        return $this->sur_state;
    }

    public function setSurState(int $sur_state): self
    {
        $this->sur_state = $sur_state;

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
    public function setStage($stage): void
    {
        $this->stage = $stage;
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
     * @return ArrayCollection
     */
    public function getParticipants(): ArrayCollection
    {
        return $this->participants;
    }

    /**
     * @param ArrayCollection $participants
     */
    public function setParticipants(ArrayCollection $participants): void
    {
        $this->participants = $participants;
    }

    /**
     * @return SurveyField[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param SurveyField[] $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @return Answer[]|ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param Answer[]|ArrayCollection $answers
     */
    public function setAnswers($answers): void
    {
        $this->answers = $answers;
    }

}
