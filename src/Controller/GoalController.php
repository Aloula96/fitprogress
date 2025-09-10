<?php

namespace App\Controller;

use App\Entity\Goal;
use App\Enum\GoalType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

class GoalController extends AbstractController
{
    #[Route('/goal', name: 'goal', methods: ['GET', 'POST'])]
    public function goal(
        Request $request,
        EntityManagerInterface $em,
        Security $security,

    ): Response {
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Tu dois être connecté pour définir un objectif.');
        }

        if ($request->isMethod('POST')) {
            $selectedGoal = $request->request->get('goal'); // ex: "perte_de_poids"

            // Calcul automatique du poids idéal via IMC (IMC 22 par défaut)
            $height = $user->getHeight() / 100;
            $bmiReference = 22;
            $idealWeight = $bmiReference * ($height ** 2);

            // Récupérer un poids cible personnalisé si l’utilisateur l’a saisi
            $customTarget = $request->request->get('target_weight');

            $goal = new Goal();
            $goal->setType(GoalType::from($selectedGoal));
            $goal->setUser($user);
            $goal->setCreatedAt(new \DateTimeImmutable());


            // Soit on prend la valeur perso, soit on garde la suggestion
            if (!empty($customTarget)) {
                $goal->setTargetWeight((float) $customTarget);
            } else {
                $goal->setTargetWeight(round($idealWeight, 1));
            }

            $em->persist($goal);
            $em->flush();

            $this->addFlash('success', 'Ton objectif a été enregistré !');
            return $this->redirectToRoute('app_');
        }

        return $this->render('goal/index.html.twig', [
            'user' => $user
        ]);
    }
}
// 
