<?php

namespace App\Controller;

use App\Entity\Mentoring;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/chat/{id}", name="chat", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function index(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        //check if user and mentoring are null
        $user = $this->checkUser($id);
        $mentoring = $this->checkMentoring($user);

        //load the form
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        //handle the request, etc.
        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($user);
            if ($user !== null) {
                if ($user->getMentor() !== null) {
                    $mentoring = $user->getMentor()->getMentoring();
                    $message->setMentoring($mentoring);
                } elseif ($user->getStudent() !== null) {
                    $mentoring = $user->getStudent()->getMentoring();
                    $message->setMentoring($mentoring);
                }
            }

            $entityManager->persist($message);
            $entityManager->flush();
            return $this->redirectToRoute('chat', ['id' => $user->getId()]);
        }
        //check if this user has a mentoring, if not redirect to profile
        if (!$mentoring) {
            //send a message to the user instead of 404 (if user used to have a chat and add the url in favorites)
            $this->addFlash('info', "Vous n'avez pas encore de mentorat.");
            return $this->redirectToRoute("profile_index");
        }

        return $this->render('chat/index.html.twig', [
            'mentoring' => $mentoring,
            'messageForm' => $form->createView(),
        ]);
    }

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

    public function checkUser(int $id): User
    {
        $user = $this->userRepository->find($id);
        if (is_null($user)) {
            throw $this->createNotFoundException("No user found with id $id.");
        }
        return $user;
    }
}
