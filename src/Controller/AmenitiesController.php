<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AmenityRepository;

final class AmenitiesController extends AbstractController
{
    public function __construct(
        private readonly AmenityRepository $amenityRepository,
    ) {
    }

    #[Route('/amenities', name: 'app_amenities')]
    public function index(): Response
    {
        $amenities = $this->amenityRepository->findBy(
            [],
            ['id' => 'ASC']
        );

        $groupedAmenities = [];

        foreach ($amenities as $amenity) {
            $category = $amenity->getCategory();

            $groupedAmenities[$category][] = $amenity;
        }

        return $this->render('amenities/index.html.twig', [
            'controller_name' => 'AmenitiesController',
            'amenities' => $amenities,
            'grouped_amenities' => $groupedAmenities,
        ]);
    }
}