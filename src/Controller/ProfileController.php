<?php

namespace App\Controller;

use App\Entity\Mentoring;
use App\Entity\Topic;
use App\Entity\User;
use App\Form\EditPasswordType;
use App\Form\EditProfileType;
use App\Form\TopicType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\MatchManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/profile", name="profile_index")
     * @IsGranted("ROLE_USER")
     */
    public function profile(): Response
    {
        // Fetch UserEmail to get the property IsVerfied
        if ($this->getUser() instanceof UserInterface) {
            $userEmail = $this->getUser()->getUserIdentifier();
            $user = $this->userRepository->findOneBy(['email' => $userEmail]);
            // if it's not verified yet, we redirect the user to home with a flash message
            if ($user === null || ($user !== null && !$user->getIsVerified())) {
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
            'formpassword' => $formpassword,
        ]);
    }

    /**
     * @Route("/profile/edit/choices/{id}", name="edit_choices", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function editChoice(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        MatchManager $matchManager
    ): Response {
        //fetch the user, the topic and the mentoring
        $user = $this->checkUser($id);
        $topic = $this->checkTopic($user);
        $mentoring = $this->checkMentoring($user);

        if ($mentoring  !== null) {
            $this->addFlash(
                "danger",
                "Le mentorat est en cours. À la fin de celui-ci, vous pourrez modifier vos choix."
            );
            return $this->redirectToRoute("profile_index");
        }
        //create the topic form
        $topicForm = $this->createForm(TopicType::class, $topic);
        //handle form request
        $topicForm->handleRequest($request);
        if ($topicForm->isSubmitted() && $topicForm->isValid()) {
            $entityManager->flush();
            $student = $user->getStudent();
            if ($student !== null && $student->getMentoring() === null) {
                $matchManager->matchByTopic($student);
            }
            $this->addFlash("success", "Les modifications ont bien été enregistrées.");
            return $this->redirectToRoute("profile_index");
        }
        //if we didn't get any topics, throw an exception
        if (is_null($topic)) {
            throw $this->createNotFoundException("Topics not found for user with the id $id.");
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

    //function to verify is User is null or not
    public function checkUser(int $id): User
    {
        $user = $this->userRepository->find($id);
        if (is_null($user)) {
            throw $this->createNotFoundException("No user found with id $id.");
        }
        return $user;
    }

    //function to fetch topic depending if user is a mentor or a student
    public function checkTopic(User $user): ?Topic
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

    //function to check if a mentoring is already active
    public function checkMentoring(User $user): ?Mentoring
    {
        $mentoring = null;
        if ($user->getMentor() === null && $user->getStudent() === null) {
            throw $this->createNotFoundException('User is neither a student or a mentor.');
        }
        if ($user->getMentor() !== null) {
            $mentoring = $user->getMentor()->getMentoring();
        } elseif ($user->getStudent() !== null) {
            $mentoring = $user->getStudent()->getMentoring();
        }
        return $mentoring;
    }
}
