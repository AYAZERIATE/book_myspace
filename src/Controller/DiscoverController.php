<?php

namespace App\Controller;

use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DiscoverController extends AbstractController
{
    #[Route('/discover', name: 'app_discover')]
    public function index(ReviewRepository $reviewRepository): Response
    {
        return $this->render('discover/index.html.twig', [
            'controller_name' => 'DiscoverController',
            'reviews' => $reviewRepository->findBy(
                ['status' => 'approved'],
                ['createdAt' => 'DESC']
            ),
        ]);
    }

    #[Route('/story', name: 'app_story')]
    public function story(): Response
    {
        return $this->render('discover/story.html.twig');
    }
}
