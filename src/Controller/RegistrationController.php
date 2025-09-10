<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\BodyWeight;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    // création d'un constructeur avec serializer pour le format json concernant la communication entre aplis
    public function __construct(
        private SerializerInterface $serializer
    ) {}

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            $security->login($user, AppCustomAuthenticator::class, 'main');
            return $this->redirectToRoute('goal');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/test', name: 'test')]
    public function imc(
        userRepository $userRepository
    ): Response {
        $user = $userRepository->find(1);

        $userJson = $this->serializer->serialize(
            $user,
            'json',
            ['groups' => 'user_read']
        );
        //dd($userJson);
        return $this->render('registration/test.html.twig', [
            'userJson' => $userJson,
        ]);
    }
    #[Route('/user', name: 'app_user')]
    public function show(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Récupérer l'évolution du poids
        $weights = $em->getRepository(BodyWeight::class)->findBy(['user' => $user], ['date' => 'ASC']);

        return $this->render('registration/show.html.twig', [
            'user' => $user,
            'weights' => $weights,
        ]);
    }
}
