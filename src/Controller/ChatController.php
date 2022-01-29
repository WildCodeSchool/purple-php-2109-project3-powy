<?php

namespace App\Controller;

use App\Entity\Mentoring;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Service\MentoringManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    /**
     * @Route("/chat/{id}", name="chat", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function index(
        User $user,
        Request $request,
        EntityManagerInterface $entityManager,
        MentoringManager $mentoringManager
    ): Response {
        //fetch Mentoring
        $mentoring = $mentoringManager->fetchMentoring($user);
        // if user connected is not the user from the mentoring, redirect to his/her chat
        if ($this->getUser() instanceof User) {
            $idUserConnected = $this->getUser()->getId();
            if ($user->getId() !== $idUserConnected) {
                return $this->redirectToRoute('chat', ['id' => $idUserConnected]);
            }
        }
        //create message form
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        //handle form request
        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($user);
            if ($user !== null) {
                //set message to mentoring depending if user is a mentor or a student
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
        if (is_null($mentoring)) {
            //send a message to the user instead of 404 (if user used to have a chat and add the url in favorites)
            $this->addFlash('info', "Vous n'avez pas encore de mentorat.");
            return $this->redirectToRoute("profile_index");
        }

        return $this->render('chat/index.html.twig', [
            'mentoring' => $mentoring,
            'messageForm' => $form->createView(),
        ]);
    }
}
