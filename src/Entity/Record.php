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
     * @Column(name="rec_entity", type="string", nullable=true)
     * @var string
     */
    public $entity;

    /**
     * @ORM\Column(name="rec_table_id", type="integer", nullable=true)
     */
    public $tableId;

    /**
     * @ORM\Column(name="rec_property", type="string", length=255, nullable=true)
     */
    public $property;

    /**
     * @ORM\Column(name="rec_old", type="string", length=255, nullable=true)
     */
    public $old;

    /**
     * @ORM\Column(name="rec_new", type="string", length=255, nullable=true)
     */
    public $new;

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


    public function getTableId(): ?int
    {
        return $this->tableId;
    }

    public function setTableId(int $tableId): self
    {
        $this->tableId = $tableId;

        return $this;
    }

    public function getProperty(): ?string
    {
        return $this->property;
    }

    public function setProperty(string $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function getOld(): ?string
    {
        return $this->old;
    }

    public function setOld(string $old): self
    {
        $this->old = $old;

        return $this;
    }

    public function getNew(): ?string
    {
        return $this->new;
    }

    public function setNew(string $new): self
    {
        $this->new = $new;

        return $this;
    }
}
