<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ActivityUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ActivityUserRepository::class)
 */
class ActivityUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_usr_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $a_u_status;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $a_u_aw_result;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $a_u_ae_result;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $a_u_re_result;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $a_u_e_dev;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $a_u_w_devratio;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $a_u_e_devratio;

    /**
     * @ORM\Column(type="boolean")
     */
    private $a_u_leader;

    /**
     * @ORM\Column(type="integer")
     */
    private $a_u_type;

    /**
     * @ORM\Column(type="float")
     */
    private $a_u_mWeight;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $a_u_precomment;

    /**
     * @ORM\Column(type="float")
     */
    private $a_u_ivp_bonus;

    /**
     * @ORM\Column(type="float")
     */
    private $a_u_ivp_penalty;

    /**
     * @ORM\Column(type="float")
     */
    private $a_u_of_bonys;

    /**
     * @ORM\Column(type="float")
     */
    private $a_u_of_penalty;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $a_u_mailed;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $a_u_createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $a_u_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $a_u_confirmed;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $a_u_deleted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserUsrId(): ?int
    {
        return $this->user_usr_id;
    }

    public function setUserUsrId(int $user_usr_id): self
    {
        $this->user_usr_id = $user_usr_id;

        return $this;
    }

    public function getAUStatus(): ?int
    {
        return $this->a_u_status;
    }

    public function setAUStatus(int $a_u_status): self
    {
        $this->a_u_status = $a_u_status;

        return $this;
    }

    public function getAUAwResult(): ?float
    {
        return $this->a_u_aw_result;
    }

    public function setAUAwResult(?float $a_u_aw_result): self
    {
        $this->a_u_aw_result = $a_u_aw_result;

        return $this;
    }

    public function getAUAeResult(): ?float
    {
        return $this->a_u_ae_result;
    }

    public function setAUAeResult(?float $a_u_ae_result): self
    {
        $this->a_u_ae_result = $a_u_ae_result;

        return $this;
    }

    public function getAUReResult(): ?float
    {
        return $this->a_u_re_result;
    }

    public function setAUReResult(?float $a_u_re_result): self
    {
        $this->a_u_re_result = $a_u_re_result;

        return $this;
    }

    public function getAUEDev(): ?float
    {
        return $this->a_u_e_dev;
    }

    public function setAUEDev(?float $a_u_e_dev): self
    {
        $this->a_u_e_dev = $a_u_e_dev;

        return $this;
    }

    public function getAUWDevratio(): ?float
    {
        return $this->a_u_w_devratio;
    }

    public function setAUWDevratio(?float $a_u_w_devratio): self
    {
        $this->a_u_w_devratio = $a_u_w_devratio;

        return $this;
    }

    public function getAUEDevratio(): ?float
    {
        return $this->a_u_e_devratio;
    }

    public function setAUEDevratio(?float $a_u_e_devratio): self
    {
        $this->a_u_e_devratio = $a_u_e_devratio;

        return $this;
    }

    public function getAULeader(): ?bool
    {
        return $this->a_u_leader;
    }

    public function setAULeader(bool $a_u_leader): self
    {
        $this->a_u_leader = $a_u_leader;

        return $this;
    }

    public function getAUType(): ?int
    {
        return $this->a_u_type;
    }

    public function setAUType(int $a_u_type): self
    {
        $this->a_u_type = $a_u_type;

        return $this;
    }

    public function getAUMWeight(): ?float
    {
        return $this->a_u_mWeight;
    }

    public function setAUMWeight(float $a_u_mWeight): self
    {
        $this->a_u_mWeight = $a_u_mWeight;

        return $this;
    }

    public function getAUPrecomment(): ?string
    {
        return $this->a_u_precomment;
    }

    public function setAUPrecomment(?string $a_u_precomment): self
    {
        $this->a_u_precomment = $a_u_precomment;

        return $this;
    }

    public function getAUIvpBonus(): ?float
    {
        return $this->a_u_ivp_bonus;
    }

    public function setAUIvpBonus(float $a_u_ivp_bonus): self
    {
        $this->a_u_ivp_bonus = $a_u_ivp_bonus;

        return $this;
    }

    public function getAUIvpPenalty(): ?float
    {
        return $this->a_u_ivp_penalty;
    }

    public function setAUIvpPenalty(float $a_u_ivp_penalty): self
    {
        $this->a_u_ivp_penalty = $a_u_ivp_penalty;

        return $this;
    }

    public function getAUOfBonys(): ?float
    {
        return $this->a_u_of_bonys;
    }

    public function setAUOfBonys(float $a_u_of_bonys): self
    {
        $this->a_u_of_bonys = $a_u_of_bonys;

        return $this;
    }

    public function getAUOfPenalty(): ?float
    {
        return $this->a_u_of_penalty;
    }

    public function setAUOfPenalty(float $a_u_of_penalty): self
    {
        $this->a_u_of_penalty = $a_u_of_penalty;

        return $this;
    }

    public function getAUMailed(): ?bool
    {
        return $this->a_u_mailed;
    }

    public function setAUMailed(?bool $a_u_mailed): self
    {
        $this->a_u_mailed = $a_u_mailed;

        return $this;
    }

    public function getAUCreatedBy(): ?int
    {
        return $this->a_u_createdBy;
    }

    public function setAUCreatedBy(?int $a_u_createdBy): self
    {
        $this->a_u_createdBy = $a_u_createdBy;

        return $this;
    }

    public function getAUInserted(): ?\DateTimeInterface
    {
        return $this->a_u_inserted;
    }

    public function setAUInserted(?\DateTimeInterface $a_u_inserted): self
    {
        $this->a_u_inserted = $a_u_inserted;

        return $this;
    }

    public function getAUConfirmed(): ?\DateTimeInterface
    {
        return $this->a_u_confirmed;
    }

    public function setAUConfirmed(?\DateTimeInterface $a_u_confirmed): self
    {
        $this->a_u_confirmed = $a_u_confirmed;

        return $this;
    }

    public function getAUDeleted(): ?\DateTimeInterface
    {
        return $this->a_u_deleted;
    }

    public function setAUDeleted(?\DateTimeInterface $a_u_deleted): self
    {
        $this->a_u_deleted = $a_u_deleted;

        return $this;
    }
}
