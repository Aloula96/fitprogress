<?php

namespace App\Controller;

use App\Entity\BodyWeight;
use App\Repository\GoalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function show(Request $request, EntityManagerInterface $em, GoalRepository $goalRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Tu dois être connecté pour voir ton profil.');
        }

        // Récupérer l'objectif actuel
        $goal = $goalRepository->findOneBy(['user' => $user]);

        // Ajouter un nouveau poids si le formulaire est soumis
        if ($request->isMethod('POST')) {
            $newWeight = $request->request->get('weightValue');
            if ($newWeight) {
                $weight = new BodyWeight();
                $weight->setWeightValue((float)$newWeight);
                $weight->setUser($user);

                $em->persist($weight);
                $em->flush();

                $this->addFlash('success', 'Nouveau poids enregistré !');
                return $this->redirectToRoute('app_user');
            }
        }

        // Récupérer tous les poids de l'utilisateur
        $weights = $em->getRepository(BodyWeight::class)->findBy(
            ['user' => $user],
            ['recordedAt' => 'ASC']
        );

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'weights' => $weights,
            'goal' => $goal
        ]);
    }
}
