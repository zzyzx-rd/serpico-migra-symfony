<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TemplateActivityRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\TemplateActivityRepository", repositoryClass=TemplateActivityRepository::class)
 */
class TemplateActivity extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="act_id", type="integer", nullable=false)
     * @var int
     */
    public  $id;

    /**
     * @Column(name="act_magnitude", type="integer", nullable=true)
     * @var int
     */
    protected $magnitude;
    
    /**
     * @ORM\Column(name="act_simplified", type="boolean", nullable=true)
     */
    public $simplified;

    /**
     * @ORM\Column(name="act_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="act_visibility", type="string", length=255, nullable=true)
     */
    public $visibility;

    /**
     * @ORM\Column(name="act_objectives", type="string", length=255, nullable=true)
     */
    public $objectives;

    /**
     * @ORM\Column(name="act_created_by", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="act_inserted", type="datetime", nullable=true)
     */
    public $inserted;

    /**
     * @ORM\Column(name="act_saved", type="datetime", nullable=true)
     */
    public $saved;

    /**
     * @ManyToOne(targetEntity="Organization", inversedBy="templateActivities")
     * @JoinColumn(name="organization_org_id", referencedColumnName="org_id",nullable=false)
     */
    protected $organization;

    /**
     *@ManyToOne(targetEntity="Department", inversedBy="templateActivities")
     *@JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id", nullable=false)
     */
    protected $department;

    /**
     * @ManyToOne(targetEntity="TemplateRecurring", inversedBy="activities")
     * @JoinColumn(name="recurring_rct_id", referencedColumnName="rct_id",nullable=true)
     */
    protected $recurring;

    /**
     * @OneToMany(targetEntity="TemplateStage", mappedBy="activity", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var Collection|TemplateStage[]
     */
//     * @OrderBy({"startdate" = "ASC"})
    public $stages;

    /**
     * @OneToMany(targetEntity="TemplateActivityUser", mappedBy="activity",cascade={"persist", "remove"}, orphanRemoval=true)
     * @JoinColumn(name="a_u_participant", referencedColumnName="a_u_id")
     */
