<?php

namespace App\Entity;

use App\Repository\BodyWeightRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BodyWeightRepository::class)]
class BodyWeight
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['weight_read'])]
    private ?float $weightValue = null;


    #[ORM\Column]
    #[Groups(['weight_read'])]
    private ?\DateTime $recordedAt = null;

    #[ORM\ManyToOne(inversedBy: 'bodyWeights')]
    private ?User $user = null;
    public function __construct()
    {
        $this->recordedAt = new \DateTime();
    }

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

    public function getRecordedAt(): ?\DateTime
    {
        return $this->recordedAt;
    }

    public function setRecordedAt(\DateTime $recordedAt): static
    {
        $this->recordedAt = $recordedAt;

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
}
