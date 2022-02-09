<?php

namespace App\Service;

use App\Entity\Mentoring;
use App\Entity\Student;
use App\Entity\User;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailerManager extends AbstractController
{
    private MailerInterface $mailerInterface;
    private EmailVerifier $emailVerifier;

    public function __construct(MailerInterface $mailerInterface, EmailVerifier $emailVerifier)
    {
        $this->mailerInterface = $mailerInterface;
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * After a match this method send an email to a student to propose a mentoring.
     */
    public function sendProposal(Student $student, Mentoring $mentoring): void
    {
        $user = $student->getUser();
        if ($user !== null) {
            $emailUser = $user->getEmail();
            if ($emailUser !== null) {
                $email = (new Email())
                ->from(new Address('noreply@powy.io', 'powy-registration'))
                ->to($emailUser)
                ->subject('Proposition de mentorat 🥳')
                ->html($this->renderView('emails/mentoring_proposal.html.twig', [
                    'user' => $user,
                    'mentoring' => $mentoring
                ]));
                $this->mailerInterface->send($email);
            }
        }
    }

    /**
     * when a mentoring is accepted by student send a confirmation email to a mentor or
     */
    public function sendAcceptation(User $user): void
    {
        $emailUser = $user->getEmail();
        if ($emailUser !== null) {
            $email = (new TemplatedEmail())
            ->from(new Address('noreply@powy.io', 'powy-registration'))
            ->to($emailUser)
            ->subject('Confirmation du mentorat 🥳')
            ->htmlTemplate('emails/mentoring_confirmation.html.twig');
            $this->mailerInterface->send($email);
        }
    }

    /**
     * mail send after the registration, with link to confirm e-mail adress
     */
    public function sendVerifyRegistration(User $user): void
    {
        $emailUser = $user->getEmail();
        if (is_string($emailUser)) {
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                ->from(new Address('noreply@powy.io', 'powy-registration'))
                ->to($emailUser)
                ->subject('Confirme ton inscription 🙌')
                ->htmlTemplate('emails/confirmation_email.html.twig')
            );
            $this->addFlash(
                'warning',
                'Un email va vous être envoyé afin de finaliser votre inscription.'
            );
        }
    }

     /**
     * mail send after the e-mail adress confirmation
     */
    public function sendConfirmationRegistration(user $user): void
    {
        $emailUser = $user->getEmail();
        if (is_string($emailUser)) {
            $email = (new Email())
            ->from(new Address('noreply@powy.io', 'powy-registration'))
            ->to($emailUser)
            ->subject('Inscription validée 🥳 !')
            ->html($this->renderView('emails/registration_email.html.twig', ['user' => $user]));
            $this->mailerInterface->send($email);
        }
    }

    // email when user deletes her/his account
    public function deleteAccount(User $user): void
    {
        $emailUser = $user->getEmail();
        if (is_string($emailUser)) {
            $email = (new Email())
            ->from(new Address('noreply@powy.io', 'Powy'))
            ->to($emailUser)
            ->subject('Suppression de compte')
            ->html($this->renderView('emails/delete_account_email.html.twig', ['user' => $user]));
            $this->mailerInterface->send($email);
        }
    }

    public function sendMentoringEnded(User $user): void
    {
        $emailUser = $user->getEmail();
        if (is_string($emailUser)) {
            $email = (new Email())
            ->from(new Address('noreply@powy.io', 'Powy'))
            ->to($emailUser)
            ->subject('Suppression de compte')
            ->html($this->renderView('emails/mentoring_ended_email.html.twig', ['user' => $user]));
            $this->mailerInterface->send($email);
        }
    }
}
