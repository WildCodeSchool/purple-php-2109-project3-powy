<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\User;
use App\Service\MatchManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MentorController extends AbstractController
{
    /**
     * @Route("/mentor", name="mentor_index")
     * Render an information page before registration
     */
    public function index(): Response
    {
        return $this->render('mentor/index.html.twig');
    }
}
