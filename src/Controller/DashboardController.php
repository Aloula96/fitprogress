<?php

namespace App\Controller;

use App\Entity\Goal;
use App\Repository\GoalRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(GoalRepository $goalRepository): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }
        /** @var \App\Entity\User $user */

        $user = $this->getUser();
        $nextSession = (new \DateTimeImmutable('tomorrow 7:00'))->format('l, H:i');


        $goal = $goalRepository->findOneBy(['user' => $user]);

        return $this->render('dashboard/index.html.twig', [
            'user' => $user,
            'nextSession' => $nextSession,
            'goal' => $goal,
        ]);
    }
}
