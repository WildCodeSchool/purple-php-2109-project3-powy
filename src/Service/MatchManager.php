<?php

namespace App\Service;

use App\Entity\Mentoring;
use App\Entity\Student;
use App\Repository\MentorRepository;
use App\Repository\StudentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * This will suppress all the PMD warnings in
 * this class because af cylomatic complexity at 11 on matchByTopic
 *
 * @SuppressWarnings(PHPMD)
 */

class MatchManager
{
    private MentorRepository $mentorRepository;
    private EntityManagerInterface $entityManager;
    private MailerManager $mailerManager;
    private MentoringManager $mentoringManager;
    private StudentRepository $studentRepository;
    private ?int $topicMatched;

    public function __construct(
        MentorRepository $mentorRepository,
        MailerManager $mailerManager,
        EntityManagerInterface $entityManager,
        MentoringManager $mentoringManager,
        StudentRepository $studentRepository
    ) {
        $this->mentorRepository = $mentorRepository;
        $this->entityManager = $entityManager;
        $this->mailerManager = $mailerManager;
        $this->mentoringManager = $mentoringManager;
        $this->studentRepository = $studentRepository;
    }

    /**
     * return an array with the 3 topics choose by a student
     */
    public function getTopicsToMatch(Student $studentToMatch): array
    {
        if ($studentToMatch->getTopic() !== null) {
            $studentTopics = [
                "topic 1" => $studentToMatch->getTopic()->getTopic1(),
                "topic 2" => $studentToMatch->getTopic()->getTopic2(),
                "topic 3" => $studentToMatch->getTopic()->getTopic3(),
            ];
            return $studentTopics;
        }
        return [];
    }

    /**
     * return an array of mentors with no active mentoring, matching with one of the studentTopics, by priority :
     * studentTopic1 > studentTopic2 > studentTopic3
     */
    public function matchByTopic(Student $studentToMatch): array
    {
        $mentors = [];
        $studentTopics = $this->getTopicsToMatch($studentToMatch);

        if (!empty($studentTopics)) {
            //try matching by topic 1
            $mentors = $this->mentorRepository->findMentorsByTopic($studentTopics["topic 1"]);
            if (!empty($mentors)) {
                $this->topicMatched = $studentTopics["topic 1"];
            }
        }
        //if no match, try matching by topic 2
        if (
            empty($mentors)
            && $studentToMatch->getTopic() !== null
            && $studentToMatch->getTopic()->getTopic2() !== null
        ) {
            $mentors = $this->mentorRepository->findMentorsByTopic($studentTopics["topic 2"]);
            if (!empty($mentors)) {
                $this->topicMatched = $studentTopics["topic 2"];
            }
        }

        //if no match, try matching by topic 3
        if (
            empty($mentors)
            && $studentToMatch->getTopic() !== null
            && $studentToMatch->getTopic()->getTopic3() !== null
        ) {
            $mentors = $this->mentorRepository->findMentorsByTopic($studentTopics["topic 3"]);

            if (!empty($mentors)) {
                $this->topicMatched = $studentTopics["topic 3"];
            }
        }

        return $mentors;
    }
    /**
     * Match a student and a mentor by priority topics and same professionnal sector
     * if there is no mentor with same professional sector, the student will match only by topics
     */
    public function match(Student $studentToMatch): void
    {
        $mentorsBySector = [];
        $user = $studentToMatch->getUser();
        //check if student has already a mentor
        if ($user !== null) {
            if ($this->mentoringManager->hasMentoring($user) === false) {
                //try to match by topic
                $matchingMentors = $this->matchByTopic($studentToMatch);
                //check if there is a match by topic
                if (!empty($matchingMentors)) {
                    //try to find a Mentor with same professionalSector than student to match
                    foreach ($matchingMentors as $matchingMentor) {
                        if ($matchingMentor->getProfessionalSector() === $studentToMatch->getProfessionalSector()) {
                            $mentorsBySector[] = $matchingMentor;
                        }
                    }
                    // if no match by professional sector, get the first mentor of the list
                    if (empty($mentorsBySector)) {
                        $matchingMentor = $matchingMentors[0];
                    } else {
                        //there is a match by sector, get the first mentor of the list
                        $matchingMentor = $mentorsBySector[0];
                    }

                    //creation of a new mentoring relation
                    $mentoring = new Mentoring();
                    $mentoring->setStudent($studentToMatch);
                    $mentoring->setMentor($matchingMentor);
                    $mentoring->setMentoringTopic($this->topicMatched);
                    $this->entityManager->flush();
                    //sending mentoring proposition to student
                    $this->mailerManager->sendProposal($studentToMatch);
                }
            }
        }
    }

    /**
     * look for all students with no active mentoring and try to find one
     */
    public function checkForMatches(): void
    {
        $students = $this->studentRepository->findAll();

        if ($students !== null) {
            foreach ($students as $student) {
                $user = $student->getUser();
                if ($user !== null) {
                    if (!$this->mentoringManager->hasMentoring($user)) {
                        $this->match($student);
                    }
                }
            }
        }
    }
}
