<?php


namespace App\Entity;

use App\Repository\OutputRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=OutputRepository::class)
 */
class Output extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="otp_id", type="integer",nullable=false, length=10)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="otp_startdate", type="datetime", nullable=true)
     */
    public DateTime $startdate;

    /**
     * @ORM\Column(name="otp_enddate", type="datetime", nullable=true)
     */
    public DateTime $enddate;

    /**
     * @ManyToOne(targetEntity=Stage::class, inversedBy="outputs")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id",nullable=false)
     */
    protected $stage;

    /**
     * @OneToMany(targetEntity="Criterion", mappedBy="output", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $criteria;

    /**
     * @ORM\Column(name="otp_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

    /**
     * @ORM\Column(name="otp_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @OneToOne(targetEntity="Survey")
     * @JoinColumn(name="survey_sur_id", referencedColumnName="sur_id",nullable=true)
     * @var Survey
     */
    protected ?Survey $survey;

    /**
     * Stage constructor.
     * @param ?int$id
     * @param null $masterUser
     * @param int $createdBy
     * @param DateTime $startdate
     * @param DateTime $enddate
     * @param DateTime $inserted
     */

    public function __construct($id = 0,
                                $createdBy = null
    )
    {
        parent::__construct($id, $createdBy, new DateTime);
        $this->criteria= new ArrayCollection;
        $this->startdate = new DateTime;
        $this->enddate = new DateTime;
    }


    /**
     * @return  ArrayCollection|Criterion[]
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param mixed $stage
     */
    public function setStage($stage): void
    {
        $this->stage = $stage;
    }

    public function addCriterion(Criterion $criterion): Output
    {

        $this->criteria->add($criterion);
        $criterion->setOutput($this);
        return $this;

    }
    public function removeCriterion(Criterion $criterion): Output
{
    $this->criteria->removeElement($criterion);
    return $this;
}

    /**
     * @return DateTime
     */
    public function getStartdate(): DateTime
    {
        return $this->startdate;
    }

    /**
     * @param DateTime $startdate
     */
    public function setStartdate(DateTime $startdate): self
    {
        $this->startdate = $startdate;
    }

    /**
     * @return DateTime
     */
    public function getEnddate(): DateTime
    {
        return $this->enddate;
    }

    /**
     * @param DateTime $enddate
     */
    public function setEnddate(DateTime $enddate): self
    {
        $this->enddate = $enddate;
    }

    /**
     * @return mixed
     */
    public function getStage()
    {
        return $this->stage;
    }




    /**
     * @return Survey
     */
    public function getSurvey(): Survey
    {
        return $this->survey;
    }

    /**
     * @param Survey $survey
     */
    public function setSurvey(Survey $survey): self
    {
        $this->survey = $survey;
    }





}

