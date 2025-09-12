<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\BodyWeight;
use App\Repository\GoalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    #[Route('/user', name: 'app_profile')]
    public function show(Request $request, EntityManagerInterface $em, GoalRepository $goalRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to view your profile.');
        }


        // Get the current goal
        $goal = $goalRepository->findOneBy(['user' => $user]);
        $targetWeight = $goal ? $goal->getTargetWeight() : null;


        if ($request->isMethod('POST')) {
            $newWeight = $request->request->get('weightValue');

            if ($newWeight) {
                $weight = new BodyWeight();
                $weight->setWeightValue((float) $newWeight);
                $weight->setUser($user);

                $em->persist($weight);
                $em->flush();

                $this->addFlash('success', 'Nouveau poids enregistré !');

                return $this->redirectToRoute('app_profile');
            }
        }

        // Get all weights for the user
        $weights = $em->getRepository(BodyWeight::class)->findBy(
            ['user' => $user],
            ['recordedAt' => 'ASC']
        );

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'weights' => $weights,
            'goal' => $goal,
            'targetWeight' => $targetWeight,
            'workoutPlan' => $goal ? $goal->getWorkoutPlan() : null,
        ]);
    }
}
