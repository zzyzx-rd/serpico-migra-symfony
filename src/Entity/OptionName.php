<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OptionNameRepository;
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
     * @ORM\Column(type="integer", nullable=true)
     */
    public $ona_type;

    /**
     * @ORM\Column(name="ona_name", type="string", length=255, nullable=true)
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $ona_description;

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

        $this->ona_type = $ona_type;
        $this->name = $ona_name;
        $this->ona_description = $ona_description;
        $this->inserted = $ona_inserted;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->ona_type;
    }

    public function setType(int $ona_type): self
    {
        $this->ona_type = $ona_type;

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
        return $this->ona_description;
    }

    public function setDescription(string $ona_description): self
    {
        $this->ona_description = $ona_description;

        return $this;
    }

    public function getInserted(): ?string
    {
        return $this->inserted;
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
