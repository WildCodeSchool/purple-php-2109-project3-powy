<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
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
                $student = new Student();
                $studentData = [$form->get('student')->getNormData()];
                // dd($studentData);
                // exit;
                if (
                    $studentData[0] instanceof Student &&
                    is_string($studentData[0]->getDreamJob()) &&
                    is_int($studentData[0]->getTopic1())
                ) {
                    $student->setScholarship($studentData[0]->getScholarship());
                    $student->setDreamJob($studentData[0]->getDreamJob());
                    $student->setDreamDescription($studentData[0]->getDreamDescription());
                    $student->setProfessionalSector($studentData[0]->getProfessionalSector());
                    $student->setSchool($studentData[0]->getSchool());
                    $student->setStudyLevel($studentData[0]->getStudyLevel());
                    $student->setTopic1($studentData[0]->getTopic1());
                    $student->setTopic2($studentData[0]->getTopic2());
                    $student->setTopic3($studentData[0]->getTopic3());
                    $user->setStudent($student);
                    $student->setUser($user);
                    $user->setRoles(['ROLE_STUDENT']);
                    $entityManager->persist($user);
                    $entityManager->persist($student);
                    $entityManager->flush();
                }
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
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                );
                    return $this->redirectToRoute('login');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            if ($this->getUser() instanceof UserInterface) {
                $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
            }
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre adresse a bien été vérifiée.');

        return $this->redirectToRoute('app_register');
    }
}
