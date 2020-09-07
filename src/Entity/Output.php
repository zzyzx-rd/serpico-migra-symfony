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
     * @param bool $complete
     * @param null $masterUser
     * @param string $name
     * @param int $createdBy
     * @param DateTime $startdate
     * @param DateTime $enddate
     */
    public function __construct($id = 0, $createdBy = null)
    {
        parent::__construct($id, $createdBy, new DateTime);
    }

    /**
     * @return DateTime
     */
    public function getInserted(): DateTime
    {
        return $this->inserted;
    }

    /**
     * @param DateTime $inserted
     */
    public function setInserted(DateTime $inserted): void
    {
        $this->inserted = $inserted;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
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
    public function setStartdate(DateTime $startdate): void
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
    public function setEnddate(DateTime $enddate): void
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
     * @param mixed $stage
     */
    public function setStage($stage): void
    {
        $this->stage = $stage;
    }

    /**
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param mixed $criteria
     */
    public function setCriteria($criteria): void
    {
        $this->criteria = $criteria;
    }



}

