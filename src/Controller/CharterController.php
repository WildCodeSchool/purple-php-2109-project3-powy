<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CharterController extends AbstractController
{
    /**
     * @Route("/charter", name="charter")
     */
    public function index(): Response
    {
        return $this->render('charter/index.html.twig');
    }
}
