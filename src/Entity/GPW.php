<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GPWRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=GPWRepository::class)
 */
class GPW
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_pid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_firm;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_sector;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_location;

    /**
     * @ORM\Column(type="integer")
     */
    private $gpw_cDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $gpw_nb_workers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_website;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_genMail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_fb;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_linkedIn;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_twitter;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_xing;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_yt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_insta;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_contact_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_contact_position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_contact_mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_contact_tel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_values_careers_inspiration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_employee_author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_employee_position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_employee_comment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_boss_author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_boss_division;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_boss_comment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_award_year_1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_award_title_1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_award_rnk_1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_award_year_2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_award_title_2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_award_rnk_2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_award_year_3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_award_title_3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_award_rnk3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_survey_text_1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_survey_res_1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_survey_text_2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_survey_res_2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_survey_text_3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_survey_res_3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_survey_text_4;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_survey_res_4;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_survey_text_5;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_survey_res_5;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_survey_text_6;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_survey_res_6;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_survey_text_7;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gpw_survey_res_7;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gpw_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $gpw_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGpwPid(): ?string
    {
        return $this->gpw_pid;
    }

    public function setGpwPid(string $gpw_pid): self
    {
        $this->gpw_pid = $gpw_pid;

        return $this;
    }

    public function getGpwFirm(): ?string
    {
        return $this->gpw_firm;
    }

    public function setGpwFirm(string $gpw_firm): self
    {
        $this->gpw_firm = $gpw_firm;

        return $this;
    }

    public function getGpwSector(): ?string
    {
        return $this->gpw_sector;
    }

    public function setGpwSector(string $gpw_sector): self
    {
        $this->gpw_sector = $gpw_sector;

        return $this;
    }

    public function getGpwLocation(): ?string
    {
        return $this->gpw_location;
    }

    public function setGpwLocation(string $gpw_location): self
    {
        $this->gpw_location = $gpw_location;

        return $this;
    }

    public function getGpwCDate(): ?int
    {
        return $this->gpw_cDate;
    }

    public function setGpwCDate(int $gpw_cDate): self
    {
        $this->gpw_cDate = $gpw_cDate;

        return $this;
    }

    public function getGpwNbWorkers(): ?int
    {
        return $this->gpw_nb_workers;
    }

    public function setGpwNbWorkers(int $gpw_nb_workers): self
    {
        $this->gpw_nb_workers = $gpw_nb_workers;

        return $this;
    }

    public function getGpwWebsite(): ?string
    {
        return $this->gpw_website;
    }

    public function setGpwWebsite(string $gpw_website): self
    {
        $this->gpw_website = $gpw_website;

        return $this;
    }

    public function getGpwGenMail(): ?string
    {
        return $this->gpw_genMail;
    }

    public function setGpwGenMail(string $gpw_genMail): self
    {
        $this->gpw_genMail = $gpw_genMail;

        return $this;
    }

    public function getGpwFb(): ?string
    {
        return $this->gpw_fb;
    }

    public function setGpwFb(string $gpw_fb): self
    {
        $this->gpw_fb = $gpw_fb;

        return $this;
    }

    public function getGpwLinkedIn(): ?string
    {
        return $this->gpw_linkedIn;
    }

    public function setGpwLinkedIn(string $gpw_linkedIn): self
    {
        $this->gpw_linkedIn = $gpw_linkedIn;

        return $this;
    }

    public function getGpwTwitter(): ?string
    {
        return $this->gpw_twitter;
    }

    public function setGpwTwitter(string $gpw_twitter): self
    {
        $this->gpw_twitter = $gpw_twitter;

        return $this;
    }

    public function getGpwXing(): ?string
    {
        return $this->gpw_xing;
    }

    public function setGpwXing(string $gpw_xing): self
    {
        $this->gpw_xing = $gpw_xing;

        return $this;
    }

    public function getGpwYt(): ?string
    {
        return $this->gpw_yt;
    }

    public function setGpwYt(string $gpw_yt): self
    {
        $this->gpw_yt = $gpw_yt;

        return $this;
    }

    public function getGpwInsta(): ?string
    {
        return $this->gpw_insta;
    }

    public function setGpwInsta(string $gpw_insta): self
    {
        $this->gpw_insta = $gpw_insta;

        return $this;
    }

    public function getGpwContactName(): ?string
    {
        return $this->gpw_contact_name;
    }

    public function setGpwContactName(string $gpw_contact_name): self
    {
        $this->gpw_contact_name = $gpw_contact_name;

        return $this;
    }

    public function getGpwContactPosition(): ?string
    {
        return $this->gpw_contact_position;
    }

    public function setGpwContactPosition(string $gpw_contact_position): self
    {
        $this->gpw_contact_position = $gpw_contact_position;

        return $this;
    }

    public function getGpwContactMail(): ?string
    {
        return $this->gpw_contact_mail;
    }

    public function setGpwContactMail(string $gpw_contact_mail): self
    {
        $this->gpw_contact_mail = $gpw_contact_mail;

        return $this;
    }

    public function getGpwContactTel(): ?string
    {
        return $this->gpw_contact_tel;
    }

    public function setGpwContactTel(string $gpw_contact_tel): self
    {
        $this->gpw_contact_tel = $gpw_contact_tel;

        return $this;
    }

    public function getGpwValuesCareersInspiration(): ?string
    {
        return $this->gpw_values_careers_inspiration;
    }

    public function setGpwValuesCareersInspiration(string $gpw_values_careers_inspiration): self
    {
        $this->gpw_values_careers_inspiration = $gpw_values_careers_inspiration;

        return $this;
    }

    public function getGpwEmployeeAuthor(): ?string
    {
        return $this->gpw_employee_author;
    }

    public function setGpwEmployeeAuthor(string $gpw_employee_author): self
    {
        $this->gpw_employee_author = $gpw_employee_author;

        return $this;
    }

    public function getGpwEmployeePosition(): ?string
    {
        return $this->gpw_employee_position;
    }

    public function setGpwEmployeePosition(string $gpw_employee_position): self
    {
        $this->gpw_employee_position = $gpw_employee_position;

        return $this;
    }

    public function getGpwEmployeeComment(): ?string
    {
        return $this->gpw_employee_comment;
    }

    public function setGpwEmployeeComment(string $gpw_employee_comment): self
    {
        $this->gpw_employee_comment = $gpw_employee_comment;

        return $this;
    }

    public function getGpwBossAuthor(): ?string
    {
        return $this->gpw_boss_author;
    }

    public function setGpwBossAuthor(string $gpw_boss_author): self
    {
        $this->gpw_boss_author = $gpw_boss_author;

        return $this;
    }

    public function getGpwBossDivision(): ?string
    {
        return $this->gpw_boss_division;
    }

    public function setGpwBossDivision(string $gpw_boss_division): self
    {
        $this->gpw_boss_division = $gpw_boss_division;

        return $this;
    }

    public function getGpwBossComment(): ?string
    {
        return $this->gpw_boss_comment;
    }

    public function setGpwBossComment(string $gpw_boss_comment): self
    {
        $this->gpw_boss_comment = $gpw_boss_comment;

        return $this;
    }

    public function getGpwAwardyear1(): ?string
    {
        return $this->gpw_award_year_1;
    }

    public function setGpwAwardyear1(string $gpw_award_year_1): self
    {
        $this->gpw_award_year_1 = $gpw_award_year_1;

        return $this;
    }

    public function getGpwAwardTitle1(): ?string
    {
        return $this->gpw_award_title_1;
    }

    public function setGpwAwardTitle1(string $gpw_award_title_1): self
    {
        $this->gpw_award_title_1 = $gpw_award_title_1;

        return $this;
    }

    public function getGpwAwardRnk1(): ?string
    {
        return $this->gpw_award_rnk_1;
    }

    public function setGpwAwardRnk1(string $gpw_award_rnk_1): self
    {
        $this->gpw_award_rnk_1 = $gpw_award_rnk_1;

        return $this;
    }

    public function getGpwAwardYear2(): ?string
    {
        return $this->gpw_award_year_2;
    }

    public function setGpwAwardYear2(string $gpw_award_year_2): self
    {
        $this->gpw_award_year_2 = $gpw_award_year_2;

        return $this;
    }

    public function getGpwAwardTitle2(): ?string
    {
        return $this->gpw_award_title_2;
    }

    public function setGpwAwardTitle2(string $gpw_award_title_2): self
    {
        $this->gpw_award_title_2 = $gpw_award_title_2;

        return $this;
    }

    public function getGpwAwardRnk2(): ?string
    {
        return $this->gpw_award_rnk_2;
    }

    public function setGpwAwardRnk2(string $gpw_award_rnk_2): self
    {
        $this->gpw_award_rnk_2 = $gpw_award_rnk_2;

        return $this;
    }

    public function getGpwAwardYear3(): ?string
    {
        return $this->gpw_award_year_3;
    }

    public function setGpwAwardYear3(string $gpw_award_year_3): self
    {
        $this->gpw_award_year_3 = $gpw_award_year_3;

        return $this;
    }

    public function getGpwAwardTitle3(): ?string
    {
        return $this->gpw_award_title_3;
    }

    public function setGpwAwardTitle3(string $gpw_award_title_3): self
    {
        $this->gpw_award_title_3 = $gpw_award_title_3;

        return $this;
    }

    public function getGpwAwardRnk3(): ?string
    {
        return $this->gpw_award_rnk3;
    }

    public function setGpwAwardRnk3(string $gpw_award_rnk3): self
    {
        $this->gpw_award_rnk3 = $gpw_award_rnk3;

        return $this;
    }

    public function getGpwSurveyText1(): ?string
    {
        return $this->gpw_survey_text_1;
    }

    public function setGpwSurveyText1(string $gpw_survey_text_1): self
    {
        $this->gpw_survey_text_1 = $gpw_survey_text_1;

        return $this;
    }

    public function getGpwSurveyRes1(): ?string
    {
        return $this->gpw_survey_res_1;
    }

    public function setGpwSurveyRes1(string $gpw_survey_res_1): self
    {
        $this->gpw_survey_res_1 = $gpw_survey_res_1;

        return $this;
    }

    public function getGpwSurveyText2(): ?string
    {
        return $this->gpw_survey_text_2;
    }

    public function setGpwSurveyText2(string $gpw_survey_text_2): self
    {
        $this->gpw_survey_text_2 = $gpw_survey_text_2;

        return $this;
    }

    public function getGpwSurveyRes2(): ?string
    {
        return $this->gpw_survey_res_2;
    }

    public function setGpwSurveyRes2(string $gpw_survey_res_2): self
    {
        $this->gpw_survey_res_2 = $gpw_survey_res_2;

        return $this;
    }

    public function getGpwSurveyText3(): ?string
    {
        return $this->gpw_survey_text_3;
    }

    public function setGpwSurveyText3(string $gpw_survey_text_3): self
    {
        $this->gpw_survey_text_3 = $gpw_survey_text_3;

        return $this;
    }

    public function getGpwSurveyRes3(): ?string
    {
        return $this->gpw_survey_res_3;
    }

    public function setGpwSurveyRes3(string $gpw_survey_res_3): self
    {
        $this->gpw_survey_res_3 = $gpw_survey_res_3;

        return $this;
    }

    public function getGpwSurveyText4(): ?string
    {
        return $this->gpw_survey_text_4;
    }

    public function setGpwSurveyText4(string $gpw_survey_text_4): self
    {
        $this->gpw_survey_text_4 = $gpw_survey_text_4;

        return $this;
    }

    public function getGpwSurveyRes4(): ?string
    {
        return $this->gpw_survey_res_4;
    }

    public function setGpwSurveyRes4(string $gpw_survey_res_4): self
    {
        $this->gpw_survey_res_4 = $gpw_survey_res_4;

        return $this;
    }

    public function getGpwSurveyText5(): ?string
    {
        return $this->gpw_survey_text_5;
    }

    public function setGpwSurveyText5(string $gpw_survey_text_5): self
    {
        $this->gpw_survey_text_5 = $gpw_survey_text_5;

        return $this;
    }

    public function getGpwSurveyRes5(): ?string
    {
        return $this->gpw_survey_res_5;
    }

    public function setGpwSurveyRes5(string $gpw_survey_res_5): self
    {
        $this->gpw_survey_res_5 = $gpw_survey_res_5;

        return $this;
    }

    public function getGpwSurveyText6(): ?string
    {
        return $this->gpw_survey_text_6;
    }

    public function setGpwSurveyText6(string $gpw_survey_text_6): self
    {
        $this->gpw_survey_text_6 = $gpw_survey_text_6;

        return $this;
    }

    public function getGpwSurveyRes6(): ?string
    {
        return $this->gpw_survey_res_6;
    }

    public function setGpwSurveyRes6(string $gpw_survey_res_6): self
    {
        $this->gpw_survey_res_6 = $gpw_survey_res_6;

        return $this;
    }

    public function getGpwSurveyText7(): ?string
    {
        return $this->gpw_survey_text_7;
    }

    public function setGpwSurveyText7(string $gpw_survey_text_7): self
    {
        $this->gpw_survey_text_7 = $gpw_survey_text_7;

        return $this;
    }

    public function getGpwSurveyRes7(): ?string
    {
        return $this->gpw_survey_res_7;
    }

    public function setGpwSurveyRes7(string $gpw_survey_res_7): self
    {
        $this->gpw_survey_res_7 = $gpw_survey_res_7;

        return $this;
    }

    public function getGpwCreatedBy(): ?int
    {
        return $this->gpw_createdBy;
    }

    public function setGpwCreatedBy(?int $gpw_createdBy): self
    {
        $this->gpw_createdBy = $gpw_createdBy;

        return $this;
    }

    public function getGpwInserted(): ?\DateTimeInterface
    {
        return $this->gpw_inserted;
    }

    public function setGpwInserted(\DateTimeInterface $gpw_inserted): self
    {
        $this->gpw_inserted = $gpw_inserted;

        return $this;
    }
}
