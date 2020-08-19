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
    public $id;

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
    public $createdBy;

    /**
     * @ORM\Column(name="ona_inserted", type="datetime", nullable=true)
     */
    public $inserted;

    /**
     * OptionName constructor.
     * @param $id
     * @param $ona_type
     * @param $ona_name
     * @param $ona_description
     * @param $ona_createdBy
     * @param $ona_inserted
     */
    public function __construct(
        $id = 0,
        $ona_type = null,
        $ona_description = null,
        $ona_name = null,
        $ona_createdBy = null,
        $ona_inserted = null)
    {
        parent::__construct($id, $ona_createdBy, new DateTime());

        $this->type = $ona_type;
        $this->name = $ona_name;
        $this->description = $ona_description;
        $this->inserted = $ona_inserted;
    }


    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $ona_type): self
    {
        $this->type = $ona_type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $ona_name): self
    {
        $this->name = $ona_name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $ona_description): self
    {
        $this->description = $ona_description;

        return $this;
    }

    public function setInserted(string $ona_inserted): self
    {
        $this->inserted = $ona_inserted;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }
}
