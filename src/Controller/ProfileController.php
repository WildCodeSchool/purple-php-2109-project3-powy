<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditPasswordType;
use App\Form\EditProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile_index")
     * @IsGranted("ROLE_USER")
     */
    public function profile(UserRepository $userRepository): Response
    {
        if ($this->getUser() instanceof UserInterface) {
            $userEmail = $this->getUser()->getUserIdentifier();
            $user = $userRepository->findOneBy(['email' => $userEmail]);
            if ($user === null || ($user !== null && !$user->getIsVerified())) {
                $this->addFlash(
                    'warning',
                    "Votre adresse email n'a pas encore été confirmée. Mer
                    ci de cliquer sur le lien que vous avez reçu pour valider votre inscription."
                );
                return $this->redirectToRoute('home');
            }
        }
        return $this->render('profile/index.html.twig');
    }

    /**
     * @Route("/profile/edit", name="profile_edit")
     * @IsGranted("ROLE_USER")
     */
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $this->getUser();

        //Modification form user information
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
        }

        //Modification form password user
        $formpassword = $this->createForm(EditPasswordType::class, $user);
        $formpassword->handleRequest($request);

        if ($formpassword->isSubmitted() && $formpassword->isValid()) {
            $plaintextPassword = $formpassword['plainPassword']->getData();
            if (is_string($plaintextPassword) && $user instanceof User) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);
                $entityManager->flush();
                return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('profile/edit.html.twig', [
            'form' => $form,
            'formpassword' => $formpassword
        ]);
    }

    /**
     * @Route("/profile/delete", name="profile_delete")
     * @IsGranted("ROLE_USER")
     */
    public function delete(
        EntityManagerInterface $entityManager,
        Request $request,
        TokenStorageInterface $tokenStorage
    ): Response {
        $user = $this->getUser();
        $session = $request->getSession();
        if ($user != null) {
            $entityManager->remove($user);
            $entityManager->flush();
            $tokenStorage->setToken(null);
            $session->invalidate();
        }
        return $this->redirectToRoute('home');
    }
}
