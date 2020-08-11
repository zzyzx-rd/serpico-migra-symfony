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
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ona_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ona_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ona_description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $ona_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $ona_inserted;

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
        $this->ona_type = $ona_type;
        $this->ona_name = $ona_name;
        $this->ona_description = $ona_description;
        $this->ona_inserted = $ona_inserted;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOnaType(): ?int
    {
        return $this->ona_type;
    }

    public function setOnaType(int $ona_type): self
    {
        $this->ona_type = $ona_type;

        return $this;
    }

    public function getOnaName(): ?string
    {
        return $this->ona_name;
    }

    public function setOnaName(string $ona_name): self
    {
        $this->ona_name = $ona_name;

        return $this;
    }

    public function getOnaDescription(): ?string
    {
        return $this->ona_description;
    }

    public function setOnaDescription(string $ona_description): self
    {
        $this->ona_description = $ona_description;

        return $this;
    }

    public function getOnaCreatedBy(): ?\DateTimeInterface
    {
        return $this->ona_createdBy;
    }

    public function setOnaCreatedBy(\DateTimeInterface $ona_createdBy): self
    {
        $this->ona_createdBy = $ona_createdBy;

        return $this;
    }

    public function getOnaInserted(): ?string
    {
        return $this->ona_inserted;
    }

    public function setOnaInserted(string $ona_inserted): self
    {
        $this->ona_inserted = $ona_inserted;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }
}
