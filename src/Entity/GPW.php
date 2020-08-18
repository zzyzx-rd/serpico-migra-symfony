<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GPWRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=GPWRepository::class)
 */
class GPW extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="gpw_id", type="integer", nullable=true)
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(name="gpw_pid", type="string", length=255, nullable=true)
     */
    public $pid;

    /**
     * @ORM\Column(name="gpw_firm", type="string", length=255, nullable=true)
     */
    public $firm;

    /**
     * @ORM\Column(name="gpw_sector", type="string", length=255, nullable=true)
     */
    public $sector;

    /**
     * @ORM\Column(name="gpw_location", type="string", length=255, nullable=true)
     */
    public $location;

    /**
     * @ORM\Column(name="gpw_cDate", type="integer", nullable=true)
     */
    public $cDate;

    /**
     * @ORM\Column(name="gpw_nb_workers", type="integer", nullable=true)
     */
    public $nb_workers;

    /**
     * @ORM\Column(name="gpw_website", type="string", length=255, nullable=true)
     */
    public $website;

    /**
     * @ORM\Column(name="gpw_genMail", type="string", length=255, nullable=true)
     */
    public $genMail;

    /**
     * @ORM\Column(name="gpw_fb", type="string", length=255, nullable=true)
     */
    public $fb;

    /**
     * @ORM\Column(name="gpw_linkedIn", type="string", length=255, nullable=true)
     */
    public $linkedIn;

    /**
     * @ORM\Column(name="gpw_twitter", type="string", length=255, nullable=true)
     */
    public $twitter;

    /**
     * @ORM\Column(name="gpw_xing", type="string", length=255, nullable=true)
     */
    public $xing;

    /**
     * @ORM\Column(name="gpw_yt", type="string", length=255, nullable=true)
     */
    public $yt;

    /**
     * @ORM\Column(name="gpw_insta", type="string", length=255, nullable=true)
     */
    public $insta;

    /**
     * @ORM\Column(name="gpw_contact_name", type="string", length=255, nullable=true)
     */
    public $contact_name;

    /**
     * @ORM\Column(name="gpw_contact_position", type="string", length=255, nullable=true)
     */
    public $contact_position;

    /**
     * @ORM\Column(name="gpw_contact_mail", type="string", length=255, nullable=true)
     */
    public $contact_mail;

    /**
     * @ORM\Column(name="gpw_contact_tel", type="string", length=255, nullable=true)
     */
    public $contact_tel;

    /**
     * @ORM\Column(name="gpw_values_careers_inspiration", type="string", length=255, nullable=true)
     */
    public $values_careers_inspiration;

    /**
     * @ORM\Column(name="gpw_employee_author", type="string", length=255, nullable=true)
     */
    public $employee_author;

    /**
     * @ORM\Column(name="gpw_employee_position", type="string", length=255, nullable=true)
     */
    public $employee_position;

    /**
     * @ORM\Column(name="gpw_employee_comment", type="string", length=255, nullable=true)
     */
    public $employee_comment;

    /**
     * @ORM\Column(name="gpw_boss_author", type="string", length=255, nullable=true)
     */
    public $boss_author;

    /**
     * @ORM\Column(name="gpw_boss_division", type="string", length=255, nullable=true)
     */
    public $boss_division;

    /**
     * @ORM\Column(name="gpw_boss_comment", type="string", length=255, nullable=true)
     */
    public $boss_comment;

    /**
     * @ORM\Column(name="gpw_award_year_1", type="string", length=255, nullable=true)
     */
    public $award_year_1;

    /**
     * @ORM\Column(name="gpw_award_title_1", type="string", length=255, nullable=true)
     */
    public $award_title_1;

    /**
     * @ORM\Column(name="gpw_award_rnk_1", type="string", length=255, nullable=true)
     */
    public $award_rnk_1;

    /**
     * @ORM\Column(name="gpw_award_year_2", type="string", length=255, nullable=true)
     */
    public $award_year_2;

    /**
     * @ORM\Column(name="gpw_award_title_2", type="string", length=255, nullable=true)
     */
    public $award_title_2;

    /**
     * @ORM\Column(name="gpw_award_rnk_2", type="string", length=255, nullable=true)
     */
    public $award_rnk_2;

    /**
     * @ORM\Column(name="gpw_award_year_3", type="string", length=255, nullable=true)
     */
    public $award_year_3;

    /**
     * @ORM\Column(name="gpw_award_title_3", type="string", length=255, nullable=true)
     */
    public $award_title_3;

    /**
     * @ORM\Column(name="gpw_award_rnk3", type="string", length=255, nullable=true)
     */
    public $award_rnk3;

    /**
     * @ORM\Column(name="gpw_survey_text_1", type="string", length=255, nullable=true)
     */
    public $survey_text_1;

    /**
     * @ORM\Column(name="gpw_survey_res_1", type="string", length=255, nullable=true)
     */
    public $survey_res_1;

    /**
     * @ORM\Column(name="gpw_survey_text_2", type="string", length=255, nullable=true)
     */
    public $survey_text_2;

    /**
     * @ORM\Column(name="gpw_survey_res_2", type="string", length=255, nullable=true)
     */
    public $survey_res_2;

    /**
     * @ORM\Column(name="gpw_survey_text_3", type="string", length=255, nullable=true)
     */
    public $survey_text_3;

    /**
     * @ORM\Column(name="gpw_survey_res_3", type="string", length=255, nullable=true)
     */
    public $survey_res_3;

    /**
     * @ORM\Column(name="gpw_survey_text_4", type="string", length=255, nullable=true)
     */
    public $survey_text_4;

    /**
     * @ORM\Column(name="gpw_survey_res_4", type="string", length=255, nullable=true)
     */
    public $survey_res_4;

    /**
     * @ORM\Column(name="gpw_survey_text_5", type="string", length=255, nullable=true)
     */
    public $survey_text_5;

    /**
     * @ORM\Column(name="gpw_survey_res_5", type="string", length=255, nullable=true)
     */
    public $survey_res_5;

    /**
     * @ORM\Column(name="gpw_survey_text_6", type="string", length=255, nullable=true)
     */
    public $survey_text_6;

    /**
     * @ORM\Column(name="gpw_survey_res_6", type="string", length=255, nullable=true)
     */
    public $survey_res_6;

    /**
     * @ORM\Column(name="gpw_survey_text_7", type="string", length=255, nullable=true)
     */
    public $survey_text_7;

    /**
     * @ORM\Column(name="gpw_survey_res_7", type="string", length=255, nullable=true)
     */
    public $survey_res_7;

    /**
     * @ORM\Column(name="gpw_created_by", type="integer", nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(name="gpw_inserted", type="datetime", nullable=true)
     */
    public $inserted;

    /**
     * GPW constructor.
     * @param int $id
     * @param $gpw_pid
     * @param $gpw_firm
     * @param $gpw_sector
     * @param $gpw_location
     * @param $gpw_cDate
     * @param $gpw_nb_workers
     * @param $gpw_website
     * @param $gpw_genMail
     * @param $gpw_fb
     * @param $gpw_linkedIn
     * @param $gpw_twitter
     * @param $gpw_xing
     * @param $gpw_yt
     * @param $gpw_insta
     * @param $gpw_contact_name
     * @param $gpw_contact_position
     * @param $gpw_contact_mail
     * @param $gpw_contact_tel
     * @param $gpw_values_careers_inspiration
     * @param $gpw_employee_author
     * @param $gpw_employee_position
     * @param $gpw_employee_comment
     * @param $gpw_boss_author
     * @param $gpw_boss_division
     * @param $gpw_boss_comment
     * @param $gpw_award_year_1
     * @param $gpw_award_title_1
     * @param $gpw_award_rnk_1
     * @param $gpw_award_year_2
     * @param $gpw_award_title_2
     * @param $gpw_award_rnk_2
     * @param $gpw_award_year_3
     * @param $gpw_award_title_3
     * @param $gpw_award_rnk3
     * @param $gpw_survey_text_1
     * @param $gpw_survey_res_1
     * @param $gpw_survey_text_2
     * @param $gpw_survey_res_2
     * @param $gpw_survey_text_3
     * @param $gpw_survey_res_3
     * @param $gpw_survey_text_4
     * @param $gpw_survey_res_4
     * @param $gpw_survey_text_5
     * @param $gpw_survey_res_5
     * @param $gpw_survey_text_6
     * @param $gpw_survey_res_6
     * @param $gpw_survey_text_7
     * @param $gpw_survey_res_7
     * @param $gpw_createdBy
     * @param $gpw_inserted
     */
    public function __construct(
        int $id,
        $gpw_pid = null,
        $gpw_firm = null,
        $gpw_sector = null,
        $gpw_location = null,
        $gpw_cDate = null,
        $gpw_nb_workers = null,
        $gpw_website = null,
        $gpw_genMail = null,
        $gpw_fb = null,
        $gpw_linkedIn = null,
        $gpw_twitter = null,
        $gpw_xing = null,
        $gpw_yt = null,
        $gpw_insta = null,
        $gpw_contact_name = null,
        $gpw_contact_position = null,
        $gpw_contact_mail = null,
        $gpw_contact_tel = null,
        $gpw_values_careers_inspiration = null,
        $gpw_employee_author = null,
        $gpw_employee_position = null,
        $gpw_employee_comment = null,
        $gpw_boss_author = null,
        $gpw_boss_division = null,
        $gpw_boss_comment = null,
        $gpw_award_year_1 = null,
        $gpw_award_title_1 = null,
        $gpw_award_rnk_1 = null,
        $gpw_award_year_2 = null,
        $gpw_award_title_2 = null,
        $gpw_award_rnk_2 = null,
        $gpw_award_year_3 = null,
        $gpw_award_title_3 = null,
        $gpw_award_rnk3 = null,
        $gpw_survey_text_1 = null,
        $gpw_survey_res_1 = null,
        $gpw_survey_text_2 = null,
        $gpw_survey_res_2 = null,
        $gpw_survey_text_3 = null,
        $gpw_survey_res_3 = null,
        $gpw_survey_text_4 = null,
        $gpw_survey_res_4 = null,
        $gpw_survey_text_5 = null,
        $gpw_survey_res_5 = null,
        $gpw_survey_text_6 = null,
        $gpw_survey_res_6 = null,
        $gpw_survey_text_7 = null,
        $gpw_survey_res_7 = null,
        $gpw_createdBy = null,
        $gpw_inserted = null)
    {
        parent::__construct($id, $gpw_createdBy, new DateTime());
        $this->pid = $gpw_pid;
        $this->firm = $gpw_firm;
        $this->sector = $gpw_sector;
        $this->location = $gpw_location;
        $this->cDate = $gpw_cDate;
        $this->nb_workers = $gpw_nb_workers;
        $this->website = $gpw_website;
        $this->genMail = $gpw_genMail;
        $this->fb = $gpw_fb;
        $this->linkedIn = $gpw_linkedIn;
        $this->twitter = $gpw_twitter;
        $this->xing = $gpw_xing;
        $this->yt = $gpw_yt;
        $this->insta = $gpw_insta;
        $this->contact_name = $gpw_contact_name;
        $this->contact_position = $gpw_contact_position;
        $this->contact_mail = $gpw_contact_mail;
        $this->contact_tel = $gpw_contact_tel;
        $this->values_careers_inspiration = $gpw_values_careers_inspiration;
        $this->employee_author = $gpw_employee_author;
        $this->employee_position = $gpw_employee_position;
        $this->employee_comment = $gpw_employee_comment;
        $this->boss_author = $gpw_boss_author;
        $this->boss_division = $gpw_boss_division;
        $this->boss_comment = $gpw_boss_comment;
        $this->award_year_1 = $gpw_award_year_1;
        $this->award_title_1 = $gpw_award_title_1;
        $this->award_rnk_1 = $gpw_award_rnk_1;
        $this->award_year_2 = $gpw_award_year_2;
        $this->award_title_2 = $gpw_award_title_2;
        $this->award_rnk_2 = $gpw_award_rnk_2;
        $this->award_year_3 = $gpw_award_year_3;
        $this->award_title_3 = $gpw_award_title_3;
        $this->award_rnk3 = $gpw_award_rnk3;
        $this->survey_text_1 = $gpw_survey_text_1;
        $this->survey_res_1 = $gpw_survey_res_1;
        $this->survey_text_2 = $gpw_survey_text_2;
        $this->survey_res_2 = $gpw_survey_res_2;
        $this->survey_text_3 = $gpw_survey_text_3;
        $this->survey_res_3 = $gpw_survey_res_3;
        $this->survey_text_4 = $gpw_survey_text_4;
        $this->survey_res_4 = $gpw_survey_res_4;
        $this->survey_text_5 = $gpw_survey_text_5;
        $this->survey_res_5 = $gpw_survey_res_5;
        $this->survey_text_6 = $gpw_survey_text_6;
        $this->survey_res_6 = $gpw_survey_res_6;
        $this->survey_text_7 = $gpw_survey_text_7;
        $this->survey_res_7 = $gpw_survey_res_7;
        $this->inserted = $gpw_inserted;
    }

    public function getPid(): ?string
    {
        return $this->pid;
    }

    public function setPid(string $gpw_pid): self
    {
        $this->pid = $gpw_pid;

        return $this;
    }

    public function getFirm(): ?string
    {
        return $this->firm;
    }

    public function setFirm(string $gpw_firm): self
    {
        $this->firm = $gpw_firm;

        return $this;
    }

    public function getSector(): ?string
    {
        return $this->sector;
    }

    public function setSector(string $gpw_sector): self
    {
        $this->sector = $gpw_sector;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $gpw_location): self
    {
        $this->location = $gpw_location;

        return $this;
    }

    public function getCDate(): ?int
    {
        return $this->cDate;
    }

    public function setCDate(int $gpw_cDate): self
    {
        $this->cDate = $gpw_cDate;

        return $this;
    }

    public function getNbWorkers(): ?int
    {
        return $this->nb_workers;
    }

    public function setNbWorkers(int $gpw_nb_workers): self
    {
        $this->nb_workers = $gpw_nb_workers;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $gpw_website): self
    {
        $this->website = $gpw_website;

        return $this;
    }

    public function getGenMail(): ?string
    {
        return $this->genMail;
    }

    public function setGenMail(string $gpw_genMail): self
    {
        $this->genMail = $gpw_genMail;

        return $this;
    }

    public function getFb(): ?string
    {
        return $this->fb;
    }

    public function setFb(string $gpw_fb): self
    {
        $this->fb = $gpw_fb;

        return $this;
    }

    public function getLinkedIn(): ?string
    {
        return $this->linkedIn;
    }

    public function setLinkedIn(string $gpw_linkedIn): self
    {
        $this->linkedIn = $gpw_linkedIn;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(string $gpw_twitter): self
    {
        $this->twitter = $gpw_twitter;

        return $this;
    }

    public function getXing(): ?string
    {
        return $this->xing;
    }

    public function setXing(string $gpw_xing): self
    {
        $this->xing = $gpw_xing;

        return $this;
    }

    public function getYt(): ?string
    {
        return $this->yt;
    }

    public function setYt(string $gpw_yt): self
    {
        $this->yt = $gpw_yt;

        return $this;
    }

    public function getInsta(): ?string
    {
        return $this->insta;
    }

    public function setInsta(string $gpw_insta): self
    {
        $this->insta = $gpw_insta;

        return $this;
    }

    public function getContactName(): ?string
    {
        return $this->contact_name;
    }

    public function setContactName(string $gpw_contact_name): self
    {
        $this->contact_name = $gpw_contact_name;

        return $this;
    }

    public function getContactPosition(): ?string
    {
        return $this->contact_position;
    }

    public function setContactPosition(string $gpw_contact_position): self
    {
        $this->contact_position = $gpw_contact_position;

        return $this;
    }

    public function getContactMail(): ?string
    {
        return $this->contact_mail;
    }

    public function setContactMail(string $gpw_contact_mail): self
    {
        $this->contact_mail = $gpw_contact_mail;

        return $this;
    }

    public function getContactTel(): ?string
    {
        return $this->contact_tel;
    }

    public function setContactTel(string $gpw_contact_tel): self
    {
        $this->contact_tel = $gpw_contact_tel;

        return $this;
    }

    public function getValuesCareersInspiration(): ?string
    {
        return $this->values_careers_inspiration;
    }

    public function setValuesCareersInspiration(string $gpw_values_careers_inspiration): self
    {
        $this->values_careers_inspiration = $gpw_values_careers_inspiration;

        return $this;
    }

    public function getEmployeeAuthor(): ?string
    {
        return $this->employee_author;
    }

    public function setEmployeeAuthor(string $gpw_employee_author): self
    {
        $this->employee_author = $gpw_employee_author;

        return $this;
    }

    public function getEmployeePosition(): ?string
    {
        return $this->employee_position;
    }

    public function setEmployeePosition(string $gpw_employee_position): self
    {
        $this->employee_position = $gpw_employee_position;

        return $this;
    }

    public function getEmployeeComment(): ?string
    {
        return $this->employee_comment;
    }

    public function setEmployeeComment(string $gpw_employee_comment): self
    {
        $this->employee_comment = $gpw_employee_comment;

        return $this;
    }

    public function getBossAuthor(): ?string
    {
        return $this->boss_author;
    }

    public function setBossAuthor(string $gpw_boss_author): self
    {
        $this->boss_author = $gpw_boss_author;

        return $this;
    }

    public function getBossDivision(): ?string
    {
        return $this->boss_division;
    }

    public function setBossDivision(string $gpw_boss_division): self
    {
        $this->boss_division = $gpw_boss_division;

        return $this;
    }

    public function getBossComment(): ?string
    {
        return $this->boss_comment;
    }

    public function setBossComment(string $gpw_boss_comment): self
    {
        $this->boss_comment = $gpw_boss_comment;

        return $this;
    }

    public function getAwardyear1(): ?string
    {
        return $this->award_year_1;
    }

    public function setAwardyear1(string $gpw_award_year_1): self
    {
        $this->award_year_1 = $gpw_award_year_1;

        return $this;
    }

    public function getAwardTitle1(): ?string
    {
        return $this->award_title_1;
    }

    public function setAwardTitle1(string $gpw_award_title_1): self
    {
        $this->award_title_1 = $gpw_award_title_1;

        return $this;
    }

    public function getAwardRnk1(): ?string
    {
        return $this->award_rnk_1;
    }

    public function setAwardRnk1(string $gpw_award_rnk_1): self
    {
        $this->award_rnk_1 = $gpw_award_rnk_1;

        return $this;
    }

    public function getAwardYear2(): ?string
    {
        return $this->award_year_2;
    }

    public function setAwardYear2(string $gpw_award_year_2): self
    {
        $this->award_year_2 = $gpw_award_year_2;

        return $this;
    }

    public function getAwardTitle2(): ?string
    {
        return $this->award_title_2;
    }

    public function setAwardTitle2(string $gpw_award_title_2): self
    {
        $this->award_title_2 = $gpw_award_title_2;

        return $this;
    }

    public function getAwardRnk2(): ?string
    {
        return $this->award_rnk_2;
    }

    public function setAwardRnk2(string $gpw_award_rnk_2): self
    {
        $this->award_rnk_2 = $gpw_award_rnk_2;

        return $this;
    }

    public function getAwardYear3(): ?string
    {
        return $this->award_year_3;
    }

    public function setAwardYear3(string $gpw_award_year_3): self
    {
        $this->award_year_3 = $gpw_award_year_3;

        return $this;
    }

    public function getAwardTitle3(): ?string
    {
        return $this->award_title_3;
    }

    public function setAwardTitle3(string $gpw_award_title_3): self
    {
        $this->award_title_3 = $gpw_award_title_3;

        return $this;
    }

    public function getAwardRnk3(): ?string
    {
        return $this->award_rnk3;
    }

    public function setAwardRnk3(string $gpw_award_rnk3): self
    {
        $this->award_rnk3 = $gpw_award_rnk3;

        return $this;
    }

    public function getSurveyText1(): ?string
    {
        return $this->survey_text_1;
    }

    public function setSurveyText1(string $gpw_survey_text_1): self
    {
        $this->survey_text_1 = $gpw_survey_text_1;

        return $this;
    }

    public function getSurveyRes1(): ?string
    {
        return $this->survey_res_1;
    }

    public function setSurveyRes1(string $gpw_survey_res_1): self
    {
        $this->survey_res_1 = $gpw_survey_res_1;

        return $this;
    }

    public function getSurveyText2(): ?string
    {
        return $this->survey_text_2;
    }

    public function setSurveyText2(string $gpw_survey_text_2): self
    {
        $this->survey_text_2 = $gpw_survey_text_2;

        return $this;
    }

    public function getSurveyRes2(): ?string
    {
        return $this->survey_res_2;
    }

    public function setSurveyRes2(string $gpw_survey_res_2): self
    {
        $this->survey_res_2 = $gpw_survey_res_2;

        return $this;
    }

    public function getSurveyText3(): ?string
    {
        return $this->survey_text_3;
    }

    public function setSurveyText3(string $gpw_survey_text_3): self
    {
        $this->survey_text_3 = $gpw_survey_text_3;

        return $this;
    }

    public function getSurveyRes3(): ?string
    {
        return $this->survey_res_3;
    }

    public function setSurveyRes3(string $gpw_survey_res_3): self
    {
        $this->survey_res_3 = $gpw_survey_res_3;

        return $this;
    }

    public function getSurveyText4(): ?string
    {
        return $this->survey_text_4;
    }

    public function setSurveyText4(string $gpw_survey_text_4): self
    {
        $this->survey_text_4 = $gpw_survey_text_4;

        return $this;
    }

    public function getSurveyRes4(): ?string
    {
        return $this->survey_res_4;
    }

    public function setSurveyRes4(string $gpw_survey_res_4): self
    {
        $this->survey_res_4 = $gpw_survey_res_4;

        return $this;
    }

    public function getSurveyText5(): ?string
    {
        return $this->survey_text_5;
    }

    public function setSurveyText5(string $gpw_survey_text_5): self
    {
        $this->survey_text_5 = $gpw_survey_text_5;

        return $this;
    }

    public function getSurveyRes5(): ?string
    {
        return $this->survey_res_5;
    }

    public function setSurveyRes5(string $gpw_survey_res_5): self
    {
        $this->survey_res_5 = $gpw_survey_res_5;

        return $this;
    }

    public function getSurveyText6(): ?string
    {
        return $this->survey_text_6;
    }

    public function setSurveyText6(string $gpw_survey_text_6): self
    {
        $this->survey_text_6 = $gpw_survey_text_6;

        return $this;
    }

    public function getSurveyRes6(): ?string
    {
        return $this->survey_res_6;
    }

    public function setSurveyRes6(string $gpw_survey_res_6): self
    {
        $this->survey_res_6 = $gpw_survey_res_6;

        return $this;
    }

    public function getSurveyText7(): ?string
    {
        return $this->survey_text_7;
    }

    public function setSurveyText7(string $gpw_survey_text_7): self
    {
        $this->survey_text_7 = $gpw_survey_text_7;

        return $this;
    }

    public function getSurveyRes7(): ?string
    {
        return $this->survey_res_7;
    }

    public function setSurveyRes7(string $gpw_survey_res_7): self
    {
        $this->survey_res_7 = $gpw_survey_res_7;

        return $this;
    }


    public function setInserted(DateTimeInterface $gpw_inserted): self
    {
        $this->inserted = $gpw_inserted;

        return $this;
    }
}
