<?php

namespace App\Entity;

use App\Repository\OceanariumRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OceanariumRepository::class)]
class Oceanarium implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $validate = null;

    #[ORM\ManyToOne(inversedBy: 'oceanarium')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'oceanarium')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Species $species = null;

    public function __toString(): string
    {
        return $this->species;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isValidate(): ?bool
    {
        return $this->validate;
    }

    public function setValidate(bool $validate): static
    {
        $this->validate = $validate;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user;
    }

    public function setUserId(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getSpeciesId(): ?Species
    {
        return $this->species;
    }

    public function setSpeciesId(?Species $species): static
    {
        $this->species = $species;

        return $this;
    }
}
