<?php

namespace App\Controller;

use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BusinessController extends AbstractController
{
    public function __construct(
        private readonly OfferRepository $offerRepository,
    ) {
    }

    #[Route('/business', name: 'app_business')]
    public function index(): Response
    {
        $offers = $this->offerRepository->findBy(
            ['status' => 'active'],
            ['id' => 'ASC']
        );

        usort($offers, function ($a, $b) {
            if ($a->getTitle() === 'Premium Business Escape') {
                return -1;
            }

            if ($b->getTitle() === 'Premium Business Escape') {
                return 1;
            }

            return 0;
        });

        $discountPercentage = 20;

        return $this->render('business.html.twig', [
            'offers' => $offers,
            'discount_percentage' => $discountPercentage,
        ]);
    }
}