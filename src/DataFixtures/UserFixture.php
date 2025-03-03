<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture implements FixtureGroupInterface
{
    public function __construct(private UserPasswordHasherInterface $hasher){}

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@gmai.com');
        $admin->setUsername('admin'); // Set username
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, '1234'));
        $manager->persist($admin);

        $admin2 = new User();
        $admin2->setEmail('admin2@gmai.com');
        $admin2->setUsername('admin2'); // Set username
        $admin2->setPassword($this->hasher->hashPassword($admin2, '1234'));
        $manager->persist($admin2);

        for ($i = 0; $i <= 5; $i++) {
            $user = new User();
            $user->setEmail("user$i@gmail.com");
            $user->setUsername("user$i"); // Set username
            $user->setPassword($this->hasher->hashPassword($user, 'user'));
            $manager->persist($user);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['user'];
    }
}
