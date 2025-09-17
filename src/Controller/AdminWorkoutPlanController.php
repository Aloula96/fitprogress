<?php

namespace App\Controller;

use App\Entity\Goal;
use App\Entity\WorkoutPlan;
use App\Form\AdminWorkoutPlanType;
use App\Enum\WorkoutLevel;
use App\Repository\GoalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/workoutplan')]
class AdminWorkoutPlanController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private GoalRepository $goalRepository
    ) {}

    #[Route('/', name: 'admin_workoutplan')]
    public function index(): Response
    {
        return $this->render('admin_workoutplan/index.html.twig', [
            'goalsWithoutPlans' => $this->goalRepository->findBy(['workoutPlan' => null]),
            'goalsWithPlans' => $this->goalRepository->findByWorkoutPlanExists()
        ]);
    }

    #[Route('/create/{id}', name: 'admin_workoutplan_create')]
    public function create(Goal $goal): Response
    {
        if ($goal->getWorkoutPlan()) {
            return $this->redirectToRoute('admin_workoutplan_edit', ['id' => $goal->getWorkoutPlan()->getId()]);
        }

        $workoutPlan = (new WorkoutPlan())
            ->setType($goal->getType())
            ->setLevel(WorkoutLevel::BEGINNER)
            ->setDescription('Plan personnalisé basé sur votre objectif');

        $goal->setWorkoutPlan($workoutPlan);

        $this->em->persist($workoutPlan);
        $this->em->persist($goal);
        $this->em->flush();

        $this->addFlash('success', 'Plan créé avec succès ✅');

        return $this->redirectToRoute('admin_workoutplan_edit', ['id' => $workoutPlan->getId()]);
    }

    #[Route('/{id}/edit', name: 'admin_workoutplan_edit')]
    public function edit(WorkoutPlan $plan, Request $request): Response
    {
        $form = $this->createForm(AdminWorkoutPlanType::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Plan mis à jour ✅');
            return $this->redirectToRoute('admin_workoutplan');
        }

        return $this->render('admin_workoutplan/edit.html.twig', [
            'form' => $form->createView(),
            'plan' => $plan
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_workoutplan_delete', methods: ['POST'])]
    public function delete(Request $request, WorkoutPlan $workoutPlan): Response
    {
        if ($this->isCsrfTokenValid('delete' . $workoutPlan->getId(), $request->request->get('_token'))) {
            // Détacher le goal lié
            $goal = $this->em->getRepository(Goal::class)->findOneBy(['workoutPlan' => $workoutPlan]);
            if ($goal) {
                $goal->setWorkoutPlan(null);
                $this->em->persist($goal);
            }

            $this->em->remove($workoutPlan);
            $this->em->flush();

            $this->addFlash('success', 'Plan supprimé ✅');
        }

        return $this->redirectToRoute('admin_workoutplan');
    }
}
