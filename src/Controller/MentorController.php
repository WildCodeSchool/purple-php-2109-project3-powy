<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentorController extends AbstractController
{
    /**
     * @Route("/mentor", name="mentor_index")
     */
    public function index(): Response
    {
        return $this->render('mentor/index.html.twig');
    }
}