<?php

namespace App\Controller;

use App\Entity\Goal;
use App\Entity\User;
use App\Entity\WorkoutPlan;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/workout-plan')]
class WorkoutPlanController extends AbstractController
{
    #[Route('/', name: 'workout_plan_show')]
    public function show(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user->getGoal()?->getWorkoutPlan()) {
            return $this->render('workout_plan/index.html.twig');
        }

        return $this->render('workout_plan/show.html.twig', [
            'workoutPlan' => $user->getGoal()->getWorkoutPlan()
        ]);
    }
}
