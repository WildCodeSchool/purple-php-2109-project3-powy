<?php

namespace App\Service;

use App\Entity\Mentor;
use App\Entity\Mentoring;
use App\Entity\Student;
use App\Repository\MentorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class MatchManager
{
    /**
     * first version of matching (by professionnal sector only)
     */
    public function match(
        Student $studentToMatch,
        MentorRepository $mentorRepository,
        EntityManagerInterface $entityManager
    ): void {
        //check if student has already a mentor
        if ($studentToMatch->getMentoring() === null) {
            //find a mentor with same ProfessionnalSector than the student to match
            $matchingMentor = $mentorRepository->findOneBy([
                'professionalSector' => $studentToMatch->getProfessionalSector(),
                'mentoring' => null]);

            //creation of a new mentoring relation
            $mentoring = new Mentoring();
            $mentoring->setStudent($studentToMatch);
            $mentoring->setMentor($matchingMentor);
            $entityManager->flush();
        } else {
            throw new Exception("This student already has an active mentoring");
        }
    }
}
