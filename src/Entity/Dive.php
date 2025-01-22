<?php

namespace App\Entity;

use App\Repository\DiveRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiveRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Dive implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(targetEntity: Gallery::class, mappedBy: 'dive', cascade: ['remove'])]
    private Collection $photos;

    #[ORM\OneToMany(targetEntity: DiveSpecies::class, mappedBy: 'dive_id')]
    private Collection $diveSpecies;

    #[ORM\ManyToOne(inversedBy: 'dives')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Spot $spot = null;

    #[ORM\ManyToOne(inversedBy: 'dives')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->diveSpecies = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title . '' . $this->description . '' . $this->photos;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    #[ORM\PrePersist()]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * @return Collection<int, Gallery>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Gallery $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setDive($this);
        }

        return $this;
    }

    public function removePhoto(Gallery $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getDive() === $this) {
                $photo->setDive(null);
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
            $diveSpecies->setDiveId($this);
        }

        return $this;
    }

    public function removeDiveSpecies(DiveSpecies $diveSpecies): static
    {
        if ($this->diveSpecies->removeElement($diveSpecies)) {
            // set the owning side to null (unless already changed)
            if ($diveSpecies->getDiveId() === $this) {
                $diveSpecies->setDiveId(null);
            }
        }

        return $this;
    }

    public function getSpot(): ?Spot
    {
        return $this->spot;
    }

    public function setSpot(?Spot $spot): static
    {
        $this->spot = $spot;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
}
