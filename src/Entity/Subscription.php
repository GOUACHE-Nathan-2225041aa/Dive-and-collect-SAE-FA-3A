<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
class Subscription implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::JSON)]
    private array $allowedFeatures = [];

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $pricePerMonth = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, UserSubscription>
     */
    #[ORM\OneToMany(targetEntity: UserSubscription::class, mappedBy: 'subscription')]
    private Collection $userSubscriptions;

    public function __construct()
    {
        $this->userSubscriptions = new ArrayCollection();
    }

    public function __toString():string
    {
        return $this->type;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAllowedFeatures(): array
    {
        return $this->allowedFeatures;
    }

    public function setAllowedFeatures(array $allowedFeatures): static
    {
        $this->allowedFeatures = $allowedFeatures;

        return $this;
    }

    public function addAllowedFeature(string $feature): static
    {
        if (!in_array($feature, $this->allowedFeatures, true)) {
            $this->allowedFeatures[] = $feature;
        }

        return $this;
    }

    public function removeAllowedFeature(string $feature): static
    {
        $this->allowedFeatures = array_filter($this->allowedFeatures, fn($f) => $f !== $feature);

        return $this;
    }

    public function getPricePerMonth(): ?string
    {
        return $this->pricePerMonth;
    }

    public function setPricePerMonth(string $pricePerMonth): static
    {
        $this->pricePerMonth = $pricePerMonth;

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
            $userSubscription->setSubscription($this);
        }

        return $this;
    }

    public function removeUserSubscription(UserSubscription $userSubscription): static
    {
        if ($this->userSubscriptions->removeElement($userSubscription)) {
            // set the owning side to null (unless already changed)
            if ($userSubscription->getSubscription() === $this) {
                $userSubscription->setSubscription(null);
            }
        }

        return $this;
    }
}
