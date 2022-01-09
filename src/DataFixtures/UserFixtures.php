<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public const USERSNUMBERFIXTURES = 10;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        //create 10 users with student role
        for ($i = 0; $i < self::USERSNUMBERFIXTURES; $i++) {
            $student = new User();
            //ex : student_0@monsite.com
            $student->setEmail('student' . $i . '@monsite.com');
            $student->setRoles(['ROLE_STUDENT']);
            $student->setFirstname('Alice' . $i);
            $student->setLastname('Inwonderland' . $i);
            $student->setGender('Féminin');
            $student->setAge('22');
            $student->setPhone('0234567890');
            $hashedPassword = $this->passwordHasher->hashPassword(
                $student,
                'studentpassword'
            );
            $student->setPassword($hashedPassword);
            $student->setStudent($this->getReference('student_' . $i));
            $manager->persist($student);
        }

        //create 10 users with mentor role
        for ($i = 0; $i < self::USERSNUMBERFIXTURES; $i++) {
            $mentor = new User();
            //ex : mentor_0@monsite.com
            $mentor->setEmail('mentor_' . $i . '@monsite.com');
            $mentor->setRoles(['ROLE_MENTOR']);
            $mentor->setFirstname('Jane ' . $i);
            $mentor->setLastname('Doe' . $i);
            $mentor->setGender('Féminin');
            $mentor->setAge('42');
            $mentor->setPhone('0234567800');
            $hashedPassword = $this->passwordHasher->hashPassword(
                $mentor,
                'mentorpassword'
            );
            $mentor->setPassword($hashedPassword);
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
