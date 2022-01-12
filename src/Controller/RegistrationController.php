<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private MailerInterface $mailer;

    public function __construct(
        VerifyEmailHelperInterface $helper,
        MailerInterface $mailer
    ) {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
        $student = new Student();
        $user->setStudent($student);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        // encode the plain password
            $plainPassword = $form->get('plainPassword')->getData();
            if (is_string($plainPassword)) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $plainPassword,
                    )
                );
                $user->setRoles(['ROLE_STUDENT']);
                $entityManager->persist($user);
                $entityManager->persist($student);
                $entityManager->flush();
            }

            $emailUser = $user->getEmail();
            if (is_string($emailUser)) {
                // generate a signed url and email it to the user
                $signatureComponents = $this->verifyEmailHelper->generateSignature(
                    'app_verify_email',
                    strval($user->getId()),
                    $emailUser,
                    ['id' => $user->getId()]
                );
                //send email to verify user's email
                $email = new TemplatedEmail();
                $email->from(new Address('noreply@powy.io', 'powy-registration'));
                $email->to($emailUser);
                $email->subject('Confirme ton inscription 🙌');
                $email->htmlTemplate('registration/confirmation_email.html.twig');
                $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);
                $this->mailer->send($email);
                $this->addFlash(
                    'warning',
                    'Un email va vous être envoyé afin de finaliser votre inscription.'
                );
                return $this->redirectToRoute('login');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $id = $request->get('id');
        // Verify the user id exists and is not null
        if (null === $id) {
            return $this->redirectToRoute('home');
        }

        $user = $userRepository->find($id);

        // Ensure the user exists in persistence
        if (null === $user) {
            return $this->redirectToRoute('home');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            if ($this->getUser() instanceof UserInterface && is_string($user->getEmail())) {
                $this->verifyEmailHelper->validateEmailConfirmation(
                    $request->getUri(),
                    strval($user->getId()),
                    $user->getEmail()
                );
            }
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', $exception->getReason());
            return $this->redirectToRoute('app_register');
        }

        $user->setIsVerified(true);
        $entityManager->flush();

        $emailUser = $user->getEmail();
        if (is_string($emailUser)) {
            $email = new TemplatedEmail();
            $email->from(new Address('noreply@powy.io', 'powy-registration'));
            $email->to($emailUser);
            $email->subject('Inscription validée 🥳 !');
            $email->htmlTemplate('registration/registration_email.html.twig');
            $email->context(['user' => $user]);
            $this->mailer->send($email);
        }

        $this->addFlash(
            'success',
            'Votre adresse a bien été vérifiée. Vous pouvez maintenant vous connecter.'
        );

        return $this->redirectToRoute('login');
    }
}
