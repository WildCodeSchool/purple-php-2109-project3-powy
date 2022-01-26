<?php

namespace App\Service;

use App\Entity\Mentoring;
use App\Entity\Message;
use App\Entity\User;
use App\Repository\MentoringRepository;
use App\Repository\MessageRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class MentoringManager
{
    private MentoringRepository $mentoringRepository;
    private EntityManagerInterface $entityManager;
    private MessageRepository $messageRepository;

    public function __construct(
        MentoringRepository $mentoringRepository,
        EntityManagerInterface $entityManager,
        MessageRepository $messageRepository
    ) {
        $this->mentoringRepository = $mentoringRepository;
        $this->entityManager = $entityManager;
        $this->messageRepository = $messageRepository;
    }

    /**
     * return if an user (student or mentor) has an active mentoring or not
     */
    public function hasMentoring(User $user): bool
    {
        $todaysDate = new DateTime();

        $mentoring = null;
        if ($user->getMentor() === null && $user->getStudent() === null) {
            throw new Exception('User is neither a student or a mentor.');
        }
        if ($user->getMentor() !== null) {
            $mentoring = $user->getMentor()->getMentoring();
        } elseif ($user->getStudent() !== null) {
            $mentoring = $user->getStudent()->getMentoring();
        }
        if ($mentoring === null) {
            return false;
        }
        if ($mentoring->getIsAccepted() === false) {
            return false;
        }
        if ($mentoring->getEndingDtae() < $todaysDate) {
            return false;
        }
        return true;
    }
    /**
     * To use when a match is accepted by a student
     */
    public function startMentoring(Mentoring $mentoring): void
    {
        $startingDate = new DateTime();
        $endingDate = new DateTime();
        $endingDate->modify('+4 months');

        $mentoringToUpdate = $this->mentoringRepository->find($mentoring);
        if ($mentoringToUpdate !== null) {
            $mentoringToUpdate->setIsAccepted(true);
            $mentoringToUpdate->setStartingDate($startingDate);
            $mentoringToUpdate->setEndingDtae($endingDate);
            $this->entityManager->flush();
        }
    }

    /**
     * to use whenever you need to stop a mentoring
     * set ending date to today's date, is accepted to false and remove all messages.
     */
    public function stopMentoring(Mentoring $mentoring): void
    {
        $mentoringToUpdate = $this->mentoringRepository->find($mentoring);
        if ($mentoringToUpdate !== null) {
            $mentoringToUpdate->setIsAccepted(false);
            $mentoringToUpdate->setEndingDtae(new DateTime());
            $this->entityManager->flush();
            $this->removeMessages($mentoringToUpdate);
        }
    }

    /**
     * remove all messages for a specific mentoring
     */
    public function removeMessages(Mentoring $mentoring): void
    {
        $messages = $this->messageRepository->findBy(['mentoring' => $mentoring]);
        if ($messages !== null) {
            if (!empty($messages)) {
                foreach ($messages as $message) {
                    $this->entityManager->remove($message);
                    $this->entityManager->flush();
                }
            }
        }
    }
}
