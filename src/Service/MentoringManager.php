<?php

namespace App\Service;

use App\Entity\Mentoring;
use App\Entity\User;
use App\Repository\MentoringRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class MentoringManager
{
    private MentoringRepository $mentoringRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(MentoringRepository $mentoringRepository, EntityManagerInterface $entityManager)
    {
        $this->mentoringRepository = $mentoringRepository;
        $this->entityManager = $entityManager;
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
     * to use whenever you need to unaccept a mentoring
     */
    public function stopMentoring(Mentoring $mentoring): void
    {
        $mentoringToUpdate = $this->mentoringRepository->find($mentoring);
        if ($mentoringToUpdate !== null) {
            $mentoringToUpdate->setIsAccepted(false);
            $this->entityManager->flush();
        }
    }
}
