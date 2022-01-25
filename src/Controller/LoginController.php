<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditPasswordType;
use App\Form\EmailFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class LoginController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }
    /**
     * @Route("/login", name="login")
     */

    public function index(AuthenticationUtils $authenticationUtils, Request $request): Response
    {

        $user = $this->getUser();
        if ($user) {
            return $this->redirectToRoute('profile_index');
        }

         // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
          ]);
    }

    /**
     * @Route("/login/reset", name="reset_password")
     */
    public function askEmailForResetPassword(
        Request $request,
        UserRepository $userRepository
    ): Response {
        $form = $this->createForm(EmailFormType::class);
        $form->handleRequest($request);
        //check if there's a form to handle and fetch the email
        if ($form->isSubmitted() && $form->isValid()) {
            $emailUser = $form->get('email')->getData();

            //verify if there's a user registered with the email
            $user = $userRepository->findOneBy(['email' => $emailUser]);
            //if there's not user, notify the person
            if (($user !== null && !$user->getIsVerified()) || $user === null) {
                $this->addFlash(
                    'warning',
                    "Aucun utilisateur n'est enregistré à cette adresse mail."
                );
                $this->redirectToRoute('reset_password');
            } elseif ($user !== null && is_string($emailUser)) {
                //send an email with a link that will redirect the user on a form to change his/her password
                $this->emailVerifier->sendEmailConfirmation(
                    'change_password',
                    $user,
                    (new TemplatedEmail())
                    ->from(new Address('noreply@powy.io', 'Powy'))
                    ->to($emailUser)
                    ->subject('Mot de passe oublié')
                    ->htmlTemplate('emails/changePasswordEmail.html.twig')
                );
                $this->addFlash(
                    'success',
                    "Tu vas recevoir un message à ton adresse mail afin de modifier ton mot de passe."
                );
                $this->redirectToRoute('login');
            }
        }
        return $this->render('login/reset.html.twig', [
            'EmailFormType' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login/password" ,name="change_password")
     */
    public function resetPassword(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        MailerInterface $mailerInterface
    ): Response {
        // get id from the link clicked by the user in the email
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('home');
        }
        //fetch user by his/her id
        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('home');
        }
        //Modification form password user
        $formpassword = $this->createForm(EditPasswordType::class, $user);
        $formpassword->handleRequest($request);
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', $exception->getReason());
            return $this->redirectToRoute('home');
        }
        //check if there's a form to handle
        if ($formpassword->isSubmitted() && $formpassword->isValid()) {
            //get the user email
            $emailUser = $user->getEmail();
            //get the password
            $plaintextPassword = $formpassword['plainPassword']->getData();
            if (is_string($plaintextPassword) && is_string($emailUser)) {
                //hash the password to add it in the database
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);
                $entityManager->flush();
                //send an email to confirm the changes
                $email = (new Email())
                ->from(new Address('noreply@powy.io', 'Powy'))
                ->to($emailUser)
                ->subject('Changement de mot de passe')
                ->html($this->renderView('emails/password_changed.html.twig', ['user' => $user]));
                $mailerInterface->send($email);
                $this->addFlash("success", "Ton nouveau mot de passe a bien été enregistré.");
                return $this->redirectToRoute('login');
            }
        }

        return $this->renderForm('login/change_password.html.twig', [
            'formpassword' => $formpassword,
        ]);
    }
}
