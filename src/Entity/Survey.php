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
class Survey extends DbObject
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

    /**
     * Survey constructor.
     * @param int $id
     * @param $sur_name
     * @param $sur_createdBy
     * @param $sur_inserted
     * @param $sur_state
     * @param $stage
     * @param $organization
     * @param ArrayCollection $participants
     * @param SurveyField[] $fields
     * @param Answer[]|ArrayCollection $answers
     */
    public function __construct(
        int $id = 0,
        $sur_state = null,
        $sur_name = '',
        $sur_createdBy = null,
        $sur_inserted = null,
        Stage $stage = null,
        Organization $organization = null,
        ArrayCollection $participants = null,
        array $fields = [],
        $answers = null)
    {
        $this->sur_name = $sur_name;
        $this->sur_inserted = $sur_inserted;
        $this->sur_state = $sur_state;
        $this->stage = $stage;
        $this->organization = $organization;
        $this->participants = $participants?$participants: new ArrayCollection();
        $this->fields = $fields?$fields: new ArrayCollection();
        $this->answers = $answers?$fields: new ArrayCollection();
    }


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
    public function addParticipant(ActivityUser $participant)
    {
        $this->participants->add($participant);
        $participant->setSurvey($this);
        return $this;
    }

    public function removeParticipant(ActivityUser $participant)
    {
        // Remove this participant
        $this->participants->removeElement($participant);
        return $this;
    }
    public function addField(SurveyField $field)
    {
        $this->fields->add($field);
        $field->setSurvey($this);
        return $this;
    }

    public function removeField(SurveyField $field)
    {
        $this->fields->removeElement($field);
        return $this;
    }
    public function addUserAnswer(Answer $answer)
    {
        $this->answers->add($answer);
        $answer->setSurvey($this);
        return $this;
    }
    public function removeUserAnswer(Answer $answer)
    {
        $this->answers->removeElement($answer);
        return $this;
    }
    public function addAnswer(Answer $answer)
    {
        $this->answers->add($answer);
        $answer->setSurvey($this);
        return $this;
    }

    public function removeAnswer(Answer $answer)
    {
        $this->answers->removeElement($answer);
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }
}
