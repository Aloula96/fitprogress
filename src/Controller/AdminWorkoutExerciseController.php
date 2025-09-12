<?php

namespace App\Controller;

use App\Entity\WorkoutPlan;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/workout-exercise')]
final class AdminWorkoutExerciseController extends AbstractController
{
    #[Route('/{id}', name: 'admin_workout_exercise')]
    public function index(WorkoutPlan $workoutPlan, Emptu): Response
    {
        // dd($workoutPlan->getExercises());
        return $this->render('admin_workout_exercise/index.html.twig', [
            'workoutPlan' => $workoutPlan,
            'exercises' => $workoutPlan->getExercises()

        ]);
    }
}
