<?php

namespace App\Controller;

use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FocusController extends AbstractController
{
    public function __construct(
        private readonly OfferRepository $offerRepository,
    ) {
    }

    #[Route('/focus', name: 'app_focus')]
    public function index(): Response
    {
        $offers = $this->offerRepository->findBy(
            ['status' => 'active'],
            ['id' => 'ASC']
        );

        usort($offers, function ($a, $b) {
            if ($a->getTitle() === 'Extend Your Focus') {
                return -1;
            }

            if ($b->getTitle() === 'Extend Your Focus') {
                return 1;
            }

            return 0;
        });

        $discountPercentage = 15;

        return $this->render('focus.html.twig', [
            'offers' => $offers,
            'discount_percentage' => $discountPercentage,
        ]);
    }
}