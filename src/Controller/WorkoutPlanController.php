<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\WorkoutPlanRepository;

#[Route('/workout-plan')]
class WorkoutPlanController extends AbstractController
{
    #[Route('/', name: 'workout_plan_show')]
    public function show(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $workoutPlan = $user->getGoal()?->getWorkoutPlan();
        if (!$workoutPlan) {
            return $this->render('workout_plan/index.html.twig');
        }

        return $this->render('workout_plan/show.html.twig', [
            'workoutPlan' => $workoutPlan
        ]);
    }
}
