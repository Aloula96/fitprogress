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
    public function target(Request $request, EntityManagerInterface $em, Security $security, GoalRepository $goalRepository): Response
    {
        /** @var User $user */
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Tu dois être connecté pour définir un objectif.');
        }

        if ($request->isMethod('POST')) {
            $selectedGoal = $request->request->get('goal');
            $customTarget = $request->request->get('target_weight');

            // calcution of bmi 

            $height = $user->getHeight() / 100;
            $bmi = 22;
            $idealWeight = $bmi * ($height ** 2);

            // Créer un objectif
            $goal = new Goal();
            $goal->setType(GoalType::from($selectedGoal));
            $goal->setUser($user);
            $goal->setCreatedAt(new \DateTimeImmutable());

            // Poids cible
            if (!empty($customTarget)) {
                $goal->setTargetWeight((float)$customTarget);
            } else {
                $goal->setTargetWeight(round($idealWeight, 1));
            }

            $goal = $goalRepository->findOneBy(['user' => $user]);
            if ($goal !== null) {
                $em->remove($goal);
            }

            $em->persist($goal);
            $em->flush();

            $this->addFlash('success', 'Ton objectif a été enregistré !');
            return $this->redirectToRoute('app_user'); // change to your profile/home page
        }

        return $this->render('goal/index.html.twig', [
            'user' => $user,
        ]);
    }
}
