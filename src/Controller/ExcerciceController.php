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
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('excercise/index.html.twig', [
            'workoutPlan' => $workoutPlan,
            'exercices' => $workoutPlan->getExercices()
        ]);
    }
}
