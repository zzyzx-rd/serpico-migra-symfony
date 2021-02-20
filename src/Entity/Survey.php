<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SurveyRepository;
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
    public ?int $id;

    /**
     * @ORM\Column(name="sur_name", type="string", length=45)
     */
    public $name;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="surveyInitiatives")
     * @JoinColumn(name="sur_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\Column(name="sur_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="sur_state", type="integer", nullable=true)
     */
    public $state;

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
     * @OneToMany(targetEntity="Participation", mappedBy="survey", cascade={"persist"})
     * @var ArrayCollection<Participation>
     */
    public $participations;

    /**
     * @OneToMany(targetEntity="SurveyField", mappedBy="survey", cascade={"persist", "remove"}, orphanRemoval=true)
     *  @var ArrayCollection|SurveyField[] $fields
     */
//     * @ORM\OrderBy({"sfi_position" = "ASC"})
    protected $fields;
    /**
     * @OneToMany(targetEntity="Answer", mappedBy="survey", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Answer[] $answers
     */
//     * @ORM\OrderBy({"sfi_id" = "ASC"})
    protected $answers;

    /**
     * Survey constructor.
     * @param ?int$id
     * @param $sur_name
     * @param $sur_state
     * @param $stage
     * @param $organization
     * @param ArrayCollection $participations
     * @param SurveyField[] $fields
     * @param Answer[]|ArrayCollection $answers
     */
    public function __construct(
      ?int $id = 0,
        $sur_state = null,
        $sur_name = '',
        Stage $stage = null,
        Organization $organization = null,
        ArrayCollection $participations = null,
        ArrayCollection $fields = null,
        $answers = null)
    {
        parent::__construct($id, null, new DateTime());
        $this->name = $sur_name;
        $this->state = $sur_state;
        $this->stage = $stage;
        $this->organization = $organization;
        $this->participations = $participations?: new ArrayCollection();
        $this->fields = $fields?: new ArrayCollection();
        $this->answers = $answers?$fields: new ArrayCollection();
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

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

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
     * @return ArrayCollection|Participation[]
     */
    public function getParticipations(): ArrayCollection
    {
        return $this->participations;
    }

    /**
     * @return ArrayCollection|SurveyField[]
     */
    public function getFields(): ArrayCollection
    {
        return $this->fields;
    }

    /**
     * @return Answer[]|ArrayCollection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    public function addParticipation(Participation $participation)
    {
        $this->participations->add($participation);
        $participation->setSurvey($this);
        return $this;
    }

    public function removeParticipation(Participation $participant)
    {
        // Remove this participant
        $this->participations->removeElement($participant);
        return $this;
    }
    public function addField(SurveyField $field): Survey
    {
        $this->fields->add($field);
        $field->setSurvey($this);
        return $this;
    }

    public function removeField(SurveyField $field): Survey
    {
        $this->fields->removeElement($field);
        return $this;
    }
    public function addUserAnswer(Answer $answer): Survey
    {
        $this->answers->add($answer);
        $answer->setSurvey($this);
        return $this;
    }
    public function removeUserAnswer(Answer $answer): Survey
    {
        $this->answers->removeElement($answer);
        return $this;
    }
    public function addAnswer(Answer $answer): Survey
    {
        $this->answers->add($answer);
        $answer->setSurvey($this);
        return $this;
    }

    public function removeAnswer(Answer $answer): Survey
    {
        $this->answers->removeElement($answer);
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }
}
