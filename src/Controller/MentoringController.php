<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\User;
use App\Service\MailerManager;
use App\Service\MatchManager;
use App\Service\MentoringManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/mentoring", name="mentoring_")
*/
class MentoringController extends AbstractController
{
    private MentoringManager $mentoringManager;

    public function __construct(MentoringManager $mentoringManager)
    {
        $this->mentoringManager = $mentoringManager;
    }
    /**
     * @Route("/accepted", name="accepted")
     * Method called when a student click on acceptation link sent by email
     */
    public function mentoringAccepted(MailerManager $mailerManager): Response
    {
        //check if user is connected
        $studentUser = $this->getUser();
        if ($studentUser === null) {
            $this->addFlash('danger', 'Veuillez vous connecter avant d\'accepter le mentorat par email');
        }

        if ($studentUser instanceof User) {
            $student = $studentUser->getStudent();
            if ($student !== null) {
                $mentoring = $student->getMentoring();
                if ($mentoring !== null) {
                    //set mentoring to true, startingDtae = date acceptation and ending date + 4 months
                    $this->mentoringManager->startMentoring($mentoring);
                    //send an e-mail to the student
                    $mailerManager->sendAcceptation($studentUser);
                    $this->addFlash(
                        'success',
                        'Votre mentorat a commencé, rendez-vous sur votre profil.
                         Votre mentor a été prévenu par e-mail !'
                    );
                    ///send an email to his mentor
                    $mentor = $mentoring->getMentor();
                    if ($mentor !== null) {
                        $mentorUser = $mentor->getUser();
                        if ($mentorUser instanceof User) {
                            $mailerManager->sendAcceptation($mentorUser);
                        }
                    }
                }
            }
        }
        return $this->redirectToRoute('profile_index');
    }

    /**
     * @Route("/refused", name="refused")
     * Method called when a student click on decline link sent by email
     */
    public function mentoringRefused(MatchManager $matchManager): Response
    {
        //check if user is connected
        $studentUser = $this->getUser();

        if ($studentUser === null) {
            $this->addFlash('danger', 'Veuillez vous connecter avant d\'accepter ou refuser le mentorat par email');
        }

        if ($studentUser instanceof User) {
            $student = $studentUser->getStudent();
            if ($student !== null) {
                $mentoring = $student->getMentoring();
                if ($mentoring !== null) {
                    $this->addFlash(
                        'danger',
                        'Vous n\'avez pas accepté le mentorat, nous vous proposerons un
                         nouveau mentor dès que possible !'
                    );
                    $this->mentoringManager->stopMentoring($mentoring);
                    $matchManager->match($student);
                }
            }
        }

        return $this->redirectToRoute('profile_index');
    }
}
