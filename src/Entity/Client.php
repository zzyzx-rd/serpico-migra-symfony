<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client
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
    private $clicommname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cli_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cli_logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cli_email;

    /**
     * @ORM\Column(type="integer")
     */
    private $cli_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cli_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClicommname(): ?string
    {
        return $this->clicommname;
    }

    public function setClicommname(string $clicommname): self
    {
        $this->clicommname = $clicommname;

        return $this;
    }

    public function getCliType(): ?string
    {
        return $this->cli_type;
    }

    public function setCliType(string $cli_type): self
    {
        $this->cli_type = $cli_type;

        return $this;
    }

    public function getCliLogo(): ?string
    {
        return $this->cli_logo;
    }

    public function setCliLogo(string $cli_logo): self
    {
        $this->cli_logo = $cli_logo;

        return $this;
    }

    public function getCliEmail(): ?string
    {
        return $this->cli_email;
    }

    public function setCliEmail(?string $cli_email): self
    {
        $this->cli_email = $cli_email;

        return $this;
    }

    public function getCliCreatedBy(): ?int
    {
        return $this->cli_createdBy;
    }

    public function setCliCreatedBy(int $cli_createdBy): self
    {
        $this->cli_createdBy = $cli_createdBy;

        return $this;
    }

    public function getCliInserted(): ?\DateTimeInterface
    {
        return $this->cli_inserted;
    }

    public function setCliInserted(\DateTimeInterface $cli_inserted): self
    {
        $this->cli_inserted = $cli_inserted;

        return $this;
    }
}
