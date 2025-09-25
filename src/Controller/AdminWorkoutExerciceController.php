<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Entity\WorkoutPlan;
use App\Form\AdminExerciceType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin/workout')]
class AdminWorkoutExerciceController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private SluggerInterface $slugger
    ) {}

    #[Route('/{id}/exercices', name: 'admin_workout_exercices')]
    public function index(WorkoutPlan $workoutPlan): Response
    {
        return $this->render('admin/workout/exercices.html.twig', [
            'workoutPlan' => $workoutPlan,
            'exercices'   => $workoutPlan->getExercices()
        ]);
    }

    #[Route('/{id}/exercice/new', name: 'admin_workout_exercice_new')]
    public function new(Request $request, WorkoutPlan $workoutPlan): Response
    {
        $exercice = new Exercice();
        $exercice->setWorkoutPlan($workoutPlan);

        $form = $this->createForm(AdminExerciceType::class, $exercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->uploadImage($form->get('image')->getData(), $exercice);

            $this->em->persist($exercice);
            $this->em->flush();

            $this->addFlash('success', 'Exercice added successfully!');
            return $this->redirectToRoute('admin_workout_exercices', ['id' => $workoutPlan->getId()]);
        }

        return $this->render('admin/workout/exercice_new.html.twig', [
            'workoutPlan' => $workoutPlan,
            'form' => $form->createView()
        ]);
    }

    #[Route('/exercice/{id}/edit', name: 'admin_workout_exercice_edit')]
    public function edit(Request $request, Exercice $exercice): Response
    {
        $form = $this->createForm(AdminExerciceType::class, $exercice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->uploadImage($form->get('image')->getData(), $exercice);

            $this->em->flush();
            $this->addFlash('success', 'Exercice updated successfully!');

            return $this->redirectToRoute('admin_workout_exercices', [
                'id' => $exercice->getWorkoutPlan()->getId()
            ]);
        }

        return $this->render('admin/workout/edit.html.twig', [
            'form' => $form->createView(),
            'exercice' => $exercice
        ]);
    }

    #[Route('/exercice/{id}/delete', name: 'admin_workout_exercice_delete', methods: ['POST'])]
    public function delete(Request $request, Exercice $exercice): Response
    {
        if ($this->isCsrfTokenValid('delete' . $exercice->getId(), $request->request->get('_token'))) {
            if ($exercice->getImage()) {
                $imagePath = $this->getParameter('exercices_directory') . '/' . $exercice->getImage();
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $workoutPlanId = $exercice->getWorkoutPlan()->getId();
            $this->em->remove($exercice);
            $this->em->flush();

            $this->addFlash('success', 'Exercice deleted successfully!');
            return $this->redirectToRoute('admin_workout_exercices', ['id' => $workoutPlanId]);
        }

        $this->addFlash('error', 'Invalid CSRF token');
        return $this->redirectToRoute('admin_workout_exercices', ['id' => $exercice->getWorkoutPlan()->getId()]);
    }

    private function uploadImage(?UploadedFile $imageFile, Exercice $exercise): void
    {
        if (!$imageFile) {
            return;
        }

        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

        try {
            $imageFile->move($this->getParameter('exercices_directory'), $newFilename);

            if ($exercise->getImage()) {
                $oldImage = $this->getParameter('exercices_directory') . '/' . $exercise->getImage();
                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }

            $exercise->setImage($newFilename);
        } catch (FileException $e) {
            $this->addFlash('error', 'Error uploading image');
        }
    }
}
