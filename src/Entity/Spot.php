<?php

namespace App\Entity;

use App\Repository\SpotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpotRepository::class)]
class Spot implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 15, nullable: false)]
    private ?float $latitude = null;

    #[ORM\Column (type: 'decimal', precision: 20, scale: 15, nullable: false)]
    private ?float $longitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?bool $isPremium = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(targetEntity: Dive::class, mappedBy: 'spot_id')]
    private Collection $dives;

    #[ORM\OneToMany(targetEntity: BucketListSpot::class, mappedBy: 'spot_id')]
    private Collection $bucketListSpots;

    #[ORM\Column]
    private ?bool $marineEnvironment = null;

    public function __construct()
    {
        $this->dives = new ArrayCollection();
        $this->bucketListSpots = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function __toString():string
    {
        return $this->name.''.$this->latitude.''.$this->longitude.''.$this->image.''.$this->isPremium.''.$this->Marin;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function isIsPremium(): ?bool
    {
        return $this->isPremium;
    }

    public function setIsPremium(bool $isPremium): static
    {
        $this->isPremium = $isPremium;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Dive>
     */
    public function getDives(): Collection
    {
        return $this->dives;
    }

    public function addDive(Dive $dive): static
    {
        if (!$this->dives->contains($dive)) {
            $this->dives->add($dive);
            $dive->setSpot($this);
        }

        return $this;
    }

    public function removeDive(Dive $dive): static
    {
        if ($this->dives->removeElement($dive)) {
            // set the owning side to null (unless already changed)
            if ($dive->getSpot() === $this) {
                $dive->setSpot(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BucketListSpot>
     */
    public function getBucketListSpots(): Collection
    {
        return $this->bucketListSpots;
    }

    public function addBucketListSpot(BucketListSpot $bucketListSpot): static
    {
        if (!$this->bucketListSpots->contains($bucketListSpot)) {
            $this->bucketListSpots->add($bucketListSpot);
            $bucketListSpot->setSpotId($this);
        }

        return $this;
    }

    public function removeBucketListSpot(BucketListSpot $bucketListSpot): static
    {
        if ($this->bucketListSpots->removeElement($bucketListSpot)) {
            // set the owning side to null (unless already changed)
            if ($bucketListSpot->getSpotId() === $this) {
                $bucketListSpot->setSpotId(null);
            }
        }

        return $this;
    }

    public function isMarineEnvironment(): ?bool
    {
        return $this->marineEnvironment;
    }

    public function setMarineEnvironment(bool $marineEnvironment): static
    {
        $this->marineEnvironment = $marineEnvironment;

        return $this;
    }
}
