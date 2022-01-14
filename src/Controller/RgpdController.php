<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RgpdController extends AbstractController
{
    /**
     * @Route("/rgpd", name="rgpd")
     */
    public function index(): Response
    {
        return $this->render('rgpd/index.html.twig');
    }
}
