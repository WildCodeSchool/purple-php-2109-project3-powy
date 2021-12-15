<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d’un utilisateur de type “contributeur” (= auteur)
        $student = new User();
        $student->setEmail('student@monsite.com');
        $student->setRoles(['ROLE_STUDENT']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $student,
            'studentpassword'
        );

        $student->setPassword($hashedPassword);
        $manager->persist($student);

        // Création d’un utilisateur de type “administrateur”
        $mentor = new User();
        $mentor->setEmail('mentor@monsite.com');
        $mentor->setRoles(['ROLE_MENTOR']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $mentor,
            'mentorpassword'
        );
        $mentor->setPassword($hashedPassword);
        $manager->persist($mentor);

        // Sauvegarde des 2 nouveaux utilisateurs :
        $manager->flush();
    }
}
