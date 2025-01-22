<?php

namespace App\Entity;

use App\Repository\DiveSpeciesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiveSpeciesRepository::class)]
class DiveSpecies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'diveSpecies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Species $species = null;

    #[ORM\ManyToOne(inversedBy: 'diveSpecies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Dive $dive = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDiveId(): ?Dive
    {
        return $this->dive;
    }

    public function setDiveId(?Dive $dive): static
    {
        $this->dive = $dive;

        return $this;
    }
}
