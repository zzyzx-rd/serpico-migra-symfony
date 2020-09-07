<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OptionNameRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="ona_created_by", type="integer", nullable=true)
     */
    public ?int $createdBy;

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
     * @param $createdBy
     */
    public function __construct(
        $id = 0,
        $type = null,
        $description = null,
        $name = null,
        $createdBy = null)
    {
        parent::__construct($id, $createdBy, new DateTime());

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
