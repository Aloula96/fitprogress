<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestUserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        // Create admin user
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setName('Admin User');  // Add this line
        $admin->setAge(30);             // Add if required
        $admin->setHeight(175);         // Add if required  
        $admin->setWeight(70);          // Add if required
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->hasher->hashPassword($admin, 'testpass')
        );

        $manager->persist($admin);

        // Create a regular test user
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setName('Test User');
        $user->setAge(25);
        $user->setHeight(170);
        $user->setWeight(65);
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(
            $this->hasher->hashPassword($user, 'testpass')
        );

        $manager->persist($user);

        $manager->flush();
    }
}
