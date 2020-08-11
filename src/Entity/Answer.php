<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AnswerRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AnswerRepository::class)
 */
class Answer extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="asw_id", type="integer", length=11, nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(name="asw_text", type="string", length=255)
     */
    private $desc;

    /**
     * @ORM\Column(name="asw_created_by", type="integer")
     */
    private $createdBy;

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

    /**
     * Answer constructor.
     * @param $id
     * @param string $desc
     * @param int|null $createdBy
     * @param $field
     * @param Survey $survey
     * @param $asw_inserted
     * @param ActivityUser $participant
     */
    public function __construct(
        $id = null,
        string $desc = null,
        int $createdBy = null,
        $field = null,
        Survey $survey = null,
        $asw_inserted = null,
        ActivityUser $participant = null)
    {
        parent::__construct($id, $createdBy, new DateTime());
        $this->desc = $desc;
        $this->asw_inserted = $asw_inserted;
        $this->field = $field;
        $this->survey = $survey;
        $this->participant = $participant;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesc()
    {
        if($this->field->getType()=='MC') {

            if(!is_string($this->desc)){

                return $this->desc;
            }else{
                $data = unserialize($this->desc);
                return $data;
            }
        }
        elseif ($this->field->getType()=='UC'){

            if($this->desc=="0"){
                return false;
            }
            else{
                return true;
            }
        }
        else{

            return $this->desc;

        }

    }

    public function setDesc(string $desc): self
    {
        $this->desc = $desc;
        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(int $createdBy): self
    {
        $this->createdBy = $createdBy;

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
     * @return Answer
     */
    public function setParticipant(ActivityUser $participant): Answer
    {
        $this->participant = $participant;
        return $this;
    }

}
