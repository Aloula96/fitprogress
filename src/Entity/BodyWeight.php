<?php

namespace App\Entity;

use App\Repository\BodyWeightRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BodyWeightRepository::class)]
class BodyWeight
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $weightValue = null;

    #[ORM\Column]
    private ?\DateTime $recodedAt = null;

    #[ORM\ManyToOne(inversedBy: 'bodyWeights')]
    private ?User $User = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeightValue(): ?float
    {
        return $this->weightValue;
    }

    public function setWeightValue(float $weightValue): static
    {
        $this->weightValue = $weightValue;

        return $this;
    }

    public function getRecodedAt(): ?\DateTime
    {
        return $this->recodedAt;
    }

    public function setRecodedAt(\DateTime $recodedAt): static
    {
        $this->recodedAt = $recodedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }
}
