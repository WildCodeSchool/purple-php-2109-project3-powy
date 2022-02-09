<?php

namespace App\Service;

use DateTime;
use Exception;
use App\Entity\User;
use App\Entity\Mentor;
use App\Entity\Message;
use App\Entity\Student;
use App\Entity\Mentoring;
use App\Repository\MessageRepository;
use App\Repository\MentoringRepository;
use Doctrine\ORM\EntityManagerInterface;

class MentoringManager
{

    private EntityManagerInterface $entityManager;
    private MentoringRepository $mentoringRepository;
    private MailerManager $mailerManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        MentoringRepository $mentoringRepository,
        MailerManager $mailerManager
    ) {
        $this->entityManager = $entityManager;
        $this->mentoringRepository = $mentoringRepository;
        $this->mailerManager = $mailerManager;
    }

    /**
     * return mentoring or null depends if a user is a student or a mentor
     */
    public function fetchMentoring(User $user): ?Mentoring
    {
        $mentoring = null;
        if (!$this->hasMentoring($user)) {
            throw new Exception("User doesn't have any active mentoring.");
        } elseif ($user->getMentor() !== null) {
            $mentoring = $user->getMentor()->getMentoring();
        } elseif ($user->getStudent() !== null) {
            $mentoring = $user->getStudent()->getMentoring();
        }
        return $mentoring;
    }

    /**
     * return if an user (student or mentor) has an active mentoring or not
     */
    public function hasMentoring(User $user): bool
    {
        $mentoring = null;

        //check if user is a student or a mentor
        if ($user->getMentor() === null && $user->getStudent() === null) {
            throw new Exception('User is neither a student or a mentor.');
        }
        //check if there is an active mentoring for a student
        if ($user->getStudent() !== null) {
            $mentoring = $user->getStudent()->getMentoring();
            if ($mentoring !== null) {
                return true;
            }
        }
        //check if there is an active mentoring for a mentor
        if ($user->getMentor() !== null) {
            $mentoring = $user->getMentor()->getMentoring();
            if ($mentoring !== null) {
                return true;
            }
        }
        //there is no active mentoring
        return false;
    }
    /**
     * To use when a match is accepted by a student
     */
    public function startMentoring(Mentoring $mentoring): void
    {
        $startingDate = new DateTime();
        $endingDate = new DateTime();
        $endingDate->modify('+4 months');

        if ($mentoring !== null) {
            $mentoring->setIsAccepted(true);
            $mentoring->setStartingDate($startingDate);
            $mentoring->setEndingDtae($endingDate);
            $this->entityManager->flush();
        }
    }

    /**
     * to use whenever you need to stop a mentoring
     * set ending date to today's date, is accepted to false and remove all messages.
     */
    public function stopMentoring(Mentoring $mentoring): void
    {
        if ($mentoring !== null) {
            $mentoring->setIsAccepted(false);
            $mentoring->setEndingDtae(new DateTime());
            $this->entityManager->flush();
            $this->removeMessages($mentoring);
        }
    }

    /**
     * remove all messages for a specific mentoring
     */
    public function removeMessages(Mentoring $mentoring): void
    {
        $messages = $mentoring->getMessages();

        if ($messages !== null) {
            foreach ($messages as $message) {
                $this->entityManager->remove($message);
                $this->entityManager->flush();
            }
        }
    }

    /**
     * end all mentoring with expired date
     */
    public function endExpiredMentoring(): void
    {
        $mentorings = $this->mentoringRepository->findAll();

        if ($mentorings !== null) {
            foreach ($mentorings as $mentoring) {
                if ($mentoring->getEndingDtae() < new DateTime() && $mentoring->getEndingDtae() !== null) {
                    $this->stopMentoring($mentoring);
                }
            }
        }
    }
    /**
     * create a pending mentoring relationship
     */
    public function initiateMentoring(Student $student, Mentor $mentor, int $topic): void
    {
        $mentoring = new Mentoring();
        $mentoring->setStudent($student);
        $mentoring->setMentor($mentor);
        $mentoring->setMentoringTopic($topic);
        $this->entityManager->persist($mentoring);
        $this->entityManager->flush();
        //send an email with a link where the studennt can accept or refuse the mentoring
        $this->mailerManager->sendProposal($student, $mentoring);
    }
}
