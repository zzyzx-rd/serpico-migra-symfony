<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrganizationUserOptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=OrganizationUserOptionRepository::class)
 */
class OrganizationUserOption
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $opt_bool_value;

    /**
     * @ORM\Column(type="float")
     */
    private $opt_int_value;

    /**
     * @ORM\Column(type="float")
     */
    private $opt_int_value_2;

    /**
     * @ORM\Column(type="float")
     */
    private $opt_float_value;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pot_string_value;

    /**
     * @ORM\Column(type="boolean")
     */
    private $opt_enabled;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $opt_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $opt_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOptBoolValue(): ?bool
    {
        return $this->opt_bool_value;
    }

    public function setOptBoolValue(bool $opt_bool_value): self
    {
        $this->opt_bool_value = $opt_bool_value;

        return $this;
    }

    public function getOptIntValue(): ?float
    {
        return $this->opt_int_value;
    }

    public function setOptIntValue(float $opt_int_value): self
    {
        $this->opt_int_value = $opt_int_value;

        return $this;
    }

    public function getOptIntValue2(): ?float
    {
        return $this->opt_int_value_2;
    }

    public function setOptIntValue2(float $opt_int_value_2): self
    {
        $this->opt_int_value_2 = $opt_int_value_2;

        return $this;
    }

    public function getOptFloatValue(): ?float
    {
        return $this->opt_float_value;
    }

    public function setOptFloatValue(float $opt_float_value): self
    {
        $this->opt_float_value = $opt_float_value;

        return $this;
    }

    public function getPotStringValue(): ?string
    {
        return $this->pot_string_value;
    }

    public function setPotStringValue(string $pot_string_value): self
    {
        $this->pot_string_value = $pot_string_value;

        return $this;
    }

    public function getOptEnabled(): ?bool
    {
        return $this->opt_enabled;
    }

    public function setOptEnabled(bool $opt_enabled): self
    {
        $this->opt_enabled = $opt_enabled;

        return $this;
    }

    public function getOptCreatedBy(): ?int
    {
        return $this->opt_createdBy;
    }

    public function setOptCreatedBy(?int $opt_createdBy): self
    {
        $this->opt_createdBy = $opt_createdBy;

        return $this;
    }

    public function getOptInserted(): ?\DateTimeInterface
    {
        return $this->opt_inserted;
    }

    public function setOptInserted(\DateTimeInterface $opt_inserted): self
    {
        $this->opt_inserted = $opt_inserted;

        return $this;
    }
}
