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
            ['id' => 'ASC'],
        );

        usort($offers, static function ($first, $second): int {
            return match (true) {
                $first->getTitle() === 'Full Day, Full Focus' => -1,
                $second->getTitle() === 'Full Day, Full Focus' => 1,
                default => 0,
            };
        });

        return $this->render('Fullday.html.twig', [
            'offers' => $offers,
            'discount_percentage' => 25,
        ]);
    }
}
