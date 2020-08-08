<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DecisionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=DecisionRepository::class)
 */
class Decision
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
    private $dec_type;

    /**
     * @ORM\Column(type="integer")
     */
    private $req_anon;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $dec_anon;

    /**
     * @ORM\Column(type="integer")
     */
    private $val_usr_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $dec_result;

    /**
     * @ORM\Column(type="integer")
     */
    private $dec_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dec_inserted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dec_decided;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dec_validated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDecType(): ?int
    {
        return $this->dec_type;
    }

    public function setDecType(int $dec_type): self
    {
        $this->dec_type = $dec_type;

        return $this;
    }

    public function getReqAnon(): ?int
    {
        return $this->req_anon;
    }

    public function setReqAnon(int $req_anon): self
    {
        $this->req_anon = $req_anon;

        return $this;
    }

    public function getDecAnon(): ?bool
    {
        return $this->dec_anon;
    }

    public function setDecAnon(?bool $dec_anon): self
    {
        $this->dec_anon = $dec_anon;

        return $this;
    }

    public function getValUsrId(): ?int
    {
        return $this->val_usr_id;
    }

    public function setValUsrId(int $val_usr_id): self
    {
        $this->val_usr_id = $val_usr_id;

        return $this;
    }

    public function getDecResult(): ?int
    {
        return $this->dec_result;
    }

    public function setDecResult(int $dec_result): self
    {
        $this->dec_result = $dec_result;

        return $this;
    }

    public function getDecCreatedBy(): ?int
    {
        return $this->dec_createdBy;
    }

    public function setDecCreatedBy(int $dec_createdBy): self
    {
        $this->dec_createdBy = $dec_createdBy;

        return $this;
    }

    public function getDecInserted(): ?\DateTimeInterface
    {
        return $this->dec_inserted;
    }

    public function setDecInserted(\DateTimeInterface $dec_inserted): self
    {
        $this->dec_inserted = $dec_inserted;

        return $this;
    }

    public function getDecDecided(): ?\DateTimeInterface
    {
        return $this->dec_decided;
    }

    public function setDecDecided(\DateTimeInterface $dec_decided): self
    {
        $this->dec_decided = $dec_decided;

        return $this;
    }

    public function getDecValidated(): ?\DateTimeInterface
    {
        return $this->dec_validated;
    }

    public function setDecValidated(\DateTimeInterface $dec_validated): self
    {
        $this->dec_validated = $dec_validated;

        return $this;
    }
}
