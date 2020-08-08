<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StateRepository::class)
 */
class State
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sta_abbr;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sta_fullname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sta_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sta_createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sta_inserted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStaAbbr(): ?string
    {
        return $this->sta_abbr;
    }

    public function setStaAbbr(string $sta_abbr): self
    {
        $this->sta_abbr = $sta_abbr;

        return $this;
    }

    public function getStaFullname(): ?string
    {
        return $this->sta_fullname;
    }

    public function setStaFullname(string $sta_fullname): self
    {
        $this->sta_fullname = $sta_fullname;

        return $this;
    }

    public function getStaName(): ?string
    {
        return $this->sta_name;
    }

    public function setStaName(string $sta_name): self
    {
        $this->sta_name = $sta_name;

        return $this;
    }

    public function getStaCreatedBy(): ?int
    {
        return $this->sta_createdBy;
    }

    public function setStaCreatedBy(?int $sta_createdBy): self
    {
        $this->sta_createdBy = $sta_createdBy;

        return $this;
    }

    public function getStaInserted(): ?\DateTimeInterface
    {
        return $this->sta_inserted;
    }

    public function setStaInserted(\DateTimeInterface $sta_inserted): self
    {
        $this->sta_inserted = $sta_inserted;

        return $this;
    }
}
