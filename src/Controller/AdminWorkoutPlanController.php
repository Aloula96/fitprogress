<?php


namespace App\Controller;


use App\Entity\WorkoutPlan;
use App\Enum\WorkoutPlanType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/workoutplan')]
class AdminWorkoutPlanController extends AbstractController
{
    #[Route('/', name: 'admin_workoutplan_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $plans = $em->getRepository(WorkoutPlan::class)->findAll();

        return $this->render('admin/workoutplan/index.html.twig', [
            'plans' => $plans
        ]);
    }

    #[Route('/new', name: 'admin_workoutplan_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $plan = new WorkoutPlan();
        $form = $this->createForm(WorkoutPlanType::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($plan);
            $em->flush();

            $this->addFlash('success', 'Workout plan created!');
            return $this->redirectToRoute('admin_workoutplan_index');
        }

        return $this->render('admin/workoutplan/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_workoutplan_edit', methods: ['GET', 'POST'])]
    public function edit(WorkoutPlan $plan, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(WorkoutPlanType::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Workout plan updated!');
            return $this->redirectToRoute('admin_workoutplan_index');
        }

        return $this->render('admin/workoutplan/edit.html.twig', [
            'form' => $form->createView(),
            'plan' => $plan
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_workoutplan_delete', methods: ['POST'])]
    public function delete(Request $request, WorkoutPlan $plan, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $plan->getId(), $request->request->get('_token'))) {
            $em->remove($plan);
            $em->flush();
            $this->addFlash('success', 'Workout plan deleted!');
        }

        return $this->redirectToRoute('admin_workoutplan_index');
    }
}
