<?php

namespace App\Controller;

use App\Entity\Goal;
use App\Entity\User;
use App\Enum\GoalType;
use App\Repository\GoalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

class GoalController extends AbstractController
{
    #[Route('/goal', name: 'goal', methods: ['GET', 'POST'])]
    public function target(
        Request $request,
        EntityManagerInterface $em,
        Security $security,
        GoalRepository $goalRepository
    ): Response {
        /** @var User $user */
        $user = $security->getUser();
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }

        //  If the user already has a goal → redirect to Dashboard
        $goal = $goalRepository->findOneBy(['user' => $user]);
        if ($goal && $request->isMethod('GET')) {
            return $this->redirectToRoute('app_dashboard');
        }

        if ($request->isMethod('POST')) {
            $selectedGoal = $request->request->get('goal');
            $customTarget = $request->request->get('target_weight');

            // Simple BMI calculation for default target
            $height = $user->getHeight() / 100;
            $bmi = 22; // "ideal" BMI
            $idealWeight = $bmi * ($height ** 2);

            if (!$goal) {
                $goal = new Goal();
                $goal->setUser($user);
                $goal->setCreatedAt(new \DateTimeImmutable());
                $em->persist($goal);
            } else {
                // Reset workout plan if user changes goal
                $goal->setWorkoutPlan(null);
            }

            $goal->setType(GoalType::from($selectedGoal));

            // Target weight
            if (!empty($customTarget)) {
                $goal->setTargetWeight((float) $customTarget);
            } else {
                $goal->setTargetWeight(round($idealWeight, 1));
            }

            $em->flush();

            $this->addFlash('success', 'Ton objectif a été enregistré !');

            // After goal → go to workout plan
            return $this->redirectToRoute('workout_plan_show');
        }

        return $this->render('goal/index.html.twig', [
            'user' => $user,
            'goal' => $goal,
        ]);
    }
}
