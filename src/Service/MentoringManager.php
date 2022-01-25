<?php

namespace App\Service;

use App\Entity\Mentoring;
use App\Entity\User;
use Exception;

class MentoringManager
{
    /**
     * This method return if an user (student or mentor) has an active mentoring or not
     */

    public function checkMentoring(User $user): ?Mentoring
    {
        $mentoring = null;
        if ($user->getMentor() === null && $user->getStudent() === null) {
            throw new Exception('User is neither a student or a mentor.');
        }
        if ($user->getMentor() !== null) {
            $mentoring = $user->getMentor()->getMentoring();
        } elseif ($user->getStudent() !== null) {
            $mentoring = $user->getStudent()->getMentoring();
        }
        return $mentoring;
    }
}
