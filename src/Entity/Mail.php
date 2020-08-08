<?php

namespace App\Entity;

use App\Repository\MailRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MailRepository::class)
 */
class Mail
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $mail_persona;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail_token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $mail_read;

    /**
     * @ORM\Column(type="integer")
     */
    private $mail_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $mail_inserted;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $mail_language;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMailPersona(): ?string
    {
        return $this->mail_persona;
    }

    public function setMailPersona(string $mail_persona): self
    {
        $this->mail_persona = $mail_persona;

        return $this;
    }

    public function getMailToken(): ?string
    {
        return $this->mail_token;
    }

    public function setMailToken(string $mail_token): self
    {
        $this->mail_token = $mail_token;

        return $this;
    }

    public function getMailRead(): ?\DateTimeInterface
    {
        return $this->mail_read;
    }

    public function setMailRead(\DateTimeInterface $mail_read): self
    {
        $this->mail_read = $mail_read;

        return $this;
    }

    public function getMailCreatedBy(): ?int
    {
        return $this->mail_createdBy;
    }

    public function setMailCreatedBy(int $mail_createdBy): self
    {
        $this->mail_createdBy = $mail_createdBy;

        return $this;
    }

    public function getMailInserted(): ?\DateTimeInterface
    {
        return $this->mail_inserted;
    }

    public function setMailInserted(\DateTimeInterface $mail_inserted): self
    {
        $this->mail_inserted = $mail_inserted;

        return $this;
    }

    public function getMailLanguage(): ?string
    {
        return $this->mail_language;
    }

    public function setMailLanguage(string $mail_language): self
    {
        $this->mail_language = $mail_language;

        return $this;
    }
}
