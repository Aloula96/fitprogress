<?php

namespace App\Controller;

use App\Entity\WorkoutPlan;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ExcerciceController extends AbstractController
{
    #[Route('/workout-plans/{id}/exercises', name: 'workout_exercises')]
    public function index(WorkoutPlan $workoutPlan): Response
    {
        return $this->render('exercise/index.html.twig', [
            'workoutPlan' => $workoutPlan,
            'exercises' => $workoutPlan->getExercices(),
        ]);
    }
}
