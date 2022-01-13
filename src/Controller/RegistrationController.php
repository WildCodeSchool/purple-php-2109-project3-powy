<?php

namespace App\Controller;

use App\Entity\Mentor;
use App\Entity\Student;
use App\Entity\User;
use App\Form\MentorType;
use App\Form\RegistrationFormType;
use App\Form\StudentType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register/student", name="register_student")
     */
    public function registerStudent(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $student = new Student();
        $user = new User();
        $student->setUser($user);
        $form = $this->createForm(StudentType::class, $student);
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
                $entityManager->persist($student);
                $entityManager->persist($user);
                $entityManager->flush();
            }
        // generate a signed url and email it to the user
            $emailUser = $user->getEmail();
            if (is_string($emailUser)) {
                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                    ->from(new Address('noreply@powy.io', 'powy-registration'))
                    ->to($emailUser)
                    ->subject('Merci de confirmer votre adresse mail pour terminer votre inscription.')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                );
                $this->addFlash(
                    'warning',
                    'Un email va vous être envoyé afin de finaliser votre inscription.'
                );
                return $this->redirectToRoute('login');
            }
        }

        return $this->render('registration/register_student.html.twig', [
            'studentRegistrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register/mentor", name="register_mentor")
     */
    public function registerMentor(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $mentor = new Mentor();
        $user = new User();
        $mentor->setUser($user);
        $form = $this->createForm(MentorType::class, $mentor);
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
                $entityManager->persist($mentor);
                $entityManager->persist($user);
                $entityManager->flush();
            }
        // generate a signed url and email it to the user
            $emailUser = $user->getEmail();
            if (is_string($emailUser)) {
                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                    ->from(new Address('noreply@powy.io', 'powy-registration'))
                    ->to($emailUser)
                    ->subject('Merci de confirmer votre adresse mail pour terminer votre inscription.')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                );
                $this->addFlash(
                    'warning',
                    'Un email va vous être envoyé afin de finaliser votre inscription.'
                );
                return $this->redirectToRoute('login');
            }
        }

        return $this->render('registration/register_mentor.html.twig', [
            'mentorRegistrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            if ($this->getUser() instanceof UserInterface) {
                $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
            }
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }
        $this->addFlash('success', 'Votre adresse a bien été vérifiée.');
        return $this->redirectToRoute('login');
    }
}
