<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\BodyWeight;
use App\Repository\GoalRepository;
use App\Repository\BodyWeightRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProfileController extends AbstractController
{
    public function __construct(private SerializerInterface $serializer) {}


    #[Route('/user', name: 'app_profile')]
    public function show(Request $request, EntityManagerInterface $em, GoalRepository $goalRepository,  BodyWeightRepository $bodyWeightRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
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
        $stats = $bodyWeightRepository->findBy(['user' => 9]);


        $statsJson = $this->serializer->serialize(
            $stats,
            'json',
            ['groups' => 'user_read']
        );


        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'weights' => $weights,
            'goal' => $goal,
            'targetWeight' => $targetWeight,
            'workoutPlan' => $goal ? $goal->getWorkoutPlan() : null,
            'statsJson' => $statsJson,
        ]);
    }

    #[Route('/user/{id}/stats', name: 'app_stats')]
    public function stats(
        BodyWeightRepository $bodyWeightRepository,
        int $id
    ): Response {
        $stats = $bodyWeightRepository->findBy(['user' => $id]);


        $statsJson = $this->serializer->serialize(
            $stats,
            'json',
            ['groups' => 'weight_read']
        );

        return $this->render('profile/stats.html.twig', [
            'statsJson' => $statsJson,

        ]);
    }
}
