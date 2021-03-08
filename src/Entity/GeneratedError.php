<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GeneratedErrorRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=GeneratedErrorRepository::class)
 */
class GeneratedError extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="err_id", type="integer", nullable=true)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="err_usr_id", type="integer", nullable=true)
     */
    public $usrId;

    /**
     * @ORM\Column(name="err_method", type="string",length=255, nullable=false)
     */
    public $method;

    /**
     * @ORM\Column(name="err_req_uri", type="string", length=255, nullable=true)
     */
    public $requestURI;

    /**
     * @ORM\Column(name="err_route", type="string", length=255, nullable=true)
     */
    public $route;

    /**
     * @ORM\Column(name="err_referer", type="string", length=255, nullable=true)
     */
    public $referer;

    /**
     * @ORM\Column(name="err_locale", type="string", length=255, nullable=true)
     */
    public $locale;

    /**
     * @ORM\Column(name="err_agent", type="string", length=255, nullable=true)
     */
    public $agent;

    /**
     * @ORM\Column(name="err_file", type="string", length=255, nullable=true)
     */
    public $file;

    /**
     * @ORM\Column(name="err_line", type="string", length=255, nullable=true)
     */
    public $line;

    /**
     * @ORM\Column(name="err_message", type="string", nullable=true)
     */
    public $message;

    /**
     * @ORM\Column(name="err_inserted", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;
    
    /**
     * @ORM\Column(name="err_solved", type="datetime", nullable=true, options={"default": "CURRENT_TIMESTAMP"})
     */
    public $solved;

    /**
     * @ORM\Column(name="err_feedback", type="text", nullable=true, options={"default": "CURRENT_TIMESTAMP"})
     */
    public $feedback;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="generatedErrorInitiatives")
     * @JoinColumn(name="err_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * Contact constructor.
     * @param $id
     * @param $usrId
     * @param $method
     * @param $requestURI
     * @param $referer
     * @param $locale
     * @param $agent
     * @param $file
     * @param $line
     * @param $message
     */
    public function __construct(
        $id = 0,
        $usrId = null,
        $method = null,
        $requestURI = null,
        $route = null,
        $referer = null,
        $locale = null,
        $agent = null,
        $file = null,
        $line = null,
        $message = null,
        $solved = null,
        $feedback = null)
    {
        parent::__construct($id, null, new DateTime());
        $this->usrId = $usrId;
        $this->locale = $locale;
        $this->method = $method;
        $this->requestURI = $requestURI;
        $this->route = $route;
        $this->referer = $referer;
        $this->agent = $agent;
        $this->file = $file;
        $this->line = $line;
        $this->message = $message;
        $this->solved = $solved;
        $this->feedback = $feedback;
    }

    public function getUsrId(): ?int
    {
        return $this->usrId;
    }

    public function setUsrId(?int $usrId): self
    {
        $this->usrId = $usrId;
        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }
    public function getRequestURI(): ?string
    {
        return $this->requestURI;
    }

    public function setRequestURI(?string $requestURI): self
    {
        $this->requestURI = $requestURI;
        return $this;
    }
    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): self
    {
        $this->route = $route;
        return $this;
    }
    public function getReferer(): ?string
    {
        return $this->referer;
    }

    public function setReferer(?string $referer): self
    {
        $this->referer = $referer;
        return $this;
    }
    public function getAgent(): ?string
    {
        return $this->agent;
    }

    public function setAgent(string $agent): self
    {
        $this->agent = $agent;
        return $this;
    }
    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;
        return $this;
    }
    public function getLine(): ?string
    {
        return $this->line;
    }

    public function setLine(string $line): self
    {
        $this->line = $line;
        return $this;
    }
    
    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function setInserted(?DateTimeInterface $inserted): self
    {
        $this->inserted = $inserted;
        return $this;
    }

    public function getSolved(): ?DateTimeInterface
    {
        return $this->solved;
    }

    public function setSolved(?DateTimeInterface $solved): self
    {
        $this->solved = $solved;
        return $this;
    }

    public function getFeedback(): ?string
    {
        return $this->feedback;
    }

    public function setFeedback(string $feedback): self
    {
        $this->feedback = $feedback;
        return $this;
    }


}
