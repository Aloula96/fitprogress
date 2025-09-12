<?php

namespace App\Controller;

use App\Entity\Goal;
use App\Enum\WorkoutLevel;
use App\Entity\WorkoutPlan;
use App\Form\AdminWorkoutPlanType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
final class AdminGoalController extends AbstractController
{
    #[Route('/goal', name: 'admin_goal')]
    public function index(EntityManagerInterface $em): Response
    {
        $goalWithoutPlan = $em->getRepository(Goal::class)->findBy(['workoutPlan' => null]);
        $goalWithPlan = $em->getRepository(Goal::class)->findBy(['workoutPlan' => true]);

        return $this->render('admin_goal/index.html.twig', [
            'goalWithPlan' => $goalWithPlan,
            'goalWithoutPlan' => $goalWithoutPlan
        ]);
    }

    #[Route('/goal/{id}/create-plan', name: 'admin_goal_create_plan')]
    public function createWorkoutPlan(Goal $goal, EntityManagerInterface $em): Response
    {
        $workoutPlan = new WorkoutPlan();
        $workoutPlan->setTitles('Plan pour ' . $goal->getUser()->getEmail());
        $workoutPlan->setType($goal->getType());



        $workoutPlan->setLevel(WorkoutLevel::BEGINNER);


        $workoutPlan->setDescription('Plan personnalisé basé sur votre objectif');

        $em->persist($workoutPlan);
        $em->flush();


        $goal->setWorkoutPlan($workoutPlan);
        $em->persist($goal);
        $em->flush();

        $this->addFlash('success', 'Workout plan created for the goal!');

        return $this->redirectToRoute('admin_workoutplan_edit', [
            'id' => $workoutPlan->getId()
        ]);
    }

    #[Route('/', name: 'admin_without_workout_plan')]
    public function WithoutWorkoutPlan(EntityManagerInterface $em): Response
    {

        $goals = $em->getRepository(Goal::class)->findBy(['workoutPlan' => null]);

        return $this->render('admin/goal/index.html.twig', [
            'goals' => $goals,

        ]);
    }
    #[Route('/{id}/assign-plan', name: 'admin_goal_assign_plan')]
    public function assignPlan(Request $request, Goal $goal, EntityManagerInterface $em): Response
    {
        $plan = new WorkoutPlan();
        $form = $this->createForm(AdminWorkoutPlanType::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $goal->setWorkoutPlan($plan);
            $em->persist($plan);
            $em->flush();

            $this->addFlash('success', 'Workout plan created and linked to goal!');
            return $this->redirectToRoute('admin_goal_index');
        }

        return $this->render('admin/goal/assign_plan.html.twig', [
            'form' => $form->createView(),
            'goal' => $goal,
        ]);
    }
}
