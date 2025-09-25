<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        // Create admin user
        $admin = new User();
        $admin->setEmail('admin@fitprogress.com');
        $admin->setName('Administrator');
        $admin->setAge(30);
        $admin->setHeight(175);
        $admin->setWeight(70);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->hasher->hashPassword($admin, 'admin123')
        );

        $manager->persist($admin);

        // Create regular user
        $user = new User();
        $user->setEmail('user@fitprogress.com');
        $user->setName('Test User');
        $user->setAge(25);
        $user->setHeight(170);
        $user->setWeight(65);
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(
            $this->hasher->hashPassword($user, 'user123')
        );

        $manager->persist($user);

        $manager->flush();
    }
}
