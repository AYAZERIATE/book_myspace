<?php

namespace App\Controller;

use App\Entity\Offers;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OffersController extends AbstractController
{
    #[Route('/offers', name: 'app_offers')]
    public function index(OfferRepository $offerRepository): Response
    {
        $offers = $offerRepository->findBy(
            ['status' => 'active'],
            ['id' => 'ASC']
        );

        return $this->render('offers/index.html.twig', [
            'offers' => $offers,
        ]);
    }

    #[Route('/offers/{id}', name: 'app_offer_show', requirements: ['id' => '\\d+'])]
    public function show(Offers $offer): Response
    {
        $routes = [
            'Double Your Productivity' => 'app_productivity',
            'Team Together' => 'app_team',
            'Premium Business Escape' => 'app_business',
            'Private Workspace Experience' => 'app_workspace',
            'Full Day, Full Focus' => 'app_fullday',
            'Extend Your Focus' => 'app_focus',
        ];

        return $this->redirectToRoute($routes[$offer->getTitle()] ?? 'app_offers');
    }
}
