<?php

namespace App\Entity;

use App\Repository\SpeciesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpeciesRepository::class)]
class Species implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $api_id = null;

    #[ORM\OneToMany(targetEntity: Oceanarium::class, mappedBy: 'species_id')]
    private Collection $oceanarium;

    #[ORM\OneToMany(targetEntity: DiveSpecies::class, mappedBy: 'species_id')]
    private Collection $diveSpecies;

    public function __construct()
    {
        $this->oceanarium = new ArrayCollection();
        $this->diveSpecies = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->api_id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApiId(): ?int
    {
        return $this->api_id;
    }

    public function setApiId(int $api_id): static
    {
        $this->api_id = $api_id;

        return $this;
    }

    /**
     * @return Collection<int, Oceanarium>
     */
    public function getOceanarium(): Collection
    {
        return $this->oceanarium;
    }

    public function addOceanarium(Oceanarium $oceanarium): static
    {
        if (!$this->oceanarium->contains($oceanarium)) {
            $this->oceanarium->add($oceanarium);
            $oceanarium->setSpeciesId($this);
        }

        return $this;
    }

    public function removeOceanarium(Oceanarium $oceanarium): static
    {
        if ($this->oceanarium->removeElement($oceanarium)) {
            // set the owning side to null (unless already changed)
            if ($oceanarium->getSpeciesId() === $this) {
                $oceanarium->setSpeciesId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DiveSpecies>
     */
    public function getDiveSpecies(): Collection
    {
        return $this->diveSpecies;
    }

    public function addDiveSpecies(DiveSpecies $diveSpecies): static
    {
        if (!$this->diveSpecies->contains($diveSpecies)) {
            $this->diveSpecies->add($diveSpecies);
            $diveSpecies->setSpeciesId($this);
        }

        return $this;
    }

    public function removeDiveSpecies(DiveSpecies $diveSpecies): static
    {
        if ($this->diveSpecies->removeElement($diveSpecies)) {
            // set the owning side to null (unless already changed)
            if ($diveSpecies->getSpeciesId() === $this) {
                $diveSpecies->setSpeciesId(null);
            }
        }

        return $this;
    }
}
