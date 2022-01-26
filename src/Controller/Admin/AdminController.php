<?php

namespace App\Controller\Admin;

use App\Repository\MentoringRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/", name="app")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('admin/index.html.twig', [
            'user' => $user
        ]);
    }
}
