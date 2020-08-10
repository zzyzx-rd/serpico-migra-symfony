<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AnswerRepository::class)
 */
class Answer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="asw_id", type="integer", length=11, nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $asw_text;

    /**
     * @ORM\Column(type="integer")
     */
    private $asw_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $asw_inserted;
    /**
     * @ManyToOne(targetEntity="SurveyField")
     * @JoinColumn(name="survey_field_sfi_id", referencedColumnName="sfi_id", nullable=false)
     */
    protected $field;

    /**
     * @ManyToOne(targetEntity="Survey")
     * @JoinColumn(name="survey_sur_id", referencedColumnName="sur_id", nullable=false)
     */
    protected $survey;
    /**
     * @ManyToOne(targetEntity="ActivityUser")
     * @JoinColumn(name="activity_user_a_u_id", referencedColumnName="a_u_id", nullable=true)
     */
    protected $participant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAswText(): ?string
    {
        return $this->asw_text;
    }

    public function setAswText(string $asw_text): self
    {
        $this->asw_text = $asw_text;

        return $this;
    }

    public function getAswCreatedBy(): ?int
    {
        return $this->asw_createdBy;
    }

    public function setAswCreatedBy(int $asw_createdBy): self
    {
        $this->asw_createdBy = $asw_createdBy;

        return $this;
    }

    public function getAswInserted(): ?\DateTimeInterface
    {
        return $this->asw_inserted;
    }

    public function setAswInserted(\DateTimeInterface $asw_inserted): self
    {
        $this->asw_inserted = $asw_inserted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     */
    public function setField($field): void
    {
        $this->field = $field;
    }

    /**
     * @return mixed
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * @param mixed $survey
     */
    public function setSurvey($survey): void
    {
        $this->survey = $survey;
    }

    /**
     * @return mixed
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * @param mixed $participant
     */
    public function setParticipant($participant): void
    {
        $this->participant = $participant;
    }

}
