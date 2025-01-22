<?php

namespace App\Entity;

use App\Repository\BucketListSpotRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BucketListSpotRepository::class)]
class BucketListSpot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: BucketList::class, inversedBy: 'bucketListSpots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BucketList $bucketList = null;

    #[ORM\ManyToOne(inversedBy: 'bucketListSpots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Spot $spot = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBucketListId(): ?BucketList
    {
        return $this->bucketList;
    }

    public function setBucketListId(?BucketList $bucketList): static
    {
        $this->bucketList = $bucketList;

        return $this;
    }

    public function getSpotId(): ?Spot
    {
        return $this->spot;
    }

    public function setSpotId(?Spot $spot): static
    {
        $this->spot = $spot;

        return $this;
    }
}
