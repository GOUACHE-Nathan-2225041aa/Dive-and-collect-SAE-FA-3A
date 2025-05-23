<?php

namespace App\Entity;

use App\Repository\OngBadgeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OngBadgeRepository::class)]
class OngBadge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'ongBadges')]
    #[ORM\JoinColumn(nullable: false)]
    private ONG $Ong;

    #[ORM\ManyToOne(inversedBy: 'ongBadges')]
    #[ORM\JoinColumn(nullable: false)]
    private Badge $Badge;

    #[ORM\Column]
    private \DateTimeImmutable $DateAttribution;

    public function getId(): int
    {
        return $this->id;
    }

    public function getOng(): ONG
    {
        return $this->Ong;
    }

    public function setOng(ONG $Ong): static
    {
        $this->Ong = $Ong;

        return $this;
    }

    public function getBadge(): Badge
    {
        return $this->Badge;
    }

    public function setBadge(Badge $Badge): static
    {
        $this->Badge = $Badge;

        return $this;
    }

    public function getDateAttribution(): \DateTimeImmutable
    {
        return $this->DateAttribution;
    }

    public function setDateAttribution(\DateTimeImmutable $DateAttribution): static
    {
        $this->DateAttribution = $DateAttribution;

        return $this;
    }
}
