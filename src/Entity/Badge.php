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
    private ?string $description = null;

    /**
     * @var Collection<int, ONG>
     */
    #[ORM\ManyToMany(targetEntity: ONG::class, mappedBy: 'badges')]
    private Collection $ongs;

    public function __construct()
    {
        $this->ongs = new ArrayCollection();
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
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, ONG>
     */
    public function getOngs(): Collection
    {
        return $this->ongs;
    }

    public function addOng(ONG $ong): static
    {
        if (!$this->ongs->contains($ong)) {
            $this->ongs->add($ong);
            $ong->addBadge($this);
        }

        return $this;
    }

    public function removeOng(ONG $ong): static
    {
        if ($this->ongs->removeElement($ong)) {
            $ong->removeBadge($this);
        }

        return $this;
    }
}
