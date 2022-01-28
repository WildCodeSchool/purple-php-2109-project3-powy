<?php

namespace App\Controller\Admin;

use App\Entity\Mentoring;
use App\Repository\MentoringRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MentoringManager;

/**
 * @Route("/admin", name="admin_")
 */

class MentoringController extends AbstractController
{

    /**
     * @Route("/mentoring", name="mentoring")
     */
    public function showMentoring(MentoringRepository $mentoringRepository): Response
    {
        $mentoring = $mentoringRepository->findAll();
        return $this->render('admin/mentoring/index.html.twig', [
            'mentorings' => $mentoring
        ]);
    }

    /**
     * @Route("/mentoring/desactivate/{id}", name="mentoring_desactivate")
     */
    public function desactivateMentoring(MentoringManager $mentoringManager, Mentoring $mentoring): Response
    {
        $mentoringManager->stopMentoring($mentoring);
        return $this->redirectToRoute('admin_mentoring');
    }
}
