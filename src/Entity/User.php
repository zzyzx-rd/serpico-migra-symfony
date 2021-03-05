<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
// * @UniqueEntity(
// *     fields={"email"},
// *     message="This email already exists"
// * )
class User extends DbObject implements  UserInterface, \Serializable
{

    public const ROLE_ROOT = 0;
    public const ROLE_SUPER_ADMIN = 1;
    public const ROLE_ADMIN = 2;
    public const ROLE_AM = 3;
    public const ROLE_COLLAB = 4;
    /**
     * @Id()
     * @GeneratedValue()
     * @Column(name="usr_id", type="integer", nullable=false)
     * @var int
     */
    protected ?int $id;


    /**
     * @ORM\Column(name="usr_int", type="boolean", nullable=true)
     */
    public $internal;

    /**
     * @ORM\Column(name="usr_synth", type="boolean", nullable=true)
     */
    public $synthetic;

    /**
     * @ORM\Column(name="usr_firstname", type="string", length=255, nullable=true)
     */
    public $firstname;

    /**
     * @ORM\Column(name="usr_lastname", type="string", length=255, nullable=true)
     */
    public $lastname;

    /**
     * @ORM\Column(name="usr_username", type="string", length=255, nullable=true)
     */
    public $username;

    /**
     * @ORM\Column(name="usr_nickname", type="string", length=255, nullable=true)
     */
    public $nickname;

    /**
     * @ORM\Column(name="usr_birthdate", type="datetime", nullable=true)
     */
    public $birthdate;

    /**
     * @ORM\Column(name="usr_email", type="string", length=255, nullable=true)
     */
    public $email;

    /**
     * @ORM\Column(name="usr_alt_email", type="string", length=255, nullable=true)
     */
    public $altEmail;

    /**
     * @ORM\Column(name="usr_password", type="string", length=255, nullable=true)
     */
    public $password;

    /**
     * @ORM\Column(name="usr_position_name", type="string", length=255, nullable=true)
     */
    public $positionName;

    /**
     * @ORM\Column(name="usr_picture", type="string", length=255, nullable=true)
     */
    public $picture;

    /**
     * @ORM\Column(name="usr_token", type="string", length=255, nullable=true)
     */
    public $token;

    /**
     * @ORM\Column(name="usr_weight_ini", type="float", nullable=true)
     */
    public $weight_ini;

    /**
     * @ORM\Column(name="usr_act_archive_nb_days", type="integer", nullable=true)
     */
    public $activitiesArchivingNbDays;

    /**
     * @ORM\Column(name="usr_rm_token", type="string", length=255, nullable=true)
     */
    public $rememberMeToken;

    /**
     * @ORM\Column(name="usr_validated", type="datetime", nullable=true)
     */
    public $validated;

