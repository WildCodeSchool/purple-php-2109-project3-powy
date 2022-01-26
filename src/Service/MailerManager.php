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
    public function sendProposal(Student $student): void
    {
        $user = $student->getUser();

        if ($user !== null) {
            $emailUser = $user->getEmail();
            if ($emailUser !== null) {
                $email = (new TemplatedEmail())
                ->from(new Address('noreply@powy.io', 'powy-registration'))
                ->to($emailUser)
                ->subject('Proposition de mentorat 🥳')
                ->htmlTemplate('emails/mentoring_proposal.html.twig');
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
}
