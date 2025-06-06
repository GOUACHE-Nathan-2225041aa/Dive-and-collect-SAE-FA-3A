<?php

namespace App\Entity;

use App\Repository\CoordonneeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoordonneeRepository::class)]
class Coordonnee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $latitude = null;

    #[ORM\Column]
    private ?float $longitude = null;

    /**
     * @var Collection<int, EspecePoisson>
     */
    #[ORM\ManyToMany(targetEntity: EspecePoisson::class, inversedBy: 'coordonnees')]
    private Collection $especes;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'coordonnees')]
    private Collection $photos;

    public function __construct()
    {
        $this->especes = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, EspecePoisson>
     */
    public function getEspeces(): Collection
    {
        return $this->especes;
    }

    public function addEspece(EspecePoisson $espece): static
    {
        if (!$this->especes->contains($espece)) {
            $this->especes->add($espece);
        }

        return $this;
    }

    public function removeEspece(EspecePoisson $espece): static
    {
        $this->especes->removeElement($espece);

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setCoordonnees($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getCoordonnees() === $this) {
                $photo->setCoordonnees(null);
            }
        }

        return $this;
    }
}
