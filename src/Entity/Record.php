<?php

namespace App\Entity;

use App\Repository\RecordRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;

/**
 * @ORM\Entity(repositoryClass=RecordRepository::class)
 */
class Record
{
    /**
     * @Id
     * @Column(name="rec_entity", type="string")
     * @var string
     */
    public $entity;

    /**
     * @ORM\Column(type="integer")
     */
    private $rec_table_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rec_property;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rec_old;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rec_new;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @param string $entity
     */
    public function setEntity(string $entity): void
    {
        $this->entity = $entity;
    }


    public function getRecTableId(): ?int
    {
        return $this->rec_table_id;
    }

    public function setRecTableId(int $rec_table_id): self
    {
        $this->rec_table_id = $rec_table_id;

        return $this;
    }

    public function getRecProperty(): ?string
    {
        return $this->rec_property;
    }

    public function setRecProperty(string $rec_property): self
    {
        $this->rec_property = $rec_property;

        return $this;
    }

    public function getRecOld(): ?string
    {
        return $this->rec_old;
    }

    public function setRecOld(string $rec_old): self
    {
        $this->rec_old = $rec_old;

        return $this;
    }

    public function getRecNew(): ?string
    {
        return $this->rec_new;
    }

    public function setRecNew(string $rec_new): self
    {
        $this->rec_new = $rec_new;

        return $this;
    }
}
