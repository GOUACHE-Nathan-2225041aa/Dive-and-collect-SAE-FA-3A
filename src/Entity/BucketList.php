<?php

namespace App\Entity;

use App\Repository\BucketListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BucketListRepository::class)]
class BucketList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $state = null;

    #[ORM\ManyToOne(inversedBy: 'bucketList')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: BucketListSpot::class, mappedBy: 'bucketList')]
    private Collection $bucketListSpots;

    public function __construct()
    {
        $this->bucketListSpots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user;
    }

    public function setUserId(?User $user): static
    {
        $this->user = $user;

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
            $bucketListSpot->setBucketListId($this);
        }

        return $this;
    }

    public function removeBucketListSpot(BucketListSpot $bucketListSpot): static
    {
        if ($this->bucketListSpots->removeElement($bucketListSpot)) {
            // set the owning side to null (unless already changed)
            if ($bucketListSpot->getBucketListId() === $this) {
                $bucketListSpot->setBucketListId(null);
            }
        }

        return $this;
    }
}
