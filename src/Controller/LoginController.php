<?php

namespace App\Controller;

use App\Form\EmailFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
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
        UserRepository $userRepository,
        MailerInterface $mailerInterface,
        EmailVerifier $emailVerifier
    ): Response {
        $form = $this->createForm(EmailFormType::class);
        $form->handleRequest($request);
        //check if there's a form to handle and fetch the email
        if ($form->isSubmitted() && $form->isValid()) {
            $emailUser = $form->get('email')->getData();

            //verify if there's a user registered with the email
            $user = $userRepository->findOneBy(['email' => $emailUser]);
            //if there's not user, notify the person
            if ($user === null) {
                $this->addFlash(
                    'warning',
                    "Aucun utilisateur n'est enregistré à cette adresse mail."
                );
                $this->redirectToRoute('reset_password');
            } elseif ($user !== null && is_string($emailUser)) {
                $emailVerifier->sendEmailConfirmation(
                    'change_password',
                    $user,
                    (new TemplatedEmail())
                    ->from(new Address('noreply@powy.io', 'Powy'))
                    ->to($emailUser)
                    ->subject('Mot de passe oublié')
                    ->htmlTemplate('login/changePasswordEmail.html.twig')
                );
                $this->addFlash(
                    'success',
                    "Vous allez recevoir un mail à votre adresse afin de modifier votre mot de passe."
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
    public function resetPassword(): Response
    {
        return $this->redirectToRoute('login');
    }
}
