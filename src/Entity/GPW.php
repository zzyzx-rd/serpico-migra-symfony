<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GPWRepository;
use DateTime;
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
     * @ORM\Column(name="gpw_id", type="integer")
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_pid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_firm;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_sector;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_location;

    /**
     * @ORM\Column(type="integer")
     */
    public $gpw_cDate;

    /**
     * @ORM\Column(type="integer")
     */
    public $gpw_nb_workers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_website;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_genMail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_fb;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_linkedIn;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_twitter;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_xing;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_yt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_insta;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_contact_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_contact_position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_contact_mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_contact_tel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_values_careers_inspiration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_employee_author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_employee_position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_employee_comment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_boss_author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_boss_division;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_boss_comment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_award_year_1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_award_title_1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_award_rnk_1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_award_year_2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_award_title_2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_award_rnk_2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_award_year_3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_award_title_3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_award_rnk3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_survey_text_1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_survey_res_1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_survey_text_2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_survey_res_2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_survey_text_3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_survey_res_3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_survey_text_4;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_survey_res_4;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_survey_text_5;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_survey_res_5;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_survey_text_6;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_survey_res_6;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_survey_text_7;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $gpw_survey_res_7;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $gpw_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $gpw_inserted;

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
        $this->gpw_pid = $gpw_pid;
        $this->gpw_firm = $gpw_firm;
        $this->gpw_sector = $gpw_sector;
        $this->gpw_location = $gpw_location;
        $this->gpw_cDate = $gpw_cDate;
        $this->gpw_nb_workers = $gpw_nb_workers;
        $this->gpw_website = $gpw_website;
        $this->gpw_genMail = $gpw_genMail;
        $this->gpw_fb = $gpw_fb;
        $this->gpw_linkedIn = $gpw_linkedIn;
        $this->gpw_twitter = $gpw_twitter;
        $this->gpw_xing = $gpw_xing;
        $this->gpw_yt = $gpw_yt;
        $this->gpw_insta = $gpw_insta;
        $this->gpw_contact_name = $gpw_contact_name;
        $this->gpw_contact_position = $gpw_contact_position;
        $this->gpw_contact_mail = $gpw_contact_mail;
        $this->gpw_contact_tel = $gpw_contact_tel;
        $this->gpw_values_careers_inspiration = $gpw_values_careers_inspiration;
        $this->gpw_employee_author = $gpw_employee_author;
        $this->gpw_employee_position = $gpw_employee_position;
        $this->gpw_employee_comment = $gpw_employee_comment;
        $this->gpw_boss_author = $gpw_boss_author;
        $this->gpw_boss_division = $gpw_boss_division;
        $this->gpw_boss_comment = $gpw_boss_comment;
        $this->gpw_award_year_1 = $gpw_award_year_1;
        $this->gpw_award_title_1 = $gpw_award_title_1;
        $this->gpw_award_rnk_1 = $gpw_award_rnk_1;
        $this->gpw_award_year_2 = $gpw_award_year_2;
        $this->gpw_award_title_2 = $gpw_award_title_2;
        $this->gpw_award_rnk_2 = $gpw_award_rnk_2;
        $this->gpw_award_year_3 = $gpw_award_year_3;
        $this->gpw_award_title_3 = $gpw_award_title_3;
        $this->gpw_award_rnk3 = $gpw_award_rnk3;
        $this->gpw_survey_text_1 = $gpw_survey_text_1;
        $this->gpw_survey_res_1 = $gpw_survey_res_1;
        $this->gpw_survey_text_2 = $gpw_survey_text_2;
        $this->gpw_survey_res_2 = $gpw_survey_res_2;
        $this->gpw_survey_text_3 = $gpw_survey_text_3;
        $this->gpw_survey_res_3 = $gpw_survey_res_3;
        $this->gpw_survey_text_4 = $gpw_survey_text_4;
        $this->gpw_survey_res_4 = $gpw_survey_res_4;
        $this->gpw_survey_text_5 = $gpw_survey_text_5;
        $this->gpw_survey_res_5 = $gpw_survey_res_5;
        $this->gpw_survey_text_6 = $gpw_survey_text_6;
        $this->gpw_survey_res_6 = $gpw_survey_res_6;
        $this->gpw_survey_text_7 = $gpw_survey_text_7;
        $this->gpw_survey_res_7 = $gpw_survey_res_7;
        $this->gpw_inserted = $gpw_inserted;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPid(): ?string
    {
        return $this->gpw_pid;
    }

    public function setPid(string $gpw_pid): self
    {
        $this->gpw_pid = $gpw_pid;

        return $this;
    }

    public function getFirm(): ?string
    {
        return $this->gpw_firm;
    }

    public function setFirm(string $gpw_firm): self
    {
        $this->gpw_firm = $gpw_firm;

        return $this;
    }

    public function getSector(): ?string
    {
        return $this->gpw_sector;
    }

    public function setSector(string $gpw_sector): self
    {
        $this->gpw_sector = $gpw_sector;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->gpw_location;
    }

    public function setLocation(string $gpw_location): self
    {
        $this->gpw_location = $gpw_location;

        return $this;
    }

    public function getCDate(): ?int
    {
        return $this->gpw_cDate;
    }

    public function setCDate(int $gpw_cDate): self
    {
        $this->gpw_cDate = $gpw_cDate;

        return $this;
    }

    public function getNbWorkers(): ?int
    {
        return $this->gpw_nb_workers;
    }

    public function setNbWorkers(int $gpw_nb_workers): self
    {
        $this->gpw_nb_workers = $gpw_nb_workers;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->gpw_website;
    }

    public function setWebsite(string $gpw_website): self
    {
        $this->gpw_website = $gpw_website;

        return $this;
    }

    public function getGenMail(): ?string
    {
        return $this->gpw_genMail;
    }

    public function setGenMail(string $gpw_genMail): self
    {
        $this->gpw_genMail = $gpw_genMail;

        return $this;
    }

    public function getFb(): ?string
    {
        return $this->gpw_fb;
    }

    public function setFb(string $gpw_fb): self
    {
        $this->gpw_fb = $gpw_fb;

        return $this;
    }

    public function getLinkedIn(): ?string
    {
        return $this->gpw_linkedIn;
    }

    public function setLinkedIn(string $gpw_linkedIn): self
    {
        $this->gpw_linkedIn = $gpw_linkedIn;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->gpw_twitter;
    }

    public function setTwitter(string $gpw_twitter): self
    {
        $this->gpw_twitter = $gpw_twitter;

        return $this;
    }

    public function getXing(): ?string
    {
        return $this->gpw_xing;
    }

    public function setXing(string $gpw_xing): self
    {
        $this->gpw_xing = $gpw_xing;

        return $this;
    }

    public function getYt(): ?string
    {
        return $this->gpw_yt;
    }

    public function setYt(string $gpw_yt): self
    {
        $this->gpw_yt = $gpw_yt;

        return $this;
    }

    public function getInsta(): ?string
    {
        return $this->gpw_insta;
    }

    public function setInsta(string $gpw_insta): self
    {
        $this->gpw_insta = $gpw_insta;

        return $this;
    }

    public function getContactName(): ?string
    {
        return $this->gpw_contact_name;
    }

    public function setContactName(string $gpw_contact_name): self
    {
        $this->gpw_contact_name = $gpw_contact_name;

        return $this;
    }

    public function getContactPosition(): ?string
    {
        return $this->gpw_contact_position;
    }

    public function setContactPosition(string $gpw_contact_position): self
    {
        $this->gpw_contact_position = $gpw_contact_position;

        return $this;
    }

    public function getContactMail(): ?string
    {
        return $this->gpw_contact_mail;
    }

    public function setContactMail(string $gpw_contact_mail): self
    {
        $this->gpw_contact_mail = $gpw_contact_mail;

        return $this;
    }

    public function getContactTel(): ?string
    {
        return $this->gpw_contact_tel;
    }

    public function setContactTel(string $gpw_contact_tel): self
    {
        $this->gpw_contact_tel = $gpw_contact_tel;

        return $this;
    }

    public function getValuesCareersInspiration(): ?string
    {
        return $this->gpw_values_careers_inspiration;
    }

    public function setValuesCareersInspiration(string $gpw_values_careers_inspiration): self
    {
        $this->gpw_values_careers_inspiration = $gpw_values_careers_inspiration;

        return $this;
    }

    public function getEmployeeAuthor(): ?string
    {
        return $this->gpw_employee_author;
    }

    public function setEmployeeAuthor(string $gpw_employee_author): self
    {
        $this->gpw_employee_author = $gpw_employee_author;

        return $this;
    }

    public function getEmployeePosition(): ?string
    {
        return $this->gpw_employee_position;
    }

    public function setEmployeePosition(string $gpw_employee_position): self
    {
        $this->gpw_employee_position = $gpw_employee_position;

        return $this;
    }

    public function getEmployeeComment(): ?string
    {
        return $this->gpw_employee_comment;
    }

    public function setEmployeeComment(string $gpw_employee_comment): self
    {
        $this->gpw_employee_comment = $gpw_employee_comment;

        return $this;
    }

    public function getBossAuthor(): ?string
    {
        return $this->gpw_boss_author;
    }

    public function setBossAuthor(string $gpw_boss_author): self
    {
        $this->gpw_boss_author = $gpw_boss_author;

        return $this;
    }

    public function getBossDivision(): ?string
    {
        return $this->gpw_boss_division;
    }

    public function setBossDivision(string $gpw_boss_division): self
    {
        $this->gpw_boss_division = $gpw_boss_division;

        return $this;
    }

    public function getBossComment(): ?string
    {
        return $this->gpw_boss_comment;
    }

    public function setBossComment(string $gpw_boss_comment): self
    {
        $this->gpw_boss_comment = $gpw_boss_comment;

        return $this;
    }

    public function getAwardyear1(): ?string
    {
        return $this->gpw_award_year_1;
    }

    public function setAwardyear1(string $gpw_award_year_1): self
    {
        $this->gpw_award_year_1 = $gpw_award_year_1;

        return $this;
    }

    public function getAwardTitle1(): ?string
    {
        return $this->gpw_award_title_1;
    }

    public function setAwardTitle1(string $gpw_award_title_1): self
    {
        $this->gpw_award_title_1 = $gpw_award_title_1;

        return $this;
    }

    public function getAwardRnk1(): ?string
    {
        return $this->gpw_award_rnk_1;
    }

    public function setAwardRnk1(string $gpw_award_rnk_1): self
    {
        $this->gpw_award_rnk_1 = $gpw_award_rnk_1;

        return $this;
    }

    public function getAwardYear2(): ?string
    {
        return $this->gpw_award_year_2;
    }

    public function setAwardYear2(string $gpw_award_year_2): self
    {
        $this->gpw_award_year_2 = $gpw_award_year_2;

        return $this;
    }

    public function getAwardTitle2(): ?string
    {
        return $this->gpw_award_title_2;
    }

    public function setAwardTitle2(string $gpw_award_title_2): self
    {
        $this->gpw_award_title_2 = $gpw_award_title_2;

        return $this;
    }

    public function getAwardRnk2(): ?string
    {
        return $this->gpw_award_rnk_2;
    }

    public function setAwardRnk2(string $gpw_award_rnk_2): self
    {
        $this->gpw_award_rnk_2 = $gpw_award_rnk_2;

        return $this;
    }

    public function getAwardYear3(): ?string
    {
        return $this->gpw_award_year_3;
    }

    public function setAwardYear3(string $gpw_award_year_3): self
    {
        $this->gpw_award_year_3 = $gpw_award_year_3;

        return $this;
    }

    public function getAwardTitle3(): ?string
    {
        return $this->gpw_award_title_3;
    }

    public function setAwardTitle3(string $gpw_award_title_3): self
    {
        $this->gpw_award_title_3 = $gpw_award_title_3;

        return $this;
    }

    public function getAwardRnk3(): ?string
    {
        return $this->gpw_award_rnk3;
    }

    public function setAwardRnk3(string $gpw_award_rnk3): self
    {
        $this->gpw_award_rnk3 = $gpw_award_rnk3;

        return $this;
    }

    public function getSurveyText1(): ?string
    {
        return $this->gpw_survey_text_1;
    }

    public function setSurveyText1(string $gpw_survey_text_1): self
    {
        $this->gpw_survey_text_1 = $gpw_survey_text_1;

        return $this;
    }

    public function getSurveyRes1(): ?string
    {
        return $this->gpw_survey_res_1;
    }

    public function setSurveyRes1(string $gpw_survey_res_1): self
    {
        $this->gpw_survey_res_1 = $gpw_survey_res_1;

        return $this;
    }

    public function getSurveyText2(): ?string
    {
        return $this->gpw_survey_text_2;
    }

    public function setSurveyText2(string $gpw_survey_text_2): self
    {
        $this->gpw_survey_text_2 = $gpw_survey_text_2;

        return $this;
    }

    public function getSurveyRes2(): ?string
    {
        return $this->gpw_survey_res_2;
    }

    public function setSurveyRes2(string $gpw_survey_res_2): self
    {
        $this->gpw_survey_res_2 = $gpw_survey_res_2;

        return $this;
    }

    public function getSurveyText3(): ?string
    {
        return $this->gpw_survey_text_3;
    }

    public function setSurveyText3(string $gpw_survey_text_3): self
    {
        $this->gpw_survey_text_3 = $gpw_survey_text_3;

        return $this;
    }

    public function getSurveyRes3(): ?string
    {
        return $this->gpw_survey_res_3;
    }

    public function setSurveyRes3(string $gpw_survey_res_3): self
    {
        $this->gpw_survey_res_3 = $gpw_survey_res_3;

        return $this;
    }

    public function getSurveyText4(): ?string
    {
        return $this->gpw_survey_text_4;
    }

    public function setSurveyText4(string $gpw_survey_text_4): self
    {
        $this->gpw_survey_text_4 = $gpw_survey_text_4;

        return $this;
    }

    public function getSurveyRes4(): ?string
    {
        return $this->gpw_survey_res_4;
    }

    public function setSurveyRes4(string $gpw_survey_res_4): self
    {
        $this->gpw_survey_res_4 = $gpw_survey_res_4;

        return $this;
    }

    public function getSurveyText5(): ?string
    {
        return $this->gpw_survey_text_5;
    }

    public function setSurveyText5(string $gpw_survey_text_5): self
    {
        $this->gpw_survey_text_5 = $gpw_survey_text_5;

        return $this;
    }

    public function getSurveyRes5(): ?string
    {
        return $this->gpw_survey_res_5;
    }

    public function setSurveyRes5(string $gpw_survey_res_5): self
    {
        $this->gpw_survey_res_5 = $gpw_survey_res_5;

        return $this;
    }

    public function getSurveyText6(): ?string
    {
        return $this->gpw_survey_text_6;
    }

    public function setSurveyText6(string $gpw_survey_text_6): self
    {
        $this->gpw_survey_text_6 = $gpw_survey_text_6;

        return $this;
    }

    public function getSurveyRes6(): ?string
    {
        return $this->gpw_survey_res_6;
    }

    public function setSurveyRes6(string $gpw_survey_res_6): self
    {
        $this->gpw_survey_res_6 = $gpw_survey_res_6;

        return $this;
    }

    public function getSurveyText7(): ?string
    {
        return $this->gpw_survey_text_7;
    }

    public function setSurveyText7(string $gpw_survey_text_7): self
    {
        $this->gpw_survey_text_7 = $gpw_survey_text_7;

        return $this;
    }

    public function getSurveyRes7(): ?string
    {
        return $this->gpw_survey_res_7;
    }

    public function setSurveyRes7(string $gpw_survey_res_7): self
    {
        $this->gpw_survey_res_7 = $gpw_survey_res_7;

        return $this;
    }


    public function getInserted(): ?\DateTimeInterface
    {
        return $this->gpw_inserted;
    }

    public function setInserted(\DateTimeInterface $gpw_inserted): self
    {
        $this->gpw_inserted = $gpw_inserted;

        return $this;
    }
}
