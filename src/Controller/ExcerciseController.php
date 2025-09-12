<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ExcerciseController extends AbstractController
{
    #[Route('/excercise', name: 'app_excercise')]
    public function index(): Response
    {
        return $this->render('excercise/index.html.twig', [
            'controller_name' => 'ExcerciseController',
        ]);
    }
}
