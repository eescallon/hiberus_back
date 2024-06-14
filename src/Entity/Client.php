<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $name = null;

    #[ORM\Column(length: 25)]
    private ?string $lastName = null;

    #[ORM\Column(length: 30)]
    private ?string $email = null;

    #[ORM\Column(length: 15)]
    private ?string $phone = null;

    #[ORM\Column(length: 2)]
    private ?string $typeIdentity = null;

    #[ORM\Column(length: 15)]
    private ?string $numberIdentity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getTypeIdentity(): ?string
    {
        return $this->typeIdentity;
    }

    public function setTypeIdentity(string $typeIdentity): static
    {
        $this->typeIdentity = $typeIdentity;

        return $this;
    }

    public function getNumberIdentity(): ?string
    {
        return $this->numberIdentity;
    }

    public function setNumberIdentity(string $numberIdentity): static
    {
        $this->numberIdentity = $numberIdentity;

        return $this;
    }
}
