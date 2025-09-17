<?php

namespace App\Entity;

use App\Enum\GoalType;
use App\Enum\WorkoutLevel;
use App\Repository\WorkoutPlanRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: WorkoutPlanRepository::class)]
#[ORM\HasLifecycleCallbacks]
class WorkoutPlan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: WorkoutLevel::class)]
    private WorkoutLevel $level;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, Exercice>
     */
    #[ORM\OneToMany(mappedBy: 'workoutPlan', targetEntity: Exercice::class, orphanRemoval: true)]
    private Collection $exercices;

    #[ORM\Column(type: 'string', enumType: GoalType::class)]
    private GoalType $type;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->exercices = new ArrayCollection();
    }

    // 🕒 Lifecycle callbacks
    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    // --- Getters / Setters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevel(): WorkoutLevel
    {
        return $this->level;
    }

    public function setLevel(WorkoutLevel $level): static
    {
        $this->level = $level;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection<int, Exercice>
     */
    public function getExercices(): Collection
    {
        return $this->exercices;
    }

    public function addExercice(Exercice $exercice): self
    {
        if (!$this->exercices->contains($exercice)) {
            $this->exercices->add($exercice);
            $exercice->setWorkoutPlan($this);
        }

        return $this;
    }

    public function removeExercice(Exercice $exercice): self
    {
        if ($this->exercices->removeElement($exercice)) {
            if ($exercice->getWorkoutPlan() === $this) {
                $exercice->setWorkoutPlan(null);
            }
        }

        return $this;
    }

    public function getType(): ?GoalType
    {
        return $this->type;
    }

    public function setType(GoalType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
