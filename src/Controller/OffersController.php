<?php

namespace App\Controller;

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
}
