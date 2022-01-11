<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditPasswordType;
use App\Form\EditProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile_index")
     * @IsGranted("ROLE_USER")
     */
    public function profile(): Response
    {
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
}
