<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $image_file_name = null;

    #[ORM\ManyToOne(inversedBy: 'espece')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?EspecePoisson $espece = null;

    #[ORM\Column]
    private ?\DateTime $date_added = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $auteur = null;

    /**
     * @var Collection<int, Utilisateur>
     */
    #[ORM\ManyToMany(targetEntity: Utilisateur::class)]
    private Collection $Upvote;

    /**
     * @var Collection<int, Mission>
     */
    #[ORM\ManyToMany(targetEntity: Mission::class, mappedBy: 'images')]
    private Collection $missions;

    #[ORM\ManyToOne(targetEntity: Coordonnee::class, cascade: ['persist'], inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Coordonnee $coordonnees = null;

    public function __construct()
    {
        $this->Upvote = new ArrayCollection();
        $this->missions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageFileName(): ?string
    {
        return $this->image_file_name;
    }

    public function setImageFileName(string $image_file_name): static
    {
        $this->image_file_name = $image_file_name;

        return $this;
    }

    public function getEspece(): ?EspecePoisson
    {
        return $this->espece;
    }

    public function setEspece(?EspecePoisson $espece): static
    {
        $this->espece = $espece;

        return $this;
    }

    public function getDateAdded(): ?\DateTime
    {
        return $this->date_added;
    }

    public function setDateAdded(\DateTime $date_added): static
    {
        $this->date_added = $date_added;

        return $this;
    }

    public function getAuteur(): ?Utilisateur
    {
        return $this->auteur;
    }

    public function setAuteur(?Utilisateur $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function changeUpvote(Utilisateur $upvote): array
    {
        // On compte le nombre d'upvotes
        $count = $this->Upvote->count();
        if (!$this->Upvote->contains($upvote)) {
            $this->Upvote->add($upvote);
            return [true, $count+1];
        }
        $this->Upvote->removeElement($upvote);
        return [false, $count-1];
    }

    public function getUpVoteCount(): int
    {
        return $this->Upvote->count();
    }

    public function isLikedByUser(Utilisateur $user): bool
    {
        return $this->Upvote->contains($user);
    }

    /**
     * @return Collection<int, Mission>
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): static
    {
        if (!$this->missions->contains($mission)) {
            $this->missions->add($mission);
            $mission->addImage($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): static
    {
        if ($this->missions->removeElement($mission)) {
            $mission->removeImage($this);
        }

        return $this;
    }

    public function getCoordonnees(): ?Coordonnee
    {
        return $this->coordonnees;
    }

    public function setCoordonnees(?Coordonnee $coordonnees): static
    {
        $this->coordonnees = $coordonnees;

        return $this;
    }
}