//     * @OrderBy({"team" = "ASC"})
    public $participants;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @JoinColumn(name="act_master_id", referencedColumnName="usr_id", nullable=true)
     */
    public $masterUser;
    /**
     * @var DateTime
     */
    public $startdate;
    /**
     * @var DateTime
     */
    public $enddate;

    /**
     * TemplateActivity constructor.
     * @param int $id
     * @param int $magnitude
     * @param $act_simplified
     * @param $act_name
     * @param $act_visibility
     * @param $act_objectives
     * @param $act_createdBy
     * @param $act_inserted
     * @param $act_saved
     * @param $organization
     * @param $department
     * @param $recurring
     * @param TemplateStage[]|Collection $stages
     * @param $participants
     * @param $act_master_usr
     */
    public function __construct(
        int $id,
        $act_master_usr,
        $magnitude = 1,
        $act_simplified = true,
        $act_name = null,
        $act_visibility = 'public',
        $act_objectives = '',
        $act_createdBy = null,
        $act_inserted = null,
        $act_saved = null,
        $organization = null,
        $department = null,
        $recurring = null,
        $stages = null,
        $participants = null)
    {
        parent::__construct($id, $act_createdBy, new DateTime());
        $this->magnitude = $magnitude;
        $this->simplified = $act_simplified;
        $this->name = $act_name;
        $this->visibility = $act_visibility;
        $this->objectives = $act_objectives;
        $this->inserted = $act_inserted;
        $this->saved = $act_saved;
        $this->organization = $organization;
        $this->department = $department;
        $this->recurring = $recurring;
        $this->stages = $stages?: new ArrayCollection();
        $this->participants = $participants?:new ArrayCollection();
        $this->masterUser = $act_master_usr;
    }


    public function getSimplified(): ?bool
    {
        return $this->simplified;
    }

    public function setSimplified(bool $act_simplified): self
    {
        $this->simplified = $act_simplified;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $act_name): self
    {
        $this->name = $act_name;

        return $this;
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(string $act_visibility): self
    {
        $this->visibility = $act_visibility;

        return $this;
    }

    public function getObjectives(): ?string
    {
        return $this->objectives;
    }

    public function setObjectives(string $act_objectives): self
    {
        $this->objectives = $act_objectives;

        return $this;
    }

    public function setInserted(DateTimeInterface $act_inserted): self
    {
        $this->inserted = $act_inserted;

        return $this;
    }

    public function getSaved(): ?DateTimeInterface
    {
        return $this->saved;
    }

    public function setSaved(DateTimeInterface $act_saved): self
    {
        $this->saved = $act_saved;

        return $this;
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
     * @return mixed
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $department
     */
    public function setDepartment($department): void
    {
        $this->department = $department;
    }

    /**
     * @return mixed
     */
    public function getRecurring()
    {
        return $this->recurring;
    }

    /**
     * @param mixed $recurring
     */
    public function setRecurring($recurring): void
    {
        $this->recurring = $recurring;
    }

    /**
     * @return TemplateStage[]|Collection
     */
    public function getStages()
    {
        return $this->stages;
    }

    /**
     * @param TemplateStage[]|Collection $stages
     */
    public function setStages($stages): void
    {
        $this->stages = $stages;
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

    public function getMasterUser(): ?User
    {
        return $this->masterUser;
    }

    public function setMasterUser(?User $act_master_usr): self
    {
        $this->masterUser = $act_master_usr;

        return $this;
    }
    public function addParticipant(TemplateActivityUser $participant): TemplateActivity
    {
        $this->participants->add($participant);
        $participant->setivity($this);
        return $this;
    }

    public function removeParticipant(TemplateActivityUser $participant): TemplateActivity
    {
        $this->participants->removeElement($participant);
        return $this;
    }

    public function addStage(TemplateStage $stage): TemplateActivity
    {

        $this->stages->add($stage);
        $stage->setivity($this);
        return $this;
    }

    public function removeStage(TemplateStage $stage): TemplateActivity
    {
        $this->stages->removeElement($stage);
        return $this;
    }

    public function removeActiveStage(TemplateStage $stage): TemplateActivity
    {
        $this->stages->removeElement($stage);
        return $this;
    }
    public function getLatestStage()
    {
        $returnedStage = $this->stages->first();
        foreach ($this->stages as $stage) {
            if ($stage->getEnddate() > $returnedStage) {
                $returnedStage = $stage;
            }
        }
        return $returnedStage;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

    public function hasParticipants(): bool
    {
        foreach ($this->getActiveStages() as $stage){
            if (count($stage->getParticipants()) > 1){
                return true;
            }
        }
        return false;
    }
    /**
     * @return DateTime
     */
    public function getStartdate(): DateTime
    {
        $startDate = new DateTime('2099-01-01');
        foreach ($this->stages as $stage) {
            if ($stage->getStartdate() < $startDate) {
                $startDate = $stage->getStartDate();
            }
        }
        return $startDate;
    }

    public function getEnddate()
    {
        $enddate = new DateTime('2000-01-01');
        foreach ($this->getStages() as $stage) {
            if ($stage->getEnddate() > $enddate) {
                $enddate = $stage->getEnddate();
            }
        }
        return $enddate;
    }

    /**
     * @param DateTime $startdate
     * @return TemplateActivity
     */
    public function setStartdate($startdate): TemplateActivity
    {
        $this->startdate = $startdate;
        return $this;
    }


    /**
     * @param DateTime $enddate
     * @return TemplateActivity
     */
    public function setEnddate($enddate): TemplateActivity
    {
        $this->enddate = $enddate;
        return $this;
    }
    public function addActiveModifiableStage(TemplateStage $stage): ?TemplateActivity
    {
        if (!$stage->getActivity()) {
            $this->stages->add($stage);
            $stage->setActivity($this);
            return $this;
        }
    }

    public function removeActiveModifiableStage(TemplateStage $stage): TemplateActivity
    {
        $this->stages->removeElement($stage);
        //$stage->setActivity(null);
        return $this;
    }

    /**
     * An activity template is *never* finalized by definition,
     * however this getter is necessary in various templates
     */
    public function isFinalized(): bool
    {
        return false;
    }

    public function userCanEdit(User $u): bool
    {
        return $u->getRole() === 1 || $u->getRole() === 4 || $this->masterUser == $u;
    }


}
