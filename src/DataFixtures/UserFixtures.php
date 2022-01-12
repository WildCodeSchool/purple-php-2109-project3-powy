<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public const USERSPERROLE = 10;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        //creation of 10 users with student role
        for ($i = 0; $i < self::USERSPERROLE; $i++) {
            $student = new User();
            $student->setEmail('student' . $i . '@monsite.com');
            $student->setRoles(['ROLE_STUDENT']);
            $student->setFirstname('Alice' . $i);
            $student->setLastname('Martin' . $i);
            $student->setGender('Female');
            $student->setAge('22');
            $student->setPhone('0234567890');
            $hashedPassword = $this->passwordHasher->hashPassword(
                $student,
                'Studentpassword!' . $i
            );
            $student->setPassword($hashedPassword);
            //10 students available in MentorFixtures
            $student->setStudent($this->getReference('student_' . $i));
            $manager->persist($student);
        }

        //creation of 10 users with mmentor role
        for ($i = 0; $i < self::USERSPERROLE; $i++) {
            $mentor = new User();
            $mentor->setEmail('mentor' . $i . '@monsite.com');
            $mentor->setRoles(['ROLE_MENTOR']);
            $mentor->setFirstname('Héloïse' . $i);
            $mentor->setLastname('Durand' . $i);
            $mentor->setGender('Female');
            $mentor->setAge('42');
            $mentor->setPhone('0234567800');
            $hashedPassword = $this->passwordHasher->hashPassword(
                $mentor,
                'Mentorpassword!' . $i
            );
            $mentor->setPassword($hashedPassword);
            //10 mentors available in MentorFixtures
            $mentor->setMentor($this->getReference('mentor_' . $i));
            $manager->persist($mentor);
        }

        //save all users
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            StudentFixtures::class,
            MentorFixtures::class
        ];
    }
}
