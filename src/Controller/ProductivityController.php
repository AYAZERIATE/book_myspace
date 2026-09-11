<?php

namespace App\Controller;

use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductivityController extends AbstractController
{
    #[Route('/productivity', name: 'app_productivity')]
    public function index(OfferRepository $offerRepository): Response
    {
        $offers = $offerRepository->findBy(
            ['status' => 'active'],
            ['id' => 'ASC']
        );

        usort($offers, function ($a, $b) {
            if ($a->getTitle() === 'Double Your Productivity') {
                return -1;
            }

            if ($b->getTitle() === 'Double Your Productivity') {
                return 1;
            }

            return 0;
        });

        $discountPercentage = 15
;

        return $this->render('productivity.html.twig', [
            'offers' => $offers,
            'discount_percentage' => $discountPercentage,
        ]);
    }
}