<?php

namespace App\Entity;

use App\Repository\BadgeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BadgeRepository::class)]
class Badge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 50)]
    private string $nom;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    /**
     * @var Collection<int, OngBadge>
     */
    #[ORM\OneToMany(targetEntity: OngBadge::class, mappedBy: 'Badge')]
    private Collection $ongBadges;

    public function __construct()
    {
        $this->ongBadges = new ArrayCollection();
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

    public function getDescription(): string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    /**
     * @return Collection<int, OngBadge>
     */
    public function getOngBadges(): Collection
    {
        return $this->ongBadges;
    }

    public function addOngBadge(OngBadge $ongBadge): static
    {
        if (!$this->ongBadges->contains($ongBadge)) {
            $this->ongBadges->add($ongBadge);
            $ongBadge->setBadge($this);
        }

        return $this;
    }

    public function removeOngBadge(OngBadge $ongBadge): static
    {
        if ($this->ongBadges->removeElement($ongBadge)) {
            // set the owning side to null (unless already changed)
            if ($ongBadge->getBadge() === $this) {
                $ongBadge->setBadge(null);
            }
        }

        return $this;
    }
}
