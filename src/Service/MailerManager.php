<?php

namespace App\Service;

use App\Entity\Student;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailerManager extends AbstractController
{
    private MailerInterface $mailerInterface;

    public function __construct(MailerInterface $mailerInterface)
    {
        $this->mailerInterface = $mailerInterface;
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
}
