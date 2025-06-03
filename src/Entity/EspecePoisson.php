<?php

namespace App\Entity;

use App\Repository\EspecePoissonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EspecePoissonRepository::class)]
class EspecePoisson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Coordonnee>
     */
    #[ORM\ManyToMany(targetEntity: Coordonnee::class, mappedBy: 'especes')]
    private Collection $coordonnees;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageFileName = null;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'Espece')]
    private Collection $espece;

    public function __construct()
    {
        $this->coordonnees = new ArrayCollection();
        $this->date_added = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Coordonnee>
     */
    public function getCoordonnees(): Collection
    {
        return $this->coordonnees;
    }

    public function addCoordonnee(Coordonnee $coordonnee): static
    {
        if (!$this->coordonnees->contains($coordonnee)) {
            $this->coordonnees->add($coordonnee);
            $coordonnee->addEspece($this);
        }

        return $this;
    }

    public function removeCoordonnee(Coordonnee $coordonnee): static
    {
        if ($this->coordonnees->removeElement($coordonnee)) {
            $coordonnee->removeEspece($this);
        }

        return $this;
    }

    public function getImageFileName(): ?string
    {
        return $this->imageFileName;
    }

    public function setImageFileName(?string $imageFileName): static
    {
        $this->imageFileName = $imageFileName;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getDateAdded(): Collection
    {
        return $this->date_added;
    }

    public function addDateAdded(Photo $dateAdded): static
    {
        if (!$this->date_added->contains($dateAdded)) {
            $this->date_added->add($dateAdded);
            $dateAdded->setEspece($this);
        }

        return $this;
    }

    public function removeDateAdded(Photo $dateAdded): static
    {
        if ($this->date_added->removeElement($dateAdded)) {
            // set the owning side to null (unless already changed)
            if ($dateAdded->getEspece() === $this) {
                $dateAdded->setEspece(null);
            }
        }

        return $this;
    }
}
