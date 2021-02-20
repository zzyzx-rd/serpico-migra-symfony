<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OptionNameRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=OptionNameRepository::class)
 */
class OptionName extends DbObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="ona_id", type="integer", nullable=false)
     */
    public ?int $id;

    /**
     * @ORM\Column(name="ona_type", type="integer", nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="ona_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(name="ona_description", type="string", length=255, nullable=true)
     */
    public $description;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="optionNameInitiatives")
     * @JoinColumn(name="ona_initiator", referencedColumnName="usr_id", nullable=true)
     */
    public ?User $initiator;

    /**
     * @ORM\Column(name="ona_inserted", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    public DateTime $inserted;

    /**
     * OptionName constructor.
     * @param $id
     * @param $type
     * @param $name
     * @param $description
     */
    public function __construct(
        $id = 0,
        $type = null,
        $description = null,
        $name = null)
    {
        parent::__construct($id, null, new DateTime());
        $this->type = $type;
        $this->name = $name;
        $this->description = $description;
    }


    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

    /**
     * @return DateTime
     */
    public function getInserted(): DateTime
    {
        return $this->inserted;
    }

    /**
     * @param DateTime $inserted
     */
    public function setInserted(DateTime $inserted): void
    {
        $this->inserted = $inserted;
    }
}
