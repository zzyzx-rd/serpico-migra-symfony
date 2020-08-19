<?php

namespace App\Entity;

use App\Repository\TemplateCriterionRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ORM\Entity(repositoryClass=TemplateCriterionRepository::class)
 */
class TemplateCriterion extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="crt_id", type="integer", nullable=false)
     */
    public $id;

    /**
     * @ORM\Column(name="crt_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @Column(name="crt_name", type="string",nullable=true)
     * @var string
     */
    protected $name;
    /**
     * @Column(name="crt_weight", length= 10, type="float")
     * @var float
     */
    protected $weight;
    /**
     * @Column(name="crt_forceComment_compare", type="boolean")
     * @var bool
     */
    protected $forceCommentCompare;
    /**
     * @Column(name="crt_forceComment_value", length= 10, type="float", nullable= true)
     * @var float
     */
    protected $forceCommentValue;
    /**
     * @Column(name="crt_forceComment_sign", type="string", nullable= true)
     * @var string
     */
    protected $forceCommentSign;
    /**
     * @Column(name="crt_lowerbound", length= 10, type="float",nullable=true)
     * @var float
     */
    protected $lowerbound;
    /**
     * @Column(name="crt_upperbound", length= 10, type="float",nullable=true)
     * @var float
     *
     */
    protected $upperbound;
    /**
     * @Column(name="crt_step", length= 10, type="float",nullable=true)
     * @var float
     */
    protected $step;
    /**
     * @Column(name="crt_comment", type="string")
     * @var string
     */
    protected $comment;
    /**
     * @Column(name="crt_createdBy", type="integer", nullable= true)
     * @var int
     */
    protected $createdBy;
    /**
     * @Column(name="crt_inserted", type="datetime", nullable= true)
     * @var DateTime
     */
    protected $inserted;

    /**
     * @ManyToOne(targetEntity="TemplateStage", inversedBy="criteria")
     * @JoinColumn(name="stage_stg_id", referencedColumnName="stg_id", nullable=true)
     */
    protected $stage;

    /**
     * @OneToMany(targetEntity="TemplateActivityUser", mappedBy="criterion",cascade={"persist", "remove"},orphanRemoval=true)
     * @JoinColumn(name="")
     */
//     * @OrderBy({"leader" = "ASC"})
    public $participants;

    /**
     * @OneToOne(targetEntity="CriterionName")
     * @JoinColumn(name="criterion_name_cna_id", referencedColumnName="cna_id")
     */
    protected $cName;

    /**
     * TemplateCriterion constructor.
     * @param int $id
     * @param int $type
     * @param null $name
     * @param int $weight
     * @param int $lowerbound
     * @param int $upperbound
     * @param float $step
     * @param null $forceCommentCompare
     * @param null $forceCommentValue
     * @param null $forceCommentSign
     * @param null $comment
     * @param $crt_createdBy
     * @param $stage
     * @param $participants
     * @param $cName
     */
    public function __construct(
        $id = 0,
        $type = 1,
        $name = null,
        $weight = 1,
        $lowerbound = 0,
        $upperbound = 5,
        $step = 0.5,
        $forceCommentCompare = null,
        $forceCommentValue = null,
        $forceCommentSign = null,
        $comment = null,
        $crt_createdBy = null,
        $stage = null,
        $participants = null,
        $cName = null)
    {
        parent::__construct($id,$crt_createdBy , new DateTime());
        $this->name = $name;
        $this->type = $type;
        $this->weight = $weight;
        $this->lowerbound = $lowerbound;
        $this->upperbound = $upperbound;
        $this->step = $step;
        $this->comment = $comment;
        $this->forceCommentCompare = $forceCommentCompare;
        $this->forceCommentSign = $forceCommentSign;
        $this->forceCommentValue = $forceCommentValue;
        $this->stage = $stage;
        $this->participants = $participants?:new ArrayCollection();
        $this->cName = $cName;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

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
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param mixed $participants
     */
    public function setParticipants($participants): void
    {
        $this->participants = $participants;
    }

    /**
     * @return mixed
     */
    public function getCName()
    {
        return $this->cName;
    }

    /**
     * @param mixed $cName
     */
    public function setCName($cName): void
    {
        $this->cName = $cName;
    }
    function addParticipant(TemplateActivityUser $participant){
        $this->participants->add($participant);
        $participant->setCriterion($this);
        return $this;
    }


    public function removeParticipant(TemplateActivityUser $participant): TemplateCriterion
    {
        // Remove this participant
        $this->participants->removeElement($participant);
        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }



}
