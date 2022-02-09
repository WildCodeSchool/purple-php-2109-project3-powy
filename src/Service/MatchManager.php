<?php

namespace App\Service;

use App\Entity\Student;
use App\Repository\MentorRepository;

class MatchManager
{
    private MentorRepository $mentorRepository;
    private MentoringManager $mentoringManager;
    private int $topicMatched;

    public function __construct(
        MentorRepository $mentorRepository,
        MentoringManager $mentoringManager
    ) {
        $this->mentorRepository = $mentorRepository;
        $this->mentoringManager = $mentoringManager;
    }

    /*
    * return an array with mentors who has a topic in commun with a student, in a list of 3 topics
    */
    public function matchByTopic(Student $studentToMatch): array
    {
        $topics = $studentToMatch->getTopic();
        $availableMentors = [];
        $mentors = [];

        if ($topics === null) {
            return [];
        }
        //get all topics in an arrray, if they are not null (only topic1 can't be null)
        $studentTopics[] = $topics->getTopic1();
        if ($topics->getTopic2() !== null) {
            $studentTopics[] = $topics->getTopic2();
        }
        if ($topics->getTopic3() !== null) {
            $studentTopics[] = $topics->getTopic3();
        }
        //try to find all mentors who match with topic1, if no result try topic2, if no result try topic3
        foreach ($studentTopics as $topic) {
            $mentors = $this->mentorRepository->findMentorsByTopic($topic);
            foreach ($mentors as $mentor) {
                //register in a new array only mentors who has no active mentorings
                if ($mentor->getMentoring() === null) {
                    $availableMentors[] = $mentor;
                }
            }
            //keep the matching topic, to be used in match method
            $this->topicMatched = $topic;

            if (!empty($availableMentors)) {
                return $availableMentors;
            }
        }
        //none of the students topic found a match or there is no available mentors for thoses topics
        return [];
    }

    /*
    * return an array with mentors who has professional sector in commun with a student
    */
    public function matchByProfessionalSector(Student $studentToMatch, array $mentors): array
    {
        $matchingMentors = [];
        foreach ($mentors as $mentor) {
            if ($studentToMatch->getProfessionalSector() === $mentor->getProfessionalSector()) {
                $matchingMentors[] = $mentor;
            }
        }
        return $matchingMentors;
    }

    /**
     * Match a student and a mentor by topics with an order (topic1>topic2>topic3) and same professionnal sector
     * if there is no mentor with same professional sector, the student will find a match only by topics
     */
    public function match(Student $studentToMatch): void
    {
        if ($studentToMatch->getMentoring() === null) {
            //get all available mentors with a matching topic
            $mentorsByTopic = $this->matchByTopic($studentToMatch);
            if (!empty($mentorsByTopic)) {
                //among mentors available with a matching topic, try to find thoses with same professionalSector
                $mentorsBySector = $this->matchByProfessionalSector($studentToMatch, $mentorsByTopic);
                if (!empty($mentorsBySector)) {
                    //there is at least one mentor with same professionnal sector, match with the first of the list
                    $mentor = $mentorsBySector[0];
                } else {
                    //no mentor with the same professionnal sector : match with the 1st one with a topic in commun
                    $mentor = $mentorsByTopic[0];
                }
                $this->mentoringManager->initiateMentoring($studentToMatch, $mentor, $this->topicMatched);
            }
        }
    }
}
