<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\User;
use App\Service\MatchManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MentorController extends AbstractController
{
    /**
     * @Route("/mentor", name="mentor_index")
     */
    public function index(): Response
    {
        return $this->render('mentor/index.html.twig');
    }

    /**
     * @Route("/mentor/mentoring", name="mentor_mentoring")
     */
    public function mentoring(MailerInterface $mailerInterface): Response
    {
        $emailUser = '';
        $user = $this->getUser();
        if ($user !== null) {
            if ($user instanceof User) {
                $emailUser = $user->getEmail();
            }
        }
        if (is_string($emailUser)) {
            $email = (new Email())
            ->from(new Address('noreply@powy.io', 'powy-mentoring'))
            ->to($emailUser)
            ->subject('Demande de mentorat 🥳 !')
            ->html($this->renderView('student/demande_email.html.twig'));
            $mailerInterface->send($email);
        }
        return $this->redirectToRoute('profile_index');
    }

    /**
     * @Route("/mentor/accept", name="mentor_accepted")
     */
    public function mentoringaccept(MatchManager $matchManager, MailerInterface $mailerInterface): Response
    {
        $user = $this->getUser();
        //email student send
        if ($user !== null) {
            if ($user instanceof User) {
                $emailUser = $user->getEmail();
                if (is_string($emailUser)) {
                    $student = $user->getStudent();
                    $email = (new Email())
                    ->from(new Address('noreply@powy.io', 'powy-mentoring'))
                    ->to($emailUser)
                    ->subject('Confirmation du mentorat !')
                    ->html($this->renderView('student/confirmation_email_mentorat.html.twig'));
                    $mailerInterface->send($email);
                    if ($student !== null) {
                        $matchManager->match($student);
                    }
                    $this->addFlash('success', 'Votre mentorat à été lancé avec succés !');
                }
            }
        }
        $emailMentor = '';
        //email mentor send
        if ($user instanceof User) {
            $emailMentor =  $user->getStudent()->getMentoring()->getMentor()->getUser()->getEmail();
        }
        if (is_string($emailMentor)) {
            $email = (new Email())
            ->from(new Address('noreply@powy.io', 'powy-mentoring'))
            ->to($emailMentor)
            ->subject('Confirmation de ton mentorat !')
            ->html($this->renderView('student/confirmation_email_mentorat.html.twig'));
            $mailerInterface->send($email);
        }
        return $this->redirectToRoute('profile_index');
    }

    /**
     * @Route("/mentor/refuse", name="mentor_refuse")
     */
    public function mentoringrefuse(): Response
    {
        $this->addFlash('danger', 'Le mentorat n\'a pas pu aboutir !');
        return $this->redirectToRoute('profile_index');
    }
}
