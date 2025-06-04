<?php

namespace App\Entity;

use App\Repository\ForfaitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ForfaitRepository::class)]
class Forfait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('forfait_with_lots')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups('forfait_with_lots')]
    private string $nom;

    // role attribue a l'utilisateur si forfait choisi
    #[ORM\Column(length: 50)]
    #[Groups('forfait_with_lots')]
    private string $role;

    #[ORM\Column(length: 255)]
    #[Groups('forfait_with_lots')]
    private ?string $description = null;

    /**
     * @var Collection<int, LotDeDonnees>
     */
    #[ORM\ManyToMany(targetEntity: LotDeDonnees::class, inversedBy: 'forfaits')]
    #[Groups('forfait_with_lots')]
    private Collection $lots;

    public function __construct()
    {
        $this->lots = new ArrayCollection();
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

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

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

    /**
     * @return Collection<int, LotDeDonnees>
     */
    public function getLots(): Collection
    {
        return $this->lots;
    }

    public function addLot(LotDeDonnees $lot): static
    {
        if (!$this->lots->contains($lot)) {
            $this->lots->add($lot);
        }

        return $this;
    }

    public function removeLot(LotDeDonnees $lot): static
    {
        $this->lots->removeElement($lot);

        return $this;
    }
}
