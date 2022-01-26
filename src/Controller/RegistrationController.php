<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Mentor;
use App\Entity\School;
use App\Entity\Student;
use App\Entity\User;
use App\Form\MentorType;
use App\Form\StudentType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\MailerManager;
use App\Service\MatchManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private EntityManagerInterface $entityManager;
    private MailerManager $mailerManager;

    public function __construct(
        EmailVerifier $emailVerifier,
        EntityManagerInterface $entityManager,
        MailerManager $mailerManager
    ) {
        $this->emailVerifier = $emailVerifier;
        $this->entityManager = $entityManager;
        $this->mailerManager = $mailerManager;
    }

    /**
     * @Route("/register/student", name="register_student")
     */
    public function registerStudent(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response {
        $login = $this->getUser();
        if ($login) {
            return $this->redirectToRoute('profile_index');
        }
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
                $this->entityManager->persist($student);
                $this->entityManager->persist($user);
                // if the school wasn't on the list and the student added a new name
                $school = $form->get('school')->getData();
                if ($school instanceof School && $school->getName() == 'Autre') {
                    $schoolName = $form->get('schoolAdd')->getData();
                    $newSchool = new School();
                    if ($schoolName !== null && is_string($schoolName)) {
                        $newSchool->setName($schoolName);
                        $student->setSchool($newSchool);
                        $this->entityManager->persist($newSchool);
                    }
                }
                $this->entityManager->flush();
            }
            // generate a signed url and email it to the user
            $this->mailerManager->sendVerifyRegistration($user);
            $this->redirectToRoute('login');
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
        UserPasswordHasherInterface $userPasswordHasher
    ): Response {
        $login = $this->getUser();
        if ($login) {
            return $this->redirectToRoute('profile_index');
        }
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
                $user->setRoles(['ROLE_MENTOR']);
                $this->entityManager->persist($mentor);
                $this->entityManager->persist($user);
                // if the company wasn't on the list and the mentor added a new name
                $company = $form->get('company')->getData();
                if ($company instanceof Company && $company->getName() == 'Autre') {
                    $companyName = $form->get('companyAdd')->getData();
                    $newCompany = new Company();
                    if ($companyName !== null && is_string($companyName)) {
                        $newCompany->setName($companyName);
                        $mentor->setCompany($newCompany);
                        $this->entityManager->persist($newCompany);
                    }
                }
                $this->entityManager->flush();
            }
            $this->mailerManager->sendVerifyRegistration($user);
            $this->redirectToRoute('login');
        }

        return $this->render('registration/register_mentor.html.twig', [
            'mentorRegistrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(
        Request $request,
        UserRepository $userRepository,
        MailerInterface $mailerInterface,
        MatchManager $matchManager
    ): Response {
        // get id from the link clicked by the user to confirm his or her address
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('home');
        }
        //fetch user by his/her id
        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('home');
        }
        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            if ($user instanceof User) {
                $this->emailVerifier->handleEmailConfirmation($request, $user);
                $user->setIsVerified(true);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $this->addFlash('success', 'Votre adresse a bien été vérifiée.');
                $emailUser = $user->getEmail();
                if (is_string($emailUser)) {
                    $email = (new Email())
                    ->from(new Address('noreply@powy.io', 'powy-registration'))
                    ->to($emailUser)
                    ->subject('Inscription validée 🥳 !')
                    ->html($this->renderView('emails/registration_email.html.twig', ['user' => $user]));
                    $mailerInterface->send($email);
                    if ($user->getStudent() !== null) {
                        //try to get a match with a mentor
                        $matchManager->match($user->getStudent());
                    }
                }
            }
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', $exception->getReason());

            return $this->redirectToRoute('home');
        }
        return $this->redirectToRoute('login');
    }
}
