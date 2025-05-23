<?php

namespace App\Entity;

use App\Repository\ONGRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: ONGRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class ONG implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    private ?string $nomOng = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $prenomContact = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column]
    private int $points = 0;

    /**
     * @var Collection<int, OngBadge>
     */
    #[ORM\OneToMany(targetEntity: OngBadge::class, mappedBy: 'Ong')]
    private Collection $ongBadges;

    public function __construct()
    {
        $this->ongBadges = new ArrayCollection();
    }

    // --- Getters / Setters ---

    public function getPoints(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getNomOng(): ?string
    {
        return $this->nomOng;
    }

    public function setNomOng(string $nomOng): static
    {
        $this->nomOng = $nomOng;
        return $this;
    }

    public function getPrenomContact(): ?string
    {
        return $this->prenomContact;
    }

    public function setPrenomContact(?string $prenomContact): static
    {
        $this->prenomContact = $prenomContact;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function addRole(string $role): static
    {
        $this->roles[] = $role;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

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
            $ongBadge->setOng($this);
        }

        return $this;
    }

    public function removeOngBadge(OngBadge $ongBadge): static
    {
        if ($this->ongBadges->removeElement($ongBadge)) {
            // set the owning side to null (unless already changed)
            if ($ongBadge->getOng() === $this) {
                $ongBadge->setOng(null);
            }
        }

        return $this;
    }
}
