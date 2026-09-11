<?php

namespace App\Controller;

use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FullDayController extends AbstractController
{
    public function __construct(
        private readonly OfferRepository $offerRepository,
    ) {
    }

    #[Route('/full-day', name: 'app_fullday')]
    public function index(): Response
    {
        $offers = $this->offerRepository->findBy(
            ['status' => 'active'],
            ['id' => 'ASC']
        );

        usort($offers, function ($a, $b) {
            if ($a->getTitle() === 'Full Day, Full Focus') {
                return -1;
            }

            if ($b->getTitle() === 'Full Day, Full Focus') {
                return 1;
            }

            return 0;
        });

        $discountPercentage = 25;

        return $this->render('fullday.html.twig', [
            'offers' => $offers,
            'discount_percentage' => $discountPercentage,
        ]);
    }
}