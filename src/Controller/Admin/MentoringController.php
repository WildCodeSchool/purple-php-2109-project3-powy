<?php

namespace App\Controller\Admin;

use App\Entity\Mentoring;
use App\Repository\MentoringRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/mentoring/{id}", name="mentoring_delete")
     */
    public function delete(Mentoring $mentoring, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($mentoring);
        $entityManager->flush();

        return $this->redirectToRoute('admin_mentoring');
    }
}
