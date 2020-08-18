<?php

namespace App\Entity;

use App\Repository\UserTempoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use function Symfony\Component\String\s;

/**
 * @ORM\Entity(repositoryClass=UserTempoRepository::class)
 */
class UserTempo implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", nullable=true)
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $password;

    /**
     * @ORM\Column(name="role_rol_id", type="integer", nullable=true)
     * @var int
     */
    public $role;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $username;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(int $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles()
    {
        return ['ROLE_ADMIN'];
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize([
            $this->getId(),
            $this->getUsername(),
            $this->getEmail(),
            $this->getPassword(),
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            )= unserialize($serialized, ['allow_classes' => false]);
    }
}
