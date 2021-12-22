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
        //create an user with student role
        $student = new User();
        $student->setEmail('student@monsite.com');
        $student->setRoles(['ROLE_STUDENT']);
        $student->setFirstname('Alice');
        $student->setLastname('Martin');
        $student->setGender('Féminin');
        $student->setAge('22');
        $student->setPhone('0234567890');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $student,
            'studentpassword'
        );
        $student->setPassword($hashedPassword);
        $manager->persist($student);

        //create an user with mentor role
        $mentor = new User();
        $mentor->setEmail('mentor@monsite.com');
        $mentor->setRoles(['ROLE_MENTOR']);
        $mentor->setFirstname('Héloïse');
        $mentor->setLastname('Durand');
        $mentor->setGender('Féminin');
        $mentor->setAge('42');
        $mentor->setPhone('0234567800');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $mentor,
            'mentorpassword'
        );
        $mentor->setPassword($hashedPassword);
        $manager->persist($mentor);

        //save all users
        $manager->flush();
    }
}
