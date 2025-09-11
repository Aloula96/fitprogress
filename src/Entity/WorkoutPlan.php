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

    #[ORM\Column(length: 255)]
    private ?string $titles = null;

    #[ORM\Column(enumType: WorkoutLevel::class)]
    private WorkoutLevel $level;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, Exercice>
     */
    #[ORM\OneToMany(targetEntity: Exercice::class, mappedBy: 'workoutplan')]
    private Collection $exercice;

    #[ORM\Column(type: 'string', enumType: GoalType::class)]
    private GoalType $type;

    public function __construct()
    {
        $this->exercice = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitles(): ?string
    {
        return $this->titles;
    }

    public function setTitles(string $titles): static
    {
        $this->titles = $titles;
        return $this;
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
    public function getExercice(): Collection
    {
        return $this->exercice;
    }

    public function addExercice(Exercice $exercice): static
    {
        if (!$this->exercice->contains($exercice)) {
            $this->exercice->add($exercice);
            $exercice->setWorkoutplan($this);
        }

        return $this;
    }

    public function removeExercice(Exercice $exercice): static
    {
        if ($this->exercice->removeElement($exercice)) {
            // set the owning side to null (unless already changed)
            if ($exercice->getWorkoutplan() === $this) {
                $exercice->setWorkoutplan(null);
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
