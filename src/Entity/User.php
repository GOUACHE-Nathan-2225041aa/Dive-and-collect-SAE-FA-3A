<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\DBAL\Types\Types;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profession = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $biography = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $certificate = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt;

    #[ORM\OneToMany(targetEntity: Subscription::class, mappedBy: 'user_id')]
    private Collection $subscription;

    #[ORM\OneToMany(targetEntity: BucketList::class, mappedBy: 'user_id')]
    private Collection $bucketList;

    #[ORM\OneToMany(targetEntity: Oceanarium::class, mappedBy: 'user_id')]
    private Collection $oceanarium;

    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'user_id')]
    private Collection $likes;

    #[ORM\Column]
    private ?bool $isVerified = false;

    #[ORM\Column]
    private ?bool $certificateIsValidate = false;

    /**
     * @var Collection<int, UserSubscription>
     */
    #[ORM\OneToMany(targetEntity: UserSubscription::class, mappedBy: 'user')]
    private Collection $userSubscriptions;

    public function __construct()
    {
        $this->subscription = new ArrayCollection();
        $this->bucketList = new ArrayCollection();
        $this->oceanarium = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->userSubscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): static
    {
        $this->profession = $profession;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): static
    {
        $this->biography = $biography;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
//        //guarantee every user at least has ROLE_USER
//        $roles[] = 'ROLE_USER';
//
//        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getCertificate(): ?string
    {
        return $this->certificate;
    }

    public function setCertificate(?string $certificate): static
    {
        $this->certificate = $certificate;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    /**
     * @return Collection<int, Subscription>
     */
    public function getSubscription(): Collection
    {
        return $this->subscription;
    }

    public function addSubscription(Subscription $subscription): static
    {
        if (!$this->subscription->contains($subscription)) {
            $this->subscription->add($subscription);
            $subscription->setUserId($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): static
    {
        if ($this->subscription->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getUserId() === $this) {
                $subscription->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BucketList>
     */
    public function getBucketList(): Collection
    {
        return $this->bucketList;
    }

    public function addBucketList(BucketList $bucketList): static
    {
        if (!$this->bucketList->contains($bucketList)) {
            $this->bucketList->add($bucketList);
            $bucketList->setUserId($this);
        }

        return $this;
    }

    public function removeBucketList(BucketList $bucketList): static
    {
        if ($this->bucketList->removeElement($bucketList)) {
            // set the owning side to null (unless already changed)
            if ($bucketList->getUserId() === $this) {
                $bucketList->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Oceanarium>
     */
    public function getOceanarium(): Collection
    {
        return $this->oceanarium;
    }

    public function addOceanarium(Oceanarium $oceanarium): static
    {
        if (!$this->oceanarium->contains($oceanarium)) {
            $this->oceanarium->add($oceanarium);
            $oceanarium->setUserId($this);
        }

        return $this;
    }

    public function removeOceanarium(Oceanarium $oceanarium): static
    {
        if ($this->oceanarium->removeElement($oceanarium)) {
            // set the owning side to null (unless already changed)
            if ($oceanarium->getUserId() === $this) {
                $oceanarium->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setUserId($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUserId() === $this) {
                $like->setUserId(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function isCertificateIsValidate(): ?bool
    {
        return $this->certificateIsValidate;
    }

    public function setCertificateIsValidate(bool $certificateIsValidate): static
    {
        $this->certificateIsValidate = $certificateIsValidate;

        return $this;
    }

    /**
     * @return Collection<int, UserSubscription>
     */
    public function getUserSubscriptions(): Collection
    {
        return $this->userSubscriptions;
    }

    public function addUserSubscription(UserSubscription $userSubscription): static
    {
        if (!$this->userSubscriptions->contains($userSubscription)) {
            $this->userSubscriptions->add($userSubscription);
            $userSubscription->setUser($this);
        }

        return $this;
    }

    public function removeUserSubscription(UserSubscription $userSubscription): static
    {
        if ($this->userSubscriptions->removeElement($userSubscription)) {
            // set the owning side to null (unless already changed)
            if ($userSubscription->getUser() === $this) {
                $userSubscription->setUser(null);
            }
        }

        return $this;
    }
}