    /**
     * @ORM\Column(name="usr_enabledCreatingUser", type="boolean", nullable=true)
     */
    public $enabledCreatingUser;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="userInitiatives")
     * @JoinColumn(name="usr_initiator", referencedColumnName="usr_id", nullable=true)
     */
    protected ?User $initiator;

    /**
     * @ORM\Column(name="usr_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * @ORM\Column(name="usr_last_connected", type="datetime", nullable=true)
     */
    public $lastConnected;

    /**
     * @ORM\Column(name="usr_deleted", type="datetime", nullable=true)
     */
    public $deleted;

    /**
     * @Column(name="role_rol_id", type="integer", nullable=true)
     * @var int
     */
    protected $role;
    
    /**
     * @ManyToOne(targetEntity="Subscription", inversedBy="subscriptors")
     * @JoinColumn(name="subscription_sub_id", referencedColumnName="sub_id", nullable=true)
     */
    protected $subscription;

    /**
     * @OneToMany(targetEntity="ExternalUser", mappedBy="user",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $externalUsers;

    /**
     * @OneToMany(targetEntity="EventComment", mappedBy="author",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $eventComments;
    
    /**
     * @OneToMany(targetEntity="DocumentAuthor", mappedBy="author", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $documentContributions;

    /** 
     * @ManyToOne(targetEntity="User", inversedBy="subordinates")
     * @JoinColumn(name="usr_superior", referencedColumnName="usr_id")
     * @var User|null
     */
    protected $superior;

    /**
     * @OneToMany(targetEntity="Mail", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $mails;

    /**
     * @OneToMany(targetEntity="Target", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $targets;

    /**
     * @OneToMany(targetEntity="OrganizationUserOption", mappedBy="user", cascade={"persist","remove"}, orphanRemoval=true)
     */
    public $options;

    /**
     * @OneToOne(targetEntity="WorkerIndividual")
     * @JoinColumn(name="worker_individual_win_id", referencedColumnName="win_id")
     */
    public $workerIndividual;

    /**
     * @ORM\OneToMany(targetEntity=Participation::class, mappedBy="user")
     */
    public $participations;

    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="user")
     */
    public $results;

    /**
     * @ORM\OneToMany(targetEntity=Member::class, mappedBy="user")
     */
    public $members;

    /**
     * @ORM\ManyToOne(targetEntity=Weight::class, inversedBy="users")
     * @ORM\JoinColumn(name="weight_wgt_id",referencedColumnName="wgt_id", nullable=true)
     */
    public $weight;

    /**
     * @ORM\ManyToOne(targetEntity=Position::class, inversedBy="users")
     * @JoinColumn(name="position_pos_id", referencedColumnName="pos_id", nullable=true, onDelete="SET NULL")
     */
    public $position;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="users")
     * @JoinColumn(name="department_dpt_id", referencedColumnName="dpt_id", nullable=true, onDelete="SET NULL")
     */
    public $department;

    /**
     * @ORM\ManyToOne(targetEntity=Title::class)
     * @JoinColumn(name="title_tit_id", referencedColumnName="tit_id", nullable=true)
     */
    public $title;

    /**
     * @ORM\ManyToOne(targetEntity=Organization::class, inversedBy="users")
     * @ORM\JoinColumn(name="organization_org_id", referencedColumnName="org_id", nullable=true, onDelete="CASCADE")
     */
    public $organization;

    /**
     * @ORM\ManyToOne(targetEntity=UserGlobal::class, inversedBy="userAccounts")
     * @ORM\JoinColumn(name="user_global_usg_id", referencedColumnName="usg_id", nullable=true, onDelete="CASCADE")
     */
    public $userGlobal;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="superior")
     */
    public $subordinates;

    /**
     * @OneToMany(targetEntity="ElementUpdate", mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    public $updates;

    private $roles;

    /**
     * @ORM\OneToMany(targetEntity=UserMaster::class, mappedBy="user", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|UserMaster[]
     */
    private $masterings;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|User[]
     */
    private $userInitiatives;

    /**
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Activity[]
     */
    private $activityInitiatives;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Answer[]
     */
    private $answerInitiatives;

    /**
     * @ORM\OneToMany(targetEntity=City::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|City[]
     */
    private $cityInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Client::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Client[]
     */
    private $clientInitiatives;

    /**
     * @ORM\OneToMany(targetEntity=Contact::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Contact[]
     */
    private $contactInitiatives;

    /**
     * @ORM\OneToMany(targetEntity=Country::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Country[]
     */
    private $countryInitiatives;

    /**
     * @ORM\OneToMany(targetEntity=Criterion::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Criterion[]
     */
    private $criterionInitiatives;

    /**
     * @ORM\OneToMany(targetEntity=CriterionGroup::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|CriterionGroup[]
     */
    private $criterionGroupInitiatives;

    /**
     * @ORM\OneToMany(targetEntity=CriterionName::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|CriterionName[]
     */
    private $criterionNameInitiatives;
    
    /**
     * @ORM\OneToMany(targetEntity=Decision::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Decision[]
     */
    private $decisionInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Department::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Department[]
     */
    private $departmentInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=DocumentAuthor::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|DocumentAuthor[]
     */
    private $documentAuthorInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=DynamicTranslation::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|DynamicTranslation[]
     */
    private $dynamicTranslationInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=ElementUpdate::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|ElementUpdate[]
     */
    private $elementUpdateInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Event[]
     */
    private $eventInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=EventComment::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|EventComment[]
     */
    private $eventCommentInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=EventDocument::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|EventDocument[]
     */
    private $eventDocumentInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=EventGroup::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|EventGroup[]
     */
    private $eventGroupInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=EventGroupName::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|EventGroupName[]
     */
    private $eventGroupNameInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=EventName::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|EventName[]
     */
    private $eventNameInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=EventType::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|EventType[]
     */
    private $eventTypeInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=ExternalUser::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|ExternalUser[]
     */
    private $externalUserInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=GeneratedError::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|GeneratedError[]
     */
    private $generatedErrorInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=GeneratedImage::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|GeneratedImage[]
     */
    private $generatedImageInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Grade::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Grade[]
     */
    private $gradeInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Icon::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Icon[]
     */
    private $iconInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=InstitutionProcess::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|InstitutionProcess[]
     */
    private $institutionProcessInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=IProcessCriterion::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|IProcessCriterion[]
     */
    private $iProcessCriterionInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=IProcessParticipation::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|IProcessParticipation[]
     */
    private $iProcessParticipationInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=IProcessStage::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|IProcessStage[]
     */
    private $iProcessStageInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Mail::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Mail[]
     */
    private $mailInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Member::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Member[]
     */
    private $memberInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=OptionName::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|OptionName[]
     */
    private $optionNameInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Organization::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Organization[]
     */
    private $organizationInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=OrganizationPaymentMethod::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|OrganizationPaymentMethod[]
     */
    private $organizationPaymentMethodInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=OrganizationUserOption::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|OrganizationUserOption[]
     */
    private $organizationUserOptionInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=OTPUser::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|OTPUser[]
     */
    private $OTPUserInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Output::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Output[]
     */
    private $outputInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Participation::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Participation[]
     */
    private $participationInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Position::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Position[]
     */
    private $positionInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Process::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Process[]
     */
    private $processInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=ProcessCriterion::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|ProcessCriterion[]
     */
    private $processCriterionInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=ProcessStage::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|ProcessStage[]
     */
    private $processStageInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Ranking::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Ranking[]
     */
    private $rankingInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=RankingHistory::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|RankingHistory[]
     */
    private $rankingHistoryInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=RankingTeam::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|RankingTeam[]
     */
    private $rankingTeamInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=RankingTeamHistory::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|RankingTeamHistory[]
     */
    private $rankingTeamHistoryInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Recurring::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Recurring[]
     */
    private $recurringInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Result[]
     */
    private $resultInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=ResultProject::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|ResultProject[]
     */
    private $resultProjectInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=ResultTeam::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|ResultTeam[]
     */
    private $resultTeamInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Stage::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Stage[]
     */
    private $stageInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=State::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|State[]
     */
    private $stateInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Survey::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Survey[]
     */
    private $surveyInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=SurveyField::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|SurveyField[]
     */
    private $surveyFieldInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=SurveyFieldParameter::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|SurveyFieldParameter[]
     */
    private $surveyFieldParameterInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Target::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Target[]
     */
    private $targetInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Team::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Team[]
     */
    private $teamInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Title::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Title[]
     */
    private $titleInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=UserGlobal::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|UserGlobal[]
     */
    private $userGlobalInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=UserMaster::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|UserMaster[]
     */
    private $userMasterInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Weight::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Weight[]
     */
    private $weightInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=WorkerExperience::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|WorkerExperience[]
     */
    private $workerExperienceInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=WorkerFirm::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|WorkerFirm[]
     */
    private $workerFirmInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=WorkerFirmCompetency::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|WorkerFirmCompetency[]
     */
    private $workerFirmCompetencyInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=WorkerFirmLocation::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|WorkerFirmLocation[]
     */
    private $workerFirmLocationInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=WorkerFirmSector::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|WorkerFirmSector[]
     */
    private $workerFirmSectorInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=WorkerIndividual::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|WorkerIndividual[]
     */
    private $workerIndividualInitiatives;
    /**
     * @ORM\OneToMany(targetEntity=Subscription::class, mappedBy="initiator", cascade={"persist", "remove"}, orphanRemoval=true)
     * @var ArrayCollection|Subscription[]
     */
    private $subscriptionInitiatives;

    /**
     * @var UploadedFile
     */
    protected $pictureFile;

    /**
     * User constructor.
     * @param ?int$id
     * @param bool $int
     * @param string $firstname
     * @param string $lastname
     * @param string $username
     * @param string $nickname
     * @param $birthdate
     * @param string $email
     * @param string $altEmail
     * @param string $password
     * @param $picture
     * @param null $pictureFile
     * @param $positionName
     * @param $token
     * @param $weight_ini
     * @param $usr_weight_1y
     * @param $weight_2y
     * @param $weight_3y
     * @param $weight_4y
     * @param $weight_5y
     * @param $activitiesArchivingNbDays
     * @param $rememberMeToken
     * @param $validated
     * @param $enabledCreatingUser
     * @param $lastConnected
     * @param $deleted
     * @param int $role
     * @param $externalUsers
     * @param int $superior
     * @param $mails
     * @param $targets
     * @param $options
     * @param $workerIndividual
     * @param $Reccuring
     * @param $results
     * @param $members
     * @param $weight
     * @param $position
     * @param $department
     * @param $title
     * @param $organization
     */
    public function __construct(
      ?int $id = null,
        $internal = true,
        $synthetic = null,
        $firstname = null,
        $lastname = null,
        $username = null,
        $nickname = null,
        $birthdate = null,
        $altEmail = null,
        $email = null,
        $password = null,
        $picture = null,
        $pictureFile = null,
        $token = null,
        $weight_ini = null,
        int $role = null,
        $department = null,
        $position = null,
        $positionName = null,
        $activitiesArchivingNbDays = 7,
        $rememberMeToken = null,
        $superior = null,
        $validated = null,
        $lastConnected = null,
        $deleted = null,
        $enabledCreatingUser = null,
        $externalUsers = null,
        $mails = null,
        $targets = null,
        $options = null,
        $workerIndividual = null,
        $Reccuring = null,
        $results = null,
        $members = null,
        $weight = null,
        $title = null
    )
    {
        parent::__construct($id, null, new DateTime());
        $this->pictureFile = $pictureFile;
        $this->internal = $internal;
        $this->synthetic = $synthetic;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->username = $username;
        $this->nickname = $nickname;
        $this->birthdate = $birthdate;
        $this->altEmail = $altEmail;
        $this->email = $email;
        $this->password = $password;
        $this->positionName = $positionName;
        $this->picture = $picture;
        $this->token = $token;
        $this->weight_ini = $weight_ini;
        $this->activitiesArchivingNbDays = $activitiesArchivingNbDays;
        $this->rememberMeToken = $rememberMeToken;
        $this->validated = $validated;
        $this->enabledCreatingUser = $enabledCreatingUser;
        $this->lastConnected = $lastConnected;
        $this->deleted = $deleted;
        $this->role = $role;
        $this->externalUsers = new ArrayCollection();
        $this->superior = $superior;
        $this->mails = $mails?:new ArrayCollection();
        $this->targets = $targets?:new ArrayCollection();
        $this->options = $options?:new ArrayCollection();
        $this->workerIndividual = $workerIndividual;
        $this->Reccuring = $Reccuring;
        $this->results = new ArrayCollection();
        $this->members = $members;
        $this->weight = $weight;
        $this->position = $position;
        $this->department = $department;
        $this->title = $title;
        $this->subordinates = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->updates = new ArrayCollection();
        $this->masterings = new ArrayCollection();
        $this->userInitiatives = new ArrayCollection();
        $this->activityInitiatives = new ArrayCollection();
        $this->answerInitiatives = new ArrayCollection();
        $this->cityInitiatives = new ArrayCollection();
        $this->clientInitiatives = new ArrayCollection();
        $this->contactInitiatives = new ArrayCollection();
        $this->countryInitiatives = new ArrayCollection();
        $this->criterionInitiatives = new ArrayCollection();
        $this->criterionGroupInitiatives = new ArrayCollection();
        $this->criterionNameInitiatives = new ArrayCollection();
        $this->decisionInitiatives = new ArrayCollection();
        $this->departmentInitiatives = new ArrayCollection();
        $this->documentAuthorInitiatives = new ArrayCollection();
        $this->dynamicTranslationInitiatives = new ArrayCollection();
        $this->elementUpdateInitiatives = new ArrayCollection();
        $this->eventInitiatives = new ArrayCollection();
        $this->eventCommentInitiatives = new ArrayCollection();
        $this->eventDocumentInitiatives = new ArrayCollection();
        $this->eventGroupInitiatives = new ArrayCollection();
        $this->eventGroupNameInitiatives = new ArrayCollection();
        $this->eventNameInitiatives = new ArrayCollection();
        $this->eventTypeInitiatives = new ArrayCollection();
        $this->externalUserInitiatives = new ArrayCollection();
        $this->generatedErrorInitiatives = new ArrayCollection();
        $this->generatedImageInitiatives = new ArrayCollection();
        $this->gradeInitiatives = new ArrayCollection();
        $this->iconInitiatives = new ArrayCollection();
        $this->institutionProcessInitiatives = new ArrayCollection();
        $this->iProcessCriterionInitiatives = new ArrayCollection();
        $this->iProcessParticipationInitiatives = new ArrayCollection();
        $this->iProcessStageInitiatives = new ArrayCollection();
        $this->memberInitiatives = new ArrayCollection();
        $this->optionNameInitiatives = new ArrayCollection();
        $this->organizationInitiatives = new ArrayCollection();
        $this->organizationPaymentMethodInitiatives = new ArrayCollection();
        $this->organizationUserOptionInitiatives = new ArrayCollection();
        $this->OTPUserInitiatives = new ArrayCollection();
        $this->participationInitiatives = new ArrayCollection();
        $this->positionInitiatives = new ArrayCollection();
        $this->processInitiatives = new ArrayCollection();
        $this->processCriterionInitiatives = new ArrayCollection();
        $this->processStageInitiatives = new ArrayCollection();
        $this->rankingInitiatives = new ArrayCollection();
        $this->rankingHistoryInitiatives = new ArrayCollection();
        $this->rankingTeamInitiatives = new ArrayCollection();
        $this->rankingTeamHistoryInitiatives = new ArrayCollection();
        $this->recurringInitiatives = new ArrayCollection();
        $this->resultInitiatives = new ArrayCollection();
        $this->resultProjectInitiatives = new ArrayCollection();
        $this->resultTeamInitiatives = new ArrayCollection();
        $this->stageInitiatives = new ArrayCollection();
        $this->stateInitiatives = new ArrayCollection();
        $this->surveyInitiatives = new ArrayCollection();
        $this->surveyFieldInitiatives = new ArrayCollection();
        $this->surveyFieldParameterInitiatives = new ArrayCollection();
        $this->targetInitiatives = new ArrayCollection();
        $this->teamInitiatives = new ArrayCollection();
        $this->titleInitiatives = new ArrayCollection();
        $this->userGlobalInitiatives = new ArrayCollection();
        $this->userMasterInitiatives = new ArrayCollection();
        $this->weightInitiatives = new ArrayCollection();
        $this->workerExperienceInitiatives = new ArrayCollection();
        $this->workerFirmCompetencyInitiatives = new ArrayCollection();
        $this->workerFirmLocationInitiatives = new ArrayCollection();
        $this->workerFirmSectorInitiatives = new ArrayCollection();
        $this->workerIndividualInitiatives = new ArrayCollection();
        $this->subscriptionInitiatives = new ArrayCollection();
    }
    
    /**
     * @return string
     */
    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    /**
     * @param Subscription $subscription
     */
    public function setSubscription(?Subscription $subscription): self
    {
        $this->subscription = $subscription;
        return $this;
    }

    public function isInternal(): ?bool
    {
        return $this->internal;
    }

    public function setInternal(bool $internal): self
    {
        $this->internal = $internal;

        return $this;
    }

    public function isSynthetic(): ?bool
    {
        return $this->synthetic;
    }

    public function setSynthetic(bool $synthetic): self
    {
        $this->synthetic = $synthetic;
        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getBirthdate(): ?DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAltEmail(): ?string
    {
        return $this->altEmail;
    }

    public function setAltEmail(?string $altEmail): self
    {
        $this->altEmail = $altEmail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPositionName(): ?string
    {
        return $this->positionName;
    }

    public function setPositionName(?string $positionName): self
    {
        $this->positionName = $positionName;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getWeightIni(): ?float
    {
        return $this->weight_ini;
    }

    public function setWeightIni(float $weight_ini): self
    {
        $this->weight_ini = $weight_ini;

        return $this;
    }



    public function getActivitiesArchivingNbDays(): ?int
    {
        return $this->activitiesArchivingNbDays;
    }

    public function setActivitiesArchivingNbDays(int $activitiesArchivingNbDays): self
    {
        $this->activitiesArchivingNbDays = $activitiesArchivingNbDays;

        return $this;
    }

    public function getRememberMeToken(): ?string
    {
        return $this->rememberMeToken;
    }

    public function setRememberMeToken(string $rememberMeToken): self
    {
        $this->rememberMeToken = $rememberMeToken;

        return $this;
    }

    public function getValidated(): ?DateTimeInterface
    {
        return $this->validated;
    }

    public function setValidated(DateTimeInterface $validated): self
    {
        $this->validated = $validated;

        return $this;
    }

    public function getEnabledCreatingUser(): ?bool
    {
        return $this->enabledCreatingUser;
    }

    public function setEnabledCreatingUser(bool $enabledCreatingUser): self
    {
        $this->enabledCreatingUser = $enabledCreatingUser;
        return $this;
    }

    public function setInserted(DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;

        return $this;
    }

    public function getLastConnected(): ?DateTimeInterface
    {
        return $this->lastConnected;
    }

    public function setLastConnected(?DateTimeInterface $lastConnected): self
    {
        $this->lastConnected = $lastConnected;
        return $this;
    }

    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    public function setDeleted(?DateTimeInterface $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @param ?int$id
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getRole(): ?int
    {
        return $this->role;
    }

    /**
     * @param int $role
     * @return User
     */
    public function setRole(int $role): User
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return User
     */
    public function getSuperior()
    {
        return $this->superior;
    }

    /**
     * @param User $superior
     * @return User
     */
    public function setSuperior(?User $superior)
    {
        $this->superior = $superior;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMails()
    {
        return $this->mails;
    }

    /**
     * @param mixed $mails
     * @return User
     */
    public function setMails($mails): User
    {
        $this->mails = $mails;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTargets()
    {
        return $this->targets;
    }


    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return mixed
     */
    public function getWorkerIndividual()
    {
        return $this->workerIndividual;
    }

    /**
     * @param mixed $workerIndividual
     * @return User
     */
    public function setWorkerIndividual($workerIndividual): User
    {
        $this->workerIndividual = $workerIndividual;
        return $this;
    }

    /**
     * @return ArrayCollection|Participation[]
     */
    public function getParticipations(){
        return $this->participations;
    }

    public function addParticipation(Participation $participation): self
    {
        $this->participations->add($participation);
        $participation->setUser($this);
        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        $this->participations->removeElement($participation);
        return $this;
    }

    /**
     * @return ArrayCollection|ExternalUser[]
     */
    public function getExternalUsers()
    {
        return $this->externalUsers;
    }

    public function addExternalUser(ExternalUser $externalUser): self
    {
        $this->externalUsers->add($externalUser);
        $externalUser->setUser($this);
        return $this;
    }

    public function removeExternalUser(ExternalUser $externalUser): self
    {
        $this->externalUsers->removeElement($externalUser);
        return $this;
    }

    /**
     * @return ArrayCollection|EventComment[]
     */
    public function getEventComments()
    {
        return $this->eventComments;
    }

    public function addEventComment(EventComment $eventComment): self
    {
        $this->eventComments->add($eventComment);
        $eventComment->setAuthor($this);
        return $this;
    }

    public function removeEventComment(EventComment $eventComment): self
    {
        $this->eventComments->removeElement($eventComment);
        return $this;
    }

    /**
     * @return ArrayCollection|EventDocument[]
     */
    public function getEventDocuments()
    {
        return $this->eventDocuments;
    }

    public function addEventDocument(EventDocument $eventDocument): self
    {
        $this->eventDocuments->add($eventDocument);
        $eventDocument->setAuthor($this);
        return $this;
    }

    public function removeEventDocument(EventDocument $eventDocument): self
    {
        $this->eventDocuments->removeElement($eventDocument);
        return $this;
    }

    /**
     * @return ArrayCollection|Result[]
     */
    public function getResults()
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setUser($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getUser() === $this) {
                $result->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection|Member[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setUser($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
            // set the owning side to null (unless already changed)
            if ($member->getUser() === $this) {
                $member->setUser(null);
            }
        }

        return $this;
    }

    public function getWeight(): ?Weight
    {
        return $this->weight;
    }

    public function setWeight(Weight $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getTitle(): ?Title
    {
        return $this->title;
    }

    public function setTitle(?Title $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(Organization $organization): self
    {
        $this->organization = $organization;
        return $this;
    }

    public function getUserGlobal(): ?UserGlobal
    {
        return $this->userGlobal;
    }

    public function setUserGlobal(UserGlobal $userGlobal): self
    {
        $this->userGlobal = $userGlobal;
        return $this;
    }

    public function getFullName(): string
    {
        if ($this->deleted) {
            return (string) $this->id;
        }
        return "$this->firstname $this->lastname";
    }

    public function getInvertedFullName(): string
    {
        if ($this->deleted) {
            return (string) $this->id;
        }

        return "$this->lastname $this->firstname";
    }
    public function getAbbr()
    {
        $prefix = $this->firstname ? $this->firstname[0] : '';
        $suffix = $this->lastname ? $this->lastname[0] : '';
        return strtoupper($prefix . $suffix);
    }
    public function removeOption(OrganizationUserOption $option): User
    {
        $this->options->removeElement($option);
        return $this;
    }
    public function __toString()
    {
        return (string) $this->id;
    }
    public function addMail(Mail $mail): User
    {
        $this->mails->add($mail);
        $mail->setUser($this);
        return $this;
    }

    public function removeMail(Mail $mail): User
    {
        $this->mails->removeElement($mail);
        return $this;
    }
    public function addTarget(Target $target): User
    {
        $this->targets->add($target);
        $target->setUser($this);
        return $this;
    }

    public function removeTarget(Target $target): User
    {
        $this->targets->removeElement($target);
        return $this;
    }

    /**
     * @param Stage $stage
     * @param User $gradingUser
     * @return ArrayCollection|Grade[]
     */
    public function getGrades(Stage $stage, User $gradingUser)
    {
        $grades = new ArrayCollection;
        foreach ($stage->getParticipants() as $participant) {
            // Retrieving grading user participations
            if ($participant->getUsrId() == $gradingUser->getId()) {
                foreach ($participant->getGrades() as $grade) {
                    if ($grade->getGradedUsrId() == $this->id) {
                        $grades->add($grade);
                    }
                }
            }
        }
        return $grades;
    }

    /**
     * @param Stage $stage
     * @return ArrayCollection|Participation[]
     */
    public function getStageParticipations(Stage $stage)
    {
        return $stage->getParticipants()->matching(Criteria::create()->where(Criteria::expr()->eq("usrId", $this->id)));
    }
    //TODO get Role et les autres gros trucs, subordinates


    public function getRoles(): array
    {   

        $roles = [];
        if($this->organization){
            switch($this->organization->getPlan()){
                case 3:
                    $roles[] = 'ROLE_FREE';
                    break;
                case 2:
                    $roles[] = 'ROLE_PREMIUM';
                    break;
                case 1:
                    $roles[] = 'ROLE_ENTERPRISE';
                    break;
            }
        }

        switch($this->role){
            case 4:
                $roles[] = 'ROLE_COLLABORATOR';
                break;
            case 3:
                $roles[] = 'ROLE_ACTIVITY_MANAGER';
                break;
            case 2:
                $roles[] = 'ROLE_ADMIN';
                break;
            case 1:
                $roles[] = 'ROLE_SUPER_ADMIN';
                break;
            case 0:
                $roles[] = 'ROLE_ROOT';
                break;
        }

        return $roles;

    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize([
            $this->getId(),
            $this->getUsername(),
            $this->getEmail(),
            $this->getPassword(),
        ]);
    }

    public function unserialize($serialized)
    {
        [
            $this->id,
            $this->username,
            $this->email,
            $this->password,
        ] = unserialize($serialized, ['allow_classes' => false]);
    }

    public function toArray(): array
    {
        if ($this->position !== null) {
            $posName = $this->position->getName();
        } else {
            $posName = "";
        }

//        $sql =
//            'SELECT par_id
//         FROM activity_user
//         INNER JOIN activity ON activity_user.activity_act_id = activity.act_id
//         WHERE activity.act_status >= :status
//        AND activity_user.user_usr_id = :usrId GROUP BY activity_user.activity_act_id';
//        $pdoStatement = $app['db']->prepare($sql);
//        $pdoStatement->bindValue(':status', 2);
//        $pdoStatement->bindValue(':usrId', $this->id);
//        $pdoStatement->execute();
//        $nbCompletedActivities = count($pdoStatement->fetchAll());

//        $sql =
//            'SELECT par_id
//         FROM activity_user
//         INNER JOIN activity ON activity_user.activity_act_id = activity.act_id
//         WHERE activity.act_status IN (:status_1, :status_2)
//         AND activity_user.user_usr_id = :usrId
//         GROUP BY activity_user.activity_act_id';
//
//        $pdoStatement = $app['db']->prepare($sql);
//        $pdoStatement->bindValue(':status_1', 0);
//        $pdoStatement->bindValue(':status_2', 1);
//        $pdoStatement->bindValue(':usrId', $this->id);
//        $pdoStatement->execute();
//        $nbScheduledActivities = count($pdoStatement->fetchAll());
//
//        //* GET WEIGHT
//        // Weight is defined for internal user (we find associated weight object), whereas for external user it is a property value.
//        $em = MasterController::getEntityManager();
//
//        if ($this->wgtId !== null) {
//            /**
//             * @var EntityRepository
//             */
//            $weightRepository = $em->getRepository(Weight::class);
//            /**
//             * @var Weight
//             */
//            $weight = $weightRepository->find($this->wgtId);
//            $weightValue = $weight ? $weight->getValue() : 0;
//        } else {
//            /**
//             * @var EntityRepository
//             */
//            $externalUserRepository = $em->getRepository(ExternalUser::class);
//            /**
//             * @var ExternalUser
//             */
//            $externalUser = $externalUserRepository->findOneBy(['user' => $this]);
//            $weightValue = $externalUser ? $externalUser->getWeightValue() : 0;
//        }

        return [
            'obj' => $this,
            'id' => $this->id,
            'orgId' => $this->getOrganization()->getId(),
            'organization' => $this->getOrganization(),
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'picture' => $this->picture,
            'weight' => $this->weight?$this->weight->getValue():null,
            'inserted' => $this->inserted,
            'internal' => $this->internal,
            'position' => $posName,
            'role' => $this->role,
            'email' => ($this->email === null) ? 0 : 1,
//            'avgGrade' => ($this->role == 3) ? $this->getAverage($app, false, 'P', 'W', 'A') : $this->getAverage($app, true, 'P', 'W', 'A'),
//            'avgStdDevRatio' => ($this->role == 3) ? $this->getAverage($app, false, 'D', 'W', 'A') : $this->getAverage($app, true, 'D', 'W', 'A'),
//            'nbCompletedActivities' => $nbCompletedActivities,
//            'nbScheduledActivities' => $nbScheduledActivities,
            'lastConnected' => $this->getLastConnected(),
        ];
    }

    public function is_root(): bool
    {
        return $this->role === 4;
    }

    public function is_admin(): bool
    {
        return $this->role === 1 || $this->role === 4;
    }
    public function is_am(): bool
    {
        return $this->role !== 3;
    }

    public function is_collab(): bool
    {
        return $this->role === 3;
    }

     /**
     * @return ArrayCollection|User[]
     */
    public function getSubordinates()
    {
        return $this->subordinates;
    }

    public function addSubordinate(User $user): User
    {
        $this->subordinates->add($user);
        $user->setSuperior($this);
        return $this;
    }

    public function removeSubordinate(User $user): User
    {
        $this->subordinates->removeElement($user);
        return $this;
    }

    /**
    * @return ArrayCollection|Activity[]
    */
    public function getExternalActivities()
    {
        return new ArrayCollection(array_unique($this->getExternalStages()->map(fn(Stage $s) => $s->getActivity())->getValues(), SORT_REGULAR));       
    }

    /**
    * @return ArrayCollection|Stage[]
    */
    public function getExternalStages()
    {
        $externalStages = new ArrayCollection(array_unique($this->participations->filter(fn(Participation $p) => $p->getStage()->getOrganization() != $this->organization)
        ->map(fn(Participation $p) => $p->getStage())->getValues(), SORT_REGULAR));
        if($this->role <= USER::ROLE_ADMIN){
            // If user is admin, we also retrieve stages with legal person involved
            $legalPersonStages = array_unique($this->organization->getParticipations()->filter(fn(Participation $p) => $p->getUser()->isSynthetic() && $p->getStage()->getOrganization() != $this->organization)
            ->map(fn(Participation $p) => $p->getStage())->getValues(), SORT_REGULAR);  
            
            foreach($legalPersonStages as $legalPersonStage){
                $externalStages->add($legalPersonStage);
            }
        }
        return $externalStages;   
    }

    /**
    * @return ArrayCollection|Activity[]
    */
    public function getInternalActivities()
    {
        return new ArrayCollection(array_unique($this->getInternalStages()->map(fn(Stage $s) => $s->getActivity())->getValues(), SORT_REGULAR));
    }

    /**
    * @return ArrayCollection|Stage[]
    */
    public function getInternalStages()
    {
        $internalStages = new ArrayCollection(array_unique($this->participations->filter(fn(Participation $p) => $p->getStage()->getOrganization() == $this->organization)
            ->map(fn(Participation $p) => $p->getStage())->getValues(), SORT_REGULAR));
        if($this->role <= USER::ROLE_ADMIN){
            // We also retrieve stage with organization legal person involved
            $legalPersonStages = array_unique($this->organization->getParticipations()->filter(fn(Participation $p) => $p->getUser()->isSynthetic() && $p->getStage()->getOrganization() == $this->organization)
            ->map(fn(Participation $p) => $p->getStage())->getValues(), SORT_REGULAR);  
            
            foreach($legalPersonStages as $legalPersonStage){
                $internalStages->add($legalPersonStage);
            }
        }
        return $internalStages;   
        
    }

    /**
    * @return ArrayCollection|ElementUpdate[]
    */
    public function getUpdates()
    {
        return $this->updates;
    }

    public function addUpdate(ElementUpdate $update): User
    {
        $this->updates->add($update);
        $update->setUser($this);
        return $this;
    }

    public function removeUpdate(ElementUpdate $update): User
    {
        $this->updates->removeElement($update);
        return $this;
    }

    /**
    * @return ArrayCollection|Mastering[]
    */
    public function getMasterings()
    {
        return $this->masterings;
    }

    public function addMastering(UserMaster $userMaster): self
    {
        $this->masterings->add($userMaster);
        $userMaster->setUser($this);
        return $this;
    }

    public function removeMastering(UserMaster $userMaster): self
    {
        $this->masterings->removeElement($userMaster);
        return $this;
    }

    public function getPicturePath(): string
    {
        if(!$this->picture){
            return "/lib/img/user/no-picture.png";
        } else {

        
            $folder = $this->isSynthetic() && $this->getFirstname() == 'ZZ' ? 'org' : 'user';
            $suffix = $this->picture ?: (
                $this->getOrganization()->getType() == 'C' || $this->getOrganization()->getType() == 'I' || $isIndivIndep ? 'no-picture-i.png' : (
                    !$this->getLastConnected() ? 'virtual-user.png' : 'no-picture.png'
                )
            );
            return "/lib/img/$folder/$suffix";
        }
    }

    /**
    * @return ArrayCollection|User[]
    */
    public function getUserInitiatives()
    {
        return $this->userInitiatives;
    }

    public function addUserInitiative(User $user): self
    {
        $this->userInitiatives->add($user);
        $user->setInitiator($this);
        return $this;
    }

    public function removeUserInitiative(User $user): self
    {
        $this->userInitiatives->removeElement($user);
        return $this;
    }
    /**
    * @return ArrayCollection|Activity[]
    */
    public function getActivityInitiatives()
    {
        return $this->activityInitiatives;
    }

    public function addActivityInitiative(Activity $activity): self
    {
        $this->activityInitiatives->add($activity);
        $activity->setInitiator($this);
        return $this;
    }

    public function removeActivityInitiative(Activity $activity): self
    {
        $this->activityInitiatives->removeElement($activity);
        return $this;
    }
    /**
    * @return ArrayCollection|Answer[]
    */
    public function getAnswerInitiatives()
    {
        return $this->answerInitiatives;
    }

    public function addAnswerInitiative(Answer $answer): self
    {
        $this->answerInitiatives->add($answer);
        $answer->setInitiator($this);
        return $this;
    }

    public function removeAnswerInitiative(Answer $answer): self
    {
        $this->answerInitiatives->removeElement($answer);
        return $this;
    }
    /**
    * @return ArrayCollection|City[]
    */
    public function getCityInitiatives()
    {
        return $this->cityInitiatives;
    }

    public function addCityInitiative(City $city): self
    {
        $this->cityInitiatives->add($city);
        $city->setInitiator($this);
        return $this;
    }

    public function removeCityInitiative(City $city): self
    {
        $this->cityInitiatives->removeElement($city);
        return $this;
    }
    /**
    * @return ArrayCollection|Client[]
    */
    public function getClientInitiatives()
    {
        return $this->clientInitiatives;
    }

    public function addClientInitiative(Client $client): self
    {
        $this->clientInitiatives->add($client);
        $client->setInitiator($this);
        return $this;
    }

    public function removeClientInitiative(Client $client): self
    {
        $this->clientInitiatives->removeElement($client);
        return $this;
    }
    /**
    * @return ArrayCollection|Contact[]
    */
    public function getContactInitiatives()
    {
        return $this->contactInitiatives;
    }

    public function addContactInitiative(Contact $contact): self
    {
        $this->contactInitiatives->add($contact);
        $contact->setInitiator($this);
        return $this;
    }

    public function removeContactInitiative(Contact $contact): self
    {
        $this->contactInitiatives->removeElement($contact);
        return $this;
    }
    /**
    * @return ArrayCollection|Country[]
    */
    public function getCountryInitiatives()
    {
        return $this->countryInitiatives;
    }

    public function addCountryInitiative(Country $country): self
    {
        $this->countryInitiatives->add($country);
        $country->setInitiator($this);
        return $this;
    }

    public function removeCountryInitiative(Country $country): self
    {
        $this->countryInitiatives->removeElement($country);
        return $this;
    }
    /**
    * @return ArrayCollection|Criterion[]
    */
    public function getCriterionInitiatives()
    {
        return $this->criterionInitiatives;
    }

    public function addCriterionInitiative(Criterion $criterion): self
    {
        $this->criterionInitiatives->add($criterion);
        $criterion->setInitiator($this);
        return $this;
    }

    public function removeCriterionInitiative(Criterion $criterion): self
    {
        $this->criterionInitiatives->removeElement($criterion);
        return $this;
    }
    /**
    * @return ArrayCollection|CriterionGroup[]
    */
    public function getCriterionGroupInitiatives()
    {
        return $this->criterionGroupInitiatives;
    }

    public function addCriterionGroupInitiative(CriterionGroup $criterionGroup): self
    {
        $this->criterionGroupInitiatives->add($criterionGroup);
        $criterionGroup->setInitiator($this);
        return $this;
    }

    public function removeCriterionGroupInitiative(CriterionGroup $criterionGroup): self
    {
        $this->criterionGroupInitiatives->removeElement($criterionGroup);
        return $this;
    }
    /**
    * @return ArrayCollection|CriterionName[]
    */
    public function getCriterionNameInitiatives()
    {
        return $this->criterionNameInitiatives;
    }

    public function addCriterionNameInitiative(CriterionName $criterionName): self
    {
        $this->criterionNameInitiatives->add($criterionName);
        $criterionName->setInitiator($this);
        return $this;
    }

    public function removeCriterionNameInitiative(CriterionName $criterionName): self
    {
        $this->criterionNameInitiatives->removeElement($criterionName);
        return $this;
    }
    /**
    * @return ArrayCollection|Decision[]
    */
    public function getDecisionInitiatives()
    {
        return $this->decisionInitiatives;
    }

    public function addDecisionInitiative(Decision $decision): self
    {
        $this->decisionInitiatives->add($decision);
        $decision->setInitiator($this);
        return $this;
    }

    public function removeDecisionInitiative(Decision $decision): self
    {
        $this->decisionInitiatives->removeElement($decision);
        return $this;
    }
    /**
    * @return ArrayCollection|Department[]
    */
    public function getDepartmentInitiatives()
    {
        return $this->departmentInitiatives;
    }

    public function addDepartmentInitiative(Department $department): self
    {
        $this->departmentInitiatives->add($department);
        $department->setInitiator($this);
        return $this;
    }

    public function removeDepartmentInitiative(Department $department): self
    {
        $this->departmentInitiatives->removeElement($department);
        return $this;
    }
    /**
    * @return ArrayCollection|DocumentAuthor[]
    */
    public function getDocumentAuthorInitiatives()
    {
        return $this->documentAuthorInitiatives;
    }

    public function addDocumentAuthorInitiative(DocumentAuthor $documentAuthor): self
    {
        $this->documentAuthorInitiatives->add($documentAuthor);
        $documentAuthor->setInitiator($this);
        return $this;
    }

    public function removeDocumentAuthorInitiative(DocumentAuthor $documentAuthor): self
    {
        $this->documentAuthorInitiatives->removeElement($documentAuthor);
        return $this;
    }
    /**
    * @return ArrayCollection|DynamicTranslation[]
    */
    public function getDynamicTranslationInitiatives()
    {
        return $this->dynamicTranslationInitiatives;
    }

    public function addDynamicTranslationInitiative(DynamicTranslation $dynamicTranslation): self
    {
        $this->dynamicTranslationInitiatives->add($dynamicTranslation);
        $dynamicTranslation->setInitiator($this);
        return $this;
    }

    public function removeDynamicTranslationInitiative(DynamicTranslation $dynamicTranslation): self
    {
        $this->dynamicTranslationInitiatives->removeElement($dynamicTranslation);
        return $this;
    }
    /**
    * @return ArrayCollection|ElementUpdate[]
    */
    public function getElementUpdateInitiatives()
    {
        return $this->elementUpdateInitiatives;
    }

    public function addElementUpdateInitiative(ElementUpdate $elementUpdate): self
    {
        $this->elementUpdateInitiatives->add($elementUpdate);
        $elementUpdate->setInitiator($this);
        return $this;
    }

    public function removeElementUpdateInitiative(ElementUpdate $elementUpdate): self
    {
        $this->elementUpdateInitiatives->removeElement($elementUpdate);
        return $this;
    }
    /**
    * @return ArrayCollection|Event[]
    */
    public function getEventInitiatives()
    {
        return $this->eventInitiatives;
    }

    public function addEventInitiative(Event $event): self
    {
        $this->eventInitiatives->add($event);
        $event->setInitiator($this);
        return $this;
    }

    public function removeEventInitiative(Event $event): self
    {
        $this->eventInitiatives->removeElement($event);
        return $this;
    }
    /**
    * @return ArrayCollection|EventComment[]
    */
    public function getEventCommentInitiatives()
    {
        return $this->eventCommentInitiatives;
    }

    public function addEventCommentInitiative(EventComment $eventComment): self
    {
        $this->eventCommentInitiatives->add($eventComment);
        $eventComment->setInitiator($this);
        return $this;
    }

    public function removeEventCommentInitiative(EventComment $eventComment): self
    {
        $this->eventCommentInitiatives->removeElement($eventComment);
        return $this;
    }
    /**
    * @return ArrayCollection|EventDocument[]
    */
    public function getEventDocumentInitiatives()
    {
        return $this->eventDocumentInitiatives;
    }

    public function addEventDocumentInitiative(EventDocument $eventDocument): self
    {
        $this->eventDocumentInitiatives->add($eventDocument);
        $eventDocument->setInitiator($this);
        return $this;
    }

    public function removeEventDocumentInitiative(EventDocument $eventDocument): self
    {
        $this->eventDocumentInitiatives->removeElement($eventDocument);
        return $this;
    }
    /**
    * @return ArrayCollection|EventGroup[]
    */
    public function getEventGroupInitiatives()
    {
        return $this->eventGroupInitiatives;
    }

    public function addEventGroupInitiative(EventGroup $eventGroup): self
    {
        $this->eventGroupInitiatives->add($eventGroup);
        $eventGroup->setInitiator($this);
        return $this;
    }

    public function removeEventGroupInitiative(EventGroup $eventGroup): self
    {
        $this->eventGroupInitiatives->removeElement($eventGroup);
        return $this;
    }
    /**
    * @return ArrayCollection|EventGroupName[]
    */
    public function getEventGroupNameInitiatives()
    {
        return $this->eventGroupNameInitiatives;
    }

    public function addEventGroupNameInitiative(EventGroupName $eventGroupName): self
    {
        $this->eventGroupNameInitiatives->add($eventGroupName);
        $eventGroupName->setInitiator($this);
        return $this;
    }

    public function removeEventGroupNameInitiative(EventGroupName $eventGroupName): self
    {
        $this->eventGroupNameInitiatives->removeElement($eventGroupName);
        return $this;
    }
    /**
    * @return ArrayCollection|EventName[]
    */
    public function getEventNameInitiatives()
    {
        return $this->eventNameInitiatives;
    }

    public function addEventNameInitiative(EventName $eventName): self
    {
        $this->eventNameInitiatives->add($eventName);
        $eventName->setInitiator($this);
        return $this;
    }

    public function removeEventNameInitiative(EventName $eventName): self
    {
        $this->eventNameInitiatives->removeElement($eventName);
        return $this;
    }
    /**
    * @return ArrayCollection|EventType[]
    */
    public function getEventTypeInitiatives()
    {
        return $this->eventTypeInitiatives;
    }

    public function addEventTypeInitiative(EventType $eventType): self
    {
        $this->eventTypeInitiatives->add($eventType);
        $eventType->setInitiator($this);
        return $this;
    }

    public function removeEventTypeInitiative(EventType $eventType): self
    {
        $this->eventTypeInitiatives->removeElement($eventType);
        return $this;
    }
    /**
    * @return ArrayCollection|ExternalUser[]
    */
    public function getExternalUserInitiatives()
    {
        return $this->externalUserInitiatives;
    }

    public function addExternalUserInitiative(ExternalUser $externalUser): self
    {
        $this->externalUserInitiatives->add($externalUser);
        $externalUser->setInitiator($this);
        return $this;
    }

    public function removeExternalUserInitiative(ExternalUser $externalUser): self
    {
        $this->externalUserInitiatives->removeElement($externalUser);
        return $this;
    }
    /**
    * @return ArrayCollection|GeneratedError[]
    */
    public function getGeneratedErrorInitiatives()
    {
        return $this->generatedErrorInitiatives;
    }

    public function addGeneratedErrorInitiative(GeneratedError $generatedError): self
    {
        $this->generatedErrorInitiatives->add($generatedError);
        $generatedError->setInitiator($this);
        return $this;
    }

    public function removeGeneratedErrorInitiative(GeneratedError $generatedError): self
    {
        $this->generatedErrorInitiatives->removeElement($generatedError);
        return $this;
    }
    /**
    * @return ArrayCollection|GeneratedImage[]
    */
    public function getGeneratedImageInitiatives()
    {
        return $this->generatedImageInitiatives;
    }

    public function addGeneratedImageInitiative(GeneratedImage $generatedImage): self
    {
        $this->generatedImageInitiatives->add($generatedImage);
        $generatedImage->setInitiator($this);
        return $this;
    }

    public function removeGeneratedImageInitiative(GeneratedImage $generatedImage): self
    {
        $this->generatedImageInitiatives->removeElement($generatedImage);
        return $this;
    }
    /**
    * @return ArrayCollection|Grade[]
    */
    public function getGradeInitiatives()
    {
        return $this->gradeInitiatives;
    }

    public function addGradeInitiative(Grade $grade): self
    {
        $this->gradeInitiatives->add($grade);
        $grade->setInitiator($this);
        return $this;
    }

    public function removeGradeInitiative(Grade $grade): self
    {
        $this->gradeInitiatives->removeElement($grade);
        return $this;
    }
    /**
    * @return ArrayCollection|Icon[]
    */
    public function getIconInitiatives()
    {
        return $this->iconInitiatives;
    }

    public function addIconInitiative(Icon $icon): self
    {
        $this->iconInitiatives->add($icon);
        $icon->setInitiator($this);
        return $this;
    }

    public function removeIconInitiative(Icon $icon): self
    {
        $this->iconInitiatives->removeElement($icon);
        return $this;
    }
    /**
    * @return ArrayCollection|InstitutionProcess[]
    */
    public function getInstitutionProcessInitiatives()
    {
        return $this->institutionProcessInitiatives;
    }

    public function addInstitutionProcessInitiative(InstitutionProcess $institutionProcess): self
    {
        $this->institutionProcessInitiatives->add($institutionProcess);
        $institutionProcess->setInitiator($this);
        return $this;
    }

    public function removeInstitutionProcessInitiative(InstitutionProcess $institutionProcess): self
    {
        $this->institutionProcessInitiatives->removeElement($institutionProcess);
        return $this;
    }
    /**
    * @return ArrayCollection|IProcessCriterion[]
    */
    public function getIProcessCriterionInitiatives()
    {
        return $this->IProcessCriterionInitiatives;
    }

    public function addIProcessCriterionInitiative(IProcessCriterion $IProcessCriterion): self
    {
        $this->iProcessCriterionInitiatives->add($IProcessCriterion);
        $IProcessCriterion->setInitiator($this);
        return $this;
    }

    public function removeIProcessCriterionInitiative(IProcessCriterion $IProcessCriterion): self
    {
        $this->iProcessCriterionInitiatives->removeElement($IProcessCriterion);
        return $this;
    }
    /**
    * @return ArrayCollection|IProcessParticipation[]
    */
    public function getIProcessParticipationInitiatives()
    {
        return $this->iProcessParticipationInitiatives;
    }

    public function addIProcessParticipationInitiative(IProcessParticipation $iProcessParticipation): self
    {
        $this->iProcessParticipationInitiatives->add($iProcessParticipation);
        $iProcessParticipation->setInitiator($this);
        return $this;
    }

    public function removeIProcessParticipationInitiative(IProcessParticipation $iProcessParticipation): self
    {
        $this->iProcessParticipationInitiatives->removeElement($iProcessParticipation);
        return $this;
    }
    /**
    * @return ArrayCollection|IProcessStage[]
    */
    public function getIProcessStageInitiatives()
    {
        return $this->iProcessStageInitiatives;
    }

    public function addIProcessStageInitiative(IProcessStage $iProcessStage): self
    {
        $this->iProcessStageInitiatives->add($iProcessStage);
        $iProcessStage->setInitiator($this);
        return $this;
    }

    public function removeIProcessStageInitiative(IProcessStage $iProcessStage): self
    {
        $this->iProcessStageInitiatives->removeElement($iProcessStage);
        return $this;
    }
    /**
    * @return ArrayCollection|Mail[]
    */
    public function getMailInitiatives()
    {
        return $this->mailInitiatives;
    }

    public function addMailInitiative(Mail $mail): self
    {
        $this->mailInitiatives->add($mail);
        $mail->setInitiator($this);
        return $this;
    }

    public function removeMailInitiative(Mail $mail): self
    {
        $this->mailInitiatives->removeElement($mail);
        return $this;
    }
    /**
    * @return ArrayCollection|Member[]
    */
    public function getMemberInitiatives()
    {
        return $this->memberInitiatives;
    }

    public function addMemberInitiative(Member $member): self
    {
        $this->memberInitiatives->add($member);
        $member->setInitiator($this);
        return $this;
    }

    public function removeMemberInitiative(Member $member): self
    {
        $this->memberInitiatives->removeElement($member);
        return $this;
    }
    /**
    * @return ArrayCollection|OptionName[]
    */
    public function getOptionNameInitiatives()
    {
        return $this->optionNameInitiatives;
    }

    public function addOptionNameInitiative(OptionName $optionName): self
    {
        $this->optionNameInitiatives->add($optionName);
        $optionName->setInitiator($this);
        return $this;
    }

    public function removeOptionNameInitiative(OptionName $optionName): self
    {
        $this->optionNameInitiatives->removeElement($optionName);
        return $this;
    }
    /**
    * @return ArrayCollection|Organization[]
    */
    public function getOrganizationInitiatives()
    {
        return $this->organizationInitiatives;
    }

    public function addOrganizationInitiative(Organization $organization): self
    {
        $this->organizationInitiatives->add($organization);
        $organization->setInitiator($this);
        return $this;
    }

    public function removeOrganizationInitiative(Organization $organization): self
    {
        $this->organizationInitiatives->removeElement($organization);
        return $this;
    }
    /**
    * @return ArrayCollection|OrganizationPaymentMethod[]
    */
    public function getOrganizationPaymentMethodInitiatives()
    {
        return $this->organizationPaymentMethodInitiatives;
    }

    public function addOrganizationPaymentMethodInitiative(OrganizationPaymentMethod $organizationPaymentMethod): self
    {
        $this->organizationPaymentMethodInitiatives->add($organizationPaymentMethod);
        $organizationPaymentMethod->setInitiator($this);
        return $this;
    }

    public function removeOrganizationPaymentMethodInitiative(OrganizationPaymentMethod $organizationPaymentMethod): self
    {
        $this->organizationPaymentMethodInitiatives->removeElement($organizationPaymentMethod);
        return $this;
    }
    /**
    * @return ArrayCollection|OrganizationUserOption[]
    */
    public function getOrganizationUserOptionInitiatives()
    {
        return $this->organizationUserOptionInitiatives;
    }

    public function addOrganizationUserOptionInitiative(OrganizationUserOption $organizationUserOption): self
    {
        $this->organizationUserOptionInitiatives->add($organizationUserOption);
        $organizationUserOption->setInitiator($this);
        return $this;
    }

    public function removeOrganizationUserOptionInitiative(OrganizationUserOption $organizationUserOption): self
    {
        $this->organizationUserOptionInitiatives->removeElement($organizationUserOption);
        return $this;
    }
    /**
    * @return ArrayCollection|OTPUser[]
    */
    public function getOTPUserInitiatives()
    {
        return $this->OTPUserInitiatives;
    }

    public function addOTPUserInitiative(OTPUser $OTPUser): self
    {
        $this->OTPUserInitiatives->add($OTPUser);
        $OTPUser->setInitiator($this);
        return $this;
    }

    public function removeOTPUserInitiative(OTPUser $OTPUser): self
    {
        $this->OTPUserInitiatives->removeElement($OTPUser);
        return $this;
    }
    /**
    * @return ArrayCollection|Output[]
    */
    public function getOutputInitiatives()
    {
        return $this->outputInitiatives;
    }

    public function addOutputInitiative(Output $output): self
    {
        $this->outputInitiatives->add($output);
        $output->setInitiator($this);
        return $this;
    }

    public function removeOutputInitiative(Output $output): self
    {
        $this->outputInitiatives->removeElement($output);
        return $this;
    }
    /**
    * @return ArrayCollection|Participation[]
    */
    public function getParticipationInitiatives()
    {
        return $this->participationInitiatives;
    }

    public function addParticipationInitiative(Participation $participation): self
    {
        $this->participationInitiatives->add($participation);
        $participation->setInitiator($this);
        return $this;
    }

    public function removeParticipationInitiative(Participation $participation): self
    {
        $this->participationInitiatives->removeElement($participation);
        return $this;
    }
    /**
    * @return ArrayCollection|Position[]
    */
    public function getPositionInitiatives()
    {
        return $this->positionInitiatives;
    }

    public function addPositionInitiative(Position $position): self
    {
        $this->positionInitiatives->add($position);
        $position->setInitiator($this);
        return $this;
    }

    public function removePositionInitiative(Position $position): self
    {
        $this->positionInitiatives->removeElement($position);
        return $this;
    }
    /**
    * @return ArrayCollection|Process[]
    */
    public function getProcessInitiatives()
    {
        return $this->processInitiatives;
    }

    public function addProcessInitiative(Process $process): self
    {
        $this->processInitiatives->add($process);
        $process->setInitiator($this);
        return $this;
    }

    public function removeProcessInitiative(Process $process): self
    {
        $this->processInitiatives->removeElement($process);
        return $this;
    }
    /**
    * @return ArrayCollection|ProcessCriterion[]
    */
    public function getProcessCriterionInitiatives()
    {
        return $this->processCriterionInitiatives;
    }

    public function addProcessCriterionInitiative(ProcessCriterion $processCriterion): self
    {
        $this->processCriterionInitiatives->add($processCriterion);
        $processCriterion->setInitiator($this);
        return $this;
    }

    public function removeProcessCriterionInitiative(ProcessCriterion $processCriterion): self
    {
        $this->processCriterionInitiatives->removeElement($processCriterion);
        return $this;
    }
    /**
    * @return ArrayCollection|ProcessStage[]
    */
    public function getProcessStageInitiatives()
    {
        return $this->processStageInitiatives;
    }

    public function addProcessStageInitiative(ProcessStage $processStage): self
    {
        $this->processStageInitiatives->add($processStage);
        $processStage->setInitiator($this);
        return $this;
    }

    public function removeProcessStageInitiative(ProcessStage $processStage): self
    {
        $this->processStageInitiatives->removeElement($processStage);
        return $this;
    }
    /**
    * @return ArrayCollection|Ranking[]
    */
    public function getRankingInitiatives()
    {
        return $this->rankingInitiatives;
    }

    public function addRankingInitiative(Ranking $ranking): self
    {
        $this->rankingInitiatives->add($ranking);
        $ranking->setInitiator($this);
        return $this;
    }

    public function removeRankingInitiative(Ranking $ranking): self
    {
        $this->rankingInitiatives->removeElement($ranking);
        return $this;
    }
    /**
    * @return ArrayCollection|RankingHistory[]
    */
    public function getRankingHistoryInitiatives()
    {
        return $this->rankingHistoryInitiatives;
    }

    public function addRankingHistoryInitiative(RankingHistory $rankingHistory): self
    {
        $this->rankingHistoryInitiatives->add($rankingHistory);
        $rankingHistory->setInitiator($this);
        return $this;
    }

    public function removeRankingHistoryInitiative(RankingHistory $rankingHistory): self
    {
        $this->rankingHistoryInitiatives->removeElement($rankingHistory);
        return $this;
    }
    /**
    * @return ArrayCollection|RankingTeam[]
    */
    public function getRankingTeamInitiatives()
    {
        return $this->rankingTeamInitiatives;
    }

    public function addRankingTeamInitiative(RankingTeam $rankingTeam): self
    {
        $this->rankingTeamInitiatives->add($rankingTeam);
        $rankingTeam->setInitiator($this);
        return $this;
    }

    public function removeRankingTeamInitiative(RankingTeam $rankingTeam): self
    {
        $this->rankingTeamInitiatives->removeElement($rankingTeam);
        return $this;
    }
    /**
    * @return ArrayCollection|RankingTeamHistory[]
    */
    public function getRankingTeamHistoryInitiatives()
    {
        return $this->rankingTeamHistoryInitiatives;
    }

    public function addRankingTeamHistoryInitiative(RankingTeamHistory $rankingTeamHistory): self
    {
        $this->rankingTeamHistoryInitiatives->add($rankingTeamHistory);
        $rankingTeamHistory->setInitiator($this);
        return $this;
    }

    public function removeRankingTeamHistoryInitiative(RankingTeamHistory $rankingTeamHistory): self
    {
        $this->rankingTeamHistoryInitiatives->removeElement($rankingTeamHistory);
        return $this;
    }
    /**
    * @return ArrayCollection|Recurring[]
    */
    public function getRecurringInitiatives()
    {
        return $this->recurringInitiatives;
    }

    public function addRecurringInitiative(Recurring $recurring): self
    {
        $this->recurringInitiatives->add($recurring);
        $recurring->setInitiator($this);
        return $this;
    }

    public function removeRecurringInitiative(Recurring $recurring): self
    {
        $this->recurringInitiatives->removeElement($recurring);
        return $this;
    }
    /**
    * @return ArrayCollection|Result[]
    */
    public function getResultInitiatives()
    {
        return $this->resultInitiatives;
    }

    public function addResultInitiative(Result $result): self
    {
        $this->resultInitiatives->add($result);
        $result->setInitiator($this);
        return $this;
    }

    public function removeResultInitiative(Result $result): self
    {
        $this->resultInitiatives->removeElement($result);
        return $this;
    }
    /**
    * @return ArrayCollection|ResultProject[]
    */
    public function getResultProjectInitiatives()
    {
        return $this->resultProjectInitiatives;
    }

    public function addResultProjectInitiative(ResultProject $resultProject): self
    {
        $this->resultProjectInitiatives->add($resultProject);
        $resultProject->setInitiator($this);
        return $this;
    }

    public function removeResultProjectInitiative(ResultProject $resultProject): self
    {
        $this->resultProjectInitiatives->removeElement($resultProject);
        return $this;
    }
    
    /**
    * @return ArrayCollection|ResultTeam[]
    */
    public function getResultTeamInitiatives()
    {
        return $this->resultTeamInitiatives;
    }

    public function addResultTeamInitiative(ResultTeam $resultTeam): self
    {
        $this->resultTeamInitiatives->add($resultTeam);
        $resultTeam->setInitiator($this);
        return $this;
    }

    public function removeResultTeamInitiative(ResultTeam $resultTeam): self
    {
        $this->resultTeamInitiatives->removeElement($resultTeam);
        return $this;
    }
    /**
    * @return ArrayCollection|Stage[]
    */
    public function getStageInitiatives()
    {
        return $this->stageInitiatives;
    }

    public function addStageInitiative(Stage $stage): self
    {
        $this->stageInitiatives->add($stage);
        $stage->setInitiator($this);
        return $this;
    }

    public function removeStageInitiative(Stage $stage): self
    {
        $this->stageInitiatives->removeElement($stage);
        return $this;
    }
    /**
    * @return ArrayCollection|State[]
    */
    public function getStateInitiatives()
    {
        return $this->stateInitiatives;
    }

    public function addStateInitiative(State $state): self
    {
        $this->stateInitiatives->add($state);
        $state->setInitiator($this);
        return $this;
    }

    public function removeStateInitiative(State $state): self
    {
        $this->stateInitiatives->removeElement($state);
        return $this;
    }
    /**
    * @return ArrayCollection|Survey[]
    */
    public function getSurveyInitiatives()
    {
        return $this->surveyInitiatives;
    }

    public function addSurveyInitiative(Survey $survey): self
    {
        $this->surveyInitiatives->add($survey);
        $survey->setInitiator($this);
        return $this;
    }

    public function removeSurveyInitiative(Survey $survey): self
    {
        $this->surveyInitiatives->removeElement($survey);
        return $this;
    }
    /**
    * @return ArrayCollection|SurveyField[]
    */
    public function getSurveyFieldInitiatives()
    {
        return $this->surveyFieldInitiatives;
    }

    public function addSurveyFieldInitiative(SurveyField $surveyField): self
    {
        $this->surveyFieldInitiatives->add($surveyField);
        $surveyField->setInitiator($this);
        return $this;
    }

    public function removeSurveyFieldInitiative(SurveyField $surveyField): self
    {
        $this->surveyFieldInitiatives->removeElement($surveyField);
        return $this;
    }
    /**
    * @return ArrayCollection|SurveyFieldParameter[]
    */
    public function getSurveyFieldParameterInitiatives()
    {
        return $this->surveyFieldParameterInitiatives;
    }

    public function addSurveyFieldParameterInitiative(SurveyFieldParameter $surveyFieldParameter): self
    {
        $this->surveyFieldParameterInitiatives->add($surveyFieldParameter);
        $surveyFieldParameter->setInitiator($this);
        return $this;
    }

    public function removeSurveyFieldParameterInitiative(SurveyFieldParameter $surveyFieldParameter): self
    {
        $this->surveyFieldParameterInitiatives->removeElement($surveyFieldParameter);
        return $this;
    }
    /**
    * @return ArrayCollection|Target[]
    */
    public function getTargetInitiatives()
    {
        return $this->targetInitiatives;
    }

    public function addTargetInitiative(Target $target): self
    {
        $this->targetInitiatives->add($target);
        $target->setInitiator($this);
        return $this;
    }

    public function removeTargetInitiative(Target $target): self
    {
        $this->targetInitiatives->removeElement($target);
        return $this;
    }
    /**
    * @return ArrayCollection|Team[]
    */
    public function getTeamInitiatives()
    {
        return $this->teamInitiatives;
    }

    public function addTeamInitiative(Team $team): self
    {
        $this->teamInitiatives->add($team);
        $team->setInitiator($this);
        return $this;
    }

    public function removeTeamInitiative(Team $team): self
    {
        $this->teamInitiatives->removeElement($team);
        return $this;
    }
    /**
    * @return ArrayCollection|Title[]
    */
    public function getTitleInitiatives()
    {
        return $this->titleInitiatives;
    }

    public function addTitleInitiative(Title $title): self
    {
        $this->titleInitiatives->add($title);
        $title->setInitiator($this);
        return $this;
    }

    public function removeTitleInitiative(Title $title): self
    {
        $this->titleInitiatives->removeElement($title);
        return $this;
    }
    /**
    * @return ArrayCollection|UserGlobal[]
    */
    public function getUserGlobalInitiatives()
    {
        return $this->userGlobalInitiatives;
    }

    public function addUserGlobalInitiative(UserGlobal $userGlobal): self
    {
        $this->userGlobalInitiatives->add($userGlobal);
        $userGlobal->setInitiator($this);
        return $this;
    }

    public function removeUserGlobalInitiative(UserGlobal $userGlobal): self
    {
        $this->userGlobalInitiatives->removeElement($userGlobal);
        return $this;
    }
    /**
    * @return ArrayCollection|UserMaster[]
    */
    public function getUserMasterInitiatives()
    {
        return $this->userMasterInitiatives;
    }

    public function addUserMasterInitiative(UserMaster $userMaster): self
    {
        $this->userMasterInitiatives->add($userMaster);
        $userMaster->setInitiator($this);
        return $this;
    }

    public function removeUserMasterInitiative(UserMaster $userMaster): self
    {
        $this->userMasterInitiatives->removeElement($userMaster);
        return $this;
    }
    /**
    * @return ArrayCollection|Weight[]
    */
    public function getWeightInitiatives()
    {
        return $this->weightInitiatives;
    }

    public function addWeightInitiative(Weight $weight): self
    {
        $this->weightInitiatives->add($weight);
        $weight->setInitiator($this);
        return $this;
    }

    public function removeWeightInitiative(Weight $weight): self
    {
        $this->weightInitiatives->removeElement($weight);
        return $this;
    }
    /**
    * @return ArrayCollection|WorkerExperience[]
    */
    public function getWorkerExperienceInitiatives()
    {
        return $this->workerExperienceInitiatives;
    }

    public function addWorkerExperienceInitiative(WorkerExperience $workerExperience): self
    {
        $this->workerExperienceInitiatives->add($workerExperience);
        $workerExperience->setInitiator($this);
        return $this;
    }

    public function removeWorkerExperienceInitiative(WorkerExperience $workerExperience): self
    {
        $this->workerExperienceInitiatives->removeElement($workerExperience);
        return $this;
    }
    /**
    * @return ArrayCollection|WorkerFirm[]
    */
    public function getWorkerFirmInitiatives()
    {
        return $this->workerFirmInitiatives;
    }

    public function addWorkerFirmInitiative(WorkerFirm $workerFirm): self
    {
        $this->workerFirmInitiatives->add($workerFirm);
        $workerFirm->setInitiator($this);
        return $this;
    }

    public function removeWorkerFirmInitiative(WorkerFirm $workerFirm): self
    {
        $this->workerFirmInitiatives->removeElement($workerFirm);
        return $this;
    }
    /**
    * @return ArrayCollection|WorkerFirmCompetency[]
    */
    public function getWorkerFirmCompetencyInitiatives()
    {
        return $this->workerFirmCompetencyInitiatives;
    }

    public function addWorkerFirmCompetencyInitiative(WorkerFirmCompetency $workerFirmCompetency): self
    {
        $this->workerFirmCompetencyInitiatives->add($workerFirmCompetency);
        $workerFirmCompetency->setInitiator($this);
        return $this;
    }

    public function removeWorkerFirmCompetencyInitiative(WorkerFirmCompetency $workerFirmCompetency): self
    {
        $this->workerFirmCompetencyInitiatives->removeElement($workerFirmCompetency);
        return $this;
    }
    /**
    * @return ArrayCollection|WorkerFirmLocation[]
    */
    public function getWorkerFirmLocationInitiatives()
    {
        return $this->workerFirmLocationInitiatives;
    }

    public function addWorkerFirmLocationInitiative(WorkerFirmLocation $workerFirmLocation): self
    {
        $this->workerFirmLocationInitiatives->add($workerFirmLocation);
        $workerFirmLocation->setInitiator($this);
        return $this;
    }

    public function removeWorkerFirmLocationInitiative(WorkerFirmLocation $workerFirmLocation): self
    {
        $this->workerFirmLocationInitiatives->removeElement($workerFirmLocation);
        return $this;
    }
    /**
    * @return ArrayCollection|WorkerFirmSector[]
    */
    public function getWorkerFirmSectorInitiatives()
    {
        return $this->workerFirmSectorInitiatives;
    }

    public function addWorkerFirmSectorInitiative(WorkerFirmSector $workerFirmSector): self
    {
        $this->workerFirmSectorInitiatives->add($workerFirmSector);
        $workerFirmSector->setInitiator($this);
        return $this;
    }

    public function removeWorkerFirmSectorInitiative(WorkerFirmSector $workerFirmSector): self
    {
        $this->workerFirmSectorInitiatives->removeElement($workerFirmSector);
        return $this;
    }
    /**
    * @return ArrayCollection|WorkerIndividual[]
    */
    public function getWorkerIndividualInitiatives()
    {
        return $this->workerIndividualInitiatives;
    }

    public function addWorkerIndividualInitiative(WorkerIndividual $workerIndividual): self
    {
        $this->workerIndividualInitiatives->add($workerIndividual);
        $workerIndividual->setInitiator($this);
        return $this;
    }

    public function removeWorkerIndividualInitiative(WorkerIndividual $workerIndividual): self
    {
        $this->workerIndividualInitiatives->removeElement($workerIndividual);
        return $this;
    }
    /**
    * @return ArrayCollection|Subscription[]
    */
    public function getSubscriptionInitiatives()
    {
        return $this->subscriptionInitiatives;
    }

    public function addSubscriptionInitiative(Subscription $subscription): self
    {
        $this->subscriptionInitiatives->add($subscription);
        $subscription->setInitiator($this);
        return $this;
    }

    public function removeSubscriptionInitiative(Subscription $subscription): self
    {
        $this->subscriptionInitiatives->removeElement($subscription);
        return $this;
    }
}
