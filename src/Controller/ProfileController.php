<?php

namespace App\Controller;

use App\Entity\Mentoring;
use App\Entity\Topic;
use App\Entity\User;
use App\Form\EditPasswordType;
use App\Form\EditProfileType;
use App\Form\TopicType;
use App\Service\FileUploader;
use App\Service\MailerManager;
use App\Service\MatchManager;
use App\Service\MentoringManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProfileController extends AbstractController
{

    /**
     * @Route("/profile", name="profile_index")
     * @IsGranted("ROLE_USER")
     */
    public function profile(): Response
    {
        // Fetch User to get the property IsVerfied
        if ($this->getUser() instanceof User) {
            $user = $this->getUser();
            // if it's not verified yet, we redirect the user to home with a flash message
            if ($user == null || ($user !== null && !$user->getIsVerified())) {
                $this->addFlash(
                    'warning',
                    "Votre adresse email n'a pas encore été confirmée.
                     Merci de cliquer sur le lien que vous avez reçu pour valider votre inscription."
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
        UserPasswordHasherInterface $passwordHasher,
        FileUploader $fileUploader
    ): Response {
        $user = $this->getUser();

        if (!($user instanceof User)) {
            throw $this->createAccessDeniedException();
        }
        //Modification form user information & Upload Picture profil
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('picture')->getData();
            if ($pictureFile instanceof UploadedFile) {
                $pictureFileName = $fileUploader->upload($pictureFile);
                $user->setPicture($pictureFileName);
            }

            $entityManager->flush();
            $this->addFlash("success", "Les modifications ont bien été prises en compte.");
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
                $this->addFlash("success", "Les modifications ont bien été prises en compte.");
                return $this->redirectToRoute('profile_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('profile/edit.html.twig', [
            'form' => $form,
            'formpassword' => $formpassword,
        ]);
    }

    /**
     * @Route("/profile/edit/choices/{id}", name="edit_choices", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function editChoice(
        User $user,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        //fetch the user, the topic
        $topic = $this->checkTopic($user);

        //create the topic form
        $topicForm = $this->createForm(TopicType::class, $topic);

        //handle form request
        $topicForm->handleRequest($request);
        if ($topicForm->isSubmitted() && $topicForm->isValid()) {
            $entityManager->flush();
            $this->addFlash(
                "success",
                "Vos nouveaux choix ont bien été enregistrés. Une recherche de mentorat sur ces sujets sera relancée."
            );
            return $this->redirectToRoute("profile_index");
        }

        //if we didn't get any topics, throw an exception
        if (is_null($topic)) {
            throw $this->createNotFoundException("Topics not found for user.");
        }

        return $this->renderForm('profile/choices.html.twig', [
            'topicForm' => $topicForm
        ]);
    }

    /**
     * @Route("/profile/delete", name="profile_delete")
     * @IsGranted("ROLE_USER")
     */
    public function delete(
        EntityManagerInterface $entityManager,
        Request $request,
        TokenStorageInterface $tokenStorage,
        MailerManager $mailerManager,
        MentoringManager $mentoringManager
    ): Response {
        $user = $this->getUser();
        $session = $request->getSession();
        if ($user instanceof User) {
            // check if user is a mentor or a student and then end the mentoring
            if ($user->getMentor() != null && $user->getMentor()->getMentoring() != null) {
                $mentoring = $user->getMentor()->getMentoring();
                $mentoringManager->stopMentoring($mentoring);
            }
            if ($user->getStudent() != null && $user->getStudent()->getMentoring() != null) {
                $mentoring = $user->getStudent()->getMentoring();
                $mentoringManager->stopMentoring($mentoring);
            }
            $entityManager->remove($user);
            $entityManager->flush();
            $mailerManager->deleteAccount($user);
            $tokenStorage->setToken(null);
            $session->invalidate();
        }
        return $this->redirectToRoute('home');
    }

    //function to fetch topic depending if user is a mentor or a student
    private function checkTopic(User $user): ?Topic
    {
        $topic = null;
        if ($user->getMentor() === null && $user->getStudent() === null) {
            throw $this->createNotFoundException('User is neither a student or a mentor.');
        }
        if ($user->getMentor() !== null) {
            $topic = $user->getMentor()->getTopic();
        } elseif ($user->getStudent() !== null) {
            $topic = $user->getStudent()->getTopic();
        }
        return $topic;
    }
}
