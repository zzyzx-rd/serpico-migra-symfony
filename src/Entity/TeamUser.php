<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TeamUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TeamUserRepository::class)
 */
class TeamUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="tus_id", type="integer", length=10)
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $tus_leader;

    /**
     * @ORM\Column(type="integer")
     */
    private $tus_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tus_inserted;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tus_deleted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $tus_is_deleted;

    /**
     *@ManyToOne(targetEntity="Team")
     *@JoinColumn(name="team_tea_id", referencedColumnName="tea_id", nullable=false)
     */
    protected $team;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTusLeader(): ?bool
    {
        return $this->tus_leader;
    }

    public function setTusLeader(bool $tus_leader): self
    {
        $this->tus_leader = $tus_leader;

        return $this;
    }

    public function getTusCreatedBy(): ?int
    {
        return $this->tus_createdBy;
    }

    public function setTusCreatedBy(int $tus_createdBy): self
    {
        $this->tus_createdBy = $tus_createdBy;

        return $this;
    }

    public function getTusInserted(): ?\DateTimeInterface
    {
        return $this->tus_inserted;
    }

    public function setTusInserted(\DateTimeInterface $tus_inserted): self
    {
        $this->tus_inserted = $tus_inserted;

        return $this;
    }

    public function getTusDeleted(): ?\DateTimeInterface
    {
        return $this->tus_deleted;
    }

    public function setTusDeleted(?\DateTimeInterface $tus_deleted): self
    {
        $this->tus_deleted = $tus_deleted;

        return $this;
    }

    public function getTusIsDeleted(): ?bool
    {
        return $this->tus_is_deleted;
    }

    public function setTusIsDeleted(bool $tus_is_deleted): self
    {
        $this->tus_is_deleted = $tus_is_deleted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param mixed $team
     */
    public function setTeam($team): void
    {
        $this->team = $team;
    }

}
