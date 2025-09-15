<?php

namespace App\Entity;

use App\Enum\GoalType;
use App\Enum\WorkoutLevel;
use App\Enum\WorkoutPlanType;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\WorkoutPlanRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity(repositoryClass: WorkoutPlanRepository::class)]
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

    public function __construct()
    {
        $this->exercices = new ArrayCollection();
    }

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
            // Set the owning side to null
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
}
