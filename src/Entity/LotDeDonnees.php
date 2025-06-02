<?php

namespace App\Entity;

use App\Repository\LotDeDonneesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LotDeDonneesRepository::class)]
class LotDeDonnees
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 50)]
    private string $nom;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private float $prix = 0;

    /**
     * @var Collection<int, Forfait>
     */
    #[ORM\ManyToMany(targetEntity: Forfait::class, mappedBy: 'lots')]
    private Collection $forfaits;

    public function __construct()
    {
        $this->forfaits = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, Forfait>
     */
    public function getForfaits(): Collection
    {
        return $this->forfaits;
    }

    public function addForfait(Forfait $forfait): static
    {
        if (!$this->forfaits->contains($forfait)) {
            $this->forfaits->add($forfait);
            $forfait->addLot($this);
        }

        return $this;
    }

    public function removeForfait(Forfait $forfait): static
    {
        if ($this->forfaits->removeElement($forfait)) {
            $forfait->removeLot($this);
        }

        return $this;
    }
}
