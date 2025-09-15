<?php

namespace App\Controller;

use App\Entity\WorkoutPlan;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ExcerciseController extends AbstractController
{
    #[Route('/{id}/exercices', name: 'workout_exercices')]
    public function index(WorkoutPlan $workoutPlan): Response
    {
        return $this->render('excercise/exercices.html.twig', [
            'workoutPlan' => $workoutPlan,
            'exercices' => $workoutPlan->getExercices()
        ]);
    }
}
